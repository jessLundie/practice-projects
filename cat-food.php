<?php

// Feeding calculator for cats based on weight and diet type

function how_much_food($weightCat, $kcalDry, $kcalWet, $dietType, $percentDry, $percentWet) {
  //constants
  $tbsConv = 0.0625; // cup / tbs
  $kcalBase = array (
    39,
    65,
    88,
    110,
    130,
    149,
    167,
    184,
    200,
    218,
    234,
    250,
    265,
    280,
    295,
    310,
    324,
    339,
    353,
    366,
  ); // RER / day by weight 1-20lbs ref. Hill’s Pet Nutrition calculator

  $dietMult = array (
    "Neutered adult cat" => 1.2,
    "Intact adult cat" => 1.4,
    "Inactive/obese prone cat" => 1,
    "Weight loss for cat" => 0.8,
    "Weight gain for cat" => 1.8,
    "Kitten 0 to 4 months" => 2.5,
    "Kitten 4 months to a year" => 2,
  ); // RER multiplier based on specific factors

  $multiplier = $dietMult[$dietType]; // get multiplier based on diet type

  $kcalFeed = $kcalBase[$weightCat-1] * $multiplier; // calculate total required kcal / day based on weight and diet type

  $qtyDry = round ((($percentDry * $kcalFeed) / $kcalDry) / $tbsConv, 2); // qty of dry cat food per day as a percent of total food
  $qtyCan = round (($percentWet * $kcalFeed) / $kcalWet, 2); // qty of wet cat food per day as a percent of total food

  if (($weightCat >= 1) && ($weightCat <= 20)) {

    echo "Feed $qtyDry tbs dry food and $qtyCan cans of wet food daily.";

  } else {
    echo "Please enter a weight between 1 and 20";
  }
}

how_much_food(1, 410, 190, "Kitten 0 to 4 months", .2, .8);
