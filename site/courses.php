<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
<title>Courses</title>
<link href="menubar.css" rel="stylesheet">
<link href="../site/forms.css" rel="stylesheet">
</head>
<body>
  <?php include '../site/menubar.php'; ?>
  <?php include '../site/courseForm.php'; ?>
<script>
  $(document).on('change','#checkExam', function(){
    $('.hideExam').toggle();
  }
);
$('#checkLab').change(function(){
  $('.hideLab').toggle();
}
);
	$(document).on('submit', 'form', function(event) {
		event.preventDefault();
		var send = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data: send,
			success: function(data)
				{
					document.getElementById("shown").innerHTML=data; //Close popup on submission
				}
	})
});
  </script>
</body>
