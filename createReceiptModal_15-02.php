<?php
error_reporting(E_ALL);
ini_set('display_errors',-1);
// if($_SERVER['REQUEST_METHOD'] === 'POST')
// {
    
//     $title= mysqli_real_escape_string($conn, $_POST['title']);
//     $name= mysqli_real_escape_string($conn, $_POST['name']);
//     $entity_type= mysqli_real_escape_string($conn, $_POST['entity_type']);
//     $mobile_number= mysqli_real_escape_string($conn, $_POST['mobile_number']);
//     $email = mysqli_real_escape_string($conn, $_POST['email']);
//     $customer_gstin = mysqli_real_escape_string($conn, $_POST['customer_gstin']);
//     $customer_registered_name = mysqli_real_escape_string($conn, $_POST['customer_registered_name']);
//     $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
//     $display_name = mysqli_real_escape_string($conn, $_POST['display_name']);
//     $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
//     $fax = mysqli_real_escape_string($conn, $_POST['fax']);
//     $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
//     $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
//     $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
//     $ifsc_code= mysqli_real_escape_string($conn, $_POST['ifsc_code']);
//     $account_type= mysqli_real_escape_string($conn, $_POST['account_type']);
//     $branch_name= mysqli_real_escape_string($conn, $_POST['branch_name']);
//     $pan= mysqli_real_escape_string($conn, $_POST['pan']);
//     $tan= mysqli_real_escape_string($conn, $_POST['tan']);
//     $tds_slab_rate= mysqli_real_escape_string($conn, $_POST['tds_slab_rate']);
//     $currency= mysqli_real_escape_string($conn, $_POST['currency']);
//     $terms_of_payment= mysqli_real_escape_string($conn, $_POST['terms_of_payment']);
//     $reverse_charge= mysqli_real_escape_string($conn, $_POST['reverse_charge']);
//     $export_type= mysqli_real_escape_string($conn, $_POST['export_type']);
//     $bill_address_line1= mysqli_real_escape_string($conn, $_POST['bill_address_line1']);
//     $bill_address_line2= mysqli_real_escape_string($conn, $_POST['bill_address_line2']);
//     $bill_city= mysqli_real_escape_string($conn, $_POST['bill_city']);
//     $bill_pin_code= mysqli_real_escape_string($conn, $_POST['bill_pin_code']);
//     $bill_state= mysqli_real_escape_string($conn, $_POST['bill_state']);
//     $bill_country= mysqli_real_escape_string($conn, $_POST['bill_country']);
//     $bill_branch_name= mysqli_real_escape_string($conn, $_POST['bill_branch_name']);
//     $bill_gstin= mysqli_real_escape_string($conn, $_POST['bill_gstin']);

// $checkbox_name = mysqli_real_escape_string($conn,$_POST['checkbox_name']);

//     if(isset($_POST['checkbox_name']))
//     {
//         $ship_address_line1= mysqli_real_escape_string($conn, $_POST['bill_address_line1']);
//     $ship_address_line2 = mysqli_real_escape_string($conn, $_POST['bill_address_line2']);
//     $ship_city = mysqli_real_escape_string($conn,$_POST['bill_city']);
//     $ship_pin_code = mysqli_real_escape_string($conn, $_POST['bill_pin_code']);
//     $ship_state = mysqli_real_escape_string($conn, $_POST['bill_state']);
//     $ship_country = mysqli_real_escape_string($conn, $_POST['bill_country']);
//     $ship_branch_name = mysqli_real_escape_string($conn, $_POST['bill_branch_name']);
//     $ship_gstin = mysqli_real_escape_string($conn, $_POST['bill_gstin']);
        
//     }
//     else
//     {
//         $ship_address_line1= mysqli_real_escape_string($conn, $_POST['ship_address_line1']);
//     $ship_address_line2 = mysqli_real_escape_string($conn, $_POST['ship_address_line2']);
//     $ship_city = mysqli_real_escape_string($conn,$_POST['ship_city']);
//     $ship_pin_code = mysqli_real_escape_string($conn, $_POST['ship_pin_code']);
//     $ship_state = mysqli_real_escape_string($conn, $_POST['ship_state']);
//     $ship_country = mysqli_real_escape_string($conn, $_POST['ship_country']);
//     $ship_branch_name = mysqli_real_escape_string($conn, $_POST['ship_branch_name']);
//     $ship_gstin = mysqli_real_escape_string($conn, $_POST['ship_gstin']);
//     }

    
// $created_by = $_SESSION['name'];
    

