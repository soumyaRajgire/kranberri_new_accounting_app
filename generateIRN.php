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
//     $url = "https://gsp.adaequare.com/enriched/ei/api/master/gstin?gstin=$gstin";

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
//         $response = $client->request('POST', 'https://gsp.adaequare.com/enriched/ei/api/invoice', [
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
$inv_id = $_POST['inv_id'];
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


try {
    // Fetch Seller Details from GST API using Guzzle
    // $sellerDetailsUrl = "https://gsp.adaequare.com/enriched/ei/api/master/gstin";
    // $sellerResponse = $client->get($sellerDetailsUrl, [
    //     'headers' => [
    //         'Content-Type' => 'application/json',
    //         'user_name' => $user_name,
    //         'password' => $password,
    //         'gstin' => $gstin,
    //         'requestid' => uniqid(),
    //         'Authorization' => 'Bearer ' . $access_token,
    //     ],
    //     'query' => [
    //         'gstin' => $gstin
    //     ],
    // ]);




    // $sellerResponseBody = json_decode($sellerResponse->getBody(), true);

    // if ($sellerResponseBody['success']) {
    //     $SellerDtls = [
    //         'Gstin' => $sellerResponseBody['result']['Gstin'],
    //         'LglNm' => $sellerResponseBody['result']['LegalName'] ?? 'Dummy Legal Name',
    //         'TrdNm' => $sellerResponseBody['result']['TradeName'] ?? 'Dummy Trade Name',
    //         // 'Addr1' => $sellerResponseBody['result']['AddrBnm'] . ' ' . $sellerResponseBody['result']['AddrBno'] ?? 'Dummy Address 1',
    //         'Addr1' => 'Adrees 1',
    //         'Addr2' => $sellerResponseBody['result']['AddrFlno'] ?? 'Dummy Address 2',
    //         'Loc' => $sellerResponseBody['result']['AddrLoc'] ?? 'Dummy Location',
    //         'Pin' => $sellerResponseBody['result']['AddrPncd'] ?? '175121',
    //         'Stcd' => (string)$sellerResponseBody['result']['StateCode'] ?? '02',
    //         'Ph' => '8106517443',
    //         'Em' => 'irctcssy@gmail.com',
    //     ];
    // } else {
    //     die("Seller details not found: " . $sellerResponseBody['message']);
    // }


$branch_id = $_SESSION['branch_id'];
    $query = "SELECT * FROM add_branch WHERE branch_id = ?";
  $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $buyer = $result->fetch_assoc();

        // Buyer Details Array
        $SellerDtls = [
            'Gstin' => $buyer['GST'],
            'LglNm' => $buyer['branch_name'],
            'TrdNm' => $buyer['branch_name'],
            'Pos' => '12', // Assuming 'Pos' is the state code
            'Addr1' => $buyer['address_line1'],
            'Addr2' => $buyer['address_line2'],
            'Loc' => $buyer['city'],
            'Pin' => $buyer['pincode'],
            'Stcd' => $buyer['state_code'],
            'Ph' => $buyer['phone_number'],
            'Em' => $buyer['email']
        ];
    }

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
        'TotAmt' => round($product['line_total'],2),
        
        
        'Discount' => $product['discount'],
        'PreTaxVal' => 0,
        'AssAmt' => round($product['line_total']-$product['discount'],2),
        'GstRt' => $product['gst'],
        'IgstAmt' => $product['igst'],
       // 'CgstAmt' => $product['cgst'],
        //'SgstAmt' => $product['sgst'],
        'CgstAmt' => 0,
        'SgstAmt' =>0,
        'CesRt' => $product['cess_rate'],
        'CesAmt' => $product['cess_amount'],
        'CesNonAdvlAmt' => 0,
        'StateCesRt' => 0,
        'StateCesAmt' => 0,
        'StateCesNonAdvlAmt' => 0,
        'OthChrg' => 0,
        'TotItemVal' => round($product['cgst']+$product['sgst']+$product['igst']+$product['line_total']-$product['discount'],2),
        'OrgCntry' => 'IN'
    ];

    $AssVal = $product['line_total']-$product['discount'];
   // $AssVal += $product['total']; // Assessed value (Pre-Tax value of products)
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
    //"AssVal" => $AssVal,
    "AssVal" => round($AssVal, 2),
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
    $stmt->close();


    $query = "SELECT * FROM customer_master c JOIN address_master a on a.customer_master_id = c.id  WHERE c.id = ?";
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
            'Stcd' => $buyer['state_code'],
            'Ph' => $buyer['mobile'],
            'Em' => $buyer['email']
        ];
    }

