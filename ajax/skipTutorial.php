<?php
if(session_id() == "") session_start();
if (isset($_SESSION['tutorial']))
$_SESSION['tutorial'] += 1;
?>
