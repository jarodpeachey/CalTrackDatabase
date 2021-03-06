<?php

// Database class
class WorkoutDB
{
  // Database variables
  private $host  = 'localhost';
  private $user  = 'soft'; // Change this
  private $password   = "root"; // Change this
  private $database  = "caltrack";
  private $table = 'workouts';
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

    $query = "
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

    if ($conn->query($query) === TRUE) {
      echo "Created workouts table!";
    } else {
      echo "Error creating database: " . $conn->error;
    }
  }

  function getWorkouts($userID)
  {
    $workouts = [];

    // Check if email already exists in db
    $workoutQuery = "SELECT workoutName,calories FROM workouts WHERE userID='$userID'";

    // Set response
    $response = $this->conn->query($workoutQuery);

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
