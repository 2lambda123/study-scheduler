var number = 1;
function addNewField() { //adds a new coursework

    var container = document.getElementById("a"); //parent container
    var lab = document.createElement("input"); //creates new input element
    lab.type = "checkbox";//gives input a type
    lab.name = "lab";//gives input a name

    var coursework = document.createElement("input");
    coursework.type = "text";
    coursework.name = "coursework" + number;

    var startdate = document.createElement("input");
    startdate.type = "date";
    startdate.name = "startdate" + number;

    var enddate = document.createElement("input");
    enddate.type = "date";
    enddate.name = "enddate" + number;

    var hp = document.createElement("input");
    hp.type = "float";
    hp.name = "hp_work" + number;

    //13 appendChild (used in removeField as static variable)

    container.appendChild(document.createTextNode("Course assignment " + number + ": ")); //Adds text
    container.appendChild(coursework); //Adds element created above
    container.appendChild(document.createElement("br"));

    container.appendChild(document.createTextNode("Start date: "));
    container.appendChild(startdate);
    container.appendChild(document.createElement("br"));

    container.appendChild(document.createTextNode("End date: "));
    container.appendChild(enddate);
    container.appendChild(document.createElement("br"));

    container.appendChild(document.createTextNode("HP: "));
    container.appendChild(hp);
    container.appendChild(document.createElement("br"));
    container.appendChild(document.createElement("br"));

    number++; //increment to keep track of number of courseworks
}

function removeField() { //removes a coursework

    var container = document.getElementById("a"); //parent container
    if (number > 1) { //prevent button to be removed
        number--;

        var loop = 13; // if lab checkbox exists, then must loop 16 times

        for (var i = 0; i < loop; i++) //Loop through all container.appendChild done in addNewField
            container.removeChild(container.lastChild); //Removes last child from container
    }


}
