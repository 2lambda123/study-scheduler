<?php
function createCal ($UUID, $KTHlink) {
	include_once '../scripts/DB.php';
	include_once '../scripts/importCal.php';
	$db = new DB();
	$CAL = downloadFile($KTHlink);
	$CAL = $db->quote($CAL);
	$sql = "UPDATE calendar SET STUDY = ".$CAL." WHERE ID = '".$UUID."'";
	if ($temp = $db -> query($sql)) {
		echo "<br> success: ";
		var_dump($temp);
	}
	else { 
		echo "<br> not success: ";
		var_dump($temp);
		echo ".";
	}
}

//Check if username and password has been entered, if it has, call createUser function
if (isset($_POST['uuid'],$_POST['KTHlink'])) {
	createCal($_POST['uuid'], $_POST['KTHlink']);
} else {
	echo "<br>You have not filled in all fields.";
}
?>
