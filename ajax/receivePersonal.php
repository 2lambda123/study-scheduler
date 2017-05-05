<?php 
include_once '../scripts/DB.php';
$db = new DB();

//If sleepfrom exists, we have a form sent from personal routines, if coursecode exists, we have a form sent from courses
if (isset($_POST["sleepfrom"])) { //Routines
	//Update database to match new routines
	$db -> query("UPDATE data SET ROUTINES=".$db->quote(json_encode($_POST))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
} else if (isset($_POST["coursecode"])) { //Courses
	//Get courses from database since we have to add courses, not replace existing ones
	$result = $db -> select("SELECT COURSES FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		
	$r = json_decode($result[0]['COURSES'], true);
	$p = array();
	
	//If new coursecode has same name as an existing coursecode, die and echo error message
	if (is_array($r)) {
		foreach ($r as $c) {
			if ($_POST['coursecode'] == $c['coursecode']) {
				die('You cant add the same course twice.');
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
			//Add $r into new array and $post to new array
			array_push($p, (object)$r);
			array_push($p, (object)$_POST);
		}
	}
	//Update database to match new courses
	$db -> query("UPDATE data SET COURSES=".$db->quote(json_encode($p))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	include '../ajax/showCourses.php';
} else if (isset($_POST['repetition'])) {
	//Event with standard values
	class event {
		public $SUMMARY = NULL;
		public $DTSTART = NULL;
		public $DTEND = NULL;
		public $UID = NULL;
		public $DESCRIPTION = NULL;
		public $LOCATION = NULL;
		public $AVAILABLE = NULL;
	}


	$h = $_POST;
	$events;
	$x = $h['duration'];
	$db = new DB();

	//Get existing habits, to not overwrite existing ones
	$result = $db -> select("SELECT HABITS FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
			
	$r = json_decode($result[0]['HABITS'], true);
	$p = array();

	//Check so we cant add habits with the same name	
	if (is_array($r)) {
		foreach ($r as $c) {
			if ($_POST['name'] == $c['name']) {
				die('You cant add the same habit twice.');
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
	$db -> query("UPDATE data SET HABITS=".$db->quote(json_encode($p))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");

	//Add chosen days from form in one array
	$wD[] = array();
	if (isset($h['Monday'])) {
			array_push($wD, 'Monday');
		}
		if (isset($h['Tuesday'])) {
			array_push($wD, 'Tuesday');
		}
		if (isset($h['Wednesday'])) {
			array_push($wD, 'Wednesday');
		}
		if (isset($h['Thursday'])) {
			array_push($wD, 'Thursday');
		}
		if (isset($h['Friday'])) {
			array_push($wD, 'Friday');
		}
		if (isset($h['Saturday'])) {
			array_push($wD, 'Saturday');
		}
		if (isset($h['Sunday'])) {
			array_push($wD, 'Sunday');
	}

	//If repetition is daily, create new event for this day and x (reps) days forward
	if ($h['repetition'] == "Daily") {
		$d = date('Ymd');
		for ($i = 0; $i < $x; $i++) {
			$events[] = new event();
			$events[$i]->SUMMARY = $h['name'];
			$events[$i]->DESCRIPTION = $h['travel'];
			$events[$i]->LOCATION = $h['location'];
			$events[$i]->DTSTART = $d . "T" . str_replace(":", "", $h['dtstart']) . "Z";
			$events[$i]->DTEND = $d . "T" . str_replace(":", "", $h['dtend']) . "Z";
			$events[$i]->UID = $d . $h['dtstart'];
			$events[$i]->AVAILABLE = FALSE;
			$d = date('Ymd', strtotime($d . "+1 day"));
		}
	} else if ($h['repetition'] == "Weekly") { //If repetition is weekly, create new events on the days chosen for x (reps) weeks
		$rep = (count($wD)-1)*$x;
		$d = date('Ymd');
		for ($i = 0; $i < $rep;) {
			if (in_array(date('l', strtotime($d)), $wD)) {
				$events[] = new event();
				$events[$i]->SUMMARY = $h['name'];
				$events[$i]->DESCRIPTION = $h['travel'];
				$events[$i]->LOCATION = $h['location'];
				$events[$i]->DTSTART = $d . "T" . str_replace(":", "", $h['dtstart']) . "Z";
				$events[$i]->DTEND = $d . "T" . str_replace(":", "", $h['dtend']) . "Z";
				$events[$i]->UID = $d . $h['dtstart'];
				$events[$i]->AVAILABLE = FALSE;
				$i++;
			}
			$d = date('Ymd', strtotime($d . "+1 day"));
		}
	}

	//Get habit events from calendar
	$result = $db -> select("SELECT HABITS FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
			
	$r = json_decode($result[0]['HABITS'], true);
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
	$db -> query("UPDATE calendar SET HABITS=".$db->quote(json_encode($p))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");


	//Get current calendar
	$result = $db -> select("SELECT CURRENT FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
			
	$r = json_decode($result[0]['CURRENT'], true);
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
	$db -> query("UPDATE calendar SET CURRENT=".$db->quote(json_encode($p))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	//Echo's table of habits, since changes have been made
	include '../ajax/showHabits.php';

} else { //Not sent from personal routines nor courses 
	die ('No correct form sent');
}
?>
