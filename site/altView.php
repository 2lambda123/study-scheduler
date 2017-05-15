<?php

include_once '../scripts/DB.php';
include_once '../scripts/importCal.php';
include_once '../algorithm/distribute.php';
include_once '../site/genCal.php';

date_default_timezone_set('UTC'); 
/*function TimeToSec($time) {
	$sec = 0;
	foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
	return $sec*60;
}*/
function alt_check($clickedEvent){
	/*
	This function checks an event's summary and sees if it belongs to KTH or not, for giving it an HTML class when called in gen_event
	*/
	if(preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $clickedEvent['SUMMARY']))
	{
		$str ='KTH';
		return $str;
	}
	else
	{
		$str = 'Others';
		return $str;
	}
}

//begin database calls, which should not actually be in this function.
global $f;
$db = new DB();
$result = null;
if(session_id() == "") session_start();
if(isset($_SESSION['uuid'])){
	$query = "SELECT CURRENT FROM calendar WHERE ID='".$_SESSION['uuid']."'";
	$result = $db -> select($query);
	//var_dump($result);
}
$current = (isset($result[0]['CURRENT'])) ? $result[0]['CURRENT'] : null;
$f = $current;
if(isset($f)) $f = json_decode($f,true);
//end database calls.

function gen_alt_event($event, $event1=null){
	/*
	This function takes an event (class event from importCal.php) and returns an HTML formatted event to be used in a calendar view.
	It also accepts the previous event, in which case it sets the margin-top of this event to be proportional to the time between the two.
	*/
	$json = json_encode($event);
	$order   = array("\\r\\n", "\\n", "\\r");
	$replace = ' <br />';
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

	if ($event) {
		$cl = alt_check($event);
		$str = str_replace($order, $replace, $event['DESCRIPTION']);
		$html  = "<div class='event $cl' value ='$json'>";
		$html .= "<div class='pretty_time'>".$event['DTSTART']."</div>";
		$html .= "<div class='SUMMARY'>". str_replace($order, $replace, $event['SUMMARY']) ."</div>";
		if (preg_match($reg_exUrl, $str, $url)) {
			$html .= "<br><div class='extra'>" . preg_replace($reg_exUrl, '<a href="' . $url[0] . '">' . $url[0] . '</a>', $str) . "<br> Plats: " . str_replace($order, $replace, $event['LOCATION']) . "</div>";
		} else {
		$html .= "<br><div class='extra'>" . $str . "<br> Plats: " . $str . "</div>";
		}
		if (!$event['AVAILABLE']) { $html .= "<br><div><button class='edit'>Edit</button></div>"; }
		$html .= "</div>";
	}

	return $html;
}

function req_events($dtstart,$dtend,$n) {
	global $f;
		$b = binarySearch($dtstart,$f,0,count($f)-1,8);		
		if($b >= count($f)-1) {
			echo "<div class='event'>end</div>";
		}
		else {
			while(isset($f[$b]) && $f[$b]['DTSTART'] <= $dtend) {
				echo gen_alt_event($f[$b]);
				$b++;
			}
		}
	
}
if(isset($_POST['dtstart'],$_POST['dtend'])){
	req_events($_POST['dtstart'],$_POST['dtend'],7);
}
else {
	echo "<div id='cal'><button id='load' value='".count($f)."'>load</button></div>";
	include_once '../site/altViewTemplate.php';
}
?>