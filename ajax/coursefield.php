<?php

$courses = json_decode(file_get_contents('courses.json'));
print_r($courses);
echo count($courses);

?>