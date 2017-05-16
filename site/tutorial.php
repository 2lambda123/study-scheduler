<script src="../ajax/buttonAjax.js"></script>

<?php
include_once '../scripts/popupEvent.php';
if(session_id() == "") session_start();
if (isset($_POST['tutorial'])) {
	$uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$uri = explode('/', $uri);
	$wholeURL = "http://";
	foreach ($uri as $u) {
		if ($u == "scripts" || $u == "site" || $u == "ajax" || $u == "algorithm") {
			break;
		}
		$wholeURL .= $u . "/";
	}
	$_SESSION['tutorial'] = 0;
	header("Location: " . $wholeURL . "site/personalRoutines.php");
}
if (isset($_SESSION['tutorial'])){
	echo $_SESSION['tutorial'] . " - " . $_SESSION['temp'];
  //echo $_SERVER['PHP_SELF'];
  switch($_SESSION['tutorial']){
    case 0: // routines
    popupGen('<div>
    <h1>Step: 1/6 - Routines</h1><br />
    Fill in the form according to your preferences. The algorithm will use the information to create a personal study schedule for you.<br /><br />
    <form class="changeForm" action="../ajax/skipTutorial.php" method="post"><input type="submit" value="Skip this part"/></form><br />
    <form class="changeForm" action="../ajax/endTutorial.php" method="post"><input type="submit" value="End tutorial" /></form>
    </div>');
    break;

    case 1: // import
    popupGen('<div>
    <h1>Step: 2/6 - Import</h1><br />
    Paste the link of your KTH schedule here. The link can be found by logging into your KTH account via this link:<br />
    <a target="_blank" href="https://www.kth.se/social/home/calendar/settings/">https://www.kth.se/social/home/calendar/settings/</a>
    <br /><br />
    When You have done that, continue by clicking on "courses" on the menubar. <br /><br />
    <form class="changeForm" action="../ajax/skipTutorial.php" method="post"><input type="submit" value="Skip this part"/></form><br />
    <form class="changeForm" action="../ajax/endTutorial.php" method="post"><input type="submit" value="End tutorial" /></form>
    </div>');
    break;

    case 2: // courses
    popupGen('<div>
    <h1>Step: 3/6 - Courses</h1><br />
    Fill in the information about the courses you are studying. The information about each course are found in the course web on KTH.<br /><br />
    <form class="changeForm" action="../ajax/skipTutorial.php" method="post"><input type="submit" value="Skip this part"/></form><br />
    <form class="changeForm" action="../ajax/endTutorial.php" method="post"><input type="submit" value="End tutorial" /></form>
    </div>');
    break;


    case 3: // habits
    popupGen('<div>
    <h1>Step: 4/6 - Habits</h1><br />
    Here, you fill in your habits. If you have multiple habits, click submit and then fill in another one. The algorithm will make sure study times don\'t clash with your habits. If the habit changes, you can remove the habit on the bottom of the page and then fill in a new one. If you don\'t have any habits, go ahead and click on the "skip this part".<br /><br />
    <form class="changeForm" action="../ajax/skipTutorial.php" method="post"><input type="submit" value="Skip this part"/></form><br />
    <form class="changeForm" action="../ajax/endTutorial.php" method="post"><input type="submit" value="End tutorial" /></form>
    </div>');
    break;

    case 4: // algorithm
    popupGen('<div>
    <h1>Step: 5/6 - Run algorithm</h1><br />
    Now that you have submitted the forms, the algorithm can be used. Click on "Schedule" on the left hand side and then "load algorithm". Make sure to do this again if you change any of the other forms.<br /><br />
    <form class="changeForm" action="../ajax/skipTutorial.php" method="post"><input type="submit" value="Skip this part"/></form><br />
    <form class="changeForm" action="../ajax/endTutorial.php" method="post"><input type="submit" value="End tutorial" /></form>
    </div>');
    break;

    case 5: // calendar
    popupGen('<div>
    <h1>Step: 6/6 - Calendar</h1><br />
    Here is your calendar! To look closer on an event, click on it. There, you can also add notes or edit them. You can edit an event by removing and/or reschedule it.<br /><br />
    <form class="changeForm" action="../ajax/skipTutorial.php" method="post"><input type="submit" value="Skip this part"/></form><br />
    <form class="changeForm" action="../ajax/endTutorial.php" method="post"><input type="submit" value="End tutorial" /></form>
    </div>');
    break;

    default:
    popupGen('<div>
    <h1>Congratulations!</h1><br />
    You are now ready to use study-scheduler. Good luck!<br /><br />
    <form class="changeForm" action="../ajax/endTutorial.php" method="post"><input type="submit" value="End tutorial" /></form>
    </div>');
    break;
  }

/*  $page = $_SERVER['PHP_SELF']; // string of page URL
  switch($page) { //TODO: ändra casen så det fungerar på hemsidan.
    case "/study-scheduler/site/homepage.php":

    case "/study-scheduler/site/calendar.php":

    case "/study-scheduler/site/personalRoutines.php":

    case "/study-scheduler/site/habits.php":

    case "/study-scheduler/site/calExpImp.php":

    case "/study-scheduler/site/settings.php":

    case "/study-scheduler/site/friends.php":
  }*/
}



?>
