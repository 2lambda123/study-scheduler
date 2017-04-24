<?php
$user = "labs";
$e = json_decode(file_get_contents("test.txt"));
$count = count($e);

for ($i = 0; $i < $count; $i++) {
	if (!in_array($e[$i]->DTSTART, $_POST)) {
		echo $e[$i]->SUMMARY;
		unset($e[$i]);
		$e = array_values($e);
		$i--;
		$count = count($e);
	}
}

file_put_contents($user.='.txt',json_encode($e));

?>