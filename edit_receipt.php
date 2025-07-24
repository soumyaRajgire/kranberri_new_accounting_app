<?php
// Start the session and check if the user is logged in
// session_start();
if(!isset($_SESSION['LOG_IN'])){
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if(!isset($_SESSION['business_id'])){
    header("Location:dashboard.php");
    exit();
} else {
    // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    
    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
        // Branch-specific code or logic here
    }
}


include("config.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

function getReceiptDetails($conn, $receiptId) {
    if (empty($receiptId)) {
        echo "No receipt ID provided.";
        return false;
    }

    $receiptId = $conn->real_escape_string($receiptId); // Sanitize input

    // Your database query logic here to fetch data from the 'receipts' table
    $query = "SELECT r.*, c.*, a.* FROM receipts r
              JOIN customer_master c ON r.customer_id = c.id
              JOIN address_master a ON a.customer_master_id = c.id
              WHERE r.id = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Failed to prepare statement: " . $conn->error;
        return false;
    }

    $stmt->bind_param("s", $receiptId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        echo "Receipt not found or query failed.";
        return false; // Receipt not found
    }
}

// Retrieve receipt_id from the URL
$receipt_id = isset($_GET['receiptId']) ? intval($_GET['receiptId']) : null;


if (!$receipt_id) {
    die("Error: Receipt ID is required.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize POST data
    $receipt_date = isset($_POST['receipt_date']) ? trim($_POST['receipt_date']) : null;
      $GLOBALS['invoice_id'] = isset($_POST['invoice_id']) ? trim($_POST['invoice_id']) : null;
       echo $GLOBALS['invoice_id'];
                     
    
    $payment_mode = isset($_POST['payment_mode']) ? trim($_POST['payment_mode']) : null;
    $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : null;
    $paid_amount = isset($_POST['paid_amount']) ? floatval($_POST['paid_amount']) : null;
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : null;
    $bank_name = isset($_POST['bank_name']) ? trim($_POST['bank_name']) : null;
    $transaction_date = isset($_POST['transaction_date']) ? trim($_POST['transaction_date']) : null;
    $filename = isset($_POST['filename']) ? trim($_POST['filename']) : null;
    
//     $form_data = "
//     Receipt Date: $receipt_date\n
//     Payment Mode: $payment_mode\n
//     Customer ID: $customer_id\n
//     Paid Amount: $paid_amount\n
//     Notes: $notes\n
//     Bank Name: $bank_name\n
//     Transaction Date: $transaction_date\n
//     Filename: $filename
// ";
// echo "<script>";
// echo "alert(" . json_encode("Form data received:\n$form_data") . ");"; // Properly escapes data
// echo "</script>";


    // Basic validation for required fields
    if (!$receipt_date || !$payment_mode || !$customer_id || !$paid_amount) {
        die("Error: All required fields must be filled.");
    }

    // Validate that the receipt exists
    $checkQuery = "SELECT id FROM receipts WHERE id = ?";
    $stmt = $conn->prepare($checkQuery);
    if (!$stmt) {
        die("Error preparing check query: " . $conn->error);
    }

    $stmt->bind_param("i", $receipt_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        die("Error: Receipt ID not found in the database.");
    }
    $stmt->close();

   
    $updateQuery = "
        UPDATE receipts 
        SET 
            receipt_date = ?, 
            payment_mode = ?, 
            customer_id = ?, 
            paid_amount = ?, 
            notes = ?, 
            bank_name = ?, 
            transaction_date = ?
        WHERE 
            id = ?
    ";

    
    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        die("Error preparing updating statement: " . $conn->error);
    }
   

    // Bind parameters
    $stmt->bind_param(
        "ssidsssi",
        $receipt_date,
        $payment_mode,
        $customer_id,
        $paid_amount,
        $notes,
        $bank_name,
        $transaction_date,
        $receipt_id
    );

    // Execute the query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
           // echo "Receipt updated successfully!";
            // Fetch invoice data
            $sql = "SELECT `grand_total`, `due_amount`, `status` FROM `invoice` WHERE id = " . $GLOBALS['invoice_id'];

            echo $sql;
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                // Get invoice data
                $invoiceData = $result->fetch_assoc();
                
                $grand_total = $invoiceData['grand_total'];
                $current_due_amount = $invoiceData['due_amount'];
                
                // Calculate the new due amount
                $new_due_amount = $grand_total - $paid_amount;
                
                
                if ($new_due_amount == 0) {
                    // If the new due amount is 0, it's fully paid
                    $status = 'paid';
                } elseif ($new_due_amount > 0 && $new_due_amount < $current_due_amount) {
                    // If there's some due amount remaining, it's partially paid
                    $status = 'partial';
                } else {
                    // If the due amount is greater than 0 and not matching, it's pending
                    $status = 'pending';
                }
                
                // Update the invoice with the new due amount and status
                $update_sql = "UPDATE `invoice` SET `due_amount` = '$new_due_amount', `status` = '$status' WHERE id = ". $GLOBALS['invoice_id'];
                
                if ($conn->query($update_sql)) {
                    echo "<script>alert('Receipt updated successfully');</script>";
                } else {
                    echo "<script>alert('Failed to update Receipt');</script>";
                }
            } else {
                echo "<script>alert('Invoice not found');</script>";
            }
                   

        $stmt_ledger = $conn->prepare("UPDATE ledger SET transaction_date = ?, transaction_type = 'Receipt', account_id = ?, account_name = ?, amount = ?, debit_credit = 'C', receipt_or_voucher_no = ?, branch_id = ? WHERE voucher_id = ?");
            $stmt_ledger->bind_param("sssdssi", $transaction_date, $customer_id, $receiptDetails['customerName'], $paid_amount, $receiptDetails['recpt_id'], $branch_id, $receipt_id);

            if (!$stmt_ledger->execute()) {
                echo "<script>alert('FAILED to update ledger for receipt!');</script>";
                throw new Exception("Failed to update ledger entry: " . $stmt_ledger->error);
            } else {
               // echo "<script>alert('Ledger updated for receipt entry saved successfully!');</script>";
            }




             $cst_mstr_id = isset($cst_mstr_id) ? $cst_mstr_id : $customer_id;
                  $purchaseDate = isset($purchaseDate) && !empty($purchaseDate) ? $purchaseDate : 
                (isset($transaction_date) && !empty($transaction_date) ? $transaction_date : 
                (isset($receipt_date) && !empty($receipt_date) ? $receipt_date : date("Y-m-d")));

    

                        
                    include 'whatsapp_communication2.php';
                            
                            $customer_result = mysqli_query($conn, "SELECT * FROM customer_master WHERE id = $cst_mstr_id");
                            
                            if (!$customer_result) {
                               echo "<script>
                                    alert('Query failed: ');
                                </script>";  
                            }
                            
                             $company_name = "Civil Core Projects";
                            
                            $pdfType = "Receipt";
                            $destinationURL="view-receipt-action.php?receiptId={$receipt_id}";
                           
                            
                            $customerRow = mysqli_fetch_assoc($customer_result);
                            if ($customerRow) {
                            
                                $mobile_number = $customerRow['mobile'];
                               $customer_name = isset($customer_name) ? $customer_name : $customerRow['customerName'];

                              
                                //echo "<script>         alert('Customer query executed successfully and no of rows are ' + " . mysqli_num_rows($customer_result) . ");     </script>";
                            
                                
                                $api_url = "https://iiiqbets.pythonanywhere.com/api/single-message-with-multiple-variable/";
                                
                                
                                
                              // Define the payload
                                $payload = [
                                    "mobile_number" => $mobile_number,
                                    "template_name" => "varuable_5",
                                    "template_variable" => [
                                        $customer_name,
                                        $company_name,
                                         $pdfType,
                                        $purchaseDate,
                                        "https://paleturquoise-jellyfish-674855.hostingersite.com/gimbook4/" . $filename
                                    ]
                                ];
                                $payload_json = json_encode($payload);
                                                            
                            //                                 echo "<script>";
                            // echo "console.log('Payload:', JSON.parse('$payload_json'));";
                            // echo "</script>";


                           //// echo "alert('Payload: ' + JSON.stringify($payload_json, null, 2));";  // JSON.stringify for better formatting
                            echo "<script>";
echo "alert('" . $pdfType . " created successfully');";
echo "window.location.href = 'view-receipt-action.php?receiptId=" . $_GET['receiptId'] . "';";
echo "</script>";

                                // Initialize cURL
                                $ch = curl_init($api_url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Content-Type: application/json'
                                ]);
                            
                                // Execute the cURL request and capture the response
                                $response = curl_exec($ch);
                            
                                // Handle errors
                               if (curl_errno($ch)) {
                                echo "<script>alert('cURL Error: " . curl_error($ch) . "');</script>";
                            } else {
                                echo "<script>
                                alert('" . $pdfType . " created successfully, Whatsapp API Response: " . addslashes($response) . "');
                                window.location = '" . $destinationURL . "'; 
                            </script>";
                            
                                 //echo "<script>alert('Invoice created successfully'); window.location = 'view-invoices.php';</script>";
                            }
                      
                            }   
            
            
        } else {
            echo "No changes were made to the receipt.";
        }
    } else {
        echo "Error updating receipt: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Fetch existing data for the given receipt_id
    $fetchQuery = "SELECT * FROM receipts WHERE id = ?";
    $stmt = $conn->prepare($fetchQuery);
    if (!$stmt) {
        die("Error preparing fetch statement: " . $conn->error);
    }

    $stmt->bind_param("i", $receipt_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Prepopulate the form with existing data
        $receipt_date = $row['receipt_date'];
        $payment_mode = $row['payment_mode'];
        $customer_id = $row['customer_id'];
        $paid_amount = $row['paid_amount'];
        $notes = $row['notes'];
        $bank_name = $row['bank_name'];
        $transaction_date = $row['transaction_date'];
        $filename=$row['pdf_file_path'];
    } else {
        die("Error: Receipt ID not found in the database.");
    }

    $stmt->close();
}


