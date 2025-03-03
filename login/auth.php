<?php
session_start();
header('Content-Type: application/json');
require('../db/connection.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    if(empty($email) || empty($password)){
        echo json_encode(array("success"=>false, "message"=>"All fields are required"));
        exit;
    }

    $query = $connection->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param('s',$email);
    $query->execute();

    $result = $query->get_result();
    if($result->num_rows === 0){
        echo json_encode(array("success"=>false, "message"=>"Invalid credentials"));
        exit;
    }

    $user = $result->fetch_assoc();

    if(password_verify($password, $user['password'])){
        $_SESSION['username'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        echo json_encode(array("success"=>true, "message"=>"credenciales correctas"));
    }else {
        echo json_encode(array("success"=>false, "message"=>"credenciales incorrectas"));
    }
    
}else{
    echo json_encode(array("success"=>false, "message"=>"Method not allowed, please use post"));
}

