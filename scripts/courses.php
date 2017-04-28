</html>
<head>

<script src="dynamic.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="../site/ajax/ajax.js"></script>
</head>
<body>
	<form class="form" action='forms.php' method='post'>
		<h3>New course</h3>
		<div id="container">
			<input type="submit"/><br/><br/>
			Course code: <input type="text" name="coursecode"/><br/>
			Exam: <input type="checkbox" name="exam"> HP: <input name="hp_exam" type="float"/><br/>
			Start of course: <input name="coursestart" type="date"/><br/>
			Exam date: <input name="courseend" type="date"/><br/><br/>
			Laboration: <input type="checkbox" name="lab"> HP: <input name="hp_lab" type="float"/><br/>
			Number of labs: <input name="numberoflabs" type="text"/><br/><br/>
			
			<a href="#" id="add" onclick="addNewField()"> [Add] </a>
			<a href="#" id="remove" onclick="removeField()">[Remove] </a><br/>
		</div>
	</form>
	<?php include 'showCourses.php'?>
</body>
</html>
