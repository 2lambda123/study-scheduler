<?php
include_once '../scripts/Analyze.php';
include_once '../scripts/find.php';
include_once '../scripts/export.php';

function dailyWork($start_date, $end_date, $encoded_json, $hp){
  $start_year = (int)substr($start_date, 0, 4);
  $start_month = (int)substr($start_date,5, 2);
  $start_date = (int)substr($start_date,8, 2);

  $end_year = (int)substr($end_date, 0, 4);
  $end_month = (int)substr($end_date,5, 2);
  $end_date = (int)substr($end_date,8, 2);

  $start_week = date("W", mktime(0,0,0,$start_month, $start_date, $start_year));
  $end_week = date("W", mktime(0,0,0,$end_month, $end_date, $end_year));

  if ($start_week == $end_week){ //same week
    $diff_week = 1;
  }
  else if($start_week < $end_week){ //same year
    $diff_week = $end_week - $start_week;
  }
  else {
    $diff_week = 52 - $start_week + $end_week;
  }
  $days = days($encoded_json);
  $days = $days * $diff_week;
  return  floor(60*$hp*(40/1.5) / $days);// returns minutes per working day. 40h = 1,5hp
}

function days($encoded_json){ //encoded_json is collection
  $decoded_json = json_decode($encoded_json, true);
  $days = 7;
  foreach($decoded_json as $temp){ //look for off days
    if (strcmp($temp, "on") == 0)
      $days--;
  }
  return $days;
}

function firstSunday($start_date){ //returns first sunday
  $year = (int)substr($start_date, 0, 4);
  $month = (int)substr($start_date,5, 2);
  $date = (int)substr($start_date,8, 2);
  $daysleft = 0;

  switch(date("l", mktime(0,0,0,$month, $date, $year))){
    case "Monday":
      $daysleft = 6;
      break;
    case "Tuesday":
      $daysleft = 5;
      break;
    case "Wednesday":
      $daysleft = 4;
      break;
    case "Thursday":
      $daysleft = 3;
      break;
    case "Friday":
      $daysleft = 2;
      break;

    }

    $numberofdays = date("t", mktime(0,0,0,$month, $date, $year));
    if ($numberofdays < $date + $daysleft){
      if($month == 12)
      {
        $month = 1;
        $year++;
      }

      else{
        $month++;
      }
      $date = $date + $daysleft - $numberofdays;
    }

    else{
      $date += $daysleft;
    }

    if ($month < 10)
    $month = "0" . $month;

    if ($date < 10)
    $date = "0" . $date;

    return $year . "-" . $month . "-" . $date;

  }

function nextWeek($start_date){ // returns date of next week. Format: DTSTART

  $year = (int)substr($start_date, 0, 4);
  $month = (int)substr($start_date,4, 2);
  $date = (int)substr($start_date,6, 2);

  $numberofdays = date("t", mktime(0,0,0,$month, $date, $year)); // number of days in current month

  if ($numberofdays < $date+7){ //entering a new month
    if($month == 12)
    {
      $month = 1;
      $year++;
    }

    else{
      $month++;
    }

    $date = $date + 7 - $numberofdays;
  }

  else{ //same month
    $date += 7;
  }

  if ($month < 10){
    $month = "0" . $month;
  }

  if ($date < 10){
    $date = "0" . $date;
  }

  return $year . $month . $date;
}

function convertDate($date){ // converts "2017-04-24" to DTSTART format

  $year = substr($date, 0, 4);
  $month = substr($date,5, 2);
  $date = substr($date,8, 2);

  return $year . $month . $date;
}

function revertDate($date){ // converts DTSTART format to "2017-04-24"
  $year = substr($date, 0, 4);
  $month = substr($date,4, 2);
  $date = substr($date,6, 2);
  return $year . "-" . $month . "-" . $date;
}

