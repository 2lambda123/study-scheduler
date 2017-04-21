var number = 1;

function addNewField() {
    var container = document.getElementById("container");
    var coursework = document.createElement("input");
    coursework.type = "text";
    coursework.name = "coursework" + number;
	coursework.id = "coursework" + number;

    var startdate = document.createElement("input"); //createElement skapar element
    startdate.type = "date";
    startdate.name = "startdate" + number;
    var enddate = document.createElement("input");
    enddate.type = "date";
    enddate.name = "enddate" + number;
    var hp = document.createElement("input");
    hp.type = "number";
    hp.name = "hp_work" + number;

    container.appendChild(document.createTextNode("Coursework " + number + ": "));
    container.appendChild(coursework);
    container.appendChild(document.createElement("br"));
	alert(coursework.id);

    container.appendChild(document.createTextNode("Startdate: "));
    container.appendChild(startdate);
    container.appendChild(document.createElement("br"));

    container.appendChild(document.createTextNode("Enddate: "));
    container.appendChild(enddate);
    container.appendChild(document.createElement("br"));

    container.appendChild(document.createTextNode("HP: "));
    container.appendChild(hp);
    container.appendChild(document.createElement("br"));
    container.appendChild(document.createElement("br"));

    number++;
}

function removeField(){
var parent = document.getElementById("container");
var types = ["coursework", "startdate", "enddate", "hp_work"];
for(var i = 0; i < types.length; i++){
	var child = document.getElementById("coursework1");
	alert(child);
	parent.removeChild(child);
}
number --;
}
