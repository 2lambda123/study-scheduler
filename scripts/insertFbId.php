<?php
include_once '../scripts/DB.php';
$db = new DB();
if (session_id() == "") session_start();

$info = json_decode($_REQUEST["q"], true);//ajax request data
$fbId = $info["id"]; //facebook uuid
$fbName = $info["name"]; //facebook user name

if(isset($_SESSION['uuid']) && $_SESSION['uuid'] != ""){ //check if $SESSION is true -> user already logged in and want to continue with fb
  $result = $db -> select("SELECT FBAUTH FROM user WHERE ID='$_SESSION[uuid]'");
  if(isset($result[0]['FBAUTH']) && $fbId == $result[0]['FBAUTH']){ //FBAUTH already in database, dont do anything
	//echo "done";
  }
  else{//no value in database, insert $fbId OR User has already authenticated with another facebook account -> change to new facebook account
    $fbId = $db -> quote($fbId);
    $result = $db -> select("SELECT ID FROM user WHERE FBAUTH=$fbId"); //Checks if the facebook account is already in database
	if(isset($result[0]['ID'])){ //fb account is in database - remove it
	  $result = $result[0]['ID'];
	  $db -> query("UPDATE user SET FBAUTH=NULL WHERE ID='$result'");
	}
	$db -> query("UPDATE user SET FBAUTH=$fbId WHERE ID='$_SESSION[uuid]'");  //update user with fb uuid
	
  }
}
?>