<?php
  if($_POST["JSON"]){
    $event = json_decode($_POST["JSON"], false);
    echo '<div id = "changeEvent">';
    echo "<h1> Change/Remove: " . $event->SUMMARY . " </h1>";
    echo '<form id = "changeForm">';
    echo 'Remove event <input type = "radio" name = "remove" value = "1"><br>';
    echo 'Remove &amp reschedule <input type = "radio" name = "remove" value = "2"><br>';
    echo "Change the start/end times, stay within: " . substr($event -> DTSTART, 9, 2) . ":" . substr($event -> DTSTART, 11, 2) . " &amp " . substr($event -> DTEND, 9, 2) . ":" . substr($event -> DTEND, 11, 2) . "<br>";
    echo 'Start time: <br><input size = "10" type = "text" name = "newStartH" value = "' . substr($event->DTSTART, 9, 2) . '"> h &nbsp';
    echo '<input size = "10" type = "text" name = "newStartM" value = "' . substr($event->DTSTART, 11, 2) . '"> min <br><br>';
    echo 'End time: <br><input size = "10" type = "text" name = "newEndH" value = "' . substr($event->DTEND, 9, 2) . '"> h &nbsp';
    echo '<input size = "10" type = "text" name = "newEndM" value = "' . substr($event->DTEND, 11, 2) . '"> min <br><br>';
    echo '<input type = "hidden" name = "JSON2" value="'. $_POST["JSON"] . '">';
    echo '<input type = "submit" style = "margin:5px" id = "submit">';
    echo '<div id = "close" onclick = "hideLabform();"> close </div>';
    echo "</form>";
    echo '</div>';
  }
?>
