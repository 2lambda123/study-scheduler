<?php
	include "../scripts/importCal.php";
	date_default_timezone_set('UTC'); 
	function cmp_date($date1,$date2){ return cmp_date_val($date1) > cmp_date_val($date2); }
	function cmp_date_val($date) 	{ return substr($date,0,8).substr($date,9,4); }
	function cmp_day($date1,$date2) { return intval(substr($date2,0,8)) - intval(substr($date1,0,8)); }
	function pretty_time($date) 	{ return substr($date,9,2)+2 . ":".substr($date,11,2); }
	function ugly_time($date) 		{ return substr($date,9,2)+2 . substr($date,11,2); }
	function TimeToSec($time) {
		$sec = 0;
		foreach (array_reverse(explode(':', $time)) as $k => $v) $sec += pow(60, $k) * $v;
		return $sec*60;
	}
	
	global $next;
	function collect($date_start, $date_end) {
		include_once '../scripts/DB.php';
		include_once '../scripts/find.php';
		include_once '../scripts/Analyze.php';
		include_once '../scripts/distribute.php';
		
		$db = new DB();
		$result = $db -> select("SELECT CURRENT FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		$result1 = $db -> select("SELECT ROUTINES FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		$result2 = $db -> select("SELECT COURSES FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		$result = $result[0]['CURRENT'];
		$result1 = $result1[0]['ROUTINES'];
		$result2 = $result2[0]['COURSES'];
		
		$file = distribute(analyze(free_time_with_events($result), $result1), $result2, $result1);
		//$file = json_decode(downloadFile("https://www.kth.se/social/user/214560/icalendar/511554f518e0f69696d2f76a1df75f49427b6471"));
		$events = array();
		foreach($file as $event) {
			if(cmp_date($event->DTSTART,$date_start)
				&& cmp_date($date_end,$event->DTEND))
				array_push($events,$event);
		}
		$week = array();
		$weeklength = 7;// cmp_day($date_start,$date_end);
		for($i = 0; $i < $weeklength; $i++) {
			array_push($week,array());
		}
		foreach($events as $event) {
			array_push($week[cmp_day($date_start,$event->DTSTART)],$event);
		}
		return $week;
	}

	function gen_event($event, $event1=1){
		$divide=80;
		global $next;
	    $json = json_encode($event);
		$length = ((TimeToSec(pretty_time($event->DTEND))-TimeToSec(pretty_time($event->DTSTART))))/$divide;
		$tB;
		if ($event1 !== 1) {
			$tB = ((TimeToSec(pretty_time($event->DTSTART))-TimeToSec(pretty_time($event1->DTEND))))/$divide;
		} else {
			$tB = (TimeToSec(pretty_time($event->DTSTART)))/$divide;
		}
		$html  = "<div class='event' style='height:$length%'>";
		$html .= "<div class='SUMMARY'>".$event->SUMMARY."</div>";
		$html .= "<div class='pretty_time'>".pretty_time($event->DTSTART)." - ".pretty_time($event->DTEND)."</div>";
		
		$html .= " </div>";
		$order   = array("\\r\\n", "\\n", "\\r");
		$replace = ' <br />';
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

		if ($event) {
			$cl = check($event);
			$length = ((TimeToSec(pretty_time($event->DTEND))-TimeToSec(pretty_time($event->DTSTART))))/$divide;
			$str = str_replace($order, $replace, $event->DESCRIPTION);
			$html  = "<div class='event $cl' value ='$json' style='height:".$length."px;margin-top:".$tB."px;";
			//if ($next) {$html .= "width:45px;";}
			$html .= "'>";
			$html .= "<div class='pretty_time'>".pretty_time($event->DTSTART)." - ".pretty_time($event->DTEND)."</div>";
			$html .= "<div class='SUMMARY'>". str_replace($order, $replace, $event->SUMMARY) ."</div>";
			if (preg_match($reg_exUrl, $str, $url)) {
				$html .= "<br><div class='extra'>" . preg_replace($reg_exUrl, '<a href="' . $url[0] . '">' . $url[0] . '</a>', $str) . "<br> Plats: " . str_replace($order, $replace, $event->LOCATION) . "</div>";
			} else {
			$html .= "<br><div class='extra'>" . $str . "<br> Plats: " . $str . "</div>";
			}
			if (!$event->AVAILABLE) { $html .= "<br><div><button class='edit'>Edit</button></div>"; }
			$html .= "</div>";
		}

		return $html;
	}

	function gen_day($events){
		global $c, $next;
		$next = false;
		$html  = "<div class='day'>";
		if (isset($events[1])) {
			if ($events[0]->DTEND > $events[1]->DTSTART && cmp_day($events[1]->DTSTART , $events[0]->DTEND) >= 0) { $next = true; }
			else { $next = false; }
		}
		for($i = 0; $i < count($events); $i++) {
			if (isset($events[$i-1])) {
				$html .= gen_event($events[$i], $events[$i-1]);
			} else {
				$html .= gen_event($events[$i]);
			}
			if (isset($events[$i+1])) {
				if ($events[$i]->DTEND > $events[$i+1]->DTSTART && cmp_day($events[$i+1]->DTSTART , $events[$i]->DTEND) >= 0) { $next = true; }
				else { $next = false; }
			}
		}
		$html .= "</div>";
		return $html;
	}
	function gen_week($date1,$date2) {
		$events = collect($date1,$date2);
		$ar = array();
		foreach($events as $event){
			array_push($ar,gen_day($event));
		}
		return $ar;
	}
	function print_events($date1,$date2) {
		return(gen_week($date1,$date2));
	}
	function check($clickedEvent){
		if(preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $clickedEvent->SUMMARY))
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
?>
