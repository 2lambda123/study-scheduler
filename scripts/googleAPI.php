<?php
require_once '../google-api-php-client/vendor/autoload.php';
include_once '../scripts/DB.php';
include_once '../algorithm/modify.php';

session_start();

define('SCOPES', implode(' ', array(
  Google_Service_Calendar::CALENDAR_READONLY, 
  'https://www.googleapis.com/auth/userinfo.profile', 
  'https://www.googleapis.com/auth/userinfo.email', 
  'https://www.googleapis.com/auth/plus.me')
));
global $client;
$client = new Google_Client();
$client->setAuthConfig('../client_id.json');
$client->addScope(SCOPES);
$client->setAccessType('offline');


$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	$client->setAccessToken($_SESSION['access_token']);

	if ($client->isAccessTokenExpired()) {
		$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		$_SESSION['access_token'] = $client->getAccessToken();
	}
	
	if (isset($_SESSION['uuid']) && $_SESSION['uuid']) {
		$plus = new Google_Service_Plus($client);
		$plus = $plus->people->get('me');
		
		$db = new DB();
		$result = $db -> SELECT("SELECT * FROM user WHERE GID=" . $db->quote($plus->id));
		if ($result) {
			if ($result[0]['ID'] == $_SESSION['uuid']) {
				$url = $_SESSION['originGLogin'];
				header("Location: http://$url");
			}
		} else {
			if ($db -> query('UPDATE user SET GID=' . $db->quote($plus->id) . " WHERE ID=" . $db->quote($_SESSION['uuid']))) {
				if ($db -> query('UPDATE user SET GAUTH=' . $db->quote(json_encode($_SESSION['access_token'])) . " WHERE ID=" . $db->quote($_SESSION['uuid']))) {
					showCalendars();
					$url = $_SESSION['originGLogin'];
					header("Location: http://$url");
				}
			}
		}
	} else {
		$db = new DB();
		
		$row = $db -> select("SELECT UUID() AS UUID");
		$UUID = $row[0]['UUID'];
		
		$plus = new Google_Service_Plus($client);
		$plus = $plus->people->get('me');
		
		$GID = $db->quote($plus->id);
		$GAUTH = $db->quote(json_encode($_SESSION['access_token']));
		
		$result = $db -> SELECT("SELECT * FROM user WHERE GID=" . $db->quote($plus->id));
		if ($result) {
			$_SESSION['uuid'] = $result[0]['ID'];
			$url = $_SESSION['originGLogin'];
			header("Location: http://$url");
		} else {
			if ($db -> query("INSERT INTO user (ID, USERNAME, PASSWORD, SETTINGS, KTHAUTH, FBAUTH, GAUTH, GID) VALUES ('$UUID', '', '', '', '', '', $GAUTH, $GID)")) {
				$sql = "INSERT INTO calendar (ID, STUDY, PERSONAL, HABITS, CURRENT) VALUES ('$UUID', '', '', '', '')";
				if ($db -> query($sql)) {
					$sql = "INSERT INTO data (ID, HABITS, COURSES, ROUTINES, KTHlink) VALUES ('$UUID', '', '', '', '')";
					if ($db -> query($sql)) {
						$_SESSION['uuid'] = $UUID;
						showCalendars();
						$url = $_SESSION['originGLogin'];
						header("Location: http://$url");
					}
				}
			}
		}
	}
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/readyForGit/scripts/oauth2callback.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

function showCalendars() {
	global $client;
	$plus = new Google_Service_Plus($client);
	$plus = $plus->people->get('me');
	$service = new Google_Service_Calendar($client);
	$html = "";
	$html .= '<h3>Choose which calendars to add</h3>';
	$html .= '<form id="calendars" action="../ajax/addGoogleCal.php" method="post"><table>';
	$calendarList = $service->calendarList->listCalendarList();
	foreach ($calendarList->getItems() as $calendarListEntry) {
		$html .= '<tr><td><input type="checkbox" value="' . $calendarListEntry->id . '" name="calendars[]"></td><td>' . $calendarListEntry->summary . '</td></tr>';
	}
	$html .= '</table><input type="submit" value="Add calendars">';
	$html .= "</form>";
	
	$_SESSION['calendarPopup'] = $html;
}

?>