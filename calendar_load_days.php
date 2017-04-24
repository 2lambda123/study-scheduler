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

 ?>
