<?php
header('Content-Type: application/json');

// Get prompt from POST request
$prompt = $_POST['prompt'] ?? '';

if (empty($prompt)) {
    echo json_encode(['error' => 'No prompt provided']);
    exit;
}

// Google Gemini API configuration
$apiKey = 'API_KEY';
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey;

$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature' => 0.7,
        'maxOutputTokens' => 512
    ]
];

// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

// Execute API request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Handle API response
if ($response === false || $httpCode !== 200) {
    echo json_encode([
        'error' => 'Failed to connect to Gemini API: ' . ($error ?: 'HTTP Status ' . $httpCode)
    ]);
    exit;
}

$result = json_decode($response, true);

// Check for API errors
if (isset($result['error'])) {
    $errorMessage = $result['error']['message'] ?? 'Unknown API error';
    if (strpos($errorMessage, 'API key') !== false) {
        $errorMessage .= ' - Please verify your API key in Google Cloud Console.';
    }
    echo json_encode(['error' => $errorMessage]);
    exit;
}

// Extract response data
$text = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No response text received.';

echo json_encode([
    'text' => $text
]);
?>