<?php
 include "popupEvent.php"; 
 


var_dump($_POST);
  if($_POST["JSON"]){
    $event = json_decode($_POST["JSON"], false);	

    $html .= '<form action="ReceiveChanges.php" method="POST" id = "changeForm">';
    $html .= "<h1> Change/Remove: " . $event->SUMMARY . " </h1>";
    $html .= 'Remove event <input type = "radio" name = "remove" value = "1" checked><br>';
    $html .= 'Remove &amp reschedule <input type = "radio" name = "remove"  id= "remove" value = "2"><br>';
    $html .= "Change the start/end times, stay within: " . substr($event -> DTSTART, 9, 2) . ":" . substr($event -> DTSTART, 11, 2) . " &amp " . substr($event -> DTEND, 9, 2) . ":" . substr($event -> DTEND, 11, 2) . "<br>";
    $html .= 'Start time: <br><input size = "10" type = "text" name = "newStartH" id = "newStartH" value = "' . substr($event->DTSTART, 9, 2) . '"> h &nbsp';
    $html .= '<input size = "10" type = "text" name = "newStartM" id = "newStartM" value = "' . substr($event->DTSTART, 11, 2) . '"> min <br><br>';
    $html .= 'End time: <br><input size = "10" type = "text" name = "newEndH" id = "newEndH" value = "' . substr($event->DTEND, 9, 2) . '"> h &nbsp';
    $html .= '<input size = "10" type = "text" name = "newEndM" id = "newEndM" value = "' . substr($event->DTEND, 11, 2) . '"> min <br><br>';
    $html .= "<input type = 'hidden' name = 'JSON2' value='".$_POST['JSON']."'>";
    $html .= '<input type="submit" style = "margin:5px">';
    $html .= "</form>";
	

  }
  
  
  	popupGen($html);

?>