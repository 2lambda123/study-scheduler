<script src="../site/jquery.min.js"></script>
<script>
var date = new Date();
function pad(n){
	if(n < 10) return '0'+n;
	return n;
}
Date.prototype.uglyDate = function(){
	return this.getUTCFullYear()+pad(this.getUTCMonth()+1)+pad(this.getUTCDate());
}
Date.prototype.addDays = function(days) {
	this.setDate(this.getDate() + parseInt(days));
	return this;
}
$(document).on('click', '#load', function(event){
	$.ajax ({
		type: 'POST',
		url: "../site/altView.php",
		data: "dtstart="+date.uglyDate()+"&dtend="+date.addDays(7).uglyDate(),
		success: function(data){
			$('#load').before(data);
		}
	})
});

</script>
<style>
#load {
	margin: 1em;
	padding: .5em;
}
#cal {
	max-height: 700px;
	display: block;
	border: 1px solid;
	overflow-y: auto;
	padding: 1em;
	margin: 1em;
}

.event {
	text-align: center;
	width: 100%;
	display:inline-block;
	border: 1px solid;
	white-space: pre;
}

</style>