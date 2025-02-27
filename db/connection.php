<?php 
$connection = mysqli_connect('localhost','root','12qwaszx','admin');

if(!$connection){
    die('connection failed: '.mysqli_connect_error());
}
