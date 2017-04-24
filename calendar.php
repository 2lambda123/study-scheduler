<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<link href="menubar.css" rel="stylesheet">
<link href="calendar.css" rel="stylesheet">
<script type="text/javascript" src="calendar_load_week.js" defer></script>
<style>
	.day {
	}
	.event {
		border:2px solid black;
	}
	.SUMMARY {
		font-weight: bold;
	}
	table {
		margin:0;
		padding:0;
		border-radius:0;
		height:100%;
	}
	td {
		height:100%;
	}
	.week {
		height:600px;
	}
</style>
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
                    <div class="days">
						<?php echo position(0); ?> 
                    </div>
              </td>
			  
			  
			  <td class="box">
                    <div class="days">
						<?php echo position(1); ?> 
                    </div>
              </td>
			  
	
 		  <td class="box">
                    <div class="days">
						<?php echo position(2); ?> 
                    </div>
              </td>
			  
			  <td class="box">
                    <div class="days">
						<?php echo position(3);?> 
                    </div>
              </td>
			  
			  <td class="box">
                    <div class="days">
						<?php echo position(4); ?> 
                    </div>
              </td>
			  
			  <td class="box">
                    <div class="days">
						<?php echo position(5);; ?> 
                    </div>
              </td>
			  
			 
			 <td class="box">
                    <div class="days">
						<?php echo position(6); ?> 
                    </div>
              </td>
			  
			  
			  
<!--
 
              <td class="box">
                <div id="box2"></div>
              </td>

              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td> -->
			  
			  
            </tr>
	  </table>

<?php
	
	//echo print_events('20170417T000000Z','20170424T000000Z');
	
	function position($day)
	{
		include_once "gen_cal.php";
		$date1 = '20170417T000000Z';
		$date2 = '20170424T000000Z';
		//$arr = array("Volvo", "BMW", "Toyota");
		$arr = gen_week($date1,$date2);
		
		$length = count($arr);
		$html = "";
		for($x = 0; $x < $length; $x++)
		{
			if ($x == $day)
			{
				$day = $arr[$x];
				$html.= "<div class='item'>$day</div>";
			}
		}
		return $html;
	}
?>

</body>
</html>
