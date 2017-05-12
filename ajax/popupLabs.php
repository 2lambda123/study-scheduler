 <?php
include '../scripts/findLabs.php';

function popupLabs ($labs) {
	$labs = labFind($labs);
	$labs = json_decode($labs, false);
	$html = "";
	$html .= "<h1> Vilka labbar ska du gå på? </h1>";
	$html .= '<form action="../ajax/labsChosen.php" id="labForm" method="post">';
	$html .= '<table>';
	$c = 0;
	$lastSummary = $labs[0]->SUMMARY;
	foreach($labs as $lab) {
		if ($lab->SUMMARY !== $lastSummary) { $html .= "<tr></tr>"; }
		$html .= '<tr><th><i>';
		$html .= $lab->SUMMARY . "</i> - " . date('l, Y-m-d H:i', strtotime(substr($lab->DTSTART,0 ,8) . substr($lab->DTSTART,9,4))). " ";
		$html .= '</th><th><input type="checkbox" name="lab[]';
		$html .= '" value="';
		$html .= $lab->DTSTART;
		$html .= '"';
		$html .= '></th></tr>';
		$c++;
		$lastSummary = $lab->SUMMARY;
	}
	$html .= '</table>';
	$html .= '<input type="submit" style="margin:5px;">';
	$html .= '</form>';
	return $html;
}
?>
