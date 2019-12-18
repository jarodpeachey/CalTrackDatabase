<?php
// Database class
class MealDB
{
  // Database variables
  private $host  = 'localhost';
  private $user  = 'soft'; // Change this
  private $password   = "root"; // Change this
  private $database  = "caltrack";
  private $table = 'meals';
  private $conn = false;
  private $loggedInUser;

  // Constructor
  public function __construct()
  {
    if (!$this->conn) {
      $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
      if ($conn->connect_error) {
        die("Error failed to connect to MySQL: " . $conn->connect_error);
      } else {
        $this->conn = $conn;
      }
    }
  }

  function getMeals($userID)
  {
    $meals = [];

    // Check if email already exists in db
    $mealQuery = "SELECT mealName,calories FROM meals WHERE userID='$userID'";

    // Set response
    $response = $this->conn->query($mealQuery);

    // Check if response exists
    if ($response->num_rows > 0) {
      // While there is a row, fetch the data from the response
      while ($row = $response->fetch_assoc()) {
        $meals[] = $row;
      }
    }

    return $meals;

    header('Content-Type: application/json');
  } // End getMeals

  function addMeal($userData)
  {
    if ($this->conn) {
      //Set variables from form
      $mealName = $userData['mealName'];
      $mealCalories = $userData['mealCalories'];
      $mealDescription = $userData['mealDescription'];
      $userID = $userData['userID'];

      // Email does not exist - insert user
      $addMealQuery = "INSERT INTO meals (mealName, mealCalories, mealDescription, userID)
            VALUES ('$mealName', '$mealCalories', '$mealDescription', '$userID');";

      // Check if query succeeded and send response
      if ($this->conn->query($addMealQuery)) {
        echo json_encode(["success" => true]);
      } else {
        echo json_encode(["success" => false]);
      }

      header('Content-Type: application/json');
    }
  } // End addMeal()
}
