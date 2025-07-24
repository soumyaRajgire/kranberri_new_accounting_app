
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
<div id="newReceiptsModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="col-md-8 modal-title">Create Receipt</h4>
                <div class="col-md-3 btn-group btn-group-sm btn_filter pull-right tab_shift" role="group" aria-label="Large button group">
                    <!--  <li class="nav-item" style="margin-left: 160px;">
                                <div class="btn-group btn-group-sm btn_filter">
                                    <button type="button" class="btn btn-outline-primary add_cust_filter create_tab active">Create</button>
                                    <button type="button" class="btn btn-outline-primary add_cust_filter reconcile_tab">Reconcile</button>
                                </div>
                           
                            </li> -->

                 <button type="button" class="btn btn-outline-primary add_cust_filter create_tab active" >Create</button>
                    <button type="button" class="btn btn-outline-primary add_cust_filter reconcile_tab" >Reconcile</button> 
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
    <form action="newreceiptdb.php"  id="addreceiptForm" method="POST" enctype="multipart/form-data" >
        <!-- <input type="hidden" name="contact_type" id="contact_type" value="Customer"> -->
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
                          $result1=mysqli_query($conn,"select id from receipts where id=(select max(id) from receipts)");
  if($row1=mysqli_fetch_array($result1))
  {
    $id=$row1['id']+1;
    $i=$row1['id'];
    $s=preg_replace("/[^0-9]/", '', $i);
    $invoice_code="RECT0".($s+1);
 }
 else{
  $id = 0;
  $invoice_code = "RECT0".(1);
 }
                          ?>
                <input style="color:black!important;font-weight:bold;" type="text" class="form-control m-input rec_no" placeholder="Receipt No" name="rec_no" value="<?php echo $invoice_code; ?>" readonly>
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
                                    <input style="color:black !important;font-weight:bold;" type="date" class="form-control m-input rec_date " placeholder="Receipt Date" id="receipt_date" name="receipt_date" required>
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
<div style="padding-top: 12px;">
    <div class="form-group">
    <select class="form-control select2-hidden-accessible" id="payment_mode" name="payment_mode" style="font-size: .875rem; width: 100%;" tabindex="-1" aria-hidden="true">
        <option value="Direct Deposit" selected>Direct Deposit</option>
        <option value="NEFT/RTGS">NEFT/RTGS</option>
        <option value="Online Payment">Online Payment</option>
        <option value="Credit Debit Card">Credit/Debit Card</option>
        <option value="Demand Draft">Demand Draft</option>
        <option value="Cheque">Cheque</option>
        <option value="Cash">Cash</option>
    </select>
    </div>
</div>
                        </div>
                    </div>
                    <div class="row" style="margin-left: 0px;margin-right: 0px;">
                        <div class=" col-md-12" style="border-top: 0.1rem solid #ada7a7;">
                          <div class="row" style="margin-top:10px">
                            <div class="col-7">
                              <div class="form-group">
                              <h6 style="font-weight:400;">Customer</h6>
<select class="form-control select-with-scroll" name="customer_name" id="customer_name" required>
    <option value="">Select Customer</option>
    <?php
    $sql = "SELECT * FROM `customer_master` WHERE contact_type='Customer';";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <option value="<?php echo htmlspecialchars($row['id']); ?>" 
                data-email="<?php echo htmlspecialchars($row['email']); ?>">
                <?php echo htmlspecialchars($row['customerName']); ?>
            </option>
            <?php
        }
    }
    ?>
</select>

<!-- Hidden Fields -->
<input type="hidden" name="cst_mstr_id" id="cst_mstr_id">
<input type="hidden" name="customer_email" id="customer_email">

<!-- JavaScript -->
<script>
    // When the customer is selected, update the hidden fields
    document.getElementById('customer_name').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('cst_mstr_id').value = this.value; // Customer ID
        document.getElementById('customer_email').value = selectedOption.getAttribute('data-email'); // Email
    });
