<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: multipart/form-data");
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Access-Control-Allow-Origin, Authorization, X-Requested-With");

$requestMethod = $_SERVER['REQUEST_METHOD'];

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
    $meal->addMeal($_POST, $_GET);
    break;
  case 'PUT':
    if (!empty($_GET["userID"])) {
      $PUT = json_decode(file_get_contents('php://input'));

      $meal->updateMeal($PUT, $_GET);
    } else {
      // header("HTTP/1.1 304 Method Not Allowed");
    }
  case 'DELETE':
    if (!empty($_GET["userID"])) {
      // header("HTTP/1.1 304 Method Not Allowed");
    } else {
      $meal->deleteMeal($_GET['mealID']);
    }
}
