
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

<?php
// Fetch data from credit_note and credit_note_items
$query = "SELECT cn.id AS note_id, cn.cnote_code, cn.customer_name, cn.total_amount,cn.cnote_date, cn.cnote_file, cn.created_by, 
        pi.invoice_code AS document, cni.product,  cni.qty, cni.price, cni.line_total
    FROM 
        credit_note AS cn
    LEFT JOIN 
        credit_note_items AS cni 
    ON 
        cn.id = cni.cnote_id
    LEFT JOIN 
        invoice AS pi 
    ON 
        cn.invoice_id = pi.id
    ORDER BY  cn.cnote_date DESC";

$result = $conn->query($query);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
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
                                <h4 class="m-b-10">Ledger</h4>
                                
                            </div>
                            
                            
                        </div>
                        
                    </div>
                </div>
            </div>

           <!-- <?php //include("sales_menu.php");?> -->

     
 <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <!-- <h5>View  Details</h5> -->
                   
                        <!-- <span class="d-block m-t-5">use class <code>table-striped</code> inside table element</span> -->
                        <!--<a  href="create-purchase-invoice.php" class="btn btn-info" style="color: #fff !important;float:right;">Create</a>-->
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
<table class="table table-striped">
    <thead>
         <tr>
            
            <th>Transaction Date</th>
            <th>Transaction Type</th>
            <th>Account Name</th>
            <th>Credit/Debit</th>
            <th>Credit</th>
            <th>Debit</th>            
            <th>Rec/Vou No</th>
            <th>Created On</th>
            
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * from ledger ORDER BY created_at DESC";

        $result = $conn->query($sql);
     
    $runningBalance = 0; // Initialize running balance
    $totalDebit = 0; // Total Debit Amount
    $totalCredit = 0; // Total Credit Amount

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
            
  $creditAmount = $row['debit_credit'] === 'C' ? $row['amount'] : 0;
            $debitAmount = $row['debit_credit'] === 'D' ? $row['amount'] : 0;

            // Add to running totals
            $totalCredit += $creditAmount;
            $totalDebit += $debitAmount;
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['transaction_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['transaction_type']) . "</td>";
                //echo "<td>" . htmlspecialchars($row['account_name']) . "</td>";
                 echo "<td><a href='customer-details.php?id=" . $row['account_id'] . "'>" . htmlspecialchars($row['account_name']) . "</a></td>";
                echo "<td>" . htmlspecialchars($row['debit_credit']) . "</td>";
                echo "<td>" . ($creditAmount > 0 ? htmlspecialchars($creditAmount) : "-") . "</td>";
                echo "<td>" . ($debitAmount > 0 ? htmlspecialchars($debitAmount) : "-") . "</td>";
                echo "<td>" . htmlspecialchars($row['receipt_or_voucher_no']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "</tr>";
            }
        

  echo "<tr class='table-light'>";
        echo "<td colspan='4' class='text-end'><strong>Total:</strong></td>";
        echo "<td><strong>" . htmlspecialchars($totalCredit) . "</strong></td>";
        echo "<td><strong>" . htmlspecialchars($totalDebit) . "</strong></td>";
        echo "<td colspan='2'></td>";
        echo "</tr>";
        } else {
            echo "<tr><td colspan='8' class='text-center'>No transactions found</td></tr>";
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
                <!-- Pagination Info -->
             <!--    <div class="d-flex justify-content-between align-items-center">
                    <select class="form-control w-auto">
                        <option>10</option>
                        <option>20</option>
                        <option>50</option>
                    </select>
                    <span>Showing 1 - 0 of 0</span>
                </div> -->
            <!-- </div> -->

            <!-- Summary Section -->
            <!-- <div class="col-lg-3">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Today
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Yesterday
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Last Week
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        This Month
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Last Month
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Last 3 Months
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        All
                        <span class="badge bg-primary rounded-pill">0</span>
                    </li>
                </ul>
            </div> -->
        <!-- </div> -->
    
<!-- </div> -->


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