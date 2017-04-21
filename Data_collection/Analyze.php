<?php

$collection = json_decode(file_get_contents("Collection.txt"));

print_r($collection);

function days($d) {
	return false;
}

function analyze ($events) {
	$e = json_decode($events);
	$tempStart;
	$tempEnd;
	
	
	
	//Denna for loop fixar dagar man ej vill plugga
	for ($i = 0; $i < count($e); $i++) {
		if ($e[$i]->AVAILABLE) {
			if (days($e[$i]->DTSTART) && days($e[$i]->DTEND)) {
				unset($e[$i]);
			} else {
				$date = $e[$i]->DTSTART;
				if(days($e[$i]->DTSTART)) {
					$e[$i]->DTSTART = date('Ymd', strtotime($e[$i]->DTSTART .' +1 day')) . "T0000Z";//$e[$i]->DTSTART = next day 00.00;
					$date = $e[$i]->DTSTART;
				}
				for ( ; $date < $e[$i]->DTEND; date('Ymd', strtotime($date .' +1 day'))) {
					if (days($date)) {
						if ($e[$i]->DTEND == $date && $e[$i]->DTSTART == $date) {
						 unset($e[$i]);
						} else if ($e[$i]->DTEND == $date) {
							$e[$i]->DTEND = date('Ymd', strtotime($e[$i]->DTEND .' -1 day')) . "T2359Z";//$e[$i]->DTEND = previous day 23.59
						}
						else {
							$e[$i]->DTSTART = date('Ymd', strtotime($e[$i]->DTSTART .' +1 day')) . "T0000Z";//$e[$i]->DTSTART next day 00.00
						}
					}
				}				
			}
		}
	}
	
	//Denna for loop fixar sömnschema
	for ($i = 0; $i < count($e); $i++) {
		if ($e[$i]->AVAILABLE) {
			if ($e[$i]->DTSTART < $collection->sleepfrom && $e[$i]->DTEND > $collection->sleepfrom && $e[$i]->DTEND <= $collection->sleepto) {
				$e[$i]->DTEND = $collection->sleepfrom;
			} else if ($e[$i]->DTSTART >= $collection->sleepfrom && $e[$i]->DTEND <= $collection->sleepto) {
				unset($e[$i]);
			} else if ($e[$i]->DTSTART >= $collection->sleepfrom && $e[$i]->DTSTART < $collection->sleepto && $e[$i]->DTEND > $collection->sleepto) {
				$e[$i]->DTSTART = $collection->sleepto;
			} else if ($e[$i]->DTSTART < $collection->sleepfrom && $e[$i]->DTEND > $collection->sleepto) {
				//Splitta och behåll efter samt innan sova
				$avEvent = $e[$i];
				$avEvent->DTSTART = $collection->sleepto;
				$e[$i]->DTEND = $collection->sleepfrom;
				array_splice($e, $i+1, 0, $avEvent);
			}
		}
	}
	
	$firstEvent = true;
	$lastEvent;
	//Denna loop fixar restider
	for($i = 0; $i < count($e) ; $i++) {
		if (!$e[$i]->AVAILABLE) {
			if ($firstEvent) {
				//lägg restid innan första event
				findAvailBetween(0,$i,0, $collection->traveltime, $e);
			}
			for ($y = $i; $y < count($e); $y++) {
				if(!$e[$y]->AVAILABLE) {
					//jämföra ny dag
					if(date('Ymd', strtotime($e[$i]->DTEND) !== date('Ymd', strtotime($e[$y]->DTSTART)))){		
						findAvailBetween($i,$y,$collection->traveltime, $collection->traveltime, $e);	
					}
					//jämföra om det är samma sorts event (skola å skola eller samma habit å habit
					else if(($e[$i]->SUMMARY == $e[$y]->SUMMARY) || (preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$i]->SUMMARY) == preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$y]->SUMMARY))){
						//leave as is
					}
					//om det inte är samma sort, lägg restid mellan
					else{
					findAvailBetween($i,$y,$collection->traveltime, $collection->traveltime, $e);
					} 
					$lastEvent = false;
				}
			}
			if ($lastEvent) {
				findAvailBetween($i,count($e)-1,$collection->traveltime, 0, $e);//lägg restid efter sista event
			}
			$lastEvent = true;
			$firstEvent = false;
		}
	}
	
	//Denna loop fixar pauser
	for($i = 0; $i < count($e); $i++){
		if($e[$i]->AVAILABLE){
			// if DTSTART - DTEND > studylength
			if((strtotime($e[$i]->DTEND)-strtotime($e[$i]->DTSTART))/60 > $collection->studylength){
				// Splitta upp i en före och en efter med breaktime mellanrum
				$event2 = $e[$i];
				$e[$i]->DTEND = // $e[$i]-> DTEND = DTSTART + studylength  
				$event2->DTSTART = // $e[$i]->DTEND + breaktime
				array_splice($e, $i+1, 0, $event2);
			}
		}
	}
}
// Hittar, klipper till och/eller tar bort events för restiden i schemat
function findAvailBetween($i,$y,$ttime1,$ttime2, $e){
	$pause1end = $e[$i]->DTSTART; // + $ttime1
	$pause2start = $e[$y]->DTSTART; // - $ttime2
	for($x = $i; $x < $y; $x++){
		$u = false;
		if($e[$x]->DTSTART >= $e[$i]->DTEND && $e[$x]->DTEND <= $pause1end){ // Om avail är innuti restiden
			unset($e[$x]);
			$u = true;
			$x--;
		}
		if($e[$x]->DTSTART < $pause1end && $e[$x]->DTEND > $pause1end && !$u){  
			$e[$x]->DTSTART = $pause1end;
		}
		if($e[$x]->DTEND > $pause2start && $e[$x]->DTSTART >= $pause2start && !$u) {
			unset($e[$x]);
			$u = true;
			$x--;
		}
		if ($e[$x]->DTEND > $pause2start && $e[$x]->DTEND <= $e[$y]->DTSTART && !$u) {
			$e[$x]->DTEND = $pause2start;
		}
	}	
}












?>