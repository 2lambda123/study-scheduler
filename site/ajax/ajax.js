$(document).on("submit", "form", function(e){
    e.preventDefault();
	$.ajax({
		type: 'POST',
		url: $(this).attr('action'),
		data: $(this).serialize(),
		success: function(data) { document.getElementById('courses').outerHTML = data; }
	})
});