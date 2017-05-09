<?php
  include_once 'DB.php';
  //Previous name Distribute_Leftover_Time.php
  // If remaining studytime is equal to the available time, the studyEvent will
  // replace the available time.
  function ifEqual($studyEvent, $calendar, $i){
    if(isset($studyEvent)){
      $studyEvent->DTSTART = $calendar[$i]->DTSTART;
      $studyEvent->DTEND = $calendar[$i]->DTEND;
      $studyEvent->AVAILABLE = false;
      $calender[$i] = $studyEvent;
    }
    return $calendar;
  }
  // If available time is bigger than the remaining studytime the studytime will
  // be placed first and the remaining available time after that.
  function ifLarger($studyEvent, $calendar, $i, $diffM, $restMin){

      $studyEvent->DTSTART = $calendar[$i]->DTSTART;
      $startM = (intval(substr($studyEvent->DTSTART, 9, 2)))*60;
      //echo $startM;
      $startM = $startM + intval(substr($studyEvent->DTSTART, 11, 2));
      $restH = round(($restMin+$startM)/60-0.5);  // Whole hours missing, rounds down (1.9 -> 1)
      $restH = $restH % 24;                 //  Fixed overclocking hours
      $restMin = ($restMin + $startM) % 60; // The rest of the minutes
      // To string
      $restH = strval($restH);
      $restMin = strval($restMin);
      //echo $restH . " " . $restMin;
      // Insert time to the dates
      $tempEnd = $calendar[$i]->DTSTART;
      //echo $tempEnd;


      // echo $restMin[0];
      // If the hours and/or minutes consists of 2 or just 1 digit
      if(strlen($restH) == 2){
        $tempEnd[9] = $restH[0];
        $tempEnd[10] = $restH[1];
      }
      else{
        $tempEnd[10] = $restH[0];
        $tempEnd[9] = "0";
      }
      if(strlen($restMin) == 2){
        $tempEnd[12] = $restMin[0];
        $tempEnd[11] = $restMin[1];
      }
      else{
        $tempEnd[11] = $restMin[0];
        $tempEnd[12] = "0";
      }
      $studyEvent->DTEND = $tempEnd;
      $studyEvent->AVAILABLE = false;
      $calendar[$i]->DTSTART = $studyEvent->DTEND;
      array_splice($calendar, $i, 0, array($studyEvent));
      return $calendar;
  }


// Finds the first available time after the deleted event and puts it there
//$lastDate = the date when enough study time has been found
function recursive_distr($restMin, $studyEvent, $calendar, $lastDate){
  // Find the first event after $StudEvent (the removed event) in the calendar
  $slot = 0;
  for($i = 0; $i<count($calendar); $i++){
    $dateCal = (intval(substr($calendar[$i]->DTSTART,0,8))* 10000) + intval(substr($calendar[$i]->DTSTART,9,4)); // y-m-d
    $dateStud = (intval(substr($studyEvent->DTSTART,0,8))* 10000) + intval(substr($studyEvent->DTSTART,9,4)); // h-min
    if($dateCal >= $dateStud){
      $slot = $i;
      break;
    }
  }
  //get YY-MM-DD from $lastDate
  $lastDate = date('Ymd', strtotime(substr($lastDate, 0, 8)));
  //  loop until $lastDate, if no long enough event appears $restMin halves and starts looping again
  for($i = $slot; $i<count($calendar); $i++){
    // get YY-MM-DD from calendar
    $calendarES = date('Ymd', strtotime(substr($calendar[$i]->DTSTART, 0, 8)));

    //If we haven't hit last date yet
    if($calendarES <= $lastDate){
      if($calendar[$i]->AVAILABLE){
        //Get the time length of the calendar event in min
        $diffH = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval(substr($calendar[$i]->DTSTART, 9, 2));
        $diffM = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval(substr($calendar[$i]->DTSTART, 11, 2));
        $diffM = $diffH*60 + $diffM;
        //If $calendar[$i]:s duration == $restmin
        if($diffM == $restMin){
          $calendar = ifEqual($studyEvent, $calendar, $i);
          return $calendar;
        }
        // If $calendar[$i]:s duration >= $restmin
        if($diffM > $restMin){
          $calendar = ifLarger($studyEvent, $calendar, $i, $diffM, $restMin);
          return $calendar;
        }
      }
    }
    //We are past $lastDate, $restmin is split in half so it may fit smaller
    //available times
    if($calendarES > $lastDate){
      $studE2 = $studyEvent;
      $studE2->UID = "new UID"; //todo Make actual UID
      $restMin = $restMin/2;
      //recursive function for the splits of restmin
      $calendar = recursive_distr($restMin, $studyEvent, $calendar, $lastDate);
      $calendar = recursive_distr($restMin, $studE2, $calendar, $lastDate);
      return $calendar;
    }
  }
}

//Use this function to distribute the leftover time, don't use anything else!!!
function distr_leftover($restMin, $studyEvent, $calendar){
  $calendar = json_decode($calendar);
  $total = 0;
  $lastDate = null;
  // Find the first event after $StudEvent in the calendar
  $slot = 0;
  for($i = 0; $i<count($calendar); $i++){
    $dateCal = (intval(substr($calendar[$i]->DTSTART,0,8))* 10000) + intval(substr($calendar[$i]->DTSTART,9,4)); // y-m-d
    $dateStud = (intval(substr($studyEvent->DTSTART,0,8))* 10000) + intval(substr($studyEvent->DTSTART,9,4)); // h-min
    if($dateCal > $dateStud){
      $slot = $i;
      break;
    }
  }
  //Calulate until what date we have enough available time for restMin
  for($i = $slot; $i < count($calendar); $i++){
    if($total < $restMin){
      if($calendar[$i]->AVAILABLE){
        //Get the length of the event in min
        $diffH = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval(substr($calendar[$i]->DTSTART, 9, 2));
        $diffM = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval(substr($calendar[$i]->DTSTART, 11, 2));
        $diffM = $diffH*60 + $diffM;
        //If the available time it's less then 20 we ignore it
        if($diffM >= 20){
          $total += $diffM;
        }
      }
    }
    else {
      $lastDate = $calendar[$i]->DTEND;
      break;
    }
  }
  echo $lastDate;
  $calendar = recursive_distr($restMin, $studyEvent, $calendar, $lastDate);
  $db = new DB();
  $db -> query("UPDATE calendar SET CURRENT=".$db->quote(json_encode($calendar)) ." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
}

?>
