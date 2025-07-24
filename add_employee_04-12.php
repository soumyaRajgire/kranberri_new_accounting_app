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
    <title>Add Employee</title>
    <meta charset="utf-8">
    <?php include("header_link.php");?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
            border: 2px solid grey; /* Define your desired border style and color here */
        }
        .custom-table th {
            width: 300px;
            font-weight: bold;
            font-size: 16px;
        }
        .custom-table td {
            width: 700px;
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
                                <h4 class="m-b-10">Employee Onboarding</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Employee Onboarding</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin: 15px;">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab" aria-selected="true">
                     Information
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab2" role="tab">
                     Others
                    </a>
                  </li>
                </ul>   
            </div>


<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $salutation = $_POST['salutation'];
    $name = $_POST['name'];
    $employee_id = $_POST['employee_id'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $officemail = $_POST['officemail'];
    $personalmobile = $_POST['personalmobile'];
    $doj = $_POST['doj'];
    $branch = $_POST['branch'];
    $accountname = $_POST['accountname'];
    $accountnumber = $_POST['accountnumber'];
    $ifsc = $_POST['ifsc'];
    $accounttype = $_POST['accounttype'];
    $bankname = $_POST['bankname'];
    $bankbranch = $_POST['bankbranch'];
    $aadhar = $_POST['aadhar'];
    $pan = $_POST['pan'];
    $uan = $_POST['uan'];
    $esi = $_POST['esi'];

// Assuming you have a database connection named $conn
$stmt = $conn->prepare("INSERT INTO employees_data (salutation, name, employee_id, department, designation, officemail, personalmobile, doj, branch, accountname, accountnumber, ifsc, accounttype, bankname, bankbranch, aadhar, pan, uan, esi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("sssssssssssssssssss", $salutation, $name, $employee_id, $department, $designation, $officemail, $personalmobile, $doj, $branch, $accountname, $accountnumber, $ifsc, $accounttype, $bankname, $bankbranch, $aadhar, $pan, $uan, $esi);

if ($stmt->execute()) {
    // Data inserted successfully
    echo '<script>alert("Data inserted successfully!");</script>';
    echo '<script>window.location = "view_employees.php";</script>';
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
}
?>

<form id="add-employee" action="add_employee.php" method="post">
    <div class="card" style="margin: 15px;">
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab1" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label for="salutation" class="form-control-label">Salutation: <span class="required_sty" style="color:red;">*</span></label>
                            <select class="form-control select_reset" id="salutation" name="salutation">
                                <option value="">Title</option>
                                <option value="mr">Mr.</option>
                                <option value="mrs">Mrs.</option>
                                <option value="miss">Miss.</option>
                                <option value="ms">Ms.</option>
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label class="form-control-label">Name: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" style="text-transform: capitalize;" class="form-control" name="name" oninput="this.value=this.value.replace(/[^A-Za-z_@.!#$%&*-+\s]/g,'');">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-control-label">Employee ID: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="employee_id" id="employee_id" placeholder="" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Department:</label>
                            <select class="form-control depapp" id="department" name="department">
                                <option value="">select department</option>
                              
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Designation:</label>
                            <select class="form-control desapp" id="designation" name="designation">
                                <option value="">select designation</option>
                               
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Office mail:</label>
                            <input type="email" id="email" class="form-control" name="officemail" id="officemail" placeholder="">
                            <p id="email_validation" style="display:none;color:red;">Enter valid Email Address</p>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Personal Mobile: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control required" id="number_validation" name="personalmobile" id="personalmobile" placeholder="" minlength="8" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Date of joining: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="date" class="form-control required" id="kt_datepicker_1" name="doj" id="doj" placeholder="Select date">
                        </div>
                        <div class="col-lg-6">
    <label class="form-control-label">Branch: <span class="required_sty" style="color:red;">*</span></label>
    <label class="form-control-label" style="float:right;" id="add_branch"><a href="organisation.php"> Add Branch</a></label>
    <select class="form-control branchapp required" id="branch" data-size="5" data-live-search="true" name="branch">
        <option value="">select branch</option>
        <!-- Options will be populated dynamically using JavaScript -->
    </select>
</div>

                    </div>
                </div>
                <div class="tab-pane" id="tab2" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Account Name:</label>
                            <input type="text" style="text-transform: capitalize;" class="form-control" name="accountname" placeholder="">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Account Number:</label>
                            <input type="number" class="form-control" name="accountnumber" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">IFSC Code:</label>
                            <input type="text" style="text-transform: uppercase;" class="form-control" name="ifsc" placeholder="" maxlength="11">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Account Type:</label>
                            <select class="form-control select_reset" name="accounttype">
                                <option value="" selected="">Please Select</option>
                                <option value="current account">Current Account</option>
                                <option value="savings account">Savings Account</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Bank Name:</label>
                            <input type="text" style="text-transform: uppercase;" class="form-control select_reset" name="bankname">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Branch Name:</label>
                            <input type="text" style="text-transform: capitalize;" class="form-control" name="bankbranch" placeholder="">
                        </div>
                    </div>
                    <div class="kt-section__content kt-section__content--solid">
                        <div class="kt-divider">
                            <span></span>
                            <span style="font-size:18px">Other Details</span>
                            <span></span>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Aadhar Number:</label>
                            <input type="text" class="form-control" name="aadhar" placeholder="" id="aadhaar-input">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Pan Number:</label>
                            <input type="text" style="text-transform: uppercase;" class="form-control" name="pan" placeholder="" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Uan Number:</label>
                            <input type="text" class="form-control" id="number_validation" name="uan" placeholder="" maxlength="12">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">ESI Number:</label>
                            <input type="text" class="form-control" id="number_validation" name ="esi" placeholder="" maxlength="10">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<div class="d-flex justify-content-end mx-3">
    <button class="btn btn-success" type="submit" id="formSubmit">Submit</button>
    <button class="btn btn-danger ml-2" type="button" id="formClose" onclick="window.location.href='view_employees.php'">Cancel</button>

</div>
</form>

     </div>
    </section>
    <script>
    // Function to fetch and populate branch names in the dropdown
    function fetchBranches() {
        // Make an Ajax request to get_branches.php
        $.ajax({
            type: 'GET',
            url: 'get_branches.php?fetch_dropdown=1',
            dataType: 'json',
            success: function (branches) {
                // Update the branch dropdown options
                var branchDropdown = $('#branch');
                branchDropdown.empty();
                branchDropdown.append($('<option>', { value: '', text: 'select branch' }));

                $.each(branches, function (index, branch) {
                    branchDropdown.append($('<option>', { value: branch.officeName , text: branch.officeName }));
                });
            },
            error: function (error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }

    // Call the fetchBranches function when the page is loaded or when needed
    $(document).ready(function () {
        fetchBranches();
    });
</script>
<!-- Place this script in your HTML file, after including jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    // Function to fetch and populate department names in the dropdown
    function fetchDepartments() {
        // Make an Ajax request to get_departments.php
        $.ajax({
            type: 'GET',
            url: 'get_departments.php?fetch_dropdown=1',
            dataType: 'json',
            success: function (departments) {
                // Update the department dropdown options
                var departmentDropdown = $('#department');
                departmentDropdown.empty();
                departmentDropdown.append($('<option>', { value: '', text: 'select department' }));

                $.each(departments, function (index, department) {
                    departmentDropdown.append($('<option>', { value: department.departmentName, text: department.departmentName }));
                });
            },
            error: function (error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }

    // Call the fetchDepartments function when the page is loaded or when needed
    $(document).ready(function () {
        fetchDepartments();
    });
</script>
<script>
    // Function to fetch and populate designation names in the dropdown
    function fetchDesignations() {
        // Make an Ajax request to get_designations.php
        $.ajax({
            type: 'GET',
            url: 'get_designations.php?fetch_dropdown=1',
            dataType: 'json',
            success: function (designations) {
                // Update the designation dropdown options
                var designationDropdown = $('#designation');
                designationDropdown.empty();
                designationDropdown.append($('<option>', { value: '', text: 'select designation' }));

                $.each(designations, function (index, designation) {
                    designationDropdown.append($('<option>', { value: designation.designationName, text: designation.designationName }));
                });
            },
            error: function (error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }

    // Call the fetchDesignations function when the page is loaded or when needed
    $(document).ready(function () {
        fetchDesignations();
    });
</script>



    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>
