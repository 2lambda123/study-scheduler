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
		req.open("POST", url, true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		req.onreadystatechange = function() {
			if(req.readyState == 4 && req.status == 200) {
				var return_data = req.responseText;
				event.innerHTML += return_data;
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

  <!-- Popup -->
    <div class="modal fade" id="myModal">
      <div class="modal-form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4>Change of plan?<h4>
          </div>
          <div class="modal-body">
            <form action="edit_kth.php" method="POST">
    	           <div>
    		             <label for="study"/>I want to skip this event and spend the time studying instead. </label> <input type="radio" name="study" id="study"/><br/>
    		             <label for="del"/>I want to skip this event do some other activity (not studying).  </label> <input type="radio" name="del" id="del"/><br/>
                     <br/><input type="submit" value="Submit" class="btn btn-default" align=""/>
                     <button type="button" class="btn btn-default" data-dismiss="modal" style="float : right;">Close</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</body>
</html>