?>



<style type="text/css">
    .select-with-scroll {
    max-height: 150px;
/*    overflow-y: auto;*/
    width: 100%; /* Optional: To ensure it takes the full container width */
}
#reconcile_tab{
    display: none;
}

</style>
    
   <div id="editReceiptModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="col-md-8 modal-title">Edit Receipt</h4>
                <div class="col-md-3 btn-group btn-group-sm btn_filter pull-right tab_shift" role="group" aria-label="Large button group">
                    <!--  <li class="nav-item" style="margin-left: 160px;">
                                <div class="btn-group btn-group-sm btn_filter">
                                    <button type="button" class="btn btn-outline-primary add_cust_filter create_tab active">Create</button>
                                    <button type="button" class="btn btn-outline-primary add_cust_filter reconcile_tab">Reconcile</button>
                                </div>
                           
                            </li> -->
                 <button type="button" class="btn btn-outline-primary add_cust_filter create_tab active" >Update</button>
                    <button type="button" class="btn btn-outline-primary add_cust_filter reconcile_tab" >Reconcile</button> 
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
    <form action=""  id="addreceiptForm" method="POST" enctype="multipart/form-data" >
        <input type="hidden" name="filename" id="filename" value="<?php echo $filename; ?>" >
        <!-- <input type="hidden" name="contact_type" id="contact_type" value="Customer"> -->
             <input type="hidden" name="receiptId" value="<?= htmlspecialchars($_GET['receiptId'] ?? '') ?>">

    <div class="modal-body">
       <div class="tabs">
            <div class="col-md-12" id="create_tab">
              <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
                <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                  <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                    <div class="row" style="margin-right: 0px; margin-left: 0px;">
                        <div class=" col-md-7" style="border-right: 0.1rem solid #ada7a7;">
                            <div class="-icon" style="margin-top:10px;  margin-bottom:10px;">
                                <div class="business_details">


                                     <?php include 'fetch_user_data.php'; ?>
