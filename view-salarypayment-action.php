<!DOCTYPE html>
<?php
session_start(); 
include("config.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
$voucherId = $_GET['voucherId'];

$query = "SELECT sp.*, em.*, em.name AS employee_name
FROM salary_payments sp
JOIN employees_data em ON sp.employee = em.id
WHERE sp.id = $voucherId";

$result = $conn->query($query);

// Fetch the data in a loop
$data = array(); // Initialize an array to store the fetched data

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Store each row in the array
    }
} else {
    // Handle the case where the query fails
    echo "Error: " . $conn->error;
}

if (count($data) > 0) {
    $row = $data[0]; // Use the first row from the fetched data
} else {
    // Handle the case where no data is found
    echo "No data found for the given voucher ID.";
    exit();
}
?>

<html lang="en">
<head>
    <title>iiiQbets</title>

    <meta charset="utf-8">
    <?php include("header_link.php");?>

    <style>
    .text-grey {
    color: grey; /* or any other shade of grey you prefer */
    background-color: white;
}
.tooltip-inner  {
    background-color: white;
    color: grey; /* Set text color to black or your preferred color */
    border-radius: 2px;
    font-size: 13px;
}

h5{
    font-size: 13px !important;
}
</style>

</head>

<body class="">
    <?php include("menu.php");?>
    
    <?php include("createReceiptModal.php");?>

 <!-- [ breadcrumb ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
       
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">View Voucher</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Voucher</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->

    
<div class="card" style="padding: 5px;">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <ul class="nav">
                <li class="nav-item mt-2">
                <h5><a href="#" class="employee_name text-primary" name="employee_name" id="employee_name" style="font-size: 19px;">Employee: <?php echo $row['employee_name']; ?></a></h5>
                </li>
            </ul>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center">
            <ul class="nav">
                <li class="nav-item">
                    <div class="btn-group" role="group">
                        
                    <div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-share-alt text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
        <a class="dropdown-item" href="#">Share Via WhatsApp</a>
        <a class="dropdown-item" href="#">Share via Email</a>
    </div>
</div>
<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
        <a class="dropdown-item" href="edit_salarypayment.php?edit_id=<?php echo $voucherId; ?>">Edit Voucher</a>
        <!-- <a class="dropdown-item" href="#">Cancel</a> -->
        <a class="dropdown-item" href="delete_salarypayment.php?voucherId=<?php echo $row['voucherId']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
        <!-- <a class="dropdown-item" href="#">Delete Permanent</a> -->
    </div>
</div>

                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 mt-2">
        <div class="card">
        <div class="panel panel-default" >
            <div class="panel-body" style="border: 1px solid black;padding: 10px;border-radius: 4px;">
                <div class="row">
                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 text-left mt-3">
                        <a style="text-decoration: none !important;">
                            <h5 class="line-height-70"><b id="seller_name" style=" color: blue;">KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</b></h5>
                        </a>
                        <h5 id="seller_add_1" class="line-height-70">120 Newport Center Dr, Newport Beach, CA 92660</h5>
                        <h5 id="seller_add_2" class="line-height-70"></h5>
                        <h5 id="seller_add_3" class="line-height-70">GST : 29AAICK7493G1ZX </h5>
                        <h5 id="seller_email" class="line-height-70"> Email: sales.usa@iiiqbets.com </h5>
                        <h5 id="seller_mobile" class="line-height-70">Phone: 91 7550705070 </h5>
                        
                    </div>
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-right">
                        <h4 class="line-height-70" style="margin-top: 5px;">Voucher</h4>
                        <h5 class="line-height-70"> <b> VOUCHER NUMBER #: <span id="voucherNumber" name="voucherNumber"><?php echo $row['voucherNumber']?></span></b></h5>
                        <h5 class="line-height-70">Date: <span id="payment_date"  name="payment_date" ><?php echo $row['payment_date']?></span></h5>
                    <p id="inv_added_by">Created By: <?php echo $row['created_by']?></p>
                        
                    </div>
                </div>
                <hr style="margin-top: 11px; margin-bottom: 0px; color: black; border-color: #676767;">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-left mt-3">
                        <h5><b>Employee</b></h5>
                        <h8><span class="" id="employee_name"><?php echo $row['employee_name']?></span></h8>
                        <h6><span class="line-height-70" id="office_mail"><?php echo $row['officemail'];?></span></h6>
                    </div>
                </div>
                <div class="row mt-1" style="padding: 1px;">
                    <div id="charges_div" class="col-xs-12 col-md-12 col-lg-12">
                        <table class="table-responsive table-condensed table table-bordered" style="text-size: 15px;">
                            <thead class="thead-default" style="background-color: lightgrey;">
                                <tr>
                                    <th class="text-center description" style="width: 600px;">Description</th>
                                    <th class="text-center amount" style="width: 200px;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="font-size: 16px;" >
                                    <td class="text-left description">
                                        Paid to an amount of Rs.
                                        <?php echo  $row['amount']; ?> through <?php echo  $row['payment_mode']; ?><br>
                                        <div class="expense-type" style="font-size: small;"><b>Expense Type-</b> Salary & Wages </div>
                                    </td>
                                    <td class="text-center amount"><span>Rs.<?php echo  $row['amount']; ?></span>.00</td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-direction: row;">
                            <p style="margin-right: auto;"><b>Notes:</b></p>
                            <p style="margin-left: auto "><b>For</b> <?php echo $row['employee_name']?></p>
                        </div>
                        <p style="margin-top: 80px; text-align: right;"><b>Authorised Signatory</b></p>
                        <hr style="margin-top: 1px; margin-bottom: 0px; color: black; border-color: #000;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 mx-auto" style="width: 300px;">
                        <p class="text-center my-3">Thank you for your business!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="col-lg-4 col-md-4 col-sm-4 mt-2">
        <div class="card">
            
        </div>
    </div>
    
    <script>
        function printPDF(pdfFilePath) {
            var printWindow = window.open(pdfFilePath, '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    </script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>

</body>
</html>
