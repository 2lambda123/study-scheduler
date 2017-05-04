$(document).on('click','.edit', function(event)
{
	var json = this.parentElement.parentElement.getAttribute('value');
	console.log(json);
	$.ajax({
		type: 'POST',
		url: 'ajax.php',
		data: "name="+this.parentElement.parentElement.className,
		success: function(data){
			console.log(data);
			if(data == 0){
				$.ajax({
					type: 'POST',
					url: "Change_Remove_Studytime_events.php",
					data: "JSON="+json,
					success: function(data){document.body.innerHTML+=data}
				})

			}
		}
	})
});

$(document).on('submit', "form", function(event){
	event.preventDefault();
	var send = $(this).serialize();
	$.ajax({
		type: 'POST',
		url: $(this).attr('action'),
		data: send,
		success: function(data)
		{

			console.log(data);

			$.ajax
			({
				type: 'POST',
				url: "Distribute_Leftover_time.php",
				data: send,
				success: function(dator){ console.log(dator);}
			})

		}
	})
});

$(document).on('click', '.prev' , function(event){
	var one = $('#whichweek').attr('value');
	$.ajax
	({
		type:"POST",
		url: "WeekButtons.php",
		data: "key="+one,
		success: function(send)
		{
				if($(this).attr('id','Next')){
					 $('#whichweek').attr('value', Number(one)+1);
				 }
				 if($(this).attr('id','Prev')){
 					 $('#whichweek').attr('value', Number(one)-1);
 				 }
				document.getElementById('calendar').innerHTML=send;
				/*	$.ajax
					({
						type:"POST",
						url: "calendar.php",
						data: send,
						success: function(send2) {document.getElementById('calendar').innerHTML=send}
					})*/
		}
		})
		console.log(one);

});
