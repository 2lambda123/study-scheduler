<?php
include_once '../scripts/DB.php';
require_once '../google-api-php-client/vendor/autoload.php';
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
//$client->setApprovalPrompt('force');

$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);

$client->setAccessToken($_SESSION['access_token']);

if ($client->isAccessTokenExpired()) {
	$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
	$_SESSION['access_token'] = $client->getAccessToken();
}
	
$service = new Google_Service_Calendar($client);
//var_dump($service->calendarList->listCalendarList());
$cal = $_POST['calendars'];
$optParams = array(
  'maxResults' => 100,
  'orderBy' => 'startTime',
  'singleEvents' => TRUE,
  'timeMin' => date('c'),
);

$p = array();
foreach($cal as $c) {
	$results = $service->events->listEvents($c, $optParams);
	if (count($results->getItems()) == 0) {
	} else {
		foreach ($results->getItems() as $event) {
			$e = new stdClass();
			if ($event->summary) {
				$e->SUMMARY = $event->summary;
				if ($event->id) {
					$e->UID = $event->id;
					if ($event->location) {
						$e->LOCATION = $event->location;
					} else {
						$e->LOCATION = NULL;
					}
					if ($event->description) {
						$e->DESCRIPTION = $event->description;
					} else {
						$e->DESCRIPTION = NULL;
					}
					if ($event->start->dateTime) {
						$start = $event->start->dateTime;
						$start = str_replace('-', '', $start);
						$start = str_replace(':', '', $start);
						$start = str_replace('+0200', 'Z', $start);
						$e->DTSTART = $start;
						if ($event->end->dateTime) {
							$end = $event->end->dateTime;
							$end = str_replace('-', '', $end);
							$end = str_replace(':', '', $end);
							$end = str_replace('+0200', 'Z', $end);
							$e->DTEND = $end;
							$e->AVAILABLE = FALSE;
							$e->NOTES = NULL;
							array_push($p, $e);
						}
					}
				}
			}
		}
	}
}
if ($p[0]) {
	$db = new DB();
	$db -> query('UPDATE calendar SET PERSONAL=' . $db->quote(json_encode($p)) . ' WHERE ID=' . $db->quote($_SESSION['uuid']));
}
?>