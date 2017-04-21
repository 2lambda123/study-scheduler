<?php

class event {
	public $SUMMARY = NULL;
	public $DTSTART = NULL;
	public $DTEND = NULL;
	public $UID = NULL;
	public $DESCRIPTION = NULL;
	public $LOCATION = NULL;
}

$h = $_POST;
$events;
$x = $h['duration'];

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

if ($h['repetition'] == "Daily") {
	$d = getdate()['year'] . "0" . getdate()['mon'] . getdate()['mday'];
	for ($i = 0; $i < $x; $i++) {
		$events[] = new event();
		$events[$i]->SUMMARY = $h['name'];
		$events[$i]->DESCRIPTION = $h['travel'];
		$events[$i]->LOCATION = $h['location'];
		$events[$i]->DTSTART = $d . "T" . str_replace(":", "", $h['dtstart']) . "Z";
		$events[$i]->DTEND = $d . "T" . str_replace(":", "", $h['dtend']) . "Z";
		$events[$i]->UID = $d . $h['dtstart'];
		$d = date('Ymd', strtotime($d . "+1 day"));
	}
} else if ($h['repetition'] == "Weekly") {
	$rep = (count($wD)-1)*$x;
	$d = getdate()['year'] . "0" . getdate()['mon'] . getdate()['mday'];
	for ($i = 0; $i < $rep;) {
		if (in_array(date('l', strtotime($d)), $wD)) {
			$events[] = new event();
			$events[$i]->SUMMARY = $h['name'];
			$events[$i]->DESCRIPTION = $h['travel'];
			$events[$i]->LOCATION = $h['location'];
			$events[$i]->DTSTART = $d . "T" . str_replace(":", "", $h['dtstart']) . "Z";
			$events[$i]->DTEND = $d . "T" . str_replace(":", "", $h['dtend']) . "Z";
			$events[$i]->UID = $d . $h['dtstart'];
			$i++;
		}
		$d = date('Ymd', strtotime($d . "+1 day"));
	}
}

$e = json_encode($events);
echo $e

?>