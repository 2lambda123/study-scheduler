<HTML>
<HEAD>
<meta charset="utf8">
	<link rel = "stylesheet" type = "text/css" href = "popupLabs.css">
</HEAD>
<BODY>
<?php
include 'LabFind.php';
$allLabs = labFind('MedLabbar.ics');

function popupLabs ($labs) {
	$labs = json_decode($labs, false);
	echo '<div id="popupLabs">';
	echo "<h1> Vilka labbar ska du gå på? </h1>";
	echo '<form action="labsChosen.php" target="dummyframe" id="labForm" method="post" onsubmit="hideLabform();">';
	echo '<table>';
	$c = 0;
	$lastSummary = $labs[0]->SUMMARY;
	foreach($labs as $lab) {
		if ($lab->SUMMARY !== $lastSummary) { echo "<tr></tr>"; }
		echo '<tr><th><i>';
		echo $lab->SUMMARY . "</i> - " . date('l, Y-m-d H:i', strtotime(substr($lab->DTSTART,0 ,8) . substr($lab->DTSTART,9,4))). " ";
		echo '</th><th><input type="checkbox" name="lab';
		echo $c;
		echo '" value="';
		echo $lab->DTSTART;
		echo '"';
		echo '></th></tr>';
		$c++;
		$lastSummary = $lab->SUMMARY;
	}
	echo '</table>';
	echo '<input type="submit" style="margin:5px;">';
	echo '</form></div>';
}

popupLabs($allLabs);
?>
<script>
function hideLabform() {
	document.getElementById('popupLabs').style.display = "none";
}
</script>
<iframe width="0" height="0" border="0" name="dummyframe" id="dummyframe" style="visibility:hidden"></iframe>
</BODY>
</HTML>
