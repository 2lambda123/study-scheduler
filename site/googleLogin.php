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

<div id="googleLogin">
<a href="../scripts/googleAPI.php"><img src="../site/googleLogin.png"></a>
</div>
<script src="../site/jquery.min.js"></script>
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