<!-- <input type="text" name="business_state" id="business_state" value="<?php echo htmlspecialchars($user['state']); ?>" hidden>

                                     <h5 class="line-height-70"><b id="seller_name" style=" color: blue;"><?php echo htmlspecialchars($user['name']); ?></b></h5>
                        <h5 id="seller_add_1" class="line-height-70"><?php echo htmlspecialchars($user['address']); ?></h5>
                        <h5 id="seller_add_2" class="line-height-70"></h5>
                        <h5 id="seller_add_3" class="line-height-70">GST : <?php echo htmlspecialchars($user['gstin']); ?></h5>
                        <h5 id="seller_email" class="line-height-70"> Email: <?php echo htmlspecialchars($user['email']); ?> </h5>
                        <h5 id="seller_mobile" class="line-height-70">Phone: <?php echo htmlspecialchars($user['phone']); ?> </h5>
 -->
                        <input type="text" name="business_state" id="business_state" value="<?php echo htmlspecialchars($user['state']); ?>" hidden>

                                     <h5 class="line-height-70"><b id="seller_name" style=" color: blue;"><?php echo htmlspecialchars($user['branch_name']); ?></b></h5>
                        <h5 id="seller_add_1" class="line-height-70"><?php echo htmlspecialchars($user['address_line1']); ?></h5>
                        <h5 id="seller_add_2" class="line-height-70"><?php echo htmlspecialchars($user['address_line2']); ?></h5>
                        <h5 id="seller_add_3" class="line-height-70">GST : <?php echo htmlspecialchars($user['GST']); ?></h5>
                        <h5 id="seller_email" class="line-height-70"> Email: <?php echo htmlspecialchars($user['email']); ?> </h5>
                        <h5 id="seller_mobile" class="line-height-70">Phone: <?php echo htmlspecialchars($user['phone_number']); ?> </h5>
                            </div>
                            </div>
                          
                        </div>
                        <div class=" col-md-5">
                          <div class="" style="padding-top: 12px;">
                            <div class="kt-input-icon kt-input-icon--right">
                              <span>
                                <div class="m-input-icon m-input-icon--right">
  <?php
