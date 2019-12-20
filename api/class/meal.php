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
    $mealName = $mealData['name'];
    $mealCalories = $mealData['calories'];
    $mealDescription = $mealData['description'];
    $userID = $mealData['userID'];

    $mealName = htmlspecialchars($mealName);
    $mealCalories = htmlspecialchars($mealCalories);
    $mealDescription = htmlspecialchars($mealDescription);
    $userID = htmlspecialchars($userID);

    // Email does not exist - insert meal
    $insertMealQuery = "INSERT INTO meals (mealname, mealCalories, mealDescription, userID)
            VALUES (?, ?, ?, ?);";

    $stmt = $this->conn->prepare($insertMealQuery);
    $stmt->bind_param("sisi", $mealName, $mealCalories, $mealDescription, $userID);

    // Check if query succeeded and send response
    if ($stmt->execute()) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false]);
    }

    header('Content-Type: application/json');
  } // End createmeal()

  function checkIfEmailExists($email)
  {
    $checkEmailQuery = "SELECT email FROM meals WHERE email = '$email'";

    $response = $this->conn->query($checkEmailQuery);

    if ($response->num_rows > 0) {
      return true;
    } else {
      return false;
    }
  }

  function verifymeal($mealData)
  {
    $mealEmail = $mealData['email'];
    $mealPassword = $mealData['password'];

    $mealEmail = htmlspecialchars($mealEmail);
    $mealPassword = htmlspecialchars($mealPassword);

    if ($this->checkIfEmailExists($mealEmail)) {
      $checkEmailQuery = "SELECT * FROM meals WHERE email = '$mealEmail'";
      $response = $this->conn->query($checkEmailQuery);
      $numberofrows = $response->num_rows;

      if ($response->num_rows > 0) {
        // While there is a row, fetch the data from the response
        while ($row = $response->fetch_assoc()) {

          // Check password against hash from db
          if (password_verify("$mealPassword", $row['hashpassword'])) {
            $meals = $this->getMeals($row['mealID']);
            $workouts = $this->getWorkouts($row['mealID']);

            $_SESSION['loggedInmeal'] = [
              "mealID" => $row['mealID'],
              "name" => $row['mealname'],
              "email" => $row['email'],
              "caloriesGained" => $row['caloriesGained'],
              "caloriesLost" => $row['caloriesLost'],
              "netCalories" => $row['netCalories'],
              "meals" => $meals,
              "workouts" => $workouts,
            ];

            // Send response data
            echo json_encode(["match" => true, "meal" => $_SESSION['loggedInmeal']]);
          } else {
            // Passwords do not match - send response data
            echo json_encode(["match" => false, "other" => "Password not found"]);
          }
        }
      }
    } else {
      echo json_encode(["match" => false, "other" => "Email not found"]);
    }

    header('Content-Type: application/json');
  } // End verifymeal()

  function getMeals($mealID)
  {
    $meals = [];

    // Check if email already exists in db
    $mealQuery = "SELECT mealName,calories FROM meals WHERE mealID='$mealID'";

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

  function getWorkouts($mealID)
  {
    $workouts = [];

    // Check if email already exists in db
    $mealQuery = "SELECT workoutName,calories FROM workouts WHERE mealID='$mealID'";

    // Set response
    $response = $this->conn->query($mealQuery);

    // Check if response exists
    if ($response->num_rows > 0) {
      // While there is a row, fetch the data from the response
      while ($row = $response->fetch_assoc()) {
        $workouts[] = $row;
      }
    }

    return $workouts;

    header('Content-Type: application/json');
  } // End getWorkouts
}
