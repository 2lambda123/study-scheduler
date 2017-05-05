<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="../ajax/ajax.js"></script>
<script>
//Toggles visibilty on weekdays forms when repetition select changes
$(document).on('change', '#rep', function() {
    $( "select option:selected" ).each(function() {
		$('#weekDays').toggle();
	});
});

//Toggles visibility on the next element when clicked, used for the habits table to show events for (this) habit
$(document).on('click', '.toggle',function(event){$(this).next().toggle();});
</script>
<form class="form" action='../scripts/receivePersonal.php' method='post'>
	<h3>New habit</h3>
	<div>
		Habit name: <input name="name" type="text"/><br/>
		Number of repetitions: <input name="duration" type="number"/><br/>
		Repetition: 
		<select id="rep" name="repetition">
			<option>Daily</option>
			<option selected>Weekly</option>
		</select><br/>
		Location: <input name="location" type="text"/><br/>
		from: <input name="dtstart" type="time"/> to: <input name="dtend" type="time"/><br/>
		
		Estimated travel time (minutes): <input name="travel" type="number"/><br/><br/>
	<div id="weekDays">
		Which days of week I will repeat (for weekly).
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
		<input type="submit"/>
	</div>
</form>

<?php include '../ajax/showHabits.php'?>