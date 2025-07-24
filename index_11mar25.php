<!DOCTYPE html>
<?php
session_start(); 
if(!isset($_SESSION['LOG_IN'])){
   header("Location:login.php");
}
else
{
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- Your JavaScript code -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<style>
         input.form-control, select.form-select {
            background-color: white; /* Set background to white */
            padding-left: 10px; /* Indent placeholder */
        }

        .form-control::placeholder {
            color: #6c757d; /* Optional: Change the placeholder color */
        }

        /* Additional styling for select dropdowns (optional) */
        select.form-select {
            padding-left: 10px;
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
            <!-- <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Dashboard</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr> -->



        <div class="card">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs" id="Tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#rev_chart">Revenue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#exp_chart">Expenses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#comp_chart">Compliance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#bank_chart">Banking</a>
                </li>
            </ul>
        </div>
    </div>
</div>


            <div class="row">
                <div class="col-lg-9 col-md-9">
                    <div class="tab-content">
                        <div id="rev_chart" class="tab-pane fade show active">
                            <!-- Content for Revenue Chart Goes Here -->
                        
    <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
    <h5 class="card-title text-primary">Quotes</h5>
    <p class="card-text" style="margin-top: -15px;">Current Month</p>
    <?php
       
        // Get the current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Get the first and last day of the current month
        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-t');

        // SQL query to fetch the grand_total for the current month
        $sql = "SELECT grand_total FROM quotation WHERE invoice_date >= '$firstDay' AND due_date <= '$lastDay'";

        $result = $conn->query($sql);

        // Check if the query was successful
        if ($result) {
            // Fetch the data
            $row = $result->fetch_assoc();
            
            // Display the grand_total value in the button
            $grandTotal = $row ? $row['grand_total'] : 0;
            echo '<a href="" class="btn btn-warning" style="float: right; margin-top: -60px;">INR ' .  number_format($grandTotal ?? 0, 2) . '</a>';

            // Close the result set
            $result->close();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

       
    ?>
</div>

           
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Invoices</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <!-- <a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR 0</a> -->
                    <?php
       
       // Get the current month and year
       $currentMonth = date('m');
       $currentYear = date('Y');

       // Get the first and last day of the current month
       $firstDay = date('Y-m-01');
       $lastDay = date('Y-m-t');

       // SQL query to fetch the grand_total for the current month
       $sql = "SELECT SUM(grand_total) FROM invoice WHERE invoice_date >= '$firstDay' AND due_date <= '$lastDay'";

       $result = $conn->query($sql);

       // Check if the query was successful
       if ($result) {
           // Fetch the data
           $row = $result->fetch_assoc();
           
           // Display the grand_total value in the button
           $grandTotal = $row ? $row['SUM(grand_total)'] : 0;
           echo '<a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR ' . number_format($grandTotal ?? 0, 2) . '</a>';

           // Close the result set
           $result->close();
       } else {
           echo "Error: " . $sql . "<br>" . $conn->error;
       }

     
   ?>
                </div>
            </div>
        </div>

     
        
    </div>
 
    <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Receipts</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <?php
// Assuming you have a connection object named $conn
    
       // Get the current month and year
       $currentMonth = date('m');
       $currentYear = date('Y');

       // Get the first and last day of the current month
       $firstDay = date('Y-m-01');
     

// SQL query to fetch the grand_total for the current month
$sql = "SELECT SUM(total_amount) FROM receipts WHERE receipt_date >= '$firstDay'";

$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch the data
    $row = $result->fetch_assoc();

    // Display the grand_total value in the button
    $grandTotal = $row ? $row['SUM(total_amount)'] : 0;
    echo '<a href="" class="btn btn-warning" style="float: right; margin-top: -60px;">INR ' . number_format($grandTotal ?? 0, 2) . '</a>';

    // Close the result set
    $result->close();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


?>
                </div>

           
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">Receivables</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR 0</a>
                </div>
            </div>
        </div>

     
        
    </div>

    <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">Unaccounted</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <a href="" class="btn btn-warning" style="float: right; margin-top: -60px;">INR 0</a>
                </div>

           
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">GST Collected</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR 0</a>
                </div>
            </div>
        </div>

     
        
    </div>

                        </div>

                        <div id="exp_chart" class="tab-pane fade">
                            <!-- Content for Expenses Chart Goes Here -->
                            <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">Purchase Orders</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <?php
       
       // Get the current month and year
       $currentMonth = date('m');
       $currentYear = date('Y');

       // Get the first and last day of the current month
       $firstDay = date('Y-m-01');
       $lastDay = date('Y-m-t');

       // SQL query to fetch the grand_total for the current month
       $sql = "SELECT SUM(grand_total) FROM purchase_orders WHERE invoice_date >= '$firstDay' AND due_date <= '$lastDay'";

       $result = $conn->query($sql);

       // Check if the query was successful
       if ($result) {
           // Fetch the data
           $row = $result->fetch_assoc();
           
           // Display the grand_total value in the button
           $grandTotal = $row ? $row['SUM(grand_total)'] : 0;
           echo '<a href="" class="btn btn-warning" style="float: right; margin-top: -60px;">INR ' . number_format($grandTotal ?? 0, 2) . '</a>';

           // Close the result set
           $result->close();
       } else {
           echo "Error: " . $sql . "<br>" . $conn->error;
       }

   ?>
                
                </div>

           
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
             <div class="card-body">
                    <h5 class="card-title text-primary">Purchase Invoices</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <?php
       
       // Get the current month and year
       $currentMonth = date('m');
       $currentYear = date('Y');

       // Get the first and last day of the current month
       $firstDay = date('Y-m-01');
       $lastDay = date('Y-m-t');

       // SQL query to fetch the grand_total for the current month
       $sql = "SELECT SUM(grand_total) FROM pi_invoice WHERE invoice_date >= '$firstDay' AND due_date <= '$lastDay'";

       $result = $conn->query($sql);

       // Check if the query was successful
       if ($result) {
           // Fetch the data
           $row = $result->fetch_assoc();
           
           // Display the grand_total value in the button
           $grandTotal = $row ? $row['SUM(grand_total)'] : 0;
           echo '<a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR ' . number_format($grandTotal ?? 0, 2) . '</a>';

           // Close the result set
           $result->close();
       } else {
           echo "Error: " . $sql . "<br>" . $conn->error;
       }

     // Close the connection
$conn->close();
   ?>
                </div>
            </div>
        </div>

     
        
    </div>
    <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">Total Payments</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <a href="" class="btn btn-warning" style="float: right; margin-top: -60px;">INR 0</a>
                </div>

           
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">Account Payable</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR 0</a>
                </div>
            </div>
        </div>

     
        
    </div>
    <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">ITC Receivable</h5>
                    <p class="card-text" style="margin-top: -15px;">Last Month</p>
                    <a href="itc-details.php" class="btn btn-warning" style="float: right; margin-top: -60px;">INR 0</a>
                </div>

           
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">ITC Received</h5>
                    <p class="card-text" style="margin-top: -15px;">Last Month</p>
                    <a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR 0</a>
                </div>
            </div>
        </div>

     
        
    </div>                 

    <div class="row" style="margin-top: -19px;">
    <div class="col-md-12">
        <div class="card p-3">
            <div id="chartdiv_purch"></div>
            <div id="expense_setup" style="background-image:url('');background-repeat:no-repeat;background-size:100% 100%;height:416px;">
                <div class="row justify-content-center align-items-center">
                    <div class="border p-3 mx-auto" style="background:#fff; max-width: 500px;">
                        <div class="col-md-12 text-center">
                            <h6 class="mb-2">Record Expense Payments</h6>
                            <p class="mb-3">Create vouchers to track money paid to suppliers and reduce payables.</p>
                            <a class="btn btn-sm btn-primary" href="javascript:;" onclick="popup('quick-voucher')">Create Voucher</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


                        </div>
                        
                        <div id="comp_chart" class="tab-pane fade">
                            <!-- Content for Expenses Chart Goes Here -->
                            <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title text-primary">GST Filing Status</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <a href="" class="btn btn-warning" style="float: right; margin-top: -60px;">INR 0</a>
                </div>

           
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">TDS Payable</h5>
                    <p class="card-text" style="margin-top: -15px;">Last Month</p>
                    <a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR 0</a>
                </div>
            </div>
        </div>

     
        
    </div>                 
    <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">Cash Ledger</h5>
                    <p class="card-text" style="margin-top: -15px;">Connect GSTN</p>
                    <a href="" class="btn btn-warning" style="float: right; margin-top: -60px;">INR 0</a>
                </div>

           
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">ITC Ledger</h5>
                    <p class="card-text" style="margin-top: -15px;">Connect GSTN</p>
                    <a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR 0</a>
                </div>
            </div>
        </div>       
    </div> 
    <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">ITC Receivable</h5>
                    <p class="card-text" style="margin-top: -15px;">Last Month</p>
                    <a href="itc-details.php" class="btn btn-warning" style="float: right; margin-top: -60px;">INR 0</a>
                </div>          
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">ITC Default</h5>
                    <p class="card-text" style="margin-top: -15px;">Last Month</p>
                    <a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR 0</a>
                </div>
            </div>
        </div>            
    </div> 
    <div class="row" style="margin-top: -19px;">
    <div class="col-md-12">
        <div class="card p-3">
            <div id="chartdiv_purch"></div>
            <div id="expense_setup" style="background-image:url('');background-repeat:no-repeat;background-size:100% 100%;height:416px;">
                <div class="row justify-content-center align-items-center">
                    <div class="border p-3 mx-auto" style="background:#fff; max-width: 500px;">
                        <div class="col-md-12 text-center">
                            <h6 class="mb-2">Sync GST Data</h6>
                            <p class="mb-3">Activate GSTN Connection and Sync the data in order to populate the reports.</p>
                            <a class="btn btn-sm btn-primary" href="javascript:;" onclick="popup('quick-voucher')">Sync Data</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


                        </div>
                        <div id="bank_chart" class="tab-pane fade">
                            <!-- Content for Banking Chart Goes Here -->
                            <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                <h3 class="card-title" style="font-size: 1.1rem;">
                Add Bank Account
            </h3>
            <p class="card-text mt-4" style="margin-top: -10px;">
                Add your bank account to start Connected Banking with Partners Bank.
            </p>
            <a href="" class="btn btn-link text-primary font-weight-bold mt-2" target="_blank" style="margin-top: -10px; float:right;">
                Manage Bank Accounts
            </a>
                </div>    
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">Money Received</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <a href="" class="btn btn-warning" style="float: right; margin-top: -60px;">INR 0</a>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary">Money Paid</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <a href="" class="btn btn-success" style="float: right; margin-top: -60px;">INR 0</a>
                </div>
            </div>
        </div>            
    </div>
    <div class="row" style="margin-top: -20px;">
        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">Unaccounted</h5>
                    <p class="card-text" style="margin-top: -15px;">Current Month</p>
                    <a href="" class="btn btn-warning" style="float: right; margin-top: -60px;">0 TXN</a>
                </div>     
            </div>
        </div>


        <div class="col-md-6">
            <div class="card">
            <div class="card-body">
                    <h5 class="card-title text-primary">Bank Accounts</h5>
                    <p class="card-text" style="margin-top: -15px;">Active</p>
                    <a href="" class="btn btn-success" style="float: right; margin-top: -60px;">0</a>
                </div>
            </div>
        </div>            
    </div> 
    <div class="row" style="margin-top: -19px;">
    <div class="col-md-12">
        <div class="card p-3">
            <div id="chartdiv_bank" class="bank_trans_yes"></div>
            <div class="bank_trans_no" style="background-image:url(''); background-repeat:no-repeat; background-size:cover; height:416px;">
                <div class="row">
                    <div class="no_con_trans border p-3 mx-auto" style="width:500px; background:#fff;">
                        <div class="col-md-12 text-center">
                            <h6 class="mb-3">Connect ICICI Bank or Upload Bank Statement</h6>
                            <p class="mb-3">Connect our ICICI Bank Current Account to get a live statement. You can also add and upload your bank statement to track transactions.</p>
                        </div>
                        <div class="col-md-12 text-center">
                            <a class="btn btn-sm btn-warning mr-2 con_icici_link" href="/m/app/business/manage-business?tab=connect" target="_blank">Connect ICICI</a>
                            <a class="btn btn-sm btn-primary" href="/m/app/banking/connect-bank?tab=verify" target="_blank">Upload Statement</a>
                        </div>
                    </div>
                    <div class="yes_con_trans border p-3 mx-auto" style="width:500px; background:#fff; display:none;">
                        <div class="col-md-12 text-center">
                            <h6 class="mb-3">No Transactions</h6>
                            <p class="mb-3">No transactions in the last 30 days. You can initiate a fund transfer to start transactions.</p>
                        </div>
                        <div class="col-md-12 text-center">
                            <a class="btn btn-sm btn-primary" href="/m/app/banking/connect-bank?tab=fund" target="_blank">Transfer Funds - View Transactions</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


                        </div>
                   
                    </div>
                </div>
                <div class="col-lg-3 col-md-3" style="margin-top: -15px;">
                <div class="card">
                <div class="row">  
                <div class="col-md-12 mt-2">
                        <h5 class="kt-portlet__head-title text-danger pt-3 pl-3">Add Bank Account</h5>
                        <p class="mb-3  kt-font-bold pl-3">Add your bank account to start automating your accounting.</p>
                    </div>
                    <div class="col-md-12">
    <ul class="d-flex pl-0 mb-0 align-items-center" style="list-style:none;">
        <li style="flex:1;">
            <a href="#" class="btn btn-danger border-bottom-0 border-left-0 kt-font-bold btn-block" 
               data-bs-toggle="modal" data-bs-target="#bankAccountModal">
                Add Bank Account
            </a>
        </li>
    </ul>
</div>

    <!-- Modal Structure -->
    <div class="modal fade" id="bankAccountModal" tabindex="-1" aria-labelledby="bankAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bankAccountModalLabel">New Bank Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="insert_bank_account.php">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="accountHolder" class="form-label">Account Holder</label>
                            <input type="text" class="form-control" id="accountHolder" name="accountHolder" placeholder="SAI KUMAR PANDRALA">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nickname" class="form-label">Nick Name</label>
                            <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Nick Name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="accountNo" class="form-label">Account No.*</label>
                            <input type="text" class="form-control" id="accountNo" name="accountNo" placeholder="Account No.*">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ifsc" class="form-label">IFSC*</label>
                            <input type="text" class="form-control" id="ifsc" name="ifsc" placeholder="IFSC*">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bank" class="form-label">Search for bank</label>
                            <select class="form-control" id="bank" name="bank">
                                <option selected disabled>Search for bank</option>
                                <option value="1">Bank 1</option>
                                <option value="2">Bank 2</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="branch" class="form-label">Search for branch</label>
                            <select class="form-control" id="branch" name="branch">
                                <option selected disabled>Search for branch</option>
                                <option value="1">Branch 1</option>
                                <option value="2">Branch 2</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="accountType" class="form-label">Account Type</label>
                            <select class="form-control" id="accountType" name="accountType">
                                <option selected>Current Account</option>
                                <option value="1">Savings Account</option>
                                <option value="2">Fixed Deposit Account</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

                    </div>
    
</div>










<div class="card" style="margin-top: -19px;">

    <!-- Navigation tabs -->
    <ul class="nav nav-pills justify-content-center mb-4" id="myTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#contact_pie">Contacts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link cat_li" id="catalog-tab" data-toggle="tab" href="#cat_pie">Catalog</a>
        </li>
        <li class="nav-item">
            <a class="nav-link attd_li" id="attendance-tab" data-toggle="tab" href="#attd_pie">Attendance</a>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content">
        <!-- Contacts Tab -->
        <div class="tab-pane active" id="contact_pie" role="tabpanel">
            <div id="chart_led_count"></div>
            <h6 class="cat_head_cont text-center">Contacts Summary</h6>
            <div class="no_led_sec mt-3 text-center">
                <a class="mt-3" href="javascript:;" onclick="popup('customer')" style="width:160px;">
                    <img src="" class="img-fluid">
                    <p>Customer accounts can help you send invoices, generate accounts statement and track receivables</p>
                </a>
            </div>
        </div>

        <!-- Catalog Tab -->
        <div class="tab-pane" id="cat_pie" role="tabpanel">
            <div id="chart_cat_count"></div>
            <h6 class="cat_head_cat text-center">Catalog Summary</h6>
            <div class="no_cat_sec mt-3 text-center">
                <a class="mt-3" onclick="popup('product','','','2',1)" href="">
                    <img src="" class="img-fluid" style="width:160px;">
                    <p>Add goods or services you sell on this to send your first invoice</p>
                </a>
            </div>
        </div>

        <!-- Attendance Tab -->
        <div class="tab-pane" id="attd_pie" role="tabpanel">
            <div id="chart_attd_count"></div>
            <h6 class="cat_head_attd text-center">Attendance Summary</h6>
            <div class="no_emp_sec mt-3 text-center">
                <a class="mt-3" href="" onclick="popup('employee')">
                    <img src="" class="img-fluid" style="width:160px;">
                    <p>Add employees to this and automatically track their daily attendance.</p>
                </a>
            </div>
            <div class="no_att_sec mt-3 text-center">
                <a class="mt-3" href="">
                    <img src="" class="img-fluid" style="width:160px;">
                    <p>Use this Attendance App to track employee attendance. Employees added to this can punch-in and punch-out times using their mobile number.</p>
                </a>
            </div>
        </div>
    </div>
</div>

                        </div>
                </div>

            </div>
      



</div>
</section>           
<script>
 $(document).ready(function(){
    $('#Tabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');

        // Update the URL hash without causing a jump
        var tabId = $(this).attr('href');
        history.replaceState(null, null, tabId);
    });

    // Check if there's a hash in the URL on page load
    if (window.location.hash) {
        $('#Tabs a[href="' + window.location.hash + '"]').tab('show');
    }
});
</script>

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>