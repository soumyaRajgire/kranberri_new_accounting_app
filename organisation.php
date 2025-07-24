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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- Your JavaScript code -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
          #branch-datatable th,
          #department-datatable th,
          #teams-datatable th,
          #shift-datatable th,
          #designation-datatable th,
          #holidays-datatable th {
        text-transform: capitalize;
        font-size: 14px;
    }
    .active-cell {
    color: #0abb87;
    border: 1px solid rgba(10, 187, 135, 0.1);
    border-radius: 5px;
 
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
                                <h4 class="m-b-10">Organisation Structure</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Organisation Structure</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
           
            <div class="col-lg-12">
                <div class="card">
                    <ul class="nav nav-tabs" id="myTabs">
                        <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#branch">Branch</a>
                        </li>
                        <li class="nav-item">        
                        <a class="nav-link" data-toggle="tab" href="#department">Department</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#teams">Teams</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#shift">Shift</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#designation">Designation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#holidays">Holidays</a>
                        </li>
                    </ul>
                </div>  

                <div class="card" style="margin-top:-20px;">
                   <div class="card-body">
                    <div class="tab-content">

   <!-- Branch section -->          

                    <div id="branch" class="tab-pane active">
    <!-- Branch Content Goes Here -->
    <div class="portlet">
        <div class="portlet-head">
            <div class="portlet-label">
                <h3 class="portlet-title"></h3>
            </div>
            <div class="portlet-actions mx-1" style="float:right;">
                <a href="#addBranchModal" class="btn btn-info" style="padding:6px;" data-toggle="modal">Add Branch</a>
            </div>
            <div class="portlet-actions mx-1" style="float:right;">
    <input type="text" class="form-control" placeholder="Search" id="branch-search">
</div>
            <br><br>
            <div class="portlet-body m-2">
                <table class="table table-bordered" id="branch-datatable">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Employees Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td class="active-cell"></td>
                            <td></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&#8230;</button>

                                    <div class="dropdown-menu" aria-labelledby="actionDropdown">
                                        <!-- Dropdown options -->
                                        <a class="dropdown-item" href="#">Active</a>
                                        <a class="dropdown-item" href="#">Inactive</a>
                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addDepartmentModal">Edit</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Place your Branch section content here -->
    </div>
</div>

   <!-- Branch section -->     

   <!-- Department section -->     
<div id="department" class="tab-pane fade">
    <div class="portlet">
        <div class="portlet-head">
            <div class="portlet-actions mx-1" style="float:right;">
                <a href="#addDepartmentModal" class="btn btn-info" style="padding:6px;" data-toggle="modal">Add Department</a>
            </div>
            <div class="portlet-actions mx-1" style="float:right;">
                <input type="text" class="form-control" placeholder="Search" id="department-search">                                               
            </div>
        </div>
        <br><br>
        <div class="portlet-body m-2">
            <!-- Department Table -->
            <table class="table table-bordered" id="department-datatable">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Employees Count</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td class="active-cell"></td>
                        <td></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&#8230;</button>

                                <div class="dropdown-menu" aria-labelledby="actionDropdown">
                                    <!-- Dropdown options -->
                                    <a class="dropdown-item" href="#">Active</a>
                                    <a class="dropdown-item" href="#">Inactive</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addDepartmentModal">Edit</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
            <!-- End Department Table -->
        </div>
    </div>
</div>
 <!-- Department section -->   

 <!-- Teams section -->   
<div id="teams" class="tab-pane fade">
    <!-- Teams Content Goes Here -->
    <div class="portlet">
        <div class="portlet-head">
            <div class="portlet-label">
                <h3 class="portlet-title"></h3>
            </div>
            <div class="portlet-actions mx-1" style="float:right;">
                <input type="text" class="form-control" placeholder="Search" id="teams-search">                                               
            </div>
        </div>
        <br><br>
        <div class="portlet-body m-2">
            <table class="table table-bordered" id="teams-datatable">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Other Info</th>
                        <th>Employees Count</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>
    </div>
</div>

 <!-- Teams section --> 

  <!-- Shift section --> 


  <div id="shift" class="tab-pane fade">
    <!-- Shift Content Goes Here -->
    <div class="portlet">
        <div class="portlet-head">
            <div class="portlet-label">
                <h3 class="portlet-title"></h3>
            </div>
            <div class="portlet-actions mx-1" style="float:right;">
                <a href="#addShiftModal" class="btn btn-info" style="padding:6px;" data-toggle="modal">Add Shift</a>
            </div>
            <div class="portlet-actions mx-1" style="float:right;">
                <input type="text" class="form-control" placeholder="Search" id="shift-search">
            </div>
            <br><br>
            <div class="portlet-body m-2">
                <table class="table table-bordered" id="shift-datatable">
                    <thead>
                        <tr>
                            <th>Shift Type</th>
                            <th>Shift Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Shift table rows will be dynamically populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
 <!-- Shift section --> 

  <!-- Designation section --> 

<div id="designation" class="tab-pane fade">
    <!-- Designation Content Goes Here -->
    <div class="portlet">
        <div class="portlet-head">
            <div class="portlet-label">
                <h3 class="portlet-title"></h3>
            </div>
            <div class="portlet-actions mx-1" style="float:right;">
                <a href="#addDesignationModal" class="btn btn-info" style="padding:6px;" data-toggle="modal">Add Designation</a>
            </div>
            <div class="portlet-actions mx-1" style="float:right;">
                <input type="text" class="form-control" placeholder="Search" id="designation-search">
            </div>
        </div>
        <br><br>
        <div class="portlet-body m-2">
            <!-- Designation Table -->
            <table class="table table-bordered" id="designation-datatable">
                <thead>
                    <tr>
                        <th>Designation</th>
                        <th>Status</th>
                        <th>Employees Count</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td class="active-cell"></td>
                        <td></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&#8230;</button>

                                <div class="dropdown-menu" aria-labelledby="actionDropdown">
                                    <!-- Dropdown options -->
                                    <a class="dropdown-item" href="#">Active</a>
                                    <a class="dropdown-item" href="#">Inactive</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addDepartmentModal">Edit</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
            <!-- End Designation Table -->
        </div>
        <!-- Place your Designation section content here -->
    </div>
</div>
 <!-- Designation section --> 

  <!-- Holidays section --> 
<div id="holidays" class="tab-pane fade">
    <!-- Holidays Content Goes Here -->
    <div class="portlet">
        <div class="portlet-head">
            <div class="portlet-label">
                <h3 class="portlet-title"></h3>
            </div>
            <div class="portlet-actions mx-1" style="float:right;">
                <a href="#addHolidayModal" class="btn btn-info" style="padding:6px;" data-toggle="modal">Add Holiday</a>
            </div>
            <div class="portlet-actions mx-1" style="float:right;">
                <input type="text" class="form-control" placeholder="Search" id="holidays-search">
            </div>
        </div>
        <br><br>
        <div class="portlet-body m-2">
            <!-- Holidays Table -->
            <table class="table table-bordered" id="holidays-datatable">
                <thead>
                    <tr>
                        <th>Holiday</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td class="active-cell"></td></td>
                        <td></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">&#8230;</button>

                                <div class="dropdown-menu" aria-labelledby="actionDropdown">
                                    <!-- Dropdown options -->
                                    <a class="dropdown-item" href="#">Active</a>
                                    <a class="dropdown-item" href="#">Inactive</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#addDepartmentModal">Edit</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
            <!-- End Holidays Table -->
        </div>
        <!-- Place your Holidays section content here -->
    </div>
</div>
 <!-- Holidays section --> 


                    </div>
                </div>
                </div>
            </div>
        </div>
    </section>




<!-- Branch Modal -->
<div class="modal" id="addBranchModal" tabindex="-1" role="dialog" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h5 class="modal-title">Add Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for adding a branch -->
                <form id="addBranchForm">
                    <div class="form-group">
                        <label for="officeName">Office Name <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="officeName"  name="officeName" placeholder="Enter office name" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="addressLine1">Address Line 1 <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="addressLine1" name="addressLine1" placeholder="Enter address line 1" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="addressLine2">Address Line 2 <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="addressLine2" name="addressLine2" placeholder="Enter address line 2" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="city">City <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="state">State <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="state" name="state" placeholder="Enter state" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="pincode">Pincode <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter pincode" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="country">Country <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="country" name="country" placeholder="Enter country" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Add cancel and submit buttons -->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitBranch()">Submit</button>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript to handle form submission (you can customize this) -->
<script>
    // Function to handle form submission
function submitBranch() {
    // Serialize the form data
    var formData = $('#addBranchForm').serialize();

    // Make an Ajax request to save_branch.php
    $.ajax({
        type: 'POST',
        url: 'save_branch.php',
        data: formData,
        success: function(response) {
            // Show an alert if the response contains a message
            if (response) {
                alert(response);
            }

            // Close the modal after successful submission
            $('#addBranchModal').modal('hide');
            
            // Fetch and update the table data after submission
            fetchBranches();

            // Redirect to the 'branch' tab
            $('#myTabs a[href="#branch"]').tab('show');
        },
        error: function(error) {
            // Handle errors (you can show an error message to the user)
            console.log(error);
        }
    });
}

    // Function to fetch and populate data in the table
    function fetchBranches() {
        // Make an Ajax request to get_branches.php
        $.ajax({
            type: 'GET',
            url: 'get_branches.php',
            success: function(response) {
                // Update the table body with the fetched data
                $('#branch-datatable tbody').html(response);
            },
            error: function(error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }

    // Call the function when the page is loaded or when needed
    $(document).ready(function() {
        fetchBranches();
    });

    function updateStatus(newStatus, branchId) {
    // Make an Ajax request to update_status.php
    $.ajax({
        type: 'POST',
        url: 'update_branch_status.php',
        data: { branchId: branchId, newStatus: newStatus },
        success: function(response) {
            // Update the status in the table
            var statusCell = $('tr[data-branch-id="' + branchId + '"] .active-cell');
            statusCell.text(newStatus);
        },
        error: function(error) {
            // Handle errors (you can show an error message to the user)
            console.log(error);
        }
    });
}

</script>

<!-- Branch Modal -->




<!--Departmemnt Modal -->
<div class="modal" id="addDepartmentModal" tabindex="-1" role="dialog" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h5 class="modal-title">Add Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your placeholder input field here -->
                <p> Department <span style="color: red;">*</span></p>
                <input type="text" class="form-control" id="departmentName" name="departmentName" placeholder="">
            </div>
            <div class="modal-footer">
                <!-- Add cancel and submit buttons -->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitDepartment()">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
   // Function to handle form submission for adding a department
   function submitDepartment() {
        // Get the department name from the input field
        var departmentName = $('#departmentName').val();

        // Validate if the department name is not empty
        if (departmentName.trim() === '') {
            alert('Department name is required.');
            return;
        }

        // Make an AJAX request to save_department.php
        $.ajax({
            type: 'POST',
            url: 'save_department.php',
            data: { departmentName: departmentName },
            success: function(response) {
                // Show an alert if the response contains a message
                alert(response);

                // Close the modal after successful submission
                $('#addDepartmentModal').modal('hide');

                // Fetch and update the department table data after submission (if needed)
                fetchDepartments();

                // Redirect to the 'branch' tab
                $('#myTabs a[href="#department"]').tab('show');
            },
            error: function(error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }
    // Function to fetch and populate department data in the table
    function fetchDepartments() {
        // Make an Ajax request to get_departments.php
        $.ajax({
            type: 'GET',
            url: 'get_departments.php',
            success: function(response) {
                // Update the table body with the fetched data
                $('#department-datatable tbody').html(response);
            },
            error: function(error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }

    // Call the function when the page is loaded or when needed
    $(document).ready(function() {
        fetchDepartments();
    });

    // Function to update department status
    function updateDepartmentStatus(newStatus, departmentId) {
        // Make an Ajax request to update_department_status.php
        $.ajax({
            type: 'POST',
            url: 'update_department_status.php',
            data: { departmentId: departmentId, newStatus: newStatus },
            success: function(response) {
                // Update the status in the table
                var statusCell = $('tr[data-department-id="' + departmentId + '"] .active-cell');
                statusCell.text(newStatus);
            },
            error: function(error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }

</script>


<!--Departmemnt Modal -->


<!-- Shift Modal  -->
<div class="modal" id="addShiftModal" tabindex="-1" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h5 class="modal-title">Add Shift</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for adding a shift -->
                <form id="addShiftForm">
                    <div class="form-group">
                        <label for="shiftType">Shift Type</label>
                        <!-- Dropdown for Shift Type -->
                        <select class="form-control" id="shiftType" name="shiftType">
                            <option value="strict">Strict</option>
                            <option value="flexiblePerDay">Flexible per day</option>
                            <option value="flexiblePerMonth">Flexible per month</option>
                            <option value="flexiblePerQuarterly">Flexible per quarterly</option>
                        </select>
                    </div>
                    
                    
                    <!-- Single Row for the remaining inputs -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="shiftName">Shift Name</label>
                            <input type="text" class="form-control" id="shiftName" name="shiftName" placeholder="" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="applicableFrom">Applicable From</label>
                            <input type="date" class="form-control" id="applicableFrom" name="applicableFrom" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="startTime">Start Time</label>
                            <input type="text" class="form-control" id="startTime"  name="startTime" placeholder="HH:MM:SS" oninput="formatTimeInput(this)" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="endTime">End Time</label>
                            <input type="text" class="form-control" id="endTime"  name="endTime" placeholder="HH:MM:SS" oninput="formatTimeInput(this)" required>
                        </div>
                        
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                            <label for="permissionTime">Permission Time</label>
                            <input type="text" class="form-control" id="permissionTime" name="permissionTime" placeholder="HH:MM:SS" oninput="formatTimeInput(this)" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="noOfLimit">No. of Limit</label>
                            <input type="number" class="form-control" id="noOfLimit" name="noOfLimit" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                            <label for="lateGrace">Late Grace</label>
                            <input type="text" class="form-control" id="lateGrace" name="lateGrace" placeholder="HH:MM:SS" oninput="formatTimeInput(this)"  required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="hours">Hours</label>
                            <input type="text" class="form-control" id="hours" name="hours" placeholder="" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="salaryDeduction">Salary Deduction</label>
                            <!-- Dropdown for Salary Deduction -->
                            <select class="form-control" id="salaryDeduction" name="salaryDeduction">
                                <option value="noDeduction">No Deduction</option>
                                <option value="halfDayDeduction">Half Day Deduction</option>
                                <option value="fullDayDeduction">Full Day Deduction</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="forceCheckoutLimit">No. of Force Checkout Limit</label>
                            <input type="text" class="form-control" id="forceCheckoutLimit" name="forceCheckoutLimit" placeholder="0" required>
                        </div>
                    </div>

                   <!-- Single Row for Casual Leave -->
                        <div class="form-row">
                          <div class="form-group col-md-12">
                             <label for="casualLeave">Casual Leave</label>
                              <select class="form-control" id="casualLeave" name="casualLeave">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                              </select>
                          </div>
                        </div>

                    <!-- Single Row for Sick Leave -->
                        <div class="form-row">
                          <div class="form-group col-md-12">
                           <label for="sickLeave">Sick Leave</label>
                            <select class="form-control" id="sickLeave" name="sickLeave">
                             <option value="no">No</option>
                             <option value="yes">Yes</option>
                            </select>
                          </div>
                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Add cancel and submit buttons -->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitShift()">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to handle form submission (you can customize this) -->


<!-- Your first script -->
<script>
   // Function to handle form submission for adding a shift
function submitShift() {
    // Get shift form data
    var shiftData = {
        shiftType: $('#shiftType').val(),
        shiftName: $('#shiftName').val(),
        applicableFrom: $('#applicableFrom').val(),
        startTime: $('#startTime').val(),
        endTime: $('#endTime').val(),
        permissionTime: $('#permissionTime').val(),
        noOfLimit: $('#noOfLimit').val(),
        lateGrace: $('#lateGrace').val(),
        hours: $('#hours').val(),
        salaryDeduction: $('#salaryDeduction').val(),
        forceCheckoutLimit: $('#forceCheckoutLimit').val(),
        casualLeave: $('#casualLeave').val(),
        sickLeave: $('#sickLeave').val()
    };

    // Validate shift form data (add your validation logic here)

    // Make an AJAX request to save_shift.php
    $.ajax({
        type: 'POST',
        url: 'save_shift.php',
        data: shiftData,
        dataType: 'json',
        success: function(response) {
            // Show an alert if the response contains a message
            alert(response.message);

            // Close the modal after successful submission
            $('#addShiftModal').modal('hide');

            // Fetch and update the shift table data after submission (if needed)
            fetchShifts();

            // Redirect to the 'branch' tab
            $('#myTabs a[href="#shift"]').tab('show');
        },
        error: function(error) {
            // Handle errors (you can show an error message to the user)
            console.log(error);
        }
    });
}

// Function to fetch and populate shift data in the table
function fetchShifts() {
    // Make an AJAX request to get_shifts.php
    $.ajax({
        type: 'GET',
        url: 'get_shifts.php',
        success: function(response) {
            // Log the response to the console for debugging
            console.log(response);

            // Update the table body with the fetched data
            $('#shift-datatable tbody').html(response);
        },
        error: function(xhr, status, error) {
            // Log the error details to the console for debugging
            console.error("Error: " + status, error);
        }
    });
}

$(document).ready(function() {
    // Fetch shifts when the document is ready
    fetchShifts();
});


// Function to update shift status
function updateShiftStatus(newStatus, shiftId) {
    // Make an AJAX request to update_shift_status.php
    $.ajax({
        type: 'POST',
        url: 'update_shift_status.php',
        data: { shiftId: shiftId, newStatus: newStatus },
        success: function(response) {
            // Update the status in the shift table
            var statusCell = $('tr[data-shift-id="' + shiftId + '"] .active-cell');
            statusCell.text(newStatus);
        },
        error: function(error) {
            // Handle errors (you can show an error message to the user)
            console.log(error);
        }
    });
}

function formatTimeInput(input) {
    // Remove any non-numeric characters
    let sanitizedValue = input.value.replace(/[^0-9]/g, '');

    // Format the value as HH:MM:SS
    if (sanitizedValue.length > 0) {
        sanitizedValue = sanitizedValue.match(/.{1,2}/g).join(':').substring(0, 8);
    }

    // Update the input value
    input.value = sanitizedValue;
}

</script>

<!-- Shift Modal  -->


<!-- Designation Modal -->
<div class="modal" id="addDesignationModal" tabindex="-1" role="dialog" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h5 class="modal-title">Add Designation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add your placeholder input field here -->
                <div class="form-group">
                    <label for="designationName">Designation <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="designationName" name="designationName" placeholder="Enter designation" required>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Add cancel and submit buttons -->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitDesignation()">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to handle form submission for adding a designation
    function submitDesignation() {
        // Get the designation name from the input field
        var designationName = $('#designationName').val();

        // Validate if the designation name is not empty
        if (designationName.trim() === '') {
            alert('Designation name is required.');
            return;
        }

        // Make an AJAX request to save_designation.php
        $.ajax({
            type: 'POST',
            url: 'save_designation.php',
            data: { designationName: designationName },
            success: function(response) {
                // Show an alert if the response contains a message
                alert(response);

                // Close the modal after successful submission
                $('#addDesignationModal').modal('hide');

                // Fetch and update the designation table data after submission (if needed)
                fetchDesignations();

                // Redirect to the 'branch' tab
                $('#myTabs a[href="#designation"]').tab('show');
            },
            error: function(error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }

    // Function to fetch and populate designation data in the table
    function fetchDesignations() {
        // Make an Ajax request to get_designations.php
        $.ajax({
            type: 'GET',
            url: 'get_designations.php',
            success: function(response) {
                // Update the table body with the fetched data
                $('#designation-datatable tbody').html(response);
            },
            error: function(error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }

    // Call the function when the page is loaded or when needed
    $(document).ready(function() {
        fetchDesignations();
    });

    // Function to update designation status
    function updateDesignationStatus(newStatus, designationId) {
        // Make an Ajax request to update_designation_status.php
        $.ajax({
            type: 'POST',
            url: 'update_designation_status.php',
            data: { designationId: designationId, newStatus: newStatus },
            success: function(response) {
                // Update the status in the table
                var statusCell = $('tr[data-designation-id="' + designationId + '"] .active-cell');
                statusCell.text(newStatus);
            },
            error: function(error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }
</script>

<!-- Designation Modal -->


<!-- Holiday Modal -->
<div class="modal" id="addHolidayModal" tabindex="-1" role="dialog" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h5 class="modal-title">Add Holiday</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for adding a holiday -->
                <form id="addHolidayForm">
                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="holidayName">Holiday <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="holidayName" name="holidayName" placeholder="" required>
                    </div>
                        <div class="form-group col-md-6">
                        <label for="holidayDate">Date <span style="color: red;">*</span></label>
                        <input type="date" class="form-control" id="holidayDate" name="holidayDate" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="workingHours">Working Hours <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="workingHours" name="workingHours"  placeholder="HH:MM:SS" oninput="formatTimeInput(this)"  required>
                    </div>
                        <div class="form-group col-md-6">
                        <label for="payInDays">Pay (in days) <span style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="payInDays"  name="payInDays" placeholder="" required>
                    </div>
                </div>                       
                </form>
            </div>
            <div class="modal-footer">
                <!-- Add cancel and submit buttons -->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitHoliday()">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to handle form submission for adding a holiday
    function submitHoliday() {
        // Get holiday form data
        var holidayData = {
            holidayName: $('#holidayName').val(),
            holidayDate: $('#holidayDate').val(),
            workingHours: $('#workingHours').val(),
            payInDays: $('#payInDays').val()
        };

        // Validate holiday form data (add your validation logic here)

        // Make an AJAX request to save_holiday.php
        $.ajax({
            type: 'POST',
            url: 'save_holiday.php',
            data: holidayData,
            success: function(response) {
                // Show an alert if the response contains a message
                alert(response);

                // Close the modal after successful submission
                $('#addHolidayModal').modal('hide');

                // Fetch and update the holidays table data after submission (if needed)
                fetchHolidays();

                // Redirect to the 'branch' tab
                $('#myTabs a[href="#holidays"]').tab('show');
            },
            error: function(error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }

    // Function to fetch and populate holiday data in the table
    function fetchHolidays() {
        // Make an AJAX request to get_holidays.php
        $.ajax({
            type: 'GET',
            url: 'get_holidays.php',
            success: function(response) {
                // Update the table body with the fetched data
                $('#holidays-datatable tbody').html(response);
            },
            error: function(error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }

    // Call the function when the page is loaded or when needed
    $(document).ready(function() {
        fetchHolidays();
    });

    // Function to update holiday status
    function updateHolidayStatus(newStatus, holidayId) {
        // Make an AJAX request to update_holiday_status.php
        $.ajax({
            type: 'POST',
            url: 'update_holiday_status.php',
            data: { holidayId: holidayId, newStatus: newStatus },
            success: function(response) {
                // Update the status in the holidays table
                var statusCell = $('tr[data-holiday-id="' + holidayId + '"] .active-cell');
                statusCell.text(newStatus);
            },
            error: function(error) {
                // Handle errors (you can show an error message to the user)
                console.log(error);
            }
        });
    }
</script>

<!-- Holiday Modal -->


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the input elements and table rows for each datatable
        var branchSearchInput = document.getElementById('branch-search');
        var branchTableRows = document.getElementById('branch-datatable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        var departmentSearchInput = document.getElementById('department-search');
        var departmentTableRows = document.getElementById('department-datatable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        var teamsSearchInput = document.getElementById('teams-search');
        var teamsTableRows = document.getElementById('teams-datatable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        var shiftSearchInput = document.getElementById('shift-search');
        var shiftTableRows = document.getElementById('shift-datatable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        var designationSearchInput = document.getElementById('designation-search');
        var designationTableRows = document.getElementById('designation-datatable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        var holidaysSearchInput = document.getElementById('holidays-search');
        var holidaysTableRows = document.getElementById('holidays-datatable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        // Add event listener to each search input
        branchSearchInput.addEventListener('input', function () {
            handleSearch(branchSearchInput, branchTableRows);
        });

        departmentSearchInput.addEventListener('input', function () {
            handleSearch(departmentSearchInput, departmentTableRows);
        });

        teamsSearchInput.addEventListener('input', function () {
            handleSearch(teamsSearchInput, teamsTableRows);
        });

        shiftSearchInput.addEventListener('input', function () {
            handleSearch(shiftSearchInput, shiftTableRows);
        });

        designationSearchInput.addEventListener('input', function () {
            handleSearch(designationSearchInput, designationTableRows);
        });

        holidaysSearchInput.addEventListener('input', function () {
            handleSearch(holidaysSearchInput, holidaysTableRows);
        });

        // Function to handle search logic
        function handleSearch(searchInput, tableRows) {
            var searchTerm = searchInput.value.toLowerCase();

            // Loop through each row in the table body
            for (var i = 0; i < tableRows.length; i++) {
                var rowData = tableRows[i].innerText.toLowerCase();

                // Check if the search term matches any part of the row data
                if (rowData.includes(searchTerm)) {
                    // Display the row
                    tableRows[i].style.display = '';
                } else {
                    // Hide the row
                    tableRows[i].style.display = 'none';
                }
            }
        }
    });
</script>



    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>