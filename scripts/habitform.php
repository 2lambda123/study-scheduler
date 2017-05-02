<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="../site/ajax/ajax.js"></script>
<form class="form" action='habits.php' method='post'>
	<h3>New habit</h3>
	<div>
		Habit name: <input name="name" type="text"/><br/>
		Number of repetitions: <input name="duration" type="number"/><br/>
		Repetition: 
		<select name="repetition">
			<option>Daily</option>
			<option>Weekly</option>
		</select><br/>
		Location: <input name="location" type="text"/><br/>
		from: <input name="dtstart" type="time"/> to: <input name="dtend" type="time"/><br/>
		
		Estimated travel time (minutes): <input name="travel" type="number"/><br/><br/>
		
		Which days of week I will repeat (for monthly and weekly).
		<div>
		<label for="mo"/>Monday:</label> <input type="checkbox" name="Monday" id="mo"/><br/>
		<label for="tu"/>Tuesday:</label> <input type="checkbox" name="Tuesday" id="tu"/><br/>
		<label for="we"/>Wednesday:</label> <input type="checkbox" name="Wednesday" id="we"/><br/>
		<label for="th"/>Thursday:</label> <input type="checkbox" name="Thursday" id="th"/><br/>
		<label for="fr"/>Friday:</label> <input type="checkbox" name="Friday" id="fr"/><br/>
		<label for="sa"/>Saturday:</label> <input type="checkbox" name="Saturday" id="sa"/><br/>
		<label for="su"/>Sunday:</label> <input type="checkbox" name="Sunday" id="su"/><br/>
	</div>
		<input type="submit"/>
	</div>
</form>

<?php include 'showHabits.php'?>
<script>

$(document).on('click', '.toggle',function(event){$(this).next().toggle();});

</script>
