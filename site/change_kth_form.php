<?php
 include "popupEvent.php"; 
 
	$html = '<form action="edit_kth.php" class = "changeForm" method="POST">';
	$html .= '<h5> '. $event->SUMMARY .' </h5> <h3> <br> Would you like to skip this event? </h3>';
	$html .= '<input type="radio" name="st" value="study" checked> I want to skip this event and spend the time studying instead.<br>';
	$html .= '<input type="radio" name="st" value="del"> I want to skip this event do some other activity (not studying).<br>';
    $html .= "<input type = 'hidden' name = 'JSON2' value='".$_POST['JSON']."'>";
    $html .= '<input type="submit" value = "Submit" class="submit" style = "margin:5px">';
    $html .= "</form>";
  
  
  	popupGen($html);

?>