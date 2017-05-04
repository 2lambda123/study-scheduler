<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    table tr th{
      border: 2px solid black;
    }

    th{
      height: 100px;
      font: black;
    }

    table {
      padding: 10px 14px;
    	background-color:#ccc;
      table-layout: fixed;
    	border-radius: 25px;
      width: 80%;
      margin: auto;
    }
  </style>
</head>
<body>
  <table> <tr><?php assign_week(getfirstday(0))?></tr>
  </table>
</body>
</html>

<?php
    function getfirstday($week) {
        if($week < 0) {
          $currentDate = date("Y-m-d", strtotime("monday this week"));
          $prevWeek = $currentDate  . ($week * 7) . " day";
          return date("Ymd", strtotime($prevWeek)) . "T000000Z";
        }

        else if($week > 0) {
          $currentDate = date("Y-m-d", strtotime("monday this week"));
          $nextWeek = $currentDate . "+" . ($week * 7) . " day";
          return date("Ymd", strtotime($nextWeek)) . "T000000Z";
        }

        else {
          $str = "monday this week";
          $currentDate = date("Ymd", strtotime($str)) . "T000000Z";
          return $currentDate;
        }
    }

    function assign_week($firstday) {
      $html = "";
      $str = substr($firstday, 0, 8);
      for($days = 0; $days < 7; $days++)
      {
        $date = date("Y-m-d", strtotime($str . "+" . $days . " days "));
        $dayofweek = date("l", strtotime($date));
        $html .= "<th>" . $dayofweek . "<br>" . $date . "</th>";
      }
        echo $html;
      }

 ?>
