<script src="../scripts/jquery.min.js"></script>
<script src="../ajax/buttonAjax.js"></script>
<script>
//Toggles visibilty on weekdays forms when repetition select changes
$(document).on('change', '#rep', function() {
    $( "select option:selected" ).each(function() {
		$('#weekDays').toggle();
    var e = document.getElementById('DaysOrWeek');
    if (e.innerHTML.substring(10,15) == "weeks") {
      e.innerHTML = 'Number of days: <input name="duration" type="number">';
    } else {
      e.innerHTML = 'Number of weeks: <input name="duration" type="number">';
    }
	});
});

//Toggles visibility on the next element when clicked, used for the habits table to show events for (this) habit
$(document).on('click', '.toggle',function(event){$(this).next().toggle();});
</script>
<form class="form" action='../ajax/receivePersonal.php' method='post'>
	<h3>New habit</h3>
	<div>
		Habit name: <input name="name" type="text"/><br/>
    from: <input name="dtstart" type="time"/> to: <input name="dtend" type="time"/><br/><br />
    Habit will go on for:
    <select id="rep" name="repetition">
      <option>Day(s)</option>
      <option selected>Week(s)</option>
    </select><br/>
    <div id="DaysOrWeek">Number of weeks: <input name="duration" type="number"/></div><br/>

	<div id="weekDays">
		Which days of the week will the habit occur?
	<div>
		<label for="mo"/>Monday:</label> <input type="checkbox" name="Monday" id="mo"/><br/>
		<label for="tu"/>Tuesday:</label> <input type="checkbox" name="Tuesday" id="tu"/><br/>
		<label for="we"/>Wednesday:</label> <input type="checkbox" name="Wednesday" id="we"/><br/>
		<label for="th"/>Thursday:</label> <input type="checkbox" name="Thursday" id="th"/><br/>
		<label for="fr"/>Friday:</label> <input type="checkbox" name="Friday" id="fr"/><br/>
		<label for="sa"/>Saturday:</label> <input type="checkbox" name="Saturday" id="sa"/><br/>
		<label for="su"/>Sunday:</label> <input type="checkbox" name="Sunday" id="su"/><br/>
	</div>
	</div>
  <br/>
  Location: <input name="location" type="text"/><br/>
  Estimated travel time (minutes): <input name="travel" type="number"/><br/><br/>
		<input type="submit" value="Send"/>
	</div>
</form>

<?php include '../ajax/showHabits.php'?>
