<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// require 'config.php';
// require 'vendor/autoload.php'; // Load Guzzle
// include("auth.php"); // Fetch stored access token

// use GuzzleHttp\Client;
// use GuzzleHttp\Exception\RequestException;

// $client = new Client();

// // Ensure we have a valid access token
// $access_token = $_SESSION['access_token'];
// $gstin = "02AMBPG7773M002"; // Seller GSTIN

// if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
//     !(isset($_POST['action']) && $_POST['action'] === 'cancel' && isset($_POST['irn_no'])) &&
//     !(isset($_POST['eway_bill_cancel_action']) && $_POST['eway_bill_cancel_action'] === 'eway_bill_cancel')) {

//     $inv_id = $_POST['inv_id'];
// echo "Received Invoice ID: " . htmlspecialchars($inv_id) . "<br>";
//     $user_name = $_POST['username'];
//     $password = $_POST['password'];

//     // Fetch Invoice Details
//     $sql = "SELECT * FROM invoice WHERE id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $inv_id);
//     $stmt->execute();
//     $result = $stmt->get_result();
    
//     if ($row = $result->fetch_assoc()) {
//         $invoice_code = $row['invoice_code'];
//         $cst_id = $row['customer_id'];
//     } else {
//         die("No invoice found.");
//     }
    
//     $stmt->close();

//     // Fetch Seller Details from GST API
//     $url = "https://gsp.adaequare.com/test/enriched/ei/api/master/gstin?gstin=$gstin";

//     try {
//         $response = $client->request('GET', $url, [
//             'headers' => [
//                 'Content-Type' => 'application/json',
//                 'user_name' => $user_name,
//                 'password' => $password,
//                 'gstin' => $gstin,
//                 'requestid' => uniqid(),
//                 'Authorization' => 'Bearer ' . $access_token,
//             ],
//         ]);

//         $sellerResponse = json_decode($response->getBody(), true);

//         if ($sellerResponse['success']) {
//             $SellerDtls = [
//                 'Gstin' => $sellerResponse['result']['Gstin'],
//                 'LglNm' => $sellerResponse['result']['LegalName'],
//                 'TrdNm' => $sellerResponse['result']['TradeName'],
//                 'Addr1' => $sellerResponse['result']['AddrBnm'] . ' ' . $sellerResponse['result']['AddrBno'],
//                 'Addr2' => $sellerResponse['result']['AddrFlno'] ?? '',
//                 'Loc' => $sellerResponse['result']['AddrLoc'],
//                 'Pin' => $sellerResponse['result']['AddrPncd'],
//                 'Stcd' => $sellerResponse['result']['StateCode'],
//                 'Ph' => '',
//                 'Em' => ''
//             ];
//         } else {
//             die("Seller details not found: " . $sellerResponse['message']);
//         }

//     } catch (RequestException $e) {
//         die("Error fetching Seller details: " . $e->getMessage());
//     }

//     // Fetch Product Details Dynamically
//     $product_sql = "SELECT * FROM invoice_items WHERE invoice_id = ?";
//     $stmt = $conn->prepare($product_sql);
//     $stmt->bind_param('i', $inv_id);
//     $stmt->execute();
//     $product_result = $stmt->get_result();

//     $ItemList = [];
//     while ($product = $product_result->fetch_assoc()) {
//         $ItemList[] = [
//             'SlNo' => $product['id'],
//             'PrdDesc' => $product['product'],
//             'IsServc' => 'N',
//             //'HsnCd' => $product['hsn_code'],
//             //'Barcde' => $product['barcode'],
//             'Qty' => $product['qty'],
//             'FreeQty' => 0,
//             'Unit' => 'PCS',
//             'UnitPrice' => $product['price'],
//             'TotAmt' => $product['line_total'],
//             'Discount' => $product['discount'],
//             'PreTaxVal' => 0,
//             'AssAmt' => $product['total'],
//             'GstRt' => $product['gst'],
//             'IgstAmt' => $product['igst'],
//             'CgstAmt' => $product['cgst'],
//             'SgstAmt' => $product['sgst'],
//             'CesRt' => $product['cess_rate'],
//             'CesAmt' => $product['cess_amount'],
//             'CesNonAdvlAmt' => 0,
//             'StateCesRt' => 0,
//             'StateCesAmt' => 0,
//             'StateCesNonAdvlAmt' => 0,
//             'OthChrg' => 0,
//             'TotItemVal' => $product['total'],
//             'OrdLineRef' => '',
//             'OrgCntry' => 'IN'
//         ];
//     }
//     $stmt->close();

