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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<!-- Your JavaScript code -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


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
                                <h4 class="m-b-10">Attendence</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Attendence</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>


    
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-9">
              <div class="card" style="border-radius: 5px;">
                <div class="row">
                  <h5 class=" col-md-2 reports mx-2" style="font-size: 18px; margin-top: 15px;">Reports</h5>
                  <input type="text" class="col-md-3 form-control mt-2 mb-2" style="width: 400px; margin-left: 100px;" placeholder="Search..." id="generalSearch1">
                  <input type="text" class="col-md-3 form-control form-control input date-filter bg-white mt-2 mb-2 mx-2"  readonly placeholder="Date range" />
                  <div class="input-group-append mt-2" style="height: 35px;">
                    <span class="input-group-text"><i class="fa fa-calendar-check" ></i></span>
                    <div class="col-md-2 dropdown" data-toggle="tooltip" data-placement="top" title="Filter" >
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 35px;">
                                        <i class="fa fa-filter"></i> &nbsp; <span class="filter-text"></span>
                                    </button>
                                    <div class="dropdown-menu" style="margin-left: -60px;">
                                        <a class="dropdown-item quick-filter" data-filter="Mapview" href="#">Mapview</a>
                                        <a class="dropdown-item quick-filter" data-filter="Approval Report" href="#">Approval Report</a>
                                        <a class="dropdown-item quick-filter" data-filter="Pending Approvals" href="#">Pending Approvals</a>
                                    </div>
                                </div>
                            
                  </div>
                            
                </div>
              </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3">
              <div class="card" style="border-radius: 5px;">

             </div>
            </div>
        </div>


<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-9">
        <div class="card" style="border-radius: 5px; margin-top: -20px;">
            <!-- Your table starts here -->
            <table class="table ">
                <thead style="">
                    <tr>
                        <th style="text-transform: capitalize; font-size: 15px;">Login ID</th>
                        <th style="text-transform: capitalize; font-size: 15px;">Basic Info</th>
                        <th style="text-transform: capitalize; font-size: 15px;">Shift</th>
                        <th style="text-transform: capitalize; font-size: 15px;">In Time</th>
                        <th style="text-transform: capitalize; font-size: 15px;">Out Time</th>
                        <th style="text-transform: capitalize; font-size: 15px;">Photo</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Add your table rows here -->
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>Day Shift</td>
                        <td>09:00 AM</td>
                        <td>05:00 PM</td>
                        <td><img src="path/to/photo.jpg" alt="User Photo"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

        </div>
    </section>
        <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>