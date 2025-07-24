<?php
// Start the session and check if the user is logged in
if (!isset($_SESSION['LOG_IN'])) {
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if (!isset($_SESSION['business_id'])) {
    header("Location:dashboard.php");
    exit();
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
    }
}

include("config.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to fetch voucher details
function getVoucherDetails($conn, $voucherId) {
    if (empty($voucherId)) {
        echo "No voucher ID provided.";
        return false;
    }

    $voucherId = $conn->real_escape_string($voucherId);
    $query = "SELECT r.*, c.*, a.* FROM voucher r
              JOIN customer_master c ON r.customer_id = c.id
              JOIN address_master a ON a.customer_master_id = c.id
              WHERE r.id = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Failed to prepare statement: " . $conn->error;
        return false;
    }

    $stmt->bind_param("s", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        echo "Voucher not found.";
        return false;
    }
}

// Retrieve voucher_id from the URL
$voucher_id = isset($_GET['voucherId']) ? intval($_GET['voucherId']) : null;

if (!$voucher_id) {
    die("Error: Voucher ID is required.");
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voucher_date = isset($_POST['voucher_date']) ? trim($_POST['voucher_date']) : null;
    $payment_mode = isset($_POST['payment_mode']) ? trim($_POST['payment_mode']) : null;
    $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : null;
    $paid_amount = isset($_POST['paid_amount']) ? floatval($_POST['paid_amount']) : null;
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : null;
    $bank_name = isset($_POST['bank_name']) ? trim($_POST['bank_name']) : null;

    $transaction_date = isset($_POST['transaction_date']) ? trim($_POST['transaction_date']) : null;

    $transaction_proof_path = null;

    // Handle file upload if provided
    if (isset($_FILES['transaction_proof']) && $_FILES['transaction_proof']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = basename($_FILES['transaction_proof']['name']);
        $file_tmp_path = $_FILES['transaction_proof']['tmp_name'];
        $file_destination = $upload_dir . uniqid() . '_' . $file_name;

        if (move_uploaded_file($file_tmp_path, $file_destination)) {
            $transaction_proof_path = $file_destination;
        } else {
            die("Error: Unable to upload the file.");
        }
    }

    // Validate required fields
    if (!$voucher_date || !$payment_mode || !$customer_id || !$paid_amount) {
        die("Error: All required fields must be filled.");
    }

    // Build SQL Update Query
    $updateQuery = "
        UPDATE voucher 
        SET 
            voucher_date = ?, 
            payment_mode = ?, 
            customer_id = ?, 
            paid_amount = ?, 
            notes = ?, 
            bank_name = ?, 
            transaction_date = ?";

    // Add transaction proof to the query if uploaded
    if ($transaction_proof_path) {
        $updateQuery .= ", trans_proof_doc = ?";
    }

    $updateQuery .= " WHERE id = ?";

    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        die("Error preparing update statement: " . $conn->error);
    }

    // Bind parameters
    if ($transaction_proof_path) {
        $stmt->bind_param(
            "ssidssssi",
            $voucher_date,
            $payment_mode,
            $customer_id,
            $paid_amount,
            $notes,
            $bank_name,
            $transaction_date,
            $transaction_proof_path,
            $voucher_id
        );
    } else {
        $stmt->bind_param(
            "ssidsssi",
            $voucher_date,
            $payment_mode,
            $customer_id,
            $paid_amount,
            $notes,
            $bank_name,
            $transaction_date,
            $voucher_id
        );
    }

    // Execute the query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Voucher updated successfully!";
        } else {
            echo "No changes were made to the voucher.";
        }
    } else {
        echo "Error updating voucher: " . $stmt->error;
    }

    $stmt->close();
}
?>




<style type="text/css">
    .select-with-scroll {
    max-height: 150px;
    overflow-y: auto;
    width: 100%; /* Optional: To ensure it takes the full container width */
}
#reconcile_tab{
    display: none;
}

</style>
    
   <div id="editVoucherModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="col-md-8 modal-title">Edit Voucher</h4>
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
    <form action=""  id="addVoucherForm" method="POST" enctype="multipart/form-data" >
        <!-- <input type="hidden" name="contact_type" id="contact_type" value="Customer"> -->
             <input type="hidden" name="voucherId" value="<?= htmlspecialchars($_GET['voucherId'] ?? '') ?>">

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
<input type="text" name="business_state" id="business_state" value="<?php echo htmlspecialchars($user['state']); ?>" hidden>

                                     <h5 class="line-height-70"><b id="seller_name" style=" color: blue;"><?php echo htmlspecialchars($user['name']); ?></b></h5>
                        <h5 id="seller_add_1" class="line-height-70"><?php echo htmlspecialchars($user['address']); ?></h5>
                        <h5 id="seller_add_2" class="line-height-70"></h5>
                        <h5 id="seller_add_3" class="line-height-70">GST : <?php echo htmlspecialchars($user['gstin']); ?></h5>
                        <h5 id="seller_email" class="line-height-70"> Email: <?php echo htmlspecialchars($user['email']); ?> </h5>
                        <h5 id="seller_mobile" class="line-height-70">Phone: <?php echo htmlspecialchars($user['phone']); ?> </h5>
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

