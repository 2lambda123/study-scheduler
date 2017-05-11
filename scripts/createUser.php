<?php
$form = 
<<<MILA
<form id='signUp' action='../scripts/createUser.php' method='POST'>
Username: <input type="text" name="username">
Password: <input type="password" name="password">
<input type='submit' value='Sign Up'>
<div id='result'/>
</form>
MILA;

global $er;

function createUser ($username, $password) {
	global $er;
	include_once '../scripts/DB.php';
	$db = new DB();
	
	//Get unique id for new user
	$row = $db -> select("SELECT UUID() AS UUID");
	$UUID = $row[0]['UUID'];
	
	//Quote username and password before querying to database, password gets encrypted to sha256
	$username = $db -> quote($username);
	$password = $db -> quote(hash('sha256', $password));
	
	//Check if username already exists
	$row = $db -> select("SELECT USERNAME FROM user WHERE USERNAME = $username");
	if (isset($row[0])) {
		$er = "Username already exists, please try another one.";
	} 
	//If not, insert new user with uuid, username and encrypted password
	//Also insert new user with uuid into calendar and data table, user is then connected through tables with uuid
	else {
		if ($db -> query("INSERT INTO user (ID, USERNAME, PASSWORD) VALUES ('$UUID', $username, $password)")) {
			$sql = "INSERT INTO calendar (ID) VALUES ('$UUID')";
			if ($db -> query($sql)) {
				$sql = "INSERT INTO data (ID) VALUES ('$UUID')";
					if ($db -> query($sql)) {
						$er = "Succesfully created user";
					}
			}
		}
	}
}

if(!isset($_SESSION['uuid'])) {
	if(!isset($_POST['username'],$_POST['password']))
			echo $form;
		else {
			createUser($_POST['username'],$_POST['password']);
			echo $er;
		}
}
?>

<style>
#signUp {
	display: inline-block;
	padding:2em;
	border: 1px solid;
	background: whitesmoke;
}
</style>
<script src="../site/jquery.min.js"></script>
<script>
$(document).on('submit','#signUp', function(event) {
	event.preventDefault();
	var user = $(this).serialize();
	$.ajax ({
		type: $(this).attr('method'),
		url: $(this).attr('action'),
		data: $(this).serialize(),
		success: function(data){	
			if(data.substr(0,24) == "Succesfully created user"){
				$.ajax ({
					type: 'POST',
					url: '../scripts/loginform.php',
					data: user,
					success: function(data){window.location.reload();}
				});
			}
			else {
				document.getElementById('result').innerHTML = data;
			}
		}
	})
});
</script>

