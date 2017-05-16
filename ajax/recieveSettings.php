<?php
if(session_id() == "") session_start();

include_once '../scripts/DB.php';

$db = new DB();
$result = $db -> select("SELECT * FROM user WHERE ID='$_SESSION[uuid]'");
$result = $result[0];

if(isset($_SESSION['uuid'])) {
  if(isset($_POST['disconnect']) && $_POST['disconnect'] == 1){
    if(isset($result['USERNAME']) && isset($result['PASSWORD']) && $result['USERNAME'] !== "" && $result['PASSWORD'] !== ""){
      $response_array['status'] = 'success';
      $db -> query("UPDATE user SET GAUTH = null WHERE ID='$_SESSION[uuid]'");
      $db -> query("UPDATE user SET GID = null WHERE ID='$_SESSION[uuid]'");
      header('Content-type: application/json');
      echo json_encode($response_array);
    }
    else{
      $response_array['status'] = 'fail';
      header('Content-type: application/json');
      echo json_encode($response_array);
    }
  }
  if(isset($_POST['disconnect']) && $_POST['disconnect'] == 2){
      $response_array['status'] = 'success2';
      $db -> query("UPDATE user SET FBAUTH = null WHERE ID='$_SESSION[uuid]'");
      header('Content-type: application/json');
      echo json_encode($response_array);
  }
}
?>
