<?php
/*** ICS IS EXTREMELY SENSITIVE TO LINE BREAKS AND WHITESPACES ***/
function export($json_encoded){ //takes an encoded json and writes to the file "calendar.ics" in ics format.
  $cal = fopen("calendar.ics", "w+");
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
        fwrite($cal, "BEGIN:VEVENT\n");
        for ($x = 0; $x < count($fields); $x++){ // write every field
            if ( (strcmp($fields[$x], "DTSTART") == 0 || strcmp($fields[$x], "DTEND") == 0)  && strlen($temp[$fields[$x]]) <= 14) // time cannot be viewed if two zeros are missing before 'Z'
              fwrite($cal, $fields[$x] . ":" . substr_replace($temp[$fields[$x]], "00", -1, 0) . "\n");

            else
            fwrite($cal, $fields[$x] . ":" . $temp[$fields[$x]] . "\n");
        }
        fwrite($cal, "END:VEVENT\n");
      }
  }
  fwrite($cal, "END:VCALENDAR\n"); // necessary end line for ics
}
?>
