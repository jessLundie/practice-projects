<?php

$url = ''; // Google Calendar API URL
​
$headers = [
    'Accept: application/json'
];
​
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
var_dump($ch);
$response = curl_exec($ch);
​
$response = json_decode($response, true);
//var_dump($response);
​
// For debugging
​
$info = curl_getinfo($ch);
​
curl_close($ch);
​
​
​
$time = new DateTime; // sets current date & time
​
$now = $time->format(DateTime::ATOM); // converts format to ATOM to match GCal API

// Returns next event
​
foreach ( $response['items'] as $event_id => $event ) {

	foreach ( $event as $title => $value ) {

		if ($event['start']['dateTime'] >= $now) {
			$next_event_title = $event['summary'];
			$next_event_time = $event['start']['dateTime'];
		} else {
			break;
		}
	}
}
​
echo "The next " . $next_event_title . " is at " . $next_event_time;
