<HTML>
<HEAD>
<meta charset="utf8">
<style>
body {
	background-color: black;
}
#popupLabs {
	background-color: white;
	position: absolute;
	left: 25%;
	top: 25%;
	width: 50%;
	padding-left: 10px;
	padding-right: 10px;
	text-align: center;
}

table {
	margin: 0 auto;
    font-family: arial, sans-serif;
    border-collapse: collapse;
}

tr {
	height: 25px;
	border-bottom: 1px solid #ddd;
}
</style>

</HEAD>
<BODY>
<?php
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
popupLabs('[{"SUMMARY":"Hej","DTSTART":"20170516T1710Z","DTEND":"20170516T1810Z","UID":null,"DESCRIPTION":null,"LOCATION":null,"AVAILABLE":true},{"SUMMARY":"Hej","DTSTART":"20170516T1820Z","DTEND":"20170516T1920Z","UID":null,"DESCRIPTION":null,"LOCATION":null,"AVAILABLE":true},{"SUMMARY":"Hej3","DTSTART":"20170516T1930Z","DTEND":"20170516T2030Z","UID":null,"DESCRIPTION":null,"LOCATION":null,"AVAILABLE":true},{"SUMMARY":"Hej4","DTSTART":"20170516T2040Z","DTEND":"20170516T2140Z","UID":null,"DESCRIPTION":null,"LOCATION":null,"AVAILABLE":true}]');
?>
<script>
function hideLabform() {
	document.getElementById('popupLabs').style.display = "none";
}
</script>
<iframe width="0" height="0" border="0" name="dummyframe" id="dummyframe" style="visibility:hidden"></iframe>
</BODY>
</HTML>