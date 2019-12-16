<?php
class User
{
  public $name;
  public $caloriesGained;
  public $caloriesLost;
  public $netCalories;

  function __construct($user)
  {
    $name = $user['username'];
    $caloriesGained = $user['caloriesGained'];
    $caloriesLost = $user['caloriesLost'];
    $netCalories = $user['netCalories'];
  }
}
