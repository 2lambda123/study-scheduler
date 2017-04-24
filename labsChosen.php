<?php
$user = "labs";
file_put_contents($user.='.txt',json_encode($_POST));

?>