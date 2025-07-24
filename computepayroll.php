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
    <title>iiiQbets - Payroll</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>

    <style>
        .edit-date {
            display: flex;
            align-items: center;
        }
        .edit-date input {
            width: 80px;
            margin-right: 5px;
        }
        .history-dropdown {
            position: relative;
            display: inline-block;
        }
        .history-dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .history-dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .history-dropdown-content a:hover {
            background-color: #f1f1f1
        }
        .history-dropdown:hover .history-dropdown-content {
            display: block;
        }
        .gross-pay {
            width: 100px; /* Adjust this value to decrease the width */
        }
        .salary-details {
            display: flex;
            align-items: center;
            gap: 10px; /* Adjust spacing as needed */
        }
        .salary-details input {
            flex-grow: 1;
        }
        .salary-details .btn {
            flex-shrink: 0;
        }
        .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }
        .next-btn {
            margin-top: 20px;
            float: right;
        }
        /* New CSS for tabs */
      
        /* New CSS for tabs */
/* .nav-tabs {
    display: flex;
    justify-content: space-around;
    border-bottom: none;
} */
.nav-item {
    flex: 1;
    text-align: center;
}
/* .nav-link {
    border: none;
    padding: 15px;
    font-size: 18px;
    background: #f9f9f9;
    border-radius: 10px;
    margin: 0 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
} */
.nav-link.active {
    background-color: #f1f1f1;
    color: #495057;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
}
.step-number {
    font-size: 24px;
    color: #5b5bff;
    font-weight: bold;
}
.step-description {
    font-size: 14px;
    color: #6c757d;
}
.container-custom {
    max-width: 1200px;
    margin: 0 auto;
    margin-left:-10px;
}
.payroll-details p {
    margin-bottom: 5px;
    font-weight: bold;
    color: #495057;
}

.value {
    font-weight: bold;
    color: #563dff; /* Blue color to match the image */
}

.card-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

.text-right {
    text-align: right;
}
.verify-reports-btn a {
        color: white !important;
        text-decoration: none;
    }

    .verify-reports-btn {
        background-color: #007bff; /* Bootstrap primary color */
        border: none;
        padding: 10px 20px;
    }

    .verify-reports-btn:hover a {
        color: #fff;
        text-decoration: none;
    }
    </style>
