<?php

/*
Plugin Name: Cat Food Calculator
Plugin URI: https://github.com/jessLundie/practice-projects/cat-food-calculator
Description: A simple calculator to help determine how much to feed your cat
Version: 1.0
Author: Jess Lundie
Author URI:
License: GPL2
*/


function cfc_base_caloric_intake( $cat_weight ) {
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

	$is_allowed_weight = $cat_weight >= 1 && $cat_weight <= 20;

	if ( ! $is_allowed_weight ) {
		echo 'Please enter a weight between 1 and 20.';
		return null;
	}
	return $kcal_base[ $cat_weight - 1 ];
}

function cfc_diet_multipliers() {
	return array(
		'neutered_adult',
		'intact_adult',
		'inactive',
		'weight_loss',
		'weight_gain',
		'kitten_0to4',
		'kitten_4to12',
	);
}

function cfc_multiplier_description( $diet_type ) {
	switch ( $diet_type ) {
		case 'neutered_adult':
			return 'Neutered adult cat';

		case 'intact_adult':
			return 'Intact adult cat';

		case 'inactive':
			return 'Inactive/obese prone cat';

		case 'weight_loss':
			return 'Weight loss for cat';

		case 'weight_gain':
			return 'Weight gain for cat';

		case 'kitten_0to4':
			return 'Kitten 0 to 4 months';

		case 'kitten_4to12':
			return 'Kitten 4 months to a year';

	}
}

function cfc_which_diet_multiplier( $diet_type ) {
	$diet_mult = array(
		'neutered_adult' => 1.2,
		'intact_adult'   => 1.4,
		'inactive'       => 1,
		'weight_loss'    => 0.8,
		'weight_gain'    => 1.8,
		'kitten_0to4'    => 2.5,
		'kitten_4to12'   => 2,
	); // RER multiplier based on specific factors

	return $diet_mult[ $diet_type ];
}

function cfc_how_much_food( $cat_weight, $kcal_dry, $kcal_wet, $diet_type, $percent_dry, $percent_wet ) {
	//constants

	$tbs_conv = 0.0625; // cup / tbs

	$weight_check = cfc_base_caloric_intake( $cat_weight );

	if ( $weight_check ) {

		$multiplier = cfc_which_diet_multiplier( $diet_type ); // get multiplier based on diet type

		$kcal_feed = cfc_base_caloric_intake( $cat_weight ) * $multiplier; // calculate total required kcal / day based on weight and diet type

		$qty_dry = round( ( ( $percent_dry * $kcal_feed ) / $kcal_dry ) / $tbs_conv, 2 ); // calculate qty dry food(tbs)

		$qty_can = round( ( $percent_wet * $kcal_feed ) / $kcal_wet, 2 ); // calculate qty wet food (cans)

		error_log( "Feed $qty_dry tbs dry food and $qty_can cans of wet food daily." );

	}
}

//Register shortcode

add_shortcode( 'cat-food-calculator', 'cfc_form_shortcode' );

function cfc_form_shortcode() {
	?>

	<h3>Cat Food Calculator</h3>

	<form action=""<?php echo plugins_url( 'cat-food-calculator.php', __FILE__ ); ?>" method="post">
	<label for="diet_mult">Cat details / diet preference:</label>
	<br />
	<select id="diet_mult" name="diet_type">
	<?php

	foreach ( cfc_diet_multipliers() as $multiplier ) {
		echo '<option value="' . $multiplier . '">' . cfc_multiplier_description( $multiplier ) . '</option>' . '<br />';
	}
	?>
	</select>
	<br />
	<label for="cat_weight">Enter your cat's weight in lbs (between 1 and 20):</label>
	<br />
	<input type="text" name="cat_weight"></input>
	<br />
	<label for="kcal_wet">Enter wet food - calories per CAN:</label>
	<br />
	<input type="text" name="kcal_wet"></input>
	<br />
	<label for="kcal_dry">Enter dry food calories per CUP:</label>
	<br />
	<input type="text" name="kcal_dry"></input>
	<br />
	<label for="percent_wet">What percentage of the diet would you like to feed in WET food?</label>
	<br />
	<input type="text" name="percent_wet"></input>
	<br />
	<label for="percent_dry">What percentage of the diet would you like to feed in DRY food?</label>
	<br />
	<input type="text" name="percent_dry"></input>
	<br />
	<input type="submit">

	</form>

	<?php
}
