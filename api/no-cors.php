<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: multipart/form-data");
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Access-Control-Allow-Methods, Access-Control-Allow-Origin, Authorization, X-Requested-With");

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
  case 'GET':
    $URL = $_GET['url'];
  case 'POST':
    $URL = $_POST['url'];
  case 'PUT':
    $URL = $_PUT['url'];
  default:
    $URL = $_GET['url'];
}

echo json_encode([($URL)]);
die();
