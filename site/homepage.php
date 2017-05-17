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
	
	<form action="homepage.php" method="post">
		<input type="hidden" name="tutorial" value="">
		<input type="submit" id="tut" value="Run tutorial">
	</form>
	
	<style>
	#tut {
		margin: 30px auto;
		font-size: 32px;
		width: 300px;
		height: 100px;
	}
	</style>

<?php

include '../scripts/createUser.php';


?>

</body>
