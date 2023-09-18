<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userText = $_POST["user_text"];

    // Save user-editable text as a cookie to persist after browser restarts
    setcookie('user_text', $userText, time() + (86400 * 30), '/'); // Cookie lasts for 30 days

    // Initialize cURL session
    $curl = curl_init();

    // Set the URL to fetch
    curl_setopt($curl, CURLOPT_URL, $userText);

    // Set cURL options to handle headers and response
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Add User-Agent header
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'User-Agent: YourApp' // Replace 'YourApp' with an appropriate name
    ]);

    // Execute the cURL request
    $response = curl_exec($curl);

    if ($response === false) {
        echo "cURL Error: " . curl_error($curl);
    } else {
        $jsonResponse = json_decode($response, true);

        if ($jsonResponse !== null) {
            $sortedStrings = sortStrings($jsonResponse);

            $responseData = [
                'original_response' => $jsonResponse,
                'sorted_strings' => $sortedStrings
            ];

            // Display both original and processed responses
            echo "<div style='display:flex;'>";
            echo "<div style='flex: 1; margin-right: 10px; max-width: 50%;'>"; // Adjust max-width here
            echo "<h2 style='text-align: center;'>URL Response</h2>";
            echo "<pre style='max-width: 100%; overflow-x: auto; padding: 3%; margin: 10px 10px 0 7%; border: 1px solid black;'>" . htmlentities(json_encode($responseData['original_response'], JSON_PRETTY_PRINT)) . "</pre>";
            echo "</div>";

            echo "<div style='flex: 1; max-width: 50%;'>"; // Adjust max-width here
            echo "<h2 style='text-align: center;'>Processed URL Response</h2>";
            echo "<pre style='max-width: 100%; overflow-x: auto; padding: 3%; margin: 10px 10px 0 7%; border: 1px solid black;'>" . htmlentities(json_encode($responseData['sorted_strings'], JSON_PRETTY_PRINT)) . "</pre>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<h2>Empty response from $userText</h2>";
        }
    }

    // Close the cURL session
    curl_close($curl);
}

function sortStrings($data)
{
    if (is_array($data)) {
        foreach ($data as &$value) {
            if (is_array($value)) {
                $value = sortStrings($value);
            } elseif (is_string($value)) {
                $value = strrev($value);
            }
        }
    }
    return $data;
}
?>