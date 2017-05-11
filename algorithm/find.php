<?php

include_once '../scripts/importCal.php';
include_once '../algorithm/modify.php';

//The calendar file has the timezone UTC
date_default_timezone_set('UTC');

//Converts for example 20170124T120000Z to 2017012412000 as an float (overflow with int)
function eventTimeFloat($tid){
		return floatval(substr($tid, 0, 8).substr($tid, 9, 4));
}

//Gets an calendar $file(a json_encoded events) and a startdate $start (which is not nessesary for the function to work.
//It returns all the empty slots in the calendar it recieves
function gen_free_time($file, $start=1){
	$eventArray = json_decode($file, true);
	$now = date('Ymd').'T'.date('H').'00'.substr($eventArray[0]["DTSTART"],-3,3);
	//if we have set a startdate
	if ($start !== 1) { $now = $start; }
	$new_times = array();
	$tempstart = $now;
	$eventS = null;//When the event starts
	$eventE = null;//When the event ends
	for($i = 1; $i < count($eventArray); $i++){
		//A guard to exclude all events that happen before $now
		if(eventTimeFloat($eventArray[$i-1]["DTSTART"]) >= eventTimeFloat($now)) {
			$eventS = $eventArray[$i-1]["DTSTART"];
			$eventE = $eventArray[$i-1]["DTEND"];
			$check = true;

			//Guard to make sure we look at events from the same date can otherwise mess up the overlapping guards
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
			//A guard to check if two following events have the exact same time, if they do we skip the current event
			if($eventE != $eventArray[$i]["DTSTART"]){
					$e = new event;
					$e->DTSTART = $eventE;
					$e->DTEND = $eventArray[$i]["DTSTART"];
					$e->AVAILABLE = true;
					array_push($new_times, $e);
			}
		}
	}
	return json_encode($new_times);
}

/*function free_time_with_events has the inputs $schedule and $start. With these two variables
we will generate free time events from the $start date. After we have gotten the free times in the
$schedule we merge free time with the calendar using the function modify. Then we return a json_encoded
calendar. $start isn't nessesary for the function to work.*/
function free_time_with_events($schedule, $start = 1){
	$freeTime = gen_free_time($schedule, $start);//Get free times
	//Merge both togheter
	$schedule = modify($schedule, $freeTime);

	return $schedule;
}

?>
