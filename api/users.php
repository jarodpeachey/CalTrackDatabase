<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: multipart/form-data");
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Access-Control-Allow-Origin, Authorization, X-Requested-With");

$requestMethod = $_SERVER['REQUEST_METHOD'];

include('./config/database.php');
include('./class/user.php');

// Set api to a new Database object
$database = new Database();
$conn = $database->getConnection();
$user = new User($conn);

// Check if method === post
switch ($requestMethod) {
  case 'GET':
    if (!empty($_GET["userID"])) {
      if ($_GET["userID"])
        $userID = intval($_GET["userID"]);
      $user->getUser($userID);
    }
    // Call the insert user method
    $user->getUser($_GET);
    break;
  case 'POST':
    // Call the insert user method
    $user->createUser($_POST);
    break;
  case 'PUT':
    // Call the update user method
    $PUT = json_decode(file_get_contents('php://input'));

    $user->updateUser($PUT, $_GET);
    break;
  case 'DELETE':
    $user->deleteUser(intval($_GET['userID']));
  default:
    break;
}
