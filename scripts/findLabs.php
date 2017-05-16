<?php
//Old name: LabFind.php
//Function labFind recieves a json_encoded event array. It returns an json
//encoded array with all the labs from the recieved calendar.
include_once '../algorithm/find.php';
function labFind($file){
  $e = json_decode($file);
  $eLabs = array();

  $now = time();
  // Finds all events with the SUMMARY that contasins the word "Laboration".
  for($i = 0; $i < count($e); $i++){
    //A guard to exclude all events that happen before $now
		if(strtotime(substr(eventTimeFloat($e[$i] -> DTSTART), 0, 8) . substr(eventTimeFloat($e[$i] -> DTSTART), 10, 6)) <= eventTimeFloat($now)) {
			continue;
    }
    if(strpos($e[$i] -> SUMMARY, 'Laboration') !== false){ //Case sensetive
      array_push($eLabs, $e[$i]);
    }
  }

  // Double array in which the first array index specifies what course and the
  // second array contains an lab event from that course
  $Cour = array();

  // Array used to match an event to an array slot in $Cour, each index
  // represents a index in $Cour s first/top array (course IDs).
  $IDs = array();

  //  Gives each course an index, signed by it's summary
  for($i = 0; $i  < count($eLabs); $i++){
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
  // Transform the 2D-array to a 1D array (in correct order)
  $gatheredArray = array();
  for($i = 0; $i < count($IDs); $i++){
    for($j = 0; $j < count($Cour[$i]); $j++){
      array_push($gatheredArray, $Cour[$i][$j]);
    }
  }
  return json_encode($gatheredArray);
}

 ?>