</head>
<body>
    <?php include("menu.php"); ?>

    <section class="pcoded-main-container">
        <div class="pcoded-content">
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
            <div class="container mb-4 container-custom">
                <ul class="nav nav-tabs" id="payrollTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="salary-tab" data-toggle="tab" href="#salary" role="tab" aria-controls="salary" aria-selected="true">
                            <span class="step-number">1</span>
                            <span class="step-description">Salary<br>Setup Salary Details</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">
                            <span class="step-number">2</span>
                            <span class="step-description">Review and Run<br>Setup Payable Days</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="false">
                            <span class="step-number">3</span>
                            <span class="step-description">Payroll Summary<br>Confirm and Submit</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content container-custom" id="payrollTabsContent">
                <div class="tab-pane fade show active" id="salary" role="tabpanel" aria-labelledby="salary-tab">
                    <!-- Salary tab content goes here -->
                    <div class="row mt-5">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Salary Details</h5>
                                    <a href="add_employee.php" class="btn btn-info" style="color: #fff !important; float:right;">Add Employee</a>
                                </div>
                                <div class="card-body table-border-style">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th>Empl-ID</th>
                                                    <th>Emp-Name</th>
                                                    <th>Gross Pay</th>
                                                    <th>Effective Date</th>
                                                    <th>PF</th>
                                                    <th>ESI</th>
                                                    <th>Take Home</th>
                                                    <th>CTC</th>
                                                </tr>
                                            </thead>
                                            <tbody id="employeeTableBody">
                                                <!-- Data will be inserted here by JavaScript -->
                                                <?php
                                                $result = $conn->query("SELECT id, salutation, name, employee_id, doj, esi FROM employees_data");
                                                $currentMonthYear = date('M-Y');
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo '<tr>';
                                                        echo '<td><a href="employee_profile.php?id=' . $row['id'] . '" style="color: blue;">' . $row['employee_id'] . '</a></td>';
                                                        echo '<td>' . $row['salutation'] . ' ' . $row['name'] . '</td>';
                                                        echo '<td>
                                                        <div class="salary-details">
                                                            <input type="number" name="gross_pay[' . $row['id'] . ']" class="form-control gross-pay" data-id="' . $row['id'] . '" placeholder="Enter Gross Pay">
                                                            <div class="history-dropdown">
                                                                <span class="history-link btn btn-primary" data-id="' . $row['id'] . '">History</span>
                                                            </div>
                                                            <button class="btn btn-primary cb-btn" data-id="' . $row['id'] . '">C&B</button>
                                                        </div>
                                                      </td>';
                                                        echo '<td>
                                                                <div class="edit-date">
                                                                    <i class="fa fa-edit editButton" aria-hidden="true" data-id="' . $row['id'] . '"></i><br>
                                                                    <input type="text" class="form-control datepicker" id="effective_date_' . $row['id'] . '" value="' . $currentMonthYear . '" readonly>
                                                                </div>
                                                                <div>
                                                                    <small>DOJ: ' . date('d M Y', strtotime($row['doj'])) . '</small>
                                                                </div>
                                                              </td>';
                                                        echo '<td>
                                                                <span id="pf_' . $row['id'] . '"></span>
                                                                <button class="btn btn-sm btn-primary pf-edit-btn" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>
                                                              </td>';
                                                        echo '<td><span id="esi_' . $row['id'] . '"></span></td>';
                                                        echo '<td><span id="take_home_' . $row['id'] . '"></span></td>';
                                                        echo '<td><span id="ctc_' . $row['id'] . '"></span></td>';
                                                        echo '</tr>';
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="8" class="text-center">No records found</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
   
                                        <button class="btn btn-primary next-btn">NEXT STEP</button>
                                    </div>
                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               
                <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                    <!-- Review and Run content here -->
                   <?php include 'setup_payabledays.php'; ?>
                </div>
                
                <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab">
                <?php include 'confirm&submit.php'; ?>
</div>

           
    </section>
<?php
include 'c&b.php';
?>
 
    
    <!-- PF Selection Modal -->
    <div class="modal fade" id="pfModal" tabindex="-1" role="dialog" aria-labelledby="pfModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pfModalLabel">Select PF Option</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <input type="hidden" id="pf_employee_id">
                        <div class="form-group">
                            <label><input type="radio" name="pf_option" value="no_pf"> NO-PF<span>Provident Fund (PF) is not deducted from employee's salary</span></label>
                        </div>
                        <div class="form-group">
                            <label><input type="radio" name="pf_option" value="12_basic"> 12% of Basic<span>12% of the employee's salary goes towards contribution to Provident Fund</span></label>
                        </div>
                        <div class="form-group">
                            <label><input type="radio" name="pf_option" value="12_gross_ex_hra"> 12% gross excluding HRA<span>The employer's monthly contribution is restricted to a maximum amount of Rs 1,800</span></label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePfOption">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Salary History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Gross Amount</th>
                                <th>Effective</th>
                                <th>PF</th>
                                <th>ESI</th>
                                <th>CTC Values</th>
                                <th>Take Home</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <tr>
                                <td colspan="6" class="text-center">No Records Found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <script>
   $(document).ready(function() {
    // Handle the NEXT STEP button click
    $('.btn-primary.next-btn').click(function() {
        var activeTab = $('.nav-tabs .active');
        var nextTab = activeTab.parent().next('li').find('a');
        if(nextTab.length > 0) {
            nextTab.tab('show');
        }
    });

    // Handle the PREVIOUS button click
    $('.btn-secondary').click(function() {
        var activeTab = $('.nav-tabs .active');
        var prevTab = activeTab.parent().prev('li').find('a');
        if(prevTab.length > 0) {
            prevTab.tab('show');
        }
    });
});


