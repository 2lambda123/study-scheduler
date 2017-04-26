<?php

/*** ICS IS EXTREMELY SENSITIVE TO LINE BREAKS AND WHITESPACES ***/
function export($json_string, $file){ //takes a massive json string and writes to the file "calendar.ics" in ics format.
  $cal = fopen("calendar.ics", "w+");
  getHeader($cal);
  getEvents($json_string, $cal);
  fclose($cal);
}

function getHeader($cal){ // necessary header for ics
  fwrite($cal, "BEGIN:VCALENDAR\nVERSION:2.0\n");
}

function getEvents($json_string, $cal){
  $fields = array("SUMMARY", "DTSTART", "DTEND", "UID", "DESCRIPTION", "LOCATION"); //all the json object fields
  $decoded_string = json_decode($json_string, true); // decodes to $decoded_string which is a 2D array

  foreach($decoded_string as $temp){ // loop through array with json objects
      if ( $temp["AVAILABLE"] == false ){ // if AVAILABLE is false, then add to ics calendar
        fwrite($cal, "BEGIN:VEVENT\n");
        for ($x = 0; $x < count($fields); $x++){ // write every field
          fwrite($cal, $fields[$x] . ":" . $temp[$fields[$x]] . "\n");
        }
        fwrite($cal, "END:VEVENT\n");
      }
  }
  fwrite($cal, "END:VCALENDAR\n"); // necessary end line for ics
}
?>
