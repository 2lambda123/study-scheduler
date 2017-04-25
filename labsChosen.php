<?php
$user = "labs";
include 'importCal.php';
$e = json_decode(downloadFile("MedLabbar.ics"));
$count = count($e);



$post_string = json_encode($_POST);
echo "<script type='text/javascript'>alert('$post_string');</script>";
//	Puts the DTSTART of the desired labs in an array
for ($i = 0; $i < $count; $i++) {
	if (!in_array($e[$i]->DTSTART, $_POST) || strstr($e[$i]->SUMMARY, 'Laboration') == false) {
		//echo $e[$i]->SUMMARY;
		unset($e[$i]);
		$e = array_values($e);
		$i--;
		$count = count($e);
	}
}

file_put_contents($user.='.txt',json_encode($e));

?>
