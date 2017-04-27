<!DOCTYPE html>
<html>
<head>
<title>Calendar</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link href="menubar.css" rel="stylesheet">
<link href="calendar.css" rel="stylesheet">
<script type="text/javascript" src="calendar_load_week.js" defer></script>
<?php include "calendar_load_days.php" ?>

<script>
function clicked(event){
	

   console.log(event);
		var req = new XMLHttpRequest();
		var url = "ajax.php";
		var type = event.parentElement.parentElement.className;
		var name = "name="+type;
		console.log(name);
		req.open("POST", url, true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		var return_data = null;
		req.onreadystatechange = function() {
			if(req.readyState == 4 && req.status == 200) {
				var return_data = req.responseText;
				<!--event.innerHTML += return_data;-->
				
				if(return_data == 0)
				{
				window.open("change_kth_form.html");
				}
			}
		}
		req.send(name);  
	
}
</script>
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

  <!-- Calendar table-->
	  <table  id="calendar">
		        <tr text-align="center">
      			       <th><?php print_dates("Monday");?></th>
      			       <th><?php print_dates("Tuesday");?></th>
      			       <th><?php print_dates("Wednesday")?></th>
      			       <th><?php print_dates("Thursday");?></th>
      			       <th><?php print_dates("Friday");?></th>
      			       <th><?php print_dates("Saturday");?></th>
      			       <th><?php print_dates("Sunday");?></th>
		        </tr>

            <tr>
              <td class="box"><div class="days"><?php echo position(0);?></div></td>
              <td class="box"><div class="days"><?php echo position(1);?></div></td>
              <td class="box"><div class="days"><?php echo position(2);?></div></td>
              <td class="box"><div class="days"><?php echo position(3);?></div></td>
              <td class="box"><div class="days"><?php echo position(4);?></div></td>
              <td class="box"><div class="days"><?php echo position(5);?></div></td>
              <td class="box"><div class="days"><?php echo position(6);?></div></td>
            </tr>
	  </table>
<<<<<<< HEAD
	  
=======

  <!-- Popup (Ask User) -->
<form action="edit_kth.php" method="POST">
    <div class="modal fade" id="myModal">
      <div class="modal-dialog">
      <div class="modal-form" role="dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4>Change of plan?<h4>
          </div>
          <div class="modal-body">
              <input type="radio" name="study" value="study"/> I want to skip this event and spend the time studying instead.<br>
              <input type="radio" name ="study" value="del"/> I want to skip this event do some other activity (not studying).<br>
              </br><input type="submit" value="Submit" class="btn btn-default"/>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
>>>>>>> 7b7065fbafa474b423af9e0aa933dd6111113c4e
</body>
</html>
