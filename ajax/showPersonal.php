<style>
th { color: #555; }
th:nth-child(odd) {color:black;}
</style>
<?php
		if (session_id() == "") session_start();
		include_once '../scripts/DB.php';

		$db = new DB();

		$r = null;
		if(isset($_SESSION['uuid'])){
			//Gets ROUTINES from database
			$query = "SELECT ROUTINES FROM data WHERE ID='".$_SESSION['uuid']."'";
			$result = $db -> select($query);
			$r = (isset($result[0]['ROUTINES'])) ? json_decode($result[0]['ROUTINES'], true) : null;
    }

		$html = "";
    $c = $r;
		//Create a table of all ROUTINES
		if ($r) {
			$html .= '<table id="courses">';
			$html .= '<tr><th >Days I don\'t want to study </th><th >Sleep from </th><th >Sleep to  </th><th >Traveltime</th><th >Study length</th><th >Break length</th></tr>';
				$html .= '<tr padding ="0px" margin valign="top"= "0px"; class="toggle">';
        $html .= '<th>';
        if(isset($c['Monday'])) $html .= 'Monday <br>';
        if(isset($c['Tuesday'])) $html .= 'Tuesday <br>';
        if(isset($c['Wednesday'])) $html .= 'Wednesday <br>';
        if(isset($c['Thursday'])) $html .= 'Thursday <br>';
        if(isset($c['Friday'])) $html .= 'Friday <br>';
        if(isset($c['Saturday'])) $html .= 'Saturday <br>';
        if(isset($c['Sunday'])) $html .= 'Sunday <br>';
        $html .= '</th>';
				$html .= '<th>' . $c['sleepfrom'] . '</th>';
				$html .= '<th>' . $c['sleepto'] . '</th>';
				$html .= '<th>' . $c['traveltime'] . '</th>';
				$html .= '<th>' . $c['studylength'] . '</th>';
				$html .= '<th>' . $c['breaktime'] . '</th>';
				$html .= '</tr>';
			$html .= '</table>';
		}
		//Echo everything we created
		echo $html;
?>
