<?php

function analyze ($events) {
	$e = json_decode($events); //Decode to array of objects

	$collection = json_decode(file_get_contents("Collection.txt")); //Tar emot personlig data fr�n fil - ska bli databas
	
	$sleepfrom = str_replace(":", "", $collection->sleepfrom); //Fr�n m�jligt 00:00 format till 0000 format
	$sleepto = str_replace(":", "", $collection->sleepto);
	
	function days($d, $collection) { //Tar emot dagens datum och returnerar true om man inte vill plugga den dagen, false om man vill
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
		if (in_array(date('l', strtotime($d)), $wD)) {
			return true;
		}
		return false;
	}	

	$count = count($e);
	//Denna for loop fixar dagar man ej vill plugga
	for ($i = 0; $i < $count; $i++) {
		if ($e[$i]->AVAILABLE) {
			if (days($e[$i]->DTSTART, $collection) && days($e[$i]->DTEND, $collection)) {
				unset($e[$i]);
				$e = array_values($e);
			} else {
				$date = $e[$i]->DTSTART;
				if(days($e[$i]->DTSTART, $collection)) {
					$e[$i]->DTSTART = date('Ymd', strtotime($e[$i]->DTSTART .' +1 day')) . "T0000Z";//$e[$i]->DTSTART = next day 00.00;
				}
				$date = $e[$i]->DTSTART;
				for ( ; $date < $e[$i]->DTEND; ) {
					if (days($date, $collection)) {
						if (substr($e[$i]->DTEND, 0, 8) == substr($date, 0, 8) && substr($e[$i]->DTSTART, 0, 8) == substr($date, 0, 8)) {
						 unset($e[$i]);
						 $e = array_values($e);
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
		$count = count($e);
	}

	//Denna for loop fixar s�mnschema
	for ($i = 0; $i < $count; $i++) {
		if ($e[$i]->AVAILABLE) {
			$t = 0;
			while ($t <= 1) {
				if ($sleepfrom > 2400 && $sleepto > 2400) {
					$sleepfrom = substr($sleepfrom, 9, 4);
					$sleepto = substr($sleepto, 9, 4);
				}
				if ($t == 0) {
					$sleepfrom = date('Ymd', strtotime(substr($e[$i]->DTSTART, 0, 8) . "-1 day")) . "T" . $sleepfrom . "Z";
					$sleepto = date('Ymd', strtotime(substr($e[$i]->DTSTART, 0, 8))) . "T" . $sleepto . "Z";
				} else {
					$sleepfrom = date('Ymd', strtotime(substr($e[$i]->DTSTART, 0, 8))) . "T" . $sleepfrom . "Z";
					$sleepto = date('Ymd', strtotime(substr($e[$i]->DTSTART, 0, 8). "+1 day")) . "T" . $sleepto . "Z";
				}
				
				if ($e[$i]->DTSTART < $sleepfrom && $e[$i]->DTEND > $sleepfrom && $e[$i]->DTEND <= $sleepto) {
					$e[$i]->DTEND = $sleepfrom;
				} else if ($e[$i]->DTSTART >= $sleepfrom && $e[$i]->DTEND <= $sleepto) {
					unset($e[$i]);
					$e = array_values($e);
				} else if ($e[$i]->DTSTART >= $sleepfrom && $e[$i]->DTSTART < $sleepto && $e[$i]->DTEND > $sleepto) {
					$e[$i]->DTSTART = $sleepto;
				} else if ($e[$i]->DTSTART < $sleepfrom && $e[$i]->DTEND > $sleepto) {
					//Splitta och beh�ll efter samt innan sova
					$avEvent = new stdClass();
					$avEvent->AVAILABLE = $e[$i]->AVAILABLE;
					$avEvent->DTEND = $e[$i]->DTEND;
					$avEvent->SUMMARY = $e[$i]->SUMMARY;
					$avEvent->DESCRIPTION = $e[$i]->DESCRIPTION;
					$avEvent->LOCATION = $e[$i]->LOCATION;
					$avEvent->UID = $e[$i]->UID;
					$avEvent->DTSTART = $sleepto;
					$e[$i]->DTEND = $sleepfrom;
					array_splice($e, $i+1, 0, array($avEvent)); 
				}
				$t++;
			}
		}
		$count = count($e);
	}
	
	$firstEvent = true;
	$lastEvent;
	//Denna loop fixar restider
	for($i = 0; $i < $count ; $i++) {
	
		if (!$e[$i]->AVAILABLE) {
			$ti;
			if(preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$i]->SUMMARY)){
				$ti = $collection->traveltime;
			} 
			else{
				$ti = $e[$i]->DESCRIPTION;
			}
			if ($firstEvent){
				//l�gg restid innan f�rsta event
				findAvailBetween(0, $i,0, $ti, $e);
			}
			for ($y = $i; $y < $count; $y++) {
				if(!$e[$y]->AVAILABLE) {
					$ty;
					if(preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$y]->SUMMARY)){ //Om det finns en kurskod inom parentes, har vi restid �r den inmatade
						$ty = $collection->traveltime;
					} 
					else{ //Annars ska restiden finnas i description (f�r habit)
						$ty = $e[$y]->DESCRIPTION;
					}
					//j�mf�ra ny dag
					if(date('Ymd', strtotime($e[$i]->DTEND) !== date('Ymd', strtotime($e[$y]->DTSTART)))){		
						findAvailBetween($i,$y,$ti, $ty, $e);	
					}
					//j�mf�ra om det �r samma sorts event (skola � skola eller samma habit � habit
					else if(($e[$i]->SUMMARY == $e[$y]->SUMMARY) || (preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$i]->SUMMARY) == preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$y]->SUMMARY))){
						//leave as is
					}
					//om det inte �r samma sort, l�gg restid mellan
					else{
					findAvailBetween($i,$y, $ti, $ty, $e);
					} 
					$lastEvent = false;
				}
				$count = count($e);
			}
			if ($lastEvent) {
				findAvailBetween($i,count($e)-1,$ti, 0, $e);//l�gg restid efter sista event
			}
			$lastEvent = true;
			$firstEvent = false;
		}
		$count = count($e);
	}
	
	//Denna loop fixar pauser
	for($i = 0; $i < $count; $i++){
		echo $i . " | " . $count . "<br>";
		if($e[$i]->AVAILABLE){
			// if DTSTART - DTEND > studylength
			if((strtotime($e[$i]->DTEND)-strtotime($e[$i]->DTSTART))/60 > $collection->studylength){
				// Splitta upp i en f�re och en efter med breaktime mellanrum
					$avEvent = new stdClass();
					$avEvent->AVAILABLE = $e[$i]->AVAILABLE;
					$avEvent->DTEND = $e[$i]->DTEND;
					$avEvent->SUMMARY = $e[$i]->SUMMARY;
					$avEvent->DESCRIPTION = $e[$i]->DESCRIPTION;
					$avEvent->LOCATION = $e[$i]->LOCATION;
					$avEvent->UID = $e[$i]->UID;
					$avEvent->DTSTART = $e[$i]->DTSTART + $collection->breaktime + $collection->studylength; //+studylength+breaktime
					$e[$i]->DTEND = $e[$i]->DTSTART + $collection->breaktime; // +studylength
					array_splice($e, $i+1, 0, array($avEvent)); 
			}                                                                
		}
		$count = count($e);
	}
	return $e;
}
// Hittar, klipper till och/eller tar bort events f�r restiden i schemat
function findAvailBetween($i,$y,$ttime1,$ttime2, $e){
	$pause1end = $e[$i]->DTSTART; // + $ttime1
	$pause2start = $e[$y]->DTSTART; // - $ttime2
	for($x = $i; $x < $y; $x++){
		$u = false;
		if($e[$x]->DTSTART >= $e[$i]->DTEND && $e[$x]->DTEND <= $pause1end){ // Om avail �r innuti restiden
			unset($e[$x]);
			$e = array_values($e);
			$u = true;
			$x--;
		}
		if($e[$x]->DTSTART < $pause1end && $e[$x]->DTEND > $pause1end && !$u){  
			$e[$x]->DTSTART = $pause1end;
		}
		if($e[$x]->DTEND > $pause2start && $e[$x]->DTSTART >= $pause2start && !$u) {
			unset($e[$x]);
			$e = array_values($e);
			$u = true;
			$x--;
		}
		if ($e[$x]->DTEND > $pause2start && $e[$x]->DTEND <= $e[$y]->DTSTART && !$u) {
			$e[$x]->DTEND = $pause2start;
		}
	}	
}

