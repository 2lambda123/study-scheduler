<?php

include_once '../algorithm/find.php';

//$events = encoded json array of objects of events, $collection = encoded json object of personal routines
//returns a json encoded array of objects of events more suitable for user taking personal routines into account
function analyze ($events, $collection) {
	$e = json_decode($events); //Decode to array of objects
	$collection = json_decode($collection, false); //Decode to array of objects
	$sleepfrom = str_replace(":", "", $collection->sleepfrom); //From possible 00:00 to 0000 format
	$sleepto = str_replace(":", "", $collection->sleepto);

	//Gets date and personal routines as input, returns true if user doesnt want to study on this date, false if he does want.
	function getDays($d, $collection) {
		$wD[] = array();
		//If key exists in $collection, add to array $wD;
		if (property_exists($collection,"Monday")) {
			array_push($wD, 'Monday');
		}
		if (property_exists($collection,"Tuesday")) {
			array_push($wD, 'Tuesday');
		}
		if (property_exists($collection,"Wednesday")) {
			array_push($wD, 'Wednesday');
		}
		if (property_exists($collection,"Thursday")) {
			array_push($wD, 'Thursday');
		}
		if (property_exists($collection,"Friday")) {
			array_push($wD, 'Friday');
		}
		if (property_exists($collection,"Saturday")) {
			array_push($wD, 'Saturday');
		}
		if (property_exists($collection,"Sunday")) {
			array_push($wD, 'Sunday');
		}
		
		//If the day of the week of the input date matches one of the array values, return true, user doesnt want to study
		if (in_array(date('l', strtotime($d)), $wD)) {
			return true;
		}
		return false;
	}
	
	$count = count($e);
	//This loop fixes sleeping schedule
	for ($i = 0; $i < $count; $i++) {
		if ($e[$i]->AVAILABLE) { //Only available events are configured
			if ($sleepfrom > 2400 && $sleepto > 2400) { //Removes date from sleepfrom and sleepto and only saves time in case (xxxx2400)
					$sleepfrom = substr($sleepfrom, 9, 4);
					$sleepto = substr($sleepto, 9, 4);
				}
				
			if ($sleepfrom < $sleepto) { //If you sleep from the morning to the evening
				$sleepfrom = substr($e[$i]->DTSTART, 0, 8) . "T" . $sleepfrom . "Z"; //Adds dates to sleepfrom
				$sleepto = substr($e[$i]->DTSTART, 0, 8) . "T" . $sleepto . "Z"; //Adds dates to slepto

				if ($e[$i]->DTSTART < $sleepfrom && $e[$i]->DTEND > $sleepfrom && $e[$i]->DTEND <= $sleepto) { //If an event begins before you sleep and ends while you sleep
					$e[$i]->DTEND = $sleepfrom;
				} else if ($e[$i]->DTSTART >= $sleepfrom && $e[$i]->DTEND <= $sleepto) { //If an event only is during you sleep
					unset($e[$i]);
					$e = array_values($e);
				} else if ($e[$i]->DTSTART >= $sleepfrom && $e[$i]->DTSTART < $sleepto && $e[$i]->DTEND > $sleepto) { //If an event begins while you sleep and ends after you've slept
					$e[$i]->DTSTART = $sleepto;
				} else if ($e[$i]->DTSTART < $sleepfrom && $e[$i]->DTEND > $sleepto) { //If an event begins before you sleep and ends after you've slept
					//Split event so one ends when you go to sleep and one begins after you've slept
					$avEvent = new stdClass();
					$avEvent->SUMMARY = $e[$i]->SUMMARY;
					$avEvent->DTSTART = $sleepto;
					$avEvent->DTEND = $e[$i]->DTEND;
					$avEvent->UID = $e[$i]->UID; //fixa unik id när vi skaffat databas
					$avEvent->DESCRIPTION = $e[$i]->DESCRIPTION;
					$avEvent->LOCATION = $e[$i]->LOCATION;
					$avEvent->AVAILABLE = $e[$i]->AVAILABLE;
					
					$e[$i]->DTEND = $sleepfrom;
					array_splice($e, $i+1, 0, array($avEvent));
				}

			} else { //If you sleep from one day to another, (not more than 12 hours)
				$t = 0;
				$date = substr($e[$i]->DTSTART, 0, 8);//Get date of event
				while ($t <= 1) {//Loops twice to check sleep from prev day to present day then from present day to next day
					if ($sleepfrom > 2400 && $sleepto > 2400) { //Removes dates from $sleepfrom and $sleepto in case (xxx2400)
						$sleepfrom = substr($sleepfrom, 9, 4);
						$sleepto = substr($sleepto, 9, 4);
					}
					if ($t == 0) { //Adds date to sleepfrom and sleepto, if t is 0, dates are prev to present day, if t is 1, dates are present day to next day
						$sleepfrom = date('Ymd', strtotime($date . "-1 day")) . "T" . $sleepfrom . "Z";
						$sleepto = date('Ymd', strtotime($date)) . "T" . $sleepto . "Z";
					} else {
						$sleepfrom = date('Ymd', strtotime($date)) . "T" . $sleepfrom . "Z";
						$sleepto = date('Ymd', strtotime($date . "+1 day")) . "T" . $sleepto . "Z";
					}
					//If event begins before sleepfrom and ends during sleep
					if ($e[$i]->DTSTART < $sleepfrom && $e[$i]->DTEND > $sleepfrom && $e[$i]->DTEND <= $sleepto) {
						$e[$i]->DTEND = $sleepfrom;
						
					//else If an event only is during you sleep
					} else if ($e[$i]->DTSTART >= $sleepfrom && $e[$i]->DTEND <= $sleepto && $e[$i]->DTSTART <= $sleepto && $e[$i]->DTEND >= $sleepfrom) {
						unset($e[$i]);
						$e = array_values($e);
						$i--;
					//else If an event begins while you sleep and ends after you've slept
					} else if ($e[$i]->DTSTART >= $sleepfrom && $e[$i]->DTSTART < $sleepto && $e[$i]->DTEND > $sleepto) {
						$e[$i]->DTSTART = $sleepto;
					//else If an event begins before you sleep and ends after you've slept
					} else if ($e[$i]->DTSTART < $sleepfrom && $e[$i]->DTEND > $sleepto) {
						//Split event so one ends when you go to sleep and one begins after you've slept
						$avEvent = new stdClass();
						$avEvent->SUMMARY = $e[$i]->SUMMARY;
						$avEvent->DTSTART = $sleepto;
						$avEvent->DTEND = $e[$i]->DTEND;
						$avEvent->UID = $e[$i]->UID; //fixa unik id när vi skaffat databas
						$avEvent->DESCRIPTION = $e[$i]->DESCRIPTION;
						$avEvent->LOCATION = $e[$i]->LOCATION;
						$avEvent->AVAILABLE = $e[$i]->AVAILABLE;
						
						$e[$i]->DTEND = $sleepfrom;
						array_splice($e, $i+1, 0, array($avEvent));
					}
					$t++; //Increment t
				}
			}
		}
		$count = count($e);
	}	

	$firstEvent = true;
	$lastEvent;
	//This loop fixes travel times
	for($i = 0; $i < $count ; $i++) {
		//Finds next not available event
		if (!$e[$i]->AVAILABLE) {
			$ti;
			//Check event contains coursecode -> standard traveltime
			if(preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$i]->SUMMARY)){
				$ti = $collection->traveltime;
			} //traveltime from description
			else{
				$ti = $e[$i]->DESCRIPTION;
			}
			if ($firstEvent){
				//traveltime before first event of calendar
				$e = findAvailBetween(0, $i,0, $ti, $e);
			}
			for ($y = $i+1; $y < $count; $y++) {
				if(!$e[$y]->AVAILABLE) { //find next not available event after $e[$i]
					$ty;
					//Check event contains coursecode -> standard traveltime
					if(preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$y]->SUMMARY)){ 
						$ty = $collection->traveltime;
					} //Else traveltime is in description
					else{
						$ty = $e[$y]->DESCRIPTION;
					}
					//Check if first event doesn't end on the same day as the next event starts
					if(date('Ymd', strtotime(substr($e[$i]->DTEND, 0, 8))) !== date('Ymd', strtotime(substr($e[$y]->DTSTART, 0, 8)))){
						$e = findAvailBetween($i,$y,$ti, $ty, $e);
						$lastEvent = false;
						$z = true; //Find next not available event $i
						while ($z && $i+1 < $count) {
							$i++;
							if (!$e[$i]->AVAILABLE) {
								$z = false;
							}
							$y = $i+1;
						}
					}
					//Check if both events are of same type; coursecoude and coursecode or habit and habit
					else if(($e[$i]->SUMMARY == $e[$y]->SUMMARY) || (preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$i]->SUMMARY) == preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$y]->SUMMARY))){
						$lastEvent = false;
						$z = true; //Find next not available event $i
						while ($z && $i+1 < $count) {
							$i++;
							if (!$e[$i]->AVAILABLE) {
								$z = false;
							}
							$y = $i+1;
						}
					}
					//If not same type, fixes traveltimes
					else{
						$e = findAvailBetween($i,$y, $ti, $ty, $e);
						$lastEvent = false;
						$z = true; //FInd next not available event $i
						while ($z && $i+1 < $count) {
							$i++;
							if (!$e[$i]->AVAILABLE) {
								$z = false;
							}
							$y = $i+1;
						}
					}
					$lastEvent = false;
				}
				$count = count($e);
			}
			//Put traveltime after last event
			if ($lastEvent) {
				$e = findAvailBetween($i,count($e)-1,$ti, 0, $e);
			}
			$lastEvent = true;
			$firstEvent = false;
		}
		$count = count($e);
	}

	//This loop fixes breaks
	for($i = 0; $i < $count; $i++){
		if($e[$i]->AVAILABLE){
			// if event is longer than studylength
			if((strtotime($e[$i]->DTEND)-strtotime($e[$i]->DTSTART))/60 > $collection->studylength){
				// Split into 2 events, first one with studylength, and the other one begins after studylength+breaklength
					$avEvent = new stdClass();
					$avEvent->SUMMARY = $e[$i]->SUMMARY;
					$avEvent->DTSTART = str_replace(":", "T", date("Ymd:Hi", strtotime( "+" . $collection->studylength+$collection->breaktime . " minutes", strtotime(substr($e[$i]->DTSTART, 0, 8) . substr($e[$i]->DTSTART, 9, 4))))) . "Z"; //+studylength+breaktime
					$avEvent->DTEND = $e[$i]->DTEND;
					$avEvent->UID = $e[$i]->UID; //fixa unikt id när vi skaffat databas
					$avEvent->DESCRIPTION = $e[$i]->DESCRIPTION;
					$avEvent->LOCATION = $e[$i]->LOCATION;
					$avEvent->AVAILABLE = $e[$i]->AVAILABLE;

					$e[$i]->DTEND = str_replace(":", "T", date("Ymd:Hi", strtotime( "+" . $collection->studylength . " minutes", strtotime(substr($e[$i]->DTSTART, 0, 8) . substr($e[$i]->DTSTART, 9, 4))))) . "Z"; // +studylength
					//If event goes out of boundary after split, discard it
					if($avEvent -> DTSTART < $avEvent -> DTEND){
							array_splice($e, $i+1, 0, array($avEvent));
					}
			}
		}
		$count = count($e);
	}
	
	//Loop that discards all events that start on a day you dont want to study
	$events = array();
	foreach ($e as $ev) {
		if(!getDays($ev->DTSTART, $collection) || !$ev->AVAILABLE) {
			array_push($events, $ev);
		}
	}
	$e = $events;
	return json_encode($e);
}
// $i is index for first not available event
// $y is index for next not available event
// $ttime1 and $ttime 2 is first and next events traveltimes, $e is array of all events
function findAvailBetween($i,$y,$ttime1,$ttime2, $e){
	//Count both traveltimes, $e[$i]->dtend + ttime1, $e[$y]->dtstart - ttime2
	$pause1end = str_replace(":", "T", date("Ymd:His", strtotime("+" . $ttime1 . " minutes", strtotime(substr($e[$i]->DTEND, 0, 8) . substr($e[$i]->DTEND, 9, 6))))) . "Z"; // + $ttime1
	$pause2start = str_replace(":", "T", date("Ymd:His", strtotime( "-" . $ttime2 . " minutes", strtotime(substr($e[$y]->DTSTART, 0, 8) . substr($e[$y]->DTSTART, 9, 6))))) . "Z"; // - $ttime2
	for($x = $i; $x < $y; $x++){
		if ($e[$x]->AVAILABLE) {
			$u = false;
			 //If event is within first traveltime
			if($e[$x]->DTSTART >= $e[$i]->DTEND && $e[$x]->DTEND <= $pause1end) {
				unset($e[$x]);
				$e = array_values($e);
				$u = true;
			}	//If event starts before first traveltime and ends after first traveltime
			if($e[$x]->DTSTART < $pause1end && $e[$x]->DTEND > $pause1end && !$u){
				$e[$x]->DTSTART = $pause1end;
			} //If event is during second traveltime
			if($e[$x]->DTEND >= $pause2start && $e[$x]->DTSTART >= $pause2start && !$u) {
				unset($e[$x]);
				$e = array_values($e);
				$u = true;
				$y--;
			} //If event begins before second traveltime and ends after second traveltime
			if ($e[$x]->DTEND > $pause2start && $e[$x]->DTEND <= $e[$y]->DTSTART && !$u) {
				$e[$x]->DTEND = $pause2start;
			}
		}
	}
	return $e;
}
?>