//     $query = "SELECT * FROM customer_master c JOIN address_master a on a.customer_master_id = c.id  WHERE c.id = '$cst_id'";
//   $stmt = $conn->prepare($query);
//     $stmt->bind_param("i", $cst_id);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         $buyer = $result->fetch_assoc();

//         // Buyer Details Array
//         $BuyerDtls = [
//             'Gstin' => $buyer['gstin'],
//             'LglNm' => $buyer['gst_reg_name'],
//             'TrdNm' => $buyer['business_name'],
//             'Pos' => '12', // Assuming 'Pos' is the state code
//             'Addr1' => $buyer['s_address_line1'],
//             'Addr2' => $buyer['s_address_line2'],
//             'Loc' => $buyer['s_city'],
//             'Pin' => $buyer['s_Pincode'],
//             'Stcd' => $buyer['state_code'],
//             'Ph' => $buyer['mobile'],
//             'Em' => $buyer['email']
//         ];

//     // Generate IRN Request
//     $inv_no = 'IRN' . uniqid();
//     $current_date = date('d/m/Y');

//     $data = [
//         'Version' => '1.1',
//         'TranDtls' => [
//             'TaxSch' => 'GST',
//             'SupTyp' => 'B2B',
//             'RegRev' => 'N',
//             'EcmGstin' => null,
//             'IgstOnIntra' => 'N'
//         ],
//         'DocDtls' => [
//             'Typ' => 'INV',
//             'No' => $inv_no,
//             'Dt' => $current_date
//         ],
//         'SellerDtls' => $SellerDtls,
//         'BuyerDtls' => $BuyerDtls,
//         'ItemList' => $ItemList
//     ];

//     try {
//         $response = $client->request('POST', 'https://gsp.adaequare.com/test/enriched/ei/api/invoice', [
//             'headers' => [
//                 'Content-Type' => 'application/json',
//                 'user_name' => $user_name,
//                 'password' => $password,
//                 'gstin' => $gstin,
//                 'requestid' => uniqid(),
//                 'Authorization' => 'Bearer ' . $access_token,
//             ],
//             'json' => $data,
//         ]);

//         $generatedIRN_decoded_response = json_decode($response->getBody(), true);
//         $IRN = $generatedIRN_decoded_response['result']['Irn'];

//         // Save IRN details in the database
//         $update_sql = "UPDATE invoice SET ack_no=?, ack_date=?, irn_no=?, signed_qr_code=?, signed_invoice=?, e_way_bill_no=?, e_way_bill_date=?, e_way_bill_valid_till=? WHERE id=?";
//         $stmt = $conn->prepare($update_sql);
//         $stmt->bind_param(
//             'ssssssssi',
//             $generatedIRN_decoded_response['result']['AckNo'],
//             $generatedIRN_decoded_response['result']['AckDt'],
//             $IRN,
//             $generatedIRN_decoded_response['result']['SignedQRCode'],
//             $generatedIRN_decoded_response['result']['SignedInvoice'],
//             $generatedIRN_decoded_response['result']['EwbNo'],
//             $generatedIRN_decoded_response['result']['EwbDt'],
//             $generatedIRN_decoded_response['result']['EwbValidTill'],
//             $inv_id
//         );
//         $stmt->execute();
//         $stmt->close();

//         echo "<script>alert('IRN created successfully')</script>";

//     } catch (RequestException $e) {
//         die("IRN Generation Failed: " . $e->getMessage());
    
//     }
//     }
// }
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
include("auth.php"); // Fetch stored access token

// Initialize cURL
$ch = curl_init();

// Ensure we have a valid access token
$access_token = $_SESSION['access_token'];
$gstin = "02AMBPG7773M002"; // Seller GSTIN

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    !(isset($_POST['action']) && $_POST['action'] === 'cancel' && isset($_POST['irn_no'])) &&
    !(isset($_POST['eway_bill_cancel_action']) && $_POST['eway_bill_cancel_action'] === 'eway_bill_cancel')) {

    $inv_id = $_POST['inv_id'];
    echo "Received Invoice ID: " . htmlspecialchars($inv_id) . "<br>";
    $user_name = $_POST['username'];
    $password = $_POST['password'];

    // Fetch Invoice Details
    $sql = "SELECT * FROM invoice WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $inv_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $invoice_code = $row['invoice_code'];
        $cst_id = $row['customer_id'];
    } else {
        die("No invoice found.");
    }
    
    $stmt->close();

