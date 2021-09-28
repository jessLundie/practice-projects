<?php


$url = 'https://api.github.com/users/jessLundie'; // Set source URL


$media_header = array(
	'Accept: application/vnd.github.inertia-preview+json',
); // Custom media header required for projects in beta

$user = ''; // Project owner - GH username or organization

$token = ''; // GH Basic Auth Token - see https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token

$column_id = ''; // Column id, found in the column link here: https://d.pr/i/W7RQJB

$project_url = 'https://api.github.com/projects/columns/' . $column_id . '/cards'; // Set API URL for project

function get_data( $url, $media_header, $user, $token, $api_url ) {

	// Create a new cURL resource
	$process = curl_init( $url );

	// Set options
	curl_setopt( $process, CURLOPT_HTTPHEADER, $media_header );
	curl_setopt( $process, CURLOPT_USERAGENT, $user );
	curl_setopt( $process, CURLOPT_USERPWD, "$user:$token" );
	curl_setopt( $process, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $process, CURLOPT_URL, $api_url );

	// Get project details
	$result = curl_exec( $process );

	// Close cURL
	curl_close( $process );

	$result = json_decode( $result, true );
	return $result;

}

$cards = get_data( $url, $media_header, $user, $token, $project_url ); // List of cards

// Create a list of all labels

$label_list = array();

foreach ( $cards as $card ) {

	$label_url = $card['content_url'] . '/labels'; // Set API URL for labels

	$label = get_data( $url, $media_header, $user, $token, $label_url ); // API call to fetch labels

	if ( $label ) {

		$label_list[] = $label[0]; // Add each label to list of labels
	}
}

// Count issues where due date is before the current date

$issues_overdue = 0;
$current_month  = date( 'm' );
$today          = time();

function check_issue_dates( $label_list, $issues_overdue, $current_month, $today ) {

	foreach ( $label_list as $label ) {

		$issue_date = str_replace( '[Due Date] ', '', $label['name'] );

		$issue_month = date( 'm', strtotime( $issue_date ) );

		if ( ( $current_month >= 10 ) && ( $current_month <= 12 ) && ( $issue_month >= 01 ) && ( $issue_month <= 03 ) ) {
			$issue_date = str_replace( '[Due Date] ', '', $label['name'] ) . ' ' . ( date( 'Y' ) + 1 );
		}

		if ( ( $current_month >= 01 ) && ( $current_month <= 03 ) && ( $issue_month >= 10 ) && ( $issue_month <= 12 ) ) {
			$issue_date = str_replace( '[Due Date] ', '', $label['name'] ) . ' ' . ( date( 'Y' ) - 1 );
		}

		if ( strtotime( $issue_date ) < $today ) {
			$issues_overdue++;
		}
	}
	return $issues_overdue;
}

$issues_overdue = check_issue_dates( $label_list, $issues_overdue, $current_month, $today );

echo "There are $issues_overdue issues in the Needs Triaged queue that are past due or due today. Could someone please take a look?";
