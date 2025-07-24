<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['LOG_IN'])) {
  header("Location:login.php");
} else {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
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
  <meta charset="utf-8">
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/custom.css"> -->
  <?php include("header_link.php"); ?>
  <!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.js"></script>

  <style type="text/css">
      .table th, .table td{
        padding:0.45rem !important;
      }
  </style>

<style>
  .vertical_line {
    border-left: 1px solid black;
    height: 300px;
    position: absolute;
    left: 70%;
    margin-left: -3px;
    top: 0;
  }

  body{
    font-size:13px;
  }
  .charge-input {
    height:30px !important;
  }
  td{
    padding: 0px !important;
  }
 #additional-charges-container {
    background-color:#f6f6f6;
  }
  .cus_padding{
    padding: 0px !important;
  }

 /* Collapse content */
.collapse-content {
    display: none;
    border-top: 1px solid #ddd;
}

/* Icon rotation */
.rotate-icon {
    transition: transform 0.3s ease;
}

/* Rotate icon when active */
.rotate-icon.active {
    transform: rotate(180deg);
}

#transporterHeader {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    width: auto; /* Adjust this width if necessary */
}

</style>

</head>

<body class="">
 
  <!-- [ Pre-loader ] start -->
  <?php include("menu.php"); ?>
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
                <h4 class="m-b-10">Create Work Order</h4>
              </div>
              <ul class="breadcrumb">
                <!-- <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li> -->
                <!-- <li class="breadcrumb-item"><a href="#">Quotation</a></li> -->
                <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <!-- [ stiped-table ] start -->
      <div id="loader-overlay" style="
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    z-index: 9999;
    text-align: center;
">
  <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
      <span class="visually-hidden">Loading...</span>
    </div>
    <div style="margin-top: 10px;">Submitting...</div>
  </div>
</div>

      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
             <h4 class="m-b-10">Create Work Order</h4>
            </div>

  <div class="card-body table-border-style">
    <div class="table-responsive">
      <div class="row">
        <div class="col-sm-12">
          <div class="">
            <div class="card-body">
              <!-- Loader Overlay -->


              <form id="purchaseinvoice" action="word_order_save.php" method="POST" onsubmit="return validateForm();">

                <div class="row border border-dark" >  
                  <?php include 'fetch_user_data.php'; ?>


<div class="col-md-8 border-right border-dark">
<h6 style="float:left;" class="pt-2">
<?php echo htmlspecialchars($user['branch_name']); ?><br/>
<?php echo htmlspecialchars($user['address_line1']); ?><?php echo $user['address_line2']?><br/>
Email: <?php echo htmlspecialchars($user['email']); ?><br/>
Phone: <?php echo htmlspecialchars($user['phone_number']); ?><br/>
GSTIN: <?php echo htmlspecialchars($user['GST']); ?><br/>
<input type="text" name="business_state" id="business_state" value="<?php echo htmlspecialchars($user['state']); ?>" hidden>

</h6>
</div> 
                  
            <div class="col-md-4 pt-1">
              <div class="py-1 input-group">
              <?php
                  $result1=mysqli_query($conn,"select id from work_orders where id=(select max(id) from work_orders)");
  if($row1=mysqli_fetch_array($result1))
  {
    $id=$row1['id']+1;
    $i=$row1['id'];
    $s=preg_replace("/[^0-9]/", '', $i);
    $invoice_code="WO0".($s+1);
 }
 else{
  $id = 0;
  $invoice_code = "WO0".(1);
 }

$purchase_id = isset($_GET['id']) ? $_GET['id'] : '';
$purchase_code = isset($_GET['qcode']) ? $_GET['qcode'] : '';

$sql2 = "SELECT * FROM pi_invoice WHERE id='$purchase_id' AND invoice_code='$purchase_code'";

$result2 = $conn->query($sql2);
if ($result2 && $result2->num_rows > 0) {
    $row2 = $result2->fetch_assoc();
}
?>
              
              <input class="form-control" type="text" id="invoice_code" value="<?php echo $invoice_code; ?>" name="invoice_code" required/>
                <label class="form-control col-sm-5" for="invoice_code">WO No</label>
                
                        </div>
                        <div class="py-1 input-group">
                            <input class="form-control" type="date" id="invoice_date" name="invoice_date" required/>
                            <label class="form-control col-sm-5" for="invoice_date">WO Date</label>
                        </div>
                        
                    </div>
                </div>

<script>
    // Get the current date
    const currentDate = new Date();
    const formattedCurrentDate = currentDate.toISOString().split('T')[0];

    // Set the values of the date inputs
    document.getElementById('invoice_date').value = formattedCurrentDate;
    // document.getElementById('dueDate').value = formattedDueDate;
</script>
<div class="row" id="customer_data"></div>
<?php
$purchase_id = isset($_GET['id']) ? $_GET['id'] : '';

