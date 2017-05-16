<?php
require_once '../google-api-php-client/vendor/autoload.php';
include_once '../scripts/DB.php';
include_once '../algorithm/modify.php';

session_start();
global $wholeURL;
$uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$uri = explode('/', $uri);
$wholeURL = "https://";
foreach ($uri as $u) {
	if ($u == "scripts" || $u == "site" || $u == "ajax" || $u == "algorithm") {
		break;
	}
	$wholeURL .= $u . "/";
}
	
//Scopes for accessing different API modules, calendar and google plus
define('SCOPES', implode(' ', array(
  Google_Service_Calendar::CALENDAR_READONLY, 
  'https://www.googleapis.com/auth/userinfo.profile', 
  'https://www.googleapis.com/auth/userinfo.email', 
  'https://www.googleapis.com/auth/plus.me')
));

//Create a client, set authorization config (client id and client secret from json file), addscopes and set accestype to offline
global $client;
$client = new Google_Client();
$client->setAuthConfig('../client_id.json');
$client->addScope(SCOPES);
$client->setAccessType('offline');
//$client->setApprovalPrompt('force');

//To ignore SSL (for localhost)
$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);
echo $_SESSION['originGLogin'];
//Check if we've received an access token from google
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	$client->setAccessToken($_SESSION['access_token']);
	
	//If access token has expired, ask for a refresh
	if ($client->isAccessTokenExpired()) {
		print_r($_SESSION['access_token']);
		$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		$_SESSION['access_token'] = $client->getAccessToken();
		print_r($_SESSION['access_token']);
	}
	
	//Check if we are already logged in
	if (isset($_SESSION['uuid']) && $_SESSION['uuid']) {
		//Access google plus api
		$plus = new Google_Service_Plus($client);
		$plus = $plus->people->get('me');

		//Check if user id already exists in db
		$db = new DB();
		$result = $db -> SELECT("SELECT * FROM user WHERE GID=" . $db->quote($plus->id));
		if ($result) {
			//If UUID of existing user id is same as logged in UUID, go back to where we came from
			if ($result[0]['ID'] == $_SESSION['uuid']) {
				$url = $_SESSION['originGLogin'];
				header("Location: https://" . $url);
			} else {
				$url = $_SESSION['originGLogin'];
				header("Location: https://" . $url . "?Google_Account_Is_Already_Connected_To_Another_Account");
			}
		} else { //If user id doesnt already exist in db, add ID and AUTH to logged in UUID to db
			echo "Hi2";
			if ($db -> query('UPDATE user SET GID=' . $db->quote($plus->id) . " WHERE ID=" . $db->quote($_SESSION['uuid']))) {
				if ($db -> query('UPDATE user SET GAUTH=' . $db->quote(json_encode($_SESSION['access_token'])) . " WHERE ID=" . $db->quote($_SESSION['uuid']))) {
					showCalendars(); //Then call calendar popup function and return to referer
					$url = $_SESSION['originGLogin'];
					header("Location: https://" . $url);
				}
			}
		}
	} else { //If you're not logged in
		$db = new DB();
		//Get new UUID from mysql
		$row = $db -> select("SELECT UUID() AS UUID");
		$UUID = $row[0]['UUID'];
		
		//Connect to google plus api
		$plus = new Google_Service_Plus($client);
		$plus = $plus->people->get('me');
		
		//Escape and quote user id and json_encode(access token)
		$GID = $db->quote($plus->id);
		$GAUTH = $db->quote(json_encode($_SESSION['access_token']));
		
		//Check if user id already exists in db
		$result = $db -> SELECT("SELECT * FROM user WHERE GID=" . $db->quote($plus->id));
		if ($result) {
			//Login and set session uuid to uuid from db and return to referer
			$_SESSION['uuid'] = $result[0]['ID'];
			$url = $_SESSION['originGLogin'];
			header("Location: https://" . $url);
		} else { //Create a new account with access token and user id
			echo "Trying to create new user <br>";
			if ($db -> query("INSERT INTO user (ID, GAUTH, GID) VALUES ('$UUID', $GAUTH, $GID)")) {
				echo "INSERT INTO USER SUCCESS<BR>";
				$sql = "INSERT INTO calendar (ID) VALUES ('$UUID')";
				if ($db -> query($sql)) {
					echo "INSERT INTO CALENDAR SUCCESS <BR>";
					$sql = "INSERT INTO data (ID) VALUES ('$UUID')";
					if ($db -> query($sql)) {
						ECHO "INSERT INTO DATA SUCCESS";
						$_SESSION['uuid'] = $UUID;
						showCalendars();
						$url = $_SESSION['originGLogin'];
						header("Location: https://" . $url);
					}
				}
			}
		}
	}
} else {
  $redirect_uri = $wholeURL . "scripts/oauth2callback.php";
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