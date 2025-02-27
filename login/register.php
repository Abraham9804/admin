<?php
header('Content-Type: application/json');
require('../db/connection.php');

echo json_encode(array("success"=>true,"message"=>"registro correcto"));