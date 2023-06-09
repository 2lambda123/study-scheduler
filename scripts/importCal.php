<?php
function strstr_after($haystack, $needle, $case_insensitive = false) {
    $strpos = ($case_insensitive) ? "stripos" : "strpos";
    $pos = $strpos($haystack, $needle);
    if (is_int($pos)) {
        return substr($haystack, $pos + strlen($needle));
    }
    // Most likely false or null
    return $pos;
}
date_default_timezone_set('UTC');
class event {
	public $SUMMARY = NULL;
	public $DTSTART = NULL;
	public $DTEND = NULL;
	public $UID = NULL;
	public $DESCRIPTION = NULL;
	public $LOCATION = NULL;
  public $AVAILABLE = false;
}
function downloadFile ($fileLink) {
	$file_content = file_get_contents($fileLink);
	$e = importCal($file_content);
	return $e;
}

function importCal ($file_content) {
	$file = explode("BEGIN:VEVENT", $file_content); // Splits the array by ""BEGIN:VEVENT""
  $events = array();

	for ($i = 1; $i < count($file); $i++) {
    array_push($events, new event);
		$file[$i] = "BEGIN:VEVENT:" . $file[$i];      // Replaces the lost "BEGIN:VEVENT"
		$attr = explode("\r\n", $file[$i]);           // Separates the attributes in the events
		//$events[] = new event;
		$sum = "";
		foreach ($attr as $a) {
			if ($sum !== "") {                          // Deletes the first whitespace and puts the events attributes in "$sum"
				if (ltrim($a) !== $a) {
					$a = ltrim($a);
					$sum = $sum . $a;
				}
				else {
					$events[$i-1]->SUMMARY = $sum;
					break;
				}
			}
			if (strstr($a, "SUMMARY:") && $sum == "") {  // Inserts the SUMMARY-attribute
				$sum = strstr_after($a, "SUMMARY:");
			}
		}

		foreach ($attr as $a) {                       // Inserts the DTSTART-attribute
			if (strstr($a, "DTSTART;VALUE=DATE-TIME:")) {
				$sum = strstr_after($a, "DTSTART;VALUE=DATE-TIME:");
				$events[$i-1]->DTSTART = $sum;
			} else if (strstr($a, "DTSTART:")) {
				$sum = strstr_after($a, "DTSTART:");
				$events[$i-1]->DTSTART = $sum;
			}
		}

		foreach ($attr as $a) {                       // Inserts the DTEND-attribute
			if (strstr($a, "DTEND;VALUE=DATE-TIME:")) {
				$sum = strstr_after($a, "DTEND;VALUE=DATE-TIME:");
				$events[$i-1]->DTEND = $sum;
			} else if (strstr($a, "DTEND:")) {
				$sum = strstr_after($a, "DTEND:");
				$events[$i-1]->DTSTART = $sum;
			}
		}

		foreach ($attr as $a) {                       // Inserts the UID-attribute
			if (strstr($a, "UID:")) {
				$sum = strstr_after($a, "UID:");
				$events[$i-1]->UID = $sum;
			}
		}

		$sum = "";
		foreach ($attr as $a) {                       // Inserts DESCRIPTION
			if ($sum !== "") {
				if(ltrim($a) !== $a) {
					$a = ltrim($a);
					$sum = $sum . $a;
				}
				else {
					$events[$i-1]->DESCRIPTION = $sum;
					break;
				}
			}
			if (strstr($a, "DESCRIPTION:") && $sum == "") {
				$sum = strstr_after($a, "DESCRIPTION:");
			}
		}

		$sum = "";
		foreach ($attr as $a) {                             // Inserts LOCATION
			if ($sum !== "") {
				if(ltrim($a) !== $a) {
					$a = ltrim($a);
					$sum = $sum . $a;
				}
				else {
					$events[$i-1]->LOCATION = $sum;
					break;
				}
			}
			if (strstr($a, "LOCATION:") && $sum == "") {
				$sum = strstr_after($a, "LOCATION:");
			}
		}
  }
	$e = json_encode($events);
	return $e;
}
?>
