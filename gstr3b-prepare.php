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
 
<?php
// session_start();
// include("config.php"); // Database connection

$selected_gstin = $_SESSION['sel_gstin'] ?? '';
$current_month = date('Y-m'); // Format: YYYY-MM

// Fetch Outward Supplies (Sales)
$sql_sales = "SELECT 
    SUM(ii.total) AS total_taxable_sales, 
    SUM(ii.igst) AS total_igst, 
    SUM(ii.cgst) AS total_cgst, 
    SUM(ii.sgst) AS total_sgst, 
    SUM(ii.cess_amount) AS total_cess
    FROM invoice i
    JOIN invoice_items ii ON i.id = ii.invoice_id
    WHERE i.branch_id IN (SELECT branch_id FROM add_branch WHERE GST = ?) 
    AND DATE_FORMAT(i.invoice_date, '%Y-%m') = ?";

$stmt = $conn->prepare($sql_sales);
$stmt->bind_param("ss", $selected_gstin, $current_month);
$stmt->execute();
$result_sales = $stmt->get_result();
$sales_data = $result_sales->fetch_assoc();

// Assigning values
$total_taxable_sales = $sales_data['total_taxable_sales'] ?? 0;
$total_igst_sales = $sales_data['total_igst'] ?? 0;
$total_cgst_sales = $sales_data['total_cgst'] ?? 0;
$total_sgst_sales = $sales_data['total_sgst'] ?? 0;
$total_cess_sales = $sales_data['total_cess'] ?? 0;



// Fetch ITC (Input Tax Credit from Purchase Invoices)
$sql_itc = "SELECT 
    SUM(pii.total) AS total_taxable_purchases, 
    SUM(pii.igst) AS total_igst, 
    SUM(pii.cgst) AS total_cgst, 
    SUM(pii.sgst) AS total_sgst, 
    SUM(pii.cess_amount) AS total_cess
    FROM pi_invoice pi
    JOIN pi_invoice_items pii ON pi.id = pii.invoice_id
    WHERE pi.branch_id IN (SELECT branch_id FROM add_branch WHERE GST = ?) 
    AND DATE_FORMAT(pi.invoice_date, '%Y-%m') = ?";

$stmt = $conn->prepare($sql_itc);
$stmt->bind_param("ss", $selected_gstin, $current_month);
$stmt->execute();
$result_itc = $stmt->get_result();
$itc_data = $result_itc->fetch_assoc();

// Assigning values
$total_taxable_purchases = $itc_data['total_taxable_purchases'] ?? 0;
$total_igst_itc = $itc_data['total_igst'] ?? 0;
$total_cgst_itc = $itc_data['total_cgst'] ?? 0;
$total_sgst_itc = $itc_data['total_sgst'] ?? 0;
$total_cess_itc = $itc_data['total_cess'] ?? 0;


