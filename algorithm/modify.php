<?php
//INPUT: encoded calendar and event -> returns encoded calendar with event in right position
function modify($array, $event){ //$array is a massive string. When decoded it will be a 2D array. TODO: $event är en array av flera events i ordning istället
  $event_decoded = json_decode($event, true); //decodes to a 1D array.
  $dtstart =  substr($event_decoded["DTSTART"], 0, 15); //The 15 first chars are to be compared as date-time.(Leaves out 'Z')
  $decoded_array = json_decode($array, true); //2D ARRAY
  $pos = 0;

  while($pos < count($decoded_array)){ // search for position in the 'first dimension'
      if (strcmp(substr($decoded_array[$pos]["DTSTART"], 0, 15), $dtstart) > 0) { // compare date-time
/* insert into $decoded_array(because $array is a string). But $decoded_array is 2D so $event_decoded must be converted to 2D*/
        array_splice($decoded_array, $pos, 0, array($event_decoded));
        return json_encode($decoded_array); // return as a massive string.
      }
      $pos++;
  }
  array_push($decoded_array,$event_decoded);
  return json_encode($decoded_array); // return as a massive string.
}
?>
