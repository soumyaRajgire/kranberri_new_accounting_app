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
     <!--  <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
          <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">Create Quotation</h4>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Quotation</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        <!--</ul>
                    </div>
          </div>
        </div>
      </div> -->
      <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">Create Quotation</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Create quotation</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <!-- [ stiped-table ] start -->
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
             <h4 class="m-b-10">Create Quotation</h4>
            </div>

  <div class="card-body table-border-style">
    <div class="table-responsive">
      <div class="row">
        <div class="col-sm-12">
          <div class="">
            <div class="card-body">
              <form id="" action="save_quotation.php" method="POST">

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
                     <!-- <div class="col-md-8 border-right border-dark" >
                        <h6 style="font-size: 13px;" class="pt-2">KRIKA MKB CORPORATION PRIVATE LIMITED </h6>
                          <span style="color:skyblue;">120 Newport Center Dr, Newport Beach, CA 92660</span><br/>
                       <span  style="color:skyblue;"> Email: abhijith.mavatoor@gmail.com</span><br/>
<span style="color:skyblue;">Phone: 9481024700</span><br/>
<span style="color:skyblue;">GSTIN: 29AAICK7493G1ZX</span>
                        </div> -->
                    <div class="col-md-4 pt-1">
                        <div class="py-1 input-group">
                          <?php
                          $result1=mysqli_query($conn,"select id from quotation where id=(select max(id) from quotation)");
  if($row1=mysqli_fetch_array($result1))
  {
    $id=$row1['id']+1;
    $i=$row1['id'];
    $s=preg_replace("/[^0-9]/", '', $i);
    $invoice_code="QUO0".($s+1);
 }
 else{
  $id = 0;
  $invoice_code = "QUO0".(1);
 }
                          ?>
  <input class="form-control" type="text" id="purchaseNo" value="<?php echo $invoice_code; ?>" name="purchaseNo" readonly />
                <label class="form-control col-sm-5" for="purchaseNo">Estimate No</label>
                
                
                        </div>
                        <div class="py-1 input-group">
    <input class="form-control" type="date" id="purchaseDate" name="purchaseDate" required />
    <label class="form-control col-sm-5" for="purchaseDate">Estimate/ Date</label>
</div>
<div class="py-1 input-group">
    <input class="form-control" type="date" id="dueDate" name="dueDate" required>
    <label class="form-control col-sm-5" for="dueDate">Validity Date</label>
</div>

                    </div>
                </div>

<script>
    // Get the current date
    const currentDate = new Date();
    const formattedCurrentDate = currentDate.toISOString().split('T')[0];

    // Calculate the due date (1 month from today)
    const dueDate = new Date();
    dueDate.setMonth(dueDate.getMonth() + 1);

    // Handle edge cases for months with fewer days
    if (dueDate.getDate() !== currentDate.getDate()) {
        dueDate.setDate(0); // Set to the last day of the previous month
    }

    const formattedDueDate = dueDate.toISOString().split('T')[0];

    // Set the values of the date inputs
    document.getElementById('purchaseDate').value = formattedCurrentDate;
    document.getElementById('dueDate').value = formattedDueDate;
</script>
<div class="row" id="customer_data"></div>


            <div class="row" id="customer_dp">
               <div class="col-md-4 border-left border-bottom border-dark p-3">
                <div>
                   
                   <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addCustomersModal" style="margin-top: -10px; height: 25px; font-size: 12px;"><i class="fa fa-plus"></i> <b>New</b></button>
                              <h6>Customer info</h6>
                                <div class="form-group" >
                                 <input class="form-control" list="customer_name" name="customer_name_choice" id="customer_name_choice"  autocomplete="off" />
                               
   
                
                
                                    <script>
                                        // JavaScript function to clear the input field when the "Edit" button is clicked
                                        function clearInput() {
                                         $("#customer_dp").show();
                                                document.getElementById("customer_name_choice").value = '';
                                    
                                                $("#customer_data").hide();
                                        }
                                    </script>

                                  <!--<input class="form-control" list="customer_name" name="customer_name_choice" id="customer_name_choice" onchange="checknamevalue(this.value)" autocomplete="off" />-->
                                        <datalist name="customer_name" id="customer_name" placeholder="Select Customer" >
                                          <!-- <option value="Others"> -->
                                            <?php
                                            $sql = "select * from customer_master where contact_type = 'Customer' ";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                              while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                          <option value="<?php echo $row["customerName"]." | ".$row["mobile"]?>" data-customerid="<?php echo $row["id"]?>">
                                        <?php
                                              }
                                            }
                                            else
                                            {?>
                                                 <option value="No Match Found" disable>
                                      <?php
                                            }
                                        ?>
                                         
                                        </datalist><br />
                                        
                                        <input type="hidden" name="cst_mstr_id" id="cst_mstr_id"  value="">
                                        <!-- <input class="form-control" type="text" name="othercustomername" id="othercustomername" placeholder="Name" style="display: none;">  -->
                                </div>

                </div>
              </div>
              <div class="col-md-4 border-left border-bottom border-dark p-3">
                <div>
                  <!-- <h6>Billing Address</h6> -->
                </div>
              </div>

              <div class="col-md-4 border-left border-bottom border-right border-dark p-3">
                <!-- <h6>Shipping Address</h6> -->
              </div>
            </div>
                
    <!--adding product -->
      <div class="row border-dark border-right border-left border-top border-bottom" id="box_loop_1">
          <div class="col-md-3 p-1 border-right border-left border-bottom">ITem
              <button type="button" class="btn btn-sm dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 11px; font-weight: 900; color: blue;"><i class="fa fa-plus"></i> New Item</button>

              <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" data-value="products">Products</a>
                  <a class="dropdown-item" href="#" data-value="services">Services</a>
              </div>

          </div>
          <div class="col-md-2 p-1 border-right border-bottom">
                 <!-- <label for="qty">Quantity</label> -->
                 Quantity
              </div>
          
              <div class="col-md-2 p-1 border-right border-bottom" id="pricevalbox">
                 <!-- <label for="price">Price</label> -->
               Price
              </div>
              <div class="col-md-2 p-1 border-right border-bottom" >
               Discount
              </div>
               <div class="col-md-2 p-1 border-right border-bottom" >
                 <!-- <label for="gst">GST</label> -->
                GST
              </div>
               <!--<div class="col-md-2 p-1 border-right border-bottom" >
                  <label for="gst">GST</label> 
                Total
              </div>-->

          <div class="col-md-3 p-1 border-right border-left border-bottom">
            
         
              <input type="number" name="itemno" id="itemno" select-group="" data-count=1 hidden />
                    <!-- <input class="form-control" list="product" name="product_choice" id="product_choice" onchange="checkvalue(this.value)" placeholder="Product" /> -->
                    <input class="form-control" list="product" name="product_choice" id="product_choice" placeholder="Product" onchange="fetchValidBatches()" />
                            <datalist name="product" id="product">
                              <option value="">Select Items </option>
                              <!-- <option value="Others"> -->
                                <?php
                                $sql = "select * from inventory_master  where  inventory_type ='Sales Catalog' OR can_be_sold = '1'";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                  while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                              <!-- <option value="<?php echo $row["name"] ?>"> -->
                  <option value="<?php echo $row["name"]?>" data-productid="<?php echo $row["id"]?>">
                            <?php
                                  }
                                }
                            ?>

                            </datalist>
                            <!-- Initially, batch select dropdown is hidden -->
<select id="batchSelect" name="batch_select" class="form-control" style="display: none;">
    <option value="">Select Batch</option>