// Get the voucherId from the URL parameter
$voucherId = isset($_GET['voucherId']) ? $_GET['voucherId'] : 0;

// Ensure voucherId is valid (if not, show an error or handle accordingly)
if ($voucherId > 0) {
    // Query to fetch the details for the given voucherId
    $query = "SELECT id FROM voucher WHERE id = $voucherId";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_array($result)) {
        $voucher_id = $row['id']; // Fetch the voucher id from the result
        $invoice_code = "VCHR0" . $voucher_id; // Generate the invoice code based on the fetched voucher id
    } else {
        echo "voucher not found."; // Handle the case where no matching voucher is found
        exit();
    }
} else {
    echo "Invalid voucher ID.";
    exit();
}
?>

  <!-- Display the invoice code (voucher number) based on the existing voucher ID -->
  <input 
        style="color:black!important;font-weight:bold;" 
        type="text" 
        class="form-control m-input rec_no" 
        placeholder="voucher No" 
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
// Assuming you have already fetched $receiptDetails as per your previous code
$voucherDate = isset($receiptDetails['voucher_date']) ? $receiptDetails['voucher_date'] : '';
?>

<input style="color:black !important;font-weight:bold;" 
       type="date" 
       class="form-control m-input rec_date" 
       placeholder="voucher Date" 
       id="voucher_date" 
       name="voucher_date" 
       value="<?php echo $voucherDate ? date('Y-m-d', strtotime($voucherDate)) : ''; ?>" 
       required>

                                    <!-- <input style="color:black !important;font-weight:bold;" type="date" class="form-control m-input rec_date " placeholder="voucher Date" id="voucher_date" name="voucher_date" required> -->
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
// Example: assuming $receiptDetails array is available with 'payment_mode' key
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
    // Get the voucherId from the URL parameter
    $voucherId = isset($_GET['voucherId']) ? $_GET['voucherId'] : 0;

    // Query to fetch the voucher details along with customer details
    $query = "
        SELECT r.*, c.customerName, c.email
        FROM voucher r
        JOIN customer_master c ON r.customer_id = c.id
        WHERE r.id = $voucherId
    ";
    $result = mysqli_query($conn, $query);

    // Check if voucher details were fetched successfully
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the details of the voucher
        $quotationDetails = mysqli_fetch_assoc($result);
    } else {
        // Handle the case where the voucher is not found (optional)
        $quotationDetails = null;
        echo "voucher not found.";
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
                // Check if this customer was already selected in the voucher
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
       placeholder="Amount" value="<?php echo isset($receiptDetails['paid_amount']) ? $receiptDetails['paid_amount'] : ''; ?>">
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
    // Get the voucherId from the URL parameter
    $voucherId = isset($_GET['voucherId']) ? $_GET['voucherId'] : 0;

    // Query to fetch the voucher details along with customer details and notes
    $query = "
        SELECT r.*, c.customerName, c.email, r.notes
        FROM voucher r
        JOIN customer_master c ON r.customer_id = c.id
        WHERE r.id = $voucherId
    ";
    $result = mysqli_query($conn, $query);

    // Check if voucher details were fetched successfully
    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the details of the voucher
        $quotationDetails = mysqli_fetch_assoc($result);
    } else {
        // Handle the case where the voucher is not found (optional)
        $quotationDetails = null;
        echo "voucher not found.";
    }
    ?>
    <!-- Textarea to display notes -->
    <textarea class="form-control" id="notes" name="notes" placeholder="Note" aria-invalid="false" style="margin: 0px; height: 100%;" maxlength="990" rows="5"><?php echo isset($quotationDetails['notes']) ? $quotationDetails['notes'] : ''; ?></textarea>
</div>

        <div class=" col-md-5" style="border-top: 0.1rem solid #ada7a7;border-left:  0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;padding: 0px;">
            <!-- <h6 class="p-2" style="color:black;display: block;">For <span id="seller_names">KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</span></h6> -->
             <h6 class="pl-5 pt-2" style="float:right;font-size:11px;color:black;display: block;font-weight: 600;margin-left: 20px;">For KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</h6>
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

<div class="col-6" id="bank_name_tab">
    <div class="form-group">
        <h6 style="font-weight:400;">Bank Name <span id="" style="color:red;display:none;"></span></h6>
        <input 
            type="text" 
            class="form-control m-input form-control-sm" 
            id="bank_name" 
            name="bank_name" 
            placeholder="Bank Name" 
            value="<?php echo isset($quotationDetails['bank_name']) ? $quotationDetails['bank_name'] : ''; ?>">
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
        <input  type="date" class="form-control m-input form-control-sm" id="transaction_date" name="transaction_date" placeholder="Transaction Date" value="<?php echo isset($receiptDetails['transaction_date']) ? $receiptDetails['transaction_date'] : ''; ?>">
    </div>
</div>

<div class="col-6" id="transaction_date_tab">
        <div class="form-group">
            <h6 style="font-weight:400;">Transaction Proof Document</h6>
            <input type="file" class="form-control m-input form-control-sm" id="transaction_proof" name="transaction_proof">
        </div>
    </div>


</div>
              </div>
                </div>
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


