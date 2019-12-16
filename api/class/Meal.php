<?php
class Meal
{
  public $calories;
  public $mealName;

  function __construct($meal)
  {
    $name = $meal['name'];
    $calories = $meal['calories'];
  }
}
