<?php
// Enable error reporting (for debugging purposes)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include any necessary headers for CORS (if needed)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Retrieve the verification request data from GHL
$requestData = json_decode(file_get_contents('php://input'), true);

// Extract the transaction ID from the request data
$transactionId = isset($requestData['transactionId']) ? $requestData['transactionId'] : '';

// If the transaction ID is missing, return an error response
if (empty($transactionId)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Transaction ID is required.'
    ]);
    exit;
}

// Define the Premium Trust API endpoint for payment verification
$apiUrl = "";
$accessToken = 'your_access_token_here'; // Replace with your actual access token

// Prepare the data for the verification request
$data = [
    'transactionId' => $transactionId
];

// Initialize cURL session
$ch = curl_init($apiUrl);

// Set cURL options for the verification request
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Send data as form-data
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $accessToken, // Pass the access token in the header
    'Content-Type: application/x-www-form-urlencoded'
]);

// Execute the request and capture the response
$response = curl_exec($ch);

// Check for errors in the cURL request
if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to verify payment: ' . curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

// Decode the response from Premium Trust
$responseData = json_decode($response, true);

// Close the cURL session
curl_close($ch);

// Check the response from Premium Trust and send the appropriate response to GHL
if (isset($responseData['status']) && $responseData['status'] === 'success') {
    // Payment is verified successfully
    echo json_encode([
        'status' => 'success',
        'amount' => $responseData['amount'], // Include other details if necessary
        'currency' => $responseData['currency'],
        'transactionId' => $transactionId
    ]);
} else {
    // Payment verification failed
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Payment verification failed.'
    ]);
}
?>