// Connect to the database

// Get the receiptId from the URL parameter
$receiptId = isset($_GET['receiptId']) ? $_GET['receiptId'] : 0;

// Ensure receiptId is valid (if not, show an error or handle accordingly)
if ($receiptId > 0) {
    // Query to fetch the details for the given receiptId
    $query = "SELECT id FROM receipts WHERE id = $receiptId";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_array($result)) {
        $receipt_id = $row['id']; // Fetch the receipt id from the result
        $invoice_code = "RECT0" . $receipt_id; // Generate the invoice code based on the fetched receipt id
    } else {
        echo "Receipt not found."; // Handle the case where no matching receipt is found
        exit();
    }
} else {
    echo "Invalid receipt ID.";
    exit();
}
?>

  <!-- Display the invoice code (receipt number) based on the existing receipt ID -->
  <input 
        style="color:black!important;font-weight:bold;" 
        type="text" 
        class="form-control m-input rec_no" 
        placeholder="Receipt No" 
        name="rec_no" 
        value="<?php echo $invoice_code; ?>" 
        readonly
    >


  <span class="m-input-icon_icon m-input-icon_icon--right">
    <span>
      <i class="la la-file"></i>
    </span>
  </span>
</div>

                              </span>
                            </div>
                          </div>
                          <div style="padding-top: 12px;" class="">
                            <div class="">
                              <span>
                                <div class="date">
                                  <div class="m-input-icon m-input-icon--right">
                                    <?php

