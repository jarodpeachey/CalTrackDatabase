CREATE DATABASE caltrack;

USE caltrack;

CREATE TABLE users (
  userID INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  hashpassword VARCHAR(255) NOT NULL UNIQUE,
  caloriesGained INT NOT NULL,
  caloriesLost INT NOT NULL,
  netCalories INT NOT NULL,
);

CREATE TABLE meals (
  mealID INT AUTO_INCREMENT PRIMARY KEY,
  mealName VARCHAR(255) NOT NULL,
  mealCalories int NOT NULL,
  mealDescription VARCHAR(255) NOT NULL,
  userID INT,
  CONSTRAINT users
  FOREIGN KEY (userID)
    REFERENCES users(userID)
);

CREATE TABLE workouts (
  workoutID INT AUTO_INCREMENT PRIMARY KEY,
  workoutName VARCHAR(255) NOT NULL,
  workoutCalories int NOT NULL,
  workoutDescription VARCHAR(255) NOT NULL,
  userID INT,
  CONSTRAINT user
  FOREIGN KEY (userID)
    REFERENCES users(userID)
);
