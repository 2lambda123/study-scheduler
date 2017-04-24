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

  <div id="weekHead"> "MIA" </div>

<div id= "cal">

	  <table  id= "table" style= "width: 80%" align="center">
		    <div class="days">
		        <tr>
      			  <th>Monday</th>
      			  <th>Tuesday</th>
      			  <th>Wednesday</th>
      			  <th>Thursday</th>
      			  <th>Friday</th>
      			  <th>Saturday</th>
      			  <th>Sunday</th>
		        </tr>
            <tr>
              <td class="box"> <div id="event"></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
              <td class="box"> <div></div></td>
            </tr>
		    </div>
	  </table>


</div>

<p id="demo"> </p>

<div id="block">
  <div id="smallblock"></div>
</div>

 <script>
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
</script>

<?php
	include "importCal.php";
	
	function cmp_date_val($date) 	{ return substr($date,0,8).substr($date,9,4); }
	function cmp_date($date1,$date2){ return cmp_date_val($date1) > cmp_date_val($date2); }
	function cmp_day($date1,$date2) { return intval(substr($date2,0,8)) - intval(substr($date1,0,8)); }
	function pretty_time($date) 	{ return substr($date,9,2).":".substr($date,11,2); }
	
	function collect($date_start, $date_end) {
		$file = json_decode(downloadFile("personal2.ics"));
		$events = array();
		foreach($file as $event) {
			if(cmp_date($event->DTSTART,$date_start)
				&& cmp_date($date_end,$event->DTEND))
				array_push($events,$event);
		}
		$week = array();
		$weeklength = cmp_day($date_start,$date_end);
		for($i = 0; $i < $weeklength; $i++) {
			array_push($week,array());
		}
		foreach($events as $event) {
			array_push($week[cmp_day($date_start,$event->DTSTART)],$event);
		}
		return $week;	
	}
	function  make_element($tag,$class,$content) {
		return "<$tag class='$class'>$content</$tag>";
	}
	function day($events) {
		//$events = collect($date_start,$date_end);
		$html = "";
		foreach($events as $event) {
			if($event->DTSTART == null){ continue; }
			$html.="<tr class='event'><td>";
			$html.=make_element('div','SUMMARY',$event->SUMMARY);
			$html.=make_element('div','pretty_time',pretty_time($event->DTSTART)." - ".pretty_time($event->DTEND));
			/*$html.=make_element('div','DTSTART',pretty_time($event->DTSTART));
			$html.=make_element('div','DTEND',pretty_time($event->DTEND));*/
			//$html.=make_element('div','DESCRIPTION',$event->DESCRIPTION);
			//$html.=make_element('div','LOCATION',$event->LOCATION);
			$html.="</td></tr>";
			$html.="<tr class='filler'><td>filler</td></tr>";
		}
		return $html;
	}
	function week($week) {
		$html = "<table class='week'><tr>";
		foreach($week as $day) {
			$html.= "<td><table class='day'><th>Day</th><tr class='filler'><td>filler</td></tr>";
			$html.= day($day);
			$html.= "</table></td>";
		}
		$html.="</tr></table>";
		return $html;
	}
	function print_events($date1,$date2) {
		return(week(collect($date1,$date2)));
	}
	echo print_events('20170421T000000Z','20170428T000000Z');
?>



</body>
</html>
