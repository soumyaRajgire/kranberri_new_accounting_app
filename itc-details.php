
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


$query = "CALL GetITCDashboardWithInvoices()";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the result into an array
$itc_data = [];
while ($row = $result->fetch_assoc()) {
    $itc_data[] = $row;
}
$stmt->close();

?>


<!DOCTYPE html>

<html lang="en">

<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">

    <style>
    .tab-button.active {
    background-color: #007bff;
    color: #fff;
}
.mandatory-symbol {
    color: red;
  }
  .error {
            color: red;
            size: 80%
        }

        .hidden {
            display: none;
        }

</style>
<style>
    .highlight-error {
        border: 2px solid red;
    }
</style>

</head>

<body class="">
    <!-- [ Pre-loader ] start -->

    <?php include("menu.php"); ?>

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
                                <h4 class="m-b-10">ITC </h4>
                                
                            </div>
                            
                            
                        </div>
                        
                    </div>
                </div>
            </div>

           <?php //include("purchases_menu.php");?>

        
               
 <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <!-- <h5>View  Details</h5> -->
                   
                        <!-- <span class="d-block m-t-5">use class <code>table-striped</code> inside table element</span> -->
             <!-- <a  href="create-purchase-invoice.php" class="btn btn-info" style="color: #fff !important;float:right;">Create</a> -->
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">                                                                                                            
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
  <thead class="table-light">
    <tr>
        <th scope="col">Month</th>
        <th scope="col">Year</th>
        <th scope="col">ITC Recorded</th>
         <th scope="col">Total Invoice GST</th>
        <th scope="col">ITC Received</th>
        <th scope="col">Reconcile</th>

        <th scope="col">Mismatch</th>
        <th scope="col">Default</th>
        <th scope="col">Purchase Not Recorded</th>
    </tr>
</thead>
<tbody>
    <?php if (!empty($itc_data)) { ?>
        <?php foreach ($itc_data as $row) { ?>
            <tr>
                <td><?= htmlspecialchars($row['Month']); ?></td>
                <td><?= htmlspecialchars($row['Year']); ?></td>
                <td><?= htmlspecialchars($row['ITC_Recorded']); ?></td>
                 <td><?= htmlspecialchars($row['Total_Invoice_GST']); ?></td>
                <td><?= htmlspecialchars($row['ITC_Received']); ?></td>
                 <td><?= htmlspecialchars($row['Reconciled']) ?></td>
                <td><?= htmlspecialchars($row['Mismatch']); ?></td>
                <td><?= htmlspecialchars($row['Default']); ?></td>
                <td><?= htmlspecialchars($row['Purchase_Not_Recorded']); ?></td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="7" style="text-align: center;">No data available for the selected period</td>
        </tr>
    <?php } ?>
</tbody>
</table>

   </div>
                    </div>
                </div>
            </div>
            <!-- [ stiped-table ] end -->
           
        </div>
              

<div id="top-error-message" class="error hidden" style="text-align: center; margin-bottom: 20px;"></div>


<script src="assets/js/myscript.js"></script>
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/pcoded.min.js"></script>
<script>
    $(document).ready(function() {
    $('#daterange').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    // Apply the selected date range to the input field
    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    // Clear the input field on cancel
    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // Open the date picker when the calendar icon is clicked
    $('#daterange-btn').click(function() {
        $('#daterange').focus();
    });
});

</script>
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> -->
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