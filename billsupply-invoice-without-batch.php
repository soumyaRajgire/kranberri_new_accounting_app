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
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h4 class="m-b-10">Bill Of Supply Invoice</h4>
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
      <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
             <h4 class="m-b-10">Bill Of Supply Invoice</h4>
            </div>

  <div class="card-body table-border-style">
    <div class="table-responsive">
      <div class="row">
        <div class="col-sm-12">
          <div class="">
            <div class="card-body">
              <form id="" action="save_billsupply.php" method="POST">

                <div class="row border border-dark" >  
                  <?php include 'fetch_user_data.php'; ?>


<div class="col-md-8 border-right border-dark">
<h6 style="float:left;" class="pt-2">
<?php echo htmlspecialchars($user['branch_name']); ?><br/>
<?php echo htmlspecialchars($user['address_line1']); ?><br/>
Email: <?php echo htmlspecialchars($user['email']); ?><br/>
Phone: <?php echo htmlspecialchars($user['phone_number']); ?><br/>
GSTIN: <?php echo htmlspecialchars($user['GST']); ?><br/>
<input type="text" name="business_state" id="business_state" value="<?php echo htmlspecialchars($user['state']); ?>" hidden>

</h6>
</div> 
                    <div class="col-md-4 pt-1">
                        <div class="py-1 input-group">
                        <?php
// Query to get the maximum ID from the invoice table
$result1 = mysqli_query($conn, "SELECT MAX(id) AS max_id FROM bill_of_supply");

// Check if the query returned a result
if ($result1 && $row1 = mysqli_fetch_assoc($result1)) {
    $max_id = $row1['max_id']; // Retrieve the maximum ID
    $id = $max_id + 1; // Increment the ID
    $bill_code = "BILL0" . $id; // Generate the bill code
} else {
    $id = 1; // Start from 1 if no records exist
    $bill_code = "BILL01"; // Initialize the first bill code
}
?>

              <input class="form-control" type="text" id="bill_code" value="<?php echo $bill_code; ?>" name="bill_code" readonly />
                <label class="form-control col-sm-5" for="bill_code">Bill invoice No</label>
                
                        </div>
                        <div class="py-1 input-group">
                            <input class="form-control" type="date" id="bill_date" name="bill_date" required/>
                            <label class="form-control col-sm-5" for="bill_date">Bill Date</label>
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
    document.getElementById('bill_date').value = formattedCurrentDate;
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
                 
               Price
              </div>
              <div class="col-md-2 p-1 border-right border-bottom" >
               Discount
              </div>
               <div class="col-md-2 p-1 border-right border-bottom" >
              
                
              </div>
               <!--<div class="col-md-2 p-1 border-right border-bottom" >
                  <label for="gst">GST</label> 
                Total
              </div>-->

          <div class="col-md-3 p-1 border-right border-left border-bottom">
            
         
              <input type="number" name="itemno" id="itemno" select-group="" data-count=1 hidden />
                    <!-- <input class="form-control" list="product" name="product_choice" id="product_choice" onchange="checkvalue(this.value)" placeholder="Product" /> -->
                    <input class="form-control" list="product" name="product_choice" id="product_choice" placeholder="Product" />
                            <datalist name="product" id="product">
                              <option value="">Select Items </option>
                              <!-- <option value="Others"> -->
                                <?php
                                $sql = "SELECT * FROM inventory_master 
        WHERE inventory_type = 'Sales Catalog' OR can_be_sold = '1'";
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
                            <input type="hidden" name="productid" id="productid" value="" />
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
                
                   <!-- <input type="number" min="0" class="form-control" name="gst" id="gst" value=""> -->
                
              </div>
              
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
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Discount</th>
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
                 <!-- <input type="hidden" name="final_cess_amount" id="final_cess_amount_field" value="">
                   <input type="hidden" name="final_taxable_amt" id="final_taxable_amt_field" value="" >
                  <input type="hidden" name="final_gst_amount" id="final_gst_amount_field" value=""> -->
                <input type="hidden" name="total_amount" id="total_amount" value="">
            </th>
                    </tr>
               
            </table>
               
                </div>
            </div>

            <?php include("transportation-details.php"); ?>

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
             <input type="text" class="form-control" id="item" name="item" hidden>
            <table class="table table-bordered">
              <tr>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Discount (%)</th>
                <th>Total</th>
              </tr>
              <tr>
                <td><input type="number" class="form-control" id="quantity" name="quantity" min="1" step="any"></td>
                <td><input type="number" class="form-control" id="rate" name="rate" min="0" step="any"></td>
                <td><input type="number" class="form-control" id="discount" name="discount" min="0" max="100" step="any"></td>
                <td><input type="number" class="form-control" id="edit_total" name="edit_total" readonly></td>
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


