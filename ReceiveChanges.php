<?php
include 'DB.php';
include 'Distribute_Leftover_Time.php';
$db = new DB();
$result = $db -> select("SELECT CURRENT FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
$calendar = json_decode($result[0]['STUDY']);

// Outputs the time needed to be distributed in minutes
if(isset($_POST["remove"])){  //  If we are to remove the event from the schedule
  $event = json_decode($_POST["JSON2"], false);
  for($i = 0; $i < count($calendar); $i++){
    if($calendar[$i]->UID == $event->UID){
      //To separate habits from course-studying (pseudo code)
      if($calendar[$i]->SUMMARY = "STUDY-SCHEDULER"){
        //  Leftover time
        $diffH = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval(substr($calendar[$i]->DTSTART, 9, 2));
        $diffM = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval(substr($calendar[$i]->DTSTART, 11, 2));
        $diffM = $diffH*60 + $diffM;  // Time before the change (in min)

        $temp = $calendar[$i];
        unset($calendar[$i]);
        if($_POST["Remove"] == 2){
          distr_leftover($diffM, $temp, $calendar);
        }
      }
      else{
      unset($calendar[$i]);
      $db -> query("UPDATE CURRENT SET calendar=".$db->quote(json_encode($calendar))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
      }
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
        //echo '<script language = "javascript">alert("'. $calendar[$i]->DTSTART . " + " . $calendar[$i]->DTEND .'")</script>';


        //To separate habits from course-studying (pseudo code)
        if($calendar[$i]->SUMMARY = "STUDY-SCHEDULER"){
          $temp = $calendar[$i];
          $temp->UID = "Split new UID"; // todo: Make atual UID
          distr_leftover($diffM, $temp, $calendar);
        }
        else{
          $db -> query("UPDATE CURRENT SET calendar=".$db->quote(json_encode($calendar))." WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
        }
      }
    }
  }
}


 ?>
