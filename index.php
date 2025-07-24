<!DOCTYPE html>
<?php
session_start(); 
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
<!-- <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card and Chart Layout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <?php include("header_link.php");?>
<!-- Include Highcharts and the Highcharts 3D module -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>




    <style>
        /* .html{
            background-color: #636363;
        }
        html:focus{
            outline: none !important;
            border: none !important;
        } */
            .container-fluid{
                padding: 20px;
                padding-bottom: 10px !important;
                background-color: #ededed67;
                margin-left: 0px;
            }
            .card {
                /* border: 1px solid #e0e0e0; */
                /* border-radius: 5px; */
                padding: 0px;
                box-shadow: 0 4px 8px 2px rgba(0, 0, 0, 0.12);
                margin-top: -40px;
                height: 120px !important;
            }
            .card2 {
                /* border: 1px solid #e0e0e0; */
                /* border-radius: 8px; */
                padding: 20px;
                box-shadow: 0 4px 8px 2px rgba(0, 0, 0, 0.12);
                margin-top: 10px;
                background-color: white;
/*                width: 300px;*/
/*                margin-left: 50px;*/
            }
            .card-title {
                font-size: 0.9rem;
                font-weight: 600;
                color: #2d2d2d;
            }
            .info-icon {
                margin-left: 10px;
                font-size: 1.2rem;
                cursor: pointer;
                color: #636363;
            }
            .card-subtitle {
                font-size: 0.875rem;
                color: #d5d4d4;
            }
            .balance-amount {
                font-size: 1rem;
                font-weight: bold;
                color: rgb(1, 107, 1); 
            }
            .balance-amount2 {
                font-size: 1rem;
                font-weight: bold;
                color: rgb(255, 4, 4); 
                margin-top: 5px;
            }
            .balance-amount3 {
                font-size: 1rem;
                font-weight: bold;
                color: rgb(255, 184, 4); 
                margin-top: 5px;
            }
            .amount {
                font-size: 0.8rem;
                font-weight: bold;
                color: #3694f9; 
                text-wrap: nowrap !important;
            }
            .amount2 {
                font-size: 1rem;
                font-weight: bold;
                color: #8800ff; 
            }
            [data-bs-toggle="tooltip"] {
                cursor: pointer;
            }
            .row {
                margin-bottom: 10px;
            }
            h6 {
                font-size: 0.8rem;
                color: #636363;
                margin: 0;
            }
            .info-icon {
                margin-left: 10px;
                font-size: 1.2rem;
                cursor: pointer;
                color: #636363;
            }
            .disabled-amount {
                color: #888888; 
                text-decoration: line-through; 
            }

            .container{
                margin: 0px !important;
                background-color: #ededed67;
                max-width: 100%;
            }

            .bi, .btn, .form-control, .rounded-circle, .btn-outline-primary {
                outline: none !important;
                border: none !important;
            }

            .filter-btns {
                display: flex;
                gap: 10px;
                margin-bottom: 10px;
            }
            
            .filter-btns button {
                font-size: 14px;
                padding: 7px 20px;
                cursor: pointer;
                border-radius: 5px;
                background-color: transparent;
            }

            .filter-btns .active {
                background-color: #298c8c;
                color: white;
                border-radius: 0px;
            }
            .buttons .active {
                background-color: #007bff;
                color: white;
            }
            .datepicker {
                background-color: #fff;
                border: 1px solid #007bff;
                border-radius: 5px;
                padding: 5px;
                width: 200px;
                animation: 1s ease all;
            }
            input[type="date"] {
                border: 1px solid #ddd;
                padding: 8px;
                font-size: 16px;
                border-radius: 5px;
                background-color: #f9f9f9;
            }

            input[type="date"]:focus {
                outline: none;
                border-color: #007bff;
            }
            .datepicker::-webkit-calendar-picker-indicator {
                background-color: transparent;
            }
            .modal-content {
                background-color: #f9f9f9;
                padding: 20px;
            }
            
            .modal-header {
                border-bottom: 1px solid #ddd;
            }

            .modal-body {
                font-size: 14px;
                padding: 10px 0;
            }
            .button{
                padding: 20px !important;
            }
            .p{
                margin-left: auto;
                margin-right: 0px !important;
            }
            .btn-group .btn{
                color: #298c8c;
            }
            .btn-group .btn:hover{
                background-color: #298c8c;
                color: whitesmoke;
            }
            .btn-group .btn:focus{
                color: #ffffff;
                border: none !important;
                outline: none !important;
            }
            .btn-group .active{
                background-color: #298c8c;
                color: #fff;
            }
                @media (max-width: 768px) {
                    .row {
                        flex-direction: column;
                        align-items: flex-start;
                    }
                    .col-8, .col-4 {
                        text-align: left;
                    }
                }
    </style>
