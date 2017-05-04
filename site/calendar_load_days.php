<?php
  include_once "gen_cal.php";

  /*getfirstday takes in a number value that represents a week
  and returns the date of the Monday of the chosen week in YYYYMMDDT000000Z format.

  - value = 0 --> Current weeks monday.
  - value > 0 --> The coming weeks monday, counting from the current week. Ex. value = 1 --> next week monday.
  - value < 0 --> The  previous weeks, counting from the current week. Ex. value = -2 previous previous week monday. */

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

  /*assign_weekHead receives the monday of the interested week in YYYYMMDDT000000Z format
  and returns a string with HTML tags <tr> and <th> that displays the dates and weekdays of the week in interest on the calendar table*/

  function assign_weekHead($firstday) {
    $html = "<tr text-align='center'>";
    $str = substr($firstday, 0, 8);
    for($days = 0; $days < 7; $days++)
    {
      $date = date("Y-m-d", strtotime($str . "+" . $days . " days "));
      $dayofweek = date("l", strtotime($date));
      $html .= "<th>" . $dayofweek . "<br>" . $date . "</th>";
    }
    $html .= "</tr>";
    echo $html;
    }

    /*assign_weekEvent receieves the monday of the interested week in YYYYMMDDT000000Z format
    and returns a string with HTML tags <tr> and <td> that displays the events of the week in interest on the calendar table*/

    function assign_weekEvent($firstday) {
      $html="<tr>";
      $firstday;
      $startDate = substr($firstday, 0, 8);
      $lastday = date("Ymd", strtotime($startDate . "+7 days")) . "T000000Z";
      $arr = gen_week($firstday, $lastday);
      $length = count($arr);
      for($days = 0; $days < $length; $days++) {
            $html .= "<td class='box'><div class='days'><div class='item'>" . $arr[$days] . "</div></div></td>";
          }
      $html .= "</tr>";
      echo $html;
      }


  function position($day)
	{
    $date1 = "20170327T000000Z";
    $date2 = "20170402T000000Z";
		$arr = gen_week($date1, $date2);
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


	function boo()
	{

		$pop = '<div class= "event"></div>';
		$pop = preg_replace('/<div class="event">/', '/<div class="poo">/', $pop);
		echo $pop;
	}
 ?>
