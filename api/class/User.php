<?php
class User
{
  // database connection and table name
  public $conn;
  public $table_name = "users";

  // constructor
  public function __construct($db)
  {
    $this->conn = $db;
  }

  function createUser($userData)
  {
    $userName = $userData['name'];
    $userEmail = $userData['email'];
    $userPassword = $userData['password'];

    $userName = htmlspecialchars($userName);
    $userEmail = htmlspecialchars($userEmail);
    $userPassword = htmlspecialchars($userPassword);

    $userPassword = password_hash($userPassword, PASSWORD_DEFAULT);

    // Check if the response exists
    if ($this->checkIfEmailExists($userEmail)) {
      // Email exists - terminate and send email_used as true
      return json_encode(["email_used" => true]);
    } else {
      // Email does not exist - insert user
      $insertUserQuery = "INSERT INTO users (username, email, hashpassword, caloriesGained, caloriesLost, netCalories)
            VALUES (?, ?, ?, 0, 0, 0);";

      $stmt = $this->conn->prepare($insertUserQuery);
      $stmt->bind_param("sss", $username, $email, $hashpassword);

      $username = $userName;
      $email = $userEmail;
      $hashpassword = $userPassword;

      // Check if query succeeded and send response
      if ($stmt->execute()) {
        echo json_encode(["email_used" => false, "success" => true]);
      } else {
        echo json_encode(["email_used" => false, "success" => false]);
      }

      header('Content-Type: application/json');
    }
  } // End createUser()

  function getUser($userID = 0)
  {
    if ($userID != 0) {
      $query = "SELECT * FROM users WHERE userID='$userID'";
    } else {
      $query = "SELECT * FROM users";
    }

    $response = $this->conn->query($query); 

    // Check if response exists
    if ($response->num_rows > 0) {
      $users = [];
      while ($row = $response->fetch_assoc()) {
        $response[] = $row;
      }
      echo json_encode(["user" => $users, "success" => true]);
    } else {
      // No rows exist with that email, send response data
      echo json_encode(["succes" => false, "message" => "There are no users with that ID. Try again."]);
    }
  } // End getUser()

  function checkIfEmailExists($email)
  {
    $checkEmailQuery = "SELECT email FROM users WHERE email = '$email'";

    $response = $this->conn->query($checkEmailQuery);

    if ($response->num_rows > 0) {
      return true;
    } else {
      return false;
    }
  }

  function verifyUser($userData)
  {
    $userEmail = $userData['email'];
    $userPassword = $userData['password'];

    $userEmail = htmlspecialchars($userEmail);
    $userPassword = htmlspecialchars($userPassword);

    if ($this->checkIfEmailExists($userEmail)) {
      $checkEmailQuery = "SELECT * FROM users WHERE email = '$userEmail'";
      $response = $this->conn->query($checkEmailQuery);

      if ($response->num_rows > 0) {
        // While there is a row, fetch the data from the response
        while ($row = $response->fetch_assoc()) {

          // Check password against hash from db
          if (password_verify("$userPassword", $row['hashpassword'])) {
            $meals = $this->getMeals($row['userID']);
            $workouts = $this->getWorkouts($row['userID']);

            $userToReturn = [
              "userID" => $row['userID'],
              "name" => $row['username'],
              "email" => $row['email'],
              "caloriesGained" => $row['caloriesGained'],
              "caloriesLost" => $row['caloriesLost'],
              "netCalories" => $row['netCalories'],
              "meals" => $meals,
              "workouts" => $workouts,
            ];

            // Send response data
            echo json_encode(["match" => true, "user" => $userToReturn]);
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
  } // End verifyUser()

  function updateUser($userPut, $userGet)
  {
    $userName = $userPut->name;
    $userEmail = $userPut->email;
    $caloriesGained = $userPut->caloriesGained;
    $caloriesLost = $userPut->caloriesLost;
    $netCalories = $userPut->netCalories;
    $userID = $userGet['userID'];

    $userName = htmlspecialchars($userName);
    $userEmail = htmlspecialchars($userEmail);
    $caloriesGained = htmlspecialchars($caloriesGained);
    $caloriesLost = htmlspecialchars($caloriesLost);
    $netCalories = htmlspecialchars($netCalories);

    $updateUserQuery = "UPDATE users SET username='$userName', email='$userEmail' caloriesGained='$caloriesGained', caloriesLost='$caloriesLost', netCalories='$netCalories' WHERE userID='$userID'";
    $response = $this->conn->query($updateUserQuery);

    // Check if query succeeded and send response
    if ($response) {
      echo json_encode(["success" => true]);
    } else {
      echo json_encode(["success" => false, "error" => htmlspecialchars($response->error)]);
    }

    header('Content-Type: application/json');
  } // End updateUser()

  function getMeals($userID)
  {
    $meals = [];

    // Check if email already exists in db
    $mealQuery = "SELECT mealID,mealName,mealCalories FROM meals WHERE userID='$userID'";

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

  function getWorkouts($userID)
  {
    $workouts = [];

    // Check if email already exists in db
    $mealQuery = "SELECT workoutID,workoutName,workoutCalories FROM workouts WHERE userID='$userID'";

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
