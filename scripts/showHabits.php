<?php
		include_once 'DB.php';
		
		$db = new DB();
		$result = $db -> select("SELECT HABITS FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
			
		$r = json_decode($result[0]['HABITS'], true);
		$html = "";
		
		if ($r) {
			$html .= '<table id="courses">';
			$html .= '<tr><th>Habits</th><th>Number of repetitions</th><th>Repetition</th><th>Location</th><th>from</th><th>to</th><th>ETT</th></tr>';
			foreach ($r as $c) {
				$html .= '<tr>';
				$html .= '<th>' . $c['name']	. '</th>';
				$html .= '<th>' . $c['duration'] . '</th>';
				$html .= '<th>' . $c['repetition'] . '</th>';
				$html .= '<th>' . $c['location'] . '</th>';
				$html .= '<th>' . $c['dtstart'] . '</th>';
				$html .= '<th>' . $c['dtend'] . '</th>';
				$html .= '<th>' . $c['travel'] . '</th>';
				$html .= '<th><form  class="form" action="removeHabits.php" method="post"><input type="hidden" name="remove" value="' . $c['name'] . '"><input type="submit" value="remove"></form></th>';
				$html .= '</tr>';
			}
			$html .= '</table>';
		}
		echo $html;
?>