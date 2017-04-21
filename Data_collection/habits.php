<?php

class event {
	public $SUMMARY = NULL;
	public $DTSTART = NULL;
	public $DTEND = NULL;
	public $UID = NULL;
	public $DESCRIPTION = NULL;
	public $LOCATION = NULL;
}

$wD[] = array();
if (isset($h['Monday'])) {
		array_push($wD, 'Monday');
	}
	if (isset($h['Tuesday'])) {
		array_push($wD, 'Tuesday');
	}
	if (isset($h['Wednesday'])) {
		array_push($wD, 'Wednesday');
	}
	if (isset($h['Thursday'])) {
		array_push($wD, 'Thursday');
	}
	if (isset($h['Friday'])) {
		array_push($wD, 'Friday');
	}
	if (isset($h['Saturday'])) {
		array_push($wD, 'Saturday');
	}
	if (isset($h['Sunday'])) {
		array_push($wD, 'Sunday');
}
	

$h = $_POST;
$events[] = new event;
$x = $h['duration'];
$events;
if ($h['repetition'] == "Daily") {
	$d = getdate()['year'] . getdate()['mon'] . getdate()['mday'];
	for ($i = 0; $i < $x; $i++) {
		$events[$i]->SUMMARY = $h['name'];
		$events[$i]->DESCRIPTION = $h['travel'];
		$events[$i]->LOCATION = $h['location'];
		$events[$i]->DTSTART = $d . "T" . str_replace(":", "", $h['dtstart']) . "Z";
		$events[$i]->DTEND = $d . "T" . str_replace(":", "", $h['dtend']) . "Z";
		$events[$i]->UID = $d . $h['dtstart'];
		$d = date('Ymd', strtotime($d . "+1 day"));
	}
} else if ($h['repetition'] == "Weekly") {
	$rep = count($wD)*$x;;
	$d = getdate()['year'] . "0" . getdate()['mon'] . getdate()['mday'];
	for ($i = 0; $i < $rep;) {
		echo date('l', strtotime($d));
		print_r($wD);
		echo "<br>";
		if (in_array(date('l', strtotime($d)), $wD)) {
			$events[$i]->SUMMARY = $h['name'];
			$events[$i]->DESCRIPTION = $h['travel'];
			$events[$i]->LOCATION = $h['location'];
			$events[$i]->DTSTART = $d . "T" . str_replace(":", "", $h['dtstart']) . "Z";
			$events[$i]->DTEND = $d . "T" . str_replace(":", "", $h['dtend']) . "Z";
			$events[$i]->UID = $d . $h['dtstart'];
			//$i++;
			echo $i;
		}
		$i++;
		$d = date('Ymd', strtotime($d . "+1 day"));
	}
}

print_r($events);
?>