<?php
// require 'config.php';
// require 'vendor/autoload.php';

// use GuzzleHttp\Client;
// use GuzzleHttp\Exception\RequestException;

// $client = new Client();
// $gspappid = "79536E39F216449883720CCD53643D8F";
// $gspappsecret = "EE5EFAACG8434G43E8GA90EG9660E98C3D71";

// $url = 'https://gsp.adaequare.com/gsp/authenticate?grant_type=token';

// try {
//     $response = $client->request('POST', $url, [
//         'headers' => [
//             'gspappid' => $gspappid,
//             'gspappsecret' => $gspappsecret,
//         ],
//     ]);

//     $data = json_decode($response->getBody(), true);

//     if (isset($data['token_type']) && isset($data['expires_in'])) {
//         session_start();
//         $_SESSION['access_token'] = $data['access_token'];
//         $_SESSION['expires_at'] = date('Y-m-d H:i:s', time() + $data['expires_in']);

//         // Save to database
//         $sql = "INSERT INTO gsp_api (access_token, token_type, expires_at, created_at, gspappid, gspappsecret) 
//                 VALUES (?, ?, ?, NOW(), ?, ?)";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param('sssss', $data['access_token'], $data['token_type'], $_SESSION['expires_at'], $gspappid, $gspappsecret);
//         $stmt->execute();
//         $stmt->close();
//     }
// } catch (RequestException $e) {
//     echo "Token Generation Failed: " . $e->getMessage();
// }
?>
<?php
require 'config.php';


//Sandbox env 
$gspappid = "79536E39F216449883720CCD53643D8F";
$gspappsecret = "EE5EFAACG8434G43E8GA90EG9660E98C3D71";


//Live/Prod env
// $gspappid = "771CB8E5C27049A48B38426439175284";

// $gspappsecret = "818DBFA5GC86CG4542G8F72GF9F93DD0D49F";


$url = 'https://gsp.adaequare.com/gsp/authenticate?grant_type=token';

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // To return the response as a string
curl_setopt($ch, CURLOPT_POST, true);  // Use POST method
curl_setopt($ch, CURLOPT_POSTFIELDS, '');  // No body data for the authentication

// Set custom headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'gspappid: ' . $gspappid,
    'gspappsecret: ' . $gspappsecret,
]);

// Execute the request and get the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

// Close the cURL session
curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);

if (isset($data['token_type']) && isset($data['expires_in'])) {
    session_start();
    $_SESSION['access_token'] = $data['access_token'];
    $_SESSION['expires_at'] = date('Y-m-d H:i:s', time() + $data['expires_in']);

    // Save to database
    $sql = "INSERT INTO gsp_api (access_token, token_type, expires_at, created_at, gspappid, gspappsecret) 
            VALUES (?, ?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $data['access_token'], $data['token_type'], $_SESSION['expires_at'], $gspappid, $gspappsecret);
    if ($stmt->execute()) {
    

            // Optionally, you can show another alert for the token generation
            echo "<script>alert('Token generated successfully')</script>";
        }
        else
        {
            echo "<script>alert('Error in Token generatation')</script>";
        }
    $stmt->close();
}

?>
