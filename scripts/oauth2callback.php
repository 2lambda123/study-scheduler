<?php

require_once '../google-api-php-client/vendor/autoload.php';

session_start();
$uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$uri = explode('/', $uri);
$wholeURL = "http://";
foreach ($uri as $u) {
	if ($u == "scripts" || $u == "site" || $u == "ajax" || $u == "algorithm") {
		break;
	}
	$wholeURL .= $u . "/";
}

define('SCOPES', implode(' ', array(
  Google_Service_Calendar::CALENDAR_READONLY, 
  'https://www.googleapis.com/auth/userinfo.profile', 
  'https://www.googleapis.com/auth/userinfo.email', 
  'https://www.googleapis.com/auth/plus.me')
));

$client = new Google_Client();
$client->setAuthConfigFile('../client_id.json');
$client->setRedirectUri($wholeURL . "scripts/oauth2callback.php");
$client->addScope(SCOPES);
$client->setAccessType('offline');
//$client->setApprovalPrompt('force');

$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();	
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = $wholeURL . "scripts/googleAPI.php";
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

?>