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
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <?php include("header_link.php");?>
<style>
  .dropdown-card {
    display: none;
    margin-top: 10px;
  }

  .active-card {
    display: block;
  }

          #purchase-invoices-datatable th,
          #purchase-order-datatable th,
          #voucher-datatable th,
          #payroll-datatable th,
          #debit-note-datatable th,
          #party-wise-datatable th,
          #accounts-datatable th {
        text-transform: capitalize;
        font-size: 14px;
    }
</style>

</head>
<body class="">
    <!-- [ Pre-loader ] start -->
     
     <?php include("menu.php");?>
    
    
    <!-- [ Header ] end -->
    

<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Purchase Invoices</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Purchase Invoices</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>


            <div class="card" style="border-radius: 5px;">
    <div class="row">
        <div class="col-lg-9">
        <ul class="nav nav-tabs" id="myTabs">
        <li class="nav-item">
            <a class="nav-link active" onclick="showCard('invoicesCard')">Purchase Invoices</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('orderCard')">Purchase Order</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('voucherCard')">Voucher</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('PayrollCard')">Payroll</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('DebitNoteCard')">Debit Note</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('PartyWiseCard')">Party Wise Payable</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('AccountsCard')">Accounts Payable</a>
        </li>
    </ul>
        </div>

        <div class="col-lg-2 mt-2 mx-2">
            <div class="dropdown">
                <a class="btn btn-success btn-sm dropdown-toggle" href="#" id="createDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Create</a>
                <div class="dropdown-menu" aria-labelledby="createDropdown">
                    <a class="dropdown-item" target="_blank" href='create-purchase-invoice.php'>Purchase Invoice</a>
                    <a class="dropdown-item" target="_blank" href='create-purchase-order.php'>Purchase Order</a>
                    <a class="dropdown-item" target="_blank" href='create_voucher.php'>Voucher Payment</a>
                    <a class="dropdown-item" target="_blank" href='salary_payment.php'>Salary Payment</a>
                    <a class="dropdown-item" target="_blank" href='create-debit-note.php'>Debit Note</a>
                </div>
            </div>
        </div>
        </div>
        <div class="row" style="justify-content:end;">
        <div class="col-lg-4 mb-3">
            <div class="input-group mt-3">
                <input type="text" class="form-control" placeholder="Search..." id="generalSearch1">
            </div>
        </div>

        <div class="col-lg-2 mt-3">
            <div class="input-group date-range-picker">
                <input type="text" class="form-control date-filter bg-white" readonly placeholder="Date range" />
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-calendar-check"></i></span>
                </div>
            </div>
        </div>

        <div class="col-lg-1 mt-3">
            <div class="dropdown" data-toggle="tooltip" title="Filter">
                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-filter"></i> &nbsp; <span class="filter-text"></span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item quick-filter" data-filter="All" href="#">All</a>
                    <a class="dropdown-item quick-filter" data-filter="Paid" href="#">Paid</a>
                    <a class="dropdown-item quick-filter" data-filter="Unpaid" href="#">Unpaid</a>
                    <a class="dropdown-item quick-filter" data-filter="Part Paid" href="#">Part Paid</a>
                    <a class="dropdown-item quick-filter" data-filter="Deleted" href="#">Deleted</a>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Cards for each option -->
<?php
$sql = "SELECT cm.customerName, pi.pinvoice_code,pi.id as pi_id, pi.pinvoice_date, pi.grand_total, pi.created_on,  pi.created_by, pi.customer_id
        FROM customer_master AS cm
        JOIN purchase_invoice AS pi ON cm.id = pi.customer_id
        WHERE cm.contact_type = 'supplier'";