$supplier = null;
if ($purchase_id) {
    $sql = "SELECT pi.*, cm.id AS cmid,cm.customerName, cm.business_name, cm.gstin, cm.tds_slab_rate, cm.id as supplier_id FROM pi_invoice pi JOIN customer_master cm ON pi.customer_id = cm.id WHERE pi.id = '$purchase_id' LIMIT 1";

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $supplier = $result->fetch_assoc();
    }
}
?>


           <div class="row" id="customer_dp">
    <?php if ($supplier): ?>
        <div class="col-md-4 border-left border-bottom border-dark p-3">
            <div>
                <div class="d-flex align-items-center">
                    <h6 class="mr-2">Supplier/Customer Info</h6>
                   <!--  <button type="button" id="edit_button" class="btn btn-primary" onclick="clearInput()">
                        <i class="fas fa-edit"></i>
                    </button> -->
                </div>
                  <?php
  $sql1 = "SELECT * FROM address_master WHERE customer_master_id = '" . $supplier['cmid'] . "'";
               
                $result1=$conn->query($sql1);
                if($result1->num_rows>0)    
                    {
                        if($row1 = mysqli_fetch_assoc($result1))
                        {
                            ?>
                <span><?php echo htmlspecialchars($supplier['customerName']); ?></span><br/>
                <span>Business Name: <?php echo $supplier['business_name'] === "" ? "" : htmlspecialchars($supplier['business_name']); ?></span><br/>
                <span><?php echo htmlspecialchars($row1['s_state']); ?></span><br/>
                <input type="text" name="customer_s_state" id="customer_s_state" value="<?php echo htmlspecialchars($row1['s_state']); ?>" hidden>
                <input type="text" name="customer_b_state" id="customer_b_state" value="<?php echo htmlspecialchars($row1['b_state']); ?>" hidden>
                <input type="text" name="supplier_id" id="supplier_id" value="<?php echo htmlspecialchars($supplier['cmid']); ?>" hidden>
                <span>GSTIN: <?php echo htmlspecialchars($supplier['gstin']); ?></span>
            </div>
        </div>
      

        <div class="col-md-4 border-left border-bottom border-dark p-3">
            <div>
                <h6>Billing Address</h6>
                <span><?php echo $row1['b_address_line1'] === "" ? '<span style="color:red;">Address Line1</span>' : htmlspecialchars($row1['b_address_line1']); ?></span><br/>
                <span><?php echo $row1['b_address_line2'] === "" ? '<span style="color:red;">Address Line2</span>' : htmlspecialchars($row1['b_address_line2']); ?></span><br/>
                <span><?php echo ($row1['b_city'] === "" ? '<span style="color:red;">City</span>' : htmlspecialchars($row1['b_city'])) . "-" . ($row1['b_Pincode'] === "" ? '<span style="color:red;">Pincode</span>' : htmlspecialchars($row1['b_Pincode'])); ?></span><br/>
            </div>
        </div>

        <div class="col-md-4 border-left border-bottom border-right border-dark p-3">
            <h6>Shipping Address</h6>
            <span><?php echo $row1['s_address_line1'] === "" ? '<span style="color:red;">Address Line1</span>' : htmlspecialchars($row1['s_address_line1']); ?></span><br/>
            <span><?php echo $row1['s_address_line2'] === "" ? '<span style="color:red;">Address Line2</span>' : htmlspecialchars($row1['s_address_line2']); ?></span><br/>
            <span><?php echo ($row1['s_city'] === "" ? '<span style="color:red;">City</span>' : htmlspecialchars($row1['s_city'])) . "-" . ($row1['s_Pincode'] === "" ? '<span style="color:red;">Pincode</span>' : htmlspecialchars($row1['s_Pincode'])); ?></span><br/>
        </div>
        <?php
}
}
        ?>
    <?php endif; ?>
</div>

                
     <div class="row border-dark border-right border-left border-top border-bottom" id="">
            <div class="col-md-3 py-1">
                  <input class="form-control" type="text" id="pi_code" value="<?php echo $purchase_code; ?>"  name="pi_code" readonly/>
                  <!-- <label class="form-control col-sm-5" for="pi_code">PI No</label> -->
            </div>
            <div class="col-md-3 py-1">
                <input class="form-control" type="text" id="purchase_invoice_date" value="<?php echo $row2['invoice_date']?>" name="purchase_invoice_date" readonly>
                 <!-- <label class="form-control col-sm-5" for="purchase_invoice_date">PI Date</label> -->
            </div>
            <div class="col-md-3 py-1">
                
    <select class="form-control" type="text" id="select_manufacturer" name="select_manufacturer" onchange="updateManufacturerName()">
        <option value="">Select Manufacturer</option>
        <?php
           $branch_id = $_SESSION['branch_id'];
            // PHP code to fetch manufacturers from your database
            $res = $conn->query("SELECT customerName, id FROM customer_master WHERE contact_type='Manufacturer' AND branch_id='$branch_id'");
            while ($row = $res->fetch_assoc()) {
                echo "<option value='{$row['id']}' data-name='{$row['customerName']}'>{$row['customerName']}</option>";
            }
        ?>
    </select>
    <!-- Hidden field to store manufacturer name -->
    <input type="hidden" name="manufacturer_name" id="manufacturer_name" />


<script>
    // Function to update the hidden input field with the manufacturer name
    function updateManufacturerName() {
        var manufacturerSelect = document.getElementById("select_manufacturer");
        var selectedOption = manufacturerSelect.options[manufacturerSelect.selectedIndex];
        var manufacturerName = selectedOption.getAttribute("data-name");
        
        // Update the hidden input field with the selected manufacturer name
        document.getElementById("manufacturer_name").value = manufacturerName;
    }
