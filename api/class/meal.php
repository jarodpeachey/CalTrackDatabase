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

  function addMeal($mealPost, $mealGet)
  {
    $mealName = $mealPost['mealName'];
    $mealCalories = $mealPost['mealCalories'];
    $mealDescription = $mealPost['mealDescription'];
    $userID = $mealGet['userID'];

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

    $response = $stmt->execute();

    // Check if query succeeded and send response
    if ($response) {
      // $getMealQuery = "SELECT mealID,mealName,mealCalories,mealDescription FROM meals WHERE mealTimestamp='$mealTimestamp'";
      $lastID = $this->conn->insert_id;
      $selectQuery = "SELECT mealID,mealName,mealCalories,mealDescription,userID FROM meals WHERE mealID='$lastID'";
      $result = $this->conn->query($selectQuery);

      if ($result->num_rows > 0) {
        // While there is a row, fetch the data from the result
        while ($row = $result->fetch_assoc()) {
          $mealToReturn = [
            "mealID" => $row['mealID'],
            "mealName" => $row['mealName'],
            "mealCalories" => $row['mealCalories'],
            "mealDescription" => $row['mealDescription'],
            "userID" => $row['userID'],
          ];

          // Send response data
          echo json_encode(["success" => true, "meal" => $mealToReturn]);
        }
      }
    } else {
      die('execute() failed: ' . htmlspecialchars($stmt->error));
      echo json_encode(["success" => false]);
    }

    header('Content-Type: application/json');
  } // End createmeal()

  function updateMeal($mealPut, $mealGet)
  {
    $mealName = $mealPut->mealName;
    $mealCalories = $mealPut->mealCalories;
    $mealDescription = $mealPut->mealDescription;
    $mealID = $mealGet['mealID'];

    $mealName = htmlspecialchars($mealName);
    $mealCalories = htmlspecialchars($mealCalories);
    $mealDescription = htmlspecialchars($mealDescription);

    $updateMealQuery = "UPDATE meals SET mealName='$mealName', mealCalories='$mealCalories', mealDescription='$mealDescription' WHERE mealID='$mealID'";
    $response = $this->conn->query($updateMealQuery);

    // Check if query succeeded and send response
    if ($response) {
      // $getMealQuery = "SELECT mealID,mealName,mealCalories,mealDescription FROM meals WHERE mealTimestamp='$mealTimestamp'";
      $lastID = $mealID;
      $selectQuery = "SELECT mealID,mealName,mealCalories,mealDescription,userID FROM meals WHERE mealID='$lastID'";
      $result = $this->conn->query($selectQuery);

      if ($result->num_rows > 0) {
        // While there is a row, fetch the data from the result
        while ($row = $result->fetch_assoc()) {
          $mealToReturn = [
            "mealID" => $row['mealID'],
            "mealName" => $row['mealName'],
            "mealCalories" => $row['mealCalories'],
            "mealDescription" => $row['mealDescription'],
            "userID" => $row['userID'],
          ];

          // Send response data
          echo json_encode(["success" => true, "meal" => $mealToReturn]);
        }
      }
    } else {
      die('execute() failed: ' . htmlspecialchars($response->error));
      echo json_encode(["success" => false]);
    }

    header('Content-Type: application/json');
  } // End createmeal()
}
