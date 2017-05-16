<?php
if(session_id() == "") session_start();
if (isset($_SESSION['tutorial']))
unset($_SESSION['tutorial']);
?>
