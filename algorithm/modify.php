<?php
include_once '../algorithm/distribute.php';
// INPUT: encoded calendar, encoded array of events(that is ordered). OUTPUT: encoded calendar with events in right position
function modify($calendar_encoded, $events_encoded){
  $calendar_decoded = json_decode($calendar_encoded, true);
  $events = json_decode($events_encoded, true);

  $count = count($calendar_decoded);
  if (strcmp(key($events), "SUMMARY") === 0) { // single event
    $event = substr($events["DTSTART"], 0, 13);
    $pos = binarySearch($event, $calendar_decoded, 0, $count-1, 13); // find index where array of events should be inserted

    if ($pos <= 0){ // if event should be first in calendar
      array_splice($calendar_decoded, 0, 0, array($events));
      return json_encode($calendar_decoded);
    }

    else if ($pos == $count-1){ // if event should be last in calendar, place it at the end of calendar
      if (strcmp(substr($events[0]["DTSTART"], 0, 13), substr($calendar_decoded[$pos]["DTSTART"], 0, 13)) > 0)
      {
        array_splice($calendar_decoded, $count, 0, array($events));
        return json_encode($calendar_decoded);
      }
    }

    else {
      array_splice($calendar_decoded, $pos, 0, array($events));
      return json_encode($calendar_decoded);
    }
  }

  else{ // array of events
  $e = 0; //index of events array
  $y = 1; // length to be spliced in events
//while loop for events
  while(isset($events[0]) ){
    $event = substr($events[0]["DTSTART"], 0, 13); // date time
    //binary search
    $pos = binarySearch($event, $calendar_decoded, 0, $count-1, 13); // find index where array of events should be inserted

    if (isset($events[1])){ // there are more than one event in events array
      $event = substr($events[++$e]["DTSTART"], 0, 13); //next event in events

      if ($pos <= 0){ // if event should be first in calendar, compare rest of the events with the event that is currently first in the calendar.
        $pos = 0;
      }

      else if ($pos == $count-1){ // if event should be last in calendar, place it at the end of calendar
        if (strcmp(substr($events[0]["DTSTART"], 0, 13), substr($calendar_decoded[$pos]["DTSTART"], 0, 13)) > 0)
        {
          array_splice($calendar_decoded, $count, 0, $events);
          break;
        }
      }

      else if (!isset($calendar_decoded[$pos])){ // if events are after calendar
        array_splice($calendar_decoded, $count, 0, $events);
        break;
      }

      $strcmp = strcmp($event, substr($calendar_decoded[$pos]["DTSTART"], 0, 13));
      while($strcmp < 0){ // events that fit in between in the calendar.
        $y++;

        if (isset($events[$e+1])){ //there are more events left in event array
          $e++;
          $event = substr($events[$e]["DTSTART"], 0, 13);
          $strcmp = strcmp($event, substr($calendar_decoded[$pos]["DTSTART"], 0, 13));
        }

        else {
          break;
        }
      }

    } //end of if there are more than one event in events array

    else{ //last event in events
      if ($pos < $count-1){
        array_splice($calendar_decoded, $pos, 0, $events);
      }

      else if (strcmp($event, substr($calendar_decoded[$pos]["DTSTART"], 0, 13)) > 0){
        array_splice($calendar_decoded, $count, 0, $events);
      }

      else {
        array_splice($calendar_decoded, $pos, 0, $events);
      }
      break;
    }

      $insert = array_splice($events, 0, $y);
      array_splice($calendar_decoded, $pos, 0, $insert);
      $count += $y; // we have added 'y' elements in calendar -> increase count of calendar
      $y = 1; // reset every time
      $e = 0;

    } // end of while loop for events
  }
  return json_encode($calendar_decoded);
}

?>
