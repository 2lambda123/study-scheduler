<?php

include 'importCal.php';
include 'modify.php';

function gen_free_time_file($file) {
	$file_content = file_get_contents($file);
	return gen_free_time($file_content);
}
date_default_timezone_set('UTC');//The calendar file has the timezone UTC

function eventTimeFloat($tid){//Converts for example 20170124T120000Z to 2017012412000 as an float (overflow with int)
		return floatval(substr($tid, 0, 8).substr($tid, 9, 4));
}

function gen_free_time($file){
	$cal = importCal($file);
	$eventArray = json_decode($cal, true);
	$now = date('Ymd').'T'.date('H').'00'.substr($eventArray[0]["DTSTART"],-3,3);
	$new_times = array();
	$tempstart = $now;
	$eventS = null;//When the event starts
	$eventE = null;//When the event ends
	for($i = 1; $i < count($eventArray); $i++){
		if(eventTimeFloat($eventArray[$i-1]["DTSTART"]) >= eventTimeFloat($now)) {
				$eventS = $eventArray[$i-1]["DTSTART"];
				$eventE = $eventArray[$i-1]["DTEND"];
				$check = true;
				echo $eventS;

				if(floatval(substr($eventS, 0, 8)) == floatval(substr($eventArray[$i]["DTSTART"], 0, 8))){
						while($check && isset($eventArray[$i + 1])){//This loop is to check if there are any overlapping events
							$firstDiff = eventTimeFloat($eventE) - eventTimeFloat($eventS);
							$secondDiff = eventTimeFloat($eventArray[$i]["DTEND"]) - eventTimeFloat($eventArray[$i]["DTSTART"]);
							$check = false;

							if($firstDiff >= $secondDiff){//Checking on which side the smaller event is on
								if(eventTimeFloat($eventE) > eventTimeFloat($eventArray[$i]["DTSTART"])){//We have a overlapping
										$check = true;
										if(eventTimeFloat($eventE) < eventTimeFloat($eventArray[$i]["DTEND"]))//Second event ends after the first one
											$eventE = $eventArray[$i]["DTEND"];

										if(eventTimeFloat($eventS) > eventTimeFloat($eventArray[$i]["DTSTART"]))//Second event starts beföre the first one
											$eventS = $eventArray[$i]["DTSTART"];
								}
							}
							else{
								if(eventTimeFloat($eventE) > eventTimeFloat($eventArray[$i]["DTSTART"])){//We have a overlapping
										$check = true;
										if(eventTimeFloat($eventS) > eventTimeFloat($eventArray[$i]["DTSTART"]))//First event starts after first one
											$eventS = $eventArray[$i]["DTSTART"];

										if(eventTimeFloat($eventE) < eventTimeFloat($eventArray[$i]["DTEND"]))//First event ends before the first one
											$eventE = $eventArray[$i]["DTEND"];
								}
							}
							if($check)//If there are multiple overlappings we don't want
								$i++;		//to look at them again in the next iteration
						}
				}

				$e = new event;
				$e->DTSTART = $eventE;
				$e->DTEND = $eventArray[$i]["DTSTART"];
				$e->AVAILABLE = true;
				array_push($new_times, $e);
		}
	}
	return json_encode($new_times);
}

function free_time_with_events($schedule){

	$freeTime = json_decode(gen_free_time_file($schedule));//Get free times
	$schema = downloadFile($schedule);//Get the events of calendar
	foreach ($freeTime as $key) {//Merge both togheter
		$schema = modify($schema, json_encode($key));
	}
	return $schema;
}
?>
