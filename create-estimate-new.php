<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['LOG_IN'])) {
  header("Location:login.php");
} else {
  $_SESSION['url'] = $_SERVER['REQUEST_URI'];
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
                <h4 class="m-b-10">Create Quotation</h4>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                <li class="breadcrumb-item"><a href="#">Quotation</a></li>
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
            </div>

  <div class="card-body table-border-style">
    <div class="table-responsive">
      <div class="row">
        <div class="col-sm-12">
          <div class="">
            <div class="card-body">
              <form action="quotationdb.php" method="POST" enctype="multipart/form-data">

                <div class="row border border-dark" >  
                  <?php include 'fetch_user_data.php'; ?>


<div class="col-md-8 border-right border-dark">
<h6 style="float:left;" class="pt-2">
<?php echo htmlspecialchars($user['name']); ?><br/>
<?php echo htmlspecialchars($user['address']); ?><br/>
Email: <?php echo htmlspecialchars($user['email']); ?><br/>
Phone: <?php echo htmlspecialchars($user['phone']); ?><br/>
GSTIN: <?php echo htmlspecialchars($user['gstin']); ?><br/>
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
    $invoice_code="Quotation0".($s+1);
 }
 else{
  $id = 0;
  $invoice_code = "Quotation0".(1);
 }
                          ?>
              <input class="form-control" type="text" id="purchaseNo" value="<?php echo $invoice_code; ?>" name="purchaseNo" readonly />
                <label class="form-control col-sm-5" for="purchaseNo">Purchase No</label>
                
                        </div>
                        <div class="py-1 input-group">
                            <input class="form-control" type="date" id="purchaseDate" name="purchaseDate" required/>
                            <label class="form-control col-sm-5" for="purchaseDate">Purchase Date</label>
                        </div>
                        <div class="py-1 input-group">
                            <input class="form-control" type="date" id="dueDate" name="dueDate" required>
                             <label class="form-control col-sm-5" for="dueDate">Validity Date</label>
                        </div>
                    </div>
                </div>

<div class="row" id="customer_data"></div>


            <div class="row" id="customer_dp">
              <div class="col-md-4 border-left border-bottom border-dark p-3">
                <div>
                   <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCustomersModal">Add New Customer</button> -->
       <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addCustomersModal" style="margin-top: -10px; height: 25px; font-size: 12px;"><i class="fa fa-plus"></i> <b>New</b></button>
                  <h6>Customer info</h6>
                    <div class="form-group" >
    <input class="form-control" list="customer_name" name="customer_name_choice" id="customer_name_choice" onchange="checknamevalue(this.value)" autocomplete="off" />
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
                    <input class="form-control" list="product" name="product_choice" id="product_choice" placeholder="Product" />
                            <datalist name="product" id="product">
                              <option value="">Select Items </option>
                              <!-- <option value="Others"> -->
                                <?php
                                $sql = "select * from inventory_master";
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
                      <th class="text-center">Product </th>
                      <th class="text-center">Product Desc </th>
                       <th class="text-center">Quantity</th>
                      <th class="text-center">Price</th>
                       <th class="text-center">Discount</th>
                      <th class="text-center">GST</th>
                       <!-- For CGST/SGST -->
            <th id="cgst-th" style="display: none;">CGST</th>
            <th id="sgst-th" style="display: none;">SGST</th>
            <!-- For IGST -->
            <th id="igst-th" style="display: none;">IGST</th>
                      <th class="text-center">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                         <tfoot>
                 <tr>

                    <td colspan="2" rowspan="5">
                        <textarea class="form-control" placeholder="Note" name="note" id="note" cols="20" style="width: 100%; height: 112px;"></textarea>
                    </td>           
                     <td colspan="3" class="text-right" id="taxable_amt_text" style="vertical-align: middle;">Taxable Amount</td>
                     <td style="text-align:right;" id="final_taxable_amt"></td>
                </tr> 
          
                <tr>
                    <td class="text-right" colspan="3">CGST & SGST</td>
                    <td></td>
                </tr>
             
                 <tr id="additional-charges-container" >
                    <td colspan="2" class="" style="padding: 0px 2px !important;" >
                        <div class="additional-charges-list">
                            <!-- Additional charges will be appended here-->
                        </div>
                    </td> 
                </tr>
                <tr>
                    <td class="text-right" colspan="3">Select Additional Charges</td>
                    <td>
                        <select class="form-control" id="additional_charges" onchange="addCharge();">
                            <option value="">Select Additional Charges</option>
                            <option value="freight charge">Freight Charge</option>
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
                      <th class="text-right" colspan="3">Grand Total</th>
                     <th class="text-right">
                <span id="gtotal">0.00</span>
                <input type="hidden" name="total_amount" value="0">
            </th>
                    </tr>
               
            </tfoot>
               
                </table>
        
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
                    <div class="col-md-2"><input type="submit" class="btn btn-primary " name="submit" value="Submit" /></div>
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
        const purchaseDate = document.getElementById('purchaseDate');
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

// Function to add more items
function add_more() {
    const prod_desc = $('#prod_desc').val();
    const product = $('#product_choice').val();
    const productid = $('#productid').val();
    const qty = $('#qty').val();
    const price = $('#price').val();
    let ttprice = $('#ttprice').val(); // Price including GST
    const gst = $('#gst').val(); // GST rate
    const netprice = $('#netprice').val(); // Base price (exclusive GST)
    const in_ex_gst = $('#in_ex_gst').val(); // GST type: inclusive or exclusive
    const discount = parseFloat($('#discount').val()) || 0;  // Discount (default to 0 if empty)

    let total = 0;
    let cgst = 0;
    let sgst = 0;
    let igst = 0;

    // Get customer billing and shipping states
    const customer_s_state = $('#customer_s_state').val();
    const business_state = $('#business_state').val();

    // Determine if the transaction is intrastate or interstate
    let gst_type;
    if (customer_s_state === business_state) {
        gst_type = "CGST/SGST";
        $('#cgst-th').show();
        $('#sgst-th').show();
        $('#igst-th').hide();
    } else {
        gst_type = "IGST";
        $('#igst-th').show();
        $('#cgst-th').hide();
        $('#sgst-th').hide();
    }

    // Add GST and discount columns if not already added
    if ($('#item-list thead th').length === 8) {  // Default columns
        if (gst_type === "CGST/SGST") {
            $('#item-list thead tr').append('<th>CGST</th><th>SGST</th>');
        } else if (gst_type === "IGST") {
            $('#item-list thead tr').append('<th>IGST</th>');
        }
        // Add discount column after price column dynamically
        $('#item-list thead tr').append('<th>Discount</th>');
    }

    let gstAmount = 0;
let discountedPrice = parseFloat(ttprice);  // Initial price (inclusive or exclusive of GST)
let discountedBasePrice = discountedPrice;  // Variable for the discounted base price

// Apply discount on the base price (exclusive of GST)
if (discount > 0) {
    discountedBasePrice = discountedBasePrice - (discountedBasePrice * discount) / 100;  // Apply discount to base price
}

// Calculate based on GST type (inclusive or exclusive)
if (in_ex_gst === "inclusive of GST") {
    // Calculate base price from inclusive price
    let basePrice = discountedBasePrice / (1 + parseFloat(gst) / 100);  // Calculate base price from inclusive price
    gstAmount = discountedBasePrice - basePrice;  // GST amount = Total price - base price

    // Split GST into CGST/SGST or IGST
    if (gst_type === "CGST/SGST") {
        cgst = (gstAmount / 2).toFixed(2); // CGST is half of the GST amount
        sgst = (gstAmount / 2).toFixed(2); // SGST is half of the GST amount
    } else if (gst_type === "IGST") {
        igst = gstAmount.toFixed(2); // IGST is the full GST amount
    }

    // Total after discount (inclusive price should be used here)
    total = (discountedPrice * parseFloat(qty));  // Total after discount (inclusive price)
} else if (in_ex_gst === "exclusive of GST") {
    gstAmount = (discountedBasePrice * parseFloat(gst)) / 100; // GST amount = Price * GST rate

    // Split GST into CGST/SGST or IGST
    if (gst_type === "CGST/SGST") {
        cgst = (gstAmount / 2).toFixed(2); // CGST is half of the GST amount
        sgst = (gstAmount / 2).toFixed(2); // SGST is half of the GST amount
    } else if (gst_type === "IGST") {
        igst = gstAmount.toFixed(2); // IGST is the full GST amount
    }

    // Total after discount + GST (for exclusive GST)
    total = (discountedBasePrice + gstAmount) * parseFloat(qty);  // Total after discount + GST
}


   
    // Generate the HTML for the item row
    itemno = $('#item-list tbody tr').length + 1;

    const html = `
        <tr data-item-id="${itemno}">
            <td>${product}</td>
            <td>${prod_desc}</td>
            <td>${qty}</td>
            <td>${price}</td>
            <td>${discount > 0 ? discount + "%" : "0%"}</td> <!-- Discount column -->
            <td>${gst}</td>
            ${gst_type === "CGST/SGST" ? `
                <td>${cgst}</td>
                <td>${sgst}</td>
            ` : `
                <td>${igst}</td>
            `}
            <td>${total.toFixed(2)}</td>
            <td class="cus_padding">
                <textarea name="proddesc[]" id="proddesc${itemno}" hidden>${prod_desc}</textarea>
                <input type="number" name="itemnum[]" id="itemnumval${itemno}" value="${itemno}" hidden/>
                <input type="number" name="gstval[]" id="gstval${itemno}" value="${gst}" hidden/>
                <input type="number" name="netpriceval[]" id="netpriceval${itemno}" value="${netprice}" hidden/>
                <input type="text" name="in_ex_gst_val[]" id="in_ex_gst_val${itemno}" value="${in_ex_gst}" hidden/>
                <input type="number" name="cgstval[]" id="cgstval${itemno}" value="${cgst}" hidden/>
                <input type="number" name="sgstval[]" id="sgstval${itemno}" value="${sgst}" hidden/>
                <input type="number" name="igstval[]" id="igstval${itemno}" value="${igst}" hidden/>
                <input name="products[]" id="productsval${itemno}" value="${product}" hidden/>
                <input name="productids[]" id="productidsval${itemno}" value="${productid}" hidden/>
                <input type="number" name="qtyvalue[]" id="qtyvalueval${itemno}" value="${qty}" hidden/>
                <input type="number" name="priceval[]" id="priceval${itemno}" value="${price}" hidden/>
                <input type="hidden" name="total[]" id="total${itemno}" value="${total.toFixed(2)}" />
                <button class="btn btn-sm" type="button" onclick="rem_item(this)">
                    <i class="fa fa-trash" style="color:red;"></i>
                </button>
            </td>
            <td class="cus_padding">
                <button class="btn btn-sm btn-edit" type="button" onclick="editItem(this)">
                    <i class="fa fa-edit" style="color:blue;"></i>
                </button>
            </td>
        </tr>
    `;

    // Append the new item row to the table
    $('#item-list tbody').append(html);
       $('#prod_desc').val('');
    $('#product_choice').val('');
    $('#qty').val(1);

    $('#price').val('');
    calc_total();


     updateFooterTotals();
}

