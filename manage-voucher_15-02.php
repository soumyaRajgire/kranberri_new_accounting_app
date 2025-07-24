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
    <?php include("header_link.php");?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .notification-item:hover {
            background-color: #f0f0f0; /* Change this to the desired hover background color */
        }

        .notification-item-details:hover {
            color: #00acc1; /* Change this to the desired hover text color */
        }

.tooltip-inner  {
    background-color: white;
    color: black; /* Set text color to black or your preferred color */
    border-radius: 2px;
    font-size: 13px;
}
#receipt-datatable th {
        text-transform: capitalize;
        font-size: 14px;
    }
    </style>
</head>
<body class="">
    <!-- Pre-loader -->
     
     <?php include("menu.php");?>
  


   <?php include("createNewVoucherModal.php"); ?>


   <!-- Header -->
   <!-- <style>
    /* Custom CSS styles for the card */
    .custom-card {
        width: 1452px;
        height: 100%;
        margin-left: 235px;
    }
    #info_form {
        margin-top: 100px;
        width: 100%;
    }
    .custom-table th, td, tr {
            border: 1px solid lightgray; /* Define your desired border style and color here */
    }
    .custom-table th {
           font-weight: bold;
    font-size: 14px;
    color: dimgrey;
    padding: 7px 0.75rem;
    }
    .custom-table td {
/*        width: 700px;*/
  padding: 7px 0.75rem;
    }
</style> -->


<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">Manage Voucher</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;" >
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Manage Voucher</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>

<?php include("purchases_menu.php");?>

<div class="row">
     
        <div class="col-lg-12">
    <div class="card" style="border-radius: 5px;">
        <div class="row">
            <div class="col-lg-12">
                <ul class="filter-list list-unstyled mt-3 mx-2">
                    <div class="row">
                        <div class="col-lg-2">
                           <!--  <li>
                                <div class="dropdown mx-2" data-toggle="tooltip" data-placement="top" title="Manage">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Receipts
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-start">
                                        <a class="dropdown-item invoice-type" data-doc-type="estimate" href="view-quotation.php">Estimates</a>
                                        <a class="dropdown-item invoice-type" data-doc-type="invoice" href="view-invoices.php">Invoices</a>
                                        <a class="dropdown-item invoice-type" data-doc-type="bos" href="/invoice/manage-bos">Bill of Supply</a>
                                        <a class="dropdown-item invoice-type" data-doc-type="credit-note" href="/invoice/manage-creditnote">Credit Note</a>
                                        <a class="dropdown-item invoice-type" data-doc-type="dc" href="/invoice/manage-dc">Delivery Challan</a>
                                    </div>
                                </div>
                            </li> -->
                        </div>
                        <div class="col-lg-3">
                            <li class="search-filter" style="margin-left: -40px;">
                                <div class="input-icon input-icon-left">
                                    <input type="text" class="form-control form-control-sm input" placeholder="Search..." id="generalSearchReceipt">
                                    <span class="input-icon__icon input-icon__icon--left">
                                        <span><i class="la la-search"></i></span>
                                    </span>
                                </div>
                            </li>
                        </div>
                       <!--  <div class="col-lg-2">
    <li>
        <button class="btn btn-sm btn-success" style="margin-left: -10px;" type="button" onclick="openCreateReceiptPage()">New Receipt</button>
    </li>
</div> -->

<script>
    // function openCreateReceiptPage() {
    //     // Redirect to create-receipt.php
    //     window.location.href = 'create-receipt.php';
    // }
