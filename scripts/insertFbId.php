<?php
include_once '../scripts/DB.php';
$db = new DB();
if (session_id() == "") session_start();

//TODO - Need to consider what happends if someone uses the same fb account on two accounts

$info = json_decode($_REQUEST["q"], true);//ajax request data
$fbId = $info["id"]; //facebook uuid
$fbName = $info["name"]; //facebook user name

if(isset($_SESSION['uuid']) && $_SESSION['uuid'] != ""){ //check if $SESSION is true -> user already logged in and want to continue with fb
  $result = $db -> select("SELECT FBAUTH FROM user WHERE ID='$_SESSION[uuid]'");
    if(isset($result[0]['FBAUTH']) && $fbId == $result[0]['FBAUTH']){ //FBAUTH already in database, dont do anything
  }
  else{//no value in database, insert $fbId OR User has already authenticated with another facebook account -> change to new facebook account
	echo $_SESSION['uuid'];
   $db -> query("UPDATE user SET FBAUTH=".$db->quote($fbId)." WHERE ID='$_SESSION[uuid]'");
  }
}
else{ //user is not logged in -> assume he either wants to login or create a new account using his facebook
  $row = $db -> select("SELECT UUID() AS UUID");
  $UUID = $row[0]['UUID']; //Unique ID
  $fbId = $db -> quote($fbId);
  
  $result = $db -> select("SELECT ID FROM user WHERE FBAUTH=$fbId"); //
  if (isset($result[0]["ID"])){ //True if user already has connected with facebook -> login
    $_SESSION["uuid"] = $result[0]["ID"];
	echo "Logged in successfully";
	//echo "<script type='text/javascript'>window.location.reload()</script>";
  }
  else{ //user has not connected with facebook before and is not logged in -> create new account using facebook 
    if ($db -> query("INSERT INTO user (ID, USERNAME, PASSWORD, SETTINGS, KTHAUTH, FBAUTH) VALUES ('$UUID', '', '', '', '', $fbId)")) {
	  $sql = "INSERT INTO calendar (ID, STUDY, PERSONAL, HABITS, CURRENT) VALUES ('$UUID', '', '', '', '')";
	  if ($db -> query($sql)) {
	    $sql = "INSERT INTO data (ID, HABITS, COURSES, ROUTINES) VALUES ('$UUID', '', '', '')";
		  if ($db -> query($sql)) {
		    echo "Successfully created user";
		  }
	  }
	}
  } 
}




	
?>