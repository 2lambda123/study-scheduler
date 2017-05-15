<?php
$css = file_get_contents('settings.css');
echo '<style type="type/css">$css</style>';
echo"<h2>What information do we gather?</h2><br><br>";
echo'<div class="infobox">';
echo"Depending on what account you sign in with on our website we collect
     different types of information<br><br>";
echo'</div>';
echo'<div class="infobox">';
echo"<b>Personal information.</b><br>";
echo"When you use our services we collect information about yourself. For example
     when you fill in our habit form, the courses form and personal routines.<br><br>";
echo"<b>What information is gathered from my Facebook account?</b><br>";
echo"When you sign in with Facebook on our website we don't save any personal
     information nor do we save your username and password. What we save to our database
     is an unique ID that we get from Facebook that we associate with your account.
      If you use our function 'find time
     with friends' we collect your friendslist from Facebook where eventual study
     time matches with other students are saved.<br><br>";
echo"<b>What is gathered from my Google account?</b><br>";
echo"From Google we get an (account ID) that we save in our database. If you wish
     to connect your Google calendar to our website. We will save the events in your
     calendar togheter with our own Study-scheduler on our database. No other information
     about your account is saved on our database.<br><br>";
echo'</div>';
?>
