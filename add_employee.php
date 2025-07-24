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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
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

     
    h5 {
      font-size: 1.25rem;
      color: #3b3b3b;
      font-weight: bold;
    }

    h6 {
      font-size: 1rem;
      font-weight: bold;
      color: #3b3b3b;
    }

    p {
      color: #666;
      font-size: 0.875rem;
      margin-top: 0.2rem;
    }
    .toggle-section {
      display: none;
      margin-top: 10px;
    }
    .d-flex {
      display: flex;
    }

    .justify-content-between {
      justify-content: space-between;
    }
   /* Toggle Switch Styling */
   .switch {
      position: relative;
      display: inline-block;
      width: 40px;
      height: 24px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: 0.4s;
      border-radius: 24px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      border-radius: 50%;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: 0.4s;
    }

    input:checked + .slider {
      background-color: #4CAF50;
    }

    input:checked + .slider:before {
      transform: translateX(16px);
    }
    
    /* Highlighted Section Heading */
    .highlighted {
      color: #4CAF50;
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
                        <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab">Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab2" role="tab">Address</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab3" role="tab">Bank</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab4" role="tab">Access</a>
                    </li>
                </ul>   
            </div>


            <?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input function
    function sanitize_input($data, $conn) {
        return mysqli_real_escape_string($conn, trim($data));
    }

    // Gather and sanitize POST data
    $salutation = isset($_POST['salutation']) ? sanitize_input($_POST['salutation'], $conn) : NULL;
    $name = sanitize_input($_POST['name'], $conn);
    $employee_id = sanitize_input($_POST['employee_id'], $conn);
    $employee_status = sanitize_input($_POST['employee_status'], $conn);
    $branch_id = isset($_POST['branch_id']) ? intval($_POST['branch_id']) : 0;
    $business_id = isset($_POST['business_id']) ? intval($_POST['business_id']) : 0;
    $officemail = sanitize_input($_POST['officemail'], $conn);
    $officemobile = sanitize_input($_POST['officemobile'], $conn);
    $personalmobile = !empty($_POST['personalmobile']) ? sanitize_input($_POST['personalmobile'], $conn) : null;
    $personalemail = sanitize_input($_POST['personalemail'], $conn);
    $department = sanitize_input($_POST['department'], $conn);
    $designation = sanitize_input($_POST['designation'], $conn);
    $reporting_to = isset($_POST['reporting_to']) ? sanitize_input($_POST['reporting_to'], $conn) : null;
    $fathername = sanitize_input($_POST['fathername'], $conn);
    $employment_type = sanitize_input($_POST['employment_type'], $conn);
    $gender = sanitize_input($_POST['gender'], $conn);
    $marital_status = sanitize_input($_POST['marital_status'], $conn);
    $aadhar = sanitize_input($_POST['aadhar'], $conn);
    $pan = sanitize_input($_POST['pan'], $conn);
    $religion = sanitize_input($_POST['religion'], $conn);
    $blood_group = sanitize_input($_POST['blood_group'], $conn);
    $emergency_contact = sanitize_input($_POST['emergency_contact'], $conn);
    $branch = sanitize_input($_POST['branch'], $conn);
    $uan = sanitize_input($_POST['uan'], $conn);
    $esi = sanitize_input($_POST['esi'], $conn);
    $doj = sanitize_input($_POST['doj'], $conn);
    $doe = sanitize_input($_POST['doe'], $conn);
    $checkin_time = sanitize_input($_POST['checkin_time'], $conn);
    $checkout_time = sanitize_input($_POST['checkout_time'], $conn);
    $ctc = sanitize_input($_POST['ctc'], $conn);
    $gstin = sanitize_input($_POST['gstin'], $conn);
    $accountname = sanitize_input($_POST['accountname'], $conn);
    $accountnumber = sanitize_input($_POST['accountnumber'], $conn);
    $ifsc = sanitize_input($_POST['ifsc'], $conn);
    $accounttype = sanitize_input($_POST['accounttype'], $conn);
    $bankname = sanitize_input($_POST['bankname'], $conn);
    $bankbranch = sanitize_input($_POST['bankbranch'], $conn);
    $hashed_password = isset($_POST['password']) ? md5(sanitize_input($_POST['password'], $conn)) : '';

    // Permissions
    $administrator = isset($_POST['administrator']) ? 1 : 0;
    $sales_terminal = isset($_POST['sales_terminal']) ? 1 : 0;
    $billing = isset($_POST['billing']) ? 1 : 0;
    $purchase = isset($_POST['purchase']) ? 1 : 0;

    // Address fields
    $permanent_address_line1 = sanitize_input($_POST['permanent_address_line1'], $conn);
    $permanent_address_line2 = sanitize_input($_POST['permanent_address_line2'], $conn);
    $permanent_city = sanitize_input($_POST['permanent_city'], $conn);
    $permanent_pincode = sanitize_input($_POST['permanent_pincode'], $conn);
    $permanent_state = sanitize_input($_POST['permanent_state'], $conn);
    $permanent_country = sanitize_input($_POST['permanent_country'], $conn);

    $same_as_permanent = isset($_POST['same_as_permanent']) && $_POST['same_as_permanent'] == 'on';
    $present_address_line1 = $same_as_permanent ? $permanent_address_line1 : sanitize_input($_POST['present_address_line1'], $conn);
    $present_address_line2 = $same_as_permanent ? $permanent_address_line2 : sanitize_input($_POST['present_address_line2'], $conn);
    $present_city = $same_as_permanent ? $permanent_city : sanitize_input($_POST['present_city'], $conn);
    $present_pincode = $same_as_permanent ? $permanent_pincode : sanitize_input($_POST['present_pincode'], $conn);
    $present_state = $same_as_permanent ? $permanent_state : sanitize_input($_POST['present_state'], $conn);
    $present_country = $same_as_permanent ? $permanent_country : sanitize_input($_POST['present_country'], $conn);

    // Image upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $uniqueFileName = uniqid() . '.' . $imageFileType;
        $imagePath = $targetDir . $uniqueFileName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            die("Error: Image upload failed.");
        }
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into employees_data
        $sql = "INSERT INTO employees_data (
            salutation, name, employee_id, employee_status, branch_id, business_id, officemail, officemobile,
            personalmobile, personalemail, department, designation, reporting_to, fathername,
            employment_type, gender, marital_status, aadhar, pan, religion, blood_group,
            emergency_contact, branch, uan, esi, doj, doe, checkin_time, checkout_time, ctc, gstin,
            accountname, accountnumber, ifsc, accounttype, bankname, bankbranch, image_path,
            personal_address_line1, personal_address_line2, personal_city, personal_pincode,
            personal_state, personal_country, present_address_line1, present_address_line2,
            present_city, present_pincode, present_state, present_country,
            administrator, sales_terminal, billing, purchase
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssssssssssssssssssssssssssssssssssssssssssss", 
            $salutation, $name, $employee_id, $employee_status, $branch_id, $business_id, $officemail, $officemobile,
            $personalmobile, $personalemail, $department, $designation, $reporting_to, $fathername,
            $employment_type, $gender, $marital_status, $aadhar, $pan, $religion, $blood_group,
            $emergency_contact, $branch, $uan, $esi, $doj, $doe, $checkin_time, $checkout_time, $ctc, $gstin,
            $accountname, $accountnumber, $ifsc, $accounttype, $bankname, $bankbranch, $imagePath,
            $permanent_address_line1, $permanent_address_line2, $permanent_city, $permanent_pincode,
            $permanent_state, $permanent_country, $present_address_line1, $present_address_line2,
            $present_city, $present_pincode, $present_state, $present_country,
            $administrator, $sales_terminal, $billing, $purchase
        );

        if (!$stmt->execute()) {
            throw new Exception("Error inserting employee data: " . $stmt->error);
        }

        // Insert into add_branch
        $branchSql = "INSERT INTO add_branch (
            branch_id, business_id, branch, personalemail, personal_address_line1, personal_address_line2, 
            present_city, present_state, present_country, gstin, billing_scheme, office_email, 
            personalmobile, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Initialize the $branchStmt
        $branchStmt = $conn->prepare($branchSql);
        $branchStmt->bind_param(
            "iissssssssssss", 
            $branch_id, $business_id, $_POST['branch'], $_POST['personalemail'], $permanent_address_line1,
            $permanent_address_line2, $present_city, $present_state, $present_country, $_POST['gstin'], 
            $_POST['billing_scheme'], $_POST['office_email'], $personalmobile, $_POST['status']
        );

        if (!$branchStmt->execute()) {
            throw new Exception("Error inserting into add_branch.");
        }

        // Insert into add_business
        $businessSql = "INSERT INTO add_business (
            branch_id, business_id, business_name, pan, gstin, personalemail, personalmobile, contact_person, 
            personal_address_line1, personal_address_line2, present_pincode, present_city, present_state, logo
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Initialize the $businessStmt
        $businessStmt = $conn->prepare($businessSql);
        $businessStmt->bind_param(
            "iissssssssisss", 
            $branch_id, $business_id, $_POST['business_name'], $_POST['pan'], $_POST['gstin'], 
            $_POST['personalemail'], $personalmobile, $_POST['contact_person'], $permanent_address_line1, 
            $permanent_address_line2, $present_pincode, $present_city, $present_state, $_POST['logo']
        );

        if (!$businessStmt->execute()) {
            throw new Exception("Error inserting into add_business.");
        }

        // Commit the transaction
        $conn->commit();
        echo "<script>
                alert('Employee added successfully');
                window.location.href = 'view_employees.php';
              </script>";
        exit;
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>



