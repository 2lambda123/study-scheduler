<?php
 //popGen receives a string and will generate a popup from it. The popup will have a "close" button by default.
 if(isset($_POST['contentHTML'])){
      $html = "<link href='../scripts/popupEvent.css' rel='stylesheet'><div id='modal'><div class='modal-content' ";
	  if(isset($_POST['JSON'])) $html .= "value= '".$_POST['JSON'];
	  $html .= "'><span class='close' onclick='this.parentElement.parentElement.outerHTML=null;'>&times;</span> ";
      $html .= $_POST['contentHTML'];
      $html .= "</div></div>";
      echo $html;
  }
?>
