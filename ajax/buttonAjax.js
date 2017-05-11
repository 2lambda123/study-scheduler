  //Calculate week number.
  Date.prototype.getWeek = function() {
        var onejan = new Date(this.getFullYear(), 0, 1);
        return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
  }

  var week = (new Date()).getWeek();
  document.getElementById('weekHead').innerHTML = "Week: " + week;


/*
When edit button is clicked, go to the right form depending on type of event, KTH or non-KRTH.
*/
$(document).on('click','.edit', function(event)
{
	var json = this.parentElement.parentElement.getAttribute('value'); //encoded json of clicked event
			//console.log(this.parentElement.parentElement.className);
			if(this.parentElement.parentElement.className == 'event KTH') //KTH event
			{
				$.ajax
				({
					type: 'POST',
					url: "../ajax/changeKTHForm.php", //form
					data: "JSON="+json,
					success: function(data){document.body.innerHTML+=data} //popup
				})

			}
			else //non-KTH event
			{
					$.ajax({
						type: 'POST',
						url: "../ajax/changeEventsPopup.php", //fotm
						data: "JSON="+json,
						success: function(data){document.body.innerHTML+=data} //popup
				})

			}

		});

/*
When form is submitted, send input to action specified in form tag
*/

$(document).on('submit', ".changeForm", function(event){
	event.preventDefault();
	var send = $(this).serialize();
	$.ajax({
		type: 'POST',
		url: $(this).attr('action'),
		data: send,
		success: function(data)
		{
      console.log(data);
			document.getElementById("modal").outerHTML=null; //Close popup on submission
		}
	})
});

/*
  "add notes" implementation
*/
$(document).on('click','.note', function(event)
{
  var json = this.parentElement.parentElement.getAttribute('value'); //encoded json of clicked event
	$.ajax
	({
		type: 'POST',
		url: "../ajax/changeNotesPopup.php", //form
		data: "JSON="+json,
		success: function(data){document.body.innerHTML+=data} //popup
	})
});

/*
When form is submitted, send input to action specified in form tag
*/
$(document).on('submit', ".changeForm", function(event){
	event.preventDefault();
	var send = $(this).serialize();
	$.ajax({
		type: 'POST',
		url: $(this).attr('action'),
		data: send,
		success: function(data)
		{
      console.log(data);
			document.getElementById("modal").outerHTML=null; //Close popup on submission
		}
	})
});


/*
When next and previous buttons in calendar are clicked, notify weekButtons.php to change week, also change week in weekHead
*/
$(document).on('click', '.weekBtn' , function(event)
{
	//disable buttons for a	short time after click, to prevent spamming and ensure calendar and weekHead are synchronized
	$('.weekBtn').attr('disabled', 'disabled');
	setTimeout(enable, 80);

	//steps holds how many steps we have moved from current week. if steps = -1, we are on previous week.
	var steps = this.parentElement.getAttribute('value');

	if($(this).attr('id')=='Prev') //if "previous" button is clicked
	{
		steps--; //one week back

		//find what week it is, to update weekHead
		if(week == 1){week = 52;}
		else{week--;}

		$.ajax
		({
			type:"POST",
			url: "../ajax/weekButtons.php",
			data: "key="+steps,
			success: function(send)
			{
				//console.log(send);
				document.getElementById('weekHead').innerHTML = "Week: " + week; //update weekHead with new week
				document.getElementById('calendar').innerHTML=send; //update calendar
				$('#calHead').attr('value', steps); //store how many weeks away from current week
			}
		})
	}

	 else if($(this).attr('id') =='Next') //if "next" button is clicked
	{
		steps++; //one week forward

		//find what week it is, to update weekHead
		if(week == 52){week = 1;}
		else{week++;}

		$.ajax
		({
			type:"POST",
			url: "../ajax/weekButtons.php",
			data: "key="+steps,
			success: function(send)
			{
				//console.log(send);
				document.getElementById('weekHead').innerHTML = "Week: " + week; //update weekHead with new week
				document.getElementById('calendar').innerHTML=send; //update calendar
				$('#calHead').attr('value', steps); //store how many weeks away from current week
			}
		})
	}
});

//enable buttons again
function enable()
{
    $('.weekBtn').removeAttr('disabled');
}
