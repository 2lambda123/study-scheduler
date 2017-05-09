<?php
/*	MAIN FUNCTION IS "schedule_update" WHICH UPDATES THE "CURRENT" SCHEDUAL BY ADDING
    CHANGES FROM KTH-SCHEDUAL AND REDISTRIBUTES COLLISIONS, RETURNS NOTHING */
/*	NOTICE: KTH-EVENTS MAY STILL COLLIDE WITH EACHOTHER IF THEY DO SO IN THE
    ORIGINAL KTH-SCHEDUAL */

include_once '../algorithm/distribute.php';
include_once 'DB.php';
include_once 'importCal.php';

// INPUT: Two colliding events
// OUTPUT: the total time of collision in min
function collisionTime($e1, $e2){
  $start1 = strtotime(substr($e1["DTSTART"], 0, 8).substr($e1["DTSTART"], 9, 4));
  $start2  = strtotime(substr($e2["DTSTART"], 0, 8).substr($e2["DTSTART"], 9, 4));
  $end1 = strtotime(substr($e1["DTEND"], 0, 8).substr($e1["DTEND"], 9, 4));
  $end2  = strtotime(substr($e2["DTEND"], 0, 8).substr($e2["DTEND"], 9, 4));
  $diff1 = $end1 - $start1;
  $diff2 = $end2 - $start2;
  if($diff1 > $diff2){
    return ($diff1 - $diff2)/60;
  }
  else{
    return ($diff2 - $diff1)/60;
  }
}

// INPUT: decoded calendar, length of rescheduled event, position of the rescheduled event in calendar(array index), summary=string
// OUTPUT: returns decoded calendar with the given event distributed on different time(s) within 7 days
function redistribute($calendar_decoded, $minutes, $x, $summary){ //$x = index of removed event(?), $summary = summary of the rescheduled event
  $current_day = $calendar_decoded[$x];
  $weekEnd = nextWeek($calendar_decoded[$x]["DTSTART"] - 1);
  while(strcmp(substr($calendar_decoded[$x]["DTSTART"], 0, 8),$weekEnd) <= 0 && $minutes > 0){ // new time should be within 7 days.
    if ($calendar_decoded[$x]["AVAILABLE"] == true){ // found available time
      $freeTime = timeDiff($calendar_decoded[$x]); // how long is this event?

      //checks if previous event is a study time with same course code and work inserted by us already -> extends that event rather than making a new one
        if (strcmp($calendar_decoded[$x-1]["SUMMARY"],$summary) == 0 // same summary = same course and same course work
          && strcmp($calendar_decoded[$x-1]["DTEND"], $calendar_decoded[$x]["DTSTART"]) == 0)// prevents the algorithm to remove the break.
        {
          if ($freeTime < $minutes){ // if the free time is shorter than study time
            $work = $freeTime;
            $minutes -= $freeTime;
          }

          else { // if the free time is longer than study time
            $work = $minutes;
            $minutes = 0;
          }

          $newDT = minutesToHour($calendar_decoded[$x-1]["DTEND"], $work);
          $calendar_decoded[$x-1]["DTEND"] = $newDT;
          $calendar_decoded[$x-1]["SUMMARY"] .= " (rescheduled)";
          $calendar_decoded[$x]["DTSTART"] = $newDT; //TODO: lägg till paus på något sätt?
        }

        else { // prevous event doesnt have same summary
          if ($freeTime <= $minutes && $freeTime > 15){ // if the free time is shorter than study time
            $calendar_decoded[$x]["AVAILABLE"] = false;
            $calendar_decoded[$x]["SUMMARY"] = $summary . " (rescheduled)";
            $minutes -= $freeTime;
          }

          else { // if the free time is longer than study time
            $event = $calendar_decoded[$x]; // create a copy of current event(will be the new free event from left over time)
            $calendar_decoded[$x]["AVAILABLE"] = false;
            $calendar_decoded[$x]["SUMMARY"] = $summary . " (rescheduled)";

            $newDT = minutesToHour($calendar_decoded[$x]["DTSTART"], $minutes); //end time for this event, and start time for new event
            $calendar_decoded[$x]["DTEND"] = $newDT;
            $event["DTSTART"] = $newDT; // new available event // TODO: ny dtstart ska ha paus innan?
            array_splice($calendar_decoded, $x + 1, 0, array($event)); // new event
            $minutes = 0;
          }
        }
    }
    $x++;

    if(!isset($calendar_decoded[$x])) //prevent out of bounds
    break;
  } // end of while in same week

return $calendar_decoded;
//  return json_encode($calendar_decoded);
}