</head>
<body>
<?php include("menu.php");?>

<section class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
               <div class="row">
    <!-- <div class="container-fluid"> -->
        
        <!-- <div class="row"> -->
       
            <div class="col-md-8" style=" background-color: white;border-radius: 10px;box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                <div class="w-100">
                    <!-- <div class="btn-group" role="group" aria-label="Basic outlined example">
                        <button type="button" class="btn  active" id="revenueBtn">Sales</button>
                        <button type="button" class="btn" id="expensesBtn">Expenses</button>
                        <button type="button" class="btn btn-outline-primary" id="complianceBtn">Compliance</button>
                        <button type="button" class="btn btn-outline-primary" id="bankingBtn">Banking</button>
                    </div> -->
                     <ul class="nav nav-tabs" id="Tabs">
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#rev_chart">Revenue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#exp_chart">Expenses</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#comp_chart">Compliance</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#bank_chart">Banking</a>
                </li>
            </ul>
                </div>                
            </div>
            <!-- <div class="col-md-1"></div> -->
            <div class="col-md-4 d-flex">
                <div class="d-flex align-items-center justify-content-end">
                <div class="ms-3">

<div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
    <i class="fa fa-calendar"></i>&nbsp;
    <span></span> <i class="fa fa-caret-down"></i>
</div>
</div>




                  
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- <div class="container mt-1"> -->
        <!-- sales-->
<div class="tab-content">

<!--dashboard-->
<div id="dashboard" class="tab-pane fade show active">
<div class="row " >
    <div class="col-md-8">
        <!-- <div class="row mt-4"> -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center"> Bank Balance</h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text amount2">INR </p>
                    </div>
                </div>                                          
            </div>
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Cash Balance</h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount">INR </p>
                    </div>
                </div>                                            
            </div>
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Receivables </h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount2" id="receivablesCard">INR </p>
                    </div>
                </div>                                            
            </div>                                 
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Payables</h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount3" id="payablesCard">INR </p>
                    </div>
                </div>                                            
            </div>
        </div>
        <div class="row">
            <div class="col-12">
            <!-- <canvas id="myBarChart" width="400" height="170"></canvas> -->
            <!-- HTML Container for the 3D Chart -->
<div id="3dChartContainer" style="width: 100%; height: 400px;"></div>


            </div>
        </div>
                            <!-- </div> -->
    </div>
    <div class="col-md-4">
        <!-- <div class="card2">
            <div class="row">
                <div class="col-8">
                    <h6>Total Sales</h6>
                </div>
                <div class="col-4 text-end">
                    <span class="amount">INR 55.96 L</span>
                </div>
            </div>                           
            
            <div class="row">
                <div class="col-8">
                    <h6>Cost</h6>
                </div>
                <div class="col-4 text-end">
                    <span class="amount">INR 0</span>
                </div>
            </div>                         
            
            <div class="row">
                <div class="col-8">
                    <h6>Salary</h6>
                </div>
                <div class="col-4 text-end">
                    <span class="amount">INR 0</span>
                </div>
            </div>                        
           
            <div class="row">
                <div class="col-8">
                    <h6>Travel</h6>
                </div>
                <div class="col-4 text-end">
                    <span class="amount">INR 0</span>
                </div>
            </div>                         
            
            <div class="row">
                <div class="col-8">
                    <h6>Rent</h6>
                </div>
                <div class="col-4 text-end">
                    <span class="amount">INR 0</span>
                </div>
            </div>                           
          
            <div class="row">
                <div class="col-8">
                    <h6>Communication</h6>
                </div>
                <div class="col-4 text-end">
                    <span class="amount">INR 0</span>
                </div>
            </div>                          
           
            <div class="row">
                <div class="col-8">
                    <h6>Marketing</h6>
                </div>
                <div class="col-4 text-end">
                    <span class="amount">INR 0</span>
                </div>
            </div>                         
            
            <div class="row">
                <div class="col-8">
                    <h6>Misc</h6>
                </div>
                <div class="col-4 text-end">
                    <span class="amount">INR 36,161</span>
                </div>
            </div>
        </div> --> 
           <!--  <div class="card2">
                <div class="row">
                    <div class="col-8">
                        <h6>Total Sales</h6>
                    </div>
                    <div class="col-4 text-end">
                        <span class="amount">INR 55.96 L</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <h6>Cost</h6>
                    </div>
                    <div class="col-4 text-end">
                        <span class="amount">INR 0</span>
                    </div>
                </div>
            </div>          -->          
    </div>
