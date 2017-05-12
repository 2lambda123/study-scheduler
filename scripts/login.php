<?php
if(isset($_POST['logout'])){
	session_destroy();
	echo "destroying session";
}
else if(isset($_POST['username']) && isset($_POST['password'])) {
	session_start();
	include 'DB.php';
	$db = new DB();
	$user = $db->quote($_POST['username']);
	$password = hash('sha256',$_POST['password']);
	$query = "SELECT * FROM user WHERE USERNAME=$user";

	$results = $db->select($query);
	//print_r($results[0]);
	if($results[0]['PASSWORD'] == $password){
		echo "valid!";
		$_SESSION['uuid'] = $results[0]['ID'];
		$_SESSION['username'] = $results[0]['USERNAME'];
		echo "session dump: ";
		var_dump($_SESSION);
	}
	else
		echo "invalid!";
}

else echo "unaccepted input";

include_once "importCal.php";
//var_dump(downloadFile("C:\Users\wilhe\Downloads\personal(2).ics"));
?>