</script>
<script>
$(document).ready(function(){
    $('.cb-btn').on('click', function(){
        var employeeId = $(this).data('id');
        
        // Populate the modal with data here, if needed
        // For example, you can use AJAX to fetch data for the employee

        $('#cnBModal').modal('show');
    });

    $('#saveBtn').on('click', function(){
        var basicSalary = $('#basicSalary').val();
        var grossTotal = $('#grossTotal').val();
        var ctcPerMonth = $('#ctcPerMonth').val();
        var ctcPerAnnum = $('#ctcPerAnnum').val();

        // Use AJAX to save the data to the database

        $('#cnBModal').modal('hide');
    });
});
</script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.pf-edit-btn', function() {
                var employeeId = $(this).data('id');
                $('#pf_employee_id').val(employeeId);
                $('#pfModal').modal('show');
            });

            $('#savePfOption').click(function() {
                var employeeId = $('#pf_employee_id').val();
                var selectedOption = $('input[name="pf_option"]:checked').val();
                // You can add AJAX here to save the PF option in the database
                $('#pf_' + employeeId).text(selectedOption);
                $('#pfModal').modal('hide');
            });

            $(document).on('input', '.gross-pay', function() {
                var id = $(this).data('id');
                var grossPay = parseFloat($(this).val()) || 0;
                $('#pf_' + id).text(calculatePF(grossPay));
                $('#esi_' + id).text(calculateESI(grossPay));
                $('#take_home_' + id).text(calculateTakeHome(grossPay));
                $('#ctc_' + id).text(calculateCTC(grossPay));
            });

            // Initialize the datepicker
            $(".datepicker").datepicker({
                dateFormat: "M-yy",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                onClose: function(dateText, inst) {
                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                    $(this).val($.datepicker.formatDate('M-yy', new Date(year, month, 1)));
                }
            });

            $(".datepicker").focus(function() {
                $(".ui-datepicker-calendar").hide();
                $("#ui-datepicker-div").position({
                    my: "center top",
                    at: "center bottom",
                    of: $(this)
                });
            });

            $(document).on('click', '.editButton', function() {
                var id = $(this).data('id');
                $('#effective_date_' + id).removeAttr('readonly').datepicker('show');
            });

            // Handle history link click
            $(document).on('click', '.history-link', function() {
                var employeeId = $(this).data('id');
                // Fetch history data using AJAX (example data provided here)
                var historyData = [
                    {gross: 'INR 15,000', effective: 'AUG 2024', pf: 'INR 1,530', esi: 'INR 488', ctc: 'INR 12,982', takeHome: 'INR 12,000'},
                    // Add more history data as needed
                ];
                var tableBody = '';
                if (historyData.length > 0) {
                    historyData.forEach(function(row) {
                        tableBody += '<tr><td>' + row.gross + '</td><td>' + row.effective + '</td><td>' + row.pf + '</td><td>' + row.esi + '</td><td>' + row.ctc + '</td><td>' + row.takeHome + '</td></tr>';
                    });
                } else {
                    tableBody = '<tr><td colspan="6" class="text-center">No Records Found</td></tr>';
                }
                $('#historyTableBody').html(tableBody);
                $('#historyModal').modal('show');
            });
        });

        // Placeholder functions for calculations
        function calculatePF(grossPay) {
            return 'INR ' + (grossPay * 0.12).toFixed(2);
        }

        function calculateESI(grossPay) {
            return 'INR ' + (grossPay * 0.075).toFixed(2);
        }

        function calculateTakeHome(grossPay) {
            return 'INR ' + (grossPay - calculatePF(grossPay) - calculateESI(grossPay)).toFixed(2);
        }

        function calculateCTC(grossPay) {
            return 'INR ' + grossPay;
        }
    </script>
      <!-- <script src="assets/js/vendor-all.min.js"></script> -->
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
<script src="assets/js/myscript.js"></script>
</body>
</html>
