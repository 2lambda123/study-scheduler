<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />

<title> Home Page </title>
<link href="../site/menubar.css" rel="stylesheet">
</head>

<body>
<?php include "../site/menubar.php";?>

	<div id ='logo'>
   		 <img src="small.png"/>
   	</div>
	
	<?php if(isset($_SESSION['uuid'])) echo '
	<div id="tut"> <h2>Need help to get started?</h2>
	<form action="homepage.php" method="post">
		<input type="hidden" name="tutorial" value="">
		<input type="submit" id="tutBtn" value="Run tutorial">
	</form>
	</div>'; ?>

<?php

include '../scripts/createUser.php';


?>

</body>
