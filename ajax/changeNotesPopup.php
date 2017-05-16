<?php

 include "../scripts/popupEvent.php";
 $html = "";
  if(isset($_POST["JSON"])){
    $event = json_decode($_POST["JSON"], false);
	$html = "";
    $html .= '<form action="../ajax/receiveNotes.php" method="POST" class = "changeForm">';
    $html .= '<h5> '. $event->SUMMARY .' </h5> <h3> <br> Would you like to change the notes for all the similar events or just this one? </h3>';
    $html .= '<input type = "radio" name = "changeAll" id= "changeNotes" value = "1">Everyone <br>';
    $html .= '<input type = "radio" name = "changeAll" id= "changeNotes" value = "0">Just this one <br>';
    $html .= 'New note: <input type = "text" name = "note" id= "note" ><br>';
    $html .= "<input type = 'hidden' name = 'JSON' value='".$_POST['JSON']."'>";
    $html .= '<input type="submit" value = "Submit" class="submit" style = "margin:5px">';
    $html .= "</form>";
  }
  popupGen($html);

?>
