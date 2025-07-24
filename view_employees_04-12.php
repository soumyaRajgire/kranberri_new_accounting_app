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

<!DOCTYPE html>
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
</head>
<body class="">
    <?php include("menu.php");?>

    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">View Employees</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">View Employees</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Reports</h5>
                            <a href="add_employee.php" class="btn btn-info" style="color: #fff !important;float:right;">Add Employee</a>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Basic Info</th>
                                            <th>Contact</th>
                                            <th>Reporting Info</th>
                                            <th>Salary Info</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $result = mysqli_query($conn, "SELECT id, name, department, officemail, personalmobile, branch, doj, accounttype FROM employees_data");

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                        echo '<td><a href="employee_profile.php?id=' . $row['id'] . '" style="color: blue;">' . $row['name'] . '</a><br>' . $row['department'] . '</td>';
                                        echo '<td>' . $row['officemail'] . '<br>' . $row['personalmobile'] . '</td>';
                                        echo '<td>' . $row['branch'] . '</td>';
                                        echo '<td>' . $row['doj'] . '<br>' . $row['accounttype'] . '</td>';
                                        echo '<td><a href="edit_employees.php?id=' . $row['id'] . '"><i class="fas fa-edit"></i></a>';
                                        echo ' <a href="delete_employee.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this record?\');" class="text-danger"><i class="fas fa-trash-alt"></i></a>';
                                        echo ' <a href="#" class="view-employee" data-id="' . $row['id'] . '"><i class="fas fa-eye"></i></a></td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="viewEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewEmployeeModalLabel">Employee Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Employee details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable({
                "pageLength": 10
            });
            $('.dataTables_length').addClass('bs-select');

            $('.view-employee').on('click', function() {
                var employeeId = $(this).data('id');
                $.ajax({
                    url: 'fetch_employee.php',
                    type: 'POST',
                    data: {id: employeeId},
                    success: function(response) {
                        $('#viewEmployeeModal .modal-body').html(response);
                        $('#viewEmployeeModal').modal('show');
                    }
                });
            });
        });
    </script>

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
</body>
</html>
