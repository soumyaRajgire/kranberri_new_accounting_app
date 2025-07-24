
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
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>  
 

<html lang="en">
<head>
    <title>iiiQbets</title>

    <meta charset="utf-8">
    <?php include("header_link.php");?>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"  crossorigin="anonymous">

    <style>
    .text-grey {
    color: grey; /* or any other shade of grey you prefer */
    background-color: white;
}
.tooltip-inner  {
    background-color: white;
    color: grey; /* Set text color to black or your preferred color */
    border-radius: 2px;
    font-size: 13px;
}

h5{
    font-size: 13px !important;
}
</style>


</head>


<body >
   
<?php 
if (isset($_GET['irn_no'])) { 
    $irn_no = $_GET['irn_no'];  // Assuming you assign the value to $irn_no
?>
    <form action="cancelEInvoice.php" method="POST">
        <input type="hidden" name="irn_no" value="<?php echo $irn_no; ?>" />
        <p>Please enter the details to cancel E-Invoice</p>

        <label for="username">Username</label>
        <input class="form-control" type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input class="form-control" type="password" id="password" name="password" required>

        <button type="submit" 
                class="btn border border-grey" 
                data-toggle="tooltip" 
                data-placement="top" 
                title="Cancel E-Invoice">
            Cancel E-Invoice
        </button>
    </form>

<?php
} else if (isset($_GET['eway_bill_no'])) { 
    $eway_bill_no = $_GET['eway_bill_no'];  // Assuming you assign the value to $eway_bill_no
?>
    <form action="cancelEWayBill.php" method="POST">
        <input type="hidden" name="e_way_bill_no" value="<?php echo $eway_bill_no; ?>" />
        <p>Please enter the details to cancel the E-Way Bill:</p>

        <label for="username">Username</label>
        <input class="form-control" type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input class="form-control" type="password" id="password" name="password" required>

        <button type="submit" 
                class="btn border border-grey" 
                data-toggle="tooltip" 
                data-placement="top" 
                title="Cancel E-Way Bill">
            Cancel E-Way Bill
        </button>
    </form>

<?php 
}
?>



 
<div class="modal fade" id="tieModal" tabindex="-1" role="dialog" aria-labelledby="tieModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tieModalLabel">Cancel E-Invoice / E-Way Bill</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="cancelInvoiceForm" action="" method="POST">
          <div class="form-group">
            <label for="cancelType">Select Action</label>
            <select class="form-control" id="cancelType" name="cancel_type">
              <option value="irn">Cancel E-Invoice</option> cancelEInvoice.php $quotationDetails['irn_no']
              <option value="eway">Cancel E-Way Bill</option> cancelEWayBill.php $quotationDetails['e_way_bill_no']
            </select>
          </div>
          
           Hidden Fields to Pass Data Dynamically 
          <input type="hidden" id="irn_no" name="irn_no">
          <input type="hidden" id="eway_bill_no" name="eway_bill_no">
          
           Action buttons 
          <button type="submit" class="btn btn-danger">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cancel_type = $_POST['cancel_type'];
    
    
    if ($cancel_type === 'irn') {
        // Handle E-Invoice Cancellation
        $irn_no = $_POST['irn_no'];
      echo "irn cancel";
    } elseif ($cancel_type === 'eway') {
        // Handle E-Way Bill Cancellation
        $eway_bill_no = $_POST['eway_bill_no'];
        echo "eawybil cancel";
    }
}
?>
<script>
// Handle dynamic data insertion into the modal
$('#tieModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var irn = button.data('irn'); // Extract info from data-* attributes
    var ewayBillNo = button.data('eway-bill-no');
    
    // Set hidden fields dynamically based on the action
    if (irn) {
        $('#cancelType').val('irn'); // Set cancel type for E-Invoice
        $('#irn_no').val(irn); // Set IRN number in hidden field
        $('#eway_bill_no').val(''); // Clear E-Way Bill number
    }
    
    if (ewayBillNo) {
        $('#cancelType').val('eway'); // Set cancel type for E-Way Bill
        $('#eway_bill_no').val(ewayBillNo); // Set E-Way Bill number in hidden field
        $('#irn_no').val(''); // Clear IRN number
    }
});
</script>
</body>
</html>