
<?php

 //popGen tar emot en sträng och genererar en popup för den.
  function popupGen($contentHTML) {
      $html = "<link href='popupEvent.css' rel='stylesheet'><div id='modal'><div class='modal-content'><span class='close' onclick='document.getElementById(\"modal\").outerHTML=null;'>&times;</span> ";
      $html .= $contentHTML;
      $html .= "</div></div>";
      echo $html;
  }
?>
