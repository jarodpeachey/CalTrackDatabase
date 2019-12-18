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

    // Check if email already exists in db
    $checkEmailQuery = "SELECT * FROM users WHERE email='$userEmail'";

    // Set response
    $response = $this->conn->query($checkEmailQuery);

    // Check if the response exists
    if (mysqli_num_rows($response) > 0) {
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
        return json_encode(["email_used" => false, "success" => true]);
        return true;
      } else {
        return json_encode(["email_used" => false, "success" => false]);
      }

      header('Content-Type: application/json');
    }
  } // End createUser()
}