</script>


             
            </div>
            

     </div>
    <!--adding product -->
      <div class="row border-dark border-right border-left border-top border-bottom" id="box_loop_1">
         <div class="col-md-2 p-1 border-right border-left border-bottom">Raw Material </div>
          <div class="col-md-3 p-1 border-right border-left border-bottom">Item </div>
          <div class="col-md-1 p-1 border-right border-bottom"> D.No</div>
          <div class="col-md-1 p-1 border-right border-bottom" id="pricevalbox"> Size</div>
          <div class="col-md-1 p-1 border-right border-bottom" > Color</div>
          <div class="col-md-1 p-1 border-right border-bottom" >E. Qty</div>
           <div class="col-md-2 p-1 border-right border-bottom" >Barcode</div>
              

               <div class="col-md-2 p-1 border-right border-left border-bottom">
          <input type="number" name="itemno" id="itemno" data-count="1" hidden />
  <!-- <input class="form-control" list="product" name="product_raw_materail_choice" id="product_raw_materail_choice" placeholder="Product or Barcode" /> -->
 <select class="form-control" name="product_with_batch" id="product_with_batch">
  <option value="">Select Raw Material or type Barcode</option>
  <?php
  $purchase_id = isset($_GET['id']) ? $_GET['id'] : '';
//echo $purchase_id;
  $sql = "SELECT DISTINCT  im.id AS product_id, im.name AS product_name FROM pi_invoice_items pii JOIN inventory_master im ON pii.raw_mat_id = im.id WHERE pii.invoice_id = '$purchase_id' AND im.inventory_type = 'Raw Material'";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          echo '<optgroup label="' . htmlspecialchars($row["product_name"]) . '">';

          // Fetch batches
          $product_id = $row["product_id"];
          $batch_sql = "SELECT batch_no FROM product_batches WHERE product_id = '$product_id'";
          $batch_result = $conn->query($batch_sql);

          if ($batch_result && $batch_result->num_rows > 0) {
              while ($batch = $batch_result->fetch_assoc()) {
                  echo '<option value="' . htmlspecialchars($product_id . '|' . $batch["batch_no"]) . '">' . htmlspecialchars($batch["batch_no"]) . '</option>';
              }
          } else {
             // echo '<option value="' . htmlspecialchars($product_id) . '">No Batch</option>';
            echo '<option value="' . htmlspecialchars($product_id . '|') . '">' . htmlspecialchars($row["product_name"] . ' (No Batch)') . '</option>';

          }

          echo '</optgroup>';
      }
  }
  ?>
</select>

 
  <!-- <input type="hidden" name="batch_no[]" id="hidden_batch_no"> -->
  <!-- <input type="text" name="productid" id="productid" hidden /> -->

  </div>

          <div class="col-md-3 p-1 border-right border-left border-bottom">
          <input type="number" name="itemno" id="itemno" data-count="1" hidden />
  <input class="form-control" list="product" name="product_choice" id="product_choice" placeholder="Product or Barcode" autocomplete="off" />
  <datalist name="product" id="product">
    <option value="">Select Items</option>
    <?php
    $sql = "SELECT * FROM inventory_master WHERE inventory_type ='Sales Catalog' OR can_be_sold = '1' ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <option value="<?php echo $row["name"] ?>" data-productid="<?php echo $row["id"] ?>"></option>
    <?php
      }
    }
    ?>
  </datalist>

  <select id="batchSelect" name="batch_select" class="form-control" style="display: none;">
    <option value="">Select Batch</option>
  </select>
  <input type="hidden" name="pr_batch_no[]" id="hidden_batch_no">
  <input type="text" name="productid" id="productid" hidden />

  <textarea name="prod_desc" id="prod_desc" rows="1" class="form-control" placeholder="Product description"></textarea>
</div>

              
             <div class="col-md-1 p-1 border-right border-bottom">
                 <!-- <label for="qty">Quantity</label> -->
                 <input class="form-control" type="text" min="1" name="dno" id="dno" value="">
              </div>
              <div class="col-md-1 p-1 border-right border-bottom">
                 <!-- <label for="qty">Quantity</label> -->
                 <input class="form-control" type="text" min="1" name="size" id="size" value="1">
              </div>
          
              <div class="col-md-1 p-1 border-right border-bottom" id="colorvalbox">
                 <!-- <label for="price">Price</label> -->
                <input type="text" class="form-control" name="color" id="color" value="" >
              </div>
              <div class="col-md-1 p-1 border-right border-bottom" >
                 <!-- <label for="discount">Discount</label> -->
                 
                <input type="number" class="form-control" name="eqty" id="eqty" value="" min="0">
              </div>
               <div class="col-md-2 p-1 border-right border-bottom" >
                 <!-- <label for="gst">GST</label> -->
                
                   <input type="text" class="form-control" name="barcodeno" id="barcodeno" value="" hidden>
                     <img id="barcodeImageDisplay" src="" alt="Barcode" style="width: 30%; height: auto; display: none;">

                   <input type="text" class="form-control" name="barcodeimage" id="barcodeimage" value="" hidden>
                
              </div>
          

              <div class="col-md-1 p-1 border-right border-bottom">
                <button type="button" class="btn btn-success btn-sm" name="Addmore" id="addmore" onclick="add_more()">Add</button>
              </div>
            </div>                    
             <div class="row border border-dark">
                <table class="table table-bordered" id="item-list">
                  <colgroup>
                    <col width="18%">
                    <col width="34%">
                    <col width="10%">
                    <col width="14%">
                    <col width="12%">
                    <col width="14%">
                  </colgroup>
                <thead>
    <tr>
        <th>Raw fabric</th>
        <th>Product Name</th>
        <th>DNO</th>
        <th>Size</th>
        <th>Color</th>
        <th>E.Qty</th>
        <th>Barcode</th>
        <th>Action</th>
    </tr>
