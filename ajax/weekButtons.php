  <?php
  include "../site/calendarLoadDays.php";

  /*updateCal takes in the value of $btn and returns a string with HTML tags of the updated calendar:
    - value = 0 --> Current week
    - value > 0 --> The coming weeks, counting from the current week.
    - value < 0 --> The  previous weeks, counting from the current week.
  */
  function updateCal($btn){
        $weekHead = assign_weekHead(getfirstday($btn));
        $weekEvent = assign_weekEvent(getfirstday($btn));
        $html = "<tr text-align='center'>" . $weekHead . "</tr><tr>" . $weekEvent . "</tr>";
        echo $html;
  }

    $btn = $_POST['key']; //$btn holds how many weeks forward or backward.
    updateCal($btn);
  ?>