</script>
                        <div class="col-lg-1">
                            <li>
                                <div class="dropdown" data-toggle="tooltip" data-placement="top" title="Sort"  style="margin-left: -30px;">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 50px;">
                                        <i class="fa fa-sort"></i> &nbsp; <span class="sort-text"></span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item receipt-sort" data-filter="default" href="#">Default</a>
                                        <a class="dropdown-item receipt-sort" data-filter="new-receipt" href="#">Newest Receipt</a>
                                        <a class="dropdown-item receipt-sort" data-filter="old-receipt" href="#">Oldest Receipt</a>
                                        <a class="dropdown-item receipt-sort" data-filter="highest-amount" href="#">Highest Amount</a>
                                        <a class="dropdown-item receipt-sort" data-filter="lowest-amount" href="#">Lowest Amount</a>
                                    </div>
                                </div>
                            </li>
                        </div>
                        <div class="col-lg-1">
                            <li>
                                <div class="dropdown" data-toggle="tooltip" data-placement="top" title="Filter"  style="margin-left: -40px;">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  style="width: 50px;">
                                        <i class="fa fa-filter"></i> &nbsp; <span class="filter-text"></span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item quick-filter" data-filter="All" href="#">All Receipts</a>
                                        <a class="dropdown-item quick-filter" data-filter="Fully-Utilized" href="#">Fully Reconciled</a>
                                        <a class="dropdown-item quick-filter" data-filter="Partially-Utilized" href="#">Partially Reconciled</a>
                                        <a class="dropdown-item quick-filter" data-filter="Credit-Available" href="#">Unreconciled</a>
                                        <a class="dropdown-item quick-filter" data-filter="DELETED" href="#">Deleted</a>
                                    </div>
                                </div>
                            </li>
                        </div>
                        <div class="col-lg-3">
                            <li>
                                <div class="input-group pull-right date-range-picker" style="margin-left: -10px;">
                                    <input type="text" class="form-control form-control-sm input date-filter bg-white"  style="margin-left: -30px;" readonly placeholder="Date range" />
                                </div>
                            </li>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>



    <div class="row">

            <?php
// $sql = "SELECT re.id as recptid,re.pdf_file_path, re.recpt_id, re.receipt_date, re.paid_amount, re.payment_mode, i.invoice_code, cm.customerName, cm.email,  cm.id AS cm_id, i.id FROM receipts re LEFT JOIN invoice i ON re.invoice_id = i.id LEFT JOIN customer_master cm ON re.customer_id = cm.id";

    $sql = "SELECT 
    re.id AS recptid,
    re.pdf_file_path,
    re.voucher_id,
    re.voucher_date,
    re.paid_amount,
    re.payment_mode,
    cm.customerName,
    cm.email,
    cm.id AS cm_id,
    i.id as invoice_id,
    COALESCE(i.invoice_code, GROUP_CONCAT(DISTINCT i2.invoice_code)) AS invoice_code

FROM voucher re
LEFT JOIN pi_invoice i ON re.voucher_id = i.id -- Directly linked invoice (single invoice case)
LEFT JOIN voucher_reconciliation r ON re.id = r.voucher_id -- Reconciliation table for multiple invoices
LEFT JOIN pi_invoice i2 ON r.pi_invoice_id = i2.id -- Fetch invoice codes for multiple reconciled invoices
LEFT JOIN customer_master cm ON re.customer_id = cm.id -- Customer details
GROUP BY re.id
ORDER BY re.id, re.voucher_date;
";

