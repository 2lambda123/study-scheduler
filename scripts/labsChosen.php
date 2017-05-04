<?php
include 'DB.php';

$db = new DB();
$result = $db -> select("SELECT CURRENT FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
$e = json_decode($result[0]['STUDY']);
$count = count($e);

//	Puts the DTSTART of the desired labs in an array
for ($i = 0; $i < $count; $i++) {
	if (!in_array($e[$i]->DTSTART, $_POST['lab']) && strstr($e[$i]->SUMMARY, 'Laboration')) {
		unset($e[$i]);
		$e = array_values($e);
		$i--;
		$count = count($e);
	}
}

$db -> query("UPDATE calendar SET CURRENT='" . json_encode($e) . "' WHERE ID = 'c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
?>