$branch_gstin="29AAICK7493G1ZX";
    // Fetch Seller Details from GST API using cURL
    $url = "https://gsp.adaequare.com/test/enriched/ei/api/master/gstin?gstin=$branch_gstin";

    $headers = [
        'Content-Type: application/json',
        'user_name: ' . $user_name,
        'password: ' . $password,
        'gstin: ' . $gstin,
        'requestid: ' . uniqid(),
        'Authorization: Bearer ' . $access_token,
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as a string
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');  // Set request type to GET
    
    $response = curl_exec($ch); // Execute cURL request
    
    if ($response === false) {
        die("cURL Error: " . curl_error($ch));
    }
    
    $sellerResponse = json_decode($response, true);
    
    $sellerResponse = json_decode($response, true);
echo "<pre>"; // Optional: formats the output for better readability
print_r($sellerResponse);
echo "</pre>";

    
    if ($sellerResponse['success']) {
        $SellerDtls = [
            'Gstin' => $sellerResponse['result']['Gstin'],
            'LglNm' => $sellerResponse['result']['LegalName'],
            'TrdNm' => $sellerResponse['result']['TradeName'],
            'Addr1' => $sellerResponse['result']['AddrBnm'] . ' ' . $sellerResponse['result']['AddrBno'],
           'Addr2' => !empty($sellerResponse['result']['AddrFlno']) ? $sellerResponse['result']['AddrFlno'] : $sellerResponse['result']['AddrBnm'] . ' ' . $sellerResponse['result']['AddrBno'],

            'Loc' => $sellerResponse['result']['AddrLoc'],
            'Pin' => $sellerResponse['result']['AddrPncd'],
            //'Stcd' => $sellerResponse['result']['StateCode'],
             'Stcd' => "29",
            'Ph' => '8106517443',
            'Em' => 'irctcssy@gmail.com'
        ];
        echo "<pre>";  // Optional: Formats the output for better readability
    print_r($SellerDtls);
    echo "</pre>";
    } else {
        die("Seller details not found: " . $sellerResponse['message']);
    }
echo "invocie id for searchng item list ".$inv_id;
    // Fetch Product Details Dynamically
    $product_sql = "SELECT * FROM invoice_items WHERE invoice_id = ?";
    $stmt = $conn->prepare($product_sql);
    $stmt->bind_param('i', $inv_id);
    $stmt->execute();
    $product_result = $stmt->get_result();

    $ItemList = [];
  $AssVal = 0;
$CgstVal = 0;
$SgstVal = 0;
$IgstVal = 0;
$CesVal = 0;
$StCesVal = 0;
$Discount = 0;
$OthChrg = 0;

while ($product = $product_result->fetch_assoc()) {
    $ItemList[] = [
        'SlNo' => "43".$product['id'],
        'PrdDesc' => $product['product'],
        'IsServc' => 'N',
        "HsnCd"=> "1001",
      "Barcde"=> "123456",
        'Qty' => $product['qty'],
        'FreeQty' => 0,
        'Unit' => 'PCS',
        'UnitPrice' => $product['price'],
        'TotAmt' => $product['line_total'],
        'Discount' => $product['discount'],
        'PreTaxVal' => 0,
        'AssAmt' => $product['total'],
        'GstRt' => $product['gst'],
        'IgstAmt' => $product['igst'],
        'CgstAmt' => $product['cgst'],
        'SgstAmt' => $product['sgst'],
        'CesRt' => $product['cess_rate'],
        'CesAmt' => $product['cess_amount'],
        'CesNonAdvlAmt' => 0,
        'StateCesRt' => 0,
        'StateCesAmt' => 0,
        'StateCesNonAdvlAmt' => 0,
        'OthChrg' => 0,
        'TotItemVal' => $product['total'],
        'OrgCntry' => 'IN'
    ];

    // Add to total values
    $AssVal += $product['total']; // Assessed value (Pre-Tax value of products)
    $CgstVal += $product['cgst']; // Central GST Value
    $SgstVal += $product['sgst']; // State GST Value
    $IgstVal += $product['igst']; // IGST Value
    $CesVal += $product['cess_amount']; // Cess Value
    $StCesVal += $product['cess_amount']; // State Cess (if applicable)
    $Discount += $product['discount']; // Total Discount on Products
    $OthChrg += 0; // Other Charges (you can modify this if you have other charges)
}


// Round TotInvVal to two decimal places
$TotInvVal = round($AssVal + $CgstVal + $SgstVal + $IgstVal + $CesVal + $StCesVal + $Discount + $OthChrg + 0.3, 2);

// Round TotInvValFc to two decimal places (if needed)
$TotInvValFc = round($AssVal + $CgstVal + $SgstVal + $IgstVal + $CesVal + $StCesVal + $Discount + $OthChrg - 10, 2);

// Ensure that the result is formatted as a string (if required by the API or system)
$TotInvVal = number_format($TotInvVal, 2, '.', '');  // Ensure 2 decimal places, no commas
$TotInvValFc = number_format($TotInvValFc, 2, '.', ''); // Same for TotInvValFc

// Now, set the calculated values into the 'ValDtls' array
$ValDtls = [
    "AssVal" => $AssVal,
    "CgstVal" => $CgstVal,
    "SgstVal" => $SgstVal,
    "IgstVal" => $IgstVal,
    "CesVal" => $CesVal,
    "StCesVal" => $StCesVal,
    "Discount" => $Discount,
    "OthChrg" => $OthChrg,
    "RndOffAmt" => 0.3, // Assuming you are rounding to 2 decimal places, you can compute this if needed
    "TotInvVal" => $TotInvVal,
    "TotInvValFc" => $TotInvValFc 
];

echo "<pre>";
print_r($ValDtls);
echo "</pre>";

echo "<pre>";
print_r($ItemList);
echo "</pre>";

echo "cusotmer id is ".$cst_id;
    
    $stmt->close();

    // Fetch Buyer Details
   $query = "SELECT * FROM customer_master c JOIN address_master a on a.customer_master_id = c.id WHERE c.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $cst_id);
$stmt->execute();
$result = $stmt->get_result();


    if ($result->num_rows > 0) {
        $buyer = $result->fetch_assoc();


        // Buyer Details Array
        $BuyerDtls = [
            'Gstin' => $buyer['gstin'],
            'LglNm' => $buyer['gst_reg_name'],
            'TrdNm' => $buyer['business_name'],
            'Pos' => '12', // Assuming 'Pos' is the state code
            'Addr1' => $buyer['s_address_line1'],
            'Addr2' => $buyer['s_address_line2'],
            'Loc' => $buyer['s_city'],
            'Pin' => $buyer['s_Pincode'],
           'Stcd' => "29",
       // 'Stcd' => $buyer['state_code'],
            'Ph' => $buyer['mobile'],
            'Em' => $buyer['email']
        ];

echo "<pre> businees gstin dtails";  // Optional: Formats the output for better readability
    print_r($BuyerDtls);
    echo "</pre>";
        // Generate IRN Request
        $inv_no = 'IRN' . uniqid();
        $current_date = date('d/m/Y');

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
            'SellerDtls' => $SellerDtls,
            'BuyerDtls' => $BuyerDtls,
            'ItemList' => $ItemList,
            'ValDtls' => $ValDtls
        ];

        // Generate IRN with cURL
        $url = "https://gsp.adaequare.com/test/enriched/ei/api/invoice";
        $headers = [
            'Content-Type: application/json',
            'user_name: ' . $user_name,
            'password: ' . $password,
            'gstin: ' . $gstin,
            'requestid: ' . uniqid(),
            'Authorization: Bearer ' . $access_token,
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response as a string
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');  // Set request type to POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));  // Attach JSON data

        $response = curl_exec($ch);  // Execute cURL request

        if ($response === false) {
            die("cURL Error: " . curl_error($ch));
        }

        $generatedIRN_decoded_response = json_decode($response, true);
        $IRN = $generatedIRN_decoded_response['result']['Irn'];
echo  "IRN is ".$IRN;
var_dump($generatedIRN_decoded_response);

        // Save IRN details in the database
        $update_sql = "UPDATE invoice SET ack_no=?, ack_date=?, irn_no=?, signed_qr_code=?, signed_invoice=?, e_way_bill_no=?, e_way_bill_date=?, e_way_bill_valid_till=? WHERE id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param(
            'ssssssssi',
            $generatedIRN_decoded_response['result']['AckNo'],
            $generatedIRN_decoded_response['result']['AckDt'],
            $IRN,
            $generatedIRN_decoded_response['result']['SignedQRCode'],
            $generatedIRN_decoded_response['result']['SignedInvoice'],
            $generatedIRN_decoded_response['result']['EwbNo'],
            $generatedIRN_decoded_response['result']['EwbDt'],
            $generatedIRN_decoded_response['result']['EwbValidTill'],
            $inv_id
        );
       if ($stmt->execute()) {
    //echo "<script>alert('IRN created successfully')</script>";
} else {
    echo "Error: " . $stmt->error; // Print error if something goes wrong
}

$stmt->close();

}

curl_close($ch);  // Close cURL session
}
?>
