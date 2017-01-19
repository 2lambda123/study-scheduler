<?php
//include_once '../scripts/DB.php';
include '../scripts/distrChanges.php';
//include '../ajax/receiveChanges.php';

$db = new DB();
$result = $db -> select("SELECT CURRENT FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
$calendar = json_decode($result[0]['CURRENT']);

if(isset($_POST["st"]))
{
	$clickedEvent = json_decode($_POST["JSON2"], false);
	if($_POST["st"] == 0)
 //If the user doenst want to go to the lecture, the time is then relocated.
	{
	for($i = 0; $i < count($calendar); $i++){
		if($calendar[$i]->UID == $clickedEvent->UID){
			//if(preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $clickedEvent->SUMMARY)){
			$diffH = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval(substr($calendar[$i]->DTSTART, 9, 2));
			$diffM = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval(substr($calendar[$i]->DTSTART, 11, 2));
			$diffM = $diffH*60 + $diffM;
			$temp = $calendar[$i];
			array_splice($calendar, $i, 1);
			$temp->SUMMARY = "STUDY-SCHEDULER";
			$temp->LOCATION = "TESTING";
			$temp->DESCRIPTION = "TESTING";
			distr_leftover($diffM, $temp, json_encode($calendar));
			}
		}
	}
	//if the user doesnt want to go to the lecture and spend the time studying instead.
	else if ($_POST["st"] == 1) {
		for($i = 0; $i < count($calendar); $i++) {
			if($calendar[$i]->UID == $clickedEvent->UID) {
				print_r ($calendar[$i]);
				$calendar[$i]->SUMMARY = "STUDY-SCHEDULER";
				/*$calendar[$i]->AVAILABLE = true; WHY NO WORK? T_T*/
				$calendar[$i]->LOCATION = null;
				$calendar[$i]->DESCRIPTION = null;
				$db -> query("UPDATE calendar SET CURRENT=".$db->quote(json_encode($calendar)) ." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
			}
		}
	}
}
//$db -> query("UPDATE calendar SET CURRENT=".$db->quote(json_encode($calendar)) ." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");

function generateUid(){
  return uniqid("67209119184"); // IP + current string to time
}
/*else
{
	$freeStart = $event -> DTSTART;
	$freeEnd = $event -> DTEND;

	$arr = array('DTSTART' => $freeStart, 'DTEND' => $freeEnd);
	echo json_encode($arr);
}*/

?>
