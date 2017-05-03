
<?php
  function popupGen($contentHTML) {
      //$html = "<script type='text/javascript' src='popupEvent.js' defer></script> <link href='popupEvent.css' rel='stylesheet'><div id='modal'><div class='modal-content'><span class='close' onclick='removeMod()'>&times;</span> ";
      $html = "<link href='popupEvent.css' rel='stylesheet'><div id='modal'><div class='modal-content'><span class='close' onclick='document.getElementById(\"modal\").outerHTML=null;'>&times;</span> ";
      $html .= $contentHTML;
      $html .= "</div></div>"; 
      echo $html;
  }
?>


