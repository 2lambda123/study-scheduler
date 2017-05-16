<?php
if(session_id() == "") session_start();

if(isset($_SESSION['uuid'])) {
  echo "<h2>Privacy settings</h2>";
  echo "<div>";
  echo "<form class='form' action='../ajax/recieveSettings.php' method='post'>";
    echo "<label for='mo'/>Disconnect from Google:</label> <input type='radio' name='disconnect' value='1' id='gS'/><br/><br>";
    echo "<label for='tu'/>Disconnect from Facebook:</label> <input type='radio' name='disconnect' value='2' id='fS'/><br/>";
    echo "(This will remove the option for all your friends to see your schedule)<br><br>";
    echo "<input type='hidden' name='UID' value='" . $_SESSION['uuid'] . "'>";
    echo "<input type='submit' value='Submit'/>";
  echo"</form></div>";

  echo '<script>
  	  $(document).on("submit", "form", function(event) {
  		event.preventDefault();
  		var send = $(this).serialize();
  		$.ajax({
  		type: "POST",
  		url: $(this).attr("action"),
  		data: send,
  		success: function(data)
  			{
        console.log(data);
        if(data.status == "success"){
          alert("You for successfully disconnected your Google account!");
          document.getElementById("shown").innerHTML=data; //Close popup on submission
        }
        if(data.status == "success2"){
          alert("You for successfully disconnected your Facebook account!");
          document.getElementById("shown").innerHTML=data; //Close popup on submission
        }
        if(data.status == "fail"){
          alert("ERROR cannot remove Google account or all information will be inaccessible");
        }

  			}
  	})
    });
    </script>';
}
?>
