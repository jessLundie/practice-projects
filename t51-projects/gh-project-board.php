<?php


$url = 'https://api.github.com/users/jessLundie'; // Set source URL

$media_header = array(
	"Accept: application/vnd.github.inertia-preview+json",
); // Custom media header required for projects in beta

$user_agent = "jessLundie"; // Set user agent

$token = ""; // Basic Auth Token

$column_id = "15445284"; // Column id, found in the column link here: https://d.pr/i/W7RQJB

$project_url = "https://api.github.com/projects/columns/" . $column_id . "/cards"; // Set API URL for project

function curl_fetch( $url, $media_header, $user_agent, $token, $api_url ) {

	// Create a new cURL resource
	$process = curl_init($url);

	// Set options
	curl_setopt( $process, CURLOPT_HTTPHEADER, $media_header );
	curl_setopt( $process, CURLOPT_USERAGENT, $user_agent );
	curl_setopt( $process, CURLOPT_USERPWD, "$user_agent:$token" );
	curl_setopt( $process, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt( $process, CURLOPT_URL, $api_url );

	// Get project details
	$result = curl_exec( $process );

	// Close cURL
	curl_close( $process );

	$result = json_decode( $result, true ); // Convert response to array
	return $result;

}

$cards = curl_fetch( $url, $media_header, $user_agent, $token, $project_url ); // List of cards

// Create a list of all labels

$label_list = [];

foreach ( $cards as $card ) {

	$label_url = $card['content_url'] . "/labels"; // Set API URL for labels

	$label = curl_fetch( $url, $media_header, $user_agent, $token, $label_url  ); // API call to fetch labels

	$label_list = array_merge( $label_list, $label ); // Add each label to list of labels
}

// Count issues where due date is before the current date

$issues_overdue = 0;

foreach ( $label_list as $label ) {

	$issue_date =  str_replace( "[Due Date] ", '', $label['name'] ) . " " . date( 'Y' );

	if ( strtotime( $issue_date ) < time() ) {

		$issues_overdue++;
	}
}

echo "There are $issues_overdue issues in the Needs Triaged queue that are past due or due today. Could someone please take a look?";
