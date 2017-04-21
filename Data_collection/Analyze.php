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
	
	$pEvent;
	$nEvent;
	$firstEvent;
	$lastEvent;
	//Denna loop fixar restider
	for($i = 0; $i < count($e) ; $i++) {
	//Hitta nästa lediga tid, loopa bak och fram till de närliggande olediga tider.
		if ($e[$i]->AVAILABLE) {
			$firstEvent = true;
			$lastEvent = true;
			for ($y = $i; $y => 0; $y--) {
				if(!$e[$y]->AVAILABLE) {
					$pEvent = $e[$y];
					$firstEvent = false;
					break;
				}
			}
			for ($y = $i; $y < count($e); $y++) {
				if(!$e[$y]->AVAILABLE) {
					$nEvent = $e[$y];
					$lastEvent = false;
					break;
				}
			}
			if($firstEvent) {
				
			} else if ($lastEvent) {
				
			} else {
			
			}
		}	
	}
	//Denna loop fixar pauser
}
?>