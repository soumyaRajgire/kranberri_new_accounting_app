
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
     
    
    

</head>
<body class="">
    <!-- [ Pre-loader ] start -->
     
     <?php include("menu.php");?>
    
     <?php //include("createReceiptModal.php") ?>
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
                            <h4 class="m-b-10">Bill Of Supply Invoice</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Bill Of Supply Invoice</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->


<?php include("sales_menu.php");?>
  
                
  <div class="row">
            <div class="col-sm-12">
                <div class="card">
                <div class="card-header" >
    <!-- Left: View Invoice Details -->
    <h5 style="margin: 0;">Bill Of Supply Invoice Details</h5>

   <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                      
                        <form class="form-inline" method="POST" action="download_billsupply_monthly.php" style="display: flex; align-items: center; margin-right: 10px;">
                            <label style="margin-right: 10px;">Select Month and Year Data:</label>
                            <select class="form-control" id="month" name="month" style="margin-right: 10px; width: auto;">
                                <?php
                                $current_month = date("m");
                                for ($month = 1; $month <= 12; $month++) {
                                    $selected = ($current_month == $month) ? 'selected' : '';
                                    echo "<option value=\"$month\" $selected>" . date('F', mktime(0, 0, 0, $month, 1)) . "</option>";
                                }
                                ?>
                            </select>
                            <select class="form-control" id="year" name="year" style="margin-right: 10px; width: auto;" required>
                                <?php
                                $current_year = date("Y");
                                for ($i = $current_year; $i >= 2017; $i--) {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-success" name="download_month">
                                <i class="fa fa-download"></i> Download
                            </button>
                        </form>
                        
                        <!-- Form for Date Range Selection -->
                        <form class="form-inline" method="POST" action="download_billsupply_range.php" style="display: flex; align-items: center; margin-right: 10px;">
                            <label style="margin-right: 10px;">Select Date Range:</label>
                          <input type="date" class="form-control" id="from_date" name="from_date" required style="margin-right: 10px; width: auto;" value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
                                <input type="date" class="form-control" id="to_date" name="to_date" required style="margin-right: 10px; width: auto;" value="<?php echo date('Y-m-d'); ?>">
                            <button type="submit" class="btn btn-success" name="download_range">
                                <i class="fa fa-download"></i> Download Range
                            </button>
                        </form>
                        <a href="billsupply-invoice.php" class="btn btn-info" style="color: #fff;">Create</a>
                    </div>
</div>


                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <!-- <table class="table table-striped table-bordered" id="dataTables-example"> -->
                              
                        <!-- Your HTML table structure -->
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th>Customer </th>
            <th>Invoice</th>
            <th>Total Amount</th>
            <th>Payment</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
    <?php
// Fetch data from `bill_of_supply` and `customer_master` tables
$result = mysqli_query($conn, "
    SELECT
        cm.customerName,
        cm.id AS cm_id,
        cm.email AS customerEmail,
        bs.bill_code AS quotationNumber,
        bs.created_by AS quotationCreatedBy,
        bs.created_on AS quotationCreatedOn,
        bs.grand_total,
        bs.bill_date,
        bs.bill_file,
        bs.due_date,
        bs.status,
        bs.total_amount,
        bs.id AS iid
    FROM
        bill_of_supply bs
    JOIN
        customer_master cm ON bs.customer_id = cm.id
    WHERE
        bs.branch_id = '$branch_id'
    ORDER BY
        bs.id DESC
");

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td><a href="customer-details-info.php?ctmr_id=' . $row['cm_id'] . '">' . $row['customerName'] . '</a><br/>' . $row['customerEmail'] . '</td>';
    echo '<td><a href="view-billsupply-action.php?bill_id=' . $row['iid'] . '">' . $row['quotationNumber'] . '</a><br/>' . $row['bill_date'] . '</td>';
    echo '<td>' . $row['grand_total'] . '<br/>' . $row['status'] . '</td>';
    echo '<td>' . $row['quotationCreatedOn'] . '<br/>' . $row['quotationCreatedBy'] . '</td>';
    echo '<td>
            <a href="' . $row['bill_file'] . '" class="btn-sm btn btn-primary" download><i class="fa fa-download"></i></a>
            <a href="mail-quotation.php?id=' . $row['iid'] . '&qcode=' . $row['quotationNumber'] . '" class="btn btn-primary btn-sm"><i class="fa fa-envelope"></i></a>
          </td>';
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
        $('#dataTables-example').DataTable({
            "ordering": false // Disable sorting completely
        });
    });
</script>
</body>
</html>
