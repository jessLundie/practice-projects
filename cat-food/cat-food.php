<?php

// Feeding calculator for cats based on weight and diet type

function base_caloric_intake( $cat_weight ) {
	// $cat_weight set in lbs
	// RER / day by weight 1-20lbs ref. Hill’s Pet Nutrition calculator
	$kcal_base = array(
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
	);

	if ( ( $cat_weight >= 1 ) && ( $cat_weight <= 20 ) ) {
		return $kcal_base[ $cat_weight - 1 ];
	} else {
			echo 'Please enter a weight between 1 and 20.';
			return null;
	}
}

function which_diet_multiplier( $diet_type ) {
	$diet_mult = array(
		'Neutered adult cat'        => 1.2,
		'Intact adult cat'          => 1.4,
		'Inactive/obese prone cat'  => 1,
		'Weight loss for cat'       => 0.8,
		'Weight gain for cat'       => 1.8,
		'Kitten 0 to 4 months'      => 2.5,
		'Kitten 4 months to a year' => 2,
	); // RER multiplier based on specific factors

	return $diet_mult[ $diet_type ];
}

function how_much_food( $cat_weight, $kcal_dry, $kcal_wet, $diet_type, $percent_dry, $percent_wet ) {
	//constants

	$tbs_conv = 0.0625; // cup / tbs

	$weight_check = base_caloric_intake( $cat_weight );

	if ( $weight_check ) {

		$multiplier = which_diet_multiplier( $diet_type ); // get multiplier based on diet type

		$kcal_feed = base_caloric_intake( $cat_weight ) * $multiplier; // calculate total required kcal / day based on weight and diet type

		$qty_dry = round( ( ( $percent_dry * $kcal_feed ) / $kcal_dry ) / $tbs_conv, 2 ); // calculate qty dry food(tbs)

		$qty_can = round( ( $percent_wet * $kcal_feed ) / $kcal_wet, 2 ); // calculate qty wet food (cans)

		echo esc_html( "Feed $qty_dry tbs dry food and $qty_can cans of wet food daily." );
	}
}
