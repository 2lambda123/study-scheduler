<?php
  include "importCal.php";
    // Finds the first available time after the deleted event and puts it there
  function distr_leftover($restMin, $StudyEvent){
    $calendar = json_decode(downloadFile("MedLabbar.ics"));

    // Find the first event after $StudEvent in the calendar
    $slot = 0;
    for($i = 0; $i<count($calendar); $i++){
      $dateCal = (intval(substr($calendar[$i]->DTSTART,0,8))* 10000) + intval(substr($calendar[$i]->DTSTART,9,4)); // y-m-d
      $dateStud = (intval(substr($StudyEvent->DTSTART,0,8))* 10000) + intval(substr($StudyEvent->DTSTART,9,4)); // h-min
      if($dateCal >= $dateStud){
        $slot = $i;
        break;
      }
    }
    //  Loop until a satisfying event appears
    for($i = $slot; $i<count($calendar); $i++){
      if($calendar[$i]->AVAILABLE){
        // If $calendar[$i]:s duration >= $restmin
        $diffH = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval(substr($calendar[$i]->DTSTART, 9, 2));
        $diffM = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval(substr($calendar[$i]->DTSTART, 11, 2));
        $diffM = $diffH*60 + $diffM;
      //  echo "In it! " . $calendar[$i]->DTSTART;
        if($diffM == $restMin){
          $StudyEvent->DTSTART = $calendar[$i]->DTSTART;
          $StudyEvent->DTEND = $calendar[$i]->DTEND;
          $calender[$i] = $StudyEvent;
          break;
        }
        if($diffM > $restMin){
          $StudyEvent->DTSTART = $calendar[$i]->DTSTART;
          $startM = (intval(strpos($StudyEvent->DTSTART, 9, 2)))*60;
          echo $startM;
          $startM = $startM + intval(strpos($StudyEvent->DTSTART, 11, 2));
          $restH = round($restMin+$startM/60);  // Whole hours missing
          $restMin = ($restMin + $startM) % 60; // The rest of the minutes

          // To string
          $restH = strval($restH);
          $restMin = strval($restMin);
          //echo $restH . " " . $restMin;
          // Insert time to the dates
          $tempEnd = $calendar[$i]->DTSTART[9];
          $tempEnd[9] = $restH[0];
          $tempEnd[11] = $restMin[0];
          // If the hours and/or minutes consists of 2 or just 1 digit
          if(strlen($restH) == 2){
            $tempEnd[10] = $restH[1];
          }
          else{
            $tempEnd[10] = "0";
          }
          if(strlen($restMin) == 2){
            $tempEnd[12] = $restMin[1];
          }
          else{
            $tempEnd[12] = "0";
          }

          $StudyEvent->DTEND = $tempEnd;
          array_splice($calendar, $i-1, 0, $StudyEvent);
          //var_dump($calendar);
          //var_dump ($StudyEvent);
          break;
        }
      }
    }
  }
  $fakeEv  = new event();
  $fakeEv->DTSTART = "20170122T140000Z";
  $fakeEv->DTEND = "20170122T1210000Z";
  distr_leftover(40, $fakeEv);
 ?>