</thead>

                  <tbody>
                  </tbody>

                       
                </table>
        
            </div>
             <div class="row">
                <div class="col-md-6 border-left border-right border-bottom border-dark p-1">
                    <textarea class="form-control" placeholder="Note" name="note" id="note" cols="20" style="width: -webkit-fill-available;height: 103px;"></textarea>
                </div>
                <div class="col-md-6 border-right border-bottom border-dark p-3">
                   
                </div>
              
            </div>

          
          
             <div class="row">
                <div class="col-md-6 border-left border-right border-bottom border-dark p-3">
                    <!-- <label for="upload_pinvoice">Upload Signature</label> -->
                    <!-- <input class="form-control" type="file" name="upload_pinvoice" id="upload_pinvoice" value=""> -->
                </div>
                <div class="col-md-6 border-right border-bottom border-dark p-3">
                  <div >
                    <h6>For </h6><br/>
                    <h6>Authorized Signatory</h6>
                  </div>
                </div>
            </div>
                <div class="row col-md-12 text-center pt-3">
                    <div class="col-md-2">
                      <!-- <input type="submit" class="btn btn-primary " name="submit" value="Submit" /> -->
                     <input type="submit" class="btn btn-primary "  id="submitInvoiceBtn" name="submit" value="Submit" />
                    </div>
                     <div class="col-md-2"><input type="reset" class="btn btn-danger " name="cancel" value="Cancel" /></div>
                </div>
                       
                      </form>
                     </div>
                  </div>
                </div>
              </div>
               </div>
  </section>


<script>
function validateForm() {
    // Example: Validate if a field with id 'fieldID' is not empty
    const field = document.getElementById('fieldID').value.trim();

    if (!field) {
        alert("Please fill in all required fields before submitting.");
        return false; // Prevent form submission
    }

    // Add validation for other fields as needed

    return true; // Allow form submission if all fields are valid
}
</script>


<!-- Edit Item Modal -->
<style type="text/css">
  .modal-lg {
  max-width: 80%;
}

.form-label {
  font-weight: bold;
}

.form-control, .form-select {
  font-size: 14px;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
}

.modal-title {
  font-size: 18px;
}

.modal-body {
  padding: 20px;
}

#updateItemBtn {
  margin-top: 20px;
}

.bordered-input {
  border: 1px solid #ced4da;
  padding: 10px;
  margin-bottom: 10px;
  box-sizing: border-box;
}

</style>


  <!-- Required Js -->
  <script src="assets/js/vendor-all.min.js"></script>
  <script src="assets/js/plugins/bootstrap.min.js"></script>
  <script src="assets/js/pcoded.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Include Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
  $('#product_with_batch').select2({
    placeholder: "Select or type product/barcode",
    allowClear: true
  });

  $('#product_with_batch').on('select2:select', function (e) {
    let selectedVal = e.params.data.id;
    console.log("Selected value:", selectedVal);

    // Split product ID and batch no
    let parts = selectedVal.split('|');
    let productId = parts[0];
    let batchNo = parts[1] || '';

    // You can now fill hidden fields or make AJAX call to fetch product details
    console.log("Product ID:", productId);
    console.log("Batch No:", batchNo);
  });
});
</script>


<script type="text/javascript">

document.querySelector('input[type="reset"]').addEventListener('click', function() {
    document.getElementById('submitInvoiceBtn').disabled = false;
    document.getElementById('submitInvoiceBtn').value = 'Submit';
    document.getElementById('loader-overlay').style.display = 'none';
});

