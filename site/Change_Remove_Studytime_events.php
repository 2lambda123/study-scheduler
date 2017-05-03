<?php
 include "popupEvent.php"; 
 
  if($_POST["JSON"]){
    $event = json_decode($_POST["JSON"], false);	

    $html .= '<form action="ReceiveChanges.php" method="POST" class = "changeForm">';
    $html .= '<h5> '. $event->SUMMARY .' </h5> <h3> <br> Would you like to change or remove this event? </h3>';
    $html .= '<input type = "radio" name = "remove" value = "1" checked>Remove event <br>';
    $html .= '<input type = "radio" name = "remove"  id= "remove" value = "2">Remove &amp reschedule <br><br>';
    $html .= "Change the start/end times, stay within: " . substr($event -> DTSTART, 9, 2) . ":" . substr($event -> DTSTART, 11, 2) . " - " . substr($event -> DTEND, 9, 2) . ":" . substr($event -> DTEND, 11, 2) . "<br>";
    $html .= 'Start time: <input size = "10" type = "text" name = "newStartH" id = "newStartH" value = "' . substr($event->DTSTART, 9, 2) . '"> h &nbsp';
    $html .= '<input size = "10" type = "text" name = "newStartM" id = "newStartM" value = "' . substr($event->DTSTART, 11, 2) . '"> min <br><br>';
    $html .= 'End time: <input size = "10" type = "text" name = "newEndH" id = "newEndH" value = "' . substr($event->DTEND, 9, 2) . '"> h &nbsp';
    $html .= '<input size = "10" type = "text" name = "newEndM" id = "newEndM" value = "' . substr($event->DTEND, 11, 2) . '"> min <br><br>';
    $html .= "<input type = 'hidden' name = 'JSON2' value='".$_POST['JSON']."'>";
    $html .= '<input type="submit" value = "Submit" class="submit" style = "margin:5px">';
    $html .= "</form>";
	

  }
  
  
  	popupGen($html);

?>