$EwbDtls = [
    'Transid' => null, // Transaction ID, can be set to null if not needed
    'Transname' => null, // Transaction name, can be set to null if not needed
    'Distance' => 0, // Set distance to 0 (or the required value)
    'Transdocno' => null, // Transaction document number, set to null if not available
    'TransdocDt' => null, // Transaction document date, set to null if not available
    'Vehno' => 'KA123456', // Vehicle number, make sure it follows the format
    'Vehtype' => 'R', // Vehicle type (e.g., 'R' for Road Transport, if applicable)
    'TransMode' => '1' // Transport mode, '1' could indicate road transport (check your required values)
];

    // Prepare data for IRN Generation
    $data = [
        'Version' => '1.1',
        'TranDtls' => [
            'TaxSch' => 'GST',
            'SupTyp' => 'B2B',
            'RegRev' => 'N',
            'EcmGstin' => null,
            'IgstOnIntra' => 'N',
        ],
        'DocDtls' => [
            'Typ' => 'INV',
            'No' => 'IRN' . uniqid(),
            'Dt' => date('d/m/Y'),
        ],
        'SellerDtls' => $SellerDtls,
        'BuyerDtls' => $BuyerDtls,
        'ItemList' => $ItemList,
        'ValDtls' => $ValDtls,
        'EwbDtls'=>$EwbDtls
    ];

//echo json_encode($data, JSON_PRETTY_PRINT);

 $headers = [
            'Content-Type' => 'application/json',
            'user_name' => $user_name,
            'password' => $password,
            'gstin' => $gstin,
            'requestid' => uniqid(),
            'Authorization' => 'Bearer ' . $access_token,
        ];
        echo "<pre>"; print_r($headers);echo "</pre>";
echo "<pre>"; print_r($data);echo "</pre>";
    // Send IRN Generation Request
   // $irnGenerationUrl = "https://gsp.adaequare.com/enriched/ei/api/invoice";
   $irnGenerationUrl = "https://gsp.adaequare.com/test/enriched/ei/api/invoice";
    $irnResponse = $client->post($irnGenerationUrl, [
        'headers' => [
            'Content-Type' => 'application/json',
            'user_name' => $user_name,
            'password' => $password,
            'gstin' => $gstin,
            'requestid' => uniqid(),
            'Authorization' => 'Bearer ' . $access_token,
        ],
        'json' => $data,
    ]);

    $irnResponseBody = json_decode($irnResponse->getBody(), true);

//echo "Response Body: " . $irnResponse->getBody();

  //  echo "<pre>"; print_r($irnResponseBody);echo "</pre>";


    if (!empty($irnResponseBody['result']['Irn'])) {
        $IRN = $irnResponseBody['result']['Irn'];
        $IRNgenerated_status="Yes";
        // Save IRN details in the database
        $update_sql = "UPDATE invoice SET IRNgenerated_status=?, qr_image = ?,ack_no=?, ack_date=?, irn_no=?, signed_qr_code=?, signed_invoice=?, e_way_bill_no=?, e_way_bill_date=?, e_way_bill_valid_till=? WHERE id=?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param(
            'ssssssssssi',
            $IRNgenerated_status,
            $filePath,
            $irnResponseBody['result']['AckNo'],
            $irnResponseBody['result']['AckDt'],
            $IRN,
            $irnResponseBody['result']['SignedQRCode'],
            $irnResponseBody['result']['SignedInvoice'],
            $irnResponseBody['result']['EwbNo'],
            $irnResponseBody['result']['EwbDt'],
            $irnResponseBody['result']['EwbValidTill'],
            $inv_id
        );
        
        

