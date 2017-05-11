<?php
 //popGen receives a string and will generate a popup from it. The popup will have a "close" button by default.
  function popupGen($contentHTML) {
      $html = "<link href='../scripts/popupEvent.css' rel='stylesheet'><div id='modal'><div class='modal-content'>
              <span class='close' onclick='document.getElementById(\"modal\").outerHTML=null;'>&times;</span> ";
      $html .= $contentHTML;
      $html .= "</div></div>";
      echo $html;
  }
?>
