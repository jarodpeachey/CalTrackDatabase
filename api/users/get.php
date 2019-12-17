<?php
header("Access-Control-Allow-Origin: *");

// Get the request method and assign it to variable
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Include the UserDB.php file
include('../databases/UserDB.php');

// Set api to a new Database object
$api = new UserDB();

// Check if method === get
switch ($requestMethod) {
  case 'GET':
    // Call the insert user method
    $api->getUser($_GET);
    break;
  default:
    // Send request header as Method Not Allowed
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}
