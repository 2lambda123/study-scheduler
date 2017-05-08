<!DOCTYPE html>
<html>
<title>Calendar</title>
<link href="menubar.css" rel="stylesheet">
<link href="settings.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<body>
  <ul>
    <li><a href="homepage.php">HOME </a></li>
    <li><a href="calendar.php">CALENDAR</a></li>
    <li><a href="personal_routines.php">PERSONAL ROUTINES</a></li>
    <li><a href="import_export.php">IMPORT &amp; EXPORT</a></li>
    <li><a class="active" href="settings.php">SETTINGS</a></li>
    <li style="float:right"><a href="">LOGOUT</a></li>
  </ul>
  <h1>Settings</h1>
<div id="settingBar">
  <div class="pSettings" id="pSettings">Personal settings</div>
    <div id="psettings">
      <div class="menuS" id="accountS">test me</div>
      <div class="menuS">Korv2</div>
      <div class="menuS">Korv3</div>
    </div>
  <div class="pSettings" id="secSettings">Other personal settings</div>
    <div id="secsettings">
      <div class="menuS">Hej1</div>
      <div class="menuS">Hej2</div>
      <div class="menuS">Hej3</div>
    </div>
</div>
<div id="displaySettings">
</div>

<script>
var menuVis1 = false;
var menuVis2 = false;
$(document).ready(function(){
    //Show hide first menu
    $("#pSettings").click(function(){
      if (menuVis1) {
        $('#psettings').css({'display':'none'});
        menuVisible = false;
        return;
      }
      $('#psettings').css({'display':'block'});
      menuVis1 = true;
    });
    //Show hide second menu
    $("#secSettings").click(function(){
      if (menuVis2) {
        $('#secsettings').css({'display':'none'});
        menuVisible = false;
        return;
      }
      $('#secsettings').css({'display':'block'});
      menuVis2 = true;
    });
    $("#accountS").click(function(){
        $("#displaySettings").load('account_setting.php');
    });
});
</script>
</body>
