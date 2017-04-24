<?php
include 'importCal.php';
$file = file_get_contents('MedLabbar.ics');
labFind($file);

function labFind($file){  //Formats the calenderfile with importCal
  $e = importCal($file);
  $e = json_decode($e);
  // Find all with SUMMARY with the word "Laboration" in it
  $eLabs = array();
  for($i = 0; $i < count($e); $i++){
    if(strpos($e[$i] -> SUMMARY, 'Laboration') !== false){ //Case sensetive
      array_push($eLabs, $e[$i]);
    }
  }
  //var_dump($eLabs);
  // Separate them by course ID
  $Cour = array(array());

  //  Find all course summarys
  $IDs = array();

  for($i = 0; $i < count($eLabs); $i++){
    if(!in_array($eLabs[$i]->SUMMARY, $IDs)){
      array_push($IDs, $eLabs[$i]->SUMMARY);
    }
  }
  //  Put each lab in their corresponding field
  for($i = 0; $i < count($eLabs); $i++){
    for($j = 0; $j < count($IDs); $j++){
        if($eLabs[$i] -> SUMMARY == $IDs[$j]){
          array_push($Cour[$j], $eLabs[$i]);
        }
    }
  }
}
 ?>
