<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
include("auth.php");
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$responseData = [];

//$gstin = "29CFPPB7683J1ZP"; // hardcoded for test


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gstin'])) {
    $gstin = $_POST['gstin'];
$requestid = substr(bin2hex(openssl_random_pseudo_bytes(8)), 0, 16);



try {
    $accessToken = $_SESSION['access_token'] ?? '';

    if (empty($accessToken)) {
        throw new Exception("Access token is missing");
    }

    $client = new Client();
    $url = 'https://gsp.adaequare.com/enriched/commonapi/search';

    $response = $client->request('GET', $url, [
        'headers' => [
            'Content-Type' => 'application/json',
            // 'Authorization' => 'Bearer ' . $accessToken
            'Authorization' => 'Bearer ' . "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZSI6WyJnc3AiXSwiZXhwIjoxNzU1NDI5Njg4LCJhdXRob3JpdGllcyI6WyJST0xFX1BST0RfRV9BUElfR1NUX1JFVFVSTlMiLCJST0xFX1BST0RfRV9BUElfRVdCIiwiUk9MRV9QUk9EX0VfQVBJX0dTVF9DT01NT04iLCJST0xFX1BST0RfRV9BUElfRUkiXSwianRpIjoiMTE5N2U1ZWUtODI1Ny00OTg4LWFmNzUtZjdmZWZmOTdiYjdjIiwiY2xpZW50X2lkIjoiNzcxQ0I4RTVDMjcwNDlBNDhCMzg0MjY0MzkxNzUyODQifQ.7Vp3Wv_JfUWlWI43hVlBj2KeMTMTKFq9lTtDAR96dGc"
        ],
        'query' => [
            'action' => 'TP',
            'gstin' => $gstin
        ],
        'http_errors' => false
    ]);

    $statusCode = $response->getStatusCode();
    $data = json_decode($response->getBody(), true);
  

    if ($statusCode === 200 && $data['success'] === true && isset($data['result'])) {
        $result = $data['result'];
        $address = $result['pradr']['addr'] ?? [];
        $responseData=$data;
       
  
        error_log("Success: " . json_encode($responseData), 3, "error_log.txt");
        
        echo json_encode($responseData);
    } else {
        // Log failure response
        $message = $data['message'] ?? 'Unknown error';
        error_log("Error: " . $message, 3, "error_log.txt");
    
        echo json_encode(['success' => false, 'message' => $message]);
    }
    
    } catch (\Exception $e) {
        // Log exception details
        error_log("Exception: " . $e->getMessage(), 3, "error_log.txt");
    
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    } else {
        // Log invalid request
        error_log("Invalid request received.", 3, "error_log.txt");
    
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }
?>