</div>
</div>
    <!--revenue-->
<div id="rev_chart" class="tab-pane fade ">
    <h6>Sales Summary</h6>
<div class="row " >
    <div class="col-md-8">
        <!-- <div class="row mt-4"> -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center"> Total</h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text amount2" id="totalSalesCard">INR </p>
                    </div>
                </div>                                          
            </div>
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center"> Paid </h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount" id="paidCard">INR </p>
                    </div>
                </div>                                            
            </div>
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Receivables </h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount2" id="salesreceivablesCard">INR </p>
                    </div>
                </div>                                            
            </div>                                 
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center"> Overdue</h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount3" id="overDuecard">INR </p>
                    </div>
                </div>                                            
            </div>
        </div>
        <div class="row">
            <div class="col-12">
         <!-- HTML Container for the 3D Chart -->
<div id="salesChartContainer" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
                            <!-- </div> -->
    </div>
    <div class="col-md-4">
        <div class="card2">
            <div class="row">
                <div class="col-8">
                    <h5>Recent Sales</h5>
                      <hr/>
                </div>

                <?php


// Fetch recent invoices with invoice ID and price (total_amount)
$sql_recent_invoices = "
SELECT invoice_code, total_amount 
FROM invoice
WHERE branch_id='$branch_id'  -- You can adjust this filter based on your requirements (e.g., unpaid, partial)
ORDER BY created_on DESC
LIMIT 5";  // You can change the LIMIT based on how many recent invoices you want to display

$result_invoices = $conn->query($sql_recent_invoices);
$recent_invoices = [];

while ($row = $result_invoices->fetch_assoc()) {
    $recent_invoices[] = [
        'invoice_code' => $row['invoice_code'],
        'total_amount' => $row['total_amount']
    ];
}
?>
<div class="row">
    <?php foreach ($recent_invoices as $invoice): ?>
        <div class="col-8">
            <h5><?php echo $invoice['invoice_code']; ?></h5>
        </div>
        <div class="col-4 text-end">
            <span class="amount">INR <?php echo number_format($invoice['total_amount'], 2); ?></span>
        </div>
    <?php endforeach; ?>
</div>
              <!--   <div class="col-4 text-end">
                    <span class="amount">INR 55.96 L</span>
                </div> -->
            </div>                           
         </div> 
                     
    </div>
</div>
</div>

<!---expenses tab-->
<div id="exp_chart" class="tab-pane fade">
     <h6>Expenses Summary</h6>
<div class="row " >
    <div class="col-md-8">
        <!-- <div class="row mt-4"> -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Purchase Orders </h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text amount2" id="purchaseOrdersCard">INR </p>
                    </div>
                </div>                                          
            </div>
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Purchases  </h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount" id="purchasesCard">INR </p>
                    </div>
                </div>                                            
            </div>
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Total Payments </h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount2" id="totalPaymentsCard">INR </p>
                    </div>
                </div>                                            
            </div>                                 
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Payables</h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount3" id="expayablesCard">INR </p>
                    </div>
                </div>                                            
            </div>
        </div>
        <div class="row">
            <div class="col-12">
           <div id="payablesChartContainer" style="width: 100%; height: 400px;"></div>

            </div>
        </div>
             </div>          
    <div class="col-md-4">
        <div class="card2">
            <div class="row">
                <div class="col-8">
                    <h5>Recent Purchases</h5><hr/>
                </div>
              <?php

// Fetch recent purchase invoices with invoice ID and total_amount
$sql_recent_purchase_invoices = "
SELECT invoice_code, total_amount 
FROM pi_invoice
WHERE branch_id='$branch_id'  -- You can adjust this filter based on your requirements (e.g., unpaid, partial)
ORDER BY created_on DESC
LIMIT 5";  // You can change the LIMIT based on how many recent invoices you want to display

$result_purchase_invoices = $conn->query($sql_recent_purchase_invoices);
$recent_purchase_invoices = [];

while ($row = $result_purchase_invoices->fetch_assoc()) {
    $recent_purchase_invoices[] = [
        'invoice_code' => $row['invoice_code'],
        'total_amount' => $row['total_amount']
    ];
}
?>