$receiptDate = isset($receiptDetails['receipt_date']) ? $receiptDetails['receipt_date'] : '';
?>

<input style="color:black !important;font-weight:bold;" 
       type="date" 
       class="form-control m-input rec_date" 
       placeholder="Receipt Date" 
       id="receipt_date" 
       name="receipt_date" 
       value="<?php echo $receiptDate ? date('Y-m-d', strtotime($receiptDate)) : ''; ?>" 
       required>

                                    <!-- <input style="color:black !important;font-weight:bold;" type="date" class="form-control m-input rec_date " placeholder="Receipt Date" id="receipt_date" name="receipt_date" required> -->
                                    <span class="m-input-icon_icon m-input-icon_icon--right">
                                      <span>
                                        <i class="la la-calendar"></i>
                                      </span>
                                    </span>
                                  </div>
                                </div>
                              </span>
                            </div>
                          </div>
<?php

$paymentMode = isset($receiptDetails['payment_mode']) ? $receiptDetails['payment_mode'] : '';
?>
<div style="padding-top: 12px;">
    <div class="form-group">
        <select class="form-control select2-hidden-accessible" id="payment_mode" name="payment_mode" style="font-size: .875rem; width: 100%;" tabindex="-1" aria-hidden="true">
            <option value="Direct Deposit" <?php echo ($paymentMode == 'Direct Deposit') ? 'selected' : ''; ?>>Direct Deposit</option>
            <option value="NEFT/RTGS" <?php echo ($paymentMode == 'NEFT/RTGS') ? 'selected' : ''; ?>>NEFT/RTGS</option>
            <option value="Online Payment" <?php echo ($paymentMode == 'Online Payment') ? 'selected' : ''; ?>>Online Payment</option>
            <option value="Credit Debit Card" <?php echo ($paymentMode == 'Credit Debit Card') ? 'selected' : ''; ?>>Credit/Debit Card</option>
            <option value="Demand Draft" <?php echo ($paymentMode == 'Demand Draft') ? 'selected' : ''; ?>>Demand Draft</option>
            <option value="Cheque" <?php echo ($paymentMode == 'Cheque') ? 'selected' : ''; ?>>Cheque</option>
            <option value="Cash" <?php echo ($paymentMode == 'Cash') ? 'selected' : ''; ?>>Cash</option>
        </select>
    </div>
</div>


                        </div>
                    </div>
                    <div class="row" style="margin-left: 0px;margin-right: 0px;">
                        <div class=" col-md-12" style="border-top: 0.1rem solid #ada7a7;">
                          <div class="row" style="margin-top:10px">
                            <div class="col-7">
    <?php
    // Get the receiptId from the URL parameter
    $receiptId = isset($_GET['receiptId']) ? $_GET['receiptId'] : 0;

    // Query to fetch the receipt details along with customer details
    $query = "
        SELECT r.*, c.customerName, c.email
        FROM receipts r
        JOIN customer_master c ON r.customer_id = c.id
        WHERE r.id = $receiptId
    ";
    $result = mysqli_query($conn, $query);

    // Check if receipt details were fetched successfully
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the details of the receipt
        $quotationDetails = mysqli_fetch_assoc($result);
         $GLOBALS['invoice_id'] = isset($quotationDetails['invoice_id']) ? trim($quotationDetails['invoice_id']) : null;
       echo $GLOBALS['invoice_id'];
    } else {
        // Handle the case where the receipt is not found (optional)
        $quotationDetails = null;
        echo "Receipt not found.";
    }

    // Query to fetch all customers for the dropdown
    $customerQuery = "SELECT id, customerName FROM customer_master";
    $customerResult = mysqli_query($conn, $customerQuery);
    ?>

    <div class="form-group">
        <h6 style="font-weight:400;">Customer</h6>
        <!-- Dropdown to select customer -->
        <select class="form-control" name="customer_id" id="customer_id">
            <option value="">Select a Customer</option>
            <?php
            // Loop through all customers and populate the dropdown
            while ($customer = mysqli_fetch_assoc($customerResult)) {
                // Check if this customer was already selected in the receipt
                $selected = ($quotationDetails && $quotationDetails['customer_id'] == $customer['id']) ? 'selected' : '';
                echo "<option value='" . $customer['id'] . "' $selected>" . $customer['customerName'] . "</option>";
            }
            ?>
        </select>
    </div>



                            </div>

                            
                            <div class="col-5">
                              <div class="form-group">
                                <h6 style="font-weight:400;">Amount  </h6>
                                <div class="input-group input-group-sm">
                                  <div class="kt-input-icon kt-input-icon--right" style="width:30%">
                                    <select class="form-control form-control-sm m-select2 m-select2-general currency_list select2-hidden-accessible" style="opacity:1;width:100%" name="currency_list" id="currency_list" data-select2-id="currency_list" tabindex="-1" aria-hidden="true"> <?php include("currency-dropdown.php");?></select>
                                  </div>
                                  <div class="input-group-append" style="width:70%">
