<!DOCTYPE html>
<?php
session_start(); 
// if(!isset($_SESSION['LOG_IN'])){
//    header("Location: login.php");
//    exit;
// }
// $_SESSION['url'] = $_SERVER['REQUEST_URI'];
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

// Initialize variables
$salutation = $name = $employee_id = $department = $designation = $officemail = $personalmobile = $doj = $branch = "";
$accountname = $accountnumber = $ifsc = $accounttype = $bankname = $bankbranch = $aadhar = $pan = $uan = $esi = "";
$password = "";  // Initialize password variable

// Check if we are editing an existing employee
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM employees_data WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $salutation = $employee['salutation'];
        $name = $employee['name'];
        $employee_id = $employee['employee_id'];
        $department = $employee['department'];
        $designation = $employee['designation'];
        $officemail = $employee['officemail'];
        $personalmobile = $employee['personalmobile'];
        $doj = $employee['doj'];
        $branch = $employee['branch'];
        $accountname = $employee['accountname'];
        $accountnumber = $employee['accountnumber'];
        $ifsc = $employee['ifsc'];
        $accounttype = $employee['accounttype'];
        $bankname = $employee['bankname'];
        $bankbranch = $employee['bankbranch'];
        $aadhar = $employee['aadhar'];
        $pan = $employee['pan'];
        $uan = $employee['uan'];
        $esi = $employee['esi'];
        $password = $employee['password'];  // Fetch the password
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $salutation = $_POST['salutation'];
    $name = $_POST['name'];
    $employee_id = $_POST['employee_id'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $officemail = $_POST['officemail'];
    $personalmobile = $_POST['personalmobile'];
    $doj = $_POST['doj'];
    $branch = $_POST['branch'];
    $password = $_POST['password'];
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

    if (isset($_POST['id'])) {
        // Update existing employee
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE employees_data SET salutation=?, name=?, employee_id=?, department=?, designation=?, officemail=?, personalmobile=?, doj=?, branch=?, password=?, accountname=?, accountnumber=?, ifsc=?, accounttype=?, bankname=?, bankbranch=?, aadhar=?, pan=?, uan=?, esi=? WHERE id=?");
        $stmt->bind_param("ssssssssssssssssssssi", $salutation, $name, $employee_id, $department, $designation, $officemail, $personalmobile, $doj, $branch, $password, $accountname, $accountnumber, $ifsc, $accounttype, $bankname, $bankbranch, $aadhar, $pan, $uan, $esi, $id);
    } else {
        // Insert new employee
        $stmt = $conn->prepare("INSERT INTO employees_data (salutation, name, employee_id, department, designation, officemail, personalmobile, doj, branch, password, accountname, accountnumber, ifsc, accounttype, bankname, bankbranch, aadhar, pan, uan, esi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssssssssss", $salutation, $name, $employee_id, $department, $designation, $officemail, $personalmobile, $doj, $branch, $password, $accountname, $accountnumber, $ifsc, $accounttype, $bankname, $bankbranch, $aadhar, $pan, $uan, $esi);
    }

    if ($stmt->execute()) {
        echo '<script>alert("Data saved successfully!");</script>';
        echo '<script>window.location = "view_employees.php";</script>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>
<html lang="en">
<head>
    <title><?php echo isset($id) ? 'Edit Employee' : 'Add Employee'; ?></title>
    <meta charset="utf-8">
    <?php include("header_link.php");?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
</head>
<body class="">
    <?php include("menu.php");?>
    <style>
        .custom-card {
            width: 100%;
            height: 100%;
        }
        #info_form {
            margin-top: 20px;
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
                                <h4 class="m-b-10"><?php echo isset($id) ? 'Edit Employee' : 'Employee Onboarding'; ?></h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#"><?php echo isset($id) ? 'Edit Employee' : 'Employee Onboarding'; ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-card">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab" aria-selected="true">Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab2" role="tab">Others</a>
                    </li>
                </ul>   
            </div>

<form id="add-employee" action="" method="post">
    <?php if (isset($id)) { echo '<input type="hidden" name="id" value="' . $id . '">'; } ?>
    <div class="card custom-card">
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab1" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label for="salutation" class="form-control-label">Salutation: <span class="required_sty" style="color:red;">*</span></label>
                            <select class="form-control select_reset" id="salutation" name="salutation">
                                <option value="">Title</option>
                                <option value="mr" <?php if ($salutation == 'mr') echo 'selected'; ?>>Mr.</option>
                                <option value="mrs" <?php if ($salutation == 'mrs') echo 'selected'; ?>>Mrs.</option>
                                <option value="miss" <?php if ($salutation == 'miss') echo 'selected'; ?>>Miss.</option>
                                <option value="ms" <?php if ($salutation == 'ms') echo 'selected'; ?>>Ms.</option>
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label class="form-control-label">Name: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" style="text-transform: capitalize;" class="form-control" name="name" value="<?php echo $name; ?>" oninput="this.value=this.value.replace(/[^A-Za-z_@.!#$%&*-+\s]/g,'');">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-control-label">Employee ID: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="employee_id" id="employee_id" value="<?php echo $employee_id; ?>">
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
                            <select class="form-control desapp" id="get_departments.php" name="designation">
                                <option value="">select designation</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Office mail:</label>
                            <input type="email" id="email" class="form-control" name="officemail" value="<?php echo $officemail; ?>">
                            <p id="email_validation" style="display:none;color:red;">Enter valid Email Address</p>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Personal Mobile: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="text" class="form-control required" id="number_validation" name="personalmobile" value="<?php echo $personalmobile; ?>" minlength="8" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Date of joining: <span class="required_sty" style="color:red;">*</span></label>
                            <input type="date" class="form-control required" id="kt_datepicker_1" name="doj" value="<?php echo $doj; ?>">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Branch: <span class="required_sty" style="color:red;">*</span></label>
                            <label class="form-control-label" style="float:right;" id="add_branch"><a href="organisation.php"> Add Branch</a></label>
                            <select class="form-control branchapp required" id="branch" name="branch">
                                <option value="">select branch</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
    <div class="col-lg-6">
        <label class="form-control-label">Password: <span class="required_sty" style="color:red;">*</span></label>
        <div class="input-group">
            <input type="password" class="form-control" name="password" id="password" value="<?php echo $password; ?>" placeholder="">
            <div class="input-group-append">
                <span class="input-group-text" onclick="togglePassword()">
                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                </span>
            </div>
        </div>
    </div>
</div>

                </div>
                <div class="tab-pane" id="tab2" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Account Name:</label>
                            <input type="text" style="text-transform: capitalize;" class="form-control" name="accountname" value="<?php echo $accountname; ?>">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Account Number:</label>
                            <input type="number" class="form-control" name="accountnumber" value="<?php echo $accountnumber; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">IFSC Code:</label>
                            <input type="text" style="text-transform: uppercase;" class="form-control" name="ifsc" value="<?php echo $ifsc; ?>" maxlength="11">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Account Type:</label>
                            <select class="form-control select_reset" name="accounttype">
                                <option value="" <?php if ($accounttype == '') echo 'selected'; ?>>Please Select</option>
                                <option value="current account" <?php if ($accounttype == 'current account') echo 'selected'; ?>>Current Account</option>
                                <option value="savings account" <?php if ($accounttype == 'savings account') echo 'selected'; ?>>Savings Account</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Bank Name:</label>
                            <input type="text" style="text-transform: uppercase;" class="form-control select_reset" name="bankname" value="<?php echo $bankname; ?>">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Branch Name:</label>
                            <input type="text" style="text-transform: capitalize;" class="form-control" name="bankbranch" value="<?php echo $bankbranch; ?>">
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
                            <input type="text" class="form-control" name="aadhar" value="<?php echo $aadhar; ?>">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">Pan Number:</label>
                            <input type="text" style="text-transform: uppercase;" class="form-control" name="pan" value="<?php echo $pan; ?>" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Uan Number:</label>
                            <input type="text" class="form-control" name="uan" value="<?php echo $uan; ?>" maxlength="12">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-control-label">ESI Number:</label>
                            <input type="text" class="form-control" name ="esi" value="<?php echo $esi; ?>" maxlength="10">
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

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
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

                <?php if ($branch != "") { ?>
                branchDropdown.val('<?php echo $branch; ?>');
                <?php } ?>
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
<script>
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

                <?php if ($department != "") { ?>
                departmentDropdown.val('<?php echo $department; ?>');
                <?php } ?>
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

                <?php if ($designation != "") { ?>
                designationDropdown.val('<?php echo $designation; ?>');
                <?php } ?>
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
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
<script src="assets/js/myscript.js"></script>



<?php
// Additional PHP scripts for fetching dropdown data

if (isset($_GET['fetch_dropdown'])) {
    include("config.php");
    $endpoint = basename($_SERVER['PHP_SELF']);

    if ($endpoint == 'get_branches.php') {
        $result = $conn->query("SELECT officeName FROM branches");
    } elseif ($endpoint == 'get_departments.php') {
        $result = $conn->query("SELECT departmentName FROM departments");
    } elseif ($endpoint == 'get_designations.php') {
        $result = $conn->query("SELECT designationName FROM designations");
    }

    $data = array();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
    $conn->close();
    exit;
}
?>
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
</body>
</html>