$result = $conn->query($sql);
?>
<div class="row">
   <div class="col-md-12" style="margin-top: -30px;">
   <div class="tab-content mt-3">
    <div id="invoicesCard" class="tab-pane active">
        <!-- Purchase Invoices Card Content -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Purchase Invoices</h5>
                <table class="table" id="purchase-invoices-datatable">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Purchase Invoice</th>
                            <th>Total Amount</th>
                            <th>Payment</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                            <td>" . $row["customerName"] . "
                            <br><a href=''>Update GSTIN</a></td>
                            <td>" . $row["pinvoice_code"] . "<br>
                            " . $row["pinvoice_date"] . "</td>
                            <td>INR
                                " . $row["grand_total"] . "  
                                <br><a href='view-purchase-invoice-action.php?pinv_id=". $row['pi_id']."'>View Purchase Invoice</a>
                            </td>
                            <td></td>
                            <td>" . $row["created_on"] . " <br>
                            " . $row["created_by"] . "</td>
                        </tr>";
                        }
                    } else {
                        // If no records found
                        echo "<tr>
                                <td colspan='4'>
                                    <span>No records found</span>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>


<?php

// SQL query to fetch data from customer_master and purchase_order tables
$sql = "SELECT cm.customerName, po.order_code, po.id as pord_id, po.order_date, po.grand_total, po.created_on, po.created_by, po.customer_id
        FROM customer_master AS cm
        JOIN purchase_order AS po ON cm.id = po.customer_id
        WHERE cm.contact_type = 'supplier'";

$result = $conn->query($sql);

// Handle the result as needed (fetching data, etc.)

?>




<div id="orderCard" class="tab-pane" style="display: none;">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Purchase Order</h5>
            <table class="table" id="purchase-order-datatable">
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Number</th>
                        <th>Amount</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["customerName"] . "
                                    <br><a href=''>Update GSTIN</a></td>
                                    <td>" . $row["order_code"] . "<br>
                                    " . $row["order_date"] . "
                                </td>
                                    <td>INR " . $row["grand_total"] . "
                                    <br><a href='view-purchase-order-action.php?porder_id=". $row['pord_id']."'>View Purchase Order</a>
                                    </td>
                                    <td>" . $row["created_on"] . "
                                   <br> " . $row["created_by"] . "</td>
                                  </tr>";
                        }
                    } else {
                        // If no records found
                        echo "<tr>
                                <td colspan='4'>
                                    <span>No records found</span>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<?php
$sql = "SELECT v.id AS v_id, v.customer_name, v.voucherNumber, v.voucherDate, v.amount, v.paymentMode, pi.pinvoice_code, pi.id AS p_id
        FROM vouchers v
        JOIN purchase_invoice pi ON v.pinvoice_code = pi.pinvoice_code";
$result = $conn->query($sql);
?>

<div id="voucherCard" class="tab-pane" style="display: none;">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Voucher</h5>
      <table class="table" id="voucher-datatable">
        <thead>
          <tr>
            <th>Payee</th>
            <th>Number</th>
            <th>Amount</th>
            <th>Accounting</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>" . $row["customer_name"] . "</td>
                      <td><a href='view-voucher-action.php?voucherId=" . $row['v_id'] . "'>" . $row["voucherNumber"] . "</a><br>" . $row["voucherDate"] . "</td>
                      <td>INR " . number_format($row["amount"], 2) . "<br>" . $row["paymentMode"] . "</td>
                      <td><a href='view-purchase-invoice-action.php?pinv_id=" . $row['p_id'] . "'>" . $row["pinvoice_code"] . "</a></td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='4'><span>No records found</span></td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>





<?php
// $sql = "SELECT em.id AS employee_id, em.name, em.officemail, sp.voucherNumber, sp.payment_date, sp.amount, sp.payment_mode, sp.created_on, sp.created_by, sp.id AS voucher_id
//         FROM employees_data AS em
//         JOIN salary_payments AS sp";

$sql = "SELECT em.id AS employee_id, em.name, em.officemail, sp.voucherNumber, sp.payment_date, sp.amount, sp.payment_mode, sp.created_on, sp.created_by, sp.id AS voucher_id
FROM employees_data AS em
JOIN salary_payments AS sp
ON em.id = sp.employee
GROUP BY employee, voucherNumber";

$result = $conn->query($sql);


?>
<div id="PayrollCard" class="tab-pane" style="display: none;">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Payroll</h5>
      <!-- Add your table content here -->
      <table class="table" id="payroll-datatable">
        <thead>
          <tr>
            <th>Payee</th>
            <th>Number</th>
            <th>Amount</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result && $result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td><a href='employee_profile.php?id=" . $row['employee_id'] . "'>" . $row["name"] . "</a><br>
                          " . $row["officemail"] . "
                      </td>
                      <td><a href='view-salarypayment-action.php?voucherId=" . $row['voucher_id'] . "'>" . $row["voucherNumber"] . "</a><br>" . $row["payment_date"] . "</td>
                      <td>INR " . $row["amount"] . "<br>" . $row["payment_mode"] . "</td>
                      <td>" . $row["created_on"] . "<br>" . $row["created_by"] . "</td>
                    </tr>";
            }
          } else {
            // If no records found
            echo "<tr>
                    <td colspan='4'>
                        <span>No records found</span>
                    </td>
                  </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div id="DebitNoteCard" class="tab-pane" style="display: none;">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Debit Note</h5>
      
      <table class="table" id="debit-note-datatable">
        <thead>
          <tr>
            <th>Debit Amount</th>
            <th>Customer Name</th>
            <th>Note Number</th>
            <th>Document</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php
            // Include the database configuration file
         

            // Query to fetch data from the `debit_note` table
            $$sql = "SELECT id, dnote_code, dnote_file, customer_id, customer_name, email, dnote_date, total_amount, terms_condition, note, status, created_by, created_at 
                      FROM debit_note";
            $result = mysqli_query($conn, $$sql);

            // Check if any records are returned
            if ($result && mysqli_num_rows($result) > 0) {
              // Loop through each record and output as a table row
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['total_amount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dnote_code']) . "</td>";
                echo "<td><a href='" . htmlspecialchars($row['dnote_file']) . "' target='_blank'>View Document</a></td>";
                echo "<td>" . htmlspecialchars($row['dnote_date']) . "</td>";
                echo "</tr>";
              }
            } else {
              // If no records found, display a single row with "No records found"
              echo "<tr><td colspan='5'><span>No records found</span></td></tr>";
            }

            // Close the database connection
            // Close the database connection
