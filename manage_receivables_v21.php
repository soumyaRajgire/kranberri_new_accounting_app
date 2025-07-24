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
    <title>Accounts App</title>
    <meta chaINRet="utf-8">
    <?php include("header_link.php");?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>
<body class="">
    <!-- Pre-loader -->
    <?php include("menu.php");?>
    <!-- Header -->
    <style>
        /* Custom CSS styles for the card */
        .table{
            border: 2px solid grey; /* Define your desired border style and color here */
        
        }  
        #allreciv_sec th,
        #myreciv_sec th,
        #overdue_sec th,
        #partywise_sec th {
        text-transform: capitalize;
        font-size: 14px;
    }
    </style>
 
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Manage Receivables</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <!-- <li class="breadcrumb-item"><a href="#">Manage Receivables</a></li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

<?php include("sales_menu.php");?>
      <div class="container-fluid">
    <div class="row">
        <!-- Center Section Start-->
        <div class="col-lg-10 card">
     <div class="row">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#partywise_sec">Party-wise Receivables</a> 
        </li>
        <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#allreciv_sec">ageing</a>
        </li>
       <!--  <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#myreciv_sec">My Receivables</a>
        </li> -->
        <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#overdue_sec">Overdue Invoices</a>
        </li>
        <!-- <div class="form-group" style="margin-left: 0px;">
            <div class="input-group">
                <input type="text" class="form-control form-controt receive_search party_search mt-2" placeholder="Search...">
            </div>
        </div> -->
    </ul>
    </div>
    <!-- Tab panes -->
    <div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="partywise_sec">
        <?php

function getPartyWiseReceivables($conn) {
    $data = [];

    $query = "SELECT 
                  r.customer_id, 
                  c.customerName, 
                  SUM(r.total_amount) AS TotalBilled, 
                  SUM(r.paid_amount) AS TotalPaid, 
                  (SUM(r.total_amount) - SUM(r.paid_amount)) AS Receivable,
                  MAX(r.transaction_date) AS LastPayment 
              FROM receipts r
              INNER JOIN customer_master c ON r.customer_id = c.id
              GROUP BY r.customer_id";

    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}

// Function to get data for All Receivables
function getAllReceivables($conn) {
    $data = [];

    $query = "SELECT *,c.customerName FROM receipts r INNER JOIN customer_master c ON r.customer_id = c.id";

    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}


// Function to get data for Overdue Receivables
function getOverdueReceivables($conn) {
    $data = [];
    $today = date('Y-m-d'); // Current date

    // $query = "SELECT r.*, c.customerName  FROM receipts r  INNER JOIN customer_master c ON r.customer_id = c.id  WHERE  (SUM(r.total_amount) - SUM(r.paid_amount)) > 0 AND r.transaction_date < '$today'";

    $query =" SELECT r.customer_id, c.customerName, SUM(r.total_amount) AS total_amount, SUM(r.paid_amount) AS paid_amount, r.transaction_date FROM receipts r INNER JOIN customer_master c ON r.customer_id = c.id WHERE r.transaction_date < '$today' GROUP BY r.customer_id, c.customerName HAVING (SUM(r.total_amount) - SUM(r.paid_amount)) > 0";


    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}

// Depending on the selection, fetch the appropriate data
// Assuming there's a GET parameter 'view' that controls the selection
$partyWiseReceivables = getPartyWiseReceivables($conn);
$getAllReceivables = getAllReceivables($conn);
$overdueReceivables = getOverdueReceivables($conn); 

?>

        <table class="table table-bordered" id="dataTables-example">
            <!-- Table Header -->
            <thead>
                <tr>
                    <th>Receivable</th>
                    <th>Customer</th>
                    <th>Total Billed</th>
                    <th>Total Paid</th>
                    <th>Last Payment</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
<?php foreach ($partyWiseReceivables as $rec) : ?>
<tr>
    <td><?php echo htmlspecialchars($rec['Receivable']); ?></td>
    <td><?php echo htmlspecialchars($rec['customerName']); ?></td>
    <td><?php echo htmlspecialchars($rec['TotalBilled']); ?></td>
    <td><?php echo htmlspecialchars($rec['TotalPaid']); ?></td>
    <td><?php echo htmlspecialchars($rec['LastPayment']); ?></td>
