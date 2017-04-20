<!DOCTYPE html>
<html>
<body>

<?php
/*
$event = '{"SUMMARY":"test3","DTSTART":"20170217T140000", "DTEND":3, "UID":"f3","DESCRIPTION":"TEST3","LOCATION":"kista"}';
$array = ['{"SUMMARY":"test1","DTSTART":"20170117T120000", "DTEND":3, "UID":"f4","DESCRIPTION":"TEST1","LOCATION":"kista"}',
          '{"SUMMARY":"test1","DTSTART":"20170117T140000", "DTEND":3, "UID":"f4","DESCRIPTION":"TEST1","LOCATION":"kista"}'];*/

$event = '{"SUMMARY":"test3","DTSTART":"20170427T115355", "DTEND":3, "UID":"f3","DESCRIPTION":"TEST3","LOCATION":"kista"}';
$array = ['{"SUMMARY":"test1","DTSTART":"20170327T080000", "DTEND":3, "UID":"f4","DESCRIPTION":"TEST1","LOCATION":"kista"}',
        '{"SUMMARY":"test1","DTSTART":"20170327T100000", "DTEND":3, "UID":"f4","DESCRIPTION":"TEST1","LOCATION":"kista"}'];
$ans = modify($array, $event);
var_dump($ans);

function modify($array, $event){ //$array is an array consisting json elements. $event is a json object to be added to $array
  $event_decoded = json_decode($event, true);
  $dtstart =  substr($event_decoded["DTSTART"], 0, 15); //The 15 first chars are to be compared as date-time.(Leaves out 'Z')
  $pos = 0;
  while($pos < count($array)){ // Looping through the json array to find the correct position for $event.
    $temp = json_decode($array[$pos], true); // temporary variable to store the decoded json object.

    if (strcmp(substr($temp["DTSTART"], 0, 15), $dtstart) > 0) { // compare date-time
      array_splice($array, $pos, 0, $event); // insert into $array
      return $array;
    }
    $pos++;
  }
  array_push($array,$event);
  return $array;
}
?>

</body>
</html>