$a = analyze('[{"SUMMARY":"Gymma","DTSTART":"20170421T0000Z","DTEND":"20170421T1000Z","UID":"2017042119:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170421T1000Z","DTEND":"20170421T1400Z","UID":"2017042119:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170421T1400Z","DTEND":"20170421T1500Z","UID":"2017042119:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170421T1500Z","DTEND":"20170422T0100Z","UID":"2017042119:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170422T0200Z","DTEND":"20170422T0800Z","UID":"2017042219:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170422T0800Z","DTEND":"20170422T1500Z","UID":"2017042219:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170422T1500Z","DTEND":"20170422T1700Z","UID":"2017042219:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170422T1700Z","DTEND":"20170423T1100Z","UID":"2017042319:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170423T1100Z","DTEND":"20170423T2100Z","UID":"2017042319:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170423T2100Z","DTEND":"20170424T0500Z","UID":"2017042319:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170424T0500Z","DTEND":"20170424T1100Z","UID":"2017042419:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170424T1100Z","DTEND":"20170424T2100Z","UID":"2017042419:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170424T2100Z","DTEND":"20170424T2200Z","UID":"2017042419:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false},{"SUMMARY":"Gymma","DTSTART":"20170424T2200Z","DTEND":"20170425T1500Z","UID":"2017042519:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":true},{"SUMMARY":"Gymma","DTSTART":"20170425T1500Z","DTEND":"20170425T2100Z","UID":"2017042519:00","DESCRIPTION":"15","LOCATION":"gymmet","AVAILABLE":false}]');
echo json_encode($a);
?>