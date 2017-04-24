<?php

include 'importCal.php';

function gen_free_time_file($file) {
	$file_content = file_get_contents($file);
	return gen_free_time($file_content);
}

function gen_free_time($file) {
	$cal = json_decode(importCal($file));
	//var_dump($cal[0]);
	$today = date('Ymd');
	$now = $today.'T'.date('H').'00'.substr($cal[0]->DTSTART,-3,3);
	//echo $now;
	$available_times[] = new event;
	//print_r($available_times);

	$tempstart = $now;
	foreach($cal as $key) {
		if(intval(substr($key->DTSTART, 0, 8)) >= $today) {
			$e = new event;
			$e->DTSTART = $tempstart;
			$e->DTEND = $key->DTSTART;
			$e->AVAILABLE = true;
			$tempstart = $key->DTEND;
			array_push($available_times,$e);
			//var_dump($e);
		}
	}
	//var_dump($available_times);
	$export = json_encode($available_times);
	return $export;
}

echo gen_free_time_file('personal.ics');
?>