</tr>
<?php endforeach; ?>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

    <div class="tab-pane fade" id="allreciv_sec">
        <table class="table table-bordered" id="dataTables-example1">
            <!-- Table Header -->
            <thead>
                <tr>
                    <th>Balance</th>
                    <th>Customer</th>
                    <th>Total Billed</th>
                    <th>Total Paid</th>
                    <th>Last Payment</th>
                </tr>
            </thead>
           
            <tbody>
                <?php foreach ($getAllReceivables as $rec1) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($rec1['total_amount'] - $rec1['paid_amount']); ?></td>
                    <td><?php echo htmlspecialchars($rec1['customerName']); ?></td>                 
                    <td><?php echo htmlspecialchars($rec1['total_amount']); ?></td>
                    <td><?php echo htmlspecialchars($rec1['paid_amount']); ?></td>
                    <td><?php echo htmlspecialchars($rec1['transaction_date']); ?></td>
                </tr>
                <?php endforeach; ?>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>

<!--     <div class="tab-pane fade" id="myreciv_sec">
        <table class="table table-bordered">
           <thead>
                <tr>
                    <th>Balance</th>
                    <th>Customer</th>
                    <th>Total Billed</th>
                    <th>Total Paid</th>
                    <th>Balance</th>
                    <th>Last Payment</th>
                </tr>
            </thead>
            <tbody>
                 <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
        </table>
    </div> -->

    <div class="tab-pane fade" id="overdue_sec">
        <table class="table table-striped table-bordered" id="dataTables-example2">
            <!-- Table Header -->
            <thead>
                <tr>
                    <th>Balance</th>
                    <th>Customer</th>
                    <th>Total Billed</th>
                    <th>Total Paid</th>
                    <th>Last Payment</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                 <?php foreach ($overdueReceivables as $rec2) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($rec2['total_amount'] - $rec2['paid_amount']); ?></td>
                    <td><?php echo htmlspecialchars($rec2['customerName']); ?></td>                    
                    <td><?php echo htmlspecialchars($rec2['total_amount']); ?></td>
                    <td><?php echo htmlspecialchars($rec2['paid_amount']); ?></td>
                    <td><?php echo htmlspecialchars($rec2['transaction_date']); ?></td>
                </tr>
                <?php endforeach; ?>
               
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>

</div>

       <!--  <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="estimate_sec1">
                        <div class="list-group">
                            <a href="" class="list-group-item list-group-item-action">
                                0-15 Days
                                <span class="badge badge-primary float-right" id="total_receiveable_15">INR.0</span>
                            </a>
                            <a href="" class="list-group-item list-group-item-action">
                                15-30 Days
                                <span class="badge badge-success float-right" id="total_receiveable_30">INR.0</span>
                            </a>
                            <a href="" class="list-group-item list-group-item-action">
                                30-60 Days
                                <span class="badge badge-danger float-right" id="total_receiveable_60">INR.0</span>
                            </a>
                            <a href="" class="list-group-item list-group-item-action">
                                60-90 Days
                                <span class="badge badge-primary float-right" id="total_receiveable_90">INR.0</span>
                            </a>
                            <a href="" class="list-group-item list-group-item-action">
                                90-180 Days
                            <span class="badge badge-success float-right" id="total_receiveable_180">INR.0</span>
                            </a>
                            <a href="" class="list-group-item list-group-item-action">
                                Above 180 Days
                            <span class="badge badge-danger float-right" id="total_receiveable-180">INR.0</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Center Section End-->
        <!-- Right Section Start-->
        <!-- Right Section End -->
    </div>
    </div>


       </div>
    </section>        
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>

    <script type="text/javascript">
    $(document).ready(function () {
    $('#dataTables-example').DataTable();
    $('.dataTables_length').addClass('bs-select');

    });
    $('#dataTables-example').dataTable( {
    "orderFixed": [ 4, 'asc' ]
    } );
</script>

 <script type="text/javascript">
    $(document).ready(function () {
    $('#dataTables-example1').DataTable();
    $('.dataTables_length').addClass('bs-select');

    });
    $('#dataTables-example1').dataTable( {
    "orderFixed": [ 4, 'asc' ]
    } );
</script>

 <script type="text/javascript">
    $(document).ready(function () {
    $('#dataTables-example2').DataTable();
    $('.dataTables_length').addClass('bs-select');

    });
    $('#dataTables-example2').dataTable( {
    "orderFixed": [ 4, 'asc' ]
    } );
</script>
</body>
</html>