</script>

  <script type="text/javascript">

    /*function add_more() {
    const raw_material_value = $('#product_with_batch').val(); // e.g., "1|B123"
    const raw_material_text = $('#product_with_batch option:selected').closest('optgroup').attr('label'); // main raw material name
    const batch_text = $('#product_with_batch option:selected').text(); // batch no text

    const product = $('#product_choice').val();
    const productid = $('#productid').val();
    const prod_desc = $('#prod_desc').val();
    const dno = $('#dno').val();
    const size = $('#size').val();
    const color = $('#color').val();
    const eqty = parseFloat($('#eqty').val()) || 0;
    const barcodeimage = ($('#barcodeimage').val()) || 0;
    console.log("barcode image path",barcodeimage);
     const barcodeno = $('#barcodeno').val() || 0;
 const pr_batch_id = parseFloat($('#batchSelect').val()) || 0;
  const pr_batch_no = $('#batchSelect option:selected').text();
    const batch_parts = raw_material_value ? raw_material_value.split('|') : [];
    const batchid = batch_parts.length > 1 ? batch_parts[1] : 'No Batch';

 if ( eqty <= 0) {
        alert("Please fill in all required fields (E. Qty).");
        return;
    }

    // Check if the product already exists
    let productExists = false;
    $('#item-list tbody tr').each(function () {
        const existingProduct = $(this).find('td:nth-child(2)').text();
        if (existingProduct === product) {
            productExists = true;
        }
    });

    if (productExists) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'This product has already been added.',
            confirmButtonText: 'Ok'
        });
        return;
    }

    const itemno = $('#item-list tbody tr').length + 1;

    const rowHtml = `
        <tr>
            <td>${raw_material_text} (${batchid})</td>
            <td>${product} (${pr_batch_no})</td>
            <td>${dno}</td>
            <td>${size}</td>
            <td>${color}</td>
            <td>${eqty}</td>
            <td><img src="${barcodeimage}" width="25%"/></td>
            <td>
                <button type="button" class="btn btn-sm" onclick="confirm_remove_item(this)">
                    <i class="fa fa-trash" style="color:red;"></i>
                </button>
                <button type="button" class="btn btn-sm btn-edit" onclick="editItem(this)">
                    <i class="fa fa-edit" style="color:blue;"></i>
                </button>
            </td>
            <input type="hidden" name="product_raw_mat[]" value="${raw_material_text}">
            <input type="hidden" name="product_name[]" value="${product}">
            <input type="hidden" name="product_id[]" value="${productid}">
            <input type="hidden" name="prod_desc[]" value="${prod_desc}">
            <input type="hidden" name="dno[]" value="${dno}">
            <input type="hidden" name="size[]" value="${size}">
            <input type="hidden" name="color[]" value="${color}">
            <input type="hidden" name="eqty[]" value="${eqty}">
            <input type="hidden" name="raw_materail_batchno[]" value="${batchid}">
       <input type="hidden" name="product_batchno[]" value="${pr_batch_id}">
      <input type="hidden" name="product_barcodeno[]" value="${barcodeno}">
        <input type="hidden" name="product_barcodeimage[]" value="${barcodeimage}">

        </tr>
    `;

    $('#item-list tbody').append(rowHtml);

    // Clear Input Fields
    $('#prod_desc').val('');
    $('#product_choice').val('');
    $('#dno').val('');
    $('#size').val('');
    $('#color').val('');
    $('#eqty').val('');
    $('#barcodeno').val('');
    $('#product_with_batch').val('');
     $('#batchSelect').val('');
}*/
    function add_more() {
    const raw_material_value = $('#product_with_batch').val(); // e.g., "1|B123"
    const raw_material_text = $('#product_with_batch option:selected').closest('optgroup').attr('label'); // main raw material name
    const batch_text = $('#product_with_batch option:selected').text(); // batch no text

    const product = $('#product_choice').val();
    const productid = $('#productid').val();
    const prod_desc = $('#prod_desc').val();
    const dno = $('#dno').val();
    const size = $('#size').val();
    const color = $('#color').val();
    const eqty = parseFloat($('#eqty').val()) || 0;
    const barcodeimage = ($('#barcodeimage').val()) || 0;
    const barcodeno = $('#barcodeno').val() || 0;
    const pr_batch_id = parseFloat($('#batchSelect').val()) || 0;
    const pr_batch_no = $('#batchSelect option:selected').text();
    const batch_parts = raw_material_value ? raw_material_value.split('|') : [];
    const batchid = batch_parts.length > 1 ? batch_parts[1] : 'No Batch';

    if (eqty <= 0) {
        alert("Please fill in all required fields (E. Qty).");
        return;
    }

    // 1. Check if the product + batch already exists in the table
    let rowFound = null;
    $('#item-list tbody tr').each(function () {
        const existingProduct = $(this).find('input[name="product_name[]"]').val();
        const existingBatch = $(this).find('input[name="raw_materail_batchno[]"]').val();
        if (existingProduct === product && existingBatch === batchid) {
            rowFound = $(this);
            return false; // break out of .each()
        }
    });

    if (rowFound) {
        // 2. If found, update the quantity and recalculate
        let oldQty = parseFloat(rowFound.find('input[name="eqty[]"]').val()) || 0;
        let newQty = oldQty + eqty;
        rowFound.find('input[name="eqty[]"]').val(newQty);
        rowFound.find('td').eq(5).text(newQty); // update quantity column (6th column, zero-indexed)
        // Update any calculations/amount columns here if you have them
        // Optionally, update barcode, desc, etc. if you want last added to override
        // Clear input fields
        $('#prod_desc').val('');
        $('#product_choice').val('');
        $('#dno').val('');
        $('#size').val('');
        $('#color').val('');
        $('#eqty').val('');
        $('#barcodeno').val('');
        $('#product_with_batch').val('').trigger('change');
        $('#batchSelect').html('<option value="">Select Batch</option>').hide();
        return;
    }

    // 3. If not found, add a new row as usual
    const itemno = $('#item-list tbody tr').length + 1;
    const rowHtml = `
        <tr>
            <td>${raw_material_text} (${batchid})</td>
            <td>${product} (${pr_batch_no})</td>
            <td>${dno}</td>
            <td>${size}</td>
            <td>${color}</td>
            <td>${eqty}</td>
            <td><img src="${barcodeimage}" width="25%"/></td>
            <td>
                <button type="button" class="btn btn-sm" onclick="confirm_remove_item(this)">
                    <i class="fa fa-trash" style="color:red;"></i>
                </button>
                <button type="button" class="btn btn-sm btn-edit" onclick="editItem(this)">
                    <i class="fa fa-edit" style="color:blue;"></i>
                </button>
            </td>
            <input type="hidden" name="product_raw_mat[]" value="${raw_material_text}">
            <input type="hidden" name="product_name[]" value="${product}">
            <input type="hidden" name="product_id[]" value="${productid}">
            <input type="hidden" name="prod_desc[]" value="${prod_desc}">
            <input type="hidden" name="dno[]" value="${dno}">
            <input type="hidden" name="size[]" value="${size}">
            <input type="hidden" name="color[]" value="${color}">
            <input type="hidden" name="eqty[]" value="${eqty}">
            <input type="hidden" name="raw_materail_batchno[]" value="${batchid}">
            <input type="hidden" name="product_batchno[]" value="${pr_batch_id}">
            <input type="hidden" name="product_barcodeno[]" value="${barcodeno}">
            <input type="hidden" name="product_barcodeimage[]" value="${barcodeimage}">
        </tr>
    `;
    $('#item-list tbody').append(rowHtml);

    // Clear Input Fields
    $('#prod_desc').val('');
    $('#product_choice').val('');
    $('#dno').val('');
    $('#size').val('');
    $('#color').val('');
    $('#eqty').val('');
    $('#barcodeno').val('');
    $('#product_with_batch').val('').trigger('change');
    $('#batchSelect').html('<option value="">Select Batch</option>').hide();
}



