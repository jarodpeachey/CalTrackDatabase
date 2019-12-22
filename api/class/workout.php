<?php
class Workout
{
  // database connection and table name
  public $conn;
  public $table_name = "workouts";

  // constructor
  public function __construct($db)
  {
    $this->conn = $db;
  }

  function addWorkout($workoutData)
  {
    $workoutName = $workoutData['workoutName'];
    $workoutCalories = $workoutData['workoutCalories'];
    $workoutDescription = $workoutData['workoutDescription'];
    $userID = $workoutData['userID'];

    $workoutName = htmlspecialchars($workoutName);
    $workoutCalories = htmlspecialchars($workoutCalories);
    $workoutDescription = htmlspecialchars($workoutDescription);

    // Email does not exist - insert workout
    $insertWorkoutQuery = "INSERT INTO workouts (workoutName, workoutCalories, workoutDescription, userID)
            VALUES (?, ?, ?, ?);";

    $workoutName = $workoutName;
    $workoutDescription = $workoutDescription;
    $workoutCalories = $workoutCalories;

    $stmt = $this->conn->prepare($insertWorkoutQuery);
    $stmt->bind_param("sssd", $workoutName, $workoutCalories, $workoutDescription, $userID);

    // Check if query succeeded and send response
    if ($stmt->execute()) {
      echo json_encode(["success" => true]);
    } else {
      die('execute() failed: ' . htmlspecialchars($stmt->error));
      echo json_encode(["success" => false]);
    }

    header('Content-Type: application/json');
  } // End createworkout()
}
