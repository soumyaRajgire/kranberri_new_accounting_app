
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
   
   <style type="text/css">
       
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .compose-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .compose-header input {
            width: 48%;
            padding: 8px;
            box-sizing: border-box;
        }

        .compose-body {
            padding: 10px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4285f4;
            color: #ffffff;
            text-decoration: none;
            border-radius: 3px;
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
                            <h4 class="m-b-10">Send Mail</h4>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Send Mail</a></li>
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
                        <h5>Compose mail</h5>
                   
                        <!-- <span class="d-block m-t-5">use class <code>table-striped</code> inside table element</span> -->
                        <!-- <a  href="create-estimate.php" class="btn btn-info" style="color: #fff !important;float:right;" />Create</a> -->
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <!-- <table class="table table-striped table-bordered" id="dataTables-example"> -->
                              
                        <!-- Your HTML table structure -->
                        <?php
$qid = $_GET['id'];
$qcode = $_GET['qcode'];
                        ?>
<div class="email-container">
    <form action="mailsend.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="qid" id="qid" value="<?php echo $qid;?>" hidden>
        <input type="text" name="qcode" id="qcode" value="<?php echo $qcode;?>" hidden>
    <div class="compose-header">
        <input type="text" class="form-control" placeholder="To" name="to_address" id="to_address" value="">
    </div>
    <!-- <div class="compose-header">
        <input type="text" class="form-control" placeholder="Subject" name="subject" id="subject" value="">
    </div> -->
  <!--   <div class="compose-header">
        <textarea rows="8" class="form-control" style="width: 100%;" placeholder="Compose your email..." name="mail_body" id="mail_body"></textarea>
    </div> -->
    <div class="compose-header">
        <input type="file" class="form-control" name="attachment" id="attachment" value="">
    </div>
    <div>
        <input type="submit" name="submit" id="submit" class="button">
    </div>
</form>
</div>

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
