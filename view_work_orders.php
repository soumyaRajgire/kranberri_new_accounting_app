
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
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);

    // Start with error suppression off for debugging
    mysqli_report(MYSQLI_REPORT_OFF);

    // Delete from work_order_items first (due to FK constraints if any)
    $itemsDeleted = mysqli_query($conn, "DELETE FROM work_order_items WHERE work_order_id = $id");

    if (!$itemsDeleted) {
        error_log("Error deleting from work_order_items: " . mysqli_error($conn) . "\n", 3, "error_log.txt");
    }

    // Now delete from work_orders
    $orderDeleted = mysqli_query($conn, "DELETE FROM work_orders WHERE id = $id");

    if (!$orderDeleted) {
        error_log("Error deleting from work_orders: " . mysqli_error($conn) . "\n", 3, "error_log.txt");
    }

    if ($itemsDeleted && $orderDeleted) {
        echo "<script>
            alert('Work order deleted successfully.');
            window.location.href = 'view_work_orders.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Error deleting work order. Please check the error_log.txt for details.');</script>";
    }
}


?>  
 


<html lang="en">
<head>
    <title>iiiQbets</title>
  
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
                            <h4 class="m-b-10">View Work Orders</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Work Orders</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->

<?php include("purchases_menu.php");?>
  
                
  <div class="row">
            <div class="col-sm-12">
                <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
    
    <h5 style="margin: 0;">View work order Details</h5>

  
</div>


        <div class="card-body table-border-style">
            <div class="table-responsive">
                            <!-- <table class="table table-striped table-bordered" id="dataTables-example"> -->
                              
                        <!-- Your HTML table structure -->
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th>Supplier</th>
            <th>Manufacturer</th>
            <th>Work Order Number</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
       

       <?php
       $branch_id = $_SESSION['branch_id'];
// Fetch work order details
$result = mysqli_query($conn, "SELECT wo.id, wo.wo_number, wo.work_order_file,wo.wo_date, wo.pi_code, wo.pi_date,wo.supplier_id, wo.manufacturer_id, wo.note, wo.created_at,wo.work_order_file, cm.customerName AS supplier_name, 
        m.customerName AS manufacturer_name FROM  work_orders wo
    JOIN  customer_master cm ON wo.supplier_id = cm.id  LEFT JOIN  customer_master m ON wo.manufacturer_id = m.id
    WHERE wo.branch_id = '$branch_id' ORDER BY wo.id DESC; ");


       while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td><a href="supplier-details.php?id=' . $row['supplier_id'] . '">' . $row['supplier_name'] . '</a></td>';
            echo '<td><a href="manufacturer-details.php?id=' . $row['manufacturer_id'] . '">' . $row['manufacturer_name'] . '</a></td>';
            echo '<td><a href="#" class="" data-toggle="modal" data-target="#viewWorkOrderModal" data-file="' . $row['work_order_file'] . '">' . $row['wo_number'] . '</a><br/>' . $row['wo_date'] . '</td>';
            echo '<td>' . $row['created_at'] . '</td>';
           echo '<td>
    <a href="#" class="btn-sm btn btn-primary" data-toggle="modal" data-target="#viewWorkOrderModal" data-file="' . $row['work_order_file'] . '">
        <i class="fa fa-eye"></i>
    </a>

    <form method="post" onsubmit="return confirm(\'Are you sure you want to delete this work order?\');" style="display:inline;">
        <input type="hidden" name="delete_id" value="' . $row['id'] . '">
        <button type="submit" class="btn-sm btn btn-danger">
            <i class="fa fa-trash"></i> Delete
        </button>
    </form>

    <a href="generate-tags.php?wo_id=' . $row['id'] . '" class="btn-sm btn btn-success">
        <i class="fa fa-tag"></i> Generate Tags
    </a>
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

<!-- Modal to display Work Order PDF -->
<div class="modal fade" id="viewWorkOrderModal" tabindex="-1" role="dialog" aria-labelledby="viewWorkOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewWorkOrderModalLabel">Work Order PDF</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Embed PDF using iframe -->
                <iframe id="pdfViewer" src="" width="100%" height="500px"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">Print Work Order</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
      
        $('#viewWorkOrderModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Get the button that triggered the modal
            var pdfFile = button.data('file'); // Extract the work order PDF file path

            var modal = $(this);
            modal.find('#pdfViewer').attr('src', pdfFile); // Set the PDF source for the iframe
        });
    });
</script>

</body>
</html>
