<?php

// Before running test, comment out line 91 in gh-project-board.php

require_once 'gh-project-board.php';

$test_labels = array(
	array(
		'name' => '[Due Date] Monday January 10',
	),
	array(
		'name' => '[Due Date] Tuesday February 11',
	),
	array(
		'name' => '[Due Date] Wednesday March 12',
	),
	array(
		'name' => '[Due Date] Sunday May 16',
	),
	array(
		'name' => '[Due Date] Monday June 17',
	),
	array(
		'name' => '[Due Date] Thursday October 13',
	),
	array(
		'name' => '[Due Date] Friday November 14',
	),
	array(
		'name' => '[Due Date] Saturday December 15',
	),
);


$test_results = array(
	'10' => 2,
	'11' => 3,
	'12' => 4,
	'01' => 3,
	'02' => 4,
	'03' => 5,
);


foreach ( $test_results as $month => $expected ) {

	$test_today = strtotime( "$month/01" );

	$issues = check_issue_dates( $test_labels, $issues_overdue, $month, $test_today );

	if ( $issues === $expected ) {
		echo "Test for $month passed." . PHP_EOL;
	} else {
		echo "Test for $month failed. Expected $expected issues, found $issues issues." . PHP_EOL;
	}
}