</select>
<input type="hidden" name="batch_no[]" id="hidden_batch_no">
<script>
 function fetchValidBatches() {
    const product = document.getElementById('product_choice').value;
    
    // Find the product ID based on the selected product name
    const productId = document.querySelector(`#product option[value="${product}"]`)?.getAttribute('data-productid');
    
    if (!productId) {
        alert("Please select a valid product.");
        return;
    }

    console.log('Selected Product ID:', productId); // Debugging output

    // Call AJAX to fetch valid batches
    $.ajax({
        url: 'get_product_batches.php',
        method: 'GET',
        data: { productId: productId },
        success: function(response) {
            console.log('Response from server:', response); // Debugging output
            
            const batches = JSON.parse(response);
            if (batches.length > 0) {
                // Show the batch select dropdown
                const batchSelect = document.getElementById('batchSelect');
                batchSelect.style.display = 'block'; // Make it visible
                
                // Clear previous batch options
                batchSelect.innerHTML = '<option value="">Select Batch</option>'; // Reset options
                
                // Populate the batches dropdown with valid batches
                batches.forEach(function(batch) {
                    // Format the expiry date to dd-mm-yyyy
                    const expDate = new Date(batch.exp_date);
                    const formattedDate = (expDate.getDate()).toString().padStart(2, '0') + '-' + 
                                          (expDate.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                                          expDate.getFullYear();
                                          
                    const option = document.createElement('option');
                    option.value = batch.batch_no;
                    option.textContent = batch.batch_no + ' | ' + formattedDate;
                    batchSelect.appendChild(option);
                });
            } 
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Failed to fetch batches.');
        }
    });
}
document.getElementById('batchSelect').addEventListener('change', function () {
    const selectedBatch = this.value;
    
    if (!selectedBatch) {
        alert('Please select a batch.');
        return;
    }

    // Set hidden input value
    document.getElementById('hidden_batch_no').value = selectedBatch;

    // Fetch batch data
    getBatchDataByBatchNo(selectedBatch);
});

function getBatchDataByBatchNo(batchNo) {
    // This function will fetch the batch data from your database based on the selected batch number
    $.ajax({
        url: 'get_batch_data.php',
        method: 'GET',
        data: { batchNo: batchNo },
        success: function(response) {
            const batchDetails = JSON.parse(response);
            
            if (batchDetails && batchDetails.length > 0) {
                const batchData = batchDetails[0];  // Assuming the response is an array and we get the first result

                // Fill the price and other batch-related details into the fields
                document.getElementById('price').value = batchData.batch_price;
                document.getElementById('gst').value = batchData.batch_gst_rate;
                document.getElementById('netprice').value = batchData.batch_net_price;
                document.getElementById('hsn_code').value = batchData.hsn_code;
                document.getElementById('units').value = batchData.units;

                // You can also add any other batch-specific data here, such as stock or expiry date
            } else {
                alert('No data found for the selected batch.');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Failed to fetch batch data.');
        }
    });
}

</script>
                            <input type="text" name="productid" id="productid" value="" hidden/>
                            <textarea name="prod_desc" id="prod_desc" rows="1" class="form-control" cols="20" placeholder="Product description"></textarea>
                 </div>
            
             
              <div class="col-md-2 p-1 border-right border-bottom">
                 <!-- <label for="qty">Quantity</label> -->
                 <input class="form-control" type="number" min="1" name="qty" id="qty" value="1">
              </div>
          
              <div class="col-md-2 p-1 border-right border-bottom" id="pricevalbox">
                 <!-- <label for="price">Price</label> -->
                <input type="number" class="form-control" name="price" id="price" value="" >
              </div>
              <div class="col-md-2 p-1 border-right border-bottom" >
                 <!-- <label for="discount">Discount</label> -->
                 
                <input type="number" class="form-control" name="discount" id="discount" value="" min="0">
              </div>
               <div class="col-md-2 p-1 border-right border-bottom" >
                 <!-- <label for="gst">GST</label> -->
                
                   <input type="number" min="0" class="form-control" name="gst" id="gst" value="">
                
              </div>
              <!-- <div class="col-md-2 p-1 border-right border-bottom" id="pricevalbox"> -->
                <input type="text" class="form-control" name="netprice" id="netprice" value="" hidden >
               <input type="text" class="form-control" name="ttprice" id="ttprice" value="" hidden>
               <input type="text" class="form-control" name="cess_rate" id="cess_rate" value="" hidden>
               <input type="text" class="form-control" name="cess_amount" id="cess_amount" value="" hidden>
                <!-- <input type="text" name="gst" id="gst" value="" hidden> -->
                <input type="text" name="in_ex_gst" id="in_ex_gst" value="" hidden>
              <!-- </div> -->
           

              <div class="col-md-1 p-1 border-right border-bottom">
                <button type="button" class="btn btn-success btn-sm" name="Addmore" id="addmore" onclick="add_more()">Add</button>
              </div>
            </div>                    
             <div class="row border border-dark">
                <table class="table table-bordered" id="item-list">
                  <colgroup>
                    <col width="18%">
                    <col width="35%">
                    <col width="10%">
                    <col width="14%">
                    <col width="10%">
                    <col width="18%">
                  </colgroup>
                <thead>
    <tr>
        <th>Product</th>
        <th>Product Desc</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Discount</th>
        <th>GST</th>
        <th>CGST</th>
        <th>SGST</th>
        <th>IGST</th>
        <th>Cess</th>
        <th>Total</th>
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
                <div class="col-md-6 border-right border-bottom border-dark p-1">

                
                  <table style="width:100%;">
                 <tr>       
                     <td class="" id="taxable_amt_text" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Taxable Amount</td>
                     <td style="text-align:right;" id="final_taxable_amt"> </td>
                   
                </tr> 
          
                <tr>
                    <td class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Total GST</td>
                    <td style="text-align:right;" id="final_gst_amount"> </td>
                     
                </tr>

                <tr>
                    <td class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Total Cess</td>
                    <td style="text-align:right;" id="final_cess_amount"></td>
                    
                </tr>
             
                 <tr id="additional-charges-container" >
                    <td class="" colspan=2 style="padding: 0px 2px !important;" >
                        <div class="additional-charges-list">
                            <!-- Additional charges will be appended here-->
                        </div>
                    </td> 
                </tr>
                <tr>
                    <td class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;" >Select Additional Charges</td>
                    <td>
                        <select class="form-control" id="additional_charges" style="margin-left:3px;width:97%;height:33px;" onchange="addCharge();">
                            <option value="">Select Additional Charges</option>
                            <!-- <option value="freight charge">Freight Charge</option> -->
                            <option value="insurance charge">Insurance Charge</option>
                            <option value="loading charge">Loading Charge</option>
                            <option value="packing charge">Packing Charge</option>
                            <option value="other charge">Other Charge</option>
                            <option value="other taxes">Other Taxes</option>
                            <option value="reimbursements">Reimbursements</option>
                            <option value="excise duties">Excise Duties</option>
                            <option value="miscellaneous">Miscellaneous</option>
                        </select>
                    </td>
                </tr>
                 <tr>
                      <th class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;" >Grand Total</th>
                     <th class="text-right">
                <span id="gtotal">0.00</span>
                 <input type="hidden" name="final_cess_amount" id="final_cess_amount_field" value="">
                   <input type="hidden" name="final_taxable_amt" id="final_taxable_amt_field" value="" >
                  <input type="hidden" name="final_gst_amount" id="final_gst_amount_field" value="">
                <input type="hidden" name="total_amount" id="total_amount" value="">
            </th>
                    </tr>
               
            </table>
               
                </div>
            </div>

            

            <div class="row">
                <div class="col-md-6 border-left border-right border-bottom border-dark p-3">
                    <textarea class="form-control" placeholder="Terms and Condition" name="terms_condition" id="terms_condition" cols="20" style="width: -webkit-fill-available;height: 112px;"></textarea>
                </div>
                <div class="col-md-6 border-right border-bottom border-dark p-3">
                  <div >
                    <h6>For KRIKA MKB CORPORATION PRIVATE LIMITED</h6><br/>
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
    document.addEventListener('DOMContentLoaded', (event) => {
        const purchaseDate = document.getElementById('invoice_date');
        const dueDate = document.getElementById('dueDate');

        purchaseDate.addEventListener('change', function() {
            if (purchaseDate.value) {
                dueDate.min = purchaseDate.value;
            } else {
                dueDate.removeAttribute('min');
            }
        });

        dueDate.addEventListener('change', function() {
            if (dueDate.value && dueDate.value <= purchaseDate.value) {
                alert("Validity Date must be greater than Purchase Date");
                dueDate.value = "";
            }
        });
    });
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
<!-- Edit Item Modal -->
<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editItemModalLabel">Edit Item Details</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>             
      </div>
      <div class="modal-body">
        <form id="editForm">
          <div class="row">
             <span id="itemNameSpan" class="form-label mb-1"></span>
             <input type="text" class="form-control bordered-input" id="item" name="item" hidden>
            <table class="table table-bordered">
              <tr>
                <th>Quantity</th><th>Rate</th><th>Taxable</th><th>Amount Before Tax</th><th>Total</th>
              </tr>
              <tr>
                <td>  <input type="number" class="form-control bordered-input" id="quantity" name="quantity" ></td>
                <td> <input type="number" class="form-control bordered-input" id="rate" name="rate" ></td>
                <td> <input type="number" class="form-control bordered-input" id="taxable" name="taxable" ></td>
                <td> <input type="number" class="form-control bordered-input" id="amount_before_tax" name="amount_before_tax"></td>
                <td rowspan="2">  <input type="number" class="form-control bordered-input" id="edit_total" name="edit_total" readonly></td>
              </tr>
              <tr>
              
                <td>
                  <div class="">
              <label for="discount" class="form-label">Discount</label>
              <input type="number" class="form-control bordered-input" id="discount" name="discount">
            </div></td>

              </tr>
            </table>
         
          </div>
          <button type="button" class="btn btn-primary" id="updateItemBtn">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>



<?php include("customersModal.php");?>

<!-- Adding Services Module-->
                  
           <?php include("servicesModalPopup.php");?>
<!-- End Services Modal-->

<!-- Products Modal -->

<?php include("productsModalPopUp.php");?>
<!-- End of Products Modal-->

  <!-- Required Js -->
  <script src="assets/js/vendor-all.min.js"></script>
  <script src="assets/js/plugins/bootstrap.min.js"></script>
  <script src="assets/js/pcoded.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->



  <script type="text/javascript">
let count = 1;
let itemno = 1;
 let tot_taxable = 0; // Initialize the total taxable amount

// Calculate the taxable amount for the current product
let taxableAmount = 0;
    let cess_total =0;
    let tol_gst = 0;

// Function to add more items
// function add_more() {
//     const prod_desc = $('#prod_desc').val();
//     const product = $('#product_choice').val();
//     const productid = $('#productid').val();
//     const qty = $('#qty').val();
//     const price = $('#price').val();
//     let ttprice = $('#ttprice').val(); // Price including GST
//     const gst = $('#gst').val(); // GST rate
//     const netprice = $('#netprice').val(); // Base price (exclusive GST)
//     const in_ex_gst = $('#in_ex_gst').val(); // GST type: inclusive or exclusive
//     const discount = parseFloat($('#discount').val()) || 0;  // Discount (default to 0 if empty)
//  const  cess_rate = $('#cess_rate').val();
//  const cess_amount = $('#cess_amount').val();
//     let total = 0;
//     let cgst = 0;
//     let sgst = 0;
//     let igst = 0;
 

//     // Get customer billing and shipping states
//     const customer_s_state = $('#customer_s_state').val();
//     const business_state = $('#business_state').val();

//     // Determine if the transaction is intrastate or interstate
//     let gst_type;
//     if (customer_s_state === business_state) {
//         gst_type = "CGST/SGST";
//         $('#cgst-th').show();
//         $('#sgst-th').show();
//         $('#igst-th').hide();
//     } else {
//         gst_type = "IGST";
//         $('#igst-th').show();
//         $('#cgst-th').hide();
//         $('#sgst-th').hide();
//     }

// if (cess_rate > 0 || cess_amount > 0) {
//      $('#cess-th').show();
//    }
//     // Add GST and discount columns if not already added
//     if ($('#item-list thead th').length === 9) {  // Default columns
//         if (gst_type === "CGST/SGST") {
//             $('#item-list thead tr').append('<th>CGST</th><th>SGST</th>');
//         } else if (gst_type === "IGST") {
//             $('#item-list thead tr').append('<th>IGST</th>');
//         }
        
//     $('#item-list thead tr').append('<th>Cess</th>');      
 
//         // Add discount column after price column dynamically
//         $('#item-list thead tr').append('<th>Discount</th>');
//     }

//     let gstAmount = 0;
// let discountedPrice = parseFloat(ttprice); 
// let discountedBasePrice = discountedPrice;  

// // Apply discount on the base price (exclusive of GST)
// if (discount > 0) {
//     discountedBasePrice = discountedBasePrice - (discountedBasePrice * discount) / 100;  
// }

// // Calculate based on GST type (inclusive or exclusive)
// if (in_ex_gst === "inclusive of GST") {
//      let basePrice = discountedBasePrice / (1 + parseFloat(gst) / 100);  
//     gstAmount = discountedBasePrice - basePrice;  
//     taxableAmount = basePrice * parseFloat(qty);
//     total = (discountedPrice * parseFloat(qty));  
// } else if (in_ex_gst === "exclusive of GST") {
//     gstAmount = (discountedBasePrice * parseFloat(gst)) / 100; 
//     taxableAmount = discountedBasePrice * parseFloat(qty); 
//     total = (discountedBasePrice + gstAmount) * parseFloat(qty);  
// }


//   if (gst_type === "CGST/SGST") {
//         cgst = (gstAmount / 2).toFixed(2); // CGST is half of the GST amount
//         sgst = (gstAmount / 2).toFixed(2); // SGST is half of the GST amount
//         tol_gst = parseFloat(tol_gst) + parseFloat(cgst) + parseFloat(sgst);
       
//     } else if (gst_type === "IGST") {
//         igst = gstAmount.toFixed(2); // IGST is the full GST amount
//         tol_gst = tol_gst +igst ;
//     }



// cess_total = cess_total + cess_amount;
// //tot_taxable = tot_taxable + netprice;
// tot_taxable += taxableAmount;

// // Update the footer or a field to display the total taxable amount
//    document.getElementById('final_taxable_amt').innerText = parseFloat(tot_taxable).toFixed(2);
//    document.getElementById('final_cess_amount').innerText = parseFloat(cess_total).toFixed(2);
//    document.getElementById('final_gst_amount').innerText =  parseFloat(tol_gst).toFixed(2);

//     // Generate the HTML for the item row
//     itemno = $('#item-list tbody tr').length + 1;

//     const html = `
//         <tr data-item-id="${itemno}">
//             <td>${product}</td>
//             <td>${prod_desc}</td>
//             <td>${qty}</td>
//             <td>${price}</td>
//             <td>${discount > 0 ? discount + "%" : "0%"}</td> <!-- Discount column -->
//             <td>${gst}</td>
//             ${gst_type === "CGST/SGST" ? `
//                 <td>${cgst}</td>
//                 <td>${sgst}</td>
//             ` : `
//                 <td>${igst}</td>
//             `}
//              ${cess_rate > 0 || cess_amount > 0 ? `<td>${parseFloat(cess_amount).toFixed(2)} (${cess_rate}%)</td>` : ""}

//             <td>${total.toFixed(2)}</td>
//             <td class="cus_padding">
//                 <textarea name="proddesc[]" id="proddesc${itemno}" hidden>${prod_desc}</textarea>
//                 <input type="number" name="itemnum[]" id="itemnumval${itemno}" value="${itemno}" hidden/>
//                 <input type="number" name="gstval[]" id="gstval${itemno}" value="${gst}" hidden/>
//                 <input type="number" name="netpriceval[]" id="netpriceval${itemno}" value="${netprice}" hidden/>
//                 <input type="text" name="in_ex_gst_val[]" id="in_ex_gst_val${itemno}" value="${in_ex_gst}" hidden/>
//                 <input type="number" name="cgstval[]" id="cgstval${itemno}" value="${cgst}" hidden/>
//                 <input type="number" name="sgstval[]" id="sgstval${itemno}" value="${sgst}" hidden/>
//                 <input type="number" name="igstval[]" id="igstval${itemno}" value="${igst}" hidden/>
//                 <input name="products[]" id="productsval${itemno}" value="${product}" hidden/>
//                 <input name="productids[]" id="productidsval${itemno}" value="${productid}" hidden/>
//                 <input type="number" name="qtyvalue[]" id="qtyvalueval${itemno}" value="${qty}" hidden/>
//                 <input type="number" name="priceval[]" id="priceval${itemno}" value="${price}" hidden/>
//                  <input type="text" class="form-control" name="cessrateval[]" id="cessrate${itemno}" value="${cess_rate}" hidden>
//                <input type="text" class="form-control" name="cessamountval[]" id="cessamount${itemno}" value="${cess_amount}" hidden>
//                 <input type="hidden" name="total[]" id="total${itemno}" value="${total.toFixed(2)}" />
//                 <button class="btn btn-sm" type="button" onclick="rem_item(this)">
//                     <i class="fa fa-trash" style="color:red;"></i>
//                 </button>
//             </td>
//             <td class="cus_padding">
//                 <button class="btn btn-sm btn-edit" type="button" onclick="editItem(this)">
//                     <i class="fa fa-edit" style="color:blue;"></i>
//                 </button>
//             </td>
//         </tr>
//     `;

//     // Append the new item row to the table
//     $('#item-list tbody').append(html);
//        $('#prod_desc').val('');
//     $('#product_choice').val('');
//     $('#qty').val(1);

//     $('#price').val('');
//     calc_total();


//      //updateFooter(); // Call this function whenever the table is updated

// }

// Function to add more items to the table
function add_more() {
    const prod_desc = $('#prod_desc').val();
    const product = $('#product_choice').val();
    const productid = $('#productid').val();
    const qty = parseFloat($('#qty').val()) || 0;
    const price = parseFloat($('#price').val()) || 0; // Price
      const netprice = parseFloat($('#netprice').val()) || 0; // Price
    const gst = parseFloat($('#gst').val()) || 0; // GST rate
    const discount = parseFloat($('#discount').val()) || 0; // Discount %
    const cess_rate = parseFloat($('#cess_rate').val()) || 0; // Cess rate %
    const cess_amount = parseFloat($('#cess_amount').val()) || 0; // Cess amount (from hidden input)
    const in_ex_gst = $('#in_ex_gst').val(); // GST type (inclusive or exclusive)

    const customer_s_state = $('#customer_s_state').val(); // Customer State
    const business_state = $('#business_state').val(); // Business State

    if (!product || qty <= 0 || price <= 0) {
        alert("Please fill in all required fields (Product, Quantity, Price).");
        return;
    }

    let basePrice = 0;
    let taxableAmount = 0;
    let gstAmount = 0;
    let cgst = 0, sgst = 0, igst = 0;
    let totalAmount = 0;

    // Calculate taxable amount and GST based on inclusive/exclusive GST
    if (in_ex_gst === "inclusive of GST") {
        // basePrice = price / (1 + gst / 100); // Extract base price
        taxableAmount = netprice * qty;
        gstAmount = taxableAmount * (gst / 100); // GST amount
    } else if (in_ex_gst === "exclusive of GST") {
        taxableAmount = price * qty; // Price is already exclusive of GST
        gstAmount = taxableAmount * (gst / 100); // GST amount
    }
console.log("from add more taxable amount"+taxableAmount);
console.log("gst Amount before discount"+gstAmount);
    // Apply Discount
    const discountedTaxableAmount = taxableAmount - (taxableAmount * discount) / 100;

    // Recalculate GST based on discounted taxable amount
    gstAmount = discountedTaxableAmount * (gst / 100);
console.log("gst Amount after discount"+gstAmount);
    // Determine CGST, SGST, IGST based on state
    if (customer_s_state === business_state) {
        // Intrastate: Split GST equally into CGST and SGST
        cgst = (gstAmount / 2);
        sgst = gstAmount / 2;
    } else {
        // Interstate: Entire GST is treated as IGST
        igst = gstAmount;
    }
    

    // Use the retrieved cess amount
    // const finalCessAmount = cess_amount * qty;
const finalCessAmount = discountedTaxableAmount * (cess_rate / 100); 

    // Calculate Total Amount
    totalAmount = discountedTaxableAmount + gstAmount + finalCessAmount;

    // Generate Table Row with Hidden Inputs
    const itemno = $('#item-list tbody tr').length + 1;

    const rowHtml = `
        <tr>
            <td>${product}</td>
            <td>${prod_desc}</td>
            <td>${qty}</td>
            <td>${price.toFixed(2)}</td>
            <td>${discount}%</td>
            <td>${gst}%</td>
            <td>${cgst > 0 ? cgst.toFixed(2) : '-'}</td>
            <td>${sgst > 0 ? sgst.toFixed(2) : '-'}</td>
            <td>${igst > 0 ? igst.toFixed(2) : '-'}</td>
            <td>${finalCessAmount > 0 ? finalCessAmount.toFixed(2) + ' (' + cess_rate + '%)' : '-'}</td>
            <td>${totalAmount.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="confirm_remove_item(this)">Remove</button>
            </td>
            <input type="hidden" id="proddesc_${itemno}" name="proddesc[]" value="${prod_desc}">
            <input type="hidden" id="product_${itemno}" name="products[]" value="${product}">
            <input type="hidden" id="productid_${itemno}" name="productids[]" value="${productid}">
            <input type="hidden" id="qty_${itemno}" name="qtyvalue[]" value="${qty}">
            <input type="hidden" id="price_${itemno}" name="priceval[]" value="${price}">
            <input type="hidden" id="gst_${itemno}" name="gstval[]" value="${gst}">
            <input type="hidden" id="gstamount_${itemno}" name="gstamountval[]" value="${gstAmount.toFixed(2)}">
            <input type="hidden" id="cgst_${itemno}" name="cgstval[]" value="${cgst.toFixed(2)}">
            <input type="hidden" id="sgst_${itemno}" name="sgstval[]" value="${sgst.toFixed(2)}">
            <input type="hidden" id="igst_${itemno}" name="igstval[]" value="${igst.toFixed(2)}">
            <input type="hidden" id="discount_${itemno}" name="discountval[]" value="${discount}">
            <input type="hidden" id="cessrate_${itemno}" name="cessrateval[]" value="${cess_rate}">
            <input type="hidden" id="cessamount_${itemno}" name="cessamountval[]" value="${finalCessAmount.toFixed(2)}">
            <input type="hidden" id="total_${itemno}" name="totalval[]" value="${totalAmount.toFixed(2)}">
            <input type="hidden" id="in_ex_gst_${itemno}" name="in_ex_gst_val[]" value="${in_ex_gst}">
        </tr>
    `;

    // Append Row to Table
    $('#item-list tbody').append(rowHtml);

    // Clear Input Fields
    $('#prod_desc').val('');
    $('#product_choice').val('');
    $('#qty').val(1);
    $('#price').val('');
    $('#discount').val('');
    $('#gst').val('');
    $('#cess_rate').val('');
    $('#cess_amount').val('');

    // Recalculate Totals
    calculate_totals();
}




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

    function showTransportDetails(mode) {
        const transportData = document.getElementById('transportData');
        if (mode === "None") {
            transportData.style.display = "none";
        } else {
            transportData.style.display = "block";
        }
    }

    function toggleOptionalFields() {
        const optionalFields = document.getElementById('optionalFields');
        optionalFields.style.display = optionalFields.style.display === 'none' ? 'block' : 'none';
    }

    // Initialize by hiding transport data
    showTransportDetails('None');
    function showTransportDetails(mode) {
    // Hide all transport data sections
    const transportSections = document.querySelectorAll('.transport-mode-data');
    transportSections.forEach(section => section.classList.add('d-none'));

    // Show the selected transport mode section
    const selectedSection = document.getElementById(mode.toLowerCase() + 'Data');
    if (selectedSection) {
        selectedSection.classList.remove('d-none');
    }
}
document.getElementById('toggleButton').addEventListener('click', function () {
        const optionalFields = document.getElementById('optionalFields');
        const icon = this.querySelector('i');

        optionalFields.classList.toggle('d-none');
        icon.classList.toggle('fa-plus');
        icon.classList.toggle('fa-minus');
    });
function updateFooter() {
  // Get all table rows in tbody
  let rows = document.querySelectorAll('#item-list tbody tr');
  
  // Calculate the total
  let total = 0;
  rows.forEach(row => {
    let price = parseFloat(row.querySelector('.price').innerText); // Assuming you have a price class
    let quantity = parseInt(row.querySelector('.quantity').innerText); // Assuming you have a quantity class
    total += price * quantity;
  });

  // Update the total price in footer
  document.getElementById('totalPrice').innerText = total.toFixed(2);

  // Dynamically update colspan in footer
  let columnCount = document.querySelectorAll('#item-list thead th').length;
  document.getElementById('totalPrice').setAttribute('colspan', columnCount - 1); // Minus 1 for 'Total' column
}



// Function to calculate total
// function calc_total() {
//     let total = 0;
//     const pack_price = parseFloat($('#pack_price').val()) || 0;

//     $('#item-list tbody tr').each(function() {
//         const rowTotal = parseFloat($(this).find('[name="total[]"]').val()) || 0;
//         total += rowTotal;
//     });

//     $('[name="sub_total"]').val(total.toFixed(2));
//     $('#sub_total').text(total.toFixed(2).toLocaleString('en-US'));

//     const pack_total = pack_price + total;
//     const gt_round = Math.round(pack_total);
//     $('[name="total_amount"]').val(gt_round.toFixed(2));
//     $('#gtotal').text(gt_round.toFixed(2).toLocaleString('en-US'));
// }

// Function to remove an item
// function rem_item(button) {
//     $(button).closest('tr').remove();
//     calc_total();
// }

// function editItem(button) {
//     console.log('Edit button clicked');
//     const row = $(button).closest('tr'); // Wrap with jQuery
//     console.log('Row:', row);
//     const itemId = row.data('item-id');  // Correctly use jQuery data method
//     console.log('Item ID:', itemId);

//     const item = row.find('td').eq(0).text();
//     const prod_desc = row.find('td').eq(1).text();
//     const price = row.find('td').eq(2).text();
//     const qty = row.find('td').eq(3).text();
//     const total = row.find('td').eq(4).text();

//     $('#item').val(item);
//     $('#quantity').val(qty);
//     $('#rate').val(price);
//     $('#taxable').val(price);
//     $('#amount_before_tax').val(price);
//     $('#edit_total').val(total);
//     $('#discount').val(0);
//     $('#itemNameSpan').text(item);

//     $('#editItemModal').data('item-id', itemId);

//     $('#editItemModal').modal('show');

//     // Attach event listeners for input changes
//     $('#quantity').off('input').on('input', calculateTotal);
//     $('#rate').off('input').on('input', calculateTotal);
//     $('#discount').off('input').on('input', calculateTotal);
// }



function editItem(button) {
    console.log('Edit button clicked');
    const row = $(button).closest('tr'); // Wrap with jQuery
    console.log('Row:', row);

    // Fetch the item ID from the row data
    const itemId = row.data('item-id');  
    console.log('Item ID:', itemId);

    // Fetch data from the table row
    const item = row.find('td').eq(0).text();  // Product Name (or Description) in the first column
    const prod_desc = row.find('td').eq(1).text(); // Product Description
   
    const qty = parseInt(row.find('td').eq(3).text()); // Quantity (numeric value)
     const price = parseFloat(row.find('td').eq(4).text()); // Price (numeric value)
    const total = parseFloat(row.find('td').eq(8).text()); // Total value (numeric)

    console.log('Extracted Data: ', {
        item: item,
        prod_desc: prod_desc,
        price: price,
        qty: qty,
        total: total
    });

    // Check if discount is applied and fetch it (assuming the discount is in the 5th column, adjust if needed)
    const discount = parseFloat(row.find('td').eq(5).text()) || 0;
    console.log('Discount:', discount);

    // Set values in the modal form
    $('#item').val(item);
    $('#quantity').val(qty);
    $('#rate').val(price);
    $('#taxable').val(price);  // Assuming taxable = price, modify if required
    $('#amount_before_tax').val(price);  // Assuming amount before tax = price, modify if required
    $('#edit_total').val(total);
    $('#discount').val(discount);  // Set the discount value correctly here
    $('#itemNameSpan').text(item);  // Set the item name in the modal header

    // Store the item ID in modal data for later use
    $('#editItemModal').data('item-id', itemId);

    // Show the modal
    $('#editItemModal').modal('show');

    // Attach event listeners for input changes (to recalculate total)
    $('#quantity').off('input').on('input', calculateTotal);
    $('#rate').off('input').on('input', calculateTotal);
    $('#discount').off('input').on('input', calculateTotal);
}




// function calculateTotal() {
    // var qty = parseFloat($('#quantity').val()) || 0;
    // var price = parseFloat($('#rate').val()) || 0;
    // var discount = parseFloat($('#discount').val()) || 0;
    // var total = (qty * price) - discount;
    // $('#edit_total').val(total.toFixed(2));
// }


// function calculateTotal() {
//     // Retrieve the base total amount (discounted and GST-applied total)
//     const baseTotal = parseFloat(document.querySelector('input[name="total_amount"]').value) || 0;

//     let additionalChargesTotal = 0;

//     // Iterate over all additional charge inputs and sum up their values
//     document.querySelectorAll(".additional-charge-row .charge-input").forEach(input => {
//         additionalChargesTotal += parseFloat(input.value) || 0; // Add charge value or 0 if empty/invalid
//     });

//     // Calculate the final grand total
//     const grandTotal = baseTotal + additionalChargesTotal;

//     // Update the Grand Total field
//     document.getElementById("gtotal").innerText = grandTotal.toFixed(2);

//     // Update the hidden input for form submission
//     document.querySelector('input[name="total_amount"]').value = grandTotal.toFixed(2);
// }


// Update item details when "Update" button is clicked
$(document).ready(function() {
    $('#updateItemBtn').click(function() {
        const itemId = $('#editItemModal').data('item-id');
        const row = $(`tr[data-item-id="${itemId}"]`);

        if (!row.length) {
            console.error(`Row with item ID ${itemId} not found`);
            return;
        }

        const qty = $('#quantity').val();
        const price = $('#rate').val();
        const discount = $('#discount').val();
        const total = (qty * price) - discount;

        row.find('td').eq(2).text(price);
        row.find('td').eq(3).text(qty);
        row.find('td').eq(4).text(total.toFixed(2));

        // Update hidden inputs as well
        row.find(`input[name="qtyvalue[]"]`).val(qty);
        row.find(`input[name="priceval[]"]`).val(price);
        row.find(`input[name="total[]"]`).val(total.toFixed(2));

        calculate_totals();
          $('input[name="total[]"]').removeAttr('required');
        $('#editItemModal').modal('hide');

    });
    $('input[name="total[]"]').attr('type', 'hidden');
});

// Function to calculate subtotal and grand total
// function calc_total() {
//     let subtotal = 0; // Initialize subtotal

//     // Calculate subtotal from item list
//     $('#item-list tbody tr').each(function () {
//         const rowTotal = parseFloat($(this).find('input[name="total[]"]').val()) || 0;
//         subtotal += rowTotal;
//     });

//     console.log('Subtotal:', subtotal); // Debugging

//     // Update subtotal display
//     $('[name="sub_total"]').val(subtotal.toFixed(2));
//     $('#sub_total').text(subtotal.toFixed(2).toLocaleString('en-US'));

//     // Calculate additional charges total
//     let additionalChargesTotal = 0;
//     $('.additional-charge-row .charge-input').each(function () {
//         additionalChargesTotal += parseFloat($(this).val()) || 0; // Add charge value or 0 if empty
//     });

//     console.log('Additional Charges:', additionalChargesTotal); // Debugging

//     // Calculate the grand total
//     const grandTotal = Math.round(subtotal + additionalChargesTotal);

//     console.log('Grand Total:', grandTotal); // Debugging

//     // Update grand total display
//     $('[name="total_amount"]').val(grandTotal.toFixed(2));
//     $('#gtotal').text(grandTotal.toFixed(2).toLocaleString('en-US'));
// }

function calculate_totals() {
    let totalTaxable = 0;
    let totalCGST = 0;
    let totalSGST = 0;
    let totalIGST = 0;
    let totalCess = 0;
    let grandTotal = 0;

    // Iterate over table rows to calculate product totals
    $('#item-list tbody tr').each(function () {
        const row = $(this);

        // Read data from table cells
        let qty = parseFloat(row.find('td:nth-child(3)').text()) || 0; // Quantity
        let price = parseFloat(row.find('td:nth-child(4)').text()) || 0; // Price
        let discount = parseFloat(row.find('td:nth-child(5)').text()) || 0; // Discount %
        let gstRate = parseFloat(row.find('td:nth-child(6)').text()) || 0; // GST %
        let cessAmount = parseFloat(row.find('td:nth-child(10)').text()) || 0; // Cess Amount

        // Calculate taxable amount after discount
        const grossAmount = price * qty;
        const discountAmount = grossAmount * (discount / 100);
        const taxableAmount = grossAmount - discountAmount;

        // Calculate GST amount
        const gstAmount = taxableAmount * (gstRate / 100);

        // Split GST into CGST/SGST or assign as IGST
        let cgst = 0, sgst = 0, igst = 0;
        if ($('#customer_s_state').val() === $('#business_state').val()) {
            cgst = gstAmount / 2;
            sgst = gstAmount / 2;
        } else {
            igst = gstAmount;
        }

        // Calculate row total
        const rowTotal = taxableAmount + gstAmount + cessAmount;

        // Update totals
        totalTaxable += taxableAmount;
        totalCGST += cgst;
        totalSGST += sgst;
        totalIGST += igst;
        totalCess += cessAmount;
        grandTotal += rowTotal;

        // Update row cells if needed
        row.find('td:nth-child(7)').text(cgst.toFixed(2)); // CGST
        row.find('td:nth-child(8)').text(sgst.toFixed(2)); // SGST
        row.find('td:nth-child(9)').text(igst.toFixed(2)); // IGST
        row.find('td:nth-child(11)').text(rowTotal.toFixed(2)); // Total
    });

    // Add Freight Charges (Transportation Details)
    const freightCharges = parseFloat($('#roadFreightCharges').val() || 0) +
                           parseFloat($('#railFreightCharges').val() || 0) +
                           parseFloat($('#airFreightCharges').val() || 0) +
                           parseFloat($('#shipFreightCharges').val() || 0);
    grandTotal += freightCharges;

    // Add Other Charges (from additional charges and TCS)
    const additionalCharges = Array.from(document.querySelectorAll('.charge-input'))
        .reduce((acc, input) => acc + (parseFloat(input.value) || 0), 0);
    grandTotal += additionalCharges;

    const tcsTaxPercent = parseFloat($('#tcsTax').val() || 0);
    const tcsValue = totalTaxable * (tcsTaxPercent / 100);
    grandTotal += tcsValue;

    // Update footer fields
    $('#final_taxable_amt').text(totalTaxable.toFixed(2));
    $('#final_gst_amount').text((totalCGST + totalSGST + totalIGST).toFixed(2));
    $('#final_cess_amount').text(totalCess.toFixed(2));


     $('#final_taxable_amt_field').val(totalTaxable.toFixed(2));
      $('#final_gst_amount_field').val((totalCGST + totalSGST + totalIGST).toFixed(2));
       $('#final_cess_amount_field').val(totalCess.toFixed(2));
    
    $('#gtotal').text(grandTotal.toFixed(2));
$('#total_amount').val(grandTotal.toFixed(2));
    // Debugging (Optional)
    console.log(`Freight Charges: ${freightCharges}, Additional Charges: ${additionalCharges}, TCS: ${tcsValue}`);
}








// Function to remove an item
function remove_item(button) {
    $(button).closest('tr').remove(); // Remove the selected row
    calculate_totals(); // Recalculate totals
}

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

 <script>


  // Function to open the modal and populate it with data
 //  function editItem(button) {
 //    alert("Edit button clicked"); // Debugging alert
 //    var row = button.closest('tr');
 //    var item = row.cells[0].innerText;
 //    var prod_desc = row.cells[1].innerText;
 //    var price = row.cells[2].innerText;
 //    var qty = row.cells[3].innerText;
 //    var total = row.cells[4].innerText;

 //    // Populate modal fields
 //    document.getElementById('item').value = item;
 //    document.getElementById('quantity').value = qty;
 //    document.getElementById('rate').value = price;
 //    document.getElementById('taxable').value = price; // Adjust if needed
 //    document.getElementById('amount_before_tax').value = price; // Adjust if needed
 //    document.getElementById('total').value = total;
 //    document.getElementById('units').value = 'pcs'; // Adjust if needed
 //    document.getElementById('discount').value = 0; // Adjust if needed

 // document.getElementById('itemNameSpan').innerText = item;
 //    // Show the modal
 //    var editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
 //    editModal.show();
 //  }



  // document.addEventListener('DOMContentLoaded', (event) => {
  //   // Attach the edit function to all edit buttons
  //   document.querySelectorAll('.btn-edit').forEach(button => {
  //     button.addEventListener('click', function() {
  //       editItem(this);
  //     });
  //   });
  // document.getElementById('quantity').addEventListener('input', calculateTotal);
  //     document.getElementById('rate').addEventListener('input', calculateTotal);
  //     document.getElementById('discount').addEventListener('input', calculateTotal);

  //   // Handle update button click in the modal
  //   document.getElementById('updateItemBtn').addEventListener('click', function() {
  //     alert("Update button clicked"); // Debugging alert
  //     // Update the row with new values

  //     var item = document.getElementById('item').value;
  //     var qty = document.getElementById('quantity').value;
  //     var price = document.getElementById('rate').value;
  //     var total = document.getElementById('total').value;

  //     // Update the corresponding row in the table
  //     var row = document.querySelector(`tr[data-item-id="${itemId}"]`);
  //     row.cells[3].innerText = qty;
  //     row.cells[2].innerText = price;
  //     row.cells[4].innerText = total;

  //     // Close the modal
  //     var editModal = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
  //     editModal.hide();
  //   });
  // });
</script>

<script>
    // Handle dropdown item clicks
    $('.dropdown-item').click(function() {
        var selectedValue = $(this).data('value');
        $('#selectedOption').val(selectedValue);

         if(selectedValue === "products")
        {
            $("#addProductsModal").modal("show");
        } else if(selectedValue === "services"){
            $("#addServicesModal").modal("show");
            // $("#Div1").modal("show");
        }
    });
</script>

<script type="text/javascript">
    // Function to calculate Net Price and GST and display them in a single input field
function calculatePrices(modalId) {
    var price = parseFloat($(".modal-input.price-input[data-modal='" + modalId + "']").val()) || 0;
    var gstRate = parseFloat($(".modal-select.gst-rate-input[data-modal='" + modalId + "']").val()) || 0;
    var inclusiveGst = $(".modal-select.inclusive-gst-select[data-modal='" + modalId + "']").val();
    var nonTaxable = parseFloat($(".modal-input.non-taxable-input[data-modal='" + modalId + "']").val()) || 0;

    var netPriceField = $(".modal-input.net-price-input[data-modal='" + modalId + "']");

   if (inclusiveGst === "inclusive of GST" && price > 0) {
      var gstAmount = (price / (1 + gstRate / 100)) * (gstRate / 100);
      var netPrice = price - gstAmount - nonTaxable;
      netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
    } else if (inclusiveGst === "exclusive of GST" && price > 0) {
      var gstAmount = (price * gstRate) / 100;
      var netPrice = price - nonTaxable;
      netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
    } else {
      netPriceField.val("");
    }
}

// Attach event listeners to elements in both modals based on their classes and data attributes
$(".modal-input, .modal-select").on("input", function () {
    var modalId = $(this).data("modal");
    calculatePrices(modalId);
});

</script>

  <script type="text/javascript">
    // function checkvalue(val) {

      // if (val === "Others") {
        //alert("from  check val othesr");
        // if (document.getElementById('otherproduct').style.display === "none") {
          // document.getElementById('otherproduct').style.display = 'block';
        // }
      // } else {
        //    alert("from  check val different");
        // document.getElementById('otherproduct').style.display = "none";
      // }
    // }


    function checknamevalue(val) {
      
    }

    // function checkpriceval(val) {
    //   if (val === "Others") {
    //     if (document.getElementById('otherprice').style.display === "none") {
    //       document.getElementById('otherprice').style.display = 'block';
    //     }
    //   } else {
    //     document.getElementById('otherprice').style.display = "none";
    //   }
    // }
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
      $("#customer_name_choice").change(function() {

            // var customername = $(this).val();
            //var dataString = 'productname='+ productname ;   
            //alert(cat_type); 
             var selectedValue = $(this).val();
    
        // Split the selected value by |
        var values = selectedValue.split(" | ");
        
        // Check if the split produced the expected result
        if (values.length === 2) {
          var customername = values[0];
          var mobileNumber = values[1]; 
      }
      else{
    console.log("unexpected Format");
      }
      
         var dataListOptions = document.getElementById('customer_name').options;
    
        for (var i = 0; i < dataListOptions.length; i++) {
          if (dataListOptions[i].value === selectedValue) {
            // var customerId = dataListOptions[i].getAttribute('data-customerid');
            var customerId = dataListOptions[i].getAttribute('data-customerid');
    document.getElementById("cst_mstr_id").value = customerId;
            // alert('Selected Product ID: ' + productId);
            // You can use the productId as needed (e.g., submit it in a form)
            // console.log(customerId);
            break;
          }
        }
        
            $.ajax({
              url: 'get_customer_data.php',
              Type: "GET",
              //data:{"cat_id" : cat_id, "cat_type":cat_type}
              data: {
                "customername": customername,"mobileNumber":mobileNumber,"customerID":customerId
              },
              //cache: false,
              success: function(data) {
                $("#customer_dp").hide();
                 $("#customer_data").show();
                // console.log(data);
                $("#customer_data").html(data);
                // $("#pricevalbox").html(data);
                //}     
              }
            });
          })
    });
  </script>


  <script type="text/javascript">
    $(document).ready(function() {
      $("#product_choice").change(function() {

 var customer_s_state = $('#customer_s_state').val();
        var business_state = $('#business_state').val();
//alert(customer_s_state);

       
        var productname = $(this).val();
        //var dataString = 'productname='+ productname ;   
        //alert(cat_type);  
        var dataListOptions = document.getElementById('product').querySelectorAll('option');
    
    for (var i = 0; i < dataListOptions.length; i++) {
      if (dataListOptions[i].value === productname) {
        var productId = dataListOptions[i].getAttribute('data-productid');
        // alert('Selected Product ID: ' + productId);
        // You can use the productId as needed (e.g., submit it in a form)
        break;
      }
    }
    $("#productid").val(productId);
        $.ajax({
          url: 'getprice.php',
          Type: "GET",
          //data:{"cat_id" : cat_id, "cat_type":cat_type}
          data: {
            "productname": productname,"productid":productId
          },
          //cache: false,
          success: function(data) {
           console.log(data);
        
        var jsonData = JSON.parse(data);

        // Assign GST and price values to their respective input fields
        $("#gst").val(jsonData.gst);

        // If the price is inclusive of GST, calculate the base price and use it for price field
        if (jsonData.in_ex_gst === "inclusive of GST") {
          
            var gstRate = parseFloat(jsonData.gst);  // GST rate (in percentage)

         
            $("#price").val(jsonData.netprice);
            $("#netprice").val(jsonData.netprice);
            $("#ttprice").val(jsonData.price);
        } 
        // If the price is exclusive of GST, just use the price directly
        else if (jsonData.in_ex_gst === "exclusive of GST") {
            $("#price").val(jsonData.price); 
            $("#netprice").val(jsonData.netprice); 
        }

        // Store the GST type (inclusive or exclusive)
        $("#in_ex_gst").val(jsonData.in_ex_gst);
        $("#cess_rate").val(jsonData.cess_rate);
        $("#cess_amount").val(jsonData.cess_amt);
           
          }
        });
      })
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



// function updateChargeValue(rowId, value) {
//     const row = document.getElementById(rowId);
//     const hiddenInput = row.querySelector('input[name="additionalCharges[charge_price][]"]');

//     // Update the hidden input value
//     hiddenInput.value = value || 0;

//     // Immediately recalculate totals
//     calculate_totals();
// }



// function addCharge() {
//     const select = document.getElementById("additional_charges");
//     const selectedOption = select.options[select.selectedIndex];

//     if (selectedOption.value) {
//         const chargeName = selectedOption.text;
//         const chargeValue = parseFloat(selectedOption.getAttribute("data-charge")) || 0;

//         // Check if charge is already added
//         const existingCharge = document.getElementById("charge_" + selectedOption.value);
//         if (existingCharge) {
//             alert("This charge has already been added.");
//             return;
//         }

//         const chargesList = document.querySelector("#additional-charges-container .additional-charges-list");
//         const row = document.createElement("div");
//         row.id = "charge_" + selectedOption.value;
//         row.className = "additional-charge-row";

//         row.innerHTML = `
//         <div class="row align-items-center">
//             <div class="col-5 text-right">
//                 <span class="charge-name">${chargeName}</span>
//             </div>
//             <div class="col-2">
//                 <button type="button" onclick="removeCharge('${row.id}')" class="btn btn-link text-danger" style="padding:8px 11.5px;border-right:1px solid #ada7a7;">Remove</button>
//             </div>
//             <div class="col-5">
//                 <input type="number" class="form-control charge-input text-right" value="${chargeValue}" style="width: 100%;" 
//                 oninput="calculate_totals()"> <!-- Update total dynamically when input changes -->
//             </div>
//         </div>
//         `;

//         // Append the row to the charges list
//         chargesList.appendChild(row);

//         // Clear the dropdown selection
//         select.selectedIndex = 0;

//         // Recalculate the total after adding the new charge
//         calculate_totals();
//     }
// }




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
// function getInvoiceData() {
//     const tableRows = document.querySelectorAll("#item-list tbody tr");
//     const products = [];

//     // Extract Product Data
//     tableRows.forEach((row) => {
//         const cells = row.querySelectorAll("td");
//         const product = {
//             itemno: cells[0].innerText.trim(),
//             product_name: cells[1].innerText.trim(),
//             product_desc: cells[2].innerText.trim(),
//             quantity: parseFloat(cells[3].innerText.trim()) || 0,
//             price: parseFloat(cells[4].innerText.trim()) || 0,
//             discount: parseFloat(cells[5].innerText.trim()) || 0,
//             gst: parseFloat(cells[6].innerText.trim()) || 0,
//             gst_amount: parseFloat(cells[7].innerText.trim()) || 0,
//             total: parseFloat(cells[8].innerText.trim()) || 0,
//             cgst: parseFloat(cells[9].innerText.trim()) || 0,
//             sgst: parseFloat(cells[10].innerText.trim()) || 0,
//             cess_rate: cells[11]?.innerText.trim() || "",
//             cess_amount: parseFloat(cells[12]?.innerText.trim()) || 0,
//             in_ex_gst: cells[13]?.innerText.trim(),
//         };
//         products.push(product);
//     });

//     // Extract Additional Charges
//     const additionalCharges = [];
//     document.querySelectorAll(".additional-charge-row").forEach((chargeRow) => {
//         const chargeType = chargeRow.querySelector(".charge-name").innerText.trim();
//         const chargeValue = parseFloat(
//             chargeRow.querySelector(".charge-input").value.trim()
//         ) || 0;
//         additionalCharges.push({ charge_type: chargeType, charge_price: chargeValue });
//     });

//     // Extract Transportation Details
//     const selectedMode = document.querySelector('input[name="transportMode"]:checked')?.value || "None";
//     let transportDetails = { mode: selectedMode };

//     if (selectedMode === "Road") {
//         transportDetails = {
//             ...transportDetails,
//             vehicle_number: document.querySelector("#roadVehicleNumber")?.value || "",
//             driver_name: document.querySelector("#driverName")?.value || "",
//             license_number: document.querySelector("#licenseNumber")?.value || "",
//             freight_charges: parseFloat(document.querySelector("#roadFreightCharges")?.value) || 0,
//             insurance_details: document.querySelector("#roadInsurance")?.value || "",
//             permit_number: document.querySelector("#roadPermit")?.value || "",
//             driver_contact: document.querySelector("#roadContact")?.value || "",
//             distance: parseFloat(document.querySelector("#roadDistance")?.value) || 0,
//         };
//     } else if (selectedMode === "Rail") {
//         transportDetails = {
//             ...transportDetails,
//             train_number: document.querySelector("#trainNumber")?.value || "",
//             departure_station: document.querySelector("#railwayStation")?.value || "",
//             arrival_station: document.querySelector("#arrivalStation")?.value || "",
//             booking_reference: document.querySelector("#railwayBooking")?.value || "",
//             freight_charges: parseFloat(document.querySelector("#railFreightCharges")?.value) || 0,
//             coach_number: document.querySelector("#railwayCoach")?.value || "",
//             seat_number: document.querySelector("#railwaySeat")?.value || "",
//             departure_time: document.querySelector("#railDepartureTime")?.value || "",
//         };
//     } else if (selectedMode === "Air") {
//         transportDetails = {
//             ...transportDetails,
//             flight_number: document.querySelector("#flightNumber")?.value || "",
//             departure_airport: document.querySelector("#departureAirport")?.value || "",
//             arrival_airport: document.querySelector("#arrivalAirport")?.value || "",
//             airway_bill: document.querySelector("#airwayBill")?.value || "",
//             freight_charges: parseFloat(document.querySelector("#airFreightCharges")?.value) || 0,
//             cargo_type: document.querySelector("#airCargoType")?.value || "",
//             airline_name: document.querySelector("#airlineName")?.value || "",
//             estimated_arrival: document.querySelector("#airETA")?.value || "",
//         };
//     } else if (selectedMode === "Ship") {
//         transportDetails = {
//             ...transportDetails,
//             vessel_name: document.querySelector("#shipVesselName")?.value || "",
//             voyage_number: document.querySelector("#shipVoyageNumber")?.value || "",
//             container_number: document.querySelector("#shipContainerNumber")?.value || "",
//             bill_of_lading: document.querySelector("#shipBillOfLading")?.value || "",
//             port_of_loading: document.querySelector("#shipPortOfLoading")?.value || "",
//             port_of_discharge: document.querySelector("#shipPortOfDischarge")?.value || "",
//             freight_charges: parseFloat(document.querySelector("#shipFreightCharges")?.value) || 0,
//             estimated_arrival: document.querySelector("#shipEstimatedArrival")?.value || "",
//         };
//     }

//     // Extract Other Details
//     const otherDetails = {
//         po_number: document.querySelector("#poNumber")?.value || "",
//         po_date: document.querySelector("#poDate")?.value || "",
//         challan_number: document.querySelector("#challanNumber")?.value || "",
//         due_date: document.querySelector("#dueDate")?.value || "",
//         ewaybill_number: document.querySelector("#ewayBill")?.value || "",
//         sales_person: document.querySelector("#salesPerson")?.value || "",
//         reverse_charge: document.querySelector("#reverseCharge")?.checked ? 1 : 0,
//         tcs_value: parseFloat(document.querySelector("#tcsValue")?.value) || 0,
//         tcs_type: document.querySelector("#tcsTax")?.value || "",
//     };

//     // Combine All Data
//     return {
//         products,
//         additionalCharges,
//         transportDetails,
//         otherDetails,
//        invoice_number: document.querySelector("#invoice_code")?.value || "",
//         invoice_date: document.querySelector("#invoice_date")?.value || "", // Fetch Invoice Date
//         customer_name: document.querySelector("#customer_name_choice")?.value || "",
//         customer_email: document.querySelector("#customer_email")?.value || "",
//         total_amount: parseFloat(document.querySelector("#final_taxable_amt")?.innerText) || 0,
//         total_gst: parseFloat(document.querySelector("#final_gst_amount")?.innerText) || 0,
//         total_cess: parseFloat(document.querySelector("#final_cess_amount")?.innerText) || 0,
//         grand_total: parseFloat(document.querySelector("#gtotal")?.innerText) || 0,
//         note: document.querySelector("#note")?.value || "",
//         terms: document.querySelector("#terms_condition")?.value || ""
//     };
// }

// function submitInvoice(event) {
//    // event.preventDefault(); // Prevent default form submission

//     const invoiceData = getInvoiceData(); // Extract all invoice details

//     fetch("save_invoice.php", {
//         method: "POST",
//         headers: {
//             "Content-Type": "application/json" // Send data as JSON
//         },
//         body: JSON.stringify(invoiceData) // Convert data to JSON
//     })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 alert("Invoice saved successfully.");
//                 window.location.href = "view-invoices.php"; // Redirect on success
//             } else {
//                 alert("Failed to save invoice: " + data.message);
//             }
//         })
//         .catch(error => {
//             console.error("Error:", error);
//             alert("An error occurred while saving the invoice.");
//         });
// }




  </script>
</body>

</html>