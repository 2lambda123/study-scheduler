<!DOCTYPE html>
<html>
<body>

<?php

function export($json_array, $file){ //takes a json array and writes to the file "calendar.ics" in ics format.
  $cal = fopen($file, "a+");
  getHeader($cal);
  getEvents($json_array, $cal);
  fclose($cal);
}

function getHeader($cal){ // necessary header for ics
  fwrite($cal, "BEGIN:VCALENDAR\nVERSION:2.0 \n");
}

function getEvents($json_array, $cal){
  $fields = array("AVAILABLE", "SUMMARY", "DTSTART", "DTEND", "UID", "DESCRIPTION", "LOCATION"); //all the json object fields

  foreach($json_array as $temp){ // loop through array with json objects
    $decoded_temp = json_decode($temp, true); // decode each json object

      if ( $decoded_temp["AVAILABLE"] == false ){ // if AVAILABLE is false, then add to ics calendar
        fwrite($cal, "BEGIN:VEVENT \n");
        for ($x = 1; $x < count($fields); $x++){ // write every field
          fwrite($cal, $fields[$x] . ":" . $decoded_temp[$fields[$x]] . "\n");
        }
        fwrite($cal, "END:VEVENT \n");
      }
  }
  fwrite($cal, "END:VCALENDAR \n"); // necessary end line for ics
}
?>

</body>
</html>
