<?php
// session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';

include("auth.php"); // Fetch stored access token
require 'vendor/autoload.php'; // Include Composer's autoload file

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
   echo "<script>
                console.log('before post checking fi codntion');
                alert('before post checking fi codntion');
              </script>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gstin'])) {
       echo "<script>
                console.log('posted gstin:', " . $_POST['gstin'] . ");
                alert('psoted gstin is: ' + JSON.stringify(" . $_POST['gstin'] . "));
              </script>";
    $gstin = $_POST['gstin'];
    $requestid = substr(bin2hex(openssl_random_pseudo_bytes(8)), 0, 16);
  

    try {
        $client = new Client();

        // Define request headers
        $headers = [
            'Content-Type' => 'application/json',
            'user_name' => 'adqgsphpusr1',
            'password' => 'Gsp@1234',
            'gstin' => '02AMBPG7773M002',
            'requestid' => $requestid,
            'Authorization' => 'Bearer ' . $_SESSION['access_token']
        ];

        // Send GET request to the API
        $response = $client->request('GET', 'https://gsp.adaequare.com/test/enriched/ei/api/master/gstin', [
            'headers' => $headers,
            'query' => ['gstin' => $gstin],
        ]);

      

        
       // echo "<script> console.log('Request Headers:', " . json_encode($headers) . "); alert('Request Headers: ' + JSON.stringify(" . json_encode($headers) . ")); </script>";
$data = json_decode($response->getBody(), true);
        
        print_r($data);
        
        
        if ($data['success'] === true) {
            // Extract relevant fields
            $result = $data['result'];
            $responseData = [
                'success' => true,
                'LegalName' => $result['LegalName'] ?? '',
                'TradeName' => $result['TradeName'] ?? '',
                'AddrBnm' => $result['AddrBnm'] ?? '',
                'AddrBno' => $result['AddrBno'] ?? '',
                'AddrSt' => $result['AddrSt'] ?? '',
                'AddrLoc' => $result['AddrLoc'] ?? '',
                'StateCode' => $result['StateCode'] ?? '',
                'AddrPncd' => $result['AddrPncd'] ?? '',
                'Addaddr' => $result['addr'] ?? '',
                'Status' => $result['Status'] ?? ''
            ];
        
            // Log success response
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


