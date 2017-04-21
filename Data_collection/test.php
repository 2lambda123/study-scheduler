<?php

$d1 = "20170421T1230Z";
$d2 = "20170421T1450Z";
$d3 = strtotime($d2) - strtotime($d1);
echo $d3 . "<br>";
echo $d3/60;


?>