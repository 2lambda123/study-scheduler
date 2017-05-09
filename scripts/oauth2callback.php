<?php

require_once '../google-api-php-client/vendor/autoload.php';

session_start();

define('SCOPES', implode(' ', array(
  Google_Service_Calendar::CALENDAR_READONLY, 
  'https://www.googleapis.com/auth/userinfo.profile', 
  'https://www.googleapis.com/auth/userinfo.email', 
  'https://www.googleapis.com/auth/plus.me')
));

$client = new Google_Client();
$client->setAuthConfigFile('../client_id.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/readyForGit/scripts/oauth2callback.php');
$client->addScope(SCOPES);
$client->setAccessType('offline');

$guzzleClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
$client->setHttpClient($guzzleClient);

if (! isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();	
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/readyForGit/scripts/googleAPI.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

?>