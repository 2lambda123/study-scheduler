<?php

$event = json_decode($_POST["JSON2"], false);


if(isset($_POST["study"]))
{  
	
	
}


else
{
	$freeStart = $event -> DTSTART;	
	$freeEnd = $event -> DTEND;
	
	$arr = array('DTSTART' => $freeStart, 'DTEND' => $freeEnd);
	echo json_encode($arr);
	
}

?>