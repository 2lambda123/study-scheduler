<html>
<?php 
$user = "mila";
echo "success!";



file_put_contents($user.='.txt',json_encode($_POST));
?>
</html>
