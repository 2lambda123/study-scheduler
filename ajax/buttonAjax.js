/*
When edit button is clicked, go to the right form depending on type of event, KTH or non-KRTH.
*/
$(document).on('click','.edit', function(event)
{
	var json = this.parentElement.parentElement.getAttribute('value'); //encoded json of clicked event
			console.log(this.parentElement.parentElement.className);
			if(this.parentElement.parentElement.className == 'event KTH') //KTH event
			{
				$.ajax
				({
					type: 'POST',
					url: "changeKTHForm.php", //form
					data: "JSON="+json,
					success: function(data){document.body.innerHTML+=data} //popup
				})

			}
			else //non-KTH event
			{
					$.ajax({
						type: 'POST',
						url: "changeEventsPopup.php", //fotm
						data: "JSON="+json,
						success: function(data){document.body.innerHTML+=data} //popup
				})

			}

		});

/*
When form is submitted, send input to action specified in form tag
*/
$(document).on('submit', "form", function(event){
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


/*
When next and previous buttons in calendar are clicked, notify weekButtons.php to change week
*/
$(document).on('click', '.weekBtn' , function(event){

var x = this.parentElement.getAttribute('value');

if($(this).attr('id')=='Prev')
{
	x--;
		$.ajax
		({
			type:"POST",
			url: "weekButtons.php",
			data: "key="+x,
			success: function(send)
			{
				console.log(x);
				document.getElementById('calendar').innerHTML=send;
				$('#calHead').attr('value', x);
			}
		})
	}
 else if($(this).attr('id') =='Next')
{
	x++;
		$.ajax
		({
			type:"POST",
			url: "weekButtons.php",
			data: "key="+x,
			success: function(send)
			{
				console.log(x);
				document.getElementById('calendar').innerHTML=send;
				$('#calHead').attr('value', x);
			}
		})
	}
});