<input type="number" min="0" step="0.01" id="paid_amount" name="paid_amount" class="form-control total_amt"
       placeholder="Amount" value="<?php echo isset($receiptDetails['paid_amount']) ? number_format($receiptDetails['paid_amount'], 2, '.', '') : ''; ?>">
       <input type="text"  id="invoice_id" name="invoice_id" class="form-control"
        value="<?php echo isset($receiptDetails['invoice_id']) ? $receiptDetails['invoice_id'] : ''; ?>">

                                  </div>
                                </div>      
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
    <div class="row" style="margin-left: 0px;margin-right: 0px;margin-bottom: 10px;">
        <div class="col-md-7" style="padding: 0px; border-top: 0.1rem solid #ada7a7; border-bottom: 0.1rem solid #ada7a7;">
    <?php
    // Get the receiptId from the URL parameter
    $receiptId = isset($_GET['receiptId']) ? $_GET['receiptId'] : 0;

    // Query to fetch the receipt details along with customer details and notes
    $query = "
        SELECT r.*, c.customerName, c.email, r.notes
        FROM receipts r
        JOIN customer_master c ON r.customer_id = c.id
        WHERE r.id = $receiptId
    ";
    $result = mysqli_query($conn, $query);

    // Check if receipt details were fetched successfully
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the details of the receipt
        $quotationDetails = mysqli_fetch_assoc($result);
    } else {
        // Handle the case where the receipt is not found (optional)
        $quotationDetails = null;
        echo "Receipt not found.";
    }
    ?>
    <!-- Textarea to display notes -->
    <textarea class="form-control" id="notes" name="notes" placeholder="Note" aria-invalid="false" style="margin: 0px; height: 100%;" maxlength="990" rows="5"><?php echo isset($quotationDetails['notes']) ? $quotationDetails['notes'] : ''; ?></textarea>
</div>

        <div class=" col-md-5" style="border-top: 0.1rem solid #ada7a7;border-left:  0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;padding: 0px;">
            <!-- <h6 class="p-2" style="color:black;display: block;">For <span id="seller_names">KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</span></h6> -->
             <h6 class="pl-5 pt-2" style="float:right;font-size:11px;color:black;display: block;font-weight: 600;margin-left: 20px;"></h6>
            <h6 class="pl-2" style="float:right;font-weight:600;padding-top: 75px; color:black;font-size:13px;display: block;">Authorised Signatory</h6>
        </div>
    </div>

<div class="row" style="padding:10px;">
   

<div class="col-6" id="collected_by_tab" style="display: none;">
    <div class="form-group">
        <h6 style="font-weight:400;">Collected BY <span id="" style="color:red;display:none;"></span></h6>
        <input  type="text" class="form-control m-input form-control-sm" id="collected_by" placeholder="Collected BY">
    </div>
