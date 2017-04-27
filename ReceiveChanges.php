<?php
include "importCal.php";
// Outputs the time needed to be distributed in minutes
if(isset($_POST["remove"])){  //  If we are to remove the event from the schedule
  $calendar = json_decode(downloadFile("MedLabbar.ics"));
  $event = json_decode($_POST["JSON2"], false);
  for($i = 0; $i < count($calendar); $i++){
    if($calendar[$i]->UID == $event->UID){
      //  Leftover time
      $diffH = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval(substr($calendar[$i]->DTSTART, 9, 2));
      $diffM = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval(substr($calendar[$i]->DTSTART, 11, 2));
      $diffM = $diffH*60 + $diffM;  // Time before the change (in min)
      /*
      To separate habits from course-studying (pseudo code)
      if(substr($calendar[$i]->SUMMARY, N1, N2) = "Course Studying"){
        $temp = $calendar[$i];
        unset($calendar[$i]);
        if($_POST["Remove"] == 2){
          #2($diffM, $temp);
        }
      }
      else{
      */
      //echo '<script language = "javascript">alert("' . $diffM .'")</script>';
      unset($calendar[$i]);
    //}
    }
  }
}
else{ // If we are to "cut" the event
  if(isset($_POST["newStartH"]) || isset($_POST["newEndH"])){
    for($i = 0; $i < count($calendar); $i++){
      if($calendar[$i]->UID == $event->UID){
        // Leftover time
        $diffHS = intval($_POST["newStartH"]) - intval(substr($calendar[$i]->DTSTART, 9, 2));
        $diffHE = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval($_POST["newEndH"]);

        $diffMS = intval($_POST["newStartM"]) - intval(substr($calendar[$i]->DTSTART, 11, 2));
        $diffME = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval($_POST["newEndM"]);
        $diffM = ($diffHE - $diffHS)*60  + ($diffME - $diffMS);  // Time before the change (in min)

        $calendar[$i]->DTSTART = substr($calendar[$i]->DTSTART, 0, 9) . $_POST["newStartH"] . $_POST["newStartM"] . substr($calendar[$i]->DTSTART, -3, 3); // replace the time in DTSTART with the new time
        $calendar[$i]->DTEND = substr($calendar[$i]->DTEND, 0, 9) . $_POST["newEndH"] . $_POST["newEndM"] . substr($calendar[$i]->DTEND, -3, 3);   // replace the time in DTEND with the new time
        echo '<script language = "javascript">alert("'. $calendar[$i]->DTSTART . " + " . $calendar[$i]->DTEND .'")</script>';

        /*
        To separate habits from course-studying (pseudo code)
        if(substr($calendar[$i]->SUMMARY, N1, N2) = "Course Studying"){
          if($_POST["Remove"] == 2){
            #2($diffM, $temp);
          }
        }*/
      }
    }
  }
}
//  Update the calendar


 ?>
