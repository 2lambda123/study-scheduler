<?php
$css = file_get_contents('settings.css');
echo '<style type="type/css">$css</style>';
echo"<h2>How do we use this information used?</h2>";
echo'<div class="infobox">';
echo"<h3>How do we use the information gathered on Study-scheduler.me?</h3><br>
     We want give you the best experience when using Study-scheduler.me. All the information
     we gather is to optimize our algorithm so we can give you the best fitting schedule
     that you can use in your daily life.<br><br>";
echo"<b>Information use collected from forms on Study-scheduler</b><br><br>";
echo"By filling out the forms throughout our website such as the personal routines,
     habits and courses. We get an instight how your daily life works and our algorithm
     at Study-scheduler can take this into consideration when it plans out your studytimes
     throughout your studies. The information from the forms are not used for any other
     purpose.<br><br>";
echo"<b>Facebook account use?</b><br><br>";
echo"Your Facebook account is used so that we can connect you to potential study friends
     through the function 'Study with friends'. Where we take your friends list and try
     to find potential times where you can meet up with your friends and study. This is
     to give you the optimal experience when you are using Study-scheduler<br><br>
     By connecting your account to Facebook we can also strengthen the security of your
     account so not another person can impersonate you.<br><br>";
echo"<b>Google account use?</b><br><br>";
echo"When you connect your Google account and import your Google calendar to our website
     we can optimize our algorithm to fit you as much as possible. This creates a more
     realistic study plan that you can follow and allows you only the need of one calendar.<br><br>";
echo'</div>';
?>
