<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<link href="menubar.css" rel="stylesheet">
<link href="calendar.css" rel="stylesheet">
<script type="text/javascript" src="calendar_load_week.js" defer></script>

</head>

<body>
  <ul>
    <li><a href="homepage.php">HOME </a></li>
    <li><a class="active" href="calendar.php">CALENDAR</a></li>
    <li><a href="personal_routines.php">PERSONAL ROUTINES</a></li>
    <li><a href="import_export.php">IMPORT &amp; EXPORT</a></li>
    <li><a href="settings.php">SETTINGS</a></li>
    <li style="float:right"><a href="">LOGOUT</a></li>
  </ul>

  <div id="weekHead"> "MIA" </div>
 
	  <table  id= "table" style= "width: 80%" align="center">
		<div class="days"> 
		<tr>
		  
			  <th class= "poop">Monday</th>
			  <th class= "poop" >Tuesday</th>
			  <th class= "poop">Wednesday</th>
			  <th class= "poop">Thursday</th>
			  <th class= "poop">Friday</th>
			  <th class= "poop">Saturday</th>
			  <th class= "poop">Sunday</th>
			
		  </tr> 
		  </div>
	  </table>
  
  
<p id="demo"> </p>  
  
 <script>
 function Func()
 {		var tableEl = document.getElementById("table");
	 for(var i=1; i<100; i++)
	 {		 
			var newRow = tableEl.insertRow(i);
			for(var j=0; j<7; j++)
			{
				var newCell = newRow.insertCell(j);
	

			}
	document.getElementById("demo").innerHTML= "boooo";
	
	 }
}

window.onload = Func();
</script> 
  
  
  
</body>
</html>
