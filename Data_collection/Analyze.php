<?php

$collection = json_decode(file_get_contents("Collection.txt"));
$course = json_decode(file_get_contents("Course.txt"));
$habit = json_decode(file_get_contents("Habit.txt"));

print_r($collection);
echo "<br><br>";
print_r($course);
echo "<br><br>"; 
print_r($habit);

function analyze ($events) {
	
	
}
?>