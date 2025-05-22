<?php
/*---------------------------------------------
| 1. SET RESPONSE HEADER TO JSON
|---------------------------------------------*/
header('Content-Type: application/json');

/*---------------------------------------------
| 2. GET PROMPT FROM POST REQUEST
|---------------------------------------------*/
$prompt = $_POST['prompt'] ?? '';

// If no prompt is provided, return an error
if (empty($prompt)) {
    echo json_encode(['error' => 'No prompt provided']);
    exit;
}

/*---------------------------------------------
| 3. SETUP GOOGLE GEMINI API CONFIGURATION
|---------------------------------------------*/
$apiKey = 'API_KEY'; // Replace with your actual API key
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey;

// Data payload to send to the Gemini API
$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt] // User input (prompt)
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7,        // Controls creativity level
        'maxOutputTokens' => 512     // Max response length
    ]
];

/*---------------------------------------------
| 4. INITIALIZE CURL AND CONFIGURE REQUEST
|---------------------------------------------*/
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                  // Get response as string
curl_setopt($ch, CURLOPT_POST, true);                            // Use POST method
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));        // Send JSON data
curl_setopt($ch, CURLOPT_HTTPHEADER, [                           // Set headers
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);                  // Verify SSL cert
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);                     // Verify hostname
curl_setopt($ch, CURLOPT_TIMEOUT, 30);                           // Max wait time
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);                    // Max connect time

/*---------------------------------------------
| 5. EXECUTE THE REQUEST AND CAPTURE RESPONSE
|---------------------------------------------*/
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);               // HTTP status
$error = curl_error($ch);

/*---------------------------------------------
| 6. CLOSE THE CONNECTION
|---------------------------------------------*/
curl_close($ch);

/*---------------------------------------------
| 7. HANDLE ERRORS IF REQUEST FAILED
|---------------------------------------------*/
if ($response === false || $httpCode !== 200) {
    echo json_encode([
        'error' => 'Failed to connect to Gemini API: ' . ($error ?: 'HTTP Status ' . $httpCode)
    ]);
    exit;
}

/*---------------------------------------------
| 8. DECODE API RESPONSE
|---------------------------------------------*/
$result = json_decode($response, true);

// Check for any API-level errors
if (isset($result['error'])) {
    $errorMessage = $result['error']['message'] ?? 'Unknown API error';

    // Specific message for API key issues
    if (strpos($errorMessage, 'API key') !== false) {
        $errorMessage .= ' - Please verify your API key in Google Cloud Console.';
    }

    echo json_encode(['error' => $errorMessage]);
    exit;
}

/*---------------------------------------------
| 9. EXTRACT AND RETURN GENERATED TEXT
|---------------------------------------------*/
$text = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No response text received.';

echo json_encode([
    'text' => $text
]);
?>
