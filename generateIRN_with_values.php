
<?php
ini_set('display_errors', 1); // Display errors on the screen
ini_set('display_startup_errors', 1); // Display startup errors
error_reporting(E_ALL); // Report all errors


require 'config.php';

// <form action="generateIRN.php" method="POST">
//         $gspappid="79536E39F216449883720CCD53643D8F";
//     $gspappsecret="EE5EFAACG8434G43E8GA90EG9660E98C3D71";
    
//     $user_name="adqgsphpusr1";
// $password="Gsp@1234";


// // API details
// $url = 'https://gsp.adaequare.com/gsp/authenticate?grant_type=token';
// $headers = array(
//     'gspappid: 79536E39F216449883720CCD53643D8F',
//     'gspappsecret: EE5EFAACG8434G43E8GA90EG9660E98C3D71',
    
// );

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inv_id=$_POST['inv_id'];
    $gspappid =  $_POST['gspappid']; 
    $gspappsecret =  $_POST['gspappsecret']; 
    $user_name = $_POST['username']; // Get username from form
    $password = $_POST['password']; // Get password from form
    
    
    
    $sql = "
SELECT * FROM invoice
WHERE id = $inv_id
";


$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
    // Output the result (for example, displaying the row as an associative array)
    while($row = mysqli_fetch_assoc($result)) {
       $invoice_code=$row['invoice_code'];
       $customer_id = $row['customer_id'];
$customer_name = $row['customer_name'];
$customer_email = $row['customer_email'];
       
       // print_r($row['invoice_code']);  // For example, just printing the row
    }
} else {
    echo "No records found";
}

   echo $customer_id;
// SQL Query to fetch address details based on customer ID
$sql = "SELECT `id`, `s_address_line1`, `s_address_line2`, `s_city`, `s_Pincode`, `s_state`, `s_country`, `s_branch`, `s_gstin`
        FROM `address_master`
        WHERE `customer_master_id` = $customer_id";

// Assuming you've connected to the database using mysqli or PDO, you would execute the query like this:
$result = mysqli_query($conn, $sql);

// Check if the query executed successfully and if there are any results
if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the result (assuming only one row will be returned for a customer ID)
    $row = mysqli_fetch_assoc($result);

    // Extract address data from the row
    $s_address_line1 = $row['s_address_line1'];
    $s_address_line2 = $row['s_address_line2'];
    $s_city = $row['s_city'];
    $s_pincode = $row['s_Pincode'];
    $s_state = $row['s_state'];
    $s_country = $row['s_country'];
    $s_branch = $row['s_branch'];
    $s_gstin = $row['s_gstin'];

    // Combine this with other details (like GSTIN, legal name, etc.)
    $buyer_info = [
        'Gstin' => $s_gstin ? $s_gstin : '36AMBPG7773M002',  // Use actual GSTIN from DB if available, else fallback
        'LglNm' => 'XYZ company pvt ltd',  // Example legal name of the buyer
        'TrdNm' => 'XYZ Industries',  // Example trade name of the buyer
        'Pos' => '12',  // Example position
        'Addr1' => $s_address_line1,  // Address 1 from the database
        'Addr2' => $s_address_line2,  // Address 2 from the database
        'Loc' => $s_city,  // Location (city) from the database
        'Pin' => $s_pincode,  // Pin code from the database
        'Stcd' => '36',  // Example state code
        'Country' => $s_country,  // Country from the database
        'Branch' => $s_branch 
    ];

    // Now you can use $buyer_info in your application
    echo '<pre>';
    print_r($buyer_info);  // Display the array for testing purposes
    echo '</pre>';

} else {
    // If no address details were found, you can handle the case here
    echo "No address details found for this customer.";
}


// Prepare the SQL query to get the mobile number from the customer_master table
$sql = "SELECT mobile FROM customer_master WHERE id = $customer_id";

