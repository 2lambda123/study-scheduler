<?php
function labFind($file){  //Formats the calenderfile with importCal
  $e = json_decode($file);
  // Find all with SUMMARY with the word "Laboration" in it
  $eLabs = array();
  for($i = 0; $i < count($e); $i++){
    if(strpos($e[$i] -> SUMMARY, 'Laboration') !== false){ //Case sensetive
      array_push($eLabs, $e[$i]);
    }
  }
  // Separate them by course ID
  $Cour = array();

  //  Find all course summarys
  $IDs = array();

  for($i = 0; $i < count($eLabs); $i++){
    if(!in_array($eLabs[$i]->SUMMARY, $IDs)){
      array_push($IDs, $eLabs[$i]->SUMMARY);
    }
  }
  //  Makes the correct number of arrays in the Cour-array
  for($i = 0; $i < count($IDs); $i++){
    array_push($Cour, array());
  }
  //  Put each lab in their corresponding field
  for($i = 0; $i < count($eLabs); $i++){
    for($j = 0; $j < count($IDs); $j++){
      if($eLabs[$i] -> SUMMARY == $IDs[$j]){
          array_push($Cour[$j], $eLabs[$i]);
        }
    }
  }
  $gatheredArray = array();
  for($i = 0; $i < count($IDs); $i++){
    for($j = 0; $j < count($Cour[$i]); $j++){
      array_push($gatheredArray, $Cour[$i][$j]);
    }
  }
  return json_encode($gatheredArray);
}

 ?>
