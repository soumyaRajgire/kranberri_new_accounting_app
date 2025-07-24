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
    <?php include("header_link.php"); ?>
</head>

<body class="">
    <?php include("customersModal.php"); ?>

    <!-- Adding Services Module-->
    <?php include("servicesModalPopup.php"); ?>
    <!-- End Services Modal-->

    <!-- Products Modal -->
    <?php include("productsModalPopUp.php"); ?>
    <!-- End of Products Modal-->

    <!-- [ Pre-loader ] start -->
    <?php include("menu.php"); ?>
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
                                <h4 class="m-b-10">Salary Payment</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Salary Payment</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="salarypaymentdb.php" method="post" id="salary_payment">
                                    <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 5px;">
                                        <div class="kt-portlet__body p-3 row">
                                            <div class="col-md-12">
                                                <ul class="nav">
                                                    <li class="nav-item" style="width:50%;">
                                                        <div class="btn-group mx-3">
                                                            <h5 id="receipt_title">Create Salary Payment</h5>
                                                        </div>
                                                    </li>
                                                    <li class="nav-item" style="margin-left: 410px;">
                                                        <div class="btn-group btn-group-sm btn_filter">
                                                            <button type="button" class="btn btn-outline-primary add_cust_filter create_tab active" data-tab-id="create_tab">Create</button>
                                                            <button type="button" class="btn btn-outline-primary add_cust_filter reconcile_tab" data-tab-id="reconcile_tab">Deductions</button>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 tab-content" id="create_tab">
                                        <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
                                            <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                                                <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                                                    <div class="row" style="margin-right: 0px; margin-left: 0px;">
                                                        <div class="col-md-7" style="border-right: 0.1rem solid #ada7a7;">
                                                            <div class="-icon" style="margin-top:10px;  margin-bottom:10px;">
                                                                <div class="business_details">
                                                                    <h5 class="line-height-70 mt-4"><b id="seller_name" style=" color: blue;">KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</b><br/>120 Newport Center Dr, Newport Beach, CA 92660<br/>
                                                                        Email: abhijith.mavatoor@gmail.com<br/>
                                                                        Phone: 9481024700<br/>
                                                                        GSTIN: 29AAICK7493G1ZX<br/>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="input-group" style="margin-top: 10px;">
                                                                <div class="input-group input-group-sm" style="width: 60%;">
                                                                    <?php
                                                                        $result1 = mysqli_query($conn, "SELECT id FROM salary_payments WHERE id = (SELECT MAX(id) FROM salary_payments)");
                                                                        if ($row1 = mysqli_fetch_array($result1)) {
                                                                            $id = $row1['id'] + 1;
                                                                            $voucherNumber = "VOU0" . $id;
                                                                        } else {
                                                                            $voucherNumber = "VOU01";
                                                                        }
                                                                    ?>
                                                                    <input type="text" class="form-control" id="voucherNumber" name="voucherNumber" value="<?php echo $voucherNumber; ?>" readonly>
                                                                </div>
                                                                <div class="input-group-append" style="width: 40%;">
                                                                    <button class="btn btn-sm btn-secondary" type="button" style="width: 145px; font-weight: 600; color: white;">
                                                                        Voucher No
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="input-group payment_date" style="margin-top:10px;">
                                                                <div class="input-group input-group-sm">
                                                                    <div class="kt-input-icon kt-input-icon--left" style="width:60%">
                                                                        <input type="date" name="payment_date" class="form-control form-control-sm rec_date" style="color:#495057;font-weight: 400;" id="payment_date">
                                                                    </div>
                                                                    <div class="input-group-append" style="width:40%">
                                                                        <button class="btn btn-sm btn-secondary" type="button" style="width: 145px; font-weight:600;color:white;">Payment Date</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="input-group payment_date" style="margin-top:10px;">
                                                                <div class="input-group input-group-sm">
                                                                    <div class="kt-input-icon kt-input-icon--left" style="width:60%">
                                                                        <input type="date" name="salary_month" class="form-control form-control-sm" style="color:#495057;font-weight: 400;" id="salary_month">
                                                                    </div>
                                                                    <div class="input-group-append" style="width:40%">
                                                                        <button class="btn btn-sm btn-secondary" type="button" style="width: 145px; font-weight:600;color:white;">Salary Month</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="" style="margin-top:10px;margin-bottom:10px;">
                                                                <select style="color:black!important;font-weight:bold;" class="form-control" id="payment_mode" name="payment_mode">
                                                                    <option value=''>Select Payment Mode</option>
                                                                    <option value='Cash'>Cash</option>
                                                                    <option value='Payable'>Payable</option>
                                                                    <option value='Bank Transfer'>Bank Transfer</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                                        <div class="col-md-12" style="border-top: 0.1rem solid #ada7a7;">
                                                            <div class="row" style="margin-top:10px">
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <h6 style="font-weight:400;">Employee<a class="add_employee_btn" style="float:right;font-size: 10px;" href="add_employee.php">Add Employee</a></h6>
                                                                        <?php
                                                                        // Fetch employee names from the database
                                                                        $sql = "SELECT id, name FROM employees_data";
                                                                        $result = $conn->query($sql);
                                                                        if ($result->num_rows > 0) {
                                                                            echo '<select style="width:100%;" placeholder="Select Employee" class="form-control m-select2 select_employee" id="select_employee" name="employee" onchange="loadSelectedEmployee(this.value)">';
                                                                            echo '<option value="" disabled selected>Select Employee</option>';
                                                                            while ($row = $result->fetch_assoc()) {
                                                                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                                                            }
                                                                            echo '</select>';
                                                                        } else {
                                                                            echo '<select style="width:100%;" name="employee" placeholder="Select Employee" class="form-control m-select2 select_employee" id="select_employee">';
                                                                            echo '<option value="">No employees found</option>';
                                                                            echo '</select>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="form-group">
                                                                        <h6 style="font-weight:400;">Amount </h6>
                                                                        <div class="input-group input-group-sm">
                                                                            <div class="input-group-append" style="width:100%">
                                                                                <input type="text" id="amount" name="amount" required="" class="form-control" placeholder="Amount" value="">
                                                                            </div>
                                                                        </div>      
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-left: 0px;margin-right: 0px;margin-bottom: 10px;">
                                                        <div class="col-md-7" style="padding: 0px;border-top: 0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
                                                            <textarea class="form-control" id="notes" placeholder="Note" aria-invalid="false" style="margin: 0px;height: 100%;" maxlength="990" rows="5" disabled="">Employee Salary</textarea>
                                                        </div>
                                                        <div class="col-md-5" style="border-top: 0.1rem solid #ada7a7;border-left:  0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
                                                            <h6 class="p-2" style="color:black;display: block;">For <span id="seller_names" ></span></h6>
                                                            <h6 class="pl-2" style="padding-top: 75px; color:black;display: block;">Authorised Signatory</h6>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                                        <div class="col-12" data-toggle="collapse" data-target="#collapseTwo4">
                                                            <h5 style="font-weight:400;" class="dropdown-toggle">For Internal Use</h5>
                                                        </div>
                                                    </div>
                                                    <div id="collapseTwo4" class="collapse show" style="margin-top: 10px;">
                                                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <h6 style="font-weight:400;">Net Pay</h6>
                                                                    <input type="number" id="netpay" class="form-control m-input" style="padding: 6px;" disabled="" value="0" placeholder="Net Pay" maxlength="8">
                                                                </div> 
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <h6 style="font-weight:400;">CTC</h6>
                                                                    <input type="number" id="ctc_pay" class="form-control m-input" style="padding: 6px;" disabled="" value="0" placeholder="CTC" maxlength="8">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                                                            <div class="col-12">
                                                                <!-- Bank List Dropdown -->
                                                                <div class="form-group bank_display" style="display:none;">
                                                                    <h6 style="font-weight:400;">Bank List<span style="float:right;color:#5867dd;" id="recon_amount"></span></h6>
                                                                    <select class="form-control col-md-12 m-input form-control-sm m-select2 bank_list" id="bank_list"></select>
                                                                </div>

                                                                <!-- Employee List Dropdown (for Cash payment mode) -->
                                                                <div class="form-group employee_display" style="display:none;">
                                                                    <h6 style="font-weight:400;">Employee List<span style="float:right;color:#5867dd;" id="recon_amount"></span></h6>
                                                                    <select class="form-control col-md-12 m-input form-control-sm m-select2 employee_list" id="employee_list">
                                                                        <option value="" disabled selected>Select Employee</option>
                                                                        <?php
                                                                        // Fetch employee names from the database again for the Cash mode dropdown
                                                                        $result = $conn->query($sql);
                                                                        if ($result->num_rows > 0) {
                                                                            while ($row = $result->fetch_assoc()) {
                                                                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                                                            }
                                                                        } else {
                                                                            echo '<option value="">No employees found</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 tab-content" id="reconcile_tab" style="display:none;">
                                        <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
                                            <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                                                <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                                                    <div class="col-12" id="reconcile_table" style="padding: 0px;margin-top:20px;">
                                                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                                                            <div class="col-6">
                                                                <div class="form-group" style="font-size: .875rem;">
                                                                    <input type="number" name="tds" id="tds" placeholder="TDS Deduction" class="form-control m-input" style="padding: 6px;" required="" value="" maxlength="8">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="number" name="p_tax" id="p_tax" class="form-control m-input" placeholder="Professional Tax Deduction" style="padding: 6px;" required="" value="" maxlength="8">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="number" name="pf" id="pf" class="form-control m-input" placeholder="Employee PF" style="padding: 6px;" required="" value="" maxlength="8">
                                                                </div> 
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="number" name="er_pf" id="er_pf" class="form-control m-input" placeholder="Employer PF" style="padding: 6px;" required="" value="" maxlength="8">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="number" name="esi" id="esi" class="form-control m-input" placeholder="Employee ESI" style="padding: 6px;" required="" value="" maxlength="8">
                                                                </div> 
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="number" name="er_esi" id="er_esi" class="form-control m-input" placeholder="Employer ESI" style="padding: 6px;" required="" value="" maxlength="8">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="number" name="welfare" id="welfare" class="form-control m-input" style="padding: 6px;" placeholder="Labour Welfare Fund" required="" value="" maxlength="8">
                                                                </div> 
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <input type="number" name="others" id="others" class="form-control m-input" style="padding: 6px;" placeholder="Others" required="" value="" maxlength="8">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" style="padding-right: 0px; text-align: center;" id="receipt_balance_tab">
                                                        <span id="receipt_balance"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-left: 274px;">
                                        <ul class="nav justify-content-end mb-2">
                                            <li class="nav-item col-md-5">
                                                <button type="submit" class="btn btn-sm btn-success create-invoice-btn"><i class="fa fa-plus"></i>&nbsp;<span class="voucher-text">Create Salary Voucher</span></button>
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initial setup
            $(".create_tab").addClass("active");

            // Switching tabs
            $(".create_tab, .reconcile_tab").on("click", function () {
                $(".create_tab, .reconcile_tab").removeClass("active");
                $(this).addClass("active");

                var tabId = $(this).data("tab-id");
                $(".tab-content").hide();
                $("#" + tabId).show();
            });

            // Hide initial state
            $('.bank_display').hide();
            $('.employee_display').hide();

            // Handle change event on payment_mode dropdown
            $('#payment_mode').change(function () {
                // Hide all fields initially
                $('.bank_display').hide();
                $('.employee_display').hide();

                // Get the selected value
                var selectedOption = $(this).val();

                // Show/hide fields based on the selected option
                if (selectedOption === 'Cash') {
                    $('.employee_display').show();
                } else if (selectedOption === 'Bank Transfer') {
                    $('.bank_display').show();
                }
            });

            // Function to load bank details for the selected employee
            function loadSelectedEmployee(employeeId) {
                if (employeeId !== "") {
                    // Fetch bank details for the selected employee
                    $.ajax({
                        url: 'get_employee_details.php',
                        type: 'POST',
                        data: { employeeId: employeeId },
                        success: function (data) {
                            // Update the bank list dropdown with the data received
                            $('#bank_list').html(data);
                        }
                    });
                }
            }

            // Bind the function to the employee dropdown change event
            $('#select_employee').change(function () {
                loadSelectedEmployee($(this).val());
            });
        });
    </script>
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>

</html>