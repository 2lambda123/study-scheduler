<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="../ajax/ajax.js"></script>
<form class="form" action="../ajax/receivePersonal.php" method="POST">
	<h3>Days I don't want to study:</h3>
	<div>
		<label for="mo"/>Monday:</label> <input type="checkbox" name="Monday" id="mo"/><br/>
		<label for="tu"/>Tuesday:</label> <input type="checkbox" name="Tuesday" id="tu"/><br/>
		<label for="we"/>Wednesday:</label> <input type="checkbox" name="Wednesday" id="we"/><br/>
		<label for="th"/>Thursday:</label> <input type="checkbox" name="Thursday" id="th"/><br/>
		<label for="fr"/>Friday:</label> <input type="checkbox" name="Friday" id="fr"/><br/>
		<label for="sa"/>Saturday:</label> <input type="checkbox" name="Saturday" id="sa"/><br/>
		<label for="su"/>Sunday:</label> <input type="checkbox" name="Sunday" id="su"/><br/>
	</div>
	<h3>I normally sleep</h3>
	<div>
		<h4>from:</h4>
		<input name="sleepfrom" type="time">
		
		<h4>to:</h4>
		<input name="sleepto" type="time">
	</div>
	<div>
		<h3>Normal travel time (minutes):</h3>
		<select name="traveltime">
			<option value=15>15</option>
			<option value=30>30</option>
			<option value=45>45</option>
			<option value=60>60</option>
			<option value=90>90</option>
			<option value=120>120</option>
		</select>
	</div>
	<div>
		<h3>How long between breaks and how long breaks?</h3>
		Long: <input name="studylength" type="number"/><br/>
		Time: <input name="breaktime" type="number"><br/>
	<input type="submit"/>
</form>
