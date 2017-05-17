<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<div id="formDiv">
<h1>Personal Routines</h1>
<form class="form" action="../ajax/receivePersonal.php" method="POST">
	<table id="formTable">
	<tr><th>Days I don't want to study:</th>
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
	<tr><th>I normally sleep from: </th>
	<th><input name="sleepfrom" type="time" value="22:00"></th></tr>

	<tr><th>I normally sleep to:</th>
	<th><input name="sleepto" type="time" value="06:00"></th></tr>
	<tr><th>Normal travel time (minutes):</th>
	<th>
		<select name="traveltime">
			<option value=15>15</option>
			<option value=30>30</option>
			<option value=45>45</option>
			<option value=60>60</option>
			<option value=90>90</option>
			<option value=120>120</option>
		</select>
	</th></tr>
	<tr><th>I want to study for (minutes): </th><th><input name="studylength" type="number"/></th></tr>
	<tr><th>Then I want to take a break for (minutes): </th><th><input name="breaktime" type="number"></th></tr>
	</table>
	<input type="submit" class="btn" value="Submit"/>
</form>
