<?php
  include "gen_cal.php";

  /*function convert($date)
  {
    $date =

    return;
  }*/

  function print_dates($day) {
    echo "$day \n" . assign_date($day, 0);
  }

  function assign_date($day, $week) {
      if($week < 0) {
        $str = "+" . $week . " weeks";
        $currentDate = strtotime($str);
        $date = date("Y-m-d", strtotime($day, $currentDate));
        return $date;
      }
      else if($week > 0) {
        $str = "+" . $week . " weeks";
        $currentDate = strtotime($str);
        $date = date("Y-m-d", strtotime($day, $currentDate));
        return $date;
      }
      else {
        return $date = date("Y-m-d", strtotime($day));
      }
  }

  function position($day)
	{
		include_once "gen_cal.php";
		$date1 = '20170417T000000Z';
		$date2 = '20170424T000000Z';
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
