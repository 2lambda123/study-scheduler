<?php
if (session_id() == "") session_start();
include_once '../scripts/DB.php';
include_once '../scripts/importCal.php';
$db = new DB();
if(session_id() == "") session_start();
//If sleepfrom exists, we have a form sent from personal routines, if coursecode exists, we have a form sent from courses
if (isset($_POST["sleepfrom"])) { //Routines
	if (!isset($_POST['sleepto']) || $_POST['sleepto'] == "") {
		echo 'You need to add sleepto.';
		include '../ajax/showPersonal.php';
		die();
	}
	if (!isset($_POST['traveltime']) || $_POST['traveltime'] == "") {
		echo 'You need to add traveltime. (It can be 0)';
		include '../ajax/showPersonal.php';
		die();
	}
	if (!isset($_POST['studylength']) || $_POST['studylength'] == "") {
		echo 'You need to add studylength.';
		include '../ajax/showPersonal.php';
		die();
	}
	if (!isset($_POST['breaktime']) || $_POST['breaktime'] == "") {
		echo 'You need to add break time. (It can be 0)';
		include '../ajax/showPersonal.php';
		die();
	}
	//Update database to match new routines
	if(isset($_SESSION['uuid'])){
		if($db -> query("UPDATE data SET ROUTINES=".$db->quote(json_encode($_POST))." WHERE ID='".$_SESSION['uuid']."'")){
		  if(isset($_SESSION['tutorial']) && $_SESSION['tutorial'] == 0){
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
			header("Location; " . $wholeURL . "site/calExpImp.php");
		  }
		}
	}
	include '../ajax/showPersonal.php';

} else if (isset($_POST["coursecode"])) { //Courses
	if (!isset($_POST['coursecode']) || $_POST['coursecode'] == "") {
		echo "You need to add a course code";
		include '../ajax/showCourses.php';
		die();
	}
	if (isset($_POST['exam'])) {
		if (!isset($_POST['hp_exam']) || $_POST['hp_exam'] == "") {
			echo "You need to add hp for the exam";
			include '../ajax/showCourses.php';
			die();
		}
	}
	if (!isset($_POST['coursestart']) || $_POST['coursestart'] == "") {
		echo "You need to add a start of course";
		include '../ajax/showCourses.php';
		die();
	}
	if (!isset($_POST['courseend']) || $_POST['courseend'] == "") {
		echo "You need to add an end of course";
		include '../ajax/showCourses.php';
		die();
	}
	if (isset($_POST['lab'])) {
		if (!isset($_POST['hp_lab']) || $_POST['hp_lab'] == "") {
			echo "You need to add hp for the lab";
			include '../ajax/showCourses.php';
			die();
		}
		if (!isset($_POST['numberoflabs']) || $_POST['numberoflabs'] == "") {
			echo "You need to add numbers of labs";
			include '../ajax/showCourses.php';
			die();
		}
	}
	$x = 1;
	while (true) {
		if (!isset($_POST['coursework'.$x])) {
			break;
		}
		if (!isset($_POST['startdate'.$x]) || $_POST['startdate'.$x] == "") {
			echo "You need to add start date for the course assignment";
			include '../ajax/showCourses.php';
			die();
		}
		if (!isset($_POST['enddate'.$x]) || $_POST['enddate'.$x] == "") {
			echo "You need to add end date for the course assignment";
			include '../ajax/showCourses.php';
			die();		
		}
		if (!isset($_POST['hp_work'.$x]) || $_POST['hp_work'.$x] == "") {
			echo "You need to add hp for the course assignment";
			include '../ajax/showCourses.php';
			die();
		}
		$x++;
	}
	//Get courses from database since we have to add courses, not replace existing ones
	$result = null;
	if(isset($_SESSION['uuid'])){
		$result = $db -> select("SELECT COURSES FROM data WHERE ID='".$_SESSION['uuid']."'");
	}

	$r = (isset($result[0]['COURSES'])) ? json_decode($result[0]['COURSES'], true) : null;
	$p = array();

	//If new coursecode has same name as an existing coursecode, die and echo error message
	if (is_array($r)) {
		foreach ($r as $c) {
			if ($_POST['coursecode'] == $c['coursecode']) {
				echo "You can't add the same course twice.";
				include '../ajax/showCourses.php';
				die();
			}
		}
	}
	//Checks if $r has actual values
	if ($r !== "") {
		//Checks if $r is an array
		if (is_array($r)) {
			//Add entire post as new object into existing array, then copy that array to $p
			array_push($r, (object)$_POST);
			$p = $r;
		} else {
			array_push($p, (object)$_POST);
		}
	} else {
		//Add $r into new array and $post to new array
		//array_push($p, (object)$r);
		array_push($p, (object)$_POST);
	}
	//Update database to match new courses
	if(isset($_SESSION['uuid'])){
		if($db -> query("UPDATE data SET COURSES=".$db->quote(json_encode($p))." WHERE ID='".$_SESSION['uuid']."'")){
		  if(isset($_SESSION['tutorial']) && $_SESSION['tutorial'] == 2){
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
			header("Location; " . $wholeURL . "site/habits.php");
		  }
		}
	}
	include '../ajax/showCourses.php';
} else if (isset($_POST['repetition'])) {
	//Add chosen days from form in one array
	$wD = array();
		if (isset($_POST['Monday'])) {
			array_push($wD, 'Monday');
		}
		if (isset($_POST['Tuesday'])) {
			array_push($wD, 'Tuesday');
		}
		if (isset($_POST['Wednesday'])) {
			array_push($wD, 'Wednesday');
		}
		if (isset($_POST['Thursday'])) {
			array_push($wD, 'Thursday');
		}
		if (isset($_POST['Friday'])) {
			array_push($wD, 'Friday');
		}
		if (isset($_POST['Saturday'])) {
			array_push($wD, 'Saturday');
		}
		if (isset($_POST['Sunday'])) {
			array_push($wD, 'Sunday');
		}

	if (!isset($_POST['name']) || $_POST['name'] == "") {
		echo "You need to add a name for the habit";
		include '../ajax/showHabits.php';
		die();
	}
	if (!isset($_POST['dtstart']) || $_POST['dtstart'] == "") {
		echo "You need to add a start time for the habit";
		include '../ajax/showHabits.php';
		die();
	}
	if (!isset($_POST['dtend']) || $_POST['dtend'] == "") {
		echo "You need to add a time end for the habit";
		include '../ajax/showHabits.php';
		die();
	}
	if (!isset($_POST['name']) || $_POST['name'] == "") {
		echo "You need to add a name for the habit";
		include '../ajax/showHabits.php';
		die();
	}
	if ($_POST['repetition'] == "Week(s)") {
		if (count($wD) == 0) {
			echo "You need to choose atleast one day for the habit to occur.";
			include '../ajax/showHabits.php';
			die();
		}
	}
	if (!isset($_POST['duration'])|| $_POST['duration'] == "") {
		echo "You need to add a duration for the habit";
		include '../ajax/showHabits.php';
		die();
	}
	//Event with standard values

	$h = $_POST;
	$events;
	$x = $_POST['duration'];
	$db = new DB();

	//Get existing habits, to not overwrite existing ones
	if(isset($_SESSION['uuid'])){
		$result = $db -> select("SELECT HABITS FROM data WHERE ID='".$_SESSION['uuid']."'");
	}

	$r = json_decode($result[0]['HABITS'], true);
	$p = array();

	//Check so we cant add habits with the same name
	if (is_array($r)) {
		foreach ($r as $c) {
			if ($_POST['name'] == $c['name']) {
				echo "You can't add the same habit twice.";
				include '../ajax/showHabits.php';
				die();
			}
		}
	}


	if ($r !== "") {
		if (is_array($r)) { //Push new habit to existing habits
			array_push($r, (object)$_POST);
			$p = $r;
		} else {
			array_push($p, (object)$_POST); //Push new habit, no new existing habits
		}
	}
	//Update database with updated habits
	if(isset($_SESSION['uuid'])){
		if($db -> query("UPDATE data SET HABITS=".$db->quote(json_encode($p))." WHERE ID='".$_SESSION['uuid']."'")){
		  if(isset($_SESSION['tutorial']) && $_SESSION['tutorial'] == 3){
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
			header("Location; " . $wholeURL . "site/settings.php");
		  }
		}
	}

	//If repetition is daily, create new event for this day and x (reps) days forward
	if ($_POST['repetition'] == "Day(s)") {
		$d = date('Ymd');
		for ($i = 0; $i < $x; $i++) {
			$events[] = new event();
			$events[$i]->SUMMARY = $_POST['name'];
			$events[$i]->DESCRIPTION = $_POST['travel'];
			$events[$i]->LOCATION = $_POST['location'];
			$events[$i]->DTSTART = $d . "T" . str_replace(":", "", $_POST['dtstart']) . "Z";
			$events[$i]->DTEND = $d . "T" . str_replace(":", "", $_POST['dtend']) . "Z";
			$events[$i]->UID = $d . $_POST['dtstart'];
			$events[$i]->AVAILABLE = FALSE;
			$d = date('Ymd', strtotime($d . "+1 day"));
		}
	} else if ($_POST['repetition'] == "Week(s)") { //If repetition is weekly, create new events on the days chosen for x (reps) weeks
		$rep = (count($wD))*$x;
		$d = date('Ymd');
		for ($i = 0; $i < $rep;) {
			if (in_array(date('l', strtotime($d)), $wD)) {
				$events[] = new event();
				$events[$i]->SUMMARY = $_POST['name'];
				$events[$i]->DESCRIPTION = $_POST['travel'];
				$events[$i]->LOCATION = $_POST['location'];
				$events[$i]->DTSTART = $d . "T" . str_replace(":", "", $_POST['dtstart']) . "Z";
				$events[$i]->DTEND = $d . "T" . str_replace(":", "", $_POST['dtend']) . "Z";
				$events[$i]->UID = $d . $_POST['dtstart'];
				$events[$i]->AVAILABLE = FALSE;
				$i++;
			}
			$d = date('Ymd', strtotime($d . "+1 day"));
		}
	}

	//Get habit events from calendar
	$result = null;
	if(isset($_SESSION['uuid'])){
		$result = $db -> select("SELECT HABITS FROM calendar WHERE ID='".$_SESSION['uuid']."'");
	}

	$r = (isset($result[0]['HABITS'])) ? json_decode($result[0]['HABITS'], true) : null;
	$p = array();
	include_once '../algorithm/modify.php';
	//Add events to existing habit events
	if(!empty($r)){
			$r = json_encode($r);
			foreach($events as $event){
				$r = modify($r, json_encode($event));
			}
			$p = json_decode($r);
	}
	else{
		$p = $events;
	}
	//Update database with new events
	if(isset($_SESSION['uuid'])){
		$db -> query("UPDATE calendar SET HABITS=".$db->quote(json_encode($p))." WHERE ID='".$_SESSION['uuid']."'");
	}


	//Get current calendar
	$result = null;
	if(isset($_SESSION['uuid'])){
		$result = $db -> select("SELECT CURRENT FROM calendar WHERE ID='".$_SESSION['uuid']."'");
	}

	$r = (isset($result[0]['CURRENT'])) ? json_decode($result[0]['CURRENT'], true) : null;
	$p = array();

	//Add new habit events into current calendar
	if(!empty($r)){
			$r = json_encode($r);
			foreach($events as $event){
				$r = modify($r, json_encode($event));
			}
			$p = json_decode($r);
	}
	else{
		$p = $events;
	}
	//Update database with new events
	$db -> query("UPDATE calendar SET CURRENT=".$db->quote(json_encode($p))." WHERE ID='$_SESSION[uuid]'");
	//Echo's table of habits, since changes have been made
	include '../ajax/showHabits.php';

} else { //Not sent from personal routines nor courses
	die ('No correct form sent');
}
?>
