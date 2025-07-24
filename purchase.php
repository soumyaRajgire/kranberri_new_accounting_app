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

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    /* Custom border styles for the table */
    table.table-bordered {
        border: 1px solid grey; /* Thicker outer border with custom color */
    }
    table.table-bordered th, table.table-bordered td {
        border: 1px solid grey; /* Thicker inner borders with custom color */
    }
      /* Custom border radius for the search input and button */
      .input-group .form-control {
        border-radius: 30px 0 0 30px; /* Rounded left corners of the input */
    }

    .input-group .input-group-append .btn {
        border-radius: 0 30px 30px 0; /* Rounded right corners of the button */
    }
      /* Customizing the tab link colors */
      .nav-tabs .nav-link {
        color: #000; /* Default tab link color (black) */
        font-weight: normal; /* Normal weight for inactive tabs */
    }
    
    .nav-tabs .nav-link.active {
        color: #000; /* Active tab link color (black) */
        background-color: grey;
    }

    .nav-tabs .nav-link:hover {
        color: #000; /* Hover color (black or dark gray) */
       
    }
</style>
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
                                <h4 class="m-b-10">Purchase</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <!-- Tab structure -->
            <div class="card" style="border-radius: 5px; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="purchaseTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="purchase-bill-tab" data-toggle="tab" href="#purchase-bill" role="tab" aria-controls="purchase-bill" aria-selected="false">Purchase Bill</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="uploaded-bill-tab" data-toggle="tab" href="#uploaded-bill" role="tab" aria-controls="uploaded-bill" aria-selected="false">Uploaded Bill</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="purchaseTabContent">
                    <div class="row mt-3">
    <div class="col-md-3">
        <!-- Search section on the left -->
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search-addon">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="search-addon"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>

    <div class="col-md-9" style="text-align: right;">
        <!-- Buttons on the right -->
        <button class="btn btn-primary" id="createNewBtn">Create New</button>
        <button class="btn btn-secondary" id="uploadBillBtn">Upload Bill</button>
    </div>
</div>
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                           <!-- All content here -->


<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Purchase Invoice No</th>
            <th>Purchase Type</th>
            <th>Purchase Date</th>
            <th>Seller Name</th>
            <th>Amount (₹)</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="7" class="text-center">No data available</td>
        </tr>
    </tbody>
</table>
                        </div>
                        <div class="tab-pane fade" id="purchase-bill" role="tabpanel" aria-labelledby="purchase-bill-tab">
                            <!-- Purchase Bill content here -->
                            <table class="table  table-bordered  mt-3">
                                <thead>
                                    <tr>
                                        <th>Purchase Invoice No</th>
                                        <th>Purchase Type</th>
                                        <th>Purchase Date</th>
                                        <th>Seller Name</th>
                                        <th>Amount (₹)</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center">No data available</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="uploaded-bill" role="tabpanel" aria-labelledby="uploaded-bill-tab">
                            <!-- Uploaded Bill content here -->
                            <table class="table  table-bordered  mt-3">
                                <thead>
                                    <tr>
                                        <th>Purchase Invoice No</th>
                                        <th>Purchase Type</th>
                                        <th>Purchase Date</th>
                                        <th>Seller Name</th>
                                        <th>Amount (₹)</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center">No data available</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.getElementById("createNewBtn").addEventListener("click", function() {
        window.location.href = "purchase_create.php"; // Replace with the actual page URL
    });
</script>
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>