$api_response = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['prepare_return'])) {

    function generateRequestId() {
        return uniqid('req_', true); // Generates unique request ID with a random suffix
    }

    // $selected_gstin = "33GSPTN0292G1Z9"; // Hardcoded for testing; Replace with your GSTIN variable
    // $ret_period = "082017"; // Hardcoded for testing; Replace with dynamic return period
    $requestid = generateRequestId();
    
    $api_url = "https://gsp.adaequare.com/test/enriched/returns/gstr3b?action=RETSAVE";

    // Prepare request body as per Postman
    $api_data = [
        "gstin" => $selected_gstin,
        "ret_period" => $ret_period,
        "refclm" => [
            "igrfclm" => ["tax" => 10, "intr" => 20, "pen" => 30, "fee" => 40, "oth" => 50],
            "cgrfclm" => ["tax" => 20, "intr" => 30, "pen" => 40, "fee" => 50, "oth" => 60],
            "sgrfclm" => ["tax" => 30, "intr" => 40, "pen" => 50, "fee" => 60, "oth" => 70],
            "csrfclm" => ["tax" => 40, "intr" => 50, "pen" => 60, "fee" => 70, "oth" => 80],
            "bankacc" => 123456
        ]
    ];

    $json_data = json_encode($api_data);

    // Define headers as per Postman
    $headers = [
        "username: Adaequare.TN.2",
        "state-cd: 33",
        "otp: 575757",
        "Content-Type: application/json",
        "requestid: $requestid",
        "gstin: $selected_gstin",
        "ret_period: $ret_period",
        "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZSI6WyJnc3AiXSwiZXhwIjoxNzQwMjQzMDE5LCJhdXRob3JpdGllcyI6WyJST0xFX1NCX0VfQVBJX0VXQiIsIlJPTEVfU0JfRV9BUElfR1NUX1JFVFVSTlMiLCJST0xFX1NCX0VfQVBJX0dTVF9DT01NT04iLCJST0xFX1NCX0VfQVBJX0VJIl0sImp0aSI6ImYzZDk5NmU2LWI0M2UtNDIyNC05OTc2LWE1NTkzNTdiMGE5ZCIsImNsaWVudF9pZCI6IkNCQjcwMkZGQzc5NTQyNzZCQkQ4REZFNjM2RjcxN0RGIn0.kEe2fwLhZCG_4tmeBonLxDgfXxVujNJu9tlEJ9BVsys"
    ];

    // Initialize cURL session
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable debugging

    // Execute API request
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check for errors
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
    }
    curl_close($ch);

    // Print Response
    echo "<pre><b>API URL:</b> $api_url</pre>";
    echo "<pre><b>Request Headers:</b>\n" . print_r($headers, true) . "</pre>";
    echo "<pre><b>JSON Payload Sent:</b>\n" . htmlspecialchars($json_data) . "</pre>";
    echo "<pre><b>Response Code:</b> $http_code</pre>";
    echo "<pre><b>Response:</b> " . htmlspecialchars($response) . "</pre>";

    if (isset($error_msg)) {
        echo "<pre><b>cURL Error:</b> " . $error_msg . "</pre>";
    }
}

?>


<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php");?>
   
