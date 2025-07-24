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
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <?php include("header_link.php");?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-xwWtC5F6ZqVekUFCMQU4bRb3mRvFvdsSf44225YU7I2HYU8eWV6b23UUQFtN7e0kCjzbuYH8R1N+n5d4qxlGwA=="
        crossorigin="anonymous" />
    <style>
    .custom-table th, td, tr {
            border: 2px solid grey; /* Define your desired border style and color here */
    }
    .custom-table th {
        width: 400px;
        font-weight: bold;
        font-size: 16px;
    }
    .custom-table td {
        font-size: 16px;
    }
    #profile-datatable th {
        text-transform: capitalize;
        font-size: 14px;
    }
    .btn-custom {
        background-color: white;
        color: #00acc1; /* Text color when not hovering */
        border-color: #00acc1; /* Border color when not hovering */
    }

    .btn-custom:hover {
        background-color: #00acc1; /* Background color on hover */
        color: #fff; /* Text color on hover */
        border-color: #00acc1; /* Border color on hover */
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
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Employee profile</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Employee profile</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
           
            <div class="col-lg-12">
    <div class="card" style="height: 58px;">
        <div class="row">
            <div class="col-lg-6" style="font-size: 15px;">
                <ul class="nav nav-tabs mt-3" id="myTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#profile">Profile</a>
                    </li>
                    <li class="nav-item">        
                        <a class="nav-link" data-toggle="tab" href="#attendence">Attendance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#documents">Documents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#approvals">Approvals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#access">Access</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#notes">Notes</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 text-right mt-2">
                <div class="btn-group" style="margin-right: 10px;">
                <button type="button" class="btn btn-success btn-bold" data-toggle="modal" data-target="#employeeStatusModal">
                     Active Employee
                </button>
                </div>
            </div>
        </div>
    </div>  
</div>


<!-- Active Employee Modal -->


<div class="modal" id="employeeStatusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Employee Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Employee Status Form -->
                <div class="form-group">
                    <label for="statusDate">Date <span style="color: red;">*</span></label>
                    <input type="date" class="form-control" id="statusDate" required>
                </div>

                <div class="form-group">
                    <label for="status">Status <span style="color: red;">*</span></label>
                    <select class="form-control" id="status" required>
                        <option value="">Select</option>
                        <option value="Absconded">Absconded</option>
                        <option value="Retired">Retired</option>
                        <option value="Fired">Fired</option>
                        <option value="Sabbatical">Sabbatical</option>
                        <option value="Resigned">Resigned</option>
                        <option value="Active">Active</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" placeholder="Description" style="height: 150px;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Add cancel and submit buttons -->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success">Submit</button>
            </div>
        </div>
    </div>
</div>
<!-- Active Employee Modal -->




<div class="tab-content">
        <!-- Profile Tab Content -->
        <div id="profile" class="tab-pane fade show active">
     
                                                <div class="row"  style="margin-left:-10px;">
                                                <div class="col-md-7 card mx-4">
                                                <?php
// Assuming you have a database connection established earlier

// Step 1: Extract employee ID from the URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Step 2: Query the database
if ($id) {
    $query = "SELECT * FROM employees_data WHERE id = $id";
    $result = mysqli_query($conn, $query);

    // Step 3: Display the retrieved information in the HTML table
    if ($row = mysqli_fetch_assoc($result)) {
        // Your HTML table code with PHP echo statements to display data
        ?>
        <table class="table table-bordered custom-table mt-3">
            <tbody>
                <tr>
                    <th class="kt-font-bold">Employee ID</th>
                    <td><a href=""><span class="hname" style="color: blue;"><?php echo $row['employee_id']; ?></span></a></td>
                </tr>
                <!-- Add similar rows for other employee details -->
                <tr>
                <th class="kt-font-bold">Name</th>
                <td><a href="" id="name" name="name"><span class="hbus" style="color: blue;"><?php echo $row['name']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Official Email</th>
                <td><a href=""><span class="hemail" style="color: blue;"><?php echo $row['officemail']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Office Mobile</th>
                <td><a href="" id="officemobile" name="officemobile" data-type="text"><span class="hmobile" style="color: blue;"><?php echo $row['personalmobile']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Date of Joining</th>
                <td><a href=""><span class="pan_list tabl_size kt-font-bold" style="color: blue;"><?php echo $row['doj']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Designation</th>
                <td><a href="" id="designation" name="designation" data-type="select"><span class="gstin" style="color: blue;"><?php echo $row['designation']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Department</th>
                <td><a href="" id="department" name="department" data-type="select"><span class="pan_list" style="color: blue;"><?php echo $row['department']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Supervisor</th>
                <td><a href="" id="reporting_to" name="reporting_to" data-type="select"><span class="pan_list"  style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Shift</th>
                <td><a href="" id="shift" name="shift" data-type="select"><span class="pan_list"  style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Branch</th>
                <td><a href="" id="branch" name="branch" data-type="select"><span class="pan_list" style="color: blue;"><?php echo $row['branch']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Employee Tag</th>
                <td><a href="" id="employee_tag" data-type="text"><span class="pan_list " style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Father's Name</th>
                <td><a href=""  id="FatherName" data-type="text" ><span class="pan_list" style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Personal Mobile</th>
                <td><a href="" id="personalmobile" data-type="text"><span class="pan_list" style="color: blue;"><?php echo $row['personalmobile']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Personal Email</th>
                <td><a href=""  id="personalemail" data-type="text" ><span class="pan_list" style="color: blue;"><?php echo $row['personalemail']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Gender</th>
                <td><a href="" id="Gender" data-type="select" ><span class="pan_list " style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <!-- <tr>
                <th class="kt-font-bold">Blood Group</th>
                <td><a href=""  id="BloodGroup" data-type="select" ><span class="pan_list" style="color: blue;"><?php echo $row['blood_group']; ?></span></a></td>
            </tr> -->
            <tr>
                <th class="kt-font-bold">Marital Status</th>
                <td><a href="" id="MaritalStatus" data-type="select"><span class="pan_list " style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Religion</th>
                <td><a href="" id="Religion" data-type="select" ><span class="pan_list " style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Date of Birth</th>
                <td><a href=""  id="DateOfBirth" data-type="combodate" data-value="" data-format="DD-MMM-YYYY" data-viewformat="DD-MMM-YYYY" data-template="DD - MMM - YYYY" data-pk="1" data-combodate='{"minYear":"1950","maxYear":"2023"}'><span class="pan_list" style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Residential Status</th>
                <td><a href=""  id="Residential" data-type="select" ><span class="pan_list " style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Pan</th>
                <td><a href=""  id="PAN" data-type="text" ><span class="pan_list" style="color: blue;" ><?php echo $row['pan']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">UAN Number</th>
                <td><a href="" id="UAN" data-type="text" ><span class="pan_list " style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">ESI Number</th>
                <td><a href="" id="ESI" data-type="text" ><span class="pan_list" style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Aadhaar</th>
                <td><a href="" id="Aadhaar" data-type="text"><span class="pan_list " style="color:#b6b4b4;">Update</span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Bank</th>
                <td><a href=""  id="bankname" data-type="text"><span class="pan_list " style="color: blue;"><?php echo $row['bankname']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Account Number</th>
                <td><a href=""  id="accountnumber" data-type="text" ><span class="pan_list " style="color: blue;" ><?php echo $row['accountnumber']; ?></span></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Account Name</th>
                <td><a href=""  id="accountname" data-type="text" ><span class="pan_list" style="color: blue;"><?php echo $row['accountname']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">IFSC Code</th>
                <td><a href="" id="ifsccode" data-type="text" ><span class="pan_list" style="color: blue;"><?php echo $row['ifsc']; ?></span></a></td>
            </tr>
            <tr>
                <th class="kt-font-bold">Bank Branch</th>
                <td><a href=""  id="bankbranch" data-type="text" ><span class="pan_list " style="color: blue;"><?php echo $row['bankbranch']; ?></span></a></td>
            </tr>
            <!-- <tr>
                <th class="kt-font-bold">Date of Exit</th>
                <td class="">
                    <a href=""><span class="status_cus">
                            <p class="mb-0 tabl_size" style="color: blue;"><?php echo $row['date_of_exit']; ?></p>
                        </span>
                    </a>
                </td>
            </tr> -->
            <!-- <tr>
                <th class="kt-font-bold">Status</th>
                <td class=""><a href=""><span class="status_cus">
                            <p class="mb-0 tabl_size" style="color: blue;"><?php echo $row['status']; ?></p>
                        </span></a></td>
            </tr> -->
            </tbody>
        </table>
        <?php
    } else {
        echo "Employee not found";
    }

    // Step 4: Close the database connection
    mysqli_close($conn);
} else {
    echo "Invalid employee ID";
}
?>
                                                </div>

                                                <div class="col-md-5 card" style="height:300px; max-width: 445px;">
                                                <div class="kt-widget4__item pb-0" style="border-bottom: 0px !important;">
    <div class="box_img d-flex align-items-center mt-4" style="border: 2px solid #e0e0e0; padding: 10px;">
        <div class="img_div mr-3">
            <img src="https://dhr.ledgers.cloud/images/male.jpeg" class="img-thumbnail img-fluid" style="width: 80px; height: 80px;" onerror="this.src='https://dhr.ledgers.cloud/images/female.jpeg'" data-toggle="modal" data-target="#profileUploadModal">
        </div>
  
        <div class="img_txt_div">
            <p class="mb-1" style="font-weight: bold;"><?php echo $row['name']; ?></p>
            <p class="mb-1 "><?php echo $row['designation']; ?></p>
            <p class="mb-0 "><?php echo $row['personalmobile']; ?></p>
        </div>
       

    </div>
</div>

<div class="item mb-4 mt-5">
    <div class="info">
        <a href="#" class="username mx-2" style="color: black; font-weight: bold">KYC</a>
        <a href="#" class="kt-badge" style="margin-left: 250px; color: #ffb822; background: rgba(255, 184, 34, 0.1);">Pending</a>

    </div>
</div>

<div class="item">
    <div class="info">
        <a href="#" class="sername mx-2" style="color: black; font-weight: bold">Attendance</a>
        <a href="#" class="kt-font"  style="margin-left : 220px; color: blue;" ><span id="total_inv">0%</span></a>
    </div>
</div>

                    
        </div>
        </div>
        </div>

        <!-- Attendance Tab Content -->
        <div id="attendence" class="tab-pane fade">
            
            <!-- Content for the Attendance tab -->
            <!-- Add your attendance-related content here -->
        </div>

        <!-- Documents Tab Content -->
        <div id="documents" class="tab-pane fade">
            <!-- Content for the Documents tab -->
            <!-- Add your documents-related content here -->
        </div>

        <!-- Approvals Tab Content -->
        <div id="approvals" class="tab-pane fade">
        <div class="col-lg-12 tab-pane card mx-3" id="kt_tabs_6_4" style="width: 98%;">
        <div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-toolbar">
            <div class="">
                <div class="btn-group mt-2">
                    <a href="#" class="btn btn-md btn-custom" id="attendanceChanges">Changes</a>&nbsp;&nbsp;
                    <a href="#" class="btn btn-md btn-custom" id="leaveApplication">Application</a>&nbsp;&nbsp;
                    <a href="#" class="btn btn-md btn-custom" id="addCheckin">Add</a>&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-datatable table-responsive" id="approvals"></div>
    </div>
</div>
                                    <hr>
                                    <div class="portlet-body m-2">
            <!-- Profile Table -->
            <table class="table table-bordered" id="profile-datatable">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
            <!-- End Holidays Table -->
        </div>
                                </div>
                                
            
        </div>

        <!-- Access Tab Content -->
<div id="access" class="tab-pane fade">
    <!-- Content for the Access tab -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mx-3" style="height:400px;">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mt-1" style="height:350px;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">Modules</th>
                                    <th class="text-center">User</th>
                                    <th class="text-center">Manager</th>
                                    <th class="text-center">Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left">ConqHR - Employee Module</td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input user-select" id="user-conqhr-employee">
                                            <label class="custom-control-label" for="user-conqhr-employee"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input manager-select" id="manager-conqhr-employee">
                                            <label class="custom-control-label" for="manager-conqhr-employee"></label>
                                        </div>
                                    </td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td class="text-left">ConqHR - Employer Module</td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input user-select" id="user-conqhr-employer">
                                            <label class="custom-control-label" for="user-conqhr-employer"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input manager-select" id="manager-conqhr-employer">
                                            <label class="custom-control-label" for="manager-conqhr-employer"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input admin-select" id="admin-conqhr-employer">
                                            <label class="custom-control-label" for="admin-conqhr-employer"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">Superreceptionistapp</td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input user-select" id="user-superreceptionistapp">
                                            <label class="custom-control-label" for="user-superreceptionistapp"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input manager-select" id="manager-superreceptionistapp">
                                            <label class="custom-control-label" for="manager-superreceptionistapp"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input admin-select" id="admin-superreceptionistapp">
                                            <label class="custom-control-label" for="admin-superreceptionistapp"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">Ledgers</td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input user-select" id="user-ledgers">
                                            <label class="custom-control-label" for="user-ledgers"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input manager-select" id="manager-ledgers">
                                            <label class="custom-control-label" for="manager-ledgers"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input admin-select" id="admin-ledgers">
                                            <label class="custom-control-label" for="admin-ledgers"></label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    <div class="col-md-4 card" style="height:300px; max-width: 390px;">
     <div class="kt-widget4__item pb-0" style="border-bottom: 0px !important;">
      <div class="box_img d-flex align-items-center mt-4" style="border: 2px solid #e0e0e0; padding: 10px;">
        <div class="img_div mr-3">
            <img src="https://dhr.ledgers.cloud/images/male.jpeg" class="img-thumbnail img-fluid" style="width: 80px; height: 80px;" onerror="this.src='https://dhr.ledgers.cloud/images/female.jpeg'" data-toggle="modal" data-target="#profileUploadModal">
        </div>
        <div class="img_txt_div">
            <p class="mb-1" style="font-weight: bold;"><?php echo $row['name']; ?></p>
            <p class="mb-1 "><?php echo $row['designation']; ?></p>
            <p class="mb-0 "><?php echo $row['personalmobile']; ?></p>
        </div>
      </div>
     </div>

<div class="item mb-4 mt-5">
    <div class="info">
        <a href="#" class="username mx-2" style="color: black; font-weight: bold">KYC</a>
        <a href="#" class="kt-badge" style="margin-left: 250px; color: #ffb822; background: rgba(255, 184, 34, 0.1);">Pending</a>

    </div>
</div>

<div class="item">
    <div class="info">
        <a href="#" class="sername mx-2" style="color: black; font-weight: bold">Attendance</a>
        <a href="#" class="kt-font"  style="margin-left : 220px; color: blue;" ><span id="total_inv">0%</span></a>
    </div>
</div>

                    
        </div>
    </div>
    <!-- Add your access-related content here -->
</div>


        <!-- Notes Tab Content -->
        <div id="notes" class="tab-pane fade">
    <!-- Content for the Notes tab -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mx-3">
                <div class="dash_sec tab-pane" id="kt_tabs_6_6">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src='' id="notesframe"></iframe>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="col-md-4 card" style="height:300px; max-width: 390px;">
     <div class="kt-widget4__item pb-0" style="border-bottom: 0px !important;">
      <div class="box_img d-flex align-items-center mt-4" style="border: 2px solid #e0e0e0; padding: 10px;">
        <div class="img_div mr-3">
            <img src="https://dhr.ledgers.cloud/images/male.jpeg" class="img-thumbnail img-fluid" style="width: 80px; height: 80px;" onerror="this.src='https://dhr.ledgers.cloud/images/female.jpeg'" data-toggle="modal" data-target="#profileUploadModal">
        </div>
        <div class="img_txt_div">
            <p class="mb-1" style="font-weight: bold;"><?php echo $row['name']; ?></p>
            <p class="mb-1 "><?php echo $row['designation']; ?></p>
            <p class="mb-0 "><?php echo $row['personalmobile']; ?></p>
        </div>
      </div>
     </div>

<div class="item mb-4 mt-5">
    <div class="info">
        <a href="#" class="username mx-2" style="color: black; font-weight: bold">KYC</a>
        <a href="#" class="kt-badge" style="margin-left: 250px; color: #ffb822; background: rgba(255, 184, 34, 0.1);">Pending</a>

    </div>
</div>

<div class="item">
    <div class="info">
        <a href="#" class="sername mx-2" style="color: black; font-weight: bold">Attendance</a>
        <a href="#" class="kt-font"  style="margin-left : 220px; color: blue;" ><span id="total_inv">0%</span></a>
    </div>
</div>

                    
        </div>
    
    <!-- Add your notes-related content here -->
</div>

    </div>

            </div>  
            
</div>
</section>


    <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>