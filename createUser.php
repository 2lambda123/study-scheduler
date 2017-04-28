<?php
function createUser ($username, $password) {
	include 'DB.php';
	$db = new DB();
	
	$row = $db -> select("SELECT UUID() AS UUID");

	$UUID = $row[0]['UUID'];
	
	$username = $db -> quote($username);
	$password = $db -> quote(hash('sha256', $password));
	$row = $db -> select("SELECT USERNAME FROM user WHERE USERNAME = $username");
	if (isset($row[0])) {
		echo "Username already exists, please try another one.";
	} else {
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