<div class="row">
    <?php foreach ($recent_purchase_invoices as $purchase_invoice): ?>
        <div class="col-8">
            <h5><?php echo $purchase_invoice['invoice_code']; ?></h5>
        </div>
        <div class="col-4 text-end">
            <span class="amount">INR <?php echo number_format($purchase_invoice['total_amount'], 2); ?></span>
        </div>
    <?php endforeach; ?>
</div>

            </div>                           
           
        </div> 
                           
    </div>
</div>
</div>

<!-- </div> -->

<!--banking-->
<div id="bank_chart" class="tab-pane fade">
     <h6>Bank Transaction Summary</h6>
<div class="row " >
    <div class="col-md-8">
        <!-- <div class="row mt-4"> -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Balance</h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text amount2">INR </p>
                    </div>
                </div>                                          
            </div>
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Money Received</h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount">INR </p>
                    </div>
                </div>                                            
            </div>
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Money Paid</h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount2" id="">INR </p>
                    </div>
                </div>                                            
            </div>                                 
            <div class="col-md-3">
                <div class="card mt-1">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">Pending Recon </h5>
                        <h6 class="card-subtitle">Current FY</h6>
                        <p class="card-text balance-amount3" id="">INR </p>
                    </div>
                </div>                                            
            </div>
        </div>
        <div class="row">
            <div class="col-12">
            <canvas id="myBarChart4" width="400" height="170"></canvas>
            </div>
        </div>
                            <!-- </div> -->
    </div>
    <div class="col-md-4">
        <div class="card2">
            <div class="row">
                <div class="col-8">
                    <h6></h6>
                </div>
                <div class="col-4 text-end">
                    <span class="amount">INR 55.96 L</span>
                </div>
            </div>                           
            
            
        </div>          
    </div>
</div>
</div>

</div>


</section>
</body>
            
   
<!--  -->

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
   <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
$(document).ready(function(){
    $('#Tabs a').on('click', function (e) {
        e.preventDefault();
        
        var tabId = $(this).attr('href');
        history.replaceState(null, null, tabId);

        // Activate tab manually (Bootstrap 5 way)
        var triggerEl = document.querySelector('#Tabs a[href="' + tabId + '"]');
        var tab = new bootstrap.Tab(triggerEl);
        tab.show();
    });

    if (window.location.hash) {
        var triggerEl = document.querySelector('#Tabs a[href="' + window.location.hash + '"]');
        if (triggerEl) {
            var tab = new bootstrap.Tab(triggerEl);
            tab.show();
        }
    }
});
</script>
         

<script>
   // Declare globally
var myBarChart; 

$(document).ready(function() {
    var ctx = document.getElementById('myBarChart').getContext('2d');

    myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Receivables', 'Payables'], // We'll show two bars: Receivables & Payables
            datasets: [{
                label: 'Amount',
                data: [0, 0], // Start with empty data
                backgroundColor: ['#298c8c', '#800074'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'INR ' + value;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});

    function changeProfilePicture() {
        const fileInput = document.getElementById('fileInput');
        const profileImg = document.getElementById('profileImg');

        const file = fileInput.files[0];  
        if (file) {
            const reader = new FileReader();  

            reader.onload = function(e) {
                profileImg.src = e.target.result; 
            };

            reader.readAsDataURL(file); 
        }
    }
    const buttons = document.querySelectorAll('.btn-group .btn');
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });
    // const filterBtns = document.querySelectorAll('.filter-btns button');
    // filterBtns.forEach(btn => {
    //     btn.addEventListener('click', () => {
    //         filterBtns.forEach(b => b.classList.remove('active'));
    //         btn.classList.add('active'); 
    //         if (btn.id === 'customRangeFilter') {
    //             document.getElementById('customRange').style.display = 'block';
    //         } else {
    //             document.getElementById('customRange').style.display = 'none';
    //         }
    //     });
    // });
</script>
<script type="text/javascript">

   $(function() {
    var start = moment().startOf('month');
    var end = moment().endOf('month');

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    //  Important: Call this function immediately when page loads
    fetchPayablesReceivables(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    fetchExpensesData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
      fetchSalesData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD')); 


    // When user selects a date range manually
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        
        fetchPayablesReceivables(startDate, endDate);
        fetchExpensesData(startDate,endDate);
          fetchSalesData(startDate, endDate); 
    });
});

// // Fetch Receivables and Payables
// function fetchPayablesReceivables(startDate, endDate) {
//     $.ajax({
//         url: 'fetch_pay_receive_data.php',
//         type: 'POST',
//         data: {
//             start_date: startDate,
//             end_date: endDate
//         },
//         success: function(response) {
//             console.log(response);
//             var data = JSON.parse(response);

