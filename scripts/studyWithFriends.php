<?php		  			 
include_once '../scripts/DB.php';
$db = new DB();
session_start();

$q = $_REQUEST["q"]; //Facebook user friends
$q = json_decode($q); //$q is 2D array with $q[x][0] being UserID and $q[x][1] being UserName

$myId = $_SESSION["uuid"];

foreach ($q as $friend){ //Loops through all friends and inserts their uuid into an array,$friend[0] is fb userID
  if ($db -> select("SELECT ID FROM user WHERE FBAUTH='$friend[0]'") != null){ //make sure there is actually an user in the database with that fbauth
    $tempId = $db -> select("SELECT ID FROM user WHERE FBAUTH='$friend[0]'");
    if(isset($tempId[0]["ID"]) && $tempId[0]["ID"] != ""){ //to make sure the friend has an account
      $friend[0] = $tempId[0]["ID"];
      $frIds[] = $friend;
    }
  }
}
//starts algorithm, returns encoded JSON 2D array with all found common study times, first obj in array has name and course key-values, rest have dtstart-dtend key-values
if(isset($frIds))
  $finalStudyTimes = findStudyFriends($myId, $frIds, $db);
else  
  $finalStudyTimes = null;
//Simple and basic code to print the result, currently no spot in database where we save $finalStudyTimes
if($finalStudyTimes != null){
  $studyDecoded = json_decode($finalStudyTimes, true);
  echo "Found common study times! <br><br>";
  foreach ($studyDecoded as $study){
    $today = date("Y-m-d");
	$i = 1;
	while($today > revertDate($study[$i]["DTSTART"]) && $i < count($study)){
	  $i++;
	  }
	if($i < count($study)){  
      echo "Study with " . $study[0]["name"] . " - Course: " . $study[0]["course"] . "<br>";
	  for ($i; $i < count($study); $i++){
	    echo convertPrintTime($study[$i]["DTSTART"]) . " - ";
	    echo convertPrintTime($study[$i]["DTEND"]) . "<br>";
	  }
	  echo "<br>";
	}
  } 
}
else
  echo "No times were found";

function convertPrintTime($date){ //takes a DTFORMAT date and turns the string into a printable version, eg. 2017-05-12 10:00 
//input looks like this 20170509T131000Z
  $year = substr($date, 0, 4);
  $month = substr($date, 4, 2);
  $day = substr($date, 6, 2);
  $hour = substr($date, 9, 2);
  $min = substr($date, 11, 2);
  return $year . "-" . $month . "-" . $day . " " . $hour . ":" . $min;
}
function revertDate($date){ // converts DTSTART format to "2017-04-24"
  $year = substr($date, 0, 4);
  $month = substr($date,4, 2);
  $date = substr($date,6, 2);
  return $year . "-" . $month . "-" . $date;
}

function findStudyFriends($myId, $frIds, $db){ //takes myId is my uuid, frIds as 2D array with all friend uuids and their usernames, return 2Djson string
	$myCalTemp = $db -> select("SELECT CURRENT FROM calendar WHERE ID='$myId'"); 
	$myCal = json_decode($myCalTemp[0]["CURRENT"], true); //My calendar
	$myCoursesTemp = $db -> select("SELECT COURSES FROM data WHERE ID='$myId'");
	$myCourses = json_decode($myCoursesTemp[0]["COURSES"], true); //My courses
	
	if(isset($myCal) && isset($myCourses)){
	  foreach ($frIds as $frId){ //Loop friends
	    $frCalTemp = $db -> select("SELECT CURRENT FROM calendar WHERE ID='$frId[0]'");
	    $frCal = json_decode($frCalTemp[0]["CURRENT"], true); //friends calendar
	  
	    $frCoursesTemp = $db -> select("SELECT COURSES FROM data WHERE ID='$frId[0]'");
	    $frCourses = json_decode($frCoursesTemp[0]["COURSES"], true); //friends courses
	   
	    if(isset($frCourses) && isset($frCal)){
	      foreach ($myCourses as $myCourse){ //Loop my courses
			foreach ($frCourses as $frCourse){ //Loop friends array - Behöver skapa en json array av json array av json objekt
			  if ($myCourse["coursecode"] == $frCourse["coursecode"]){ //check if we study the same course
				$courseStudyTimes = compareStudyTimes($myCal, $frCal, $myCourse); //returns 2D array with DTSTAT-DTEND
				if ($courseStudyTimes != null){
				  $userInfo = array("name" => $frId[1], "course" => $myCourse["coursecode"]); //2d array,name of friend and the coursecode 
				  array_unshift($courseStudyTimes, $userInfo);
				  $sameStudyTimes[] = $courseStudyTimes;
				  }
				break; //no reason to continue foreach when a course has been found already
			  }
			}
		  }
	    }
	  }
	}
	
	if(isset($sameStudyTimes)) //if any common study times were found
	  return json_encode($sameStudyTimes);
	  
	return null;
}
	
function compareStudyTimes($myCal, $frCal, $course){ //takes mycalendar, friendcalendar, mycourse and returns an array with json objects containing DTSTART-DTEND
  $x = 0; //used to keep track of position in $myCal
  $y = 0; //used to keep track of position in $frCal
  while(isset($myCal[$x]) && revertDate($myCal[$x]["DTSTART"]) < $course["coursestart"]){//increments $x until start of course
	$x++;
	}
  while(isset($frCal[$y]) && revertDate($frCal[$y]["DTSTART"]) < $course["coursestart"]){ //increments $y until start of course
	$y++;
	}
	
  while(isset($myCal[$x]) && revertDate($myCal[$x]["DTSTART"]) <= $course["courseend"]){ //loop as long as myCal event is before courseend
	if (strpos($myCal[$x]["SUMMARY"],"STUDY-SCHEDULER") !== false){ //found an study event
	  while(isset($frCal[$y]) && $frCal[$y]["DTEND"] <= $myCal[$x]["DTSTART"]) //loop as long as frCal endtime is before myCal starttime 
	    $y++;

	  while(isset($frCal[$y]) && $frCal[$y]["DTSTART"] < $myCal[$x]["DTEND"]){ //loop as long as frCal event starts before myCal ends
		if($frCal[$y]["SUMMARY"] == $myCal[$x]["SUMMARY"]){ //we have found a common study time
		  $startTime = "" . ($myCal[$x]["DTSTART"] > $frCal[$y]["DTSTART"] ? $myCal[$x]["DTSTART"] : $frCal[$y]["DTSTART"]) . "";//finds largest DTSTART
		  $endTime = "" . ($myCal[$x]["DTEND"] < $frCal[$y]["DTEND"] ? $myCal[$x]["DTEND"] : $frCal[$y]["DTEND"]) . "";//finds smallest DTEND
		  $newTime = array("DTSTART" => $startTime, "DTEND" => $endTime);
		  $studyTimes[] = $newTime;
	    }
		if($myCal[$x]["DTEND"] < $frCal[$y]["DTEND"]){ //if frCal[y] is ends after myCal[x] - dont increment y as it might overlap with myCal[x+1]
	      break;
		  }
		$y++;
	  }
    }			  
    $x++;
  }
  if(isset($studyTimes))
	return $studyTimes;
  else
	return null;
}
?>