$result = $conn->query($sql);
?>
            <div class="col-lg-9" >
    <div class="card" style="border-radius: 5px;">
    <div class="container-fluid">
        <table class="table table-bordered mt-3" id="receipt-datatable">
            <thead>
                <tr style="text-align: center;">
                    <th>Payee</th>
                    <th>Number</th>
                    <th>Amount</th>
                    <th>Accounting</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
       echo "<tr>
        <td>
            <a href='customer-details-info.php?ctmr_id=" . $row['cm_id'] . "'>" . $row["customerName"] . "</a><br>" . $row["email"] . "
        </td>
        <td>
            <a href='view-voucher-action.php?voucherId=" . $row['recptid'] . "'>" . $row["voucher_id"] . "</a><br>" . $row["voucher_date"] . "
        </td>
        <td>
            INR " . $row["paid_amount"] . "<br>" . $row["payment_mode"] . "
        </td>
        <td>
            <a href='view-pinvoice-action.php?inv_id=" . $row['invoice_id'] . "'>" . $row["invoice_code"] . "</a><br>
            <a href='#' style='color: blue;'>Update Accounting</a>
        </td>
        <td>
            <a href='" . $row['pdf_file_path'] . "' class='btn-sm btn btn-primary' download>
                <i class='fa fa-download'></i>
            </a>
            <a href='mail-quotation.php?id=" . $row['recptid'] . "&qcode=" . $row['invoice_code'] . "' class='btn btn-primary btn-sm'>
                <i class='fa fa-envelope'></i>
            </a>
        </td>
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
        <div class="col-lg-3">
            <div class="card" style="border-radius: 5px;">
                <div class="row mt-3" style="font-size: 15px;" >
                <div class="col-lg-9">
                  <div class="notification-item-details mx-4">
                    <div class="notification-item-title">Total</div>
                  </div>
                  </div>
                  <div class="col-lg-3">
                  <a href="#" class="kt-font-primary count_font kt-font-bold mx-3" style="color: blue;"><span id="receipt_amount">0</span></a>
                  </div>
                </div>
                <br>
                <div class="row" style="font-size: 15px;" >
                <div class="col-lg-9">
                  <div class="notification-item-details mx-4">
                    <div class="notification-item-title">Reconciled</div>
                  </div>
                  </div>
                  <div class="col-lg-3">
                  <a href="#" class="kt-font-primary count_font kt-font-bold mx-3" style="color: green;"><span id="receipt_amount">0</span></a>
                  </div>
                </div>
                <br>
                <div class="row mb-3" style="font-size: 15px;" >
                <div class="col-lg-9">
                  <div class="notification-item-details mx-4">
                    <div class="notification-item-title">Unreconciled</div>
                  </div>
                  </div>
                  <div class="col-lg-3">
                  <a href="#" class="kt-font-primary count_font kt-font-bold mx-3" style="color: red;"><span id="receipt_amount">0</span></a>
                  </div>
                </div>
                
                </div>
                   <!-- <div class="col-lg-3"> -->
            <div class="card" style="border-radius: 5px;">
                <div class="portlet portlet-responsive-mobile page-1">
                    <div class="portlet-head mt-3 mx-3">
                        <div class="portlet-head-label">
                            <h3 class="portlet-head-title" style="font-size:18px;">Revenue</h3>
                        </div>
                        <div class="portlet-head-toolbar"></div>
                    </div>
                    <hr>
                    <div class="portlet-body p-3">
                        <div class="notification rec-menu">
                           
                            <br>
                            <div class="dropright">
                                <a href="#" class="notification-item" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <div class="row align-items-center">
                                        <div class="col-lg-2">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="col-lg-8"  style="color: black;">
                                            <div class="notification-item-details">
                                                <div class="notification-item-title">Manage</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item invoice-type" data-doc-type="estimate" href="view-quotation.php">Estimates</a>
                                    <a class="dropdown-item invoice-type" data-doc-type="invoice" href="view-invoices.php">Invoices</a>
                                    <a class="dropdown-item invoice-type" data-doc-type="billofsupply" href="#">Bill of
                                        Supply</a>
                                    <a class="dropdown-item invoice-type" data-doc-type="creditnote" href="#">Credit
                                        Note</a>
                                    <a class="dropdown-item invoice-type" data-doc-type="deliverychallan" href="#">Delivery
                                        Challan</a>
                                </div>
                            </div>
                            <br>
                            <div class="dropright">
                                <a href="#" class="notification-item" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <div class="row align-items-center">
                                        <div class="col-lg-2">
                                            <i class="fas fa-filter"></i>
                                        </div>
                                        <div class="col-lg-8"  style="color: black;">
                                            <div class="notification-item-details">
                                                <div class="notification-item-title">Filter Receipt</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item quick-filter" data-filter="All" href="#">All Receipts</a>
                                    <a class="dropdown-item quick-filter" data-filter="Fully_Utilized" href="#">Fully
                                        Reconciled</a>
                                    <a class="dropdown-item quick-filter" data-filter="Partially_Utilized" href="#">Partially
                                        Reconciled</a>
                                    <a class="dropdown-item quick-filter" data-filter="Credit_Available" href="#">Uneconciled</a>
                                    <a class="dropdown-item quick-filter" data-filter="DELETED" href="#">Deleted</a>
                                </div>
                            </div>
                            <br>
                            <div class="dropright">
                                <a href="#" class="notification-item" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <div class="row align-items-center">
                                        <div class="col-lg-2">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div class="col-lg-8"  style="color: black;">
                                            <div class="notification-item-details">
                                                <div class="notification-item-title">Payments</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item quick-filter" data-filter="All" href="#">All Receipts</a>
                                    <a class="dropdown-item quick-filter" data-filter="Fully_Utilized" href="#">Fully
                                        Reconciled</a>
                                    <a class="dropdown-item quick-filter" data-filter="Partially_Utilized" href="#">Partially
                                        Reconciled</a>
                                    <a class="dropdown-item quick-filter" data-filter="Credit_Available" href="#">Uneconciled</a>
                                    <a class="dropdown-item quick-filter" data-filter="DELETED" href="#">Deleted</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div> -->
            </div>

        

        </div>
    </div>


  <!-- <script src="assets/js/bootstrap.min.js"></script> -->
  <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>

  
</body>
</html>