//             // Update the dashboard cards
//             $('#receivablesCard').html('INR ' + (formatCurrency(data.receivables) ?? 0));
//             $('#payablesCard').html('INR ' + (formatCurrency(data.payables) ?? 0));
//   // if (myBarChart) {
//   //               myBarChart.data.datasets[0].data = [data.receivables ?? 0, data.payables ?? 0];
//   //               myBarChart.update();
//   //           }
//              my3DChart.series[0].setData([formatCurrency(data.receivables), formatCurrency(data.payables)], true);  // Update chart dynamically
//         },
//         error: function() {
//             console.log("Error fetching payables/receivables.");
//         }
//     });
// }

// var my3DChart;

// $(document).ready(function() {
//     var ctx = document.getElementById('3dChartContainer');

//     // Initialize the 3D column chart
//     my3DChart = Highcharts.chart(ctx, {
//         chart: {
//             type: 'column',
//             options3d: {
//                 enabled: true,
//                 alpha: 15, // View angle
//                 beta: 15, // Depth angle
//                 depth: 50 // Depth of 3D
//             }
//         },
//         title: {
//             text: 'Receivables vs Payables'
//         },
//         xAxis: {
//             categories: ['Receivables', 'Payables'] // Categories for X-axis
//         },
//         yAxis: {
//             title: {
//                 text: 'Amount (INR)'
//             },
//             labels: {
//                 formatter: function() {
//                     return 'INR ' + this.value; // Format Y-axis with INR
//                 }
//             }
//         },
//         series: [{
//             name: 'Amount',
//             data: [0, 0], // Initial data for Receivables and Payables (to be updated)
//             colorByPoint: true,
//             colors: ['#298c8c', '#800074'] // Different colors for Receivables and Payables
//         }],
//         plotOptions: {
//             column: {
//                 depth: 25
//             }
//         }
//     });
// });


var my3DChart;

$(document).ready(function() {
    var ctx = document.getElementById('3dChartContainer');

    // Initialize the 3D pie chart
    my3DChart = Highcharts.chart(ctx, {
        chart: {
            type: 'pie', // Changing to 'pie' for better visualization
            options3d: {
                enabled: true,
                alpha: 45, // View angle
                beta: 0, // Depth angle
                depth: 50 // Depth of 3D
            }

        },
        title: {
            text: 'Receivables & Payables'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> ({point.y})'
        },
        plotOptions: {
            pie: {
                innerSize: '50%', // Creates a donut chart
                depth: 45,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y}', // Display name and value on the chart
                    style: {
                        fontWeight: 'bold',
                        color: 'white'
                    }
                }
            }
        },
        series: [{
            name: 'Amount',
            data: [
                {
                    name: 'Receivables',
                    y: 0, // Set initial value as 0 to prevent empty chart rendering
                    color: '#298c8c' // Color for Receivables
                },
                {
                    name: 'Payables',
                    y: 0, // Set initial value as 0 to prevent empty chart rendering
                    color: '#800074' // Color for Payables
                }
            ]
        }]
    });

    // Fetch data for the current financial year on page load
    // var start = moment().startOf('year');
    // var end = moment().endOf('year');
    // fetchPayablesReceivables(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));

    // // Date picker functionality
    // $('#reportrange').daterangepicker({
    //     startDate: start,
    //     endDate: end,
    //     ranges: {
    //        'Today': [moment(), moment()],
    //        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    //        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    //        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    //        'This Month': [moment().startOf('month'), moment().endOf('month')],
    //        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    //     }
    // });

    // $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
    //     var startDate = picker.startDate.format('YYYY-MM-DD');
    //     var endDate = picker.endDate.format('YYYY-MM-DD');
        
    //     // Fetch updated data when the date range is applied
    //     fetchPayablesReceivables(startDate, endDate);
    // });
});

// Fetch Receivables and Payables based on the selected date range
function fetchPayablesReceivables(startDate, endDate) {
    $.ajax({
        url: 'fetch_pay_receive_data.php',
        type: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        success: function(response) {
            var data = JSON.parse(response);

            // Update the dashboard cards with new values
            $('#receivablesCard').html('INR ' + (formatCurrency(data.receivables) ?? 0));
            $('#payablesCard').html('INR ' + (formatCurrency(data.payables) ?? 0));

            // Update the 3D chart with new data (Receivables, Payables)
            if (my3DChart) {
                my3DChart.series[0].setData([
                    {
                        name: 'Receivables',
                        y: parseFloat(data.receivables) || 0,
                        color: '#298c8c'
                    },
                    {
                        name: 'Payables',
                        y: parseFloat(data.payables) || 0,
                        color: '#800074'
                    }
                ], true); // Update chart dynamically
            }
        },
        error: function() {
            console.log("Error fetching payables/receivables.");
        }
    });
}

