<?php
 include "../scripts/popupEvent.php";

    $event = json_decode($_POST["JSON"], false);
	  $html = '<form action="../ajax/editKTH.php" class = "changeForm" method="POST">';
	  $html .= '<h5> '. $event->SUMMARY .' </h5> <h3> <br> Would you like to skip this event? </h3>';
	  $html .= '<input type="radio" name="st" id="st" value="1" checked> I want to skip this event and spend the time studying instead.<br>';
	  $html .= '<input type="radio" name="st" id="st" value="0"> I want to skip this event do some other activity (not studying).<br>';
    $html .= "<input type = 'hidden' name = 'JSON2' value='".$_POST['JSON']."'>";
    $html .= '<input type="submit" value = "Submit" class="submit" style = "margin:5px">';
    $html .= "</form>";

  	popupGen($html);

?>
