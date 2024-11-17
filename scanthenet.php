<?php
// Print ASCII logo
echo "
  _______                    _______ __           ____ __         __
 |     __|.----.---.-.----- |_     _|  |--.-----.|    |  |.-----.|  |_
 |__     ||  __|  _  |     |  |   | |     |  -__||       ||  -__||   _|
 |_______||____|___._|__|__|  |___| |__|__|_____||__|____||_____||____|
\n";

function fetchData($url) {
    // Initialize CURL
    $curl = curl_init();

    // Set options
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute the request
    $response = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
        echo 'Request Error:' . curl_error($curl) . "\n";
        return null; // Return null on error
    }

    // Cleanup
    curl_close($curl);

    return $response; // Return the response
}

// Determine how many entries to display (default to max 100)
$maxEntries = 100; // Default value
if ($argc > 1) {
    $maxEntries = (int)$argv[1]; // Get user-defined value from command line
    if ($maxEntries < 1 || $maxEntries > 100) {
        echo "Please enter a number between 1 and 100.\n";
        exit(1); // Exit if the number is out of range
    }
}

// URL for the API
$url = "https://api.scanthe.net/";

// Fetch the data
$response = fetchData($url);
if ($response !== null) {
    // Parse the JSON data
    $jsonResponse = json_decode($response, true);

    // Check for parsing errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON parse error: " . json_last_error_msg() . "\n";
        exit(1);
    }

    // Accessing and printing relevant parts of the JSON
    $count = 0; // Counter for the entries displayed
    foreach ($jsonResponse['data'] as $packet) {
        if ($count >= $maxEntries) break; // Stop if we reach the maximum entries
        echo "ID: " . $packet['id'] . "\n";
        echo "Timestamp: " . $packet['timestamp'] . "\n";
        echo "Source IP: " . $packet['source_ip'] . "\n";
        echo "Source Port: " . $packet['source_port'] . "\n";
        echo "Destination Port: " . $packet['dest_port'] . "\n";
        echo "Data: " . $packet['data'] . "\n";
        echo "----------\n";
        $count++;
    }
}
?>
