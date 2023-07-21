<?php

include('Config\db_connect.php');

$id=$first_name=$email=$password="";
header("Content-type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin: http://localhost:5500');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");


$post_body = json_decode(file_get_contents('php://input'), true);
$email=$post_body['email'];
$password=$post_body['password'];

$sql = $conn->prepare("select user_id,user_first_name,user_password from users where user_email=?");
$sql->bind_param("s",$email);
$sql->execute();
$sql->store_result();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
if($sql->num_rows()==0){
  $data['status']="0";
  echo json_encode($data);
  exit();
} else{
  $sql->bind_result($id,$first_name,$hashed_password);
  $sql->fetch();
  if(password_verify($password,$hashed_password)){
    header("Set-Cookie: name=$first_name; Max-Age=2592000; path=/; samesite=None; secure; Domain=localhost");
    $data['status']="1";
    $data = array("id"=>$id,"first_name"=>$first_name);
    echo json_encode($data);
    exit();
  } else{
    $data['status']="0";
    $data['error']="wrong password";
    echo json_encode($data);
    exit();
  }
 

}        

?>