<style>
        /* Custom Button Styles */
        .btn-group .btn {
            border: 1px solid #0d6efd;
            color: #0d6efd;
            background-color: white;
            font-weight: 500;
        }

        .btn-group .btn.active, 
        .btn-group .btn:hover {
            background-color: #0d6efd;
            color: white;
        }

        /* Style for all outlined buttons */
        .custom-btn {
            border: 1px solid #0d6efd;
            color: #0d6efd;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .custom-btn:hover, 
        .custom-btn:focus {
            background-color: #0d6efd;
            color: white;
        }

        /* Make sure all buttons align properly */
        .btn-group, .action-buttons {
            display: flex;
            flex-wrap: wrap;
/*            gap: 10px;*/
padding:0px 0px 0px 15px !important;
        }
        .btn-sm{
            padding:3px 0px !important;
        }
    </style>
    <style>
    .table th:first-child, 
    .table td:first-child {
        width: 35%; /* Reduce width of the first column */
        white-space: normal; /* Allow text to wrap */
        word-wrap: break-word; /* Ensure text wraps properly */
    }

    .table th, .table td {
        text-align: left; /* Center align all columns except first */
        vertical-align: middle;
        padding: 5px;
        font-size: 13px;
        font-family: Poppins, Helvetica, sans-serif;
    }

    /* Adjusting table responsiveness */
    .table-responsive {
        overflow-x: auto; /* Ensures horizontal scroll if needed */
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
                            <h4 class="m-b-10">View Quotation</h4>
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

<?php include("taxation_submenu.php");?>


 <div class="container bg-white p-3 rounded">
        <div class="row">
            <!-- GST Tab Buttons -->
            <div class="col-md-4 mt-3 btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary">IFF</button>
                <button type="button" class="btn btn-sm btn-outline-primary">GSTR1</button>
                <button type="button" class="btn btn-sm btn-primary active">GSTR3B</button>
            </div>
        

        <!-- Action Buttons (First Row) -->
        <div class="col-md-4 mt-3 btn-group" role="group">
            <button type="button" class="btn btn-sm custom-btn">Import Invoice</button>
            <button type="button" class="btn btn-sm custom-btn">Reconcile ITC</button>
        </div>

        <!-- Action Buttons (Second Row) -->
        <div class="col-md-4 mt-3 btn-group" role="group">
            <!-- <button type="button" class="btn btn-sm custom-btn">Prepare Return</button> -->
            
<form method="post">
                <button type="submit" name="prepare_return" class="btn btn-sm custom-btn">Prepare Return</button>
            </form>
            <button type="button" class="btn btn-sm ">Upload Return</button>
            <button type="button" class="btn btn-sm ">Offset Liability</button>
        </div>
        </div>
    </div>


  <!-- GST Table -->
            <div class="container mt-4 bg-white p-3 rounded">
             <div class="table-responsive">
    <h5 style="color: #0497e1">Outward Supplies and Inward Supplies Liable to Reverse Charge</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nature of Supplies</th>
                <th>Total Taxable</th>
                <th>Total IGST</th>
                <th>Total CGST</th>
                <th>Total SGST</th>
                <th>Total CESS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>(a) Outward taxable supplies (other than zero-rated, nil-rated, and exempted)</td>
                <td><?php echo number_format($total_taxable_sales, 2); ?></td>
                <td><?php echo number_format($total_igst_sales, 2); ?></td>
                <td><?php echo number_format($total_cgst_sales, 2); ?></td>
                <td><?php echo number_format($total_sgst_sales, 2); ?></td>
                <td><?php echo number_format($total_cess_sales, 2); ?></td>
            </tr>
            <tr>
                <td>(b) Outward taxable supplies (zero-rated)</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
            <tr>
                <td>(c) Other outward supplies (Nil-rated, exempted)</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
            <tr>
                <td>(d) Inward supplies (liable to reverse charge)</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
            <tr>
                <td>(e) Non-GST outward supplies</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
        </tbody>
    </table>
</div>
 </div>

 <div class="container mt-4 bg-white p-3 rounded">
     <div class="table-responsive">
    <h5 style="color: #0497e1">Eligible Input Tax Credit (ITC)</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Details</th>
                <th>IGST</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>CESS</th>
            </tr>
        </thead>
        <tbody>
            <tr><td><strong>(A) ITC Available</strong></td><td colspan="4"></td></tr>
            <tr>
                <td>(1) Import of goods</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
            <tr>
                <td>(2) Import of services</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
            <tr>
                <td>(3) Inward supplies liable to reverse charge</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
            <tr>
                <td>(4) Inward supplies from ISD</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
            <tr>
                <td>(5) All other ITC</td>
                <td><?php echo number_format($total_igst_itc, 2); ?></td>
                <td><?php echo number_format($total_cgst_itc, 2); ?></td>
                <td><?php echo number_format($total_sgst_itc, 2); ?></td>
                <td><?php echo number_format($total_cess_itc, 2); ?></td>
            </tr>
            
            <tr><td><strong>(B) ITC Reversed</strong></td><td colspan="4"></td></tr>
            <tr>
                <td>(1) As per Rule 42 & 43 of CGST/SGST</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
            <tr>
                <td>(2) Others</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>

            <tr><td><strong>(C) Net ITC Available (A) - (B)</strong></td>
                <td><?php echo number_format($total_igst_itc, 2); ?></td>
                <td><?php echo number_format($total_cgst_itc, 2); ?></td>
                <td><?php echo number_format($total_sgst_itc, 2); ?></td>
                <td><?php echo number_format($total_cess_itc, 2); ?></td>
            </tr>

            <tr><td><strong>(D) Ineligible ITC</strong></td><td colspan="4"></td></tr>
            <tr>
                <td>(1) As per section 17(5)</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
            <tr>
                <td>(2) Others</td>
                <td>0.00</td><td>0.00</td><td>0.00</td><td>0.00</td>
            </tr>
        </tbody>
    </table>
</div>

 </div>
         <?= $api_response; ?>
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