//     $sqlCustomer = "INSERT INTO customer_master (title, customerName, entityType, mobile, email, gstin, gst_reg_name, business_name, display_name, phone_no, fax, account_no, account_name, bank_name, account_type,ifsc_code,branch_name, pan, tan, tds_slab_rate, currency, terms_of_payment, reverse_charge, export_or_sez,contact_type,created_by)
//                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
// $stmtCustomer = $conn->prepare($sqlCustomer);

// // Specify the correct data types for each parameter in the bind_param call
// $customerType = 'Customer'; // Create a variable for 'Customer'
// $stmtCustomer->bind_param("ssssssssssssssssssssssssss", $title, $name, $entity_type, $mobile_number, $email, $customer_gstin, $customer_registered_name, $business_name, $display_name, $phone_number, $fax, $account_number, $account_name, $bank_name, $account_type, $ifsc_code, $branch_name, $pan, $tan, $tds_slab_rate, $currency, $terms_of_payment, $reverse_charge, $export_type, $customerType, $created_by);


// if ($stmtCustomer->execute() === TRUE) {
//  $customer_id = $stmtCustomer->insert_id;

// $sqlAddress = "INSERT INTO address_master (s_address_line1, s_address_line2,s_city,s_Pincode,s_state,s_country,s_branch,s_gstin,b_address_line1,b_address_line2,b_city,b_Pincode,b_state,b_country,b_branch,b_gstin,customer_master_id )
//                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
// $stmtAddress = $conn->prepare($sqlAddress);
// $stmtAddress->bind_param("ssssssssssssssssi",$ship_address_line1,$ship_address_line2,$ship_city,$ship_pin_code,$ship_state,$ship_country,$ship_branch_name,$ship_gstin,$bill_address_line1, $bill_address_line2, $bill_city, $bill_pin_code, $bill_state, $bill_country, $bill_branch_name, $bill_gstin,$customer_id);
// if ($stmtAddress->execute() === TRUE) {

//     ?>
<script>
//                 alert("Data inserted Successfully");
//                 window.location.href = "create-estimate.php";
//             </script>
//     <?php
// }  
// } else {
//     echo "Error inserting customer: " . $stmtCustomer->error;
// }




// }

// ?>




<div id="receiptsModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
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
    <form action="receiptdb.php"  id="addreceiptForm" method="POST" enctype="multipart/form-data" >
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
                                <h6 style="font-weight:400;">Customer  </h6>
                                <input type="text" class="form-control" name="customer_name" id="customer_name" value="<?php echo $quotationDetails['customerName'];?>" readonly>
                                <input type="text" class="form-control" name="customer_email" id="customer_email" value="<?php echo $quotationDetails['email'];?>" hidden>
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
                                     <input type="number" min="0" step="0.01" id="amount" name="amount" class="form-control total_amt" placeholder="Amount" value="<?php echo $quotationDetails['grand_total']?>"  max="<?php echo $quotationDetails['grand_total']; ?>"
    oninput="validateAmount(this)">
    <script>
  function validateAmount(input) {
    const max = parseFloat(input.max); // Maximum allowed value
    const value = parseFloat(input.value); // Current entered value

    if (value > max) {
      input.value = max; // Reset value to the maximum allowed value
      alert(`Amount cannot exceed ${max}`); // Optionally show a warning
    }
  }
</script>
           
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
             <h6 class="pl-5 pt-2" style="float:right;font-size:11px;color:black;display: block;font-weight: 600;margin-left: 20px;">For KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</h6>
            <h6 class="pl-2" style="float:right;font-weight:600;padding-top: 75px; color:black;font-size:13px;display: block;">Authorised Signatory</h6>
        </div>
    </div>

<div class="row" style="padding:10px;">
   <input type="text" name="customer_id" value="<?php echo $quotationDetails['customer_id'];?>" hidden>
   <input type="text" name="invoice_pid" value="<?php echo $quotationDetails['invoice_id'];?>" hidden>
<input type="text" name="grand_total" value="<?php echo $quotationDetails['grand_total']?>" hidden>

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

    <div class="col-md-12" id="reconcile_tab" style="display:none;">
        <div class="container">
            <?php
$query = "SELECT * FROM receipts WHERE invoice_id = '$inv_id' ";
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
            <button type="submit" name="" class="btn btn-primary">Submit</button>
        </div> 
    </form>
        </div>
    </div>
</div>
       
<!-- Add this script before the closing </body> tag -->

<script src="assets/js/myscript.js"></script>

