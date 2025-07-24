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

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    echo "<script type='text/javascript'>
        alert('$message');
    </script>";
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php");?>
</head>
<body class="">
    <!-- Pre-loader -->
     
     <?php include("menu.php");?>
    
   <!-- Header -->
   <style>
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
</style>

<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">View Customer Information</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;" >
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Customer Information</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
<hr>
    <div class="row">
        <div class="col-lg-3 col-md-3 mx-1">
            <div class="card">
                <div class="card-body">
                    <div class="sticky-sidebar">
                        <div class="portlet">
                            <div class="portlet-body">
                                <div class="wizard-aside">
                                    <div class="wizard-nav div-left-fixed m-3">
                                        <div class="nav-tabs-container">
                                            <ul class="nav nav-tabs" style="border: none;">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#">
                                                        <span class="nav-link-text">Name</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <ul class="nav nav-tabs" style="border: none;">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#">
                                                        <span class="nav-link-text">Individual</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <ul class="nav nav-tabs" style="border: none;">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="#">
                                                        <span class="nav-link-text">Mobile</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="menu-center">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#">
                                                    <span class="nav-link-text">Account Information</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#">
                                                    <span class="nav-text">Accounts Statement</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#">
                                                    <span class="nav-link-text">GST Reconciliation</span>
                                                </a>
                                            </li>
                                            <li class="nav-item mb-2">
                                                <a class="nav-link" data-toggle="tab" href="#">
                                                    <span class="nav-link-text">Bank Reconciliation</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="nav-items">
                                        <ul class="nav nav-tabs mt-2" style="border: none;">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">
                                                    <span class="nav-link-text">New Quote</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="nav nav-tabs" style="border: none;">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">
                                                    <span class="nav-link-text">New Invoice</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="nav nav-tabs" style="border: none;">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">
                                                    <span class="nav-link-text">New Receipt</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="nav nav-tabs" style="border: none;">
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">
                                                    <span class="nav-link-text">New Credit note</span>
                                                </a>
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
        <?php
// Include the database connection configuration
include 'config.php';

// Get the ID from the request
$id = $_GET['ctmr_id']; // Replace with the specific ID you want to fetch

// Execute the SQL query to fetch data from customer_master
$sql_customer = "SELECT
    cm.customerName AS Name,
    cm.email AS Email,
    cm.mobile AS Mobile,
    cm.entityType AS Entity,
    cm.business_name AS BusinessName,
    cm.gstin AS GST,
    cm.pan AS PAN,
    cm.bank_name AS Bank,
    cm.tan AS TAN,
    cm.terms_of_payment AS TermsOfPayment,
    cm.reverse_charge AS TDSApplicable,
    cm.tds_slab_rate AS TDS,
    cm.created_on AS CreatedOn,
    cm.created_by AS CreatedBy,
    cm.id AS ContactID
FROM
    customer_master AS cm
WHERE
    cm.id = $id";

// Execute the SQL query to fetch data from address_master based on customer_master_id
$sql_address = "SELECT
    am.b_address_line1 AS BillingAddress,
    am.s_address_line1 AS ShippingAddress
FROM
    address_master AS am
WHERE
    am.customer_master_id = $id";

$result_customer = $conn->query($sql_customer);
$result_address = $conn->query($sql_address);


// Fetch the data from customer_master
if ($result_customer->num_rows > 0) {
    $row_customer = $result_customer->fetch_assoc();
} else {
    echo "No data found for ID: $id";
}

// Fetch the data from address_master
if ($result_address->num_rows > 0) {
    $row_address = $result_address->fetch_assoc();
}

// Close the database connection
$conn->close();
?>
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mt-2 pt-2 pb-2">
                        <div class="tab-content">
                            <div class="pr-2 pl-2" id="address">
                                <div class="mb-2">
                                    <span style="font-size: 20px; font-weight:bold;">Account Information</span>
                                </div>
                                <table class="table custom-table">
                                    <tbody>
                                        <tr>
                                            <th>Name</th>
                                            <td><?php echo $row_customer['Name']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td><?php echo $row_customer['Email']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Mobile</th>
                                            <td><?php echo $row_customer['Mobile']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Entity</th>
                                            <td><?php echo $row_customer['Entity']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Business Name</th>
                                            <td><?php echo $row_customer['BusinessName']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>GST</th>
                                            <td><?php echo $row_customer['GST']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>PAN</th>
                                            <td><?php echo $row_customer['PAN']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Billing Address
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a style="color:#5867dd; font-size: 14px;" href="#">Add Address</a>
                                                    </div>
                                                </div>
                                            </th>
                                            <td>
                                                <?php echo $row_address['BillingAddress']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Shipping Address
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a style="color:#5867dd; font-size: 14px;" href="#">Add Address</a>
                                                    </div>
                                                </div>
                                            </th>
                                            <td>
                                                <?php echo $row_address['ShippingAddress']; ?>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <th>Bank</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a href="#" style="color:#5867dd; font-weight: bold;">Add Accounts</a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                    <?php echo $row_customer['Bank']; ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>TDS Applicable</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                    <?php echo $row_customer['TDS']; ?>
                                                        <a href="#" style="color:#5867dd; font-weight: bold;">Update TDS</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>TAN</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                    <?php echo $row_customer['TAN']; ?>
                                                        <a href="#" style="color:#5867dd; font-weight: bold;">Update</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Terms of payment</th>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                    <?php echo $row_customer['TermsOfPayment']; ?>
                                                        <a href="#" style="color:#5867dd; font-weight: bold;">Update</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td class="">
                                                <!-- <?php echo $row_address['Status']; ?> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created by</th>
                                            <td class=""><?php echo $row_customer['CreatedBy']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Created on</th>
                                            <td class=""><?php echo $row_customer['CreatedOn']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Contact ID</th>
                                            <td class="">
                                                <!-- <?php echo $row_address['Contact ID']; ?> -->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="del-btn" style="text-align: end;">
                                    <a class="btn btn-outline-danger del-cust" href="delete_contact.php?ctmr_id=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to delete this contact?');" style="width: 19%; padding-left: 0px; padding-right: 0px;">Delete Contact</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <div class="col-lg-3 col-md-3 mx-1">
            <div class="card">
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</section>

    <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>
