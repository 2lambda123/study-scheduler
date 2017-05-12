<?php
  include '../scripts/updateNotes.php';
  if (session_id() == "") session_start();
  include_once '../scripts/DB.php';
  $event = json_decode($_POST["JSON"], false);
  if(isset($_POST["changeAll"])){
    updateNote($event->UID,  $_POST["note"], $event->SUMMARY, $_POST["changeAll"]);
  }
  else{
    echo "<br> ERROR, no change value has been inserted";
  }
?>
