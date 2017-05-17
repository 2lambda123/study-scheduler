<script src="../ajax/dynamic.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<div id = "formDiv">
		<h1>Courses</h1>
	<form class="form" action='../ajax/receivePersonal.php' method='post'>
		<table id = "formTable">
			<tr><th>Course code:</th><th><input type="text" name="coursecode"/></th></tr>
			<tr><th>Exam: <input id = "checkExam" type="checkbox" name="exam"></th></tr>
			<tr class = "hideExam" style = "display:none"	><th>HP: </th><th><input name="hp_exam" type="float"/></th></tr>
			<tr ><th>Start of course: </th><th><input name="coursestart" type="date" value="YYYY-MM-DD"/></th></tr>
			<tr><th>End of course: </th><th><input name="courseend" type="date" value="YYYY-MM-DD"/></th></tr>
			<tr><th>Laboration: <input id = "checkLab"type="checkbox" name="lab"></th></tr>
			<tr class = "hideLab" style = "display:none"><th>HP: </th><th><input name="hp_lab" type="float"/></th></tr>
			<tr class = "hideLab" style = "display:none"><th>Number of labs: </th><th><input name="numberoflabs" type="text"/></th></tr>
			<tr><th><a href="#" id="add" onclick="addNewField()"> [Add course assignment] </a></th>
			<th><a href="#" id="remove" onclick="removeField()">[Remove course assignment] </a></th></tr>
		</table>
		<div id = a></div>
		<input type="submit"/ value = "Submit" >
	</form>
	<div id = "shown">
	<?php include '../ajax/showCourses.php'?>
	</div>
	</div>