//$url = 'https://gsp.adaequare.com/enriched/ei/others/qr/image';
$url = 'https://gsp.adaequare.com/test/enriched/ei/others/qr/image';
// Prepare the headers
$headers = [
    'Content-Type' => 'text/plain',
    'user_name' => $user_name,
    'password' => $password,
    'gstin' => $gstin,
    'requestid' => uniqid(),
    'Authorization' => 'Bearer ' . $access_token,
    'width' => '300',
    'height' => '300',
    'imgtype' => 'jpg',
];

// Data payload
$data = $irnResponseBody['result']['SignedQRCode'];

// Create a new Guzzle client instance
$client = new Client();

try {
    // Send the POST request
    $response = $client->post($url, [
        'headers' => $headers,
        'body' => $data // Set the data payload
    ]);
    
    // Get the response body
    $responseBody = $response->getBody();
    
    // Check if the response body is empty
    if (empty($responseBody)) {
        echo 'No image data received';
        return;
    }

    // Save the image data to a file
    $filePath = 'invoice/'.$invoice_code.'.jpg'; // Change the file path/extension as necessary
    file_put_contents($filePath, $responseBody);

    echo "Image saved to $filePath";

} catch (RequestException $e) {
    // Catch errors and display the error message
    echo 'Error: ' . $e->getMessage();
}

        // Assuming the update query was successful
if ($stmt->execute()) {
    // Show success alert
  

    // Now, run a SELECT query to fetch the updated record
    $select_sql = "SELECT * FROM invoice WHERE id = ?";
    $select_stmt = $conn->prepare($select_sql);
    $select_stmt->bind_param('i', $inv_id); // Bind the invoice ID
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    // Check if a row is found
    if ($result->num_rows > 0) {
        // Fetch the row
        $row = $result->fetch_assoc();

         // <th>Signed QR Code</th>
         //            <th>Signed Invoice</th>
         //             <td>" . html In the outcome this too specialchars($row['signed_qr_code']) . "</td>
         //            <td>" . htmlspecialchars($row['signed_invoice']) . "</td>
         // <th>IRN Status</th>
         // <td>" . htmlspecialchars($row['IRNgenerated_status']) . "</td>

        echo "<table border='1'>
                <tr>
                 <th>Invoice ID</th>
                <th>IRN No</th>
                   
                    <th>Acknowledgment No</th>
                    <th>Acknowledgment Date</th>
                    
                 <th>QR Image</th>
                    <th>E-way Bill No</th>
                    <th>E-way Bill Date</th>
                    <th>E-way Bill Valid Till</th>
                </tr>
                <tr>
                    <td>" . htmlspecialchars($row['id']) . "</td> 
                    <td>" . htmlspecialchars($row['irn_no']) . "</td>
                    <td>" . htmlspecialchars($row['ack_no']) . "</td>
                    <td>" . htmlspecialchars($row['ack_date']) . "</td>";
                   ?> 
                 <td>
    <?php 
    echo $row['qr_image'] ? 
        '<img src="' . $row['qr_image'] . '" alt="QR Image" style="width: 100px; height: 100px;">' : 
        'No QR Image'; 
    ?>
</td>

         <?php      
         echo "    <td>" . htmlspecialchars($row['e_way_bill_no']) . "</td>
                    <td>" . htmlspecialchars($row['e_way_bill_date']) . "</td>
                    <td>" . htmlspecialchars($row['e_way_bill_valid_till']) . "</td>
                </tr>
              </table>";

                echo "<script>alert('IRN created successfully')</script>";
    } else {
        echo "No record found with ID: $inv_id";
    }
} 
 

        else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        die("Error generating IRN: " . $irnResponseBody['message']);
    }
} catch (RequestException $e) {
    // Handle Guzzle exceptions
    if ($e->hasResponse()) {
        
        $errorBody = $e->getResponse()->getBody()->getContents();
        echo "API Error: " . $errorBody;
    } else {
        echo "Error: " . $e->getMessage();
    }
}

?>
