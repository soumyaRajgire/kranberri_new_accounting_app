
<!DOCTYPE html>
<?php
session_start(); 
if(!isset($_SESSION['LOG_IN'])){
   header("Location:login.php");
}
else
{
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
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
                            <h4 class="m-b-10">View Employees</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Employees</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
  <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Reports</h5>
                   
                        <!-- <span class="d-block m-t-5">use class <code>table-striped</code> inside table element</span> -->
                        <a  href="add_employee.php" class="btn btn-info" style="color: #fff !important;float:right;">Add Employee</a>
                       

                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <!-- <table class="table table-striped table-bordered" id="dataTables-example"> -->
                              
                        <!-- Your HTML table structure -->
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th>Basic Info</th>
            <th>Contact</th>
            <th>Reporting Info</th>
            <th>Salary Info</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // Replace the following code with your actual database query and result retrieval logic.
    $result = mysqli_query($conn, "SELECT id, salutation, name, employee_id, department, designation, officemail, personalmobile, doj, branch, accountname, accountnumber, ifsc, accounttype, bankname, bankbranch, aadhar, pan, uan, esi FROM employees_data");

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td><a href="employee_profile.php?id=' . $row['id'] . '" style="color: blue;">' . $row['name'] . '</a><br>' . $row['department'] . '</td>';
        echo '<td>' . $row['officemail'] . '<br>' . $row['personalmobile'] . '</td>';
        echo '<td>' . $row['branch'] . '</td>';
        echo '<td>' . $row['doj'] . '<br>' . $row['accounttype'] . '</td>';
        //  echo '<td>' . $row['status'] . '</td>';
        echo '</tr>';
    }
    ?>
    </tbody>
</table>


                        </div>
                    </div>
                </div>
            </div>
            <!-- [ stiped-table ] end -->
           
        </div>
        <!-- [ Main Content ] end -->
    </div>
</section>




    <!-- Required Js -->

 <!-- <script src="assets/js/jquery.min.js"></script> -->

        <!-- Bootstrap Core JavaScript -->
        <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
       <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
 <script type="text/javascript">
            $(document).ready(function () {
  $('#dataTables-example').DataTable();
  $('.dataTables_length').addClass('bs-select');

});
            $('#dataTables-example').dataTable( {
    "orderFixed": [ 3, 'asc' ]
} );
        </script>
</body>
</html>
