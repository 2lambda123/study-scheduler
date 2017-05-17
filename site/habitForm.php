<script src="../site/jquery.min.js"></script>
<script src="../ajax/buttonAjax.js"></script>
<script>
//Toggles visibilty on weekdays forms when repetition select changes
$(document).on('change', '#rep', function() {
    $( "select option:selected" ).each(function() {
		$('#weekDays').toggle();
    var e = document.getElementById('DaysOrWeek');
    if (e.innerHTML.substring(10,15) == "weeks") {
      e.innerHTML = 'Number of days: ';
    } else {
      e.innerHTML = 'Number of weeks: ';
    }
	});
});

//Toggles visibility on the next element when clicked, used for the habits table to show events for (this) habit
$(document).on('click', '.toggle',function(event){$(this).next().toggle();});
</script>
<div id="formDiv">
<h1>Habits</h1>
<form class="form" action='../ajax/receivePersonal.php' method='post'>
<table id="formTable">
	<tr><th>Habit name:</th> <th><input name="name" type="text"/></th></tr>
    <tr><th>from:</th><th><input name="dtstart" type="time" value="00:00"/></th></tr>
	<tr><th>to:</th><th><input name="dtend" type="time" value="00:00"/></th></tr>
    <tr><th>Habit will go on for:</th>
    <th>
		<select id="rep" name="repetition">
			<option>Day(s)</option>
			<option selected>Week(s)</option>
		</select>
	</th></tr>
	<tr><th><div id="DaysOrWeek">Number of weeks:</th><th><input name="duration" type="number"/></div></th></tr>

	<tr id="weekDays"><th>
		Which days of the week will the habit occur?
	</th>
	<th>
	<div>
		<label for="mo"/>Monday:</label> <input type="checkbox" name="Monday" id="mo"/><br/>
		<label for="tu"/>Tuesday:</label> <input type="checkbox" name="Tuesday" id="tu"/><br/>
		<label for="we"/>Wednesday:</label> <input type="checkbox" name="Wednesday" id="we"/><br/>
		<label for="th"/>Thursday:</label> <input type="checkbox" name="Thursday" id="th"/><br/>
		<label for="fr"/>Friday:</label> <input type="checkbox" name="Friday" id="fr"/><br/>
		<label for="sa"/>Saturday:</label> <input type="checkbox" name="Saturday" id="sa"/><br/>
		<label for="su"/>Sunday:</label> <input type="checkbox" name="Sunday" id="su"/><br/>
	</div>
	</th></tr>
	<tr><th>Location:</th><th><input name="location" type="text"/></th></tr>
	<tr><th>Estimated travel time (minutes):</th><th><input name="travel" type="number"/></th></tr>
	</table>
	<input type="submit" class="btn" value="Submit"/>
</form>

<div id="shown">
<?php include '../ajax/showHabits.php'?>
</div>