function timeDiff($decoded_json){ //takes decoded_json event and returns minutes
  $start_date = substr($decoded_json["DTSTART"], 0, 8);
  $start_hour = substr($decoded_json["DTSTART"], 9, 2);
  $start_min = substr($decoded_json["DTSTART"], 11, 2);

  $end_date = substr($decoded_json["DTEND"], 0, 8);
  $end_hour = substr($decoded_json["DTEND"], 9, 2);
  $end_min = substr($decoded_json["DTEND"], 11, 2);

  if (strcmp($start_date, $end_date) == 0){ //same date
    $diff = ((int)$end_hour - (int)$start_hour) * 60;
    $diff += (int)$end_min - (int)$start_min;
  }
  else{
    $diff = ((int)$end_hour + 23-(int)$start_hour)*60; //DTSTART will not start 24
    $diff += (int)$end_min + 60-(int)$start_min;
  }
  return $diff; // return in minutes
}

function minutesToHour($dtstart,$minutes){ //takes dtstart and minutes and returns dtend
  $hour = (int)substr($dtstart,9,2) + floor($minutes/60);
  $oldmin = (int)substr($dtstart,11,2);
  $min = $minutes % 60;

  if ($oldmin + $min >= 60){
  	$hour++;
  	$min = ($oldmin + $min) % 60;
	}

  else {
    $min += $oldmin;
  }

  if ($min < 10)
  $min = "0" . $min;

  if ($hour < 10)
	$hour = "0" . $hour;

  return substr_replace($dtstart,$hour . $min, 9, 4);
}

//courses is an array consisting of one or more encoded courses
function distribute($calendar_encoded, $courses_encoded, $collection_encoded){ // distributes time for ONE course, collection checks how many days to work
  $courses = json_decode($courses_encoded, true);
  //var_dump($courses);
  foreach($courses as $courses_decoded){ // loop through every course, $courses is all the courses from database
	  $collection_decoded = json_decode($collection_encoded, true);
    $repeat = false;

    if (strcmp($courses_decoded["exam"], "on") == 0){ //TENTA
      $examWork = dailyWork($courses_decoded["coursestart"], $courses_decoded["courseend"], $collection_encoded, $courses_decoded["hp_exam"]);
      $calendar_decoded = json_decode($calendar_encoded, true);
      $calendar_decoded = distributeWork($calendar_decoded, $courses_decoded, $examWork, days($collection_encoded), "Course study",$courses_decoded["coursestart"], $collection_decoded, $repeat);
      $repeat = true;
    }

    if (isset($courses_decoded["lab"])){ // lab
      $labs = (int) $courses_decoded["numberoflabs"];
      $start = $courses_decoded["coursestart"];
      $found = 0;

      while($found < $labs){
        foreach($calendar_decoded as $event){
          $substr = substr($event["SUMMARY"], 0, 10); // look for laboration

          if(strpos($event["SUMMARY"], $courses_decoded["coursecode"]) && strcmp($substr, "Laboration") == 0){ //found lab for correct course
            $endtimes[$found] = revertDate($event["DTSTART"]);
            $found++;
          }
        }
      }

      for ($i = 0; $i < $labs; $i++){
        $end = $endtimes[$i];
        $work_per_lab = dailyWork($start, $end, $courses_decoded["hp_lab"]/$labs);
        $calendar_decoded = distributeWork($calendar_decoded, $courses_decoded, $work_per_lab, days($collection_encoded), "Prepare for lab " . $i, $start, $collection_decoded, $repeat);
        $start = $end; // new start is last end
      }
      $repeat = true;
    }

	$cw = 1; // not TENTA or lab ->
	while(isset($courses_decoded["coursework" . $cw])){ //As long as there is coursework to insert
	  $courseWork = dailyWork($courses_decoded["coursestart" . $cw], $courses_decoded["courseend" . $cw], $collection_encoded, $courses_decoded["hp_work" . $cw]);
	  $calendar_decoded = distributeWork($calendar_decoded, $courses_decoded, $courseWork, days($collection_encoded), "Study for " . $courses_decoded["coursework" . $cw], $courses_decoded["startdate". $cw], $collection_decoded, $repeat);
	  $cw++;
    $repeat = true;
	}

  }
  return json_encode($calendar_decoded);
}

