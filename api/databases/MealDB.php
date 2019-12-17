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
  private $dbConnect = false;
  private $loggedInUser;

  // Constructor
  public function __construct()
  {
    if (!$this->dbConnect) {
      $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
      if ($conn->connect_error) {
        die("Error failed to connect to MySQL: " . $conn->connect_error);
      } else {
        $this->dbConnect = $conn;
      }
    }

    $query = "
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

    if ($conn->query($query) === TRUE) {
      echo "Created meals table!";
    } else {
      echo "Error creating database: " . $conn->error;
    }
  }

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
}