</div>

<div class="col-6" id="bank_name_tab" >
    <div class="form-group">
        <h6 style="font-weight:400;">Bank Name <span id="" style="color:red;display:none;"></span></h6>
        <input type="text" class="form-control m-input form-control-sm" id="bank_name" name="bank_name" placeholder="Bank Name" value="<?php echo isset($quotationDetails['bank_name']) ? $quotationDetails['bank_name'] : ''; ?>">
    </div>
</div>

<div class="col-6" id="trans_no_tab" style="display: none;">
    <div class="form-group">
        <h6 style="font-weight:400;">Transaction No  <span id="remind" style="color:red;display:none;"></span></h6>
        <input type="text" class="form-control m-input form-control-sm" id="trans_no" placeholder="Transaction No">
    </div>
</div>

<div class="col-6" id="cheque_no_tab" style="display: none;">
    <div class="form-group">
        <h6 style="font-weight:400;">Cheque No  <span id="" style="color:red;display:none;"></span></h6>
        <input  type="text" class="form-control m-input form-control-sm" id="cheque_no" placeholder="Cheque No">
    </div>
</div>
<div class="col-6" id="dd_no_tab" style="display: none;">
    <div class="form-group">
        <h6 style="font-weight:400;">Demand Draft No<span id="" style="color:red;display:none;"></span></h6>
        <input type="text" class="form-control m-input form-control-sm" id="dd_no" placeholder="DD No">
    </div>
</div>
<div class="col-6" id="credit_debit_card_tab" style="display: none;">
    <div class="form-group">
        <h6 style="font-weight:400;">Card last 4 digit No<span id="" style="color:red;display:none;"></span></h6>
        <input  type="text" class="form-control m-input form-control-sm" id="card_last_no" placeholder="Card last 4 digit No"><label ></label>
    </div>
</div>
<div class="col-6" id="transaction_date_tab">
    <div class="form-group">
        <h6 style="font-weight:400;">Trasaction Date<span id="" style="color:red;display:none;"></span></h6>
        <input  type="date" class="form-control m-input form-control-sm" id="transaction_date" name="transaction_date" placeholder="Transaction Date" value="<?php echo isset($quotationDetails['transaction_date']) ? $quotationDetails['transaction_date'] : ''; ?>">
    </div>
</div>
<div class="col-6" id="transaction_date_tab">
    <div class="form-group">
        <h6 style="font-weight:400;">Trasaction Proof Document<span id="" style="color:red;display:none;"></span></h6>
        <input  type="file" class="form-control m-input form-control-sm" id="transaction_proof" name="transaction_proof" placeholder="Transaction Proof Document">
    </div>
</div>


</div>
              </div>
                </div>
              </div>

            </div>
    <div class="col-md-12" id="reconcile_tab" style="display:none;">
        <div class="container">
            <?php