// from $courses_decoded we will get start and end date. $examWork will be calculated in 'distribute'
function distributeWork($calendar_decoded, $courses_decoded, $examWork, $workingdays, $course_work, $start, $collection_decoded, $repeat){ // måste anropa från distribute $workingdays = days(collection_encoded)
  $course_code = $courses_decoded["coursecode"]; //courses_decoded is for one course
  $count = count($calendar_decoded); //$count is the size of calendar
  $x = 0; // $x is start of week
  //Dra upp $x till start of week, just nu är den bara start of calendar - $start is 2017-05-01
  $start_dt = convertDate($start); //converts $start to DT format
  $end_dt = convertDate($courses_decoded["courseend"]);
  while(strcmp(substr($calendar_decoded[$x]["DTSTART"],0,8),$start_dt) < 0) //loops as long as event DTSTART is less than $start_dt
  {
    $x++;
  }
  $firstSunday = firstSunday($start); // first sunday in normal format.
  $weekEnd = convertDate($firstSunday); //convert to DT TODO:firstSunday return in DT
  while($x < $count) {//loops through calendar (new week)
    if (strcmp(substr($calendar_decoded[$x]["DTSTART"],0,8),$end_dt) >= 0) // break at course end
    break;
    //$x is week start, $array will be [calendar, x, count]. 5 is overflow_attempts. false is to show it is first time checking school times
    $array = distributeWeekly($calendar_decoded, $examWork, $count, $x, $weekEnd, 5, $workingdays, $course_code, $repeat, $course_work, $collection_decoded, $end_dt);
    $count = $array[2];
    $x = $array[1]; // x is where the week ended in calendar_decoded
    $calendar_decoded = $array[0];
    if ($x < $count) // prevents out of bound
    $weekEnd = nextWeek($calendar_decoded[$x]["DTSTART"] - 1); // end of next week.
  }

  return $calendar_decoded;
}

