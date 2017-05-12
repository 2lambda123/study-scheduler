//Initiate FBSDK and call for //checkLoginStateStartUp
window.fbAsyncInit = function() {
    FB.init({
      appId      : '127392254473957',
      //cookie     : true,
      xfbml      : true,
      version    : 'v2.9'
    });
    FB.AppEvents.logPageView();	
	//console.log("Initiate FB API");
	checkLoginStateStartUp();
  };
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
     
function checkLoginState() { //checks if the person is logged in or not - called when clicking the login/logout button
    FB.getLoginStatus(function(response) {
      if(response && !response.error){
        statusChangeCallback(response);	
	  }
	  else{
	    console.log("Error when calling FB.getLoginStatus");
	  }
    });
}

function checkLoginStateStartUp() { //checks if the person is logged in or not - called only when website first is loaded
    FB.getLoginStatus(function(response) {
      if(response && !response.error){
        statusChangeCallbackStartUp(response);	
	  }
	  else{
	    console.log("Error when calling FB.getLoginStatus");
	  }
    });
}
   
  //checks if user is logged in or not and takes appropriate actions 
function statusChangeCallback(response){
  if(response.status === 'connected'){ //User is logged in
	insertFbId(); //calls ajax for insertFbId.php which creates account/login user
	window.setTimeout(refreshPage, 500); //refreshes page to see that you are logged in, has to wait 500ms for a session to start fully
  }
  else{ //User is not logged in
	ajaxSessionDestroy(); //Used to logout from entire website
	window.setTimeout(refreshPage, 500);
  }  
}

function statusChangeCallbackStartUp(response){ //checks if user is logged in - called when refreshing page
  if(response.status === 'connected'){ //User is logged in
	insertFbId(); //calls ajax for insertFbId.php which creates account/login user
	//call function that logs person out of facebook if no session uuid
  }
  else{
    console.log("FB account is NOT connected StartUp");
  }  
}

function insertFbId(){ //Called when connecting with facebook, calls insertFbId.php which takes appropriate actions
FB.api(
    "/me",
    function (response) {
      if (response && !response.error) {
		//console.log(response);
		insertFbIdAjax(response);
      }
    }
);
}

function insertFbIdAjax(response){ //Calls insertFbId.php using ajax - response is fb.api "me"

var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
      document.getElementById("fbLogin").innerHTML = this.responseText;
    }
  };
  xhttp.open("POST", "../scripts/insertFbId.php?q=" + JSON.stringify(response),true);
  xhttp.send();
}

function logout(){
  FB.logout(function(response) {

  });
}
function refreshPage(){
      window.location.reload();
      //console.log("refreshPage");
}
function ajaxSessionDestroy(){
var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
      document.getElementById("container").innerHTML = this.responseText;
    }
  };
  xhttp.open("POST", "../scripts/destroySession.php");
  xhttp.send();
}

