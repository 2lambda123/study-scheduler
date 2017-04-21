var number = 1;

function addNewField() { //adds a new coursework
    var container = document.getElementById("container");  //parent container
	
    var coursework = document.createElement("input"); //creates new input element
    coursework.type = "text"; //gives input a type
    coursework.name = "coursework" + number; //gives input a name

    var startdate = document.createElement("input");
    startdate.type = "date";
    startdate.name = "startdate" + number;
	
    var enddate = document.createElement("input");
    enddate.type = "date";
    enddate.name = "enddate" + number;
	
    var hp = document.createElement("input");
    hp.type = "number";
    hp.name = "hp_work" + number;

	//13 appendChild (used in removeField as static variable)
    container.appendChild(document.createTextNode("Coursework " + number + ": ")); //Adds text
    container.appendChild(coursework);	//Adds element created above
    container.appendChild(document.createElement("br"));

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

    number++; //increment to keep track of number of courseworks
}

function removeField(){ //removes a coursework
number --;
for(var i = 0; i < 13; i++) //Loop through all container.appendChild done in addNewField
	container.removeChild(container.lastChild); //Removes last child from container


}