// Function to confirm removal of an item from the table
function confirm_remove_item(button) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            remove_item(button);
        }
    });
}

// Function to remove the selected row
function remove_item(button) {
    $(button).closest('tr').remove(); // Remove the selected row
    calculate_totals(); // Recalculate totals (if needed)
}

// Optional: function to edit item in the table
function editItem(button) {
    const row = $(button).closest('tr');
    // You can pre-fill the form with data from the row to edit
}

function editItem(button) {
    const row = $(button).closest('tr');

    // Save reference to row
    $('#editItemModal').data('currentRow', row);

    const product = row.find('td').eq(0).text();
    const prod_desc = row.find('td').eq(1).text();
    const qty = row.find('td').eq(2).text();
    const price = row.find('td').eq(3).text();
    const discountText = row.find('td').eq(4).text();
    const total = row.find('td').eq(10).text();

    // Parse discount (remove % if any)
    const discount = discountText.replace('%', '') || 0;
const cessText = row.find('td').eq(9).text(); // e.g. "12.34 (2%)" or "-"
let cessValue = 0;

if (cessText && cessText !== '-') {
  // Extract just the numeric cess amount
  cessValue = parseFloat(cessText.split(' ')[0]) || 0;
}

    $('#cess').val(cessValue);
    // Set modal values
    $('#itemNameSpan').text(product);
    $('#item').val(product);
    $('#quantity').val(qty);
    $('#rate').val(price);
    $('#taxable').val(price);  // You may want to calculate taxable amount here instead of just price
    $('#amount_before_tax').val(price);  // Adjust if needed
    $('#edit_total').val(total);
    $('#discount').val(discount);

    // Show modal
    $('#editItemModal').modal('show');

    // Attach input listeners for total recalculation
    $('#quantity, #rate, #discount').off('input').on('input', calculateTotal);
}



$('#updateItemBtn').click(function() {
    const row = $('#editItemModal').data('currentRow');

    if (!row) {
        alert('No item selected for update!');
        return;
    }

    const qty = parseFloat($('#quantity').val()) || 0;
    const price = parseFloat($('#rate').val()) || 0;
    const discount = parseFloat($('#discount').val()) || 0;
    const total = parseFloat($('#edit_total').val()) || 0;
    const cess = parseFloat($('#cess').val()) || 0;
    // Update displayed columns
    row.find('td').eq(2).text(qty); // Quantity
    row.find('td').eq(3).text(price.toFixed(2)); // Price
    row.find('td').eq(4).text(discount + '%'); // Discount
    row.find('td').eq(10).text(total.toFixed(2)); // Total
    row.find('td').eq(9).text(cess > 0 ? cess.toFixed(2) + " %" : '-');

    // Update hidden inputs by matching their ids
    const itemno = row.data('item-id'); // get item number or id stored in <tr> data attribute
    if(itemno){
    $(`#cessrate_${itemno}`).val(cess);
    // If you want to update cess amount hidden field as well, calculate it:
    const qty = parseFloat($('#quantity').val()) || 0;
    const rate = parseFloat($('#rate').val()) || 0;
    const discount = parseFloat($('#discount').val()) || 0;
    let subtotal = qty * rate;
    if (discount > 0) {
        subtotal = subtotal - (subtotal * discount / 100);
    }
    let cessAmount = subtotal * (cess / 100);
    $(`#cessamount_${itemno}`).val(cessAmount.toFixed(2));
}

    // Close modal
    $('#editItemModal').modal('hide');

    // Recalculate totals in invoice
    calculate_totals();
} );


  function toggleSection(sectionId, header) {
        const content = document.getElementById(sectionId);
        const icon = header.querySelector('.rotate-icon');
        if (content.style.display === "none" || content.style.display === "") {
            content.style.display = "block";  
            icon.classList.add('active');
        } else {
            content.style.display = "none";
            icon.classList.remove('active');
        }
    }


// Function to remove an item
function remove_item(button) {
    $(button).closest('tr').remove(); // Remove the selected row
    calculate_totals(); // Recalculate totals
}

</script>


