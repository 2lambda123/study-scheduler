<?php
  include "importCal.php";

  // If remaining time is equal to the available time, the available time is
  // removed with studytime
  function ifEqual($studyEvent, $calendar, $i){
    $studyEvent->DTSTART = $calendar[$i]->DTSTART;
    $studyEvent->DTEND = $calendar[$i]->DTEND;
    $StudyEvent->AVAILABLE = false;
    $calender[$i] = $studyEvent;
    return $calendar;
  }
  // If remaining time is bigger than the available time, the available time is
  // cut up and placed after the inserted studytime
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
function recursive_distr($restMin, $studyEvent, $calendar, $lastDate){
  // Find the first event after $StudEvent in the calendar
  $slot = 0;
  for($i = 0; $i<count($calendar); $i++){
    $dateCal = (intval(substr($calendar[$i]->DTSTART,0,8))* 10000) + intval(substr($calendar[$i]->DTSTART,9,4)); // y-m-d
    $dateStud = (intval(substr($studyEvent->DTSTART,0,8))* 10000) + intval(substr($studyEvent->DTSTART,9,4)); // h-min
    if($dateCal >= $dateStud){
      $slot = $i;
      break;
    }
  }
  //  loop 1 week, if no long enough event appears, $restMin halves and start looping again
  //  Loop until a satisfying event appears
  //echo date("Y-m-d", strtotime(" + 1 week", "2017-01-22"));
  for($i = $slot; $i<count($calendar); $i++){
    //Within a week of $studyEvent's DTSTART
    $calendarES = date('Ymd', strtotime(substr($calendar[$i]->DTSTART, 0, 8)));
    $lastDate = date('Ymd', strtotime(substr($lastDate, 0, 8)));
    //$goneAweek = date('Ymd', strtotime(substr($studyEvent->DTSTART, 0, 8)) + 60*60*24*7);
    if($calendarES <= $lastDate){
      if($calendar[$i]->AVAILABLE){
        // If $calendar[$i]:s duration >= $restmin
        $diffH = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval(substr($calendar[$i]->DTSTART, 9, 2));
        $diffM = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval(substr($calendar[$i]->DTSTART, 11, 2));
        $diffM = $diffH*60 + $diffM;
        if($diffM == $restMin){
          $calendar = ifEqual($studyEvent, $calendar, $i);
          return $calendar;
        }
        if($diffM > $restMin){
          $calendar = ifLarger($studyEvent, $calendar, $i, $diffM, $restMin);
          return $calendar;
        }
      }
    }
    if($calendarES > $lastDate){
      $studE2 = $studyEvent;
      $studE2->UID = "new UID"; //todo Make actual UID
      $restMin = $restMin/2;
      $calendar = recursive_distr($restMin, $studyEvent, $calendar, $lastDate);
      $calendar = recursive_distr($restMin, $studE2, $calendar, $lastDate);
      return $calendar;
    }
  }
}

function distr_leftover($restMin, $studyEvent){
  $calendar = json_decode(downloadFile("MedLabbar.ics")); // Todo: import from database
  $total = 0;
  $lastDate = null;

  // Find the first event after $StudEvent in the calendar
  $slot = 0;
  for($i = 0; $i<count($calendar); $i++){
    $dateCal = (intval(substr($calendar[$i]->DTSTART,0,8))* 10000) + intval(substr($calendar[$i]->DTSTART,9,4)); // y-m-d
    $dateStud = (intval(substr($studyEvent->DTSTART,0,8))* 10000) + intval(substr($studyEvent->DTSTART,9,4)); // h-min
    if($dateCal >= $dateStud){
      $slot = $i;
      break;
    }
  }

  for($i = $slot; $i < count($calendar); $i++){//Calulate until what date we have enough restMin
    if($total < $restMin){
      if($calendar[$i]->AVAILABLE){
        $diffH = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval(substr($calendar[$i]->DTSTART, 9, 2));
        $diffM = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval(substr($calendar[$i]->DTSTART, 11, 2));
        $diffM = $diffH*60 + $diffM;  // Time before the change (in min)
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

  $calendar = recursive_distr($restMin, $studyEvent, $calendar, $lastDate);
  var_dump($calendar);
}
  /*
  Testdata:
  $fakeEv  = new event();
  $fakeEv->SUMMARY = "DENNA";
  $fakeEv->DTSTART = "20170428T140000Z";
  $fakeEv->DTEND = "20170428T1210000Z";
  distr_leftover(350, $fakeEv);
  */
 ?>
