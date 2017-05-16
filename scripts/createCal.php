<?php
function createCal ($UUID, $KTHlink) {
	include_once '../scripts/DB.php';
	include_once '../scripts/importCal.php';
	include_once '../scripts/popupEvent.php';
	include_once '../ajax/popupLabs.php';
	if (session_id() == "") session_start();
	$db = new DB();
	$CAL = downloadFile($KTHlink);
	$CAL1 = $db->quote($CAL);
	$sql = "UPDATE calendar SET STUDY = ".$CAL1 . " WHERE ID = '".$UUID."'";
	if($db -> query($sql)){
		echo $_SESSION['tutorial'];
	  if(isset($_SESSION['tutorial']) && $_SESSION['tutorial'] == 1){
		$_SESSION['tutorial'] += 1;
	  }
	}
	popupGen(popupLabs($CAL));
	
	/*
	if ($temp = $db -> query($sql)) {
		echo "<br> success: ";
		var_dump($temp);
	}
	else { 
		echo "<br> not success: ";
		var_dump($temp);
		echo ".";
	}*/
}

//Check if username and password has been entered, if it has, call createUser function
if (isset($_POST['uuid'],$_POST['KTHlink'])) {
	createCal($_POST['uuid'], $_POST['KTHlink']);
} else {
	echo "<br>You have not filled in all fields.";
}
?>
