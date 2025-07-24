<?php
// session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
// require 'vendor/autoload.php'; // Load Guzzle
include("auth.php"); // Fetch stored access token
// 
require 'vendor/autoload.php'; // Include Composer's autoloader

// use 
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Initialize Guzzle Client
$client = new Client();
   $user_name = $_POST['username'];
    $password = $_POST['password'];
$access_token = $_SESSION['access_token'];
// $gstin = "02AMBPG7773M002"; // Seller GSTIN

$gstin = $_SESSION['sel_gstin'];


$irn_no = $_POST['irn_no'];

// Set the Invoice Cancellation URL
$invoiceCancelUrl = "https://gsp.adaequare.com/test/enriched/ei/api/invoice/cancel";
//$invoiceCancelUrl = "https://gsp.adaequare.com/enriched/ei/api/invoice/cancel";
// Prepare the data to send in the request body
$data = [
    'Irn' => $irn_no,  // IRN (Invoice Reference Number) to cancel
    'Cnlrsn' => '1',        // Cancellation reason code (you can update it)
    'Cnlrem' => 'Wrong entry', // Remarks for cancellation
];

$headers= [
        'Content-Type' => 'application/json',  // Content type
        'user_name' => $user_name,             // Your user name
        'password' => $password,               // Your password
        'gstin' => $gstin,                     // GSTIN (Goods and Services Tax Identification Number)
        'requestid' => uniqid(),               // Unique request ID (e.g., generated using uniqid())
        'Authorization' => 'Bearer ' . $access_token,  // Authorization header with Bearer token
    ];
echo "<pre>";
echo "Headers:\n";
echo json_encode($headers, JSON_PRETTY_PRINT);
print_r($headers);

// Show the JSON data
echo "\n\nData:\n";
echo json_encode($data, JSON_PRETTY_PRINT);
echo "</pre>";

// Send the POST request using $client
$invoiceCancelResponse = $client->post($invoiceCancelUrl, [
    'headers' => [
        'Content-Type' => 'application/json',  // Content type
        'user_name' => $user_name,             // Your user name
        'password' => $password,               // Your password
        'gstin' => $gstin,                     // GSTIN (Goods and Services Tax Identification Number)
        'requestid' => uniqid(),               // Unique request ID (e.g., generated using uniqid())
        'Authorization' => 'Bearer ' . $access_token,  // Authorization header with Bearer token
    ],
    'json' => $data,  // Send the cancellation data as JSON
]);

// Get the response from the API
$invoiceCancelResponseBody = json_decode($invoiceCancelResponse->getBody(), true);

// Print the response to see what we get back from the server
echo "<pre>"; print_r($invoiceCancelResponseBody); echo "</pre>";
