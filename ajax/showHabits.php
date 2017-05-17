<?php
		if (session_id() == "") session_start();
		include_once '../scripts/DB.php';

		$db = new DB();

		$r = null;
		$r1 = null;
		if(session_id() == "") session_start();
		if(isset($_SESSION['uuid'])){
			//Gets habits from database
			$query = "SELECT HABITS FROM data WHERE ID='".$_SESSION['uuid']."'";
			$result = $db -> select($query);
			$r = (isset($result[0]['HABITS'])) ? json_decode($result[0]['HABITS'], true) : null;

			//Gets all habit events from database
			$query1 = "SELECT HABITS FROM calendar WHERE ID='".$_SESSION['uuid']."'";
			$result1 = $db -> select($query1);
			$r1 = (isset($result1[0]['HABITS'])) ? json_decode($result1[0]['HABITS'], true) : null;
		}


		$html = "";
		$html .= '<table id="courses">';
		$html .= '<tr><th>Habits</th><th>Number of repetitions</th><th>Repetition</th><th>Location</th><th>from</th><th>to</th><th>ETT</th></tr>';

		//Create a table of all habits
		if ($r) {
			foreach ($r as $c) {
				$html .= '<tr class="toggle">';
				$html .= '<th>' . $c['name']	. '</th>';
				$html .= '<th>' . $c['duration'] . '</th>';
				$html .= '<th>' . $c['repetition'] . '</th>';
				$html .= '<th>' . $c['location'] . '</th>';
				$html .= '<th>' . $c['dtstart'] . '</th>';
				$html .= '<th>' . $c['dtend'] . '</th>';
				$html .= '<th>' . $c['travel'] . '</th>';
				$html .= '<th><form  class="form" action="../ajax/removeHabits.php" method="post"><input type="hidden" name="remove" value="' . $c['name'] . '"><input type="submit" class="btn" value="remove"></form></th>';
				$html .= '</tr>';
				$html .= '<tr style="Display:none;"><td colspan="7">';
				$html .= '<table class="events">';
				$html .= '<tr>';
				$html .= '<th>Name</th>';
				$html .= '<th>DTSTART</th>';
				$html .= '<th>DTEND</th>';
				$html .= '</tr>';
				//Create a table of all events of the habit after each habit
				if ($r1) {
					foreach ($r1 as $d) {
						if ($d['SUMMARY'] == $c['name']) {
							$html .= '<tr>';
							$html .= '<th>' . $d['SUMMARY'] . '</th>';
							$html .= '<th>' . $d['DTSTART'] . '</th>';
							$html .= '<th>' . $d['DTEND'] . '</th>';
							$html .= '</tr>';
						}
					}
				}
				$html .= '</table>';
				$html .= '</td></tr>';
			}

		}
		$html .= '</table>';
		//Echo everything we created
		echo $html;
?>
