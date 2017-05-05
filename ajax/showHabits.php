<?php
		include_once '../scripts/DB.php';
		
		$db = new DB();
		//Gets habits from database
		$result = $db -> select("SELECT HABITS FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
			
		$r = json_decode($result[0]['HABITS'], true);
		$html = "";
		
		//Gets all habit events from database
		$result1 = $db -> select("SELECT HABITS FROM calendar WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
		$r1 = json_decode($result1[0]['HABITS'], true);
		
		//Create a table of all habits
		if ($r) {
			$html .= '<table id="courses">';
			$html .= '<tr><th>Habits</th><th>Number of repetitions</th><th>Repetition</th><th>Location</th><th>from</th><th>to</th><th>ETT</th></tr>';
			foreach ($r as $c) {
				$html .= '<tr class="toggle">';
				$html .= '<th>' . $c['name']	. '</th>';
				$html .= '<th>' . $c['duration'] . '</th>';
				$html .= '<th>' . $c['repetition'] . '</th>';
				$html .= '<th>' . $c['location'] . '</th>';
				$html .= '<th>' . $c['dtstart'] . '</th>';
				$html .= '<th>' . $c['dtend'] . '</th>';
				$html .= '<th>' . $c['travel'] . '</th>';
				$html .= '<th><form  class="form" action="../ajax/removeHabits.php" method="post"><input type="hidden" name="remove" value="' . $c['name'] . '"><input type="submit" value="remove"></form></th>';
				$html .= '</tr>';
				$html .= '<tr style="Display:none;"><td colspan="7">';
				$html .= '<table class="events">';
				$html .= '<tr>';
				$html .= '<th>Name</th>';
				$html .= '<th>DTSTART</th>';
				$html .= '<th>DTEND</th>';
				$html .= '</tr>';
				//Create a table of all events of the habit after each habit
				foreach ($r1 as $d) {
					if ($d['SUMMARY'] == $c['name']) {
						$html .= '<tr>';
						$html .= '<th>' . $d['SUMMARY'] . '</th>';
						$html .= '<th>' . $d['DTSTART'] . '</th>';
						$html .= '<th>' . $d['DTSTART'] . '</th>';
						$html .= '</tr>';
					}
				}
				$html .= '</table>';
				$html .= '</td></tr>';
			}
			$html .= '</table>';
		}
		//Echo everything we created
		echo $html;
?>