// Function to add more items to the table
// Function to add more items to the table
function add_more() {
    const prod_desc = $('#prod_desc').val();
    const product = $('#product_choice').val();
    const productid = $('#productid').val();
   const qty = parseFloat($('#qty').val()) || 0; // Quantity
const price = parseFloat($('#price').val()) || 0; // Price
const discountPercentage = parseFloat($('#discount').val()) || 0; 

const discountAmount = price * qty * (discountPercentage / 100);

const priceAfterDiscount = price * qty * (1 - discountPercentage / 100);



    if (!product || qty <= 0 || price <= 0) {
        alert("Please fill in all required fields (Product, Quantity, Price).");
        return;
    }

    // Calculate total price
    const totalPrice = price * qty;

    // Generate Table Row with Hidden Inputs
    const itemno = $('#item-list tbody tr').length + 1;

    const rowHtml = `
        <tr> 
        
            <td>${product}</td>
            <td>${prod_desc}</td>
            <td>${qty}</td>
            <td>${price.toFixed(2)}</td>
            <td>${discountPercentage}</td>
            <td>${priceAfterDiscount}</td>
            
            <td>
             <input type="hidden" id="proddesc_${itemno}" name="proddesc[]" value="${prod_desc}">
            <input type="hidden" id="product_${itemno}" name="products[]" value="${product}">
            <input type="hidden" id="productid_${itemno}" name="productids[]" value="${productid}">
            <input type="hidden" id="qty_${itemno}" name="qtyvalue[]" value="${qty}">
            <input type="hidden" id="discount_${itemno}" name="discount[]" value="${discountPercentage}">
            <input type="hidden" id="price_${itemno}" name="priceval[]" value="${price}">
            <input type="hidden" id="total_${itemno}" name="totalval[]" value="${totalPrice.toFixed(2)}">
            
                <button class="btn btn-sm" onclick="remove_item(this)"><i class="fa fa-trash" style="color:red;"></i></button>
            <button type="button" class="btn btn-sm btn-edit" onclick="editItem(this)"><i class="fa fa-edit" style="color:blue;"></i></button>
                </td>
            
            
           
        </tr>
    `;

    // Append Row to Table
    $('#item-list tbody').append(rowHtml);

    // Clear Input Fields
    $('#prod_desc').val('');
    $('#product_choice').val('');
    $('#qty').val(1);
    $('#price').val('');

    // Recalculate Totals
    calculate_totals();
}

function calculateTotal() {
    const qty = parseFloat($('#quantity').val()) || 0;
    const rate = parseFloat($('#rate').val()) || 0;
    const discount = parseFloat($('#discount').val()) || 0;

    let subtotal = qty * rate;

    if (discount > 0) {
        subtotal = subtotal - (subtotal * discount / 100);
    }

    $('#edit_total').val(subtotal.toFixed(2));
}

function editItem(button) {
    const row = $(button).closest('tr');

    // Save reference to row
    $('#editItemModal').data('currentRow', row);

    const product = row.find('td').eq(0).text();
    const prod_desc = row.find('td').eq(1).text(); // if needed for display
    const qty = row.find('td').eq(2).text();
    const price = row.find('td').eq(3).text();
    const discountText = row.find('td').eq(4).text();
    const total = row.find('td').eq(5).text(); // Total column index corrected

    // Parse discount (remove % if any)
    const discount = discountText.replace('%', '') || 0;

    $('#itemNameSpan').text(product);
    $('#item').val(product);
    $('#quantity').val(qty);
    $('#rate').val(price);
    $('#discount').val(discount);
    $('#edit_total').val(total);

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

    row.find('td').eq(2).text(qty); // Quantity
    row.find('td').eq(3).text(price.toFixed(2)); // Price
    row.find('td').eq(4).text(discount + '%'); // Discount
    row.find('td').eq(5).text(total.toFixed(2)); // Total

    // Also update hidden inputs if any (adjust selector if hidden inputs are inside that row)
    row.find('input[name="qtyvalue[]"]').val(qty);
    row.find('input[name="priceval[]"]').val(price);
    row.find('input[name="discount[]"]').val(discount);
    row.find('input[name="totalval[]"]').val(total);

    // Close modal
    $('#editItemModal').modal('hide');

    // Recalculate grand totals
    calculate_totals();
});


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



function calculate_totals() {
    let grandTotal = 0;

    // Iterate over table rows to calculate product totals
    $('#item-list tbody tr').each(function () {
        const row = $(this);

        // Read the total price from the row
        const rowTotal = parseFloat(row.find('td:nth-child(6)').text()) || 0;

        // Add to grand total
        grandTotal += rowTotal;
    });

    // Display the grand total in the footer
    $('#gtotal').text(grandTotal.toFixed(2));
    $('#total_amount').val(grandTotal.toFixed(2));

    // Debugging (Optional)
    console.log(`Grand Total: ${grandTotal}`);
}





// Function to remove an item
function remove_item(button) {
    $(button).closest('tr').remove(); // Remove the selected row
    calculate_totals(); // Recalculate totals
}



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
   

    function checknamevalue(val) {
      
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

         if (customer_s_state === '' || customer_s_state === undefined) {
            alert("Please select customer details before selecting a product.");
            $("#customer_name_choice").focus();
            $("#product_choice").val(''); // Reset product dropdown
            return;  // Stop further execution
        }
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

</body>

</html>