//  INPUT: Two elements
//  OUTPUT: True if they collide/overlap in time, otherwise false
function collide ($e1, $e2){
  $start1 = substr($e1["DTSTART"], 0, 8) . substr($e1["DTSTART"], 9, 6);	// Should use "eventTimeFloat"?
  $start2 = substr($e2["DTSTART"],0, 8) . substr($e2["DTSTART"], 9, 6);
  $end1 = substr($e1["DTEND"],0, 8) . substr($e1["DTEND"], 9, 6);
  $end2 = substr($e2["DTEND"],0, 8) . substr($e2["DTEND"], 9, 6);



  if($start1 < $start2){ // If e1 starts before or at the same time as e2 starts
    if($end1 <= $start2){  // if e1 ends before e2 starts
			return false;
    }
    else{
      return true;
    }
  }
  else{                  // If e2 starts before e1 starts
    if($end2 <= $start1){  // if e2 ends before e1 starts
      return false;
    }
    else{
      return true;
    }
  }
}

// inserts an event at it's correct spot in the array (assumes there are no collisions)
//  INPUT:  An calendar as an array and an event
//  OUTPUT: The calendar updated with the event put in the "correct" spot
//  according to its DTSTART (it assumes that there are no collisions)
function insertEvent($cal, $e){
	$a = array();
	array_push($a, $e);
	for($i = 0; $i < count($cal); $i++){
    //
		if(eventTimeFloat($cal[$i]["DTSTART"]) > eventTimeFloat($e["DTSTART"])){
			array_splice($cal, $i, 0, $a);
			return $cal;
		}
	}
  $cal[] = $e;
  return $cal;
}

//  INPUT:  An old ($studyC) and a new ($linkC) calendar
//  OUTPUT: An updated "old" calendar where events that only exists in "new" or
//  both are added, and events (that is not study-scheduler-time) that only
//  exists in "old" are deleted
function del_and_add($studyC, $linkC){
	$added = array();	// Boolean array that checks which $linkC events that are new and should be added
	for($i = 0; $i < count($linkC); $i++){
		array_push($added, true);
	}
	//	Deletes deleted KTH events.
	for($i = 0; $i < count($studyC); $i++){
    if($studyC[$i]["AVAILABLE"]){
      $deleted = false;
      continue;
    }
    if(strpos($studyC[$i]["SUMMARY"], "STUDY-SCHEDULER") !== false){	// Do not want to touch studyevents yet
			$deleted = false;
		}
		else{
			$deleted = true;		// Assumes $studyC[$i] has been deleted, prove it false in other cases
			for($j = 0; $j < count($linkC); $j++){
        if($linkC[$j]["AVAILABLE"]){
          continue;
        }
				if($linkC[$j] == $studyC[$i]){	// If no changes has been made with this event
					$added[$j] = false;	// $notadded[$j] is not a new event
					$deleted = false;
					break 1;
				}	// If the event has changed time (or summary)
				else if($linkC[$j]["UID"] == $studyC[$i]["UID"]){
					$added[$j] = false;	// $notadded[$j] is not a new event
					$deleted = false;
					array_splice($studyC, $i, 1);
					$studyC = insertEvent($studyC, $linkC[$j]);
					break 1;
				}
			}
		}
		if($deleted){	// Deletes deleted events
			array_splice($studyC, $i, 1);
		}
	}
	// Adds added KTH events
	for($i = 0; $i < count($linkC); $i++){
		if($added[$i]){
			$studyC = insertEvent($studyC ,$linkC[$i]);
  	}
	}
  return $studyC;
}

