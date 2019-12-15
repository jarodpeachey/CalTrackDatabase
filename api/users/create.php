<?php
header("Access-Control-Allow-Origin: *");
// $createDatabaseQuery = "
//   CREATE DATABASE IF NOT EXISTS caltrack;

//   USE caltrack;

//   CREATE TABLE IF NOT EXISTS meals (
//     mealID INT AUTO_INCREMENT PRIMARY KEY,
//     mealName VARCHAR(255) NOT NULL,
//     calories int NOT NULL,
//     userID INT,
//     CONSTRAINT caltrack_meal
//     FOREIGN KEY (userID)
//       REFERENCES users(userID)
//   );

//   CREATE TABLE IF NOT EXISTS workouts (
//     workoutID INT AUTO_INCREMENT PRIMARY KEY,
//     workoutName VARCHAR(255) NOT NULL,
//     calories int NOT NULL,
//     userID INT,
//     CONSTRAINT caltrack_workout
//     FOREIGN KEY (userID)
//       REFERENCES users(userID)
//   );
// ";

// mysqli_query($conn, $createDatabaseQuery);

// Get the request method and assign it to variable
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Include the Database.php file
include('../class/Database.php');

// Set api to a new Database object
$api = new Database();

// Check if method === post
switch ($requestMethod) {
  case 'POST':
    // Call the insert user method
    $api->createUser($_POST);
    break;
  default:
    // Send request header as Method Not Allowed
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}
