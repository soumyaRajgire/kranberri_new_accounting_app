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

<body>
    <!-- Pre-loader start -->
    <?php include("menu.php"); ?>

    <!-- Main Content start -->
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- Breadcrumb start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Reports</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Breadcrumb end -->

            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Report</h5>
                            <div class="d-flex align-items-center ms-auto">
                                <select class="form-select form-select-sm w-auto me-2" id="reportType">
                                    <option value="monthly-pay">MONTHLY PAY</option>
                                    <option value="pf-report">PF REPORT</option>
                                    <option value="esi-report">ESI REPORT</option>
                                    <option value="tds-report">TDS REPORT</option>
                                    <option value="pt-report">PT REPORT</option>
                                </select>
                                <div class="input-group">
                                    <button class="btn btn-light btn-sm">&lt;</button>
                                    <input type="text" class="form-control text-center border-light" value="AUG 2024" readonly>
                                    <button class="btn btn-light btn-sm">&gt;</button>
                                </div>
                                <div class="dropdown ms-2">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-file-earmark-x"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                        <li><a class="dropdown-item" href="download_report.php?type=monthly">Monthly Report</a></li>
                                        <li><a class="dropdown-item" href="download_report.php?type=working">Working Report</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Employee ID</th>
                                            <th>Employee Name</th>
                                            <th>Designation</th>
                                            <th>Netpay</th>
                                            <th>Fixed Salary</th>
                                            <th>Total Earnings</th>
                                            <th>Total Deductions</th>
                                            <th>Bank Account Number</th>
                                            <th>Bank Account Name</th>
                                            <th>Bank IFSC Code</th>
                                            <th>Bank Name</th>
                                            <th>Bank Branch</th>
                                            <th>Update Payment</th>
                                            <th>Payment Status</th>
                                            <th>Payment Mode</th>
                                            <th>Payment Date</th>
                                            <th>Payslip</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Required Js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