function updateFooterTotals() {
    // Example: update final taxable amount, grand total, etc.
    let finalTaxableAmt = 0;
    $('#item-list tbody tr').each(function() {
        let total = parseFloat($(this).find('td').last().text());
        finalTaxableAmt += total;
    });

    $('#final_taxable_amt').text(finalTaxableAmt.toFixed(2));
    $('#gtotal').text(finalTaxableAmt.toFixed(2));
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




function calculateTotal() {
    var qty = parseFloat($('#quantity').val()) || 0;
    var price = parseFloat($('#rate').val()) || 0;
    var discount = parseFloat($('#discount').val()) || 0;
    var total = (qty * price) - discount;
    $('#edit_total').val(total.toFixed(2));
}

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

        calc_total();
          $('input[name="total[]"]').removeAttr('required');
        $('#editItemModal').modal('hide');

    });
    $('input[name="total[]"]').attr('type', 'hidden');
});

// Function to calculate subtotal and grand total
function calc_total() {
    var total = 0;
    var pack_price = parseFloat($('#pack_price').val()) || 0;

    $('#item-list tbody tr').each(function() {
        var rowTotal = parseFloat($(this).find('input[name="total[]"]').val()) || 0;
        total += rowTotal;
    });

    console.log('Subtotal:', total);  // For debugging

    // Update subtotal
    $('[name="sub_total"]').val(total.toFixed(2));
    $('#sub_total').text(total.toFixed(2).toLocaleString('en-US'));

    // Calculate and update grand total
    var pack_total = pack_price + total;
    console.log('Pack Total:', pack_total);  // For debugging
    var gt_round = Math.round(pack_total);
    $('[name="total_amount"]').val(gt_round.toFixed(2));
    $('#gtotal').text(gt_round.toFixed(2).toLocaleString('en-US'));
}

// Function to remove an item
function rem_item(button) {
    $(button).closest('tr').remove();
    calc_total();
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

        // alert('Selected Product ID: ' + productId);
        // You can use the productId as needed (e.g., submit it in a form)
        console.log(customerId);
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
            // $("#customer_data").show();
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
alert(customer_s_state);

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
    var select = document.getElementById("additional_charges");
    var selectedOption = select.options[select.selectedIndex];

    if (selectedOption.value) {
        var chargeName = selectedOption.text;
        var chargeValue = parseFloat(selectedOption.getAttribute("data-charge"));

        // Check if charge is already added
        var existingCharge = document.getElementById("charge_" + selectedOption.value);
        if (existingCharge) {
            alert("This charge has already been added.");
            return;
        }

        var chargesList = document.querySelector("#additional-charges-container .additional-charges-list");
        var row = document.createElement("div"); // Use a div for inline elements
        row.id = "charge_" + selectedOption.value;
        row.className = "additional-charge-row";

        row.innerHTML = `

        <div id="charge_excise_duties" class="additional-charge-row">
    <div class="row align-items-center">
        <div class="col-5 text-right">
            <span class="charge-name">${chargeName}</span>
        </div>
        <div class="col-2">
            <button type="button" onclick="removeCharge('${row.id}')" class="btn btn-link text-danger">Remove</button>
        </div>
        <div class="col-5">
            <input type="number" class="form-control charge-input text-right" value="${chargeValue}" style="width: 100%;">
        </div>
    </div>
</div>

            <!--<div class="d-flex justify-content-between align-items-center">
                <span class="charge-name">${chargeName}</span>
                 <button type="button" onclick="removeCharge('${row.id}')" class="btn btn-link text-danger" style="border-right:1px solid black">Remove</button>
                <input type="number" class="form-control charge-input" value="${chargeValue}" style="width: 100px;">
               
            </div>-->
        `;

        // Append the row to the charges list
        chargesList.appendChild(row);

        // Clear the dropdown selection
        select.selectedIndex = 0;

        // Update total
        calculateTotal();
    }
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