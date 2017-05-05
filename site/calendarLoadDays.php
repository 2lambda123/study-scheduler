<?php
  include_once "genCal.php";

  /*getfirstday takes in a number value that represents a week
  and returns the date of the Monday of the chosen week in YYYYMMDDT000000Z format.

  - value = 0 --> Current weeks monday.
  - value > 0 --> The coming weeks monday, counting from the current week. Ex. value = 1 --> next week monday.
  - value < 0 --> The  previous weeks, counting from the current week. Ex. value = -2 previous previous week monday. */

  function getfirstday($week) {
      if($week < 0) {
        $currentDate = date("Y-m-d", strtotime("monday this week"));              //get date of monday of current week in format of Y-m-d
        $prevWeek = $currentDate  . ($week * 7) . " day";                         //current weeks monday goes back # of weeks.
        return date("Ymd", strtotime($prevWeek)) . "T000000Z";                    //$prevWeek converted to YYYYMMDDT000000Z format.
       }

      else if($week > 0) {
        $currentDate = date("Y-m-d", strtotime("monday this week"));
        $nextWeek = $currentDate . "+" . ($week * 7) . " day";                     //this week monday goes ahead # of weeks.
        return date("Ymd", strtotime($nextWeek)) . "T000000Z";                    //$nextWeek converted to YYYYMMDDT000000Z format.
      }

      else {
        $str = "monday this week";
        $currentDate = date("Ymd", strtotime($str)) . "T000000Z";                 //this week monday is converted to YYYYMMDDT000000Z format.
        return $currentDate;
      }
  }

  /*assign_weekHead receives the monday of the interested week in YYYYMMDDT000000Z format
  and returns a string with HTML tags <tr> and <th> that displays the dates and weekdays of the week in interest on the calendar table*/

  function assign_weekHead($firstday) {
    $html = "<tr text-align='center'>";
    $str = substr($firstday, 0, 8);                                               //gets YYYYMMDD format.
    for($days = 0; $days < 7; $days++)                                            //creates the HTML for the 7 weekdays that will display on calendar tables headcell.
    {
      $date = date("Y-m-d", strtotime($str . "+" . $days . " days "));            //creates the date of current day.
      $dayofweek = date("l", strtotime($date));                                   //creates the weekdays name of current date.
      $html .= "<th>" . $dayofweek . "<br>" . $date . "</th>";
    }
    $html .= "</tr>";                                                            //close the HTML tag and display.
    echo $html;
    }

    /*assign_weekEvent receieves the monday of the interested week in YYYYMMDDT000000Z format
    and returns a string with HTML tags <tr> and <td> that displays the events of the week in interest on the calendar table*/

    function assign_weekEvent($firstday) {
      $html="<tr>";
      $startDate = substr($firstday, 0, 8);
      $lastday = date("Ymd", strtotime($startDate . "+7 days")) . "T000000Z";     //returns the date for sunday of current week in YYYYMMDDT000000Z format.
      $arr = gen_week($firstday, $lastday);
      $length = count($arr);
      for($days = 0; $days < $length; $days++) {
            $html .= "<td class='weekData'><div class='eventbox'>" . $arr[$days] . "</div></td>";
          }
      $html .= "</tr>";
      echo $html;
      }
 ?>
