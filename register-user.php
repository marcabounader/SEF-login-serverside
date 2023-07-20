<?php

include('Config\db_connect.php');
$email=$last_name=$first_name=$password="";
$response['status']="";
// $result='';
header("Content-type: application/json; charset=utf-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Headers: Content-Type");

$errors=array('email'=>'','last_name'=>'','first_name'=>'','password'=>'','result'=>'');
$post_body = json_decode(file_get_contents('php://input'), true);

    $errors['result']='';
    $check_username = $conn->prepare('select user_email from users where user_email=?');
    $check_username->bind_param('s', $post_body['email']);
    $check_username->execute();
    $check_username->store_result();
    if ($check_username->num_rows() == 0) {

            if(empty($post_body['email'])){
                $errors['email']="An email is required";
            } else{
                if(filter_var($post_body['email'],FILTER_VALIDATE_EMAIL)){
                    $email=$post_body['email'];
                } else{
                    $response['status']="0";
                    $response['error']='Please enter a valid email';
                    echo json_encode($response);
                    exit();
                }
            }
        
            
            if(empty($post_body['first_name'])){
                $errors['first_name']="A first name is required";
            } else{
                $first_name=$post_body['first_name'];
            }
        
            if(empty($post_body['last_name'])){
                $errors['last_name']="A last name is required";
            } else{
                $last_name=$post_body['last_name'];
            }
        
        
            if(empty($post_body['password'])){
                $errors['password']="A password is required";
            } else{
                $password=$post_body['password'];
            }
        
            if(!empty($email) && !empty($first_name) && !empty($last_name) && !empty($password)){
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $sql = $conn->prepare("insert into users (user_email,user_first_name,user_last_name,user_password) values(?,?,?,?)");
                $sql->bind_param("ssss", $email, $first_name,$last_name,$hashed_password);
                $sql->execute();
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                if($sql->get_result()){
                    $response['status']="0";
                    $errors['result']='Could not register';
                    echo json_encode($response);
                    echo json_encode($errors);
                    exit();
                }else{
                    $response['status']="1";
                    echo json_encode($response);
                    exit();
                }
            }else{
                $response['status']="0";
                $errors['result']='Please fill empty fields';
                echo json_encode($response);
                echo json_encode($errors);
                exit();
            }
        
    } else {
        $response['status']="0";
        $response['error']='email already taken';
        echo json_encode($response);
        exit();
    }


?>