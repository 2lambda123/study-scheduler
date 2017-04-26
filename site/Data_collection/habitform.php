<html>
<?php 
$user = "";
if (isset($_POST["sleepfrom"])) {
	$user = "Collection";
} else if (isset($_POST["hp"])) {
	$user = "Course";
} else {
	$user = "Habit";
}
echo "success!";



file_put_contents($user.='.txt',json_encode($_POST));
?>
</html>