$conn->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<div id="PartyWiseCard" class="tab-pane" style="display: none;">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Party Wise Payable</h5>
      <!-- Add your table content here -->
      <table class="table" id="party-wise-datatable">
        <!-- Table content goes here -->
                <thead>
                    <tr>
                        <th>Balance Amount</th>
                        <th>Supplier Name</th>
                        <th>Paid Amount</th>
                        <th>Last Payment</th>
                        <th>GST ITC</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">
                        <span>No records found</span>
                        </td>
                    </tr>
                </tbody>
      </table>
    </div>
  </div>
</div>

<div id="AccountsCard"  class="tab-pane" style="display: none;">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Accounts Payable</h5>
      <!-- Add your table content here -->
      <table class="table" id="accounts-datatable">
        <!-- Table content goes here -->
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Purchase Invoice</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">
                        <span>No records found</span>
                        </td>
                    </tr>
                </tbody>
      </table>
    </div>
  </div>
</div>
</div>

<!-- <div class="col-md-3" style="margin-top: -20px;">
    <div class="card" style=" margin-left: -10px;">

    </div>
</div> -->
</div>


<!-- Debit Note popup modal -->
<div class="modal" id="debitNoteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 60%; margin-left: 25%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Debit Note</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <!-- Your content here -->
                <div class="row">
                    <!-- Copy your content here -->
                    <div class="col-lg-12">
                        <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom:5px;">
                        <div class="col-md-12" id="create_tab">
                <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
                  <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                    <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                        <div class="row" style="margin-right: 0px; margin-left: 0px;">
                            <div class=" col-md-7" style="border-right: 0.1rem solid #ada7a7;">
                                <div class="-icon" style="margin-top:10px;  margin-bottom:10px;">
                                    <div class="business_details">
                                    <h5 class="line-height-70 mt-3"><b id="seller_name" style=" color: blue;">KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</b><br/>120 Newport Center Dr, Newport Beach, CA 92660<br/>
                        Email: abhijith.mavatoor@gmail.com<br/>
