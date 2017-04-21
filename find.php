<?php

include 'importCal.php';

function gen_free_time_file($file) {
	$file_content = file_get_contents($file);
	return gen_free_time($file_content);
}
function get_comparable_datetime($formatted){
	$ans = substr($formatted,0,8).substr($formatted,9,4);
	return $ans;
}
function gen_free_time($file) {
	$cal = json_decode(importCal($file));
	$today = date('Ymd');
	$now = $today.'T'.date('H').'00'.substr($cal[0]->DTSTART,-3,3);
	$available_times = array();
	$tempstart = $now;
	$uid = 0;
	foreach($cal as $key) {
		if(intval(substr($key->DTSTART, 0, 8)) >= $today) {
			$e = new event;
			$e->DTSTART = $tempstart;
			$e->DTEND = $key->DTSTART;
			$e->AVAILABLE = true;
			$tempstart = $key->DTEND;
			if(get_comparable_datetime($e->DTSTART) < get_comparable_datetime($e->DTEND))
				array_push($available_times,$e);
		}
	}
	$export = json_encode($available_times);
	return $export;
}
?>