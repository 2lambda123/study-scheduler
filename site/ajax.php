<?php
/*
If the event on wihich we pushed the edit button is a KTH event, $KTH = 0, otherwise $KTH = 1.
*/

$KTH =strcmp($_POST['name'],'event KTH');
echo ($KTH);

?> 