Phone: 9481024700<br/>
GSTIN: 29AAICK7493G1ZX<br/></h5>
                                    </div>
                                </div>
                            </div>
                            <div class=" col-md-5">
                                <div class="" style="padding-top: 12px;">
                                    <div class="kt-input-icon kt-input-icon--right" >
                                        <span>
                                            <div class="m-input-icon m-input-icon--right">
                                                <input style="color:black!important;font-weight:bold;" type="text"
                                                class="form-control m-input note_no" name="Debit_number" placeholder="Debit Note" disabled="">
                                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                    <span>
                                                        <i class="la la-file"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                <div class="" style="padding-top: 12px;">
                                    <div class="kt-input-icon kt-input-icon--right" >
                                        <span>
                                            <div class="m-input-icon m-input-icon--right payment_date">
                                                <input style="color:black!important;font-weight:bold;" type="text"
                                                class="form-control m-input note_date" placeholder="Note Date" >
                                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                    <span>
                                                        <i class="la la-calendar"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">
                            <div class=" col-md-12" style="border-top: 0.1rem solid #ada7a7;">
                                <div class="row" style="margin-top:10px">
                                    <div class="col-7">
                                        <div class="form-group">
                                        <h6 style="font-weight:400;">Supplier<a class="add_supplier_btn" style="float:right;font-size: 10px;"
                                        href="javascript:;" >Add Supplier</a></h6>
                                        <select style="width:100%;"  placeholder="Select Employee"   class="form-control m-select2 contact_list " id="contact_list"></select>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="form-group">
                                        <h6 style="font-weight:400;">Amount </h6>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-append" style="width:100%">
                                                    <input type="number" min="0" step="0.01" id="amount" onkeypress="return isNumberKey(event)" maxlength="8" required="" class="form-control total_amt" placeholder="Amount" value=""  >
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;margin-bottom: 10px;">
                            <div class=" col-md-7" style="padding: 0px;border-top: 0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
                                <textarea class="form-control " id="notes"   name="notes" aria-invalid="false" style="margin: 0px;height: 100%;" maxlength="990" rows="5" placeholder="Notes to Supplier"></textarea>
                            </div>
                            <div class=" col-md-5" style="border-top: 0.1rem solid #ada7a7;border-left:  0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
                                <h6 class="p-2" style="color:black;display: block;">For <span id="seller_names" ></span></h6>
                                <h6 class="pl-2" style="padding-top: 75px; color:black;display: block;">Authorised Signatory</h6>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">
                            <div class="col-12" data-toggle="collapse" data-target="#collapseTwo4">
                                <h5 style="font-weight:400;" class="dropdown-toggle">For Internal Use</h5>
                            </div>
                        </div>
                      <div id="collapseTwo4" class="collapse show" style="margin-top: 10px;">
                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                            <div class="col-12">
                                <div class="form-group">
                                    <h6 style="font-weight:400;">Purchase Invoices</h6>
                                    <select   style=" opacity: 10;width=100%;"    class="form-control col-md-12 m-input form-control-sm m-select2 inv_list" id="m_select2_3"></select>
                                </div> 
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
            <ul class="nav justify-content-end mb-2">
                <li class="nav-item col-md-5" >
                    <button type="button" class="btn btn-sm btn-success create-invoice-btn" style="margin-left: 39px;"><i class="fa fa-plus"></i>&nbsp;<span class="voucher-text">Create Debit Note</span></button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" onclick="parent.modal_close();"><i class="fa fa-times"></i>&nbsp;<span class="voucher-text">Close</span></button>
                </li>
            </ul>
        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openDebitNoteModal() {
        // Show the hidden modal
        $('#debitNoteModal').modal('show');
    }
</script>
<!-- Debit Note  popup modal -->
<!-- JavaScript to handle card visibility -->

<script>
    function showCard(cardId) {
        // Hide all tab content elements
        document.querySelectorAll('.tab-pane').forEach(card => {
            card.style.display = 'none';
        });

        // Show the selected card
        document.getElementById(cardId).style.display = 'block';

        // Remove 'active' class from all tabs
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.classList.remove('active');
        });

        // Add 'active' class to the selected tab
        event.target.classList.add('active');
    }
</script>
        </div>
</section>        
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Function to handle search logic
    function handleSearch(searchInput, tableId) {
        var searchTerm = searchInput.value.toLowerCase();
        var table = document.getElementById(tableId);
        var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        // Loop through each row in the table body
        for (var i = 0; i < rows.length; i++) {
            var rowData = rows[i].innerText.toLowerCase();

            // Check if the search term matches any part of the row data
            if (rowData.includes(searchTerm)) {
                // Display the row
                rows[i].style.display = '';
            } else {
                // Hide the row
                rows[i].style.display = 'none';
            }
        }
    }

    // Attach event listeners to search inputs
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'purchase-invoices-datatable');
    });
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'purchase-order-datatable');
    });
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'voucher-datatable');
    });
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'payroll-datatable');
    });
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'party-wise-datatable');
    });
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'accounts-datatable');
    });
    // Add similar event listeners for other search inputs and their respective tables
    // Example:
    // document.getElementById('yourSearchInputId').addEventListener('input', function () {
    //     handleSearch(this, 'yourTableId');
    // });

    // Repeat for other search inputs and tables as needed
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    const expenseVouchersLink = document.querySelector('a[href="purchase_invoices.php?voucherCard"]');
    expenseVouchersLink.addEventListener('click', function (event) {
        event.preventDefault();
        showDropdown('voucherCard');
    });

    const accountsPayablesLink = document.querySelector('a[href="purchase_invoices.php?AccountsCard"]');
    accountsPayablesLink.addEventListener('click', function (event) {
        event.preventDefault();
        showDropdown('AccountsCard');
    });
});

function showDropdown(dropdownId) {
    // Hide all dropdown cards
    const allDropdownCards = document.querySelectorAll('.dropdown-card');
    allDropdownCards.forEach(card => card.style.display = 'none');

    // Show the selected dropdown card
    const selectedDropdownCard = document.getElementById(dropdownId);
    if (selectedDropdownCard) {
        selectedDropdownCard.style.display = 'block';
    }

    // Update the dropdown button text
    const dropdownButton = document.getElementById('purchaseDropdown');
    dropdownButton.textContent = selectedDropdownCard ? selectedDropdownCard.querySelector('.card-title').textContent : '';
}


</script>
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <!-- <script src="assets/js/myscript.js"></script> -->
</body>
</html>

    
