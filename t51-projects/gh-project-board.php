<?php


function curl_fetch() {

	$url = 'https://api.github.com/users/jessLundie'; // Set source URL

	$column_id = "15445284"; // Column id, found in the column link here: https://d.pr/i/W7RQJB

	$project_url = "https://api.github.com/projects/columns/" . $column_id . "/cards"; // Set project URL

	$token = "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"; // Basic Auth Token

	$user_agent = "jessLundie"; // Set user agent

	// Custom media header required for projects in beta
	$headers = array(
		"Accept: application/vnd.github.inertia-preview+json",
	);

	// Create a new cURL resource
	$process = curl_init($url);

	// Set options
	curl_setopt( $process, CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $process, CURLOPT_USERAGENT, $user_agent );
	curl_setopt( $process, CURLOPT_USERPWD, "$user_agent:$token" );
	curl_setopt( $process, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt( $process, CURLOPT_URL, $project_url );

	// Get project details
	$result = curl_exec( $process );

	// Close cURL
	curl_close( $process );

	return $result;
}

$response = curl_fetch();
$response = json_decode( $response );

$cards = count( $response );

echo "There are $cards issues in the Needs Triaged queue.";
