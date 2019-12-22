<?php
class Meal
{
  // database connection and table name
  public $conn;
  public $table_name = "meals";

  // constructor
  public function __construct($db)
  {
    $this->conn = $db;
  }

  function addMeal($mealData)
  {
    $mealName = $mealData['mealName'];
    $mealCalories = $mealData['mealCalories'];
    $mealDescription = $mealData['mealDescription'];
    $userID = $mealData['userID'];

    $mealName = htmlspecialchars($mealName);
    $mealCalories = htmlspecialchars($mealCalories);
    $mealDescription = htmlspecialchars($mealDescription);

    // Email does not exist - insert meal
    $insertMealQuery = "INSERT INTO meals (mealName, mealCalories, mealDescription, userID)
            VALUES (?, ?, ?, ?);";

    $mealName = $mealName;
    $mealDescription = $mealDescription;
    $mealCalories = $mealCalories;

    $stmt = $this->conn->prepare($insertMealQuery);
    $stmt->bind_param("sssd", $mealName, $mealCalories, $mealDescription, $userID);

    // Check if query succeeded and send response
    if ($stmt->execute()) {
      echo json_encode(["success" => true]);
    } else {
      die('execute() failed: ' . htmlspecialchars($stmt->error));
      echo json_encode(["success" => false]);
    }

    header('Content-Type: application/json');
  } // End createmeal()
}