<form id="add-employee" action="add_employee.php" method="post" enctype="multipart/form-data">
    <div class="card" style="margin: 15px;">
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab1" role="tabpanel">
                    <div class="form-group row">
                    <div class="col-lg-3">
    <label for="salutation" class="form-control-label">Salutation: <span class="required_sty" style="color:red;">*</span></label>
    <select class="form-control select_reset" id="salutation" name="salutation" required>
        <option value="">Title</option>
        <option value="mr">Mr.</option>
        <option value="mrs">Mrs.</option>
        <option value="miss">Miss.</option>
        <option value="ms">Ms.</option>
    </select>
</div>

                        <div class="col-lg-5">
                            <label class="form-control-label">Employee Name: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" style="text-transform: capitalize;" class="form-control" name="name" oninput="this.value=this.value.replace(/[^A-Za-z_@.!#$%&*-+\s]/g,'');">
                        </div>
                       <div class="col-lg-4">
    <label class="form-control-label">Employee Status: <span class="required_sty" style="color:red;">*</span></label>
    <select class="form-control" name="employee_status">
        <option value="" disabled selected>Select Status</option>
        
        <!-- Active -->
        <optgroup label="Active">
            <option value="Active">Active</option>
        </optgroup>
        
        <!-- Inactive -->
        <optgroup label="Inactive">
            <option value="Resigned">Resigned</option>
            <option value="Absconded">Absconded</option>
            <option value="Terminated">Terminated</option>
            <option value="Furloughed">Furloughed</option>
        </optgroup>
        
        <!-- Temporary Inactive -->
        <optgroup label="Temporary Inactive">
            <option value="Informed Long Leave">Informed Long Leave</option>
            <option value="Uninformed Absence">Uninformed Absence</option>
            <option value="Discipline Improvement Plan">Discipline Improvement Plan</option>
        </optgroup>
    </select>
</div>

                     
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Branch Id: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" id="branch_id" class="form-control" name="branch_id" id="branch_id" placeholder="">
                            <!-- <p id="email_validation" style="display:none;color:red;">Enter valid Email Address</p> -->
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Business Id: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text"  id="business_id"  class="form-control" name="business_id" id="business_id" placeholder="" >
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Business Name: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" id="business_name" class="form-control" name="business_name"  placeholder="">
                            <!-- <p id="email_validation" style="display:none;color:red;">Enter valid Email Address</p> -->
                        </div>
</div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Office mail:</label>
                            <input type="email" id="email" class="form-control" name="officemail" id="officemail" placeholder="">
                            <p id="email_validation" style="display:none;color:red;">Enter valid Email Address</p>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Office Mobile Number: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control required" id="number_validation" name="officemobile" id="officemobile" placeholder="" minlength="8" maxlength="10">
                        </div>
                      </div>
                        <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Employee ID: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="employee_id" id="employee_id" placeholder="" value="">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Personal Mobile Number: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control required" id="number_validation" name="personalmobile" id="personalmobile" placeholder="" minlength="10" maxlength="10">
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
    <label class="form-control-label">Reporting To:</label>
    <select class="form-control" id="reporting_to" name="reporting_to">
        <option value="">Select reporting to</option>
        <option value="Manager">Manager</option>
        <option value="Team Leader">Team Leader</option>
        <option value="Supervisor">Supervisor</option>
    </select>
</div>

                        <div class="col-lg-6">
                            <label class="form-control-label">Father's Name:</label>
                            <input type="fathername" class="form-control" name="fathername" id="fathername" placeholder="">
                        </div>
                    </div>
                 <div class="form-group row">
    <div class="col-lg-6">
        <label class="form-control-label">Employment Type:</label>
        <select class="form-control" id="employment_type" name="employment_type">
            <option value="Full Time">Full Time</option>
            <option value="Part Time">Part Time</option>
            <option value="Contractual">Contractual</option>
            <option value="Apprentice">Apprentice</option>
        </select>
    </div>
    <div class="col-lg-3">
        <label class="form-control-label">Gender:</label>
        <select class="form-control" id="gender" name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Others">Others</option>
        </select>
    </div>
    <div class="col-lg-3">
        <label class="form-control-label">Marital Status:</label>
        <select class="form-control" id="marital_status" name="marital_status">
            <option value="Unmarried">Unmarried</option>
            <option value="Married">Married</option>
            <option value="Divorced">Divorced</option>
            <option value="Others">Others</option>
        </select>
    </div>
</div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="form-control-label">Aadhar: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="aadhar" id="aadhar" placeholder="Aadhar" value="">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-control-label">PAN: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control required" id="pan" name="pan" id="pan" placeholder="PAN">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-control-label">Religion: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="religion" id="religion" placeholder="Religion" value="">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-control-label">Blood Group: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control required" id="blood_group" name="blood_group" id="bood_group" placeholder="Blood Group">
                        </div>
                    </div>
                    <!-- Personal Email Field -->
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Personal Email:</label>
                            <input type="email" class="form-control" name="personalemail" id="personalemail" placeholder="">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-control-label">CTC:</label>
                            <input type="ctc" class="form-control" name="ctc" id="ctc" placeholder="">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-control-label">gstin:</label>
                            <input type="gstin" class="form-control" name="gstin" id="gstin" placeholder="">
                        </div>
                        </div>

                    <div class="form-group row">
                       
                        <div class="col-lg-6">
                            <label>Emergency Contact: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control required" id="emergency_contact" name="emergency_contact" id="emergency_contact" placeholder="Emergency Contact">
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
                        <div class="col-lg-3">
                            <label class="form-control-label">UAN Number:</label>
                            <input type="text" class="form-control" id="number_validation" name="uan" placeholder="" maxlength="12">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-control-label">ESI Number:</label>
                            <input type="text" class="form-control" id="number_validation" name ="esi" placeholder="" maxlength="10">
                        </div>
                        <div class="col-lg-6">
                            <label>Date of joining: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="date" class="form-control required" id="kt_datepicker_1" name="doj" id="doj" placeholder="Select date">
                        </div>
                    </div>
                    <div class="form-group row">
                    <div class="col-lg-3">
    <label class="form-control-label">Checkin-time:</label>
    <input type="time" class="form-control" name="checkin_time">
</div>
<div class="col-lg-3">
    <label class="form-control-label">Checkout-time:</label>
    <input type="time" class="form-control" name="checkout_time">
</div>

                        <div class="col-lg-6">
                            <label>Date of exit: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="date" class="form-control required" id="kt_datepicker_1" name="doe" id="doe" placeholder="Select date">
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
    <div class="card" style="margin: 15px; width: 80%; max-width: 500px; text-align:center;">
        <div class="btn-group" role="group" aria-label="Address Tabs">
            <button type="button" class="btn btn-primary active" id="btn-permanent" onclick="showTab('permanent')">Permanent Address</button>
            <button type="button" class="btn btn-secondary" id="btn-present" onclick="showTab('present')">Present Address</button>
        </div>
    </div>

    <div class="tab-content mt-3">
        <!-- Permanent Address Section -->
        <div id="permanent" class="tab-pane fade show active">
            <div class="form-group row">
                <div class="col-lg-6">
                    <label class="form-control-label">Address line 1:</label>
                    <input type="text" class="form-control" name="permanent_address_line1" id="permanent_address_line1" placeholder="Address Line 1">
                </div>
                <div class="col-lg-6">
                    <label class="form-control-label">Address line 2:</label>
                    <input type="text" class="form-control" name="permanent_address_line2" id="permanent_address_line2" placeholder="Address Line 2">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label class="form-control-label">City:</label>
                    <input type="text" class="form-control" name="permanent_city" id="permanent_city" placeholder="City">
                </div>
                <div class="col-lg-6">
                    <label class="form-control-label">Pincode:</label>
                    <input type="text" class="form-control" name="permanent_pincode" id="permanent_pincode" placeholder="Pincode">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="permanent_state">Select State</label>
                    <select class="form-control" id="permanent_state" name="permanent_state">
                        <?php include("states-dropdown.php"); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="permanent_country">Country</label>
                    <select class="form-control" id="permanent_country" name="permanent_country">
                        <?php include("country-dropdown.php"); ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Present Address Section -->
        <div id="present" class="tab-pane fade">
            <div class="form-group text-center">
                <label class="form-check-label">
                    <input type="checkbox" id="same_as_permanent" name="same_as_permanent" class="form-check-input" onchange="togglePresentAddress()">
                    Permanent address is same as Present address
                </label>
            </div>
            <div id="presentAddressForm">
                <div class="form-group row">
                    <div class="col-lg-6">
                        <label class="form-control-label">Address line 1:</label>
                        <input type="text" class="form-control" name="present_address_line1" id="present_address_line1" placeholder="Address Line 1">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-control-label">Address line 2:</label>
                        <input type="text" class="form-control" name="present_address_line2" id="present_address_line2" placeholder="Address Line 2">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-6">
                        <label class="form-control-label">City:</label>
                        <input type="text" class="form-control" name="present_city" id="present_city" placeholder="City">
                    </div>
                    <div class="col-lg-6">
                        <label class="form-control-label">Pincode:</label>
                        <input type="text" class="form-control" name="present_pincode" id="present_pincode" placeholder="Pincode">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="present_state">Select State</label>
                        <select class="form-control" id="present_state" name="present_state">
                            <?php include("states-dropdown.php"); ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="present_country">Country</label>
                        <select class="form-control" id="present_country" name="present_country">
                            <?php include("country-dropdown.php"); ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                <div class="tab-pane" id="tab3" role="tabpanel">
                    <div class="form-group row">
                    <div class="col-lg-6">
                            <label class="form-control-label">Account Number:</label>
                            <input type="number" class="form-control" name="accountnumber" placeholder="">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Account Name:</label>
                            <input type="text" style="text-transform: capitalize;" class="form-control" name="accountname" placeholder="">
                        </div>
                     
                    </div>
                    <div class="form-group row">
                    <div class="col-lg-6">
                            <label class="form-control-label">Bank Name:</label>
                            <input type="text" style="text-transform: uppercase;" class="form-control select_reset" name="bankname">
                        </div>
                
                        <div class="col-lg-6">
                            <label class="form-control-label">IFSC Code:</label>
                            <input type="text" style="text-transform: uppercase;" class="form-control" name="ifsc" placeholder="" maxlength="11">
                        </div>
                       
                    </div>
                    <div class="form-group row">
                    <div class="col-lg-6">
                            <label class="form-control-label">Account Type:</label>
                            <select class="form-control select_reset" name="accounttype">
                                <option value="" selected="">Please Select</option>
                                <option value="current account">Current Account</option>
                                <option value="savings account">Savings Account</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Branch Name:</label>
                            <input type="text" style="text-transform: capitalize;" class="form-control" name="bankbranch" placeholder="">
                        </div>
                    </div>
                    </div>

                    
                    <div class="tab-pane" id="tab4" role="tabpanel">
    <h5 class="highlighted">LEDGERS Access</h5>
    
    <p>Should this user have access to the accounting portion of LEDGERS Platform?</p>
    <label class="switch">
      <input type="checkbox" id="ledgersAccessToggle" onclick="toggleAccessOptions()">
      <span class="slider"></span>
    </label>
    <div id="accessOptions" class="toggle-section">
      <div class="d-flex justify-content-between align-items-center mt-3">
        <h6>Administrator</h6>
        <label class="switch">
          <input type="checkbox" name="administrator" value="administrator" id="adminToggle">
          <span class="slider"></span>
        </label>
      </div>
      <p>User has rights to perform all functions in LEDGERS.</p>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <h6>Sales Terminal</h6>
        <label class="switch">
          <input type="checkbox" name="sales_terminal" value="sales_terminal" id="salesTerminalToggle">
          <span class="slider"></span>
        </label>
      </div>
      <p>User has rights to only send estimates or payment links to collect payments. User can only view his/her estimates.</p>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <h6>Billing</h6>
        <label class="switch">
          <input type="checkbox" name="billing" value="billing" id="billingToggle">
          <span class="slider"></span>
        </label>
      </div>
      <p>User has rights to create, edit & view estimates, receipts, invoices. User can add & edit customer contacts.</p>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <h6>Purchase</h6>
        <label class="switch">
          <input type="checkbox" name="purchase" value="purchase" id="purchaseToggle">
          <span class="slider"></span>
        </label>
      </div>
      <p>User has rights to create, edit & view purchase invoices, vouchers, accounts payable. User can add & edit supplier contacts.</p>
    </div>

    <!-- <div class="d-flex justify-content-end mt-4">
      <button style="background-color: #f44336; color: white; padding: 8px 16px; border: none; cursor: pointer;">Cancel</button>
      <button style="background-color: #4CAF50; color: white; padding: 8px 16px; border: none; cursor: pointer; margin-left: 10px;">Add Employee</button>
    </div> -->
  </div>

   
        </div>
        
    </div>
  
   
<div class="d-flex justify-content-end mx-3 mb-5">
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
<script>
    function showTab(tabId) {
        // Hide both sections first
        document.getElementById('permanent').classList.remove('show', 'active');
        document.getElementById('present').classList.remove('show', 'active');

        // Reset button styles
        document.getElementById('btn-permanent').classList.remove('btn-primary');
        document.getElementById('btn-present').classList.remove('btn-primary');
        document.getElementById('btn-permanent').classList.add('btn-secondary');
        document.getElementById('btn-present').classList.add('btn-secondary');

        // Show the selected section and update button style
        document.getElementById(tabId).classList.add('show', 'active');
        document.getElementById('btn-' + tabId).classList.remove('btn-secondary');
        document.getElementById('btn-' + tabId).classList.add('btn-primary');
    }

    function togglePresentAddress() {
        const presentAddressForm = document.getElementById('presentAddressForm');
        const sameAsPermanent = document.getElementById('same_as_permanent').checked;

        // Show or hide the Present Address form based on the checkbox
        presentAddressForm.style.display = sameAsPermanent ? 'none' : 'block';
    }

    // Initialize the display state on page load
    document.addEventListener('DOMContentLoaded', function () {
        togglePresentAddress();
    });
</script>

<script>
    function toggleAccessOptions() {
      const accessOptions = document.getElementById("accessOptions");
      const ledgersAccessToggle = document.getElementById("ledgersAccessToggle");

      if (ledgersAccessToggle.checked) {
        accessOptions.style.display = "block";
      } else {
        accessOptions.style.display = "none";
      }
    }
  </script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js">
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
   
</body>
</html>
