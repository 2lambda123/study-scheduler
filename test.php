<?php

include 'find.php';
include 'modify.php';

function test() { 
	$freetime = json_decode(gen_free_time_file('personal1.ics'));
	$events = downloadFile('personal1.ics');
	$added = $events;
	foreach($freetime as $key){
		$added = modify($added,json_encode($key));
	}
	print_r(json_decode($added));
}
test();

?>