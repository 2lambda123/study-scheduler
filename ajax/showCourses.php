<?php	
		if (session_id() == "") session_start();
		include_once '../scripts/DB.php';
		
		$db = new DB();
		
		//Get courses from database
		$result = $db -> select("SELECT COURSES FROM data WHERE ID='$_SESSION[uuid]'");
			
		$r = json_decode($result[0]['COURSES'], true);
		
		//Create a table of all courses
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
				$html .= '<th><form  class="form" action="../ajax/removeCourses.php" method="post"><input type="hidden" name="remove" value="' . $c['coursecode'] . '"><input type="submit" value="remove"></form></th>';
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
		echo $html;
?>