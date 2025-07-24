
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
$query = "SELECT DISTINCT cn.id AS note_id, cn.dnote_code, cn.customer_name, cn.customer_id,cn.total_amount,cn.dnote_date, cn.dnote_file, cn.created_by, 
        pi.invoice_code AS document,pi.invoice_file as piInvFile, cni.product,  cni.qty, cni.price, cni.line_total, cm.gstin
    FROM 
        debit_note AS cn
    LEFT JOIN 
        debit_note_items AS cni 
    ON 
        cn.id = cni.dnote_id
    LEFT JOIN 
        pi_invoice AS pi 
    ON 
        cn.purchase_invoice_id = pi.id 
         LEFT JOIN 
            customer_master AS cm
          ON 
            cn.customer_id = cm.id
            WHERE cn.branch_id='$branch_id' AND cn.is_deleted = 0
            GROUP BY 
        cn.id  -- Group by the debit note ID to avoid duplicates
    ORDER BY 
        cn.dnote_code DESC";
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

<?php include("createNewVoucherModal.php"); ?>

 

    <!-- [ Main Content ] start -->
    <section class="pcoded-main-container">
   
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Debit Note</h4>
                                
                            </div>
                            
                            
                        </div>
                        
                    </div>
                </div>
            </div>

           <?php include("purchases_menu.php");?>

            <!-- <div class="card mt-3"> -->
                
                <!-- <div class="row mt-3 ml-2"> -->

                
            <!-- Table and Filters Section -->
            <!-- <div class="col-lg-12"> -->
                <!-- Search and Filters -->
              <!--   <div class="d-flex align-items-center mb-3">
                    <input type="text" class="form-control mr-2" placeholder="Search Credit Note...">
                    <button class="btn btn-outline-primary btn-sm mr-2"><i class="bi bi-sort-down"></i></button>
                    <button class="btn btn-outline-primary btn-sm mr-2"><i class="bi bi-funnel"></i></button>
<div class="input-group">
    <input type="text" class="form-control" id="daterange" placeholder="Date range">
    <button class="btn btn-outline-primary btn-sm mr-2" type="button" id="daterange-btn">
        <i class="bi bi-calendar"></i>
    </button>
</div>
                </div>
 -->
               
 <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <!-- <h5>View  Details</h5> -->
                 <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px; margin-top: 30px;">
                            <!-- Form for Month and Year Selection -->
                            <form class="form-inline" method="POST" action="download_debitnote_monthly.php" style="display: flex; align-items: center;">
                                <label>Select Month and Year Data:</label>
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
                            <form class="form-inline" method="POST" action="download_debitnote_range.php" style="display: flex; align-items: center; margin-right: 10px;">
                                <label style="margin-right: 10px;">Select Date Range:</label>
                                <input type="date" class="form-control" id="from_date" name="from_date" required style="margin-right: 10px; width: auto;"
                                value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
                                <input type="date" class="form-control" id="to_date" name="to_date" required style="margin-right: 10px; width: auto;"
                                value="<?php echo date('Y-m-d'); ?>">
                                <button type="submit" class="btn btn-success" name="download_range">
                                    <i class="fa fa-download"></i> Download Range
                                </button>
                            </form>
                            <a href="create-debit-note.php" class="btn btn-info" style="color: #fff !important;">Create</a>
                        </div>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">                                                                                                            
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead class="table-light">
        <tr>
            <th scope="col">Supplier Name</th>
            <th scope="col">Note Number</th>
            <th scope="col">Document</th>
              <th scope="col">Debit Amount</th>
            <th scope="col">Created</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data)) : ?>
            <?php foreach ($data as $row) : ?>
                <tr>
                <td>
    <?= htmlspecialchars($row['customer_name']); ?><br />
    <?php if (!empty($row['gstin'])) : ?>
        <strong>GSTIN: <?= htmlspecialchars($row['gstin']); ?></strong>
    <?php else : ?>
        <a href="update-customer.php?id=<?= htmlspecialchars($row['customer_id']) ?>" class="text-danger">Update GSTIN</a>
    <?php endif; ?>
</td>
                   
                    
                   <td>
    <a href="view-debit-action.php?inv_id=<?= htmlspecialchars($row['note_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <?= htmlspecialchars($row['dnote_code'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
    </a><br/>
    <?= !empty($row['dnote_date']) ? htmlspecialchars(date('d-m-Y', strtotime($row['dnote_date'])), ENT_QUOTES, 'UTF-8') : ''; ?>
</td>

                    <td>
                       <a href =""> <?= htmlspecialchars($row['document']); ?></a><br />
                        <p>Purchase Invoice</p>
                    </td>
                     <td>
                        <span class="">
                            INR. <?= htmlspecialchars($row['total_amount']); ?>
                        </span><br/>
                        <a href="view-debit-action.php?inv_id=<?= htmlspecialchars($row['note_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" style="color:green" >View Debit Note</a>
                    </td>
                    <td>
                        <?= htmlspecialchars(date('d-m-Y', strtotime($row['dnote_date']))); ?><br />
                        <?= htmlspecialchars($row['created_by']); ?>
                    </td>
                    <td>
                     <a href="<?= $row['dnote_file']; ?>"  class="btn-sm btn btn-primary" download><i class="fa fa-download" ></i></a>


                        <!-- <a href="edit_debit_note.php?id=<?= $row['note_id']; ?>" class="btn btn-sm btn-primary">Edit</a> -->
                        <!-- <a href="delete_debit_note.php?id=<?= $row['note_id']; ?>" class="btn btn-sm btn-danger">Delete</a> -->
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6" class="text-center">No records found</td>
            </tr>
        <?php endif; ?>
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