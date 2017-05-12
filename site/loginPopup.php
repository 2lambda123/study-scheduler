<?php

ob_start();
include "../scripts/loginform.php";
$loginform = ob_get_clean();

include_once "../scripts/popupEvent.php";
popupGen($loginform);



?>