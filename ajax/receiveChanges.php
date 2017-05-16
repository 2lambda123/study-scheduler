<?php
//Previous name: "ReceiveChanges.php"
if (session_id() == "") session_start();
include_once '../scripts/DB.php';
include '../scripts/distrChanges.php';

$db = new DB();
$result = $db -> select("SELECT CURRENT FROM calendar WHERE ID='$_SESSION[uuid]'");
$calendar = json_decode($result[0]['CURRENT']);
// Outputs the time needed to be distributed in minutes
if(isset($_POST["remove"])){  //  If we are to remove the event from the schedule
  $event = json_decode($_POST["JSON2"], false);
  for($i = 0; $i < count($calendar); $i++){
    if($calendar[$i]->UID == $event->UID){
      //To separate habits from course-studying (pseudo code)
      if(strpos($calendar[$i]->SUMMARY, "STUDY-SCHEDULER") !== false){
        //Length of the event in calendar[$i]
        $diffH = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval(substr($calendar[$i]->DTSTART, 9, 2));
        $diffM = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval(substr($calendar[$i]->DTSTART, 11, 2));
        $diffM = $diffH*60 + $diffM;

        //We create a temporary cal[$i] so we can send it to distrChanges
        $temp = $calendar[$i];
        array_splice($calendar, $i, 1);

        //If we want to relocate the STUDY-SCHEDULER time
        if($_POST["remove"] == 2){
          distr_leftover($diffM, $temp, json_encode($calendar));
          break;
        }
        else{
          $db -> query("UPDATE calendar SET CURRENT=".$db->quote(json_encode($calendar)) ." WHERE ID='$_SESSION[uuid]'");
        }
      }
      else{//If we remove a habit instead
        array_splice($calendar, $i, 1);
        $db -> query("UPDATE calendar SET CURRENT=".$db->quote(json_encode($calendar)) ." WHERE ID='$_SESSION[uuid]'");
      }
    }
  }
}
else{ // If we are to "cut" the event
  if(isset($_POST["newStartH"]) || isset($_POST["newEndH"])){
    $event = json_decode($_POST["JSON2"], false);
    for($i = 0; $i < count($calendar); $i++){
      if($calendar[$i]->UID == $event->UID){
        //Get the new starting hour and end hour of the event
        $diffHS = intval($_POST["newStartH"]) - intval(substr($calendar[$i]->DTSTART, 9, 2));
        $diffHE = intval(substr($calendar[$i]->DTEND, 9, 2)) - intval($_POST["newEndH"]);
        //Get the new starting min and end min of the event
        $diffMS = intval($_POST["newStartM"]) - intval(substr($calendar[$i]->DTSTART, 11, 2));
        $diffME = intval(substr($calendar[$i]->DTEND, 11, 2)) - intval($_POST["newEndM"]);
        //Calculate how much STUDYTIME we cut off
        $diffM = ($diffHE + $diffHS)*60  + ($diffME + $diffMS);

        $calendar[$i]->DTSTART = substr($calendar[$i]->DTSTART, 0, 9) . $_POST["newStartH"] . $_POST["newStartM"] . substr($calendar[$i]->DTSTART, -3, 3); // replace the time in DTSTART with the new time
        $calendar[$i]->DTEND = substr($calendar[$i]->DTEND, 0, 9) . $_POST["newEndH"] . $_POST["newEndM"] . substr($calendar[$i]->DTEND, -3, 3);   // replace the time in DTEND with the new time

        //To separate habits from course-studying (pseudo code)
        if($calendar[$i]->SUMMARY = "STUDY-SCHEDULER"){
          $temp = $calendar[$i];
          $newUID = generateUid();//New UID for the split event
          $temp->UID = $newUID;
          distr_leftover($diffM, $temp, json_encode($calendar));
        }
        else{
          $db -> query("UPDATE calendar SET CURRENT=".$db->quote($calendar) ." WHERE ID='$_SESSION[uuid]'");
        }
      }
    }
  }
}

function generateUid(){
  return uniqid("67209119184"); // IP + current string to time
}

?>
