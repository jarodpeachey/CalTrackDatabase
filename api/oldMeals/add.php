<?php
header("Access-Control-Allow-Origin: *");

// Get the request method and assign it to variable
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Include the UserDB.php file
include('../databases/MealDB.php');

// Set api to a new MealDB object
$api = new MealDB();

// Check if method === post
switch ($requestMethod) {
  case 'POST':
    // Call the insert user method
    $api->addMeal($_POST);
    break;
  default:
    // Send request header as Method Not Allowed
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}
