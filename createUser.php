<?php

function createUser ($username, $password) {
	$mysqli = new mysqli("localhost", "root", "", "studyscheduler");

	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}

	$result = mysql_query("SELECT UUID() AS UUID") or die('SQL error: ' . mysql_error());
	$row = mysql_fetch_assoc($result);
	$UUID = $row["UUID"];
	$p = hash('sha256', $password);
	//$sql = "INSERT INTO user (ID, USERNAME, PASSWORD, SETTINGS, KTHAUTH, FBAUTH) VALUES ('$UUID', '$username', '" . hash('sha256', $password) . "', '', '', '')";
	$stmt = $mysqli->prepare("INSERT INTO user (ID, USERNAME, PASSWORD, SETTINGS, KTHAUTH, FBAUTH) VALUES ('$UUID', ?, ?, '', '', '')");
	$stmt->bind_param('ss', $username, $p);
	if ($stmt->execute()) {
		$sql = "INSERT INTO calendar (ID, STUDY, PERSONAL, HABITS, CURRENT) VALUES ('$UUID', '', '', '', '')";
		if ($mysqli->query($sql)) {
			$sql = "INSERT INTO data (ID, HABITS, COURSES, ROUTINES) VALUES ('$UUID', '', '', '')";
				if ($mysqli->query($sql)) {
					echo "Succesfully created user";
				}
		}
	}

	$mysqli->close();
}

if (isset($_POST['username']) && isset($_POST['password'])) {
	createUser($_POST['username'], $_POST['password']);
}
?>
<form action="createUser.php" method="post">
Username: <input type="text" name="username">
Password: <input type="text" name="password">

<input type="submit" value="Sign Up">
</form>