<script type="text/javascript">
  function confirm_remove_item(button) {
     event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            remove_item(button);
        }
    });
}

</script>


  <script type="text/javascript">
    function remove(box_count) {
      jQuery("#box_loop_" + box_count).remove();
      var box_count = jQuery("#box_count").val();
      box_count--;
      jQuery("#box_count").val(box_count);

    }
  </script>


  <script type="text/javascript">

    $(document).ready(function() {
$("#product_choice").change(function() {
  var customer_s_state = $('#customer_s_state').val();
  if (!customer_s_state) {
    alert("Please select customer details before selecting a product.");
    $("#customer_name_choice").focus();
    $("#product_choice").val('');
    return;
  }

  var productname = $(this).val();
  var dataListOptions = document.getElementById('product').querySelectorAll('option');
  var matched = false;
  var productId = null;

  for (var i = 0; i < dataListOptions.length; i++) {
    if (dataListOptions[i].value === productname) {
      matched = true;
      productId = dataListOptions[i].getAttribute('data-productid');
      break;
    }
  }

  if (matched) {
    $("#productid").val(productId);
    $("#batchSelect").html('<option value="">Select Batch</option>').hide();
    $.ajax({
      url: 'get_wo_product_batches.php',
      method: 'GET',
      data: { productId: productId },
      success: function(response) {
        console.log("get wo products batches",response);
        var batches = JSON.parse(response);
        if (batches.length > 0) {
          $("#batchSelect").show();
          batches.forEach(function(batch) {
            var expDate = new Date(batch.exp_date);
            var formattedDate = expDate.getDate().toString().padStart(2, '0') + '-' + (expDate.getMonth() + 1).toString().padStart(2, '0') + '-' + expDate.getFullYear();
            $("#batchSelect").append('<option value="' + batch.id + '">' + batch.batch_no  + '</option>');
          });
        } else {
          fetchwoWithoutBatch(productname, productId);
        }
      }
    });
  } else {

    $.ajax({
    url: 'get_work_order_by_barcode.php',
    method: 'GET',
    data: { barcode: productname },
    success: function(response) {
      console.log("barcode,"+ response);
        var data = JSON.parse(response);
        if (data.status === "success") {
            $("#product_choice").val(data.product_name);
            $("#productid").val(data.product_id);

            if (data.is_batch_barcode) {
    // Clear and show batch dropdown with only this batch
    $("#batchSelect").html('<option value="">Select Batch</option>');
    
    var expDate = new Date(data.exp_date);
    var formattedDate = expDate.getDate().toString().padStart(2, '0') + '-' + (expDate.getMonth() + 1).toString().padStart(2, '0') + '-' + expDate.getFullYear();
    
    $("#batchSelect").append('<option value="' + data.pbid + '" selected>' + data.batch_no + '</option>');
    
    $("#batchSelect").show(); // ✅ Show dropdown with single batch

    // Set batch number in hidden input
    $("#hidden_batch_no").val(data.batch_no);

    // Fill details
    $("#size").val(data.size);
    $("#color").val(data.color);
    $("#dno").val(data.dno);
    $('#barcodeno').val(data.barcode_no);
     $('#barcodeimage').val(data.barcode_image);
      var barcodeImagepath = data.barcode_image;

          document.getElementById('barcodeImageDisplay').src = data.barcode_image;
document.getElementById('barcodeImageDisplay').style.display = 'block'; // Display the barcode image

  
}


            else if (data.maintain_batch == 1) {
                // Load batches as usual
                $("#batchSelect").html('<option value="">Select Batch</option>').hide();

                $.ajax({
                    url: 'get_wo_product_batches.php',
                    method: 'GET',
                    data: { productId: data.product_id },
                    success: function(batchResponse) {
                      console.log("get wo product batches",batchResponse);
                        var batches = JSON.parse(batchResponse);
                        if (batches.length > 0) {
                            $("#batchSelect").show();
                            batches.forEach(function(batch) {
                                var expDate = new Date(batch.exp_date);
                                var formattedDate = expDate.getDate().toString().padStart(2, '0') + '-' + (expDate.getMonth() + 1).toString().padStart(2, '0') + '-' + expDate.getFullYear();
                                $("#batchSelect").append('<option value="' + batch.id + '">' + batch.batch_no + '</option>');
                            });
                        }
                    }
                });
            } else {
                // No batch maintained, fill directly
                $("#size").val(data.size);
                $("#color").val(data.color);
                $("#dno").val(data.dno);
                 $("#barcodeno").val(data.barcode_no);
                $("#barcodeimage").val(data.barcode_image);
                 var barcodeImagepath = data.barcode_image;

          document.getElementById('barcodeImageDisplay').src = data.barcode_image;
document.getElementById('barcodeImageDisplay').style.display = 'block'; // Display the barcode image

                $("#batchSelect").hide();
            }
        } else {
            alert(data.message);
            $("#product_choice").val('');
        }
    }
});

    
  }
});

$("#batchSelect").change(function() {
  var productId = $("#productid").val();
  var batchNo = $(this).val();
  if (!batchNo) {
    alert("Please select a batch.");
    return;
  }

  $.ajax({
    url: 'get_WO_batch_data.php',
    method: 'GET',
    data: { productId: productId, batchNo: batchNo },
    success: function(response) {
      console.log("get wo batch data",response);
      var jsonData = JSON.parse(response);
      $("#dno").val(jsonData.dno);
      $("#size").val(jsonData.size);
      $("#color").val(jsonData.color);
        $("#barcodeno").val(jsonData.barcode_no);
          $("#barcodeimage").val(jsonData.barcode_image);
           var barcodeImagepath = jsonData.barcode_image;

          document.getElementById('barcodeImageDisplay').src = jsonData.barcode_image;
document.getElementById('barcodeImageDisplay').style.display = 'block'; // Display the barcode image


    
    }
  });
});

function fetchwoWithoutBatch(productname, productId) {
  $.ajax({
    url: 'getworkorder_without_batch.php',
    method: 'GET',
    data: { productname: productname, productid: productId },
    success: function(data) {
      console.log("get workorder without batch",data);
      var jsonData = JSON.parse(data);
      $("#dno").val(jsonData.dno);
      $("#size").val(jsonData.size);
      $("#color").val(jsonData.color);
        $("#barcodeno").val(jsonData.barcode_no);
          $("#barcodeimage").val(jsonData.barcode_image);
          var barcodeImagepath = jsonData.barcode_image;

          document.getElementById('barcodeImageDisplay').src = jsonData.barcode_image; // Set the source to the fetched image path
document.getElementById('barcodeImageDisplay').style.display = 'block'; // Display the barcode image

     
    }
  });
}

});


  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#prv").click(function() {
        alert("preview_quotation_pdf");
        //var productname = $(this).find(":selected").val();
        var dataString = 'productname=' + productname;
        //alert(cat_type);  
        $.ajax({
          url: 'preview_quotation_pdf.php',
          Type: "GET",
          //data:{"cat_id" : cat_id, "cat_type":cat_type}
          data: dataString,
          //cache: false,
          success: function(data) {

            $("#prvid").html(data);
            //}     
          }
        });
      })
    });
  </script>