//$x is week start. if $repeat isnt 0, then don't check for school time TODO: use $courses_decoded as parameter instead of $course_code, $course_work, $end_dt
function distributeWeekly($calendar_decoded, $examWork, $count, $x, $weekEnd, $overflow_attempts, $workingdays, $course_code, $repeat, $course_work, $collection_decoded, $end_dt){
$weekly_overflow = 0;
$weekStart = $x;
  while(strcmp(substr($calendar_decoded[$x]["DTSTART"], 0, 8),$weekEnd) <= 0 && strcmp(substr($calendar_decoded[$x]["DTSTART"], 0, 8),$end_dt) < 0){ // still on same week - loops for every new day
    $y = 0; //reset every day, y chekcs all event on one day
    $z = 0;
	  $examWork_static = $examWork;
    $current_day = substr($calendar_decoded[$x]["DTSTART"], 0, 8); //YYYYMMDD

    // still on same day, finds school time
    while(strcmp(substr($calendar_decoded[$x + $y]["DTSTART"], 0, 8),$current_day) == 0){
      $substr = substr($calendar_decoded[$x + $y]["SUMMARY"], 0, 10); // look for laboration
      // strpos -> if the right course time, and also NOT a lab time
      if (strpos($calendar_decoded[$x + $y]["SUMMARY"], $course_code) && strcmp($substr, "Laboration") != 0 && $examWork > 0 && $repeat == false){
        $examWork -= timeDiff($calendar_decoded[$x + $y]);
      }

      $y++; //next event

      if ($x+$y >= $count) // break if at the end of calendar
      break;
    }

    // still on same day and finds and distributes available times.
    while(strcmp(substr($calendar_decoded[$x+$z]["DTSTART"], 0, 8),$current_day) == 0 && $examWork > 10){ //examWork > 10 to prevent short study times
      if ($calendar_decoded[$x+$z]["AVAILABLE"] == true){ // found time to study
        $freeTime = timeDiff($calendar_decoded[$x+$z]); // how long is this event?

  			//checks if previous event is a study time with same course code and work inserted by us already -> extends that event rather than making a new one
    	    if (strpos($calendar_decoded[$x+$z-1]["SUMMARY"], "STUDY-SCHEDULER: " . $course_work . " - " . $course_code) !== false // same course and same course work
            && strcmp($calendar_decoded[$x+$z-1]["DTEND"], $calendar_decoded[$x+$z]["DTSTART"]) == 0)// prevents the algorithm to remove the break.
          {
            if ($freeTime <= $examWork){ // if the free time is shorter than study time
              $work = $freeTime;
              $examWork -= $freeTime;
            }

            else { // if the free time is longer than study time
              $work = $examWork;
              $examWork = 0;
            }

            $newDT = minutesToHour($calendar_decoded[$x+$z]["DTSTART"], $work);
            $calendar_decoded[$x+$z-1]["DTEND"] = $newDT;
            $calendar_decoded[$x+$z]["DTSTART"] = $newDT;
    		  }

    		  else{ //if previous event is not a STUDY-SCHEDULER time or not same course/coursework

            if ($freeTime <= $examWork && $freeTime > 10){ // if the free time is shorter than study time, and at least 10 mins
              $calendar_decoded[$x+$z]["AVAILABLE"] = false;
              $calendar_decoded[$x+$z]["SUMMARY"] = "STUDY-SCHEDULER: " . $course_work . " - " . $course_code;
              $examWork -= $freeTime;
            }

            else{ // if free time is longer than study time
              // check if there's time to study normal study length(prevents from short study times).
              $normalWork =  (int) $collection_decoded["studylength"]; // normal study length
              if ($freeTime >= $normalWork && $normalWork > $examWork){ // if free time is longer than normal study length and normal study length is longer than examWork
                $work = $normalWork;
              }

              else {
                $work = $examWork;
              }

              $event = $calendar_decoded[$x + $z]; // create a copy of current event(will be the new free event from left over time)

              $calendar_decoded[$x + $z]["AVAILABLE"] = false;
              $calendar_decoded[$x + $z]["SUMMARY"] = "STUDY-SCHEDULER: " . $course_work . " - " . $course_code;

              $newDT = minutesToHour($calendar_decoded[$x+$z]["DTSTART"], $work);

              $calendar_decoded[$x + $z]["DTEND"] = $newDT;
              $event["DTSTART"] = $newDT; // new available event

              // put new free event into calendar TODO: inte göra array() om vi ska göra på sista, +1 to be after [x+z]
              array_splice($calendar_decoded, $x + $z + 1, 0, array($event));
              // +1 as we make an array splice
              $freeTime = timeDiff($calendar_decoded[$x+$z]);
              $examWork = 0;
              $count++;
              $x++;
            }
          }
        } // end of if statement checking if it's a study time
    $z++;

    if ($x+$z >= $count) // break at the end of calendar
	  break;

  } //end of daily distribute while loop

  $current_week_day = date("l", mktime(0,0,0,substr($current_day,4,2),substr($current_day,6,2),substr($current_day,0,4))); //day of the week (Monday,Tuesday, etc)
  if ($examWork > 10 && strpos(json_encode($collection_decoded), $current_week_day) === false){ // checked daily, also makes sure we're supposed to work that day
    $weekly_overflow += $examWork;
  }

  if ($y==0) // no events today -> go to next event
  $x++;

  else{
    $x += $y; //next day
  }

  if ($x >= $count)
    break;

  $examWork = $examWork_static; //Reverts examWork back to its original value
  } //end of weekly loop

  if ($weekly_overflow > 10 && $overflow_attempts > 0){ // if there is overflow from the week
    //call recursively the same week when overflow exists
    $overflowWork = floor($weekly_overflow/$workingdays);
    return distributeWeekly($calendar_decoded, $overflowWork, $count, $weekStart, $weekEnd, $overflow_attempts-1, $workingdays, $course_code, true, $course_work, $collection_decoded, $end_dt);
  }
  else{ // no overflow left
  return array($calendar_decoded, $x, $count);
  }

}
?>
