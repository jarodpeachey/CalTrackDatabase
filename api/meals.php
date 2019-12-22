<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get the request method and assign it to variable
$requestMethod = $_SERVER["REQUEST_METHOD"];

include('./config/database.php');
include('./class/meal.php');

// Set api to a new Database object
$database = new Database();
$conn = $database->getConnection();
$meal = new Meal($conn);

// Check if method === post
switch ($requestMethod) {
  case 'GET':
    if (!empty($_GET["userID"]) && !empty($_GET['mealID'])) {
      // Code for getting meal by user and ID
    } else if (!empty($_GET['userID'])) {
      // Code for getting all meals by user
    } else {
      // Code for getting ALL meals
    }
    break;
  case 'POST':
    $meal->addMeal($_POST);
    break;
  default:
    // Send request header as Method Not Allowed
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}
