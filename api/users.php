<?php
header("Access-Control-Allow-Origin: *");

// Get the request method and assign it to variable
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Include the UserDB.php file
include('./databases/UserDB.php');

// Set api to a new Database object
$api = new UserDB();

// Check if method === post
switch ($requestMethod) {
  case 'GET':
    if (!empty($_GET["userID"])) {
      if ($_GET["userID"])
      $userID = intval($_GET["userID"]);
      $api->getUser($userID);
    }
    // Call the insert user method
    $api->getUser($_GET);
    break;
  case 'POST':
    // Call the insert user method
    $api->createUser($_POST);
    break;
  default:
    // Send request header as Method Not Allowed
    header("HTTP/1.0 405 Method Not Allowed");
    break;
}