// Execute the query
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);

    // Check if mobile exists in the result
    if (isset($row['mobile'])) {
        // Assign the mobile number to the variable
        $customer_mobile = $row['mobile'];

        // You can now use $customer_mobile variable as needed
        echo "Customer Mobile: " . $customer_mobile;
    } else {
        echo "No mobile number found for customer ID $customer_id.";
    }
} else {
    echo "Error executing query: " . mysqli_error($conn);
}
  // echo "<script>
    //     alert('Received Values: inv_id= $inv_id , gspappid = $gspappid, gspappsecret = $gspappsecret, username = $user_name');
    //     console.log('gspappid:', '$gspappid');
    //     console.log('gspappsecret:', '$gspappsecret');
    //     console.log('username:', '$user_name');
    //     console.log('password:', '$password');
    // </script>";

    // You can also print these values on the page
    // echo "gspappid: $gspappid <br>";
    // echo "gspappsecret: $gspappsecret <br>";
    // echo "Username: $user_name <br>";
    // echo "Password: $password <br>";

    $url = 'https://gsp.adaequare.com/gsp/authenticate?grant_type=token';
    
    $headers = array(
        'gspappid: ' . $gspappid,
        'gspappsecret: ' . $gspappsecret,
    );


    
  $query = "SELECT access_token, IF(MAX(expires_at) > NOW(), TRUE, FALSE) AS is_expired
          FROM gsp_api
          WHERE gspappid = ? AND gspappsecret = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $gspappid, $gspappsecret);
$stmt->execute();
$stmt->bind_result($access_token, $is_expired);
$stmt->fetch();
$stmt->close();


