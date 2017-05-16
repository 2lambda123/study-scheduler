<?php
if(session_id() == "") session_start();

$uri = $_SERVER['HTTP_REFERER'];
$uri = explode('/', $uri);
$wholeURL = "";
foreach ($uri as $u) {
  if ($u == "scripts" || $u == "site" || $u == "ajax" || $u == "algorithm") {
  break;
  }
  $wholeURL .= $u . "/";
}

if (isset($_SESSION['tutorial'])) {
  switch ($_SESSION['tutorial']) {
    case 0:
    if ($uri[count($uri)-1] == "personalRoutines.php") $_SESSION['tutorial'] += 1;
    break;

    case 1:
    if ($uri[count($uri)-1] == "calExpImp.php") $_SESSION['tutorial'] += 1;
    break;

    case 2:
    if ($uri[count($uri)-1] == "courses.php") $_SESSION['tutorial'] += 1;
    break;

    case 3:
    if ($uri[count($uri)-1] == "habits.php") $_SESSION['tutorial'] += 1;
    break;

    case 4:
    if ($uri[count($uri)-1] == "settings.php") $_SESSION['tutorial'] += 1;
    break;

    case 5:
    if ($uri[count($uri)-1] == "calendar.php") $_SESSION['tutorial'] += 1;
    break;

    default:
    break;
  }
}

switch ($_SESSION['tutorial']) {
  case 0:
  echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$wholeURL.'site/personalRoutines.php">';
  break;

  case 1:
  echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$wholeURL.'site/calExpImp.php">';
  break;

  case 2:
  echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$wholeURL.'site/courses.php">';
  break;

  case 3:
  echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$wholeURL.'site/habits.php">';
  break;

  case 4:
  echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$wholeURL.'site/settings.php">';
  break;

  case 5:
  echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$wholeURL.'site/calendar.php">';
  break;

  default:
  echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$wholeURL.'site/homepage.php">';
  break;
}
?>
