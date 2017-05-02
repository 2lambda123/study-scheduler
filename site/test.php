<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
  <button type="button" class="btn" data-toggle="modal" data-target="#myModal">Edit</button>

  <div class="modal fade" id="myModal">
    <div class="modal-form">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Change of plan?<h4>
        </div>
        <div class="modal-body">
          <form action="edit_kth.php" method="POST">
  	           <div>
  		             <label for="study"/>I want to skip this event and spend the time studying instead. </label> <input type="radio" name="study" id="study"/><br/>
  		             <label for="del"/>I want to skip this event do some other activity (not studying).  </label> <input type="radio" name="del" id="del"/><br/>
                   <br/><input type="submit" value="Submit" class="btn btn-default" align=""/>
                   <button type="button" class="btn btn-default" data-dismiss="modal" style="float : right;">Close</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
