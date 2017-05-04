  <?php
  include "calendar_load_days.php";

  //updateCal takes in the value that ...  
  function updateCal($btn){
        $weekHead = assign_weekHead(getfirstday($btn));
        $weekEvent = assign_weekEvent(getfirstday($btn));
        $html = "<tr text-align='center'>" . $weekHead . "</tr><tr>" . $weekEvent . "</tr>";
        echo $html;
      /*else {
        $counter--;
        $weekHead = assign_weekHead(getfirstday($counter));
        $weeEvent = assign_weekEvent(getfirstday($counter));
        $html = "<table id='calendar'><tr text-align='center'>" . $weekHead . "</tr><tr>" . $weekEvent . "</tr></table>";
        echo $html;
      }*/
  }

    $btn = $_POST['key'];
    updateCal($btn);
  ?>
