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

// Get the current month and year
$currentMonth = date('F');
$currentYear = date('Y');
?>
<html lang="en">
<head>
    <title>iiiQbets - Payroll</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    body {
        background-color: #f2f3f8;
        font-family: Arial, sans-serif;
    }
    .container {
        width: 100%;
        margin: auto;
        padding: 20px;
    }
    .portlet {
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .portlet-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #fff;
        border-bottom: 1px solid #eee;
    }
    .portlet-head h5 {
        margin: 0;
    }
    .portlet-body {
        padding: 20px;
    }
    .alert {
        background-color: #dcdcdcba;
        border-radius: 4px;
        padding: 10px;
        display: flex;
        align-items: center;
    }
    .alert-icon {
        margin-right: 10px;
    }
    .alert-text {
        color: initial;
    }
    .scroll {
        max-height: 300px;
        overflow-y: auto;
    }
    .centeralignform {
        display: flex;
        justify-content: center;
    }
    .portlet-body .btn {
        float: right;
    }
</style>

<body class="">
    <!-- Rest of your HTML content for customers -->
    <!-- [ Pre-loader ] start -->
    <?php include("menu.php"); ?>

    <!-- [ Main Content ] start -->
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Payrolls</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <hr>
            <div class="container">
                <div class="portlet">
                    <div class="portlet-head">
                        <h5>Payroll Dashboard for <span style="color: blue;">iiiQbets</span></h5>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p>Start your payroll for <?php echo $currentMonth . '-' . $currentYear; ?></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <button class="btn btn-primary" onclick="window.location.href='computepayroll.php'">Compute Payroll</button>
                            </div>
                        </div>
                        <div class="scroll">
                            <!-- Dynamic data will be loaded here -->
                            <p>No records found</p>
                        </div>
                    </div>
                </div>
                <div class="portlet">
                    <div class="portlet-body">
                        <div class="alert">
                            <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                            <div class="alert-text">
                                Payroll run is empty, since there are no employees with salary set-up and attendance.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTables-example').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
        $('#dataTables-example').dataTable({
            "orderFixed": [3, 'asc']
        });
    </script>
</body>
</html>
