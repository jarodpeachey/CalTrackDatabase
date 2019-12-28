<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: multipart/form-data");
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Access-Control-Allow-Origin, Authorization, X-Requested-With");

$requestMethod = $_SERVER['REQUEST_METHOD'];

include('./config/database.php');
include('./class/workout.php');

// Set api to a new Database object
$database = new Database();
$conn = $database->getConnection();
$workout = new Workout($conn);

// Check if method === post
switch ($requestMethod) {
  case 'GET':
    if (!empty($_GET["userID"]) && !empty($_GET['workoutID'])) {
      // Code for getting workout by user and ID
    } else if (!empty($_GET['userID'])) {
      // Code for getting all workouts by user
    } else {
      // Code for getting ALL workouts
    }
    break;
  case 'POST':
    $workout->addWorkout($_POST, $_GET);
    break;
  case 'PUT':
    if (!empty($_GET["userID"])) {
      $PUT = json_decode(file_get_contents('php://input'));

      $workout->updateWorkout($PUT, $_GET);
    } else {
      // header("HTTP/1.1 304 Method Not Allowed");
    }
  case 'DELETE':
    if (!empty($_GET["userID"])) {
      // header("HTTP/1.1 304 Method Not Allowed");
    } else {
      $workout->deleteWorkout($_GET['workoutID']);
    }
}