</script>

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
                                    <input type="number" min="0" step="0.01" id="amount" name="amount" class="form-control total_amt" placeholder="Amount" value="">
                                  </div>
                                </div>      
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
    <div class="row" style="margin-left: 0px;margin-right: 0px;margin-bottom: 10px;">
        <div class=" col-md-7" style="padding: 0px;border-top: 0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
            <textarea class="form-control " id="notes" name="notes" placeholder="Note" aria-invalid="false" style="margin: 0px;height: 100%;" maxlength="990" rows="5"></textarea>
        </div>
        <div class=" col-md-5" style="border-top: 0.1rem solid #ada7a7;border-left:  0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;padding: 0px;">
            <!-- <h6 class="p-2" style="color:black;display: block;">For <span id="seller_names">KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</span></h6> -->
             <h6 class="pl-5 pt-2" style="float:right;font-size:11px;color:black;display: block;font-weight: 600;margin-left: 20px;">For </h6>
            <h6 class="pl-2" style="float:right;font-weight:600;padding-top: 75px; color:black;font-size:13px;display: block;">Authorised Signatory</h6>
        </div>
    </div>

<div class="row" style="padding:10px;">
   <!-- <input type="text" name="customer_id" value="<?php echo $quotationDetails['customer_id'];?>" hidden> -->
   <!-- <input type="text" name="invoice_pid" value="<?php echo $quotationDetails['invoice_id'];?>" hidden> -->
<!-- <input type="text" name="grand_total" value="<?php echo $quotationDetails['grand_total']?>" hidden> -->

<div class="col-6" id="collected_by_tab" style="display: none;">
    <div class="form-group">
        <h6 style="font-weight:400;">Collected BY <span id="" style="color:red;display:none;"></span></h6>
        <input  type="text" class="form-control m-input form-control-sm" id="collected_by" placeholder="Collected BY">
    </div>
</div>

<div class="col-6" id="bank_name_tab" style="display: none;">
    <div class="form-group">
        <h6 style="font-weight:400;">Bank Name <span id="" style="color:red;display:none;"></span></h6>
        <input type="text" class="form-control m-input form-control-sm" id="bank_name" placeholder="Bank Name">
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
<div class="col-6" id="transaction_date_tab" style="display: none;">
    <div class="form-group">
        <h6 style="font-weight:400;">Trasaction Date<span id="" style="color:red;display:none;"></span></h6>
        <input  type="date" class="form-control m-input form-control-sm" id="transaction_date" name="transaction_date" placeholder="Transaction Date">
    </div>
</div>
<div class="col-6" id="transaction_date_tab" style="display: none;">
    <div class="form-group">
        <h6 style="font-weight:400;">Trasaction Proof Document<span id="" style="color:red;display:none;"></span></h6>
        <input  type="file" class="form-control m-input form-control-sm" id="transaction_proof" name="transaction_proof" placeholder="Transaction Proof Document">
    </div>
</div>
<div class="col-6">
    <div class="form-group">
        <h6 style="font-weight:400;">Reconciliation Option</h6>
        <select class="form-control" id="reconciliation_option" name="reconciliation_option">
            <option value="Do Not Reconcile" selected>Do Not Reconcile</option>
            <option value="Custom Reconcile">Custom Reconcile</option>
        </select>
    </div>
</div>

<!-- <div class="col-6">
    <div class="form-group">
    <label for="reconcileOption">Reconcile</label>
    <select id="reconcileOption" class="form-control" onchange="handleReconcileChange()">
        <option value="select">Select</option>
        <option value="oldest">Reconcile to Oldest</option>
        <option value="recent">Reconcile to Recent</option>
        <option value="custom">Custom Reconcile</option>
        <option value="none">Do Not Reconcile</option>
    </select>
</div>

</div> -->
</div>
              </div>
                </div>
              </div>

            </div>

    <div class="col-md-12" id="reconcile_tab" >
        <div class="container">
            <?php
$query = "SELECT * FROM receipts ";
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
                      
    <div class="table-responsive">
        <table class="table table-bordered text-center" id="unpaid_invoices_table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Balance</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Unpaid invoices will be loaded here dynamically -->
            </tbody>
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
            <button type="submit" name="" class="btn btn-primary">Submit</button>
        </div> 
    </form>
        </div>
    </div>
</div>
       
