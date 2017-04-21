<?php






$collection;
function analyze ($events) {
	$e = json_decode($events);
	$tempStart;
	$tempEnd;
	
	print_r($e);
	$collection = json_decode(file_get_contents("Collection.txt"));
	print_r($collection);
	
	$sleepfrom = str_replace(":", "", $collection->sleepfrom);
	$sleepto = str_replace(":", "", $collection->sleepto);
	
	function days($d, $collection) {
		$wD[] = array();
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
			array_push($wD, 'Friday');
		}
		if (property_exists($collection,"Saturday")) {
			array_push($wD, 'Saturday');
		}
		if (property_exists($collection,"Sunday")) {
			array_push($wD, 'Sunday');
		}
		if (in_array(date('l', strtotime($d)), $wD)) {
			return true;
		}
		return false;
	}	
	
	//Denna for loop fixar dagar man ej vill plugga
	for ($i = 0; $i < count($e); $i++) {
		if ($e[$i]->AVAILABLE) {
			if (days($e[$i]->DTSTART, $collection) && days($e[$i]->DTEND, $collection)) {
				echo date('l', strtotime($e[$i]->DTSTART)) . " <br>";
				print_r($e[$i]);
				echo " borttagen <br>";
				unset($e[$i]);
			} else {
				$date = $e[$i]->DTSTART;
				if(days($e[$i]->DTSTART, $collection)) {
					echo "hej2";
					$e[$i]->DTSTART = date('Ymd', strtotime($e[$i]->DTSTART .' +1 day')) . "T0000Z";//$e[$i]->DTSTART = next day 00.00;
				}
				$date = $e[$i]->DTSTART;
				for ( ; $date < $e[$i]->DTEND; ) {
					if (days($date, $collection)) {
						if (substr($e[$i]->DTEND, 0, 8) == substr($date, 0, 8) && substr($e[$i]->DTSTART, 0, 8) == substr($date, 0, 8)) {
						 unset($e[$i]);
						} else if (substr($e[$i]->DTEND, 0, 8) == substr($date, 0, 8)) {
							$e[$i]->DTEND = date('Ymd', strtotime($e[$i]->DTEND .' -1 day')) . "T2359Z";//$e[$i]->DTEND = previous day 23.59
						}
						else {
							$e[$i]->DTSTART = date('Ymd', strtotime($e[$i]->DTSTART .' +1 day')) . "T0000Z";//$e[$i]->DTSTART next day 00.00
						}
					
					}
					$date = date('Ymd', strtotime($date .' +1 day'));
					$date = $date . "T0000Z";
				}				
			}
		}
	}
	//Denna for loop fixar sömnschema
	for ($i = 0; $i < count($e); $i++) {	
		if ($e[$i]->AVAILABLE) {
			echo substr($e[$i]->DTSTART, 9, 4) . " >= " . $sleepfrom . " && " . substr($e[$i]->DTSTART, 9, 4) . " < " . $sleepto . " && " . substr($e[$i]->DTEND, 9, 4) . " > " . $sleepto . "<br><br>";
			if (substr($e[$i]->DTSTART, 9, 4) < $sleepfrom && substr($e[$i]->DTEND, 9, 4) > $sleepfrom && substr($e[$i]->DTEND, 9, 4) <= $sleepto) {
				$e[$i]->DTEND = $sleepfrom;
			} else if (substr($e[$i]->DTSTART, 9, 4) >= $sleepfrom && substr($e[$i]->DTEND, 9, 4) <= $sleepto) {
				unset($e[$i]);
			} else if (substr($e[$i]->DTSTART, 9, 4) >= $sleepfrom && substr($e[$i]->DTSTART, 9, 4) < $sleepto && substr($e[$i]->DTEND, 9, 4) > $sleepto) {
				$e[$i]->DTSTART = $sleepto;
			} else if (substr($e[$i]->DTSTART, 9, 4) < $sleepfrom && substr($e[$i]->DTEND, 9, 4) > $sleepto) {
				//Splitta och behåll efter samt innan sova
				$avEvent = $e[$i];
				$avEvent->DTSTART = $sleepto;
				$e[$i]->DTEND = $sleepfrom;
				array_splice($e, $i+1, 0, $avEvent);
			}
			print_r($e[$i]);
		}
	}
	
	$firstEvent = true;
	$lastEvent;
	//Denna loop fixar restider
	for($i = 0; $i < count($e) ; $i++) {
	
		if (!$e[$i]->AVAILABLE) {
			$ti;
			if(preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$i]->SUMMARY)){
				$ti = $collection->traveltime;
			} 
			else{
				$ti = $e[i]->DESCRIPTION;
			}
			if ($firstEvent){
				//lägg restid innan första event
				findAvailBetween(0, $i,0, $ti, $e);
			}
			for ($y = $i; $y < count($e); $y++) {
				if(!$e[$y]->AVAILABLE) {
					$ty;
					if(preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$y]->SUMMARY)){
						$ty = $collection->traveltime;
					} 
					else{
						$ty = $e[y]->DESCRIPTION;
					}
					//jämföra ny dag
					if(date('Ymd', strtotime($e[$i]->DTEND) !== date('Ymd', strtotime($e[$y]->DTSTART)))){		
						findAvailBetween($i,$y,$ti, $ty, $e);	
					}
					//jämföra om det är samma sorts event (skola å skola eller samma habit å habit
					else if(($e[$i]->SUMMARY == $e[$y]->SUMMARY) || (preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$i]->SUMMARY) == preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$y]->SUMMARY))){
						//leave as is
					}
					//om det inte är samma sort, lägg restid mellan
					else{
					findAvailBetween($i,$y, $ti, $ty, $e);
					} 
					$lastEvent = false;
				}
			}
			if ($lastEvent) {
				findAvailBetween($i,count($e)-1,$ti, 0, $e);//lägg restid efter sista event
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
	return $e;
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

echo analyze('[{"SUMMARY":"Gymma","DTSTART":"20170421T0000Z","DTEND":"20170421T1000Z","UID":"2017042119:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170421T1000Z","DTEND":"20170421T1400Z","UID":"2017042119:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170421T1400Z","DTEND":"20170421T1500Z","UID":"2017042119:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170421T1500Z","DTEND":"20170422T0100Z","UID":"2017042119:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170422T0200Z","DTEND":"20170422T0800Z","UID":"2017042219:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170422T0800Z","DTEND":"20170422T1500Z","UID":"2017042219:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170422T1500Z","DTEND":"20170422T1700Z","UID":"2017042219:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170422T1700Z","DTEND":"20170423T1100Z","UID":"2017042319:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170423T1100Z","DTEND":"20170423T2100Z","UID":"2017042319:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170423T2100Z","DTEND":"20170424T0500Z","UID":"2017042319:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170424T0500Z","DTEND":"20170424T1100Z","UID":"2017042419:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170424T1100Z","DTEND":"20170424T2100Z","UID":"2017042419:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170424T2100Z","DTEND":"20170424T2200Z","UID":"2017042419:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170424T2200Z","DTEND":"20170425T1500Z","UID":"2017042519:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170425T1500Z","DTEND":"20170425T2100Z","UID":"2017042519:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false}]');
?>