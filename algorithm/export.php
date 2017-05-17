<?php
/*** ICS IS EXTREMELY SENSITIVE TO LINE BREAKS AND WHITESPACES ***/
/*
   INPUT: json_encoded calendar, session uuid
   OUTPUT: Updates the calendar file stored in /site/.. associated with the uuid,
   or if such a file doesn't exist it creates one.
*/
//takes an encoded json and the users ID writes to a personal file "calendar" + ID + ".ics" in ics format.
function export($json_encoded, $sessID){
  $cal = fopen("../userStorage/calendar_" . $sessID . ".ics", "w+");
  getHeader($cal);
  getEvents($json_encoded, $cal);
  fclose($cal);
}

function getHeader($cal){ // necessary header for ics
  fwrite($cal, "BEGIN:VCALENDAR\nVERSION:2.0\n");
}

function getEvents($json_encoded, $cal){
  $fields = array("SUMMARY", "DTSTART", "DTEND", "UID", "DESCRIPTION", "LOCATION"); //all the json object fields
  $json_decoded = json_decode($json_encoded, true); // decodes to $json_decoded which is a 2D array
  foreach($json_decoded as $temp){ // loop through array with json objects
      if ( $temp["AVAILABLE"] == false ){ // if AVAILABLE is false, then add to ics calendar
		if(isset($temp['NOTES'])) $temp['DESCRIPTION'] .= $temp['NOTES'];
        fwrite($cal, "BEGIN:VEVENT\n");
        for ($x = 0; $x < count($fields); $x++){ // write every field
            if ( (strcmp($fields[$x], "DTSTART") == 0 || strcmp($fields[$x], "DTEND") == 0)){ // time cannot be viewed if two zeros are missing before 'Z'
              // -2 hours: GMT +1 -> UTC
              $dt = $temp[$fields[$x]];
              $hours = (int) substr($dt, 9, 2);
              $hours -= 2;
              if ($hours < 10){
                $hours = "0" . $hours;
              }

              $temp[$fields[$x]] = substr_replace($dt, $hours, 9, 2);

              if (strlen($temp[$fields[$x]]) <= 14)
              fwrite($cal, $fields[$x] . ":" . substr_replace($temp[$fields[$x]], "00", -1, 0) . "\n");

              else
              fwrite($cal, $fields[$x] . ":" . $temp[$fields[$x]] . "\n");

            }
            else
            fwrite($cal, $fields[$x] . ":" . $temp[$fields[$x]] . "\n");
        }
        fwrite($cal, "END:VEVENT\n");
      }
  }
  fwrite($cal, "END:VCALENDAR\n"); // necessary end line for ics
}

?>
