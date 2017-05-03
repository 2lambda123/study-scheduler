<?php
  include_once "gen_cal.php";

  function print_dates($day) {
    echo "<p>$day <br></p>" . assign_date($day, -2);
  }

  function assign_date($day, $week) {
      if($week < 0) {
        $currentDate = date("Y-m-d", strtotime($day . " this week"));
        $add = $currentDate  . ($week * 7) . " day";
        return date("Y-m-d", strtotime($add));
      }

      else if($week > 0) {
        $currentDate = date("Y-m-d", strtotime($day . " this week"));
        $add = $currentDate . "+" . ($week * 7) . " day";
        return date("Y-m-d", strtotime($add));
      }

      else {
        $str = $day . " this week";
        $date = date("Y-m-d", strtotime($str));
        return $date;
      }
  }

  function position($day)
	{
		$date1 = '20170508T000000Z';
		$date2 = '20170514T000000Z';
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
	
	
	function boo()
	{
		
		$pop = '<div class= "event"></div>';
		$pop = preg_replace('/<div class="event">/', '/<div class="poo">/', $pop);
		echo $pop;
	}
 ?>
