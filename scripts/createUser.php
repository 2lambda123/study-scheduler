<?php
function createUser ($username, $password) {
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
		echo "Username already exists, please try another one.";
	} 
	//If not, insert new user with uuid, username and encrypted password
	//Also insert new user with uuid into calendar and data table, user is then connected through tables with uuid
	else {
		if ($db -> query("INSERT INTO user (ID, USERNAME, PASSWORD, SETTINGS, KTHAUTH, FBAUTH) VALUES ('$UUID', $username, $password, '', '', '')")) {
			$sql = "INSERT INTO calendar (ID, STUDY, PERSONAL, HABITS, CURRENT) VALUES ('$UUID', '', '', '', '')";
			if ($db -> query($sql)) {
				$sql = "INSERT INTO data (ID, HABITS, COURSES, ROUTINES) VALUES ('$UUID', '', '', '')";
					if ($db -> query($sql)) {
						echo "Succesfully created user";
					}
			}
		}
	}
}

//Check if username and password has been entered, if it has, call createUser function
if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] !== "" && $_POST['password'] !== "") {
	createUser($_POST['username'], $_POST['password']);
} else {
	echo "You have not filled in all fields";
}
?>
<form action="createUser.php" method="post">
Username: <input type="text" name="username">
Password: <input type="text" name="password">

<input type="submit" value="Sign Up">
</form>