$query = "SELECT * FROM receipts WHERE invoice_id = '$inv_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Fetch all rows from the result set
    $reconcileData = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <script>
        var reconcileData = <?php echo json_encode($reconcileData); ?>;
    </script>
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px; border: 0.1rem solid #ada7a7;">
                <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                    <div class="row">
                        <div class="col-12" id="reconcile_table" style="padding: 0px;">
                            <div class="table-responsive">
                                <table class="table table-bordered newtable text-center" style="font-size: smaller; margin: 0px;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Date</th>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Invoice</th>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Receipt</th>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Total</th>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Paid</th>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Unpaid</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody id="total_inv">
                                <?php 
                                    $tpa1=0;
                                        foreach ($reconcileData as $row): ?>
                                <?php

                                        $tpa1 += $row['paid_amount'];
                                   ?>
                                   <tr>
                                       <td><?php echo $row['receipt_date']?></td>
                                       <td><?php echo $quotationDetails['invoice_code'];?></td>
                                       <td><?php echo $row['recpt_id']?></td>
                                       <td><?php echo $row['total_amount']?></td>
                                       <td><?php echo $row['paid_amount']?></td>
                                       <td><?php echo $row['due_amount']?></td>
                                       
                                   </tr>
                                      <?php endforeach; ?>
                                           
                                    </tbody>
                                </table>
                            </div>
                            <table class="table table-bordered newtable text-center col-md-7 mt-3" style="float:right;">
                                 <tr>
                                     <th style="background-color: #ededed;">Total</th>
                                    <td><?php echo ($reconcileData[0]['total_amount'] > 0) ? 'INR ' . number_format($row['total_amount'], 2) : ''; ?></td>
                                </tr>
                                <tr>
                                    <th style="background-color: #ededed;">Paid Amount</th>            
                                    <td><?php echo ($tpa1 > 0) ? 'INR ' . number_format($tpa1, 2) : ''; ?></td> 
                                </tr>
                                <tr>
                                    <th style="background-color: #ededed;">Due Amount</th>             
                                    <td><?php echo ((($reconcileData[0]['total_amount']) - $tpa1) > 0) ? 'INR ' . number_format((($reconcileData[0]['total_amount']) - $tpa1), 2) : ''; ?></td> 
                                </tr>
                                <tr>
                                    <th style="background-color: #ededed;">Payment Status</th>
                                    <td><?php 
                                                
                        if($quotationDetails['status'] == "pending")
                        {
                            ?>
                            <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid red;color:red;font-weight:bold;">Not Paid</span>
                            <?php
                        }else if($quotationDetails['status'] == "partial")
                        {
                            ?>
                            <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid #3498db;color:#3498db;font-weight:bold;">Part Payment</span>
                            <?php
                        }else if($quotationDetails['status'] == "paid")
                        {
                            ?>
                            <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid green;color:green;font-weight:bold;">Fully Paid</span>
                            <?php
                        }      
                        ?></td>
                                </tr>
                                        
                            </table>
                        </div>
                        <div class="col-12 text-center" style="padding-right: 0px;" id="receipt_balance_tab">
                            <span id="receipt_balance"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    echo "No reconcile data found for the given invoice ID.";
}
?>

  
</div>

        </div>
   
</div>

        </div>

       <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="" class="btn btn-primary">Update</button>
        </div> 
    </form>
        </div>
    </div>
</div>
       
<!-- Add this script before the closing </body> tag -->

<script src="assets/js/myscript.js"></script> 
<script>
    $(document).ready(function() {
        // Function to show relevant fields based on the selected payment mode
        function showTab(selectedOption) {
            $('#collected_by_tab, #bank_name_tab, #trans_no_tab, #cheque_no_tab, #dd_no_tab, #credit_debit_card_tab, #transaction_date_tab, #transaction_proof_tab').hide();

            switch (selectedOption) {
                case 'Cash':
                    $('#collected_by_tab, #transaction_date_tab').show();
                    break;
                case 'Cheque':
                    $('#bank_name_tab, #cheque_no_tab, #transaction_date_tab').show();
                    break;
                case 'Direct Deposit':
                case 'NEFT/RTGS':
                    $('#bank_name_tab, #transaction_date_tab').show();
                    break;
                case 'Demand Draft':
                    $('#bank_name_tab, #dd_no_tab, #transaction_date_tab').show();
                    break;
                case 'Credit Debit Card':
                    $('#credit_debit_card_tab, #transaction_date_tab').show();
                    break;
                case 'Online Payment':
                    $('#trans_no_tab, #transaction_date_tab').show();
                    break;
            }
        }

        // Initialize by showing the appropriate tab
        var selectedOption = $('#payment_mode').val();
        showTab(selectedOption);

        // Change event for dropdown
        $('#payment_mode').change(function() {
            showTab($(this).val());
        });
    });
</script>


