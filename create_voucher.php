<!DOCTYPE html>
<?php
session_start();
// Check if the user is logged in
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
?>
<html lang="en">

<head>
  <title>iiiQbets</title>
  <meta charset="utf-8">
  <?php include("header_link.php"); ?>
  <style type="text/css">
    .table th,
    .table td {
      padding: 0.45rem !important;
    }
  </style>
  <style>
    .vertical_line {
      border-left: 1px solid black;
      height: 300px;
      position: absolute;
      left: 70%;
      margin-left: -3px;
      top: 0;
    }
  </style>
</head>

<body class="">
  <?php include("customersModal.php"); ?>
  <?php include("servicesModalPopup.php"); ?>
  <?php include("productsModalPopUp.php"); ?>
  <?php include("menu.php"); ?>

  <section class="pcoded-main-container">
    <div class="pcoded-content">
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h4 class="m-b-10">Create Voucher</h4>
              </div>
              <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                <li class="breadcrumb-item"><a href="#">create voucher</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-12">
        <div class="card">
        <form id="voucherForm" action="vouchersdb.php" method="POST" enctype="multipart/form-data">
  <div class="card-body table-border-style">
    <div class="table-responsive">
      <div class="row">
        <div class="col-sm-12">
          <div class="">
            <div class="card-body">
              <ul class="nav">
                <li class="nav-item">
                  <div class="btn-group dropdown mx-3">
                    <h5 id="receipt_title">Create Voucher</h5>
                  </div>
                </li>
                <li class="nav-item" style="margin-left: 350px;">
                  <select class="form-control form-control-sm" id="notify_settings" name="notification">
                    <option value="0">Select</option>
                    <option value="2">SMS only</option>
                    <option value="4" selected>No Email & SMS</option>
                  </select>
                </li>
                <li class="nav-item" style="margin-left: 340px;">
                  <div class="btn-group btn-group-sm btn_filter">
                    <button type="button" class="btn btn-outline-primary add_cust_filter create_tab active" data-tab-id="create_tab">Create</button>
                    <button type="button" class="btn btn-outline-primary add_cust_filter reconcile_tab" data-tab-id="reconcile_tab">Reconcile</button>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12 tab-content" id="create_tab">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px; border: 0.1rem solid #ada7a7;">
                <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                  <div class="row">
                    <div class="col-md-7 border-right">
                      <div class="-icon mt-3 mb-3">
                        <div class="business_details">
                          <h5 class="line-height-70 mt-3"><b id="seller_name" style=" color: blue;">KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</b><br />120 Newport Center Dr, Newport Beach, CA 92660<br />
                            Email: abhijith.mavatoor@gmail.com<br />
                            Phone: 9481024700<br />
                            GSTIN: 29AAICK7493G1ZX<br />
                          </h5>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-5">
                      <div class="input-group mt-3">
                        <?php
                        $result1 = mysqli_query($conn, "SELECT id FROM vouchers WHERE id = (SELECT MAX(id) FROM vouchers)");
                        if ($row1 = mysqli_fetch_array($result1)) {
                          $id = $row1['id'] + 1;
                          $i = $row1['id'];
                          $s = preg_replace("/[^0-9]/", '', $i);
                          $voucherNumber = "VOU0" . ($s + 1);
                        } else {
                          $id = 0;
                          $voucherNumber = "VOU01";
                        }
                        ?>
                        <input class="form-control" type="text" id="voucherNumber" name="voucherNumber" value="<?php echo $voucherNumber; ?>" />
                        <label class="form-control col-sm-5" for="voucherNumber">Voucher No</label>
                      </div>

                      <div class="input-group mt-3">
                        <input class="form-control" type="date" id="voucherDate" name="voucherDate" required />
                        <label class="form-control col-sm-5" for="voucherDate">Voucher Date</label>
                      </div>
                      <div style="padding-top: 12px;">
                        <div class="form-group">
                          <select class="form-control" id="paymentMode" name="paymentMode">
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
                  <div class="row border-top">
                    <div class="col-md-12">
                      <div class="row mt-3">
                        <div class="col-7">
                          <div class="form-group">
                            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addCustomersModal" style="margin-top: -10px; height: 25px; font-size: 12px;"><i class="fa fa-plus"></i> <b>New</b></button>
                            <h6>Supplier info</h6>
                            <div class="form-group">
                              <input class="form-control" list="customer_name" name="customer_name_choice" id="customer_name_choice" onchange="getPinvoiceCode(this)" autocomplete="off" />
                              <datalist name="customer_name" id="customer_name" placeholder="Select Supplier">
                                          <?php
                                          $sql = "SELECT id, customer_id, customer_name, pinvoice_code FROM purchase_invoice";
                                          $result = $conn->query($sql);

                                          if ($result->num_rows > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                          ?>
                                              <option value="<?php echo $row["customer_name"] ?> - <?php echo $row["pinvoice_code"] ?>" data-customerid="<?php echo $row["customer_id"] ?>">
                                              <?php
                                            }
                                          } else {
                                              ?>
                                              <option value="No Match Found" disable>
                                              <?php
                                            }
                                              ?>
                                        </datalist>
                              <input type="hidden" name="pinvoice_code" id="pinvoice_code" />
                              <input type="hidden" name="customer_id" id="customer_id" />
                            </div>

                            <script>
                                        function getPinvoiceCode(selectedOption) {
                                          var selectedValue = selectedOption.value;
                                          var pinvoiceCode = selectedValue.split(" - ")[1] || "No Match Found";
                                          var customerId = document.querySelector('#customer_name option[value="' + selectedValue + '"]').getAttribute('data-customerid');
                                          document.getElementById('pinvoice_code').value = pinvoiceCode;
                                          document.getElementById('customer_id').value = customerId;
                                          console.log("Selected Pinvoice Code:", pinvoiceCode);
                                          console.log("Selected Customer ID:", customerId);
                                        }
                                      </script>

                          </div>
                        </div>
                        <div class="col-5">
                          <div class="form-group">
                            <h6 style="font-weight:400;">Amount</h6>
                            <div class="input-group input-group-sm">
                              <input type="number" min="0" step="0.01" id="amount" name="amount" class="form-control total_amt" placeholder="Amount" value="">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-7 ">
                      <textarea class="form-control" id="notes" name="notes" placeholder="Note" aria-invalid="false" style="margin: 0px;height: 100%;" maxlength="990" rows="5"></textarea>
                    </div>
                    <div class="col-md-5">
                      <div class="form-control" aria-invalid="false" style="margin: 0px; height: 100%;" maxlength="990" rows="5">
                        <h6 class="p-2 text-dark">For <span id="seller_names"></span></h6>
                        <h6 class="pl-2" style="padding-top: 75px; color: black; display: block;">Authorised Signatory</h6>
                      </div>
                    </div>
                  </div>
                  <br>

                  <div class="row" style="margin-left: 0px;margin-right: 0px;" id="payment_mode">
                    <div class="col-6" id="collected_by_tab" style="display: none;">
                      <div class="form-group">
                        <h6 style="font-weight:400;">Collected BY <span id="" style="color:red;display:none;"></span></h6>
                        <input type="text" class="form-control m-input form-control-sm" id="collected_by" name="collected_by" placeholder="Collected BY">
                      </div>
                    </div>

                    <div class="col-6" id="bank_name_tab" style="display: none;">
                      <div class="form-group">
                        <h6 style="font-weight:400;">Bank Name <span id="" style="color:red;display:none;"></span></h6>
                        <input type="text" class="form-control m-input form-control-sm" id="bank_name" name="bank_name" placeholder="Bank Name">
                      </div>
                    </div>

                    <div class="col-6" id="trans_no_tab" style="display: none;">
                      <div class="form-group">
                        <h6 style="font-weight:400;">Transaction No <span id="remind" style="color:red;display:none;"></span></h6>
                        <input type="text" class="form-control m-input form-control-sm" id="trans_no" name="trans_no" placeholder="Transaction No">
                      </div>
                    </div>

                    <div class="col-6" id="cheque_no_tab" style="display: none;">
                      <div class="form-group">
                        <h6 style="font-weight:400;">Cheque No <span id="" style="color:red;display:none;"></span></h6>
                        <input type="text" class="form-control m-input form-control-sm" id="cheque_no" name="cheque_no" placeholder="Cheque No">
                      </div>
                    </div>
                    <div class="col-6" id="dd_no_tab" style="display: none;">
                      <div class="form-group">
                        <h6 style="font-weight:400;">Demand Draft No<span id="" style="color:red;display:none;"></span></h6>
                        <input type="text" class="form-control m-input form-control-sm" id="dd_no" name="dd_no" placeholder="DD No">
                      </div>
                    </div>
                    <div class="col-6" id="credit_debit_card_tab" style="display: none;">
                      <div class="form-group">
                        <h6 style="font-weight:400;">Card last 4 digit No<span id="" style="color:red;display:none;"></span></h6>
                        <input type="text" class="form-control m-input form-control-sm" id="card_last_no" name="card_last_no" placeholder="Card last 4 digit No"><label></label>
                      </div>
                    </div>
                    <div class="col-6" id="transaction_date_tab" style="display: none;">
                      <div class="form-group">
                        <h6 style="font-weight:400;">Transaction Date<span id="" style="color:red;display:none;"></span></h6>
                        <input type="date" class="form-control m-input form-control-sm" id="transaction_date" name="transaction_date" placeholder="Transaction Date">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12 tab-content" id="reconcile_tab" style="display:none;">
        <div class="container">
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
                              <th style="position: sticky; top: 0; background-color: #ededed;">Details</th>
                              <th style="position: sticky; top: 0; background-color: #ededed;">Debit</th>
                              <th style="position: sticky; top: 0; background-color: #ededed;">Credit</th>
                              <th style="position: sticky; top: 0; background-color: #ededed;">Status</th>
                            </tr>
                          </thead>
                          <tbody id="total_inv">
                            <tr>
                              <td colspan="5" style="text-align: center;">No Records found</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-12 text-center" style="padding-right: 0px;" id="receipt_balance_tab">
                      <span id="receipt_balance"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        <ul class="nav justify-content-end mb-2" style="margin-right: 16px;">
          <button type="submit" class="btn btn-sm btn-success">
            <i class="fa fa-plus"></i>&nbsp;<span class="voucher-text">Create Voucher</span>
          </button>
        </ul>
      </div>
    </div>
  </div>
</form>
        </div>
      </div>
    </div>
    </div>
  </section>

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
    $(document).ready(function() {
      $(".create_tab").addClass("active");

      $(".create_tab, .reconcile_tab").on("click", function() {
        $(".create_tab, .reconcile_tab").removeClass("active");
        $(this).addClass("active");

        var tabId = $(this).data("tab-id");
        $(".tab-content").hide();
        $("#" + tabId).show();
      });
    });
  </script>
  <script>
    $(document).ready(function() {
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

      var selectedOption = $('#paymentMode').val();
      showTab(selectedOption);

      $('#paymentMode').change(function() {
        var selectedOption = $(this).val();
        showTab(selectedOption);
      });
    });
  </script>
  <script>
    function checknamevalue(selectedValue) {
      var selectedOption = document.querySelector('option[value="' + selectedValue + '"]');
      if (selectedOption) {
        document.getElementById('customer_id').value = selectedOption.getAttribute('data-customerid');
      }
    }
  </script>

  <script src="assets/js/vendor-all.min.js"></script>
  <script src="assets/js/plugins/bootstrap.min.js"></script>
  <script src="assets/js/pcoded.min.js"></script>
  <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
  <script src="assets/js/myscript.js"></script>
</body>

</html>