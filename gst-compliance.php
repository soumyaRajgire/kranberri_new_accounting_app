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
    <style type="text/css">
        .btn-sm{
            padding:0px 0.1rem;
        }
    </style>
</head>
<body class="">
    <!-- [ Pre-loader ] start -->
     
     <?php include("menu.php");?>
        <?php //include("createReceiptModal.php") ?>
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
                            <!-- <h4 class="m-b-10">View Quotation</h4> -->
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <!-- <li class="breadcrumb-item"><a href="#">View quotation</a></li> -->
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->

     <?php
error_reporting(E_ALL); // Report all errors and warnings
ini_set('display_errors', 1); // Display errors on the screen
?>

  

<?php include("taxation_submenu.php");?>
 <?php


// Define default variables
$sales_total = 0;
$purchase_total = 0;
$gstins = [];
$selected_gstin = $_SESSION['sel_gstin'] ?? '';

// Ensure user role and other session values are set
$user_id = $_SESSION['id'] ?? null;
echo $user_role = $_SESSION['role'] ?? null;
$branch_id = $_SESSION['branch_id'] ?? null;
$selected_gstin = $_SESSION['sel_gstin'] ?? ''; // Default GSTIN

// Define GSTINs array
$gstins = [];

if ($user_role == 'superadmin') {
    // Superadmin: Fetch all GSTINs
    $sql = "SELECT DISTINCT GST FROM add_branch WHERE GST IS NOT NULL AND GST != ''";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $gstins[] = $row['GST'];
    }
} else {
    // Normal user: Fetch only branch GSTIN
   $sql = "SELECT DISTINCT GST FROM add_branch WHERE branch_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $gstins[] = $row['GST'];
    }
}

// Store GSTINs in session
$_SESSION['gstins'] = $gstins;

// Auto-select first GSTIN if none is set
if (empty($selected_gstin) && !empty($gstins)) {
    $_SESSION['sel_gstin'] = $gstins[0];
    $selected_gstin = $gstins[0];
}

// If GSTIN is changed via dropdown
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['gstin_select'])) {
    $_SESSION['sel_gstin'] = $_POST['gstin_select'];
    header("Location: gst-compliance.php"); // Refresh page after selection
    exit();
}


// If a GSTIN is selected (either from dropdown or session), fetch totals
if (!empty($selected_gstin)) {
    $current_month = date('Y-m'); // Current month in YYYY-MM format

    // Fetch total sales
    $sql_sales = "SELECT SUM(total_amount) AS total_sales FROM invoice WHERE branch_id IN (SELECT branch_id FROM add_branch WHERE GST = ?) AND DATE_FORMAT(invoice_date, '%Y-%m') = ?";
    $stmt = $conn->prepare($sql_sales);
    $stmt->bind_param("ss", $selected_gstin, $current_month);
    $stmt->execute();
    $result_sales = $stmt->get_result();
    if ($row = $result_sales->fetch_assoc()) {
        $sales_total = $row['total_sales'] ?? 0;
    }

    // Fetch total purchases
    $sql_purchases = "SELECT SUM(total_amount) AS total_purchases FROM pi_invoice WHERE branch_id IN (SELECT branch_id FROM add_branch WHERE GST = ?) AND DATE_FORMAT(invoice_date, '%Y-%m') = ?";
    $stmt = $conn->prepare($sql_purchases);
    $stmt->bind_param("ss", $selected_gstin, $current_month);
    $stmt->execute();
    $result_purchases = $stmt->get_result();
    if ($row = $result_purchases->fetch_assoc()) {
        $purchase_total = $row['total_purchases'] ?? 0;
    }
}
?>

  <div class="row">
    <div class="col-md-12">
        <div class="card">
            <!-- <div class="card-header">
                <h5>View Quotation Details</h5>
                    <a  href="create-quotation.php" class="btn btn-info" style="color: #fff !important;float:right;" />Create</a>
            </div> -->
            <div class="card-body table-border-style" style="font-size:12px !important">
        <div class="row mt-3">
    <div class="col-md-9">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Sales</th>
                        <th>Purchases</th>
                        <th>GSTR-3B</th>
                        <th>GSTR-1</th>
                        <th>GSTR-2A</th>
                        <th>GST Payment</th>
                        <th>GSTR-3B</th>
                        <th>GSTR-1</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo date('M Y'); ?></td>
                        <td>INR <?php echo number_format($sales_total, 2); ?></td>
                        <td>INR <?php echo number_format($purchase_total, 2); ?></td>
                        <td>Not Filed</td>
                        <td>Not Filed</td>
                        <td><a href="#">Sync</a></td>
                        <td><button class="status-btn">Nil</button></td>
                        <td><a class="btn btn-sm" href="gstr3b-prepare.php">Prepare GSTR-3B</a>
                            <!-- <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Prepare
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">File GSTR-3B</a></li>
                                    <li><a class="dropdown-item" href="gstr3b-prepare.php">Prepare GSTR-3B</a></li>
                                </ul>
                            </div> -->
                        </td>
                        <td><a class="btn btn-sm" href="gstr1-prepare.php">Prepare GSTR-3B</a>
                            <!-- <div class="dropdown">
                                <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Prepare
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">File GSTR-1</a></li>
                                    <li><a class="dropdown-item" href="gstr1-prepare.php">Prepare GSTR-1</a></li>
                                </ul>
                            </div> -->
                        </td>
                    </tr>
                </tbody>          
            </table>
        </div>
    </div>
    
    <!-- Summary Section -->
    <div class="col-md-3">
        <div class="summary-section p-3 mb-4 rounded shadow-sm border">
            <div class="d-flex justify-content-between mb-2">
                <button class="btn btn-primary btn-sm">Invoices</button>
                <button class="btn btn-primary btn-sm">ITC</button>
                <button class="btn btn-primary btn-sm">GST Payable</button>
            </div>
            <h5 class="text-primary mt-3">GST Summary - <?php echo date('F Y'); ?></h5>
            <hr>
            <div class="summary-item d-flex justify-content-between py-2 border-bottom">
                <span class="fw-semibold">Total Sales</span>
                <span class="text-muted">INR <?php echo number_format($sales_total, 2); ?></span>
            </div>
            <div class="summary-item d-flex justify-content-between py-2 border-bottom">
                <span class="fw-semibold">Total Purchases</span>
                <span class="text-muted">INR <?php echo number_format($purchase_total, 2); ?></span>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>
            <!-- [ stiped-table ] end -->
           
        <!-- </div> -->
        <!-- [ Main Content ] end -->
    </div>
</section>




    <!-- Required Js -->

 <!-- <script src="assets/js/jquery.min.js"></script> -->

        <!-- Bootstrap Core JavaScript -->
        <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
       <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            "ordering": false // Disable sorting completely
        });
    });
</script>
</body>
</html>
