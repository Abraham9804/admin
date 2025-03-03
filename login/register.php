<?php
header('Content-Type: application/json');
require('../db/connection.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(empty($name) || empty($lastName) || empty($email) || empty($password)){
        echo json_encode(array("success"=>false,"message"=>"All fields are required."));
        exit;
    } 

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo json_encode(array("success"=>false,"message"=>"{$email} is not a valid email address"));
        exit;
    }
    
    if(strlen($password) < 6){
        echo json_encode(array("success"=>false,"message"=>"La contraseña debe tener al menos 6 caracteres"));
        exit;
    }
    
    if( strlen($name) < 2 || strlen($lastName) <2){
        echo json_encode(array("success"=>false,"message"=>"Name and Last name must have at least 2 characters"));
        exit;
    }
    
    $query = $connection->prepare("SELECT id FROM users WHERE email = ?");
    $query->bind_param('s', $email);
    $query->execute();

    $result = $query->get_result();
    $query->close();

    if($result->num_rows > 0){
        echo json_encode(["success" => false, "message" => "Email is already registered."]);
        exit;
    } else {
        $passwordHashed = password_hash($password,PASSWORD_BCRYPT);
        $insert = $connection->prepare("INSERT INTO users (name, lastName, email, password) VALUES (?,?,?,?)");
        $insert->bind_param('ssss', $name, $lastName, $email, $passwordHashed);
        $insert->execute();
        if($insert->affected_rows > 0){
            echo json_encode(array("success"=>true,"message"=>"User registered"));
            exit;
        }else {
            echo json_encode(array("success"=>false,"message"=>"Error registering user, please try again"));
            exit;
        } 
    }
    $insert->close();

}else{
    http_response_code(405);
    echo json_encode(array("success"=>false,"message"=>"Method not allowed, please use post"));
    exit;
}

