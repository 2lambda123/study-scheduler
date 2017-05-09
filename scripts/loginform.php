<?php
	$form = "<form id='loginForm' class='loginForm' method='POST' action='../scripts/loginform.php'>";
	$endForm = "</form>";
	$loginForm = 
<<<EOF
	Username:<input name='username' type='text'/>	
	Password:<input name='password' type='password'/> 
	<input type='submit' value='Login'/>
EOF;
	$logoutForm = "<input type='hidden' name='logout' value='on'/><input type='submit' value='logout'/>";
		
	if(session_id() == "")  session_start();
	if(isset($_GET['sessionDump'])) var_dump( $_SESSION );
		
	if(isset($_POST['logout'])){
		if(session_id() !== "") 
			session_destroy();
		
		echo $loginForm;
	}
	else if(isset($_POST['username'],$_POST['password'])) {
		
		include_once 'DB.php';
		$db = new DB();
		$user = $db->quote($_POST['username']);
		$password = hash('sha256',$_POST['password']);
		$query = "SELECT * FROM user WHERE USERNAME=$user";

		$results = $db->select($query);
		if(isset($results[0])) {
			if($results[0]['PASSWORD'] == $password){
				$_SESSION['uuid'] = $results[0]['ID'];
				$_SESSION['username'] = $results[0]['USERNAME'];
				echo $logoutForm;
			}
			else echo "invalid! ".$loginForm;
		}
		else
			echo "invalid! ".$loginForm;
	}
	else if(isset($_SESSION['uuid']))
		echo $form.$logoutForm.$endForm;
	else echo $form.$loginForm.$endForm;
	
?>
<script src="../site/jquery.min.js"></script>
<script>
$(document).on('submit','#loginForm', function(event) {
	event.preventDefault();
	console.log($(this).serialize());
	$.ajax ({
		type: $(this).attr('method'),
		url: $(this).attr('action'),
		data: $(this).serialize(),
		success: function(data){
			window.location.reload();
			//document.getElementById('loginForm').innerHTML = data;
		}
	})
});
</script>