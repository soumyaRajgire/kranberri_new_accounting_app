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
ORDER BY  re.voucher_id DESC";

$result = $conn->query($sql);
?>
            <div class="col-lg-12" >
    <div class="card" style="border-radius: 5px;">
        <div class="card-header" >
                <h5>View Voucher</h5>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; margin-top: 30px;">
                        <!-- Form for Month and Year Selection -->
                        <form class="form-inline" method="POST" action="download_voucher_monthly.php" style="display: flex; align-items: center;">
                            <label>Select Month and Year Data:</label>
                            <select class="form-control" id="month" name="month" style="margin-right: 10px; width: auto;">
                                <?php
                                $current_month = date("m");
                                for ($month = 1; $month <= 12; $month++) {
                                    $selected = ($current_month == $month) ? 'selected' : '';
                                    echo "<option value=\"$month\" $selected>" . date('F', mktime(0, 0, 0, $month, 1)) . "</option>";
                                }
                                ?>
                            </select>
                            <select class="form-control" id="year" name="year" style="margin-right: 10px; width: auto;" required>
                                <?php
                                $current_year = date("Y");
                                for ($i = $current_year; $i >= 2017; $i--) {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-success" name="download_month">
                                <i class="fa fa-download"></i> Download
                            </button>
                        </form>
                        
                        <!-- Form for Date Range Selection -->
                        <form class="form-inline" method="POST" action="download_voucher_range.php" style="display: flex; align-items: center; margin-right: 10px;">
                            <label style="margin-right: 10px;">Select Date Range:</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" required style="margin-right: 10px; width: auto;" value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
                            <input type="date" class="form-control" id="to_date" name="to_date" required style="margin-right: 10px; width: auto;" value="<?php echo date('Y-m-d'); ?>">
                            <button type="submit" class="btn btn-success" name="download_range">
                                <i class="fa fa-download"></i> Download Range
                            </button>
                        </form>
                        <a href="#" data-toggle="modal" data-target="#newVoucherModal" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Open Link">Create</a>
                    </div>
            </div>
    <div class="card-body table-border-style">
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
        <!--<div class="col-lg-3">-->
        <!--    <div class="card" style="border-radius: 5px;">-->
        <!--        <div class="row mt-3" style="font-size: 15px;" >-->
        <!--        <div class="col-lg-9">-->
        <!--          <div class="notification-item-details mx-4">-->
        <!--            <div class="notification-item-title">Total</div>-->
        <!--          </div>-->
        <!--          </div>-->
        <!--          <div class="col-lg-3">-->
        <!--          <a href="#" class="kt-font-primary count_font kt-font-bold mx-3" style="color: blue;"><span id="receipt_amount">0</span></a>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--        <br>-->
        <!--        <div class="row" style="font-size: 15px;" >-->
        <!--        <div class="col-lg-9">-->
        <!--          <div class="notification-item-details mx-4">-->
        <!--            <div class="notification-item-title">Reconciled</div>-->
        <!--          </div>-->
        <!--          </div>-->
        <!--          <div class="col-lg-3">-->
        <!--          <a href="#" class="kt-font-primary count_font kt-font-bold mx-3" style="color: green;"><span id="receipt_amount">0</span></a>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--        <br>-->
        <!--        <div class="row mb-3" style="font-size: 15px;" >-->
        <!--        <div class="col-lg-9">-->
        <!--          <div class="notification-item-details mx-4">-->
        <!--            <div class="notification-item-title">Unreconciled</div>-->
        <!--          </div>-->
        <!--          </div>-->
        <!--          <div class="col-lg-3">-->
        <!--          <a href="#" class="kt-font-primary count_font kt-font-bold mx-3" style="color: red;"><span id="receipt_amount">0</span></a>-->
        <!--          </div>-->
        <!--        </div>-->
                
        <!--        </div>-->
                   <!-- <div class="col-lg-3"> -->
        <!--    <div class="card" style="border-radius: 5px;">-->
        <!--        <div class="portlet portlet-responsive-mobile page-1">-->
        <!--            <div class="portlet-head mt-3 mx-3">-->
        <!--                <div class="portlet-head-label">-->
        <!--                    <h3 class="portlet-head-title" style="font-size:18px;">Revenue</h3>-->
        <!--                </div>-->
        <!--                <div class="portlet-head-toolbar"></div>-->
        <!--            </div>-->
        <!--            <hr>-->
        <!--            <div class="portlet-body p-3">-->
        <!--                <div class="notification rec-menu">-->
                           
        <!--                    <br>-->
        <!--                    <div class="dropright">-->
        <!--                        <a href="#" class="notification-item" data-toggle="dropdown"-->
        <!--                            aria-haspopup="true" aria-expanded="false">-->
        <!--                            <div class="row align-items-center">-->
        <!--                                <div class="col-lg-2">-->
        <!--                                    <i class="fas fa-file-alt"></i>-->
        <!--                                </div>-->
        <!--                                <div class="col-lg-8"  style="color: black;">-->
        <!--                                    <div class="notification-item-details">-->
        <!--                                        <div class="notification-item-title">Manage</div>-->
        <!--                                    </div>-->
        <!--                                </div>-->
        <!--                                <div class="col-lg-2">-->
        <!--                                    <i class="fas fa-chevron-right"></i>-->
        <!--                                </div>-->
        <!--                            </div>-->
        <!--                        </a>-->
        <!--                        <div class="dropdown-menu">-->
        <!--                            <a class="dropdown-item invoice-type" data-doc-type="estimate" href="view-quotation.php">Estimates</a>-->
        <!--                            <a class="dropdown-item invoice-type" data-doc-type="invoice" href="view-invoices.php">Invoices</a>-->
        <!--                            <a class="dropdown-item invoice-type" data-doc-type="billofsupply" href="#">Bill of-->
        <!--                                Supply</a>-->
        <!--                            <a class="dropdown-item invoice-type" data-doc-type="creditnote" href="#">Credit-->
        <!--                                Note</a>-->
        <!--                            <a class="dropdown-item invoice-type" data-doc-type="deliverychallan" href="#">Delivery-->
        <!--                                Challan</a>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                    <br>-->
        <!--                    <div class="dropright">-->
        <!--                        <a href="#" class="notification-item" data-toggle="dropdown"-->
        <!--                            aria-haspopup="true" aria-expanded="false">-->
        <!--                            <div class="row align-items-center">-->
        <!--                                <div class="col-lg-2">-->
        <!--                                    <i class="fas fa-filter"></i>-->
        <!--                                </div>-->
        <!--                                <div class="col-lg-8"  style="color: black;">-->
        <!--                                    <div class="notification-item-details">-->
        <!--                                        <div class="notification-item-title">Filter Receipt</div>-->
        <!--                                    </div>-->
        <!--                                </div>-->
        <!--                                <div class="col-lg-2">-->
        <!--                                    <i class="fas fa-chevron-right"></i>-->
        <!--                                </div>-->
        <!--                            </div>-->
        <!--                        </a>-->
        <!--                        <div class="dropdown-menu dropdown-menu-right">-->
        <!--                            <a class="dropdown-item quick-filter" data-filter="All" href="#">All Receipts</a>-->
        <!--                            <a class="dropdown-item quick-filter" data-filter="Fully_Utilized" href="#">Fully-->
        <!--                                Reconciled</a>-->
        <!--                            <a class="dropdown-item quick-filter" data-filter="Partially_Utilized" href="#">Partially-->
        <!--                                Reconciled</a>-->
        <!--                            <a class="dropdown-item quick-filter" data-filter="Credit_Available" href="#">Uneconciled</a>-->
        <!--                            <a class="dropdown-item quick-filter" data-filter="DELETED" href="#">Deleted</a>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                    <br>-->
        <!--                    <div class="dropright">-->
        <!--                        <a href="#" class="notification-item" data-toggle="dropdown"-->
        <!--                            aria-haspopup="true" aria-expanded="false">-->
        <!--                            <div class="row align-items-center">-->
        <!--                                <div class="col-lg-2">-->
        <!--                                    <i class="fas fa-money-bill-wave"></i>-->
        <!--                                </div>-->
        <!--                                <div class="col-lg-8"  style="color: black;">-->
        <!--                                    <div class="notification-item-details">-->
        <!--                                        <div class="notification-item-title">Payments</div>-->
        <!--                                    </div>-->
        <!--                                </div>-->
        <!--                                <div class="col-lg-2">-->
        <!--                                    <i class="fas fa-chevron-right"></i>-->
        <!--                                </div>-->
        <!--                            </div>-->
        <!--                        </a>-->
        <!--                        <div class="dropdown-menu dropdown-menu-right">-->
        <!--                            <a class="dropdown-item quick-filter" data-filter="All" href="#">All Receipts</a>-->
        <!--                            <a class="dropdown-item quick-filter" data-filter="Fully_Utilized" href="#">Fully-->
        <!--                                Reconciled</a>-->
        <!--                            <a class="dropdown-item quick-filter" data-filter="Partially_Utilized" href="#">Partially-->
        <!--                                Reconciled</a>-->
        <!--                            <a class="dropdown-item quick-filter" data-filter="Credit_Available" href="#">Uneconciled</a>-->
        <!--                            <a class="dropdown-item quick-filter" data-filter="DELETED" href="#">Deleted</a>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
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

  
 <script type="text/javascript">
    $(document).ready(function () {
        $('#receipt-datatable').DataTable({
            "ordering": false // Disable sorting completely
        });
    });
</script>
</body>
</html>