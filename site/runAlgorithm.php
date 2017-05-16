<?php
	if (session_id() == "") session_start();
  include_once '../scripts/DB.php';
  include_once '../algorithm/find.php';
  include_once '../algorithm/analyze.php';
  include_once '../algorithm/distribute.php';
  include_once '../scripts/importCal.php';

  $db = new DB();

  $calendarLink = $db -> select("SELECT STUDY FROM calendar WHERE ID ='$_SESSION[uuid]'");
  $calendar = $calendarLink[0]["STUDY"];
// add 2 hours to convert from UTC to GMT+1(swedish time)
	$decoded_calendar = json_decode($calendar, true);
	for($i = 0; $i < count($decoded_calendar); $i++){
		$dtstart = $decoded_calendar[$i]["DTSTART"];
		$hours = (int) substr($dtstart, 9, 2);
		$hours += 2;
		if ($hours < 10){
			$hours = "0" . $hours;
		}
		$decoded_calendar[$i]["DTSTART"] = substr_replace($dtstart, $hours, 9, 2);

		$dtend = $decoded_calendar[$i]["DTEND"];
		$hours = (int) substr($dtend, 9, 2);
		$hours += 2;
		if ($hours < 10){
			$hours = "0" . $hours;
		}
		$decoded_calendar[$i]["DTEND"] = substr_replace($dtstart, $hours, 9, 2);
	}
	$calendar = json_encode($decoded_calendar);

  $calendarPersonal = $db -> select("SELECT PERSONAL FROM calendar WHERE ID ='$_SESSION[uuid]'");
  $calendarPersonal = $calendarPersonal[0]["PERSONAL"];

  $calendarHabits = $db -> select("SELECT HABITS FROM calendar WHERE ID ='$_SESSION[uuid]'");
  $calendarHabits = $calendarHabits[0]["HABITS"];

  if(isset($calendarPersonal) && $calendarPersonal !== "") {
		$calendar = modify($calendar, $calendarPersonal);
  }

  if(isset($calendarHabits) && $calendarHabits !== "") {
		$calendar = modify($calendar, $calendarHabits);
    }

  $calendarRoutines = $db -> select("SELECT ROUTINES FROM data WHERE ID ='$_SESSION[uuid]'");
  $calendarRoutines = $calendarRoutines[0]["ROUTINES"];

  $calendarCourses = $db -> select("SELECT COURSES FROM data WHERE ID ='$_SESSION[uuid]'");
  $calendarCourses = $calendarCourses[0]["COURSES"];

  $calendar = free_time_with_events($calendar);
  $calendar = analyze($calendar, $calendarRoutines);
  $calendar = distribute($calendar, $calendarCourses, $calendarRoutines);
  if($db -> query("UPDATE calendar SET CURRENT=".$db->quote($calendar) ." WHERE ID='$_SESSION[uuid]'")){
    if(isset($_SESSION['tutorial']) && $_SESSION['tutorial'] == 4){
	  $_SESSION['tutorial'] += 1;
	  $uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	  $uri = explode('/', $uri);
	  $wholeURL = "http://";
	  foreach ($uri as $u) {
	    if ($u == "scripts" || $u == "site" || $u == "ajax" || $u == "algorithm") {
		  break;
		}
	    $wholeURL .= $u . "/";
		}
		echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$wholeURL.'site/calendar.php">';
    }
  }

	  echo "<h3>Algorithm is finished</h3>";

?>
