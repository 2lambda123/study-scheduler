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


  <!--Week Heading-->
  <div id="weekHead"> "MIA" </div>

	  <table  id= "calendar" align="center">
		        <tr text-align="center">
      			  <th>Monday</th>
      			  <th>Tuesday</th>
      			  <th>Wednesday</th>
      			  <th>Thursday</th>
      			  <th>Friday</th>
      			  <th>Saturday</th>
      			  <th>Sunday</th>
		        </tr>


            <tr>
              <td class="box">
                    <div id="box1">
                      <div id="item1">
                      </div>
                      <div id="item2">
                      </div>
                    </div>
              </td>


              <td class="box">
                <div id="box2"></div>
              </td>

              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
            </tr>
	  </table>

 <!--><script>
 function Func()
 {		var tableEl = document.getElementById("table");
	 /*for(var i=1; i<24; i++)
	 {
			var newRow = tableEl.insertRow(i);
			for(var j=0; j<7; j++)
			{
				var newCell = newRow.insertCell(j);
			}*/
	 }

}

window.onload = Func();
</script>-->

  <?php
    include "importCal.php";
    $file = json_decode(downloadFile("https://www.kth.se/social/user/216124/icalendar/488f1809dad7460089c214ab25c9ecd5f7c24f1f"));
    for($i = 0; $i < 10; $i++){
      print_r(get_object_vars($file[$i]));
      echo nl2br("\n") . nl2br("\n");
    }
    ?>

</body>
</html>
