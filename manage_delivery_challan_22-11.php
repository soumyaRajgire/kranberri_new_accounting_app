
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

<!DOCTYPE html>

<html lang="en">

<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">

    <style>
    .tab-button.active {
    background-color: #007bff;
    color: #fff;
}
.mandatory-symbol {
    color: red;
  }
  .error {
            color: red;
            size: 80%
        }

        .hidden {
            display: none;
        }

</style>
<style>
    .highlight-error {
        border: 2px solid red;
    }
</style>

</head>

<body class="">
    <!-- [ Pre-loader ] start -->

    <?php include("menu.php"); ?>


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
                                <h4 class="m-b-10">Credit Note</h4>
                                
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                    <div class="col-md-12">
                        <!--  <div class="page-header-title">
                            <h4 class="m-b-10">View Quotation</h4>
                        </div> -->
                        <ul class="ul_filter pl-0 mb-0 nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold mt-0 dash_nav" role="tablist">
                    <li class="nav-item searchfilter_li">
                        <div class="dropdown">
                            <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" style="height: 2.4rem !important;width:100%;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                New
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item create" data-doc="estimate" href="javascript:;"> Quotes</a>
                                <a class="dropdown-item create" data-doc="domestic-invoice" href="javascript:;"> Domestic
                                    Invoice</a>
                                <a class="dropdown-item create" data-doc="international-invoice" href="javascript:;">
                                    International Invoice</a>
                                <a class="dropdown-item create" data-doc="bill" href="bill-of-supply.php"> Bill of Supply</a>
                                <a class="dropdown-item create" data-doc="credit" href="create-credit-note.php"> Credit Note</a>
                                <a class="dropdown-item create" data-doc="receipt" href="javascript:;"> Receipts</a>
                                <a class="dropdown-item create" data-doc="dc" href="delivery_challan.php"> Delivery Challan</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li quotes active" data-item="quotes" href="/m/app/invoice/manage-estimate">Quotations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li invoice" data-item="invoice" href="/m/app/invoice/manage-invoice">Invoices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li bos" data-item="bos" href="manage-billsupply.php">Bill Of Supply</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li cn" data-item="cn" href="manage-creditnote.php">Credit Note</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li receipts" data-item="receipts" href="/m/app/invoice/manage-receipt">Receipts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li receivables" data-item="receivables" href="/m/app/invoice/manage-receivable">Receivables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li delivery_challan" data-item="delivery_challan" href="manage_delivery_challan.php">Delivery Challan</a>
                    </li>
                </ul>
                    </div>
                </div>


            <div class="card mt-3">
                <div class="row mt-3 ml-2">
            <!-- Table and Filters Section -->
            <div class="col-lg-9">
                <!-- Search and Filters -->
                <div class="d-flex align-items-center mb-3">
                    <input type="text" class="form-control mr-2" placeholder="Search Delivery Challan...">
                    <button class="btn btn-outline-primary btn-sm mr-2"><i class="bi bi-sort-down"></i></button>
                    <button class="btn btn-outline-primary btn-sm mr-2"><i class="bi bi-funnel"></i></button>
<div class="input-group">
    <input type="text" class="form-control" id="daterange" placeholder="Date range">
    <button class="btn btn-outline-primary btn-sm mr-2" type="button" id="daterange-btn">
        <i class="bi bi-calendar"></i>
    </button>
</div>
                </div>

                <!-- Table -->
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Customer</th>
                            <th scope="col">Delivery Challan</th>
                            <th scope="col">DC Amount</th>
                            <th scope="col">Dispatched / Vehicle</th>
                            <th scope="col">Created</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center">No records found</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination Info -->
                <div class="d-flex justify-content-between align-items-center">
                    <select class="form-control w-auto">
                        <option>10</option>
                        <option>20</option>
                        <option>50</option>
                    </select>
                    <span>Showing 1 - 0 of 0</span>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="col-lg-3">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Today
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Yesterday
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Last Week
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        This Month
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Last Month
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Last 3 Months
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        All
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                </ul>
            </div>
        </div>
    
</div>


<div id="top-error-message" class="error hidden" style="text-align: center; margin-bottom: 20px;"></div>


<script src="assets/js/myscript.js"></script>
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/pcoded.min.js"></script>
<script>
    $(document).ready(function() {
    $('#daterange').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    // Apply the selected date range to the input field
    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    // Clear the input field on cancel
    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // Open the date picker when the calendar icon is clicked
    $('#daterange-btn').click(function() {
        $('#daterange').focus();
    });
});

</script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

</body>
</html>