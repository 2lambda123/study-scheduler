<?php 
include_once 'DB.php';
$db = new DB();

if (isset($_POST["sleepfrom"])) { //Routines
	$db -> query("UPDATE data SET ROUTINES=".$db->quote(json_encode($_POST))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
} else if (isset($_POST["coursecode"])) { //Course
	$result = $db -> select("SELECT COURSES FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		
	$r = json_decode($result[0]['COURSES'], true);
	$p = array();
	
	if (is_array($r)) {
		foreach ($r as $c) {
			if ($_POST['coursecode'] == $c['coursecode']) {
				die('You cant add the same course twice.');
			}
		}
	}
	
	if ($r !== "") {
		if (is_array($r)) {
			array_push($r, (object)$_POST);
			$p = $r;
		} else {
			array_push($p, (object)$r);
			array_push($p, (object)$_POST);
		}
	}

	$db -> query("UPDATE data SET COURSES=".$db->quote(json_encode($p))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	include 'showCourses.php';
} else {
	die ('No correct form sent');
}
?>
