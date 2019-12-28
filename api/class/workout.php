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

  function addWorkout($workoutPost, $workoutGet)
  {
    $workoutName = $workoutPost['workoutName'];
    $workoutCalories = $workoutPost['workoutCalories'];
    $workoutDescription = $workoutPost['workoutDescription'];
    $userID = $workoutGet['userID'];

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

    $response = $stmt->execute();

    // Check if query succeeded and send response
    if ($response) {
      // $getWorkoutQuery = "SELECT workoutID,workoutName,workoutCalories,workoutDescription FROM workouts WHERE workoutTimestamp='$workoutTimestamp'";
      $lastID = $this->conn->insert_id;
      $selectQuery = "SELECT workoutID,workoutName,workoutCalories,workoutDescription,userID FROM workouts WHERE workoutID='$lastID'";
      $result = $this->conn->query($selectQuery);

      if ($result->num_rows > 0) {
        // While there is a row, fetch the data from the result
        while ($row = $result->fetch_assoc()) {
          $workoutToReturn = [
            "workoutID" => $row['workoutID'],
            "workoutName" => $row['workoutName'],
            "workoutCalories" => $row['workoutCalories'],
            "workoutDescription" => $row['workoutDescription'],
            "userID" => $row['userID'],
          ];

          // Send response data
          echo json_encode(["success" => true, "workout" => $workoutToReturn]);
        }
      }
    } else {
      die('execute() failed: ' . htmlspecialchars($stmt->error));
      echo json_encode(["success" => false]);
    }

    header('Content-Type: application/json');
  } // End createworkout()

  function updateWorkout($workoutPut, $workoutGet)
  {
    $workoutName = $workoutPut->workoutName;
    $workoutCalories = $workoutPut->workoutCalories;
    $workoutDescription = $workoutPut->workoutDescription;
    $workoutID = $workoutGet['workoutID'];

    $workoutName = htmlspecialchars($workoutName);
    $workoutCalories = htmlspecialchars($workoutCalories);
    $workoutDescription = htmlspecialchars($workoutDescription);

    $updateWorkoutQuery = "UPDATE workouts SET workoutName='$workoutName', workoutCalories='$workoutCalories', workoutDescription='$workoutDescription' WHERE workoutID='$workoutID'";
    $response = $this->conn->query($updateWorkoutQuery);

    // Check if query succeeded and send response
    if ($response) {
      echo json_encode(["success" => true]);
    } else {
      die('execute() failed: ' . htmlspecialchars($response->error));
      echo json_encode(["success" => false]);
    }

    header('Content-Type: application/json');
  } // End updateWorkout()

  function deleteWorkout($workoutID)
  {
    $deleteWorkoutQuery = "DELETE FROM workouts WHERE workoutID='$workoutID'";
    $response = $this->conn->query($deleteWorkoutQuery);

    // Check if query succeeded and send response
    if ($response) {
      echo json_encode(["success" => true, "workoutID" => $workoutID]);
    } else {
      die('execute() failed: ' . htmlspecialchars($response->error));
      echo json_encode(["success" => false]);
    }

    header('Content-Type: application/json');
  } // End deleteWorkout()
}
