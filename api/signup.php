<?php
header("Access-Control-Allow-Origin: *");

// Get the request method and assign it to variable
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Include the UserDB.php file
include('./config/database.php');
include('./class/user.php');

// Set api to a new Database object
$database = new Database();
$conn = $database->getConnection();

$user = new User($conn);

// create the user
if ($user->createUser($_POST)) {
  // set response code
  http_response_code(200);

  // display message: user was created
  echo json_encode(["signupMessage" => "Succesfully created user!"]);

  echo "USER CREATED";
}
else {
  // set response code
  http_response_code(400);

  echo json_encode(["signupMessage" => "Failed at creating user."]);
}