if ($is_expired) {
 //   echo "No need to generate a new token. Access token: " . $access_token;
   // echo "No need to generate a new token ";
} 
else {
    echo "Token is expired, need to generate a new token.";

// Make the API request
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($curl);
$curl_error = curl_error($curl);

curl_close($curl);

if ($curl_error) {
    echo "CURL Error: " . $curl_error;
} else {
    $data = json_decode($response, true);

    if (isset($data['token_type']) && isset($data['expires_in'])) {
        $tokenType = $data['token_type'];
        $expiresInSeconds = $data['expires_in'];
        $access_token = $data['access_token'];
        
        // Calculate the expiration timestamp
        $expiresAt = date('Y-m-d H:i:s', time() + $expiresInSeconds);

        

        $sql = "INSERT INTO gsp_api ( access_token,token_type, expires_at, created_at, gspappid, gspappsecret) VALUES ('$access_token','$tokenType', '$expiresAt', NOW(), '$gspappid', '$gspappsecret')";

        if ($conn->query($sql) === TRUE) {
            echo "API data stored successfully in the database. Expires at: " . $expiresAt;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        echo "Error: token_type or expires_in not found in the API response. Response was: " . $response;
    }
}
}




$inv_no = 'IRN' . uniqid(); // Generate a unique invoice number
$current_date = date('d/m/Y'); // Get the current date



$gstin="02AMBPG7773M002";
//$gstin="29AAICK7493G1ZX";



$url = 'https://gsp.adaequare.com/test/enriched/ei/api/master/gstin?gstin=' . $gstin;
$headers = [
    'Content-Type: application/json',
    'user_name: ' . $user_name,
    'password: ' . $password,
    'gstin: ' . $gstin,
    'requestid: ' . uniqid(),
    'Authorization: Bearer ' . $access_token,
];

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Execute cURL request and get response
$response = curl_exec($ch);

// Check for errors
if(curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Decode the JSON response into an associative array
    $response_data = json_decode($response, true);

    // Check if the request was successful
    if ($response_data['success'] == true) {
        // Extract data for Seller and Buyer details
        $SellerDtls = [
            'Gstin' => $response_data['result']['Gstin'],
            'LglNm' => $response_data['result']['LegalName'],
            'TrdNm' => $response_data['result']['TradeName'],
            'Addr1' => $response_data['result']['AddrBnm'] . ' ' . $response_data['result']['AddrBno'],  // Combine address block and number
            'Addr2' => $response_data['result']['AddrFlno'],  // If address floor number is available, else leave it empty
            'Loc' => $response_data['result']['AddrLoc'],
            'Pin' => $response_data['result']['AddrPncd'],
            'Stcd' => $response_data['result']['StateCode'],
            'Ph' => '',  // You can update with phone number if available
            'Em' => ''   // You can update with email if available
        ];

        // Example Buyer details
        $BuyerDtls = [
            'Gstin' => '36AMBPG7773M002',  // Example GSTIN of the buyer
            'LglNm' => 'XYZ company pvt ltd',  // Example legal name of the buyer
            'TrdNm' => 'XYZ Industries',  // Example trade name of the buyer
            'Pos' => '12',  // Example position
            'Addr1' => '7th block, kuvempu layout',  // Example address 1
            'Addr2' => 'kuvempu layout',  // Example address 2
            'Loc' => 'GANDHINAGAR',  // Example location
            'Pin' => 500055,  // Example pin code
            'Stcd' => '36',  // Example state code
            'Ph' => '91111111111',  // Example phone number
            'Em' => 'xyz@yahoo.com'  // Example email
        ];

        
        // print_r($SellerDtls);
        // print_r($BuyerDtls);

        // You can now store these details in a database, or process them further
    } else {
        echo 'Error: ' . $response_data['message'];
    }
}

// Close cURL session
curl_close($ch);





$headers = [
    'Content-Type: application/json',
    'user_name: ' . $user_name,
    'password: ' . $password,
    'gstin: ' . $gstin,
    'requestid: ' . uniqid(),
    'Authorization: Bearer ' . $access_token,
   
];


$data = [
    'Version' => '1.1',
    'TranDtls' => [
        'TaxSch' => 'GST',
        'SupTyp' => 'B2B',
        'RegRev' => 'N',
        'EcmGstin' => null,
        'IgstOnIntra' => 'N'
    ],
    'DocDtls' => [
        'Typ' => 'INV',
        'No' => $inv_no,
        'Dt' => $current_date
    ],
    // 'SellerDtls' => [
    //     'Gstin' => $gstin,
    //     'LglNm' => 'NIC company pvt ltd',
    //     'TrdNm' => 'NIC Industries',
    //     'Addr1' => '5th block, kuvempu layout',
    //     'Addr2' => 'kuvempu layout',
    //     'Loc' => 'GANDHINAGAR',
    //     'Pin' => 175121,
    //     'Stcd' => '02',
    //     'Ph' => '9000000000',
    //     'Em' => 'abc@gmail.com'
    // ],

    'SellerDtls' => [
        'Gstin' => $gstin,
        'LglNm' => 'KRIKA MKB CORPORATION PRIVATE LIMITED',
        'TrdNm' => 'KRIKA MKB CORPORATION PRIVATE LIMITED',
        'Addr1' => 'SKYLINE BEVERLY PARK',
        'Addr2' => 'D - 402',
        'Loc' => 'BANGALORE',
        'Pin' => 560009,
        'Stcd' => '29',
        'Ph' => '9000000000',
        'Em' => 'abc@gmail.com'
    ],
       
    'BuyerDtls' => [
        // 'Gstin' => '36AMBPG7773M002',
        // 'LglNm' => 'XYZ company pvt ltd',
        // 'TrdNm' => 'XYZ Industries',
        // 'Pos' => '12',
        // 'Addr1' => '7th block, kuvempu layout',
        // 'Addr2' => 'kuvempu layout',
        // 'Loc' => 'GANDHINAGAR',
        // 'Pin' => 500055,
        // 'Stcd' => '36',
        // 'Ph' => '91111111111',
        // 'Em' => 'xyz@yahoo.com'
        'Gstin' => $s_gstin ? $s_gstin : $gstin,  // Use actual GSTIN from DB if available, else fallback
        'LglNm' => 'XYZ company pvt ltd',  // Example legal name of the buyer
        'TrdNm' => 'XYZ Industries',  // Example trade name of the buyer
        'Pos' => '12',  // Example position
        'Addr1' => $s_address_line1,  // Address 1 from the database
        'Addr2' => $s_address_line2,  // Address 2 from the database
        'Loc' => $s_city,  // Location (city) from the database
        'Pin' => $s_pincode,  // Pin code from the database
        'Stcd' => '36',  // Example state code
        
       'Ph'=>$customer_email ,
       'Em'=> $customer_mobile


    ],
    'DispDtls' => [
        'Nm' => 'ABC company pvt ltd',
        'Addr1' => '7th block, kuvempu layout',
        'Addr2' => 'kuvempu layout',
        'Loc' => 'Banagalore',
        'Pin' => 562160,
        'Stcd' => '29'
    ],
    'ShipDtls' => [
        'Gstin' => '36AMBPG7773M002',
        'LglNm' => 'CBE company pvt ltd',
        'TrdNm' => 'kuvempu layout',
        'Addr1' => '7th block, kuvempu layout',
        'Addr2' => 'kuvempu layout',
        'Loc' => 'Banagalore',
        'Pin' => 500055,
        'Stcd' => '36'
    ],
    'ItemList' => [
        [
            'SlNo' => '1',
            'PrdDesc' => 'Rice',
            'IsServc' => 'N',
            'HsnCd' => '1001',
            'Barcde' => '123456',
            'Qty' => 100.345,
            'FreeQty' => 10,
            'Unit' => 'BAG',
            'UnitPrice' => 99.545,
            'TotAmt' => 9988.84,
            'Discount' => 10,
            'PreTaxVal' => 1,
            'AssAmt' => 9978.84,
            'GstRt' => 12,
            'IgstAmt' => 1197.46,
            'CgstAmt' => 0,
            'SgstAmt' => 0,
            'CesRt' => 5,
            'CesAmt' => 498.94,
            'CesNonAdvlAmt' => 10,
            'StateCesRt' => 12,
            'StateCesAmt' => 1197.46,
            'StateCesNonAdvlAmt' => 5,
            'OthChrg' => 10,
            'TotItemVal' => 12897.7,
            'OrdLineRef' => '3256',
            'OrgCntry' => 'AG',
            'PrdSlNo' => '12345',
            'BchDtls' => [
                'Nm' => '123456',
                'Expdt' => '01/08/2020',
                'wrDt' => '01/09/2020'
            ],
            'AttribDtls' => [
                [
                    'Nm' => 'Rice',
                    'Val' => '10000'
                ]
            ]
        ]
    ],
    'ValDtls' => [
        'AssVal' => 9978.84,
        'CgstVal' => 0,
        'SgstVal' => 0,
        'IgstVal' => 1197.46,
        'CesVal' => 508.94,
        'StCesVal' => 1202.46,
        'Discount' => 10,
        'OthChrg' => 20,
        'RndOffAmt' => 0.3,
        'TotInvVal' => 12908,
        'TotInvValFc' => 12897.7
    ],
    'PayDtls' => [
        'Nm' => 'ABCDE',
        'Accdet' => '5697389713210',
        'Mode' => 'Cash',
        'Fininsbr' => 'SBIN11000',
        'Payterm' => '100',
        'Payinstr' => 'Gift',
        'Crtrn' => 'test',
        'Dirdr' => 'test',
        'Crday' => 100,
        'Paidamt' => 10000,
        'Paymtdue' => 5000
    ],
    'RefDtls' => [
        'InvRm' => 'TEST',
        'DocPerdDtls' => [
            'InvStDt' => '01/08/2020',
            'InvEndDt' => '01/09/2020'
        ],
        'PrecDocDtls' => [
            [
                'InvNo' => 'DOC/002',
                'InvDt' => '01/08/2020',
                'OthRefNo' => '123456'
            ]
        ],
        'ContrDtls' => [
            [
                'RecAdvRefr' => 'Doc/003',
                'RecAdvDt' => '01/08/2020',
                'Tendrefr' => 'Abc001',
                'Contrrefr' => 'Co123',
                'Extrefr' => 'Yo456',
                'Projrefr' => 'Doc-456',
                'Porefr' => 'Doc-789',
                'PoRefDt' => '01/08/2020'
            ]
        ]
    ],
    'AddlDocDtls' => [
        [
            'Url' => 'https://einv-apisandbox.nic.in',
            'Docs' => 'Test Doc',
            'Info' => 'Document Test'
        ]
    ],
    'ExpDtls' => [
        'ShipBNo' => 'A-248',
        'ShipBDt' => '01/08/2020',
        'Port' => 'INABG1',
        'RefClm' => 'N',
        'ForCur' => 'AED',
        'CntCode' => 'AE',
        'ExpDuty' => null
    ]
];

$ch = curl_init('https://gsp.adaequare.com/test/enriched/ei/api/invoice');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$response2 =$response ;




if ($response === false) {
    $error = curl_error($ch);
    echo "cURL error: $error";
} else {
 // echo "Response: $response";
      
    $genearatedIRN_decoded_response = json_decode($response2, true);

$IRN=$genearatedIRN_decoded_response['result']['Irn'];

echo "<pre>";
//print_r($genearatedIRN_decoded_response);
echo "</pre>";
  
  // Assuming the response is stored in $response as an array
$response = json_decode($response, true);  // Assuming the response is JSON, parse it

$ack_no = $response['result']['AckNo'];
$ack_date = $response['result']['AckDt'];
$irn_no = $response['result']['Irn'];
$signed_qr_code = $response['result']['SignedQRCode'];
$signed_invoice = $response['result']['SignedInvoice'];
$qr_image = '';  // You might need to assign this or extract it, if it's available in the response
$e_way_bill_no = $response['result']['EwbNo'];
$e_way_bill_date = $response['result']['EwbDt'];
$e_way_bill_valid_till = $response['result']['EwbValidTill'];


curl_close($ch);



$url = 'https://gsp.adaequare.com/test/enriched/ei/others/qr/image';


$headers = [
    'Content-Type: text/plain',
   'user_name: ' . $user_name,
    'password: ' . $password,
    'gstin: ' . $gstin,
    'requestid: ' . uniqid(),
    'Authorization: Bearer ' . $access_token,
    'width: 300',
    'height: 300',
    'imgtype: jpg',
  
];

// Data payload
$data = $signed_qr_code ; 
// Create a cURL handle
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Ensures the response is returned as a string
curl_setopt($ch, CURLOPT_POST, true); // Use POST method
curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Attach the data payload

// Execute the cURL request and fetch the response
$response = curl_exec($ch);
// Get the response code
$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
    curl_close($ch);
    
}

// Debugging: Print the response code
//echo "Response Code: " . $responseCode . "\n";

// Check if we got a valid image response
if ($responseCode !== 200) {
    echo "Unexpected response from server. Response code: $responseCode\n";
    
}

// Check the content type of the response (should be image/jpeg or image/png)
$headers = get_headers($url, 1); // Get response headers
if (isset($headers['Content-Type']) && strpos($headers['Content-Type'], 'image') === false) {
  //  echo "Received non-image content: " . $headers['Content-Type'] . "\n";
    
}

// Check if the response is valid (image)
if (empty($response)) {
    echo 'No image data received';
    
}

// Save the binary image response to a file
$filePath = 'invoice/'.$invoice_code.'.jpg'; // You can change the file path/extension as necessary
file_put_contents($filePath, $response);

//echo "Image saved to $filePath";




// Prepare your SQL UPDATE query
$sql = "
UPDATE invoice 
SET 
    ack_no = '$ack_no',
    ack_date = '$ack_date',
    irn_no = '$irn_no',
    signed_qr_code = '$signed_qr_code',
    signed_invoice = '$signed_invoice',
    qr_image = '$filePath',
    e_way_bill_no = '$e_way_bill_no',
    e_way_bill_date = '$e_way_bill_date',
    e_way_bill_valid_till = '$e_way_bill_valid_till'
    
WHERE id = $inv_id
";

// Execute the query
if (mysqli_query($conn, $sql)) {
    //echo "Record updated successfully";
   //  echo "<script>alert('Record updated successfully')</script>";
} else {
    echo "Error updating record: " . mysqli_error($conn);
    echo "<script>alert('error in  updated ')</script>";
}

  
   



// Assuming the necessary variables are set, such as $user_name, $password, $gstin, $access_token, $irn_no

// Set the API endpoint
$url = 'https://gsp.adaequare.com/test/enriched/ei/api/ewaybill';

// Prepare the headers
$headers = [
    'Content-Type: application/json',
    'user_name: ' . $user_name,
    'password: ' . $password,
    'gstin: ' . $gstin,
    'requestid: ' . uniqid(),
    'Authorization: Bearer ' . $access_token,
];

// Prepare the JSON data
$data = [
    "Irn" => $irn_no, 
    "Distance" => 0,
    "TransMode" => "1",
    "TransId" => "04AMBPG7773M002",
    "TransName" => "trans name",
    "TransDocDt" => $current_date,
    "TransDocNo" => "TRAN/DOC/11",
    "VehNo" => "KA12ER1234",
    "VehType" => "R"
];

// Convert the data to JSON
$data_json = json_encode($data);

// Initialize cURL session
$ch = curl_init($url);

// Set the cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);

// Execute the cURL request
$response = curl_exec($ch);

// Check for cURL errors
if ($response === false) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    
    
    
    
    // Assuming the response is stored in $response as an array
$response = json_decode($response, true);  // Parse the JSON response

// Extracting the necessary fields from the response
$ewb_no = $response['result']['EwbNo'];
$ewb_dt = $response['result']['EwbDt'];
$ewb_valid_till = $response['result']['EwbValidTill'];

// Prepare your SQL UPDATE query
$sql = "
UPDATE invoice
SET 
    e_way_bill_no = '$ewb_no',
    e_way_bill_date = '$ewb_dt',
    e_way_bill_valid_till = '$ewb_valid_till'
WHERE id = $inv_id
";

// Execute the query
if (mysqli_query($conn, $sql)) {
   // echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

}

// Close the cURL session
curl_close($ch);


   // echo "Response: $response";
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Details</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
   
<h2>Invoice Details</h2>

<table>
    <tr>
        <th>Invoice ID</th>
        <th>Acknowledgment Number</th>
        <th>Acknowledgment Date</th>
        <th>IRN Number</th>
        <!--<th>Signed QR Code</th>-->
        <!--<th>Signed Invoice</th>-->
        <th>QR Image</th>
        <th>E-way Bill Number</th>
        <th>E-way Bill Date</th>
        <th>E-way Bill Valid Till</th>
    </tr>
    <tr> 
    <?php
    $table_sql = "
SELECT * FROM invoice
WHERE id = $inv_id
";


$result = mysqli_query($conn, $table_sql);


if (mysqli_num_rows($result) > 0) {
    // Output the result (for example, displaying the row as an associative array)
    while($row = mysqli_fetch_assoc($result)) {
       $invoice_code=$row['invoice_code'];
        //print_r($row['invoice_code']);  // For example, just printing the row
        
          ?>
        <!--  <td><?php echo $inv_id; ?></td>-->
        <!--<td><?php echo $ack_no; ?></td>-->
        <!--<td><?php echo $ack_date; ?></td>-->
        <!--<td><?php echo $irn_no; ?></td>-->
        <!--<td><a href="data:image/png;base64,<?php echo $signed_qr_code; ?>" target="_blank">View QR Code</a></td>-->
        <!--<td><a href="data:application/pdf;base64,<?php echo $signed_invoice; ?>" target="_blank">View Invoice</a></td>-->
        <!--<td><?php echo $filePath ? '<img src="'.$filePath.'" alt="QR Image">' : 'No QR Image'; ?></td>-->
        <!--<td><?php echo $e_way_bill_no; ?></td>-->
        <!--<td><?php echo $e_way_bill_date; ?></td>-->
        <!--<td><?php echo $e_way_bill_valid_till; ?></td>-->
        <td><?php echo $row['invoice_code']; ?></td>
<td><?php echo $row['ack_no']; ?></td>
<td><?php echo $row['ack_date']; ?></td>
<td><?php echo $row['irn_no']; ?></td>

<td>
    <?php 
        echo $row['qr_image'] ? 
            '<img src="' . $row['qr_image'] . '" alt="QR Image" style="width: 100px; height: 100px;">' : 
            'No QR Image'; 
    ?>
</td>

<td><?php echo $row['e_way_bill_no']; ?></td>
<td><?php echo $row['e_way_bill_date']; ?></td>
<td><?php echo $row['e_way_bill_valid_till']; ?></td>

        <?php
    }
}
else {
    echo "No records found";
}
    ?>    
     
    </tr>
</table>

</body>
</html>



<?php
}
?>