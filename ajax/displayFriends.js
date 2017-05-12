//Initiate FB SDK
window.fbAsyncInit = function() {
    FB.init({
      appId      : '127392254473957',
      //cookie     : true,
      xfbml      : true,
      version    : 'v2.9'
    });
    FB.AppEvents.logPageView();
	setupPage();
  };
  
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

   //findFriends();
   function logout(){ //logout from facebook
  FB.logout(function(response) {
  });
}

function findFriends() { //calls fb api to find friends who use our app, then calls actualLoadPhp with an argument ids[][] containing all ids and names
  FB.getLoginStatus(function(response) {
    if(response && !response.error){
	  FB.api(
      "/me/friends/",
      function (response) {
		  if (response && !response.error) {
			// handle the result
			//console.log("Permission to access friends");
			var ids = new Array();
			for (var i = 0; i < response.data.length; i++){
			  ids[i] = new Array();
			  
			  ids[i][0] = response.data[i].id;
			  ids[i][1] = response.data[i].name;
			}
			//console.log(ids);
			actualLoadPhp(ids);
		  }
		  else{ //User not logged in
			console.log("No permission");
			console.log("Response error message: " + response.error.message);
	      }
      }
	  );
	}
	else{
	  console.log("Error when calling FB.getLoginStatus");
	}
  });
}

function actualLoadPhp(response){ //calls studyWithFriends.php which calculates all common study times with your friends and echos it to the webpage div "demo"
 var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("findFriendsResults").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "../scripts/studyWithFriends.php?q=" + JSON.stringify(response),true);
  xhttp.send();
}

function addButton(){ //adds button for "Find Study Friends"
  var container = document.getElementById("findFriends"); //parent container
  
  var studyBtn = document.createElement("button");
  studyBtn.onclick = findFriends;
  
  studyBtn.appendChild(document.createTextNode("Find Study Friends"));
  container.appendChild(studyBtn);
  container.appendChild(document.createElement("br"));
}

function removeButton(){ //Removes "Find Study Friends" button
  var container = document.getElementById("container"); //parent container
  while(container.hasChildNodes())
    container.removeChild(container.lastChild);
}

function removeStudy(){ //Removes result from studyWithFriends.php
  var container = document.getElementById("fbLogin"); //parent container
  while(container.hasChildNodes())
    container.removeChild(container.lastChild);
}

function setupPage(){
  FB.getLoginStatus(function(response) {
    if(response && !response.error){
	  addButton();
	}
	else{
	 var container = document.getElementById("findFriends");
	 container.appendChild(document.createTextNode("Make sure you are have connected with facebook"));
	}
  });
}