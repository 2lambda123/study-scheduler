<?php
		include_once 'DB.php';
		
		$db = new DB();
		$result = $db -> select("SELECT COURSES FROM data WHERE ID='c7fe7b83-2be5-11e7-b210-f0795931a7ef'");
			
		$r = json_decode($result[0]['COURSES'], true);
		$html = "";
		$html .= '<table id="courses">';
		$html .= '<tr><th>Course code</th><th>Start date</th><th>End date</th><th>Exam HP</th><th>Lab HP</th><th># of Labs</th><th></th></tr>';
		if($r) {
			foreach ($r as $c) {
				$html .= '<tr>';
				$html .= '<th>' . $c['coursecode']	. '</th>';
				$html .= '<th>' . $c['coursestart'] . '</th>';
				$html .= '<th>' . $c['courseend'] . '</th>';
				$html .= '<th>' . $c['hp_exam'] . '</th>';
				$html .= '<th>' . $c['hp_lab'] . '</th>';
				$html .= '<th>' . $c['numberoflabs'] . '</th>';
				$html .= '<th><form  class="form" action="../scripts/removeCourses.php" method="post"><input type="hidden" name="remove" value="' . $c['coursecode'] . '"><input type="submit" value="remove"></form></th>';
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
		echo $html;
?>