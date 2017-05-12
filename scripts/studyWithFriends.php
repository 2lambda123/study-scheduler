<?php		  			 
include_once '../scripts/DB.php';
$db = new DB();
session_start();

$q = $_REQUEST["q"]; //Facebook user friends
$q = json_decode($q); //$q is 2D array with $q[x][0] being UserID and $q[x][1] being UserName

$myId = $_SESSION["uuid"];
//var_dump($q);

foreach ($q as $friend){ //Loops through all friends and inserts their uuid into an array,$friend[0] is fb userID
  $tempId = $db -> select("SELECT ID FROM user WHERE FBAUTH='$friend[0]'");
  $friend[0] = $tempId[0]["ID"];
  echo "Foreach - " . $friend[0] . "<br>";
  $frIds[] = $friend;
}
//starts algorithm, returns encoded JSON 2D array with all found common study times, first obj in array has name and course key-values, rest have dtstart-dtend key-values
$finalStudyTimes = findStudyFriends($myId, $frIds, $db);  

//Simple and basic code to print the result, currently no spot in database where we save $finalStudyTimes
if($finalStudyTimes != null){
  $studyDecoded = json_decode($finalStudyTimes, true);
  echo "Found common studytimes! <br><br>";
  foreach ($studyDecoded as $study){
    echo "Name: " . $study[0]["name"] . " CourseID: " . $study[0]["course"] . "<br>";
	for ($i = 1; $i < count($study); $i++){
	  echo $study[$i]["DTSTART"] . " - ";
	  echo $study[$i]["DTEND"] . "<br>";
	}
	echo "<br>";
  }
  
}
else
  echo "No times were found";

function revertDate($date){ // converts DTSTART format to "2017-04-24"
  $year = substr($date, 0, 4);
  $month = substr($date,4, 2);
  $date = substr($date,6, 2);
  return $year . "-" . $month . "-" . $date;
}

function findStudyFriends($myId, $frIds, $db){ //takes myId is my uuid, frIds as 2D array with all friend uuids and their usernames, return 2Djson string
	$myCalTemp = $db -> select("SELECT CURRENT FROM calendar WHERE ID='$myId'"); //change id to variable
	$myCal = json_decode($myCalTemp[0]["CURRENT"], true);
	$myCoursesTemp = $db -> select("SELECT COURSES FROM data WHERE ID='$myId'"); //change id to variable
	$myCourses = json_decode($myCoursesTemp[0]["COURSES"], true);
	
	foreach ($frIds as $frId){ //Loop friends
	  $frCalTemp = $db -> select("SELECT CURRENT FROM calendar WHERE ID='$frId[0]'"); //friends calendar
	  /*if($frCalTemp[0]["CURRENT"] == ""){ //break in case friend doesn't have a calendar in database
	    echo "break frCalTemp " . $frId[0] . " <br>";
	    break;
		}*/
	  $frCal = json_decode($frCalTemp[0]["CURRENT"], true);
	  
	  $frCoursesTemp = $db -> select("SELECT COURSES FROM data WHERE ID='$frId[0]'"); //friends courses
	  /*if($frCoursesTemp[0]["COURSES"] == ""){//break in case friend doesn't have any courses in database
	    echo "break frCoursesTemp <br>";
		break;
		}*/
	  $frCourses = json_decode($frCoursesTemp[0]["COURSES"], true);
	  
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