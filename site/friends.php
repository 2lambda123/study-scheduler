<!DOCTYPE html>
<html>
<head>
<!-- <script src="../ajax/displayFriends.js"></script> -->
<link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />

<title> Friends </title>
<link href="../site/menubar.css" rel="stylesheet">

</head>
<body>
<?php include "../site/menubar.php";?>

  <h1> Friends </h1>
  <h2> Use the button below to find common study times with your friends</h2>
  <div id="fb-root"></div>
  <div class="fb-login-button" data-width="10" data-max-rows="1"
  data-size="medium" data-button-type="continue_with"
  data-show-faces="false" data-auto-logout-link="true"
  data-use-continue-as="false"scope="public_profile,user_friends"
  onlogin="checkLoginState();"></div>
  
  <div id="findFriends"> <!-- used for button -->
  </div>
  <div id="findFriendsResults"> <!-- used for common study times result -->
  </div>
  <div id="fbLogin">
  </div>

</body>
</html>
<script src="../ajax/displayFriends.js"></script>