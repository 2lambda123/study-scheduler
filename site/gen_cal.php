<?php
	include "../scripts/importCal.php";

	function cmp_date($date1,$date2){ return cmp_date_val($date1) > cmp_date_val($date2); }
	function cmp_date_val($date) 	{ return substr($date,0,8).substr($date,9,4); }
	function cmp_day($date1,$date2) { return intval(substr($date2,0,8)) - intval(substr($date1,0,8)); }
	function pretty_time($date) 	{ return substr($date,9,2).":".substr($date,11,2); }
	function ugly_time($date) 		{ return substr($date,9,2).substr($date,11,2); }

	function collect($date_start, $date_end) {
		$file = json_decode(downloadFile("https://www.kth.se/social/user/214560/icalendar/511554f518e0f69696d2f76a1df75f49427b6471"));
		$events = array();
		foreach($file as $event) {
			if(cmp_date($event->DTSTART,$date_start)
				&& cmp_date($date_end,$event->DTEND))
				array_push($events,$event);
		}
		$week = array();
		$weeklength = cmp_day($date_start,$date_end);
		for($i = 0; $i < $weeklength; $i++) {
			array_push($week,array());
		}
		foreach($events as $event) {
			array_push($week[cmp_day($date_start,$event->DTSTART)],$event);
		}
		return $week;
	}
	function gen_event($event){
		$length = (ugly_time($event->DTEND)-ugly_time($event->DTSTART))/24;
		$html  = "<div class='event' style='height:$length%'>";
		$html .= "<div class='SUMMARY'>".$event->SUMMARY."</div>";
		$html .= "<div class='pretty_time'>".pretty_time($event->DTSTART)." - ".pretty_time($event->DTEND)."</div>";
		$html .= "</div>";
		return $html;
	}
	function gen_day($events){
		$html  = "<div class='day'>";
		foreach($events as $event) {
			$html .= gen_event($event);
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
?>