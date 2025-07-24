
<?php
ini_set('display_errors', 1); // Display errors on the screen
ini_set('display_startup_errors', 1); // Display startup errors
error_reporting(E_ALL); // Report all errors


require '../config.php';

// API details
$url = 'https://gsp.adaequare.com/gsp/authenticate?grant_type=token';
$headers = array(
    'gspappid: 79536E39F216449883720CCD53643D8F',
    'gspappsecret: EE5EFAACG8434G43E8GA90EG9660E98C3D71',
    
);

        $gspappid="79536E39F216449883720CCD53643D8F";
    $gspappsecret="EE5EFAACG8434G43E8GA90EG9660E98C3D71";
    
  $query = "SELECT access_token, IF(MAX(expires_at) > NOW(), TRUE, FALSE) AS is_expired
          FROM gsp_api
          WHERE gspappid = ? AND gspappsecret = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $gspappid, $gspappsecret);
$stmt->execute();
$stmt->bind_result($access_token, $is_expired);
$stmt->fetch();
$stmt->close();
$conn->close();

if ($is_expired) {
 //   echo "No need to generate a new token. Access token: " . $access_token;
    echo "No need to generate a new token ";
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

$user_name="adqgsphpusr1";
$password="Gsp@1234";
$gstin="02AMBPG7773M002";

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
        'RegRev' => 'Y',
        'EcmGstin' => null,
        'IgstOnIntra' => 'N'
    ],
    'DocDtls' => [
        'Typ' => 'INV',
        'No' => $inv_no,
        'Dt' => $current_date
    ],
    'SellerDtls' => [
        'Gstin' => $gstin,
        'LglNm' => 'NIC company pvt ltd',
        'TrdNm' => 'NIC Industries',
        'Addr1' => '5th block, kuvempu layout',
        'Addr2' => 'kuvempu layout',
        'Loc' => 'GANDHINAGAR',
        'Pin' => 175121,
        'Stcd' => '02',
        'Ph' => '9000000000',
        'Em' => 'abc@gmail.com'
    ],
    'BuyerDtls' => [
        'Gstin' => '36AMBPG7773M002',
        'LglNm' => 'XYZ company pvt ltd',
        'TrdNm' => 'XYZ Industries',
        'Pos' => '12',
        'Addr1' => '7th block, kuvempu layout',
        'Addr2' => 'kuvempu layout',
        'Loc' => 'GANDHINAGAR',
        'Pin' => 500055,
        'Stcd' => '36',
        'Ph' => '91111111111',
        'Em' => 'xyz@yahoo.com'
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

if ($response === false) {
    $error = curl_error($ch);
    echo "cURL error: $error";
} else {
  echo "<script>alert('IRN created successfully')</script>";
  
   // echo "Response: $response";
      
    $genearatedIRN_decoded_response = json_decode($response, true);

$IRN=$genearatedIRN_decoded_response['result']['Irn'];

echo "<pre>";
print_r($genearatedIRN_decoded_response);
echo "</pre>";



   // echo "Response: $response";
}

curl_close($ch);




//echo $IRN;


$headers = [
    'Content-Type: application/json',
    'user_name: ' . $user_name,
    'password: ' . $password,
    'gstin: ' . $gstin,
    'requestid: ' . uniqid(),
    'Authorization: Bearer ' . $access_token,
];

$data = [
    'Irn' => $IRN,
    'Cnlrsn' => '1',
    'Cnlrem' => 'Wrong entry'
];

$ch = curl_init('https://gsp.adaequare.com/test/enriched/ei/api/invoice/cancel');


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));


$response = curl_exec($ch);

if ($response === false) {
    $error = curl_error($ch);
    echo "cURL error: $error";
} else {
      
    $cancelledIRN_decoded_response = json_decode($response, true);



echo "<pre>";
print_r($cancelledIRN_decoded_response);
echo "</pre>";
    
    echo "<script>alert('IRN Cancllled successfully')</script>";
}

curl_close($ch);