// Function to format currency values
function formatCurrency(value) {
    if (value == null || isNaN(value)) {
        return "0";  // Return 0 if the value is null or NaN
    }
    return parseFloat(value).toFixed(2); // Format to 2 decimal places
}


var branch_id= <?php echo $_SESSION['branch_id']; ?>;
alert(branch_id);
function fetchSalesData(startDate, endDate) {
    $.ajax({
        url: 'fetch_sales_data.php',  // PHP file that contains the SQL query
        type: 'POST',
        data: {
            start_date: startDate,
            end_date: endDate,
            branch_id:branch_id
        },
        success: function(response) {
            console.log(response);
            const data = JSON.parse(response);

            // Update the dashboard cards with fetched data
            $('#totalSalesCard').text('INR ' + formatCurrency(data.total_sales));
            $('#paidCard').text('INR ' + formatCurrency(data.total_paid));
            $('#salesreceivablesCard').text('INR ' + formatCurrency(data.receivables));
            $('#overdueCard').text('INR ' + formatCurrency(data.overdue_count));
             renderSalesChart(formatCurrency(data.total_sales), formatCurrency(data.total_paid), formatCurrency(data.receivables), formatCurrency(data.overdue_count));
        }
    });
}
function renderSalesChart(totalSales, totalPaid, receivables, overdueCount) {
    Highcharts.chart('salesChartContainer', {
        chart: {
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,  // View angle
                beta: 15,   // Depth angle
                depth: 50
            }
        },
        title: {
            text: 'Sales Overview'
        },
        xAxis: {
            categories: ['Total Sales', 'Paid Amount', 'Receivables', 'Overdue Count']
        },
        yAxis: {
            title: {
                text: 'Amount (INR)'
            }
        },
        series: [{
            name: 'Sales Data',
            data: [
                parseFloat(totalSales),
                parseFloat(totalPaid),
                parseFloat(receivables),
                overdueCount
            ],
            colorByPoint: true, // This makes each bar have a different color
            colors: ['#298c8c', '#800074', '#f39c12', '#e74c3c']  // Specify custom colors for each bar
        }]
    });
}



    function fetchExpensesData(start_date, end_date) {
        $.ajax({
            url: 'fetch_dashboard_expenses.php', // Path to your PHP file
            type: 'POST',
            data: {
                start_date: start_date,
                end_date: end_date
                
            },
            success: function(response) {
                console.log(response);
                const data = JSON.parse(response);
  // Update payables value
                const payables = data.payables ? parseFloat(data.payables).toFixed(2) : 0;

                // Render the 3D graph
                render3DChart(payables);
                // Update the dashboard cards with the fetched data
                $('#purchaseOrdersCard').text('INR ' + formatCurrency(data.purchase_orders));
                $('#purchasesCard').text('INR ' + formatCurrency(data.purchases));
                $('#totalPaymentsCard').text('INR ' + formatCurrency(data.total_payments));
                $('#expayablesCard').text('INR ' + formatCurrency(data.payables));
            }
        });
    }

     function render3DChart(payables) {
        Highcharts.chart('payablesChartContainer', {
            chart: {
                type: 'column',
                options3d: {
                    enabled: true,
                    alpha: 15, // View angle
                    beta: 15, // Depth angle
                    depth: 50
                }
            },
            title: {
                text: 'Payables for Current FY'
            },
            xAxis: {
                categories: ['Payables'], // Only one category for payables
            },
            yAxis: {
                title: {
                    text: 'Amount (INR)'
                }
            },
            series: [{
                name: 'Payables',
                data: [parseFloat(payables)], // Dynamic data from the server
                depth: 25
            }]
        });
    }


    // Function to format currency values
function formatCurrency(value) {
    // Check if the value is a number or can be parsed to a number
    if (value == null || isNaN(value)) {
        return "0";  // Return 0 if the value is null, undefined or not a number
    }
    // return value.toLocaleString();  // Add formatting (comma separation for thousands)
    return parseFloat(value).toFixed(2);
}

</script>

</body>
</html>
