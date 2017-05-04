/*
Handles what happens when buttons are clicked and forms are submitted from inside popup.
*/

/*
When edit button in calendar is clicked, open popup with the right form. 
There are two different forms for KTH events and other events.
*/ 
$(document).on('click','.edit', function(event)
{
	var json = this.parentElement.parentElement.getAttribute('value'); //holds the event as encoded json
	$.ajax({
		type: 'POST',
		url: 'ajax.php',
		data: "name="+this.parentElement.parentElement.className, //holds either KTH or Other, dependin on what was clicked.
		success: function(data){
			if(data == 1) //If not a KTH event
			{
				$.ajax({
					type: 'POST',
					url: "Change_Remove_Studytime_events.php", //form
					data: "JSON="+json,
					success: function(data){document.body.innerHTML+=data} //form pops up
				})

			}
			else //If a KTH event
			{
				$.ajax({
					type: 'POST',
					url: "change_kth_form.php", //form
					data: "JSON="+json,
					success: function(data){document.body.innerHTML+=data} //form pops upp
				})

			}

		}
	})
});

/*
Gather info from forms on submit and send it to the script that handles it.
*/
$(document).on('submit', "form", function(event){
	event.preventDefault();
	var send = $(this).serialize();
	$.ajax({
		type: 'POST',
		url: $(this).attr('action'), //send info to whichever script the form has as action
		data: send,
		success: function(data)
		{
<<<<<<< HEAD
			document.getElementById("modal").outerHTML=null; //close popup on submit
			
			//we decided that Distribute_Leftover_time is called from the scripts directly instead.
			/*	
			$.ajax
			({
				type: 'POST',	
				url: "Distribute_Leftover_time.php",
				data: send,
				success: function(dator){ console.log(dator);}
			})  */
			
=======
			console.log(data);

			document.getElementById("modal").outerHTML=null;

		console.log(data);
>>>>>>> 8e7b4fafa7020a4026c29d79b7a152e94f624f6b
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
