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
  private $database  = "caltrack";
  private $table = 'users';
  private $dbConnect = false;
  private $loggedInUser;

  // Constructor
  public function __construct()
  {
    if (!$this->dbConnect) {
      $conn = new mysqli($this->host, $this->user, $this->password);
      if ($conn->connect_error) {
        // die("Error failed to connect to MySQL: " . $conn->connect_error);
      } else {
        $this->dbConnect = $conn;
      }
    }

    if (!mysqli_select_db($conn, $this->database)) {
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
        );
      ";

      $query .= "
        CREATE TABLE meals (
          mealID INT AUTO_INCREMENT PRIMARY KEY,
          mealName VARCHAR(255) NOT NULL,
          calories int NOT NULL,
          userID INT,
          CONSTRAINT caltrack_meal
          FOREIGN KEY (userID)
            REFERENCES users(userID)
        );
      ";

      $query .= "
        CREATE TABLE workouts (
          workoutID INT AUTO_INCREMENT PRIMARY KEY,
          workoutName VARCHAR(255) NOT NULL,
          calories int NOT NULL,
          userID INT,
          CONSTRAINT caltrack_workout
          FOREIGN KEY (userID)
            REFERENCES users(userID)
        );
      ";

      if ($conn->multi_query($query) === TRUE) {
        $this->dbConnect = new mysqli($this->host, $this->user, $this->password, $this->database);
      } else {
        echo "Error creating database: " . $conn->error;
      }
    }

    mysqli_select_db($conn, $this->database);
  }

  function createUser($userData)
  {
    if ($this->dbConnect) {
      //Set variables from form
      $userName = $userData['name'];
      $userEmail = $userData['email'];
      $userPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

      // Check if email already exists in db
      $checkEmailQuery = "SELECT * FROM users WHERE email='$userEmail'";

      // Set response
      $response = $this->dbConnect->query($checkEmailQuery);

      // Check if the response exists
      if (mysqli_num_rows($response) > 0) {
        // Email exists - terminate and send email_used as true
        echo json_encode(["email_used" => true]);
      } else {
        // Email does not exist - insert user
        $insertUserQuery = "INSERT INTO users (username, email, hashpassword, caloriesGained, caloriesLost, netCalories)
            VALUES ('$userName', '$userEmail', '$userPassword', 0, 0, 0);";

        // Check if query succeeded and send response
        if ($this->dbConnect->query($insertUserQuery)) {
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
    $response = $this->dbConnect->query($checkEmailQuery);

    // Check if response exists
    if ($response->num_rows > 0) {
      // While there is a row, fetch the data from the response
      while ($row = $response->fetch_assoc()) {

        // Check password against hash from db
        if (password_verify("$userPassword", $row['hashpassword'])) {
          $meals = $this->getMeals($row['userID']);
          $workouts = $this->getMeals($row['userID']);

          $_SESSION['loggedInUser'] = [
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

  function getUser($getUserData)
  {
    // Return loggedInUser session variable
    echo json_encode($_SESSION['loggedInUser']);
  } // End getUser()

  function getMeals($userID)
  {
    $meals = [];

    // Check if email already exists in db
    $mealQuery = "SELECT mealName,calories FROM meals WHERE userID='$userID'";

    // Set response
    $response = $this->dbConnect->query($mealQuery);

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
    $response = $this->dbConnect->query($mealQuery);

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