<script type="text/javascript">



function addCharge() {
    const select = document.getElementById("additional_charges");
    const selectedOption = select.options[select.selectedIndex];

    if (selectedOption.value) {
        const chargeName = selectedOption.text;
        const chargeValue = parseFloat(selectedOption.getAttribute("data-charge")) || 0;

        // Check for duplicate charges
        const existingCharge = document.getElementById("charge_" + selectedOption.value);
        if (existingCharge) {
            console.log("Duplicate charge detected:", existingCharge.id);
            alert("This charge has already been added.");
            return;
        }

        const chargesList = document.querySelector("#additional-charges-container .additional-charges-list");
        const row = document.createElement("div");
        row.id = "charge_" + selectedOption.value;
        row.className = "additional-charge-row";

        // Hidden input fields
        const hiddenTypeInput = document.createElement("input");
        hiddenTypeInput.type = "hidden";
        hiddenTypeInput.name = "additionalCharges[charge_type][]";
        hiddenTypeInput.value = chargeName;

        const hiddenValueInput = document.createElement("input");
        hiddenValueInput.type = "hidden";
        hiddenValueInput.name = "additionalCharges[charge_price][]";
        hiddenValueInput.value = chargeValue;

        // Row content
        row.innerHTML = `
            <div class="row align-items-center">
                <div class="col-5 text-right">
                    <span class="charge-name">${chargeName}</span>
                </div>
                <div class="col-2">
                    <button type="button" onclick="removeCharge('${row.id}')" class="btn btn-link text-danger">Remove</button>
                </div>
                <div class="col-5">
                    <input type="number" class="form-control charge-input text-right" value="${chargeValue}" 
                    oninput="syncChargeValue(this, '${row.id}')" style="width: 100%;">
                </div>
            </div>
        `;

        // Append and sync
        chargesList.appendChild(row);
        row.appendChild(hiddenTypeInput);
        row.appendChild(hiddenValueInput);

        // Reset dropdown selection
        select.value = "";

        // Calculate totals
        setTimeout(calculate_totals, 0);
    }
}

function syncChargeValue(input, rowId) {
    const row = document.getElementById(rowId);
    if (row) {
        const hiddenInput = row.querySelector('input[name="additionalCharges[charge_price][]"]');
        if (hiddenInput) {
            hiddenInput.value = parseFloat(input.value) || 0; // Update hidden input value
        }
    }

    // Recalculate totals after syncing
    calculate_totals();
}



    function editCharge(rowId) {
        var row = document.getElementById(rowId);
        var input = row.querySelector(".charge-input");
        var editButton = row.querySelector("button");

     // Toggle between Edit and Save
if (input.readOnly) {
    input.readOnly = false;
    input.style.width = "150%"; // Increase width of input for easier editing
    input.style.height = "30px"; // Decrease height of the input
    editButton.innerText = "Save";
    editButton.onclick = function() {
        saveCharge(rowId);
    };
}
    }
    function saveCharge(rowId) {
        var row = document.getElementById(rowId);
        var input = row.querySelector(".charge-input");
        var editButton = row.querySelector("button");

        // Save the edited value and revert button to Edit
        input.readOnly = true;
        editButton.innerText = "Edit";
        editButton.onclick = function() {
            editCharge(rowId);
        };

        // Update total after editing
        calculateTotal();
    }

    function removeCharge(rowId) {
        var row = document.getElementById(rowId);
        row.parentNode.removeChild(row);

        // Update total
        calculateTotal();
    }

</script>


<script>

  </script>
</body>

</html>