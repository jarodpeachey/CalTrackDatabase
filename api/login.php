<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Access-Control-Allow-Origin, Authorization, X-Requested-With");

// Get the request method and assign it to variable
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Include the UserDB.php file
include('./config/database.php');
include('./class/user.php');

// Set api to a new Database object
$database = new Database();
$conn = $database->getConnection();

$user = new User($conn);

$user->verifyUser($_POST);
