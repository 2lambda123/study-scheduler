<?php

function analyze ($events) {
	$e = json_decode($events); //Decode to array of objects

	$collection = json_decode(file_get_contents("Collection.txt")); //Tar emot personlig data fr�n fil - ska bli databas

	$sleepfrom = str_replace(":", "", $collection->sleepfrom); //Från möjligt 00:00 format till 0000 format
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
			if (days($e[$i]->DTSTART, $collection) && days($e[$i]->DTEND, $collection)) { //Om ett event bara pågår under dagar man ej vill plugga
				unset($e[$i]);
				$e = array_values($e);
			} else {
				$date = $e[$i]->DTSTART;
				if(days($e[$i]->DTSTART, $collection)) { //Om ett event börjar på en dag man ej vill plugga
					$e[$i]->DTSTART = date('Ymd', strtotime($e[$i]->DTSTART .' +1 day')) . "T0000Z";//$e[$i]->DTSTART = next day 00.00;
				}
				$date = $e[$i]->DTSTART;
				for ( ; $date < $e[$i]->DTEND; ) {
					if (days($date, $collection)) {
						if (substr($e[$i]->DTEND, 0, 8) == substr($date, 0, 8) && substr($e[$i]->DTSTART, 0, 8) == substr($date, 0, 8)) {  //Om ett event bara pågår under en dag man ej vill plugga
						 unset($e[$i]);
						 $e = array_values($e);
						} else if (substr($e[$i]->DTEND, 0, 8) == substr($date, 0, 8)) { //Om ett event slutar under en dag man ej vill plugga
							$e[$i]->DTEND = date('Ymd', strtotime($e[$i]->DTEND .' -1 day')) . "T2359Z";//$e[$i]->DTEND = previous day 23.59
						}
						else { //Annars, om ett event börjar en dag man ej vill plugga
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

	//Denna for loop fixar sömnschema
	for ($i = 0; $i < $count; $i++) {
		if ($e[$i]->AVAILABLE) {
		
			if ($sleepfrom < $sleepto) { //Om man sover från tidigt på morgonen till senare på dagen
				if ($sleepfrom > 2400 && $sleepto > 2400) { //Tar bort datum och sparar sovtider igen
					$sleepfrom = substr($sleepfrom, 9, 4);
					$sleepto = substr($sleepto, 9, 4);
				}
				$sleepfrom = substr($e[$i]->DTSTART, 0, 8) . "T" . $sleepfrom . "Z";
				$sleepto = substr($e[$i]->DTSTART, 0, 8) . "T" . $sleepto . "Z";

				if ($e[$i]->DTSTART < $sleepfrom && $e[$i]->DTEND > $sleepfrom && $e[$i]->DTEND <= $sleepto) { //Om ett event börjar innan man sover och slutar under tiden man sover
					$e[$i]->DTEND = $sleepfrom;
				} else if ($e[$i]->DTSTART >= $sleepfrom && $e[$i]->DTEND <= $sleepto) { //Om ett event bara går medans man sover
					unset($e[$i]);
					$e = array_values($e);
				} else if ($e[$i]->DTSTART >= $sleepfrom && $e[$i]->DTSTART < $sleepto && $e[$i]->DTEND > $sleepto) { //Om ett event börjar innan man vaknar och fortsätter efter man vaknat
					$e[$i]->DTSTART = $sleepto;
				} else if ($e[$i]->DTSTART < $sleepfrom && $e[$i]->DTEND > $sleepto) { //Om ett event börjar innan man somnar och slutar efter men vaknat
					//Splitta och behåll efter samt innan sova
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
				
			} else {
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
						//Splitta och behåll efter samt innan sova
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
					$t++;
				}
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
				//l�gg restid innan första event
				findAvailBetween(0, $i,0, $ti, $e);
			}
			for ($y = $i; $y < $count; $y++) {
				if(!$e[$y]->AVAILABLE) {
					$ty;
					if(preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$y]->SUMMARY)){ //Om det finns en kurskod inom parentes, har vi restid är den inmatade
						$ty = $collection->traveltime;
					}
					else{ //Annars ska restiden finnas i description (f�r habit)
						$ty = $e[$y]->DESCRIPTION;
					}
					//jämföra ny dag
					if(date('Ymd', strtotime($e[$i]->DTEND) !== date('Ymd', strtotime($e[$y]->DTSTART)))){
						findAvailBetween($i,$y,$ti, $ty, $e);
					}
					//jämföra om det är samma sorts event (skola & skola eller samma habit & habit
					else if(($e[$i]->SUMMARY == $e[$y]->SUMMARY) || (preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$i]->SUMMARY) == preg_match('(\([A-Z][A-Z]\d\d\d\d\))', $e[$y]->SUMMARY))){
						//leave as is
					}
					//om det inte är samma sort, l�gg restid mellan
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
		if($e[$i]->AVAILABLE){
			// if DTSTART - DTEND > studylength
			if((strtotime($e[$i]->DTEND)-strtotime($e[$i]->DTSTART))/60 > $collection->studylength){
				// Splitta upp i en före och en efter med breaktime mellanrum
					$avEvent = new stdClass();
					$avEvent->SUMMARY = $e[$i]->SUMMARY;
					$avEvent->DTSTART = str_replace(":", "T", date("Ymd:Hi", strtotime( "+" . $collection->studylength+$collection->breaktime . " minutes", strtotime(substr($e[$i]->DTSTART, 0, 8) . substr($e[$i]->DTSTART, 9, 4))))) . "Z"; //+studylength+breaktime
					$avEvent->DTEND = $e[$i]->DTEND;
					$avEvent->UID = $e[$i]->UID; //fixa unikt id när vi skaffat databas
					$avEvent->DESCRIPTION = $e[$i]->DESCRIPTION;
					$avEvent->LOCATION = $e[$i]->LOCATION;
					$avEvent->AVAILABLE = $e[$i]->AVAILABLE;
					
					$e[$i]->DTEND = str_replace(":", "T", date("Ymd:Hi", strtotime( "+" . $collection->studylength . " minutes", strtotime(substr($e[$i]->DTSTART, 0, 8) . substr($e[$i]->DTSTART, 9, 4))))) . "Z"; // +studylength
					//specialfall för att undvika events som slutar innan eller samtidigt som när den börja
					if($avEvent -> DTSTART < $avEvent -> DTEND){
							array_splice($e, $i+1, 0, array($avEvent));
					}
			}
		}
		$count = count($e);
	}
	return $e;
}
// Hittar, klipper till och/eller tar bort events för restiden i schemat
function findAvailBetween($i,$y,$ttime1,$ttime2, $e){
	$pause1end = str_replace(":", "T", date("Ymd:Hi", strtotime( "+" . $ttime1 . " minutes", strtotime(substr($e[$i]->DTEND, 0, 8) . substr($e[$i]->DTEND, 9, 4))))) . "Z";; // + $ttime1
	$pause2start = str_replace(":", "T", date("Ymd:Hi", strtotime( "+" . $ttime2 . " minutes", strtotime(substr($e[$y]->DTSTART, 0, 8) . substr($e[$y]->DTSTART, 9, 4))))) . "Z"; // - $ttime2
	for($x = $i; $x < $y; $x++){
		if ($e[$x]->AVAILABLE) {
			$u = false;
			if($e[$x]->DTSTART >= $e[$i]->DTEND && $e[$x]->DTEND <= $pause1end){ // Om avail �r innuti restiden
				unset($e[$x]);
				$e = array_values($e);
				$u = true;
				$x--;
			}	//Om avail börjar innan men rest klart
			if($e[$x]->DTSTART < $pause1end && $e[$x]->DTEND > $pause1end && !$u){
				$e[$x]->DTSTART = $pause1end;
			} //Om avail är inom andra restiden
			if($e[$x]->DTEND > $pause2start && $e[$x]->DTSTART >= $pause2start && !$u) {
				unset($e[$x]);
				$e = array_values($e);
				$u = true;
				$x--;
			} //Om avail slutar efter man måste rest till nästa !avail event
			if ($e[$x]->DTEND > $pause2start && $e[$x]->DTEND <= $e[$y]->DTSTART && !$u) {
				$e[$x]->DTEND = $pause2start;
			}
		}
	}
}
?>