//	INPUT: An calendar as an array
//  OUTPUT: An updated calendar where any colliding studyevent has been
//  redistributed to a fitting time
function studCollision($studyC){
  $min = 0;
  for($i = 0; $i < (count($studyC)-1); $i++){
		if(collide($studyC[$i], $studyC[$i+1])){
			if(strpos($studyC[$i]["SUMMARY"], "STUDY-SCHEDULER" ) !== false){           // If $studyC[$j] is a studyevent
        $studyC[$i+1]["DTSTART"] = $studyC[$i]["DTEND"];
        if($studyC[$i+1]["DTSTART"] == $studyC[$i+1]["DTEND"]){  // Makes sure no new 0 time events survives
          array_splice($studyC, $i+1, 1);
          $i--;
        }
        $min = collisionTime($studyC[$i], $studyC[$i+1]);
        array_splice($studyC, $i, 1);
        $i--;
				$studyC = redistribute($studyC, $min, $i, $studyC[$i]["SUMMARY"]);                       // collision, redistribute the studytime
      }
			else if(strpos($studyC[$i+1]["SUMMARY"], "STUDY-SCHEDULER") !== false){           // If $studyC[$i] is a studyevent
        $min = collisionTime($studyC[$i], $studyC[$i+1]);
        $studyC[$i]["DTEND"] = $studyC[$i+1]["DTSTART"];
        if($studyC[$i]["DTSTART"] == $studyC[$i]["DTEND"]){     // Makes sure no new 0 time events survives
          array_splice($studyC, $i, 1);
          $i--;
        }
        array_splice($studyC, $i+1, 1);
        $i--;
				$studyC = redistribute($studyC, $min, $i+1, $studyC[$i+1]["SUMMARY"]);                       // collision, redistribute the studytime
      }
		}
	}
	return $studyC;
}
// Main function, updates the schedule and prevents collisions between new KTH events and studytime
// changes appear in database, there is no return value.
function schedule_update(){
  $db = new DB();
	//	Get the old (stored) KTH-sched
	$oldC = $db -> select("SELECT STUDY FROM calendar WHERE ID = 'c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	$oldC = json_decode($oldC[0]["STUDY"], true);

	//	Get the new (link) KTH-sched
	$linkC = $db -> select("SELECT KTHlink FROM data WHERE ID = 'c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	$linkC = $linkC[0]["KTHlink"];
	$linkC = json_decode(downloadFile($linkC), true);
	$changed = false;

	//	Quick check if there have been any changes in the schedule
	if(!count($linkC) == count($oldC)){
		$changed = true;
	}
	else{
	  for($i = 0; $i < count($linkC); $i++)
	    if(!$linkC[$i] == $oldC[$i]){
	      $changed = true;
	      break;
	    }
	}
	// If there have been changes in the link calendar, we need to update
	if($changed){
	   // Get the previously "finished" calendar with study-scheduler times
	   $studyC = $db -> select("SELECT CURRENT FROM calendar WHERE ID = 'c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
	   $studyC = json_decode($studyC[0]["CURRENT"], true);
		 //	deletes and adds changes made in $linkC to $studyC
		 $studyC = del_and_add($studyC, $linkC);
	   //  Fixes collisions involving studyevents
		 $studyC = studCollision($studyC);
     $studyC = $db -> quote(json_encode($studyC));
		 // Updates CURRENT in DB with $studyC
     $sql = "UPDATE calendar SET CURRENT=$studyC WHERE ID = 'c7fe7b83-2be5-11e7-b210-f0795931a7ef'";
     $db -> query($sql);
	}
}

?>
