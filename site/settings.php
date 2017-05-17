<!DOCTYPE html>
<html>
<title>Calendar</title>
<link href="menubar.css" rel="stylesheet">
<link href="settings.css" rel="stylesheet">
<script src="../site/jquery.min.js"></script>
<body>
<?php include_once "../site/menubar.php";?>
<h1>Welcome to the settings page!</h1>
<ul id="settingBar"><h2 id="header2">Settings</h2>
  <li class="pSettings" id="pSettings">Personal settings</li>
    <div id="psettings">
      <li class="menuS" id="whatSettings">Change privacy settings</li>
    </div>
  <li class="pSettings" id="secSettings">Schedule</li>
    <div id="secsettings">
      <li class="menuS" id="runAlgo">Load algorithm</li>
    </div>
  <li class="pSettings" id="dataPolicy">Data policy</li>
    <div id="datapolicy">
      <li class="menuS" id="whatGather">What type of information do we gather?</li>
      <li class="menuS" id="whatUse">How do we use this information?</li>
      <li class="menuS" id="whatShare">How is this information shared?</li>
    </div>
</ul>
<div id="displaySettings"></div>

<script>
var menuVis1 = false;
var menuVis2 = false;
var menuPersData = false;
$(document).ready(function(){
    //Show hide first menu
    $("#pSettings").click(function(){
      if (menuVis1) {
        $('#psettings').css({'display':'none'});
        menuVis1 = false;
        return;
      }
      $('#psettings').css({'display':'block'});
      menuVis1 = true;
    });
    //Show hide second menu
    $("#secSettings").click(function(){
      if (menuVis2) {
        $('#secsettings').css({'display':'none'});
        menuVis2 = false;
        return;
      }
      $('#secsettings').css({'display':'block'});
      menuVis2 = true;
    });
    //Show hide information handeling settings
    $("#dataPolicy").click(function(){
      if (menuPersData) {
        $('#datapolicy').css({'display':'none'});
        menuPersData = false;
        return;
      }
      $('#datapolicy').css({'display':'block'});
      menuPersData = true;
    });
    $("#runAlgo").click(function(){
        $("#displaySettings").load('runAlgorithm.php');
    });
    $("#whatGather").click(function(){
        $("#displaySettings").load('infoSiteGather.php');
    });
    $("#whatUse").click(function(){
        $("#displaySettings").load('infoSiteUse.php');
    });
    $("#whatShare").click(function(){
        $("#displaySettings").load('infoSiteShare.php');
    });
    $("#whatSettings").click(function(){
        $("#displaySettings").load('dataSettingsForm.php');
    });
});
</script>
</body>
