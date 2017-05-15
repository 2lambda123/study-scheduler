<?php
  if (session_id() == "") session_start();
  include_once 'DB.php';

  /*Function updateNote changes an events notes, the variable changeAll determins
  if all events with the same summary changes or if we look at unique UID.*/
  function updateNote($UID, $note, $summary, $changeAll){
    $db = new DB();
    $current = $db -> select("SELECT CURRENT FROM calendar WHERE ID='$_SESSION[uuid]'");
    $current = json_decode($current[0]["CURRENT"]);
    if($changeAll){
      for($i = 0; $i < count($current); $i++){
        if($current[$i]->SUMMARY == $summary)
          $current[$i]->NOTES = $note;
      }
    }
    else{
      for($i = 0; $i < count($current); $i++){
        if($current[$i]->UID == $UID)
          $current[$i]->NOTES = $note;
      }
    }
    $db -> query("UPDATE calendar SET CURRENT=".$db->quote(json_encode($current)) ." WHERE ID='$_SESSION[uuid]'");
  }
?>
