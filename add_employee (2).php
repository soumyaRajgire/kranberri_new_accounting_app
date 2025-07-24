<?php
session_start(); 
if(!isset($_SESSION['LOG_IN'])){
   header("Location: login.php");
   exit();
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
include("config.php");
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Employee</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="">
    <!-- Pre-loader -->
    <?php include("menu.php"); ?>
    <!-- Header -->
    <style>
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
            border: 2px solid grey;
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data and sanitize
    $salutation = isset($_POST['salutation']) ? mysqli_real_escape_string($conn, $_POST['salutation']) : '';
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $employee_id = isset($_POST['employee_id']) ? mysqli_real_escape_string($conn, $_POST['employee_id']) : '';
    $department = isset($_POST['department']) ? mysqli_real_escape_string($conn, $_POST['department']) : '';
    $designation = isset($_POST['designation']) ? mysqli_real_escape_string($conn, $_POST['designation']) : '';
    $officemail = isset($_POST['officemail']) ? mysqli_real_escape_string($conn, $_POST['officemail']) : '';
    $personalmobile = isset($_POST['personalmobile']) ? mysqli_real_escape_string($conn, $_POST['personalmobile']) : '';
    $personalemail = isset($_POST['personalemail']) ? mysqli_real_escape_string($conn, $_POST['personalemail']) : '';
    $doj = isset($_POST['doj']) ? mysqli_real_escape_string($conn, $_POST['doj']) : '';
    $branch = isset($_POST['branch']) ? mysqli_real_escape_string($conn, $_POST['branch']) : '';
    $password = isset($_POST['password']) ? md5(mysqli_real_escape_string($conn, $_POST['password'])) : '';
    $accountname = isset($_POST['accountname']) ? mysqli_real_escape_string($conn, $_POST['accountname']) : '';
    $accountnumber = isset($_POST['accountnumber']) ? mysqli_real_escape_string($conn, $_POST['accountnumber']) : '';
    $ifsc = isset($_POST['ifsc']) ? mysqli_real_escape_string($conn, $_POST['ifsc']) : '';
    $accounttype = isset($_POST['accounttype']) ? mysqli_real_escape_string($conn, $_POST['accounttype']) : '';
    $bankname = isset($_POST['bankname']) ? mysqli_real_escape_string($conn, $_POST['bankname']) : '';
    $bankbranch = isset($_POST['bankbranch']) ? mysqli_real_escape_string($conn, $_POST['bankbranch']) : '';
    $aadhar = isset($_POST['aadhar']) ? mysqli_real_escape_string($conn, $_POST['aadhar']) : '';
    $pan = isset($_POST['pan']) ? mysqli_real_escape_string($conn, $_POST['pan']) : '';
    $uan = isset($_POST['uan']) ? mysqli_real_escape_string($conn, $_POST['uan']) : '';
    $esi = isset($_POST['esi']) ? mysqli_real_escape_string($conn, $_POST['esi']) : '';
    $username = $officemail; // Assuming the username is the official email

    // Handle file upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $originalFileName = basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        $uniqueFileName = uniqid() . '.' . $imageFileType;
        $imagePath = $targetDir . $uniqueFileName;

        // Check if file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            throw new Exception("File is not an image.");
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Move uploaded file to target directory
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            throw new Exception("Sorry, there was an error uploading your file.");
        }
    }

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Insert data into employees_data
        $sql = "INSERT INTO employees_data (salutation, name, employee_id, department, designation, officemail, personalmobile, personalemail, doj, branch, password, image_path, accountname, accountnumber, ifsc, accounttype, bankname, bankbranch, aadhar, pan, uan, esi) VALUES ('$salutation', '$name', '$employee_id', '$department', '$designation', '$officemail', '$personalmobile', '$personalemail', '$doj', '$branch', '$password', '$imagePath', '$accountname', '$accountnumber', '$ifsc', '$accounttype', '$bankname', '$bankbranch', '$aadhar', '$pan', '$uan', '$esi')";

        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error: " . mysqli_error($conn));
        }

        // Get the last inserted id
        $last_id = mysqli_insert_id($conn);

        // Insert data into employee_login
        $login_sql = "INSERT INTO employee_login (id, username, password) VALUES ('$last_id', '$username', '$password')";
        if (!mysqli_query($conn, $login_sql)) {
            throw new Exception("Error: " . mysqli_error($conn));
        }

        // Commit transaction
        mysqli_commit($conn);
        echo "<script>
                alert('Employee added successfully');
                window.location.href = 'view_employees.php';
              </script>";
        exit;
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}
ob_end_flush(); // Flush the output buffer
?>

<form id="add-employee" action="add_employee.php" method="post" enctype="multipart/form-data">
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
                    <!-- Personal Email Field -->
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Personal Email:</label>
                            <input type="email" class="form-control" name="personalemail" id="personalemail" placeholder="">
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
                        <div class="form-group row">
    <div class="col-lg-6">
        <label class="form-control-label">Password: <span class="required_sty" style="color:red;">*</span></label>
        <div class="input-group">
            <input type="password" class="form-control" name="password" id="password" placeholder="">
            <div class="input-group-append">
                <span class="input-group-text" onclick="togglePassword()">
                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
    <!-- <label for="image" class="col-sm-2 col-form-label">Upload Image:</label> -->
    <label for="image">Upload Image: <span class="required_sty" style="color:red;">*</span></label>
                               
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*" capture="environment" required>
                               
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
        $.ajax({
            type: 'GET',
            url: 'get_branches.php?fetch_dropdown=1',
            dataType: 'json',
            success: function (branches) {
                var branchDropdown = $('#branch');
                branchDropdown.empty();
                branchDropdown.append($('<option>', { value: '', text: 'select branch' }));

                $.each(branches, function (index, branch) {
                    branchDropdown.append($('<option>', { value: branch.officeName , text: branch.officeName }));
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    $(document).ready(function () {
        fetchBranches();
    });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
    // Function to fetch and populate department names in the dropdown
    function fetchDepartments() {
        $.ajax({
            type: 'GET',
            url: 'get_departments.php?fetch_dropdown=1',
            dataType: 'json',
            success: function (departments) {
                var departmentDropdown = $('#department');
                departmentDropdown.empty();
                departmentDropdown.append($('<option>', { value: '', text: 'select department' }));

                $.each(departments, function (index, department) {
                    departmentDropdown.append($('<option>', { value: department.departmentName, text: department.departmentName }));
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    $(document).ready(function () {
        fetchDepartments();
    });
    </script>
    <script>
    // Function to fetch and populate designation names in the dropdown
    function fetchDesignations() {
        $.ajax({
            type: 'GET',
            url: 'get_designations.php?fetch_dropdown=1',
            dataType: 'json',
            success: function (designations) {
                var designationDropdown = $('#designation');
                designationDropdown.empty();
                designationDropdown.append($('<option>', { value: '', text: 'select designation' }));

                $.each(designations, function (index, designation) {
                    designationDropdown.append($('<option>', { value: designation.designationName, text: designation.designationName }));
                });
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    $(document).ready(function () {
        fetchDesignations();
    });
    </script>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>
