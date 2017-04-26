function ajax(){
	var req = new XMLHttpRequest();
	var url = "ajax.php";
	var name = "name="+document.getElementById("name").value;
	req.open("POST", url, true);
	req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	req.onreadystatechange = function() {
		if(req.readyState == 4 && req.status == 200) {
			var return_data = req.responseText;
			document.getElementById("status").innerHTML = return_data;
		}
	}
	req.send(name);
	document.getElementById("status").innerHTML = "processing...";
}