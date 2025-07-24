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
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .receipt-container {
            border: 2px solid #676767;
            padding: 15px;
            margin: 20px;
        }

        .receipt-header {
            border-bottom: 1px solid #676767;
            margin-bottom: 15px;
        }

        .payor-section {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .charges-table {
            margin-top: 15px;
          
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 14px;
        }

        .footer-section {
            margin-top: 10px;
            border-top: 1px solid #676767;
            padding-top: 10px;
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
                            <h4 class="m-b-10">Receipt</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Receipt</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="container">
        <div class="receipt-container">
            <div class="receipt-header">
                <div class="row">
                    <div class="col-md-7">
                        <a href="#" style="text-decoration: none !important;">
                            <h5 class="line-height-70"><b id="seller_name">VAMSHI KRISHNA  PATTABHI</b></h5>
                        </a>
                        <h5 id="seller_add_1" class="line-height-70"></h5>
                        <h5 id="seller_add_2" class="line-height-70"></h5>
                        <h5 id="seller_add_3" class="line-height-70"></h5>
                        <h5 id="seller_add_4" class="line-height-70"></h5>
                    </div>
                    <div class="col-md-5 text-right">
                        <h4 style="margin-top:5px;">RECEIPT</h4>
                        <h5><b>Receipt #: <span id="inv_no">2023-1</span></b></h5>
                        <h5 class="line-height-70">Receipt Date: <span id="inv_date">01-12-2023</span></h5>
                        <p id="inv_delete_status" style="display:none;">1</p>
                        <p id="inv_added_by">Created By: KRISHNA VAMSHI</p>
                    </div>
                </div>
            </div>

            <div class="row payor-section">
                <div class="col-md-6">
                    <h4><b>Payor</b></h4>
                    <h6><span id="cust_name">krishna</span></h6>
                    <h6><span id="cust_mail">krishnavamshi927917@gmail.com</span></h6>
                    <h6><span>GSTIN: 36AAIFH7981F1Z0</span></h6>
                </div>
                <div class="col-md-6"></div>
            </div>

            <hr class="my-4">

            <div class="row charges-table">
                <div class="col-12">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th style="width:70%;">Description</th>
                                <th style="width:30%">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <tr class="text-center">
                                <td><p>Received from krishna an amount of Rs.20,000.00 (Twenty thousands) through Direct Deposit</p></td>
                                <td id="total_pay_amt">Rs.20,000.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="my-4">

            <div class="row signature-section">
                <div class="col-12">
                    <p class="text-right"><b>For VAMSHI KRISHNA PATTABHI</b></p>
                </div>
            </div>
            <div class="row signature-section" style="margin-top: 80px;">
                <div class="col-12">
                    <p class="text-right"><b>Authorised Signatory</b></p>
                </div>
            </div>

            <div class="row footer-section">
                <div class="col-12 text-center">
                    <p>This is a computer-generated receipt. Thank you!</p>
                </div>
            </div>
        </div>
    </div>

        <!-- [ Main Content ] End -->
        <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
       <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>

</body>
</html>