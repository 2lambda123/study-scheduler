<ul class='menubar'>
<?php
if(session_id() == "") session_start();
include_once "../scripts/DB.php";
$db = new DB();
$result = null;

if(isset($_SESSION['uuid'])) {
	include_once '../site/tutorial.php';
	$result = $db->select('SELECT * FROM user WHERE ID=' . $db->quote($_SESSION['uuid']));
	echo "<li class='menubarEntry'><a href='homepage.php'>HOME </a></li>
    <li class='menubarEntry'><a href='calendar.php'>CALENDAR</a></li>
    <li class='menubarEntry'><a href='personalRoutines.php'>PERSONAL ROUTINES</a></li>
	<li class='menubarEntry'><a href='habits.php'>HABITS</a></li>
	<li class='menubarEntry'><a href='courses.php'>COURSES</a></li>
    <li class='menubarEntry'><a href='calExpImp.php'>IMPORT &amp; EXPORT</a></li>
    <li class='menubarEntry'><a href='settings.php'>SETTINGS</a></li>
	<li class='menubarEntry'><a href='friends.php'>FRIENDS</a></li>";
}
echo "<li class='menubarEntry' id ='login'>";

include_once '../scripts/loginForm.php';
if (!$result || $result[0]['GID'] == "") {
	echo "<li class='menubarEntry' id='gLogin'>";
	include_once '../site/googleLogin.php';
}
?>

	</li>
</ul>
