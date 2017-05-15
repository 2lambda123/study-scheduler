<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
<title>Routines</title>
<link href="menubar.css" rel="stylesheet">
<link href="../site/forms.css" rel="stylesheet">
</head>
<body>
  <?php include 'menubar.php'; ?>
  <?php include '../site/routineForm.php'; ?>
  <div id="shown">
  <?php include '../ajax/showPersonal.php';?>
  </div>
  </div>
  <script>
	$(document).on('submit', 'form', function(event) {
		event.preventDefault();
		var send = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: send,
			success: function(data)
				{
					document.getElementById("courses").outerHTML=data; //Close popup on submission
				}
	})
});
  </script>
</body>
