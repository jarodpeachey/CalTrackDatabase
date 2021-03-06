<?php
  // Get the request method and assign it to variable
  $requestMethod = $_SERVER["REQUEST_METHOD"];

  // Include the UserDB.php file
  include('../class/UserDB.php');

  // Set api to a new Database object
  $api = new Database();

  // Check if method === get
  switch($requestMethod) {
    case 'GET':
      // Call the insert user method
      $api->getMeals($_GET);
      break;
    default:
      // Send request header as Method Not Allowed
      header("HTTP/1.0 405 Method Not Allowed");
      break;
  }
?>
