<?php
include_once '../scripts/popupEvent.php';
if (session_id() == "") session_start();
$_SESSION['originGLogin'] = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
if (isset($_SESSION['calendarPopup']) && $_SESSION['calendarPopup'] !== "") {
	popupGen($_SESSION['calendarPopup']);
	unset($_SESSION['calendarPopup']);
}
//UNSET($_SESSION['access_token']);
?>
<a  style="padding: 0px; margin-top: 4px;"href="../scripts/googleAPI.php">
<button class="loginBtn loginBtn--google">
<?php
	if (session_id() == "") session_start();

	if (isset($_SESSION['uuid']) && $_SESSION['uuid'] !== "") echo "Connect to Google";
	else echo "Login with Google";
?>
</button>
</a>
</li>
<script src="../scripts/jquery.min.js"></script>
<script>
$(document).on('submit', "#calendars", function(event){
	event.preventDefault();
	var send = $(this).serialize();
	$.ajax({
		type: 'POST',
		url: $(this).attr('action'),
		data: send,
		success: function(data)
		{
			document.getElementById("modal").outerHTML=null; //Close popup on submission
		}
	})
});
</script>
<style>
.loginBtn {
  box-sizing: border-box;
  position: relative;
  /* width: 13em;  - apply for fixed size */
  margin: 0.2em;
  padding: 0 15px 0 46px;
  border: none;
  text-align: left;
  line-height: 34px;
  white-space: nowrap;
  border-radius: 0.2em;
  font-size: 16px;
  color: #FFF;
}
.loginBtn:before {
  content: "";
  box-sizing: border-box;
  position: absolute;
  top: 0;
  left: 0;
  width: 34px;
  height: 100%;
}
.loginBtn:focus {
  outline: none;
}
.loginBtn:active {
  box-shadow: inset 0 0 0 32px rgba(0,0,0,0.1);
}

/* Google */
.loginBtn--google {
  /*font-family: "Roboto", Roboto, arial, sans-serif;*/
  background: #DD4B39;
}
.loginBtn--google:before {
  border-right: #BB3F30 1px solid;
  background: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/14082/icon_google.png') 6px 6px no-repeat;
}
.loginBtn--google:hover,
.loginBtn--google:focus {
  background: #E74B37;
}
</style>