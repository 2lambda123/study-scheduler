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