<?php
// Start a session
session_start();

// Database class
class Database
{
  // Database variables
  private $host  = 'localhost';
  private $user  = 'soft'; // Change this
  private $password   = "root"; // Change this
  private $dbName  = "caltrack";
  private $conn = false;

  // Constructor
  public function __construct()
  {
    $conn = new mysqli($this->host, $this->user, $this->password);
    if ($conn->connect_error) {
      die("Error failed to connect to MySQL: " . $conn->connect_error);
    } else {
      $this->conn = $conn;
    }

    if (!mysqli_select_db($conn, $this->dbName)) {
      $query = "
        CREATE DATABASE IF NOT EXISTS caltrack;
      ";

      $query .= "USE caltrack;";

      $query .= "
        CREATE TABLE users (
          userID INT AUTO_INCREMENT PRIMARY KEY,
          username VARCHAR(255) NOT NULL,
          email VARCHAR(255) NOT NULL,
          hashpassword VARCHAR(255) NOT NULL UNIQUE,
          caloriesGained INT NOT NULL,
          caloriesLost INT NOT NULL,
          netCalories INT NOT NULL
        ) ENGINE=InnoDB;
      ";

      $query .= "
        CREATE TABLE meals (
          mealID INT AUTO_INCREMENT,
          mealName VARCHAR(255) NOT NULL,
          mealCalories int NOT NULL,
          mealDescription VARCHAR(255) NOT NULL,
          userID INT,
          PRIMARY KEY (mealID),
          CONSTRAINT FK_users_meals
          FOREIGN KEY (userID)
            REFERENCES users(userID)
        ) ENGINE=InnoDB;
      ";

      $query .= "
        CREATE TABLE workouts (
          workoutID INT AUTO_INCREMENT,
          workoutName VARCHAR(255) NOT NULL,
          workoutCalories int NOT NULL,
          workoutDescription VARCHAR(255) NOT NULL,
          userID INT,
          PRIMARY KEY (workoutID),
          CONSTRAINT FK_users_workouts
          FOREIGN KEY (userID)
            REFERENCES users(userID)
        ) ENGINE=InnoDB;
      ";

      if ($conn->multi_query($query) === TRUE) {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbName);
      } else {
        echo "Error creating database: " . $conn->error;
      }
    }

    mysqli_select_db($conn, $this->dbName);
  }

  public function getConnection()
  {
    if ($this->conn) {
      return $this->conn;
    } else {
      $conn = new mysqli($this->host, $this->user, $this->password, $this->dbName);
      if ($conn->connect_error) {
        die("Error failed to connect to MySQL: " . $conn->connect_error);
      } else {
        $this->conn = $conn;
      }
    }
    
    return $this->conn;
  }

  function createUser($userData)
  {
    if ($this->conn) {
      //Set variables from form
      $userName = $userData['name'];
      $userEmail = $userData['email'];
      $userPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

      // Check if email already exists in db
      $checkEmailQuery = "SELECT * FROM users WHERE email='$userEmail'";

      // Set response
      $response = $this->conn->query($checkEmailQuery);

      // Check if the response exists
      if (mysqli_num_rows($response) > 0) {
        // Email exists - terminate and send email_used as true
        echo json_encode(["email_used" => true]);
      } else {
        // Email does not exist - insert user
        $insertUserQuery = "INSERT INTO users (username, email, hashpassword, caloriesGained, caloriesLost, netCalories)
            VALUES ('$userName', '$userEmail', '$userPassword', 0, 0, 0);";

        // Check if query succeeded and send response
        if ($this->conn->query($insertUserQuery)) {
          echo json_encode(["email_used" => false, "success" => true]);
        } else {
          echo json_encode(["email_used" => false, "success" => false]);
        }
      }

      header('Content-Type: application/json');
    }
  } // End createUser()

  function verifyUser($userData)
  {
    //Set variables from form
    $userEmail = $userData['email'];
    $userPassword = $userData['password'];

    // Check if email already exists in db
    $checkEmailQuery = "SELECT * FROM users WHERE email='$userEmail'";

    // Set response
    $response = $this->conn->query($checkEmailQuery);

    // Check if response exists
    if ($response->num_rows > 0) {
      // While there is a row, fetch the data from the response
      while ($row = $response->fetch_assoc()) {

        // Check password against hash from db
        if (password_verify("$userPassword", $row['hashpassword'])) {
          $meals = $this->getMeals($row['userID']);
          $workouts = $this->getWorkouts($row['userID']);

          $_SESSION['loggedInUser'] = [
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
          echo json_encode(["match" => true, "user" => $_SESSION['loggedInUser']]);
        } else {
          // Passwords do not match - send response data
          echo json_encode(["match" => false, "other" => "Password does not match"]);
        }
      }
    } else {
      // No rows exist with that email, send response data
      echo json_encode(["match" => false]);
    }

    header('Content-Type: application/json');
  } // End verifyUser

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

  function getWorkouts($userID)
  {
    $workouts = [];

    // Check if email already exists in db
    $mealQuery = "SELECT workoutName,calories FROM workouts WHERE userID='$userID'";

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