<!-- Add this script before the closing </body> tag -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- <script src="assets/js/myscript.js"></script> -->
<script>
$(document).ready(function() {
    // Function to show the relevant tab based on the selected option
    function showTab(selectedOption) {
        $('#collected_by_tab, #bank_name_tab, #trans_no_tab, #cheque_no_tab, #dd_no_tab, #credit_debit_card_tab, #transaction_date_tab').hide();

        if (selectedOption === 'Cash') {
            $('#collected_by_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Cheque') {
            $('#bank_name_tab, #cheque_no_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Direct Deposit') {
            $('#bank_name_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Demand Draft') {
            $('#bank_name_tab, #dd_no_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Credit Debit Card') {
            $('#credit_debit_card_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Online Payment') {
            $('#trans_no_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'NEFT/RTGS') {
            $('#bank_name_tab, #transaction_date_tab').show();
        }
    }

    // Initial setup to show the default tab
    var selectedOption = $('#payment_mode').val();
    showTab(selectedOption);

    // Change event handler for the dropdown
    $('#payment_mode').change(function() {
        var selectedOption = $(this).val();
        showTab(selectedOption);
    });
});
</script>


<script type="text/javascript">
    
    $(document).ready(function () {
        // Initial setup
        $("#create_tab").show();
        $("#reconcile_tab").hide();

        // Switching tabs
        $(".create_tab").on("click", function () {
            $("#create_tab").show();
            $("#reconcile_tab").hide();
              // $('#reconcile_tab').css('display', 'none');
               // $('#create_tab').css('display', 'block');

            // Change background color for the selected tab
            $(".add_cust_filter").removeClass("active");
            $(this).addClass("active");

            // Add code to load data for the "Create" tab
            // Example: loadDataForCreateTab();
        });

        $(".reconcile_tab").on("click", function () {
             // $('#create_tab').css('display', 'none');
            $("#create_tab").hide();
            // $('#reconcile_tab').css('display', 'block');
            $("#reconcile_tab").show();

            // Change background color for the selected tab
            $(".add_cust_filter").removeClass("active");
            $(this).addClass("active");

            // Add code to load data for the "Reconcile" tab
            // Example: loadDataForReconcileTab();
        });
    });
</script>

<script>
$(document).ready(function () {
    $('#reconciliation_option').change(function () {
        const selectedOption = $(this).val();
        if (selectedOption === 'Custom Reconcile') {
            alert("from custom reconcile");
            // $('#reconcile_tab').show(); // Show the reconciliation tab
            loadUnpaidInvoices(); // Load unpaid invoices
        } else {
            $('#reconcile_tab').hide(); // Hide the reconciliation tab
            alert("from dont reconciel");
        }
    });

    // Function to load unpaid invoices for the selected customer
    function loadUnpaidInvoices() {
        const customerId = $('#customer_name').val(); // Get the selected customer ID
//alert(customerId);
        if (customerId) {
            $.ajax({
                url: 'fetch_unpaid_invoices.php', // Endpoint to fetch unpaid invoices
                type: 'GET',
                data: { customer_id: customerId },
                success: function (response) {
                     console.log('Unpaid Invoices Response:', response); 
                    $('#unpaid_invoices_table tbody').html(response); // Populate table body with response
                     // $("#reconcile_tab").show();
                },
                error: function () {
                    alert('Error fetching unpaid invoices.');
                },
            });
        } else {
            alert('Please select a customer first.');
        }
    }
});

</script>
<script>
$(document).ready(function () {
    // Function to validate reconcile amounts
    function validateReconcileAmounts() {
        // Get the total amount from the Create tab
        const totalAmount = parseFloat($('#amount').val()) || 0;

        // Calculate the sum of all reconcile amounts
        let totalReconciled = 0;
        $('.reconcile-amount').each(function () {
            const reconcileValue = parseFloat($(this).val()) || 0;
            totalReconciled += reconcileValue;
        });

        // Validate the total reconciled amount
        if (totalReconciled > totalAmount) {
            alert(`Reconciled amount exceeds the total amount: INR ${totalAmount}`);
            return false;
        }

        // Update the receipt balance dynamically
        $('#receipt_balance').text(`Total Reconciled: INR ${totalReconciled.toFixed(2)}`);
        return true;
    }

    // Event listener for changes in reconcile inputs
    $(document).on('input', '.reconcile-amount', function () {
        // Ensure the input value does not exceed the max allowed amount
        const maxAllowed = parseFloat($(this).attr('max'));
        const enteredValue = parseFloat($(this).val());
        if (enteredValue > maxAllowed) {
            alert(`Amount exceeds the maximum allowed: INR ${maxAllowed}`);
            $(this).val(maxAllowed.toFixed(2)); // Reset to max value
        }

        // Re-validate all reconcile amounts
        validateReconcileAmounts();
    });

    // Event listener for changes in the Create tab amount
    $('#amount').on('input', function () {
        validateReconcileAmounts(); // Re-validate reconcile amounts
    });
});

</script>
