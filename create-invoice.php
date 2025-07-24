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
<!-- Include select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

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
                <h4 class="m-b-10">Create Invoice</h4>
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
             <h4 class="m-b-10">Create Invoice</h4>
            </div>
            

  <div class="card-body table-border-style">
    <div class="table-responsive">
      <div class="row">
        <div class="col-sm-12">
          <div class="">
            <div class="card-body">
              <form id="" action="save_invoice.php" method="POST">
                  <!--<form id="" action="si123.php" method="POST">-->
                  <!--<form id="dynamic-form" action="<?php echo $activeTemplate; ?>" save_invoice.php method="POST">-->

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
                          $result1=mysqli_query($conn,"select id from invoice where id=(select max(id) from invoice)");
  if($row1=mysqli_fetch_array($result1))
  {
    $id=$row1['id']+1;
    $i=$row1['id'];
    $s=preg_replace("/[^0-9]/", '', $i);
    $invoice_code="INV0".($s+1);
 }
 else{
  $id = 0;
  $invoice_code = "INV0".(1);
 }
                          ?>
              <input class="form-control" type="text" id="invoice_code" value="<?php echo $invoice_code; ?>" name="invoice_code" readonly />
                <label class="form-control col-sm-5" for="invoice_code">Invoice No</label>
                
                        </div>
                        <div class="py-1 input-group">
                            <input class="form-control" type="date" id="invoice_date" name="invoice_date" required/>
                            <label class="form-control col-sm-5" for="invoice_date">Invoice Date</label>
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
    document.getElementById('invoice_date').value = formattedCurrentDate;
    document.getElementById('dueDate').value = formattedDueDate;
</script>
<div class="row" id="customer_data">
      
</div>


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

                                 
              <datalist name="customer_name" id="customer_name" placeholder="Select Customer" >
                <!-- <option value="Others"> -->
                  <?php
                  $sql = "select * from customer_master where contact_type = 'Customer' AND branch_id='$branch_id' ";
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
              <div class="col-md-1 p-1 border-right border-bottom"> Quantity</div>
              <div class="col-md-2 p-1 border-right border-bottom" id="pricevalbox"> Price</div>
              <div class="col-md-1 p-1 border-right border-bottom" >Discount</div>
               <div class="col-md-1 p-1 border-right border-bottom" >GST </div>
                <div class="col-md-1 p-1 border-right border-bottom" >DNO </div>        
                 <div class="col-md-1 p-1 border-right border-bottom" >Size </div>
                  <div class="col-md-1 p-1 border-right border-bottom" >Color </div>

               <!--<div class="col-md-2 p-1 border-right border-bottom" >
                  <label for="gst">GST</label> 
                Total
              </div>-->

      <div class="col-md-3 p-1 border-right border-left border-bottom">
  <input type="number" name="itemno" id="itemno" data-count="1" hidden />
 
  <input class="form-control" list="product" name="product_choice" id="product_choice" placeholder="Product or Barcode" autocomplete="off"  />
  <datalist name="product" id="product">
    <option value="">Select Items</option>
    <?php
    $sql = "SELECT * FROM inventory_master WHERE inventory_type ='Sales Catalog' OR can_be_sold = '1'";
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
  <input type="hidden" name="batch_no[]" id="hidden_batch_no">
  <input type="text" name="productid" id="productid" hidden />

  <textarea name="prod_desc" id="prod_desc" rows="1" class="form-control" placeholder="Product description"></textarea>
</div>
       <script type="text/javascript">
        document.getElementById("product_choice").addEventListener("input", function () {
    let selectedOption = document.querySelector("#product option[value='" + this.value + "']");

    if (selectedOption) {
        let stock = selectedOption.getAttribute("data-stock");
        let catlogType = selectedOption.getAttribute("data-catlogtype"); // Get the catalog type (product/service)
console.log(stock);
        console.log("Stock:", stock, "Type:", catlogType); // Debugging output

        if (catlogType !== "services" && stock === "0") { 
            // Only check stock for products, not services
            alert("This product is out of stock!");
            this.value = ""; // Clear the input field
        }
    }
});


       </script>     
             
              <div class="col-md-1 p-1 border-right border-bottom">
                 <!-- <label for="qty">Quantity</label> -->
                 <input class="form-control" type="number" min="1" name="qty" id="qty" value="1">
              </div>

          
              <div class="col-md-2 p-1 border-right border-bottom" id="pricevalbox">
                 <!-- <label for="price">Price</label> -->
                <input type="number" class="form-control" name="price" id="price" value="" >
              </div>
              <div class="col-md-1 p-1 border-right border-bottom" >
                 <!-- <label for="discount">Discount</label> -->
                 
                <input type="number" class="form-control" name="discount" id="discount" value="" min="0">
              </div>
               <div class="col-md-1 p-1 border-right border-bottom" >
                 <!-- <label for="gst">GST</label> -->
                
                   <input type="number" min="0" class="form-control" name="gst" id="gst" value="">
                
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
              <!-- <div class="col-md-2 p-1 border-right border-bottom" id="pricevalbox"> -->
                <input type="text" class="form-control" name="netprice" id="netprice" value="" hidden >
               <input type="text" class="form-control" name="ttprice" id="ttprice" value="" hidden>
               <input type="text" class="form-control" name="cess_rate" id="cess_rate" value="" hidden>
               <input type="text" class="form-control" name="cess_amount" id="cess_amount" value="" hidden>
               <input type="text" class="form-control" name="hsn_code" id="hsn_code" value="" hidden>
               <input type="text" class="form-control" name="units" id="units" value="" hidden>
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
        <th>HSN</th>
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
<!-- 
                <tr>
                    <td class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Total Cess</td>
                    <td style="text-align:right;" id="final_cess_amount"></td>
                    
                </tr> -->

              <tr id="tcs-row" style="display: none;">
                  <td class="" style="width: 60%; vertical-align: middle; border-right: 1px solid #ada7a7; border-bottom: 0px;">TCS</td>
                  <td style="text-align:right;" id="final_tcs_amount">0.00</td>
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
                   <input type="hidden" name="final_tcs_amount" id="final_tcs_value_field" value="">
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
                  </div>
                </td>
                <td>
                <div class="">
                  <label for="cess" class="form-label">Cess</label>
                  <input type="number" step="0.01" class="form-control bordered-input" id="cess" name="cess">
                </div>
              </td>
              </tr>
              <tr>
              
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
// let count = 1;
// let itemno = 1;
//  let tot_taxable = 0; // Initialize the total taxable amount

// // Calculate the taxable amount for the current product
// let taxableAmount = 0;
//     let cess_total =0;
//     let tol_gst = 0;

// // Function to add more items to the table
// function add_more() {
//     const prod_desc = $('#prod_desc').val();
//     const product = $('#product_choice').val();
//     const productid = $('#productid').val();
//     const qty = ($('#qty').val()) || 0;
//     const price = parseFloat($('#price').val()) || 0; // Price
//       const netprice = parseFloat($('#netprice').val()) || 0; // Price
//     const gst = parseFloat($('#gst').val()) || 0; // GST rate
//     const discount = parseFloat($('#discount').val()) || 0; // Discount %
//     const cess_rate = ($('#cess_rate').val()) || 0; // Cess rate %
//     const cess_amount = parseFloat($('#cess_amount').val()) || 0; // Cess amount (from hidden input)
//     const in_ex_gst = $('#in_ex_gst').val(); // GST type (inclusive or exclusive)
//   const hsn_code = ($('#hsn_code').val()) || 0; 
 
//     const units = ($('#units').val()); 
//  const batchid = ($('#hidden_batch_no').val()) || 0; 
//  console.log("batch id",batchid);
//    const colorval = ($('#color').val()) || " "; 
//      const sizeval = ($('#size').val()) || 0; 
//        const dnoval = ($('#dno').val()) || 0; 
    

//     const customer_s_state = $('#customer_s_state').val(); // Customer State
//     const business_state = $('#business_state').val(); // Business State

//     if (!product || qty <= 0 || price <= 0) {
//         // alert("Please fill in all required fields (Product, Quantity, Price).");
//         return;
//     }
// // Check if the product already exists in the table
//     let productExists = false;
//     $('#item-list tbody tr').each(function () {
//         const existingProduct = $(this).find('td:nth-child(1)').text(); // Get the product name from the first column
//         if (existingProduct === product) {
//             productExists = true;
//         }
//     });

//     // If the product already exists, show SweetAlert
//     if (productExists) {
//         Swal.fire({
//             icon: 'error',
//             title: 'Oops...',
//             text: 'This product has already been added.',
//             confirmButtonText: 'Ok'
//         });
//         return;
//     }
//     let basePrice = 0;
//     let taxableAmount = 0;
//     let gstAmount = 0;
//     let cgst = 0, sgst = 0, igst = 0;
//     let totalAmount = 0;

//     // Calculate taxable amount and GST based on inclusive/exclusive GST
//     if (in_ex_gst === "inclusive of GST") {
//         // basePrice = price / (1 + gst / 100); // Extract base price
//         taxableAmount = netprice * qty;
//         gstAmount = taxableAmount * (gst / 100); // GST amount
//     } else if (in_ex_gst === "exclusive of GST") {
//         taxableAmount = price * qty; // Price is already exclusive of GST
//         gstAmount = taxableAmount * (gst / 100); // GST amount
//     }

//     // Apply Discount
//     const discountedTaxableAmount = taxableAmount - (taxableAmount * discount) / 100;

//     // Recalculate GST based on discounted taxable amount
//     gstAmount = discountedTaxableAmount * (gst / 100);

//     // Determine CGST, SGST, IGST based on state
//     if (customer_s_state === business_state) {
//         // Intrastate: Split GST equally into CGST and SGST
//         cgst = (gstAmount / 2);
//         sgst = gstAmount / 2;
//     } else {
//         // Interstate: Entire GST is treated as IGST
//         igst = gstAmount;
//     }

// // Calculate Cess on discounted taxable amount
// const finalCessAmount = discountedTaxableAmount * (cess_rate / 100); 

//     // Use the retrieved cess amount
//     // const finalCessAmount = cess_amount * qty;

//     // Calculate Total Amount
//     totalAmount = discountedTaxableAmount + gstAmount + finalCessAmount;

//     // Generate Table Row with Hidden Inputs
//     const itemno = $('#item-list tbody tr').length + 1;

//     const rowHtml = `
//         <tr data-item-id="${itemno}">
//             <td>${product}</td>
//             <td>${prod_desc}</td>
//             <td>${qty}</td>
//             <td>${price.toFixed(2)}</td>
//             <td>${discount}%</td>
//             <td>${gst}%</td>
//             <td>${cgst > 0 ? cgst.toFixed(2) : '-'}</td>
//             <td>${sgst > 0 ? sgst.toFixed(2) : '-'}</td>
//             <td>${igst > 0 ? igst.toFixed(2) : '-'}</td>
//             <td style="display:none;">${finalCessAmount > 0 ? finalCessAmount.toFixed(2) + ' (' + cess_rate + '%)' : '-'}</td>
//             <td>${hsn_code}</td>
//             <td>${totalAmount.toFixed(2)}</td>
//             <td>
//                 <button type="button" class="btn btn-sm" onclick="confirm_remove_item(this)""><i class="fa fa-trash" style="color:red;"></i></button>
//                       <button type="button" class="btn btn-sm btn-edit" onclick="editItem(this)"><i class="fa fa-edit" style="color:blue;"></i></button>
//                 </td>
//             <input type="hidden" id="proddesc_${itemno}" name="proddesc[]" value="${prod_desc}">
            
//             <input type="hidden" id="product_${itemno}" name="products[]" value="${product}">
//             <input type="hidden" id="productid_${itemno}" name="productids[]" value="${productid}">
//             <input type="hidden" id="qty_${itemno}" name="qtyvalue[]" value="${qty}">
//             <input type="hidden" id="price_${itemno}" name="priceval[]" value="${price}">
//             <input type="hidden" id="gst_${itemno}" name="gstval[]" value="${gst}">
//             <input type="hidden" id="gstamount_${itemno}" name="gstamountval[]" value="${gstAmount.toFixed(2)}">
//             <input type="hidden" id="cgst_${itemno}" name="cgstval[]" value="${cgst.toFixed(2)}">
//             <input type="hidden" id="sgst_${itemno}" name="sgstval[]" value="${sgst.toFixed(2)}">
//             <input type="hidden" id="igst_${itemno}" name="igstval[]" value="${igst.toFixed(2)}">
//             <input type="hidden" id="discount_${itemno}" name="discountval[]" value="${discount}">
//             <input type="hidden" id="cessrate_${itemno}" name="cessrateval[]" value="${cess_rate}">
//             <input type="hidden" id="cessamount_${itemno}" name="cessamountval[]" value="${finalCessAmount.toFixed(2)}">
//             <input type="hidden" id="total_${itemno}" name="totalval[]" value="${totalAmount.toFixed(2)}">
//             <input type="hidden" id="in_ex_gst_${itemno}" name="in_ex_gst_val[]" value="${in_ex_gst}">
//              <input type="hidden" id="hsn_code_val_${itemno}" name="hsn_code_val[]" value="${hsn_code}">
//               <input type="hidden" id="units_val_${itemno}" name="units_val[]" value="${units}">
//       <input type="hidden" id="batch_id_val_${itemno}" name="batchid[]" value="${batchid}">
//       <input type="hidden" id="color_id_val_${itemno}" name="color_val[]" value="${colorval}">
//       <input type="hidden" id="size_id_val_${itemno}" name="size_val[]" value="${sizeval}">
//       <input type="hidden" id="dno_id_val_${itemno}" name="dno_val[]" value="${dnoval}">
//         </tr>
//     `;

//     // Append Row to Table
//     $('#item-list tbody').append(rowHtml);

//     // Clear Input Fields
//     $('#prod_desc').val('');
//     $('#product_choice').val('');
//     $('#qty').val(1);
//     $('#price').val('');
//     $('#discount').val('');
//     $('#gst').val('');
//     $('#cess_rate').val('');
//     $('#cess_amount').val('');
//      $('#dno').val('');
//  $('#size').val('');
//     $('#batchSelect').html('<option value="">Select Batch</option>').hide();
//  $('#color').val('');
//     // Recalculate Totals
//     calculate_totals();
// }

    function updateRowCalculations(rowIndex, newQty) {
    // Get the necessary values for recalculation
    const row = $('#item-list tbody tr').eq(rowIndex); // Get the row by index
    const price = parseFloat(row.find('input[name="priceval[]"]').val()); // Price
      const netprice = parseFloat(row.find('input[name="netpriceval[]"]').val()); // Price
    const gst = parseFloat(row.find('input[name="gstval[]"]').val()); // GST rate
    const discount = parseFloat(row.find('input[name="discountval[]"]').val()); // Discount
    const cess_rate = parseFloat(row.find('input[name="cessrateval[]"]').val()); // Cess rate
    const in_ex_gst = row.find('input[name="in_ex_gst_val[]"]').val(); // Whether GST is inclusive or exclusive

    // Initialize variables for recalculations
    let taxableAmount = 0;
    let gstAmount = 0;

    // Recalculate taxable amount and GST based on inclusive or exclusive GST
  if (in_ex_gst === "inclusive of GST") {
            taxableAmount = netprice * newQty;
            gstAmount = taxableAmount * (gst / 100); // GST amount
        } else if (in_ex_gst === "exclusive of GST") {
            taxableAmount = price * newQty; // Price is already exclusive of GST
            gstAmount = taxableAmount * (gst / 100); // GST amount
        }

    // Apply discount to the taxable amount
    const discountedTaxableAmount = taxableAmount - (taxableAmount * discount) / 100;

    // Recalculate GST based on discounted taxable amount
    gstAmount = discountedTaxableAmount * (gst / 100);

    // Determine CGST, SGST, IGST based on the states
    const customer_s_state = $('#customer_s_state').val(); // Customer state
    const business_state = $('#business_state').val(); // Business state
    let cgst = 0, sgst = 0, igst = 0;

    if (customer_s_state === business_state) {
        // Intrastate: Split GST equally into CGST and SGST
        cgst = gstAmount / 2;
        sgst = gstAmount / 2;
    } else {
        // Interstate: Entire GST is treated as IGST
        igst = gstAmount;
    }

    // Calculate cess on discounted taxable amount
    const finalCessAmount = discountedTaxableAmount * (cess_rate / 100);

    // Update the row with new values
    row.find('td:nth-child(3)').text(newQty); // Update quantity
    row.find('td:nth-child(6)').text(gst.toFixed(2)); // Update GST in the table
    row.find('td:nth-child(7)').text(cgst > 0 ? cgst.toFixed(2) : '-'); // Update CGST in the table
    row.find('td:nth-child(8)').text(sgst > 0 ? sgst.toFixed(2) : '-'); // Update SGST in the table
    row.find('td:nth-child(9)').text(igst > 0 ? igst.toFixed(2) : '-'); // Update IGST in the table

    // Update hidden fields with recalculated values
    row.find('input[name="gstamountval[]"]').val(gstAmount.toFixed(2)); // Update hidden GST field
    row.find('input[name="cgstval[]"]').val(cgst.toFixed(2)); // Update hidden CGST field
    row.find('input[name="sgstval[]"]').val(sgst.toFixed(2)); // Update hidden SGST field
    row.find('input[name="igstval[]"]').val(igst.toFixed(2)); // Update hidden IGST field
    row.find('input[name="qtyvalue[]"]').val(newQty); // Update hidden quantity field

    // Calculate and update the total amount
    const totalAmount = discountedTaxableAmount + gstAmount + finalCessAmount;
    row.find('td:nth-child(12)').text(totalAmount.toFixed(2)); // Update total in the table

    // Update the hidden total field
    row.find('input[name="totalval[]"]').val(totalAmount.toFixed(2)); // Update hidden total field

    // Recalculate the overall totals
    calculate_totals();
}


function clearInputFields() {
    $('#prod_desc').val('');
    $('#product_choice').val('');
    $('#qty').val(1);
    $('#price').val('');
    $('#discount').val('');
    $('#gst').val('');
    $('#cess_rate').val('');
    $('#cess_amount').val('');
    $('#dno').val('');
    $('#size').val('');
    $('#batchSelect').html('<option value="">Select Batch</option>').hide();
    $('#color').val('');
}

let count = 1;
let itemno = 1;
 let tot_taxable = 0; // Initialize the total taxable amount

// // Calculate the taxable amount for the current product
let taxableAmount = 0;
    let cess_total =0;
    let tol_gst = 0;
function add_more() {
    const prod_desc = $('#prod_desc').val();
    const product = $('#product_choice').val();
    const productid = $('#productid').val();
    const qty = parseInt($('#qty').val()) || 0; 
    const price = parseFloat($('#price').val()) || 0; // Price
    const netprice = parseFloat($('#netprice').val()) || 0; // Price
    const gst = parseFloat($('#gst').val()) || 0; // GST rate
    const discount = parseFloat($('#discount').val()) || 0; // Discount %
    const cess_rate = ($('#cess_rate').val()) || 0; // Cess rate %
    const cess_amount = parseFloat($('#cess_amount').val()) || 0; // Cess amount (from hidden input)
    const in_ex_gst = $('#in_ex_gst').val(); // GST type (inclusive or exclusive)
    const hsn_code = ($('#hsn_code').val()) || 0; 
    const units = ($('#units').val()); 
    const batchid = ($('#hidden_batch_no').val()) || 0; 
    // alert(batchid);
    const colorval = ($('#color').val()) || " "; 
    const sizeval = ($('#size').val()) || 0; 
    const dnoval = ($('#dno').val()) || 0; 
    const customer_s_state = $('#customer_s_state').val(); // Customer State
    const business_state = $('#business_state').val(); // Business State

    // Validate product and quantity
    if (!product || qty <= 0 || price <= 0) {
        return;
    }

    let productExists = false;
    let batchExists = false;

    // Iterate over existing rows to check if the product and batch already exist
    $('#item-list tbody tr').each(function () {
        const existingProduct = $(this).find('td:nth-child(1)').text(); // Get the product name from the first column
        const existingBatch = $(this).find('input[name="batchid[]"]').val(); // Get batch ID from the hidden input
        const existingDno = $(this).find('td:nth-child(7)').text(); // Get DNO (DNO column in the table)
        const existingSize = $(this).find('td:nth-child(8)').text(); // Get size (Size column)
        const existingColor = $(this).find('td:nth-child(9)').text(); // Get color (Color column)
// alert(existingBatch);
// alert(existingProduct);
// alert(product);
        // Check if product and batch exist
        if (existingProduct === product && existingBatch === batchid) {
          // alert("from batch");
            productExists = true;
            batchExists = true;
            let existingQty = parseInt($(this).find('td:nth-child(3)').text()); // Get existing quantity
            let newQty = existingQty + qty;  // Increase quantity
            $(this).find('td:nth-child(3)').text(newQty); // Update quantity in the table

            // Recalculate totals for this row
            let rowIndex = $(this).index(); // Get row index
            updateRowCalculations(rowIndex, newQty); // Update calculations for this row
            clearInputFields();
        }
        // If no batch exists, check DNO, Size, and Color
        else if (existingDno === dnoval && existingSize === sizeval && existingColor === colorval) {
            productExists = true;
            let existingQty = parseInt($(this).find('td:nth-child(3)').text()); // Get existing quantity
            let newQty = existingQty + qty;  // Increase quantity
            $(this).find('td:nth-child(3)').text(newQty); // Update quantity in the table

            // Recalculate totals for this row
            let rowIndex = $(this).index(); // Get row index
            updateRowCalculations(rowIndex, newQty); // Update calculations for this row
            clearInputFields();
        }
    });

    // If product and batch don't exist, add a new row
    if (!productExists) {
        let basePrice = 0;
        let taxableAmount = 0;
        let gstAmount = 0;
        let cgst = 0, sgst = 0, igst = 0;
        let totalAmount = 0;

        // Calculate taxable amount and GST based on inclusive/exclusive GST
        if (in_ex_gst === "inclusive of GST") {
            taxableAmount = netprice * qty;
            gstAmount = taxableAmount * (gst / 100); // GST amount
        } else if (in_ex_gst === "exclusive of GST") {
            taxableAmount = price * qty; // Price is already exclusive of GST
            gstAmount = taxableAmount * (gst / 100); // GST amount
        }

        // Apply Discount
        const discountedTaxableAmount = taxableAmount - (taxableAmount * discount) / 100;

        // Recalculate GST based on discounted taxable amount
        gstAmount = discountedTaxableAmount * (gst / 100);

        // Determine CGST, SGST, IGST based on state
        if (customer_s_state === business_state) {
            cgst = (gstAmount / 2);
            sgst = gstAmount / 2;
        } else {
            igst = gstAmount;
        }

        // Calculate Cess on discounted taxable amount
        const finalCessAmount = discountedTaxableAmount * (cess_rate / 100);

        // Calculate Total Amount
        totalAmount = discountedTaxableAmount + gstAmount + finalCessAmount;

        // Generate Table Row with Hidden Inputs
        const itemno = $('#item-list tbody tr').length + 1;

        // Include batch details in the product column (if batch exists)
        // const productColumn = batchid ? `${product} (Batch: ${batchid})` : product;

        const rowHtml = `
            <tr data-item-id="${itemno}">
                <td>${product}</td>
                <td>${prod_desc}</td>
                <td>${qty}</td>
                <td>${price.toFixed(2)}</td>
                <td>${discount}%</td>
                <td>${gst}%</td>
                <td>${cgst > 0 ? cgst.toFixed(2) : '-'}</td>
                <td>${sgst > 0 ? sgst.toFixed(2) : '-'}</td>
                <td>${igst > 0 ? igst.toFixed(2) : '-'}</td>
                <td style="display:none;">${finalCessAmount > 0 ? finalCessAmount.toFixed(2) + ' (' + cess_rate + '%)' : '-'}</td>
                <td>${hsn_code}</td>
                <td>${totalAmount.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm" onclick="confirm_remove_item(this)"><i class="fa fa-trash" style="color:red;"></i></button>
                    <button type="button" class="btn btn-sm btn-edit" onclick="editItem(this)"><i class="fa fa-edit" style="color:blue;"></i></button>
                </td>
                <input type="hidden" id="proddesc_${itemno}" name="proddesc[]" value="${prod_desc}">
                <input type="hidden" id="product_${itemno}" name="products[]" value="${product}">
                <input type="hidden" id="productid_${itemno}" name="productids[]" value="${productid}">
                <input type="hidden" id="qty_${itemno}" name="qtyvalue[]" value="${qty}">
                <input type="hidden" id="net_price_${itemno}" name="netpriceval[]" value="${netprice}">
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
                <input type="hidden" id="hsn_code_val_${itemno}" name="hsn_code_val[]" value="${hsn_code}">
                <input type="hidden" id="units_val_${itemno}" name="units_val[]" value="${units}">
                <input type="hidden" id="batch_id_val_${itemno}" name="batchid[]" value="${batchid}">
                <input type="hidden" id="color_id_val_${itemno}" name="color_val[]" value="${colorval}">
                <input type="hidden" id="size_id_val_${itemno}" name="size_val[]" value="${sizeval}">
                <input type="hidden" id="dno_id_val_${itemno}" name="dno_val[]" value="${dnoval}">
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
        $('#dno').val('');
        $('#size').val('');
        $('#batchSelect').html('<option value="">Select Batch</option>').hide();
        $('#color').val('');

        // Recalculate Totals
        calculate_totals();
    }
}

// Update the calculations for an existing row when quantity is updated
// function updateRowCalculations(rowIndex, newQty) {
//     // Get the row element
//     const row = $('#item-list tbody tr').eq(rowIndex);

//     // Recalculate the total for the updated quantity
//     const price = parseFloat(row.find('td:nth-child(4)').text());  // Price column
//     const gst = parseFloat(row.find('td:nth-child(6)').text());  // GST column
//     const totalAmount = (price * newQty) + ((price * newQty) * (gst / 100)); // Price + GST

//     // Update the total in the row
//     row.find('td:nth-child(11)').text(totalAmount.toFixed(2));

//     // Recalculate totals (if you have a total section)
//     calculate_totals();
// }



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

function calculateTotal() {
    const qty = parseFloat($('#quantity').val()) || 0;
    const rate = parseFloat($('#rate').val()) || 0;
    const discount = parseFloat($('#discount').val()) || 0;
    const cess = parseFloat($('#cess').val()) || 0;

    // Calculate subtotal (qty * rate)
    let subtotal = qty * rate;

    // Apply discount
    if (discount > 0) {
        subtotal = subtotal - (subtotal * discount / 100);
    }

    // Calculate cess amount on discounted subtotal
    let cessAmount = subtotal * (cess / 100);

    // Total including cess
    let total = subtotal + cessAmount;

    // Update total field
    $('#edit_total').val(total.toFixed(2));
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
    row.find('td').eq(12).text(total.toFixed(2)); // Total
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
        row.find('td:nth-child(12)').text(rowTotal.toFixed(2)); // Total
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

    // Update the TCS row if TCS is applicable
    if (tcsTaxPercent > 0) {
        $('#tcs-row').show(); // Show the TCS row
        $('#final_tcs_amount').text(tcsValue.toFixed(2)); // Display TCS amount
        grandTotal += tcsValue;
    } else {
        $('#tcs-row').hide(); // Hide the TCS row if not applicable
    }


    // Update footer 
    $('#final_taxable_amt').text(totalTaxable.toFixed(2));
    $('#final_gst_amount').text((totalCGST + totalSGST + totalIGST).toFixed(2));
    $('#final_cess_amount').text(totalCess.toFixed(2));


     $('#final_taxable_amt_field').val(totalTaxable.toFixed(2));
      $('#final_gst_amount_field').val((totalCGST + totalSGST + totalIGST).toFixed(2));
       $('#final_cess_amount_field').val(totalCess.toFixed(2));
       $('#final_tcs_value_field').val(tcsValue.toFixed(2));
    
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
         $("#customer_name_choice").on('keydown', function(e) {
  
    if (e.key === "Tab") {
        e.preventDefault();  
     //   alert("presses space or tab");
    }
});
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

  // Function to populate product details and trigger add_more
  function populateProductDetails(productname, productId, isBarcode) {
    var url = isBarcode ? 'get_product_by_barcode.php' : 'get_product_batches.php';
    
    $.ajax({
      url: url,
      method: 'GET',
      data: isBarcode ? { barcode: productname } : { productId: productId },
      success: function(response) {
        var data = JSON.parse(response);
        
        // Check for valid data
        if (data.status === "success" || data.product_id) {
          // Populate product details (name, id, etc.)
          $("#product_choice").val(data.product_name || productname);
          $("#productid").val(data.product_id || productId);

          // Handle batch population if available
          if (data.is_batch_barcode || isBarcode) {
            $("#batchSelect").html('<option value="">Select Batch</option>');
            if (data.pbid) {
              $("#batchSelect").append('<option value="' + data.pbid + '" selected>' + data.batch_no + '</option>');
              $("#batchSelect").show(); 
              $("#hidden_batch_no").val(data.batch_no);
            }
          }

          // Populate other fields like price, GST, etc.
          $("#gst").val(data.gst || 0);
          // $("#price").val(data.netprice || 0);
          // $("#netprice").val(data.netprice || 0);
          // $("#in_ex_gst").val(data.in_ex_gst || "exclusive of GST");
          if (data.in_ex_gst === "inclusive of GST") {
            $("#price").val(data.netprice);
            $("#netprice").val(data.netprice);
            $("#ttprice").val(data.price);
        } 
        // If the price is exclusive of GST, just use the price directly
        else if (data.in_ex_gst === "exclusive of GST") {
            $("#price").val(data.price); 
            $("#netprice").val(data.netprice); 
        }
          $("#cess_rate").val(data.cess_rate || 0);
          $("#cess_amount").val(data.cess_amt || 0);
          $("#hsn_code").val(data.hsn_code || "");
          $("#units").val(data.units || "");
          $("#color").val(data.color || "");
          $("#size").val(data.size || "");
          $("#dno").val(data.dno || "");

          // If no batches, fetch price without batch
          if (!data.pbid) {
            fetchPriceWithoutBatch(productname, productId);
          }

          // Trigger add_more after all data is populated
          add_more();
        } else {
          alert(data.message || "Product data fetch failed.");
        }
      }
    });
  }

  // Handle product choice change (dropdown selection)
  $("#product_choice").change(function() {
    var productname = $(this).val();
    var customer_s_state = $('#customer_s_state').val();

    if (!customer_s_state) {
      alert("Please select customer details before selecting a product.");
      $("#product_choice").val('');
      return;
    }

    var dataListOptions = document.getElementById('product').querySelectorAll('option');
    var matched = false;
    var productId = null;

    // Check if the product exists in the dropdown
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
      // If product matched, fetch batch data for this product
      $.ajax({
        url: 'get_product_batches.php',
        method: 'GET',
        data: { productId: productId },
        success: function(response) {
          var batches = JSON.parse(response);

          // If batches are found
          if (batches.length > 0) {
            $("#batchSelect").html('<option value="">Select Batch</option>').show();
            batches.forEach(function(batch) {
              var expDate = new Date(batch.exp_date);
              var formattedDate = expDate.getDate().toString().padStart(2, '0') + '-' + (expDate.getMonth() + 1).toString().padStart(2, '0') + '-' + expDate.getFullYear();
              $("#batchSelect").append('<option value="' + batch.id + '">' + batch.batch_no + '</option>');
            });
          } else {
            // If no batches, fetch price without batch
            fetchPriceWithoutBatch(productname, productId);
          }
        }
      });
    } else {
      // If no product matched, treat it as a barcode
      populateProductDetails(productname, null, true);  // true means it's from barcode
    }
  });

  // Handle Enter key for barcode input (when typing in product_choice)
  $("#product_choice").on('keydown', function(e) {
    if (e.which === 13) {  // Enter key
      e.preventDefault();  // Prevent form submission or default behavior

      var productValue = $(this).val();
      var dataListOptions = document.getElementById('product').querySelectorAll('option');
      var matched = false;

      // Check if the value exists in the dropdown
      for (var i = 0; i < dataListOptions.length; i++) {
        if (dataListOptions[i].value === productValue) {
          matched = true;
          break;
        }
      }

      // If it's not a matched product (i.e., barcode), trigger add_more()
      if (!matched) {
        populateProductDetails(productValue, null, true);  // true indicates barcode input
      }
    }
  });

  // Batch selection logic
  $("#batchSelect").change(function() {
    var productId = $("#productid").val();
    // alert(productId);
    var batchNo = $(this).val();
    // alert("from batch");
    if (!batchNo) {
      alert("Please select a batch.");
      return;
    }

    $.ajax({
      url: 'get_batch_data.php',
      method: 'GET',
      data: { productId: productId, batchNo: batchNo },
      success: function(response) {
        var jsonData = JSON.parse(response);
console.log(jsonData);
        // Populate batch details
        $("#hidden_batch_no").val(batchNo); 
        $("#gst").val(jsonData.gst);
        // $("#price").val(jsonData.netprice);
        // $("#netprice").val(jsonData.netprice);
        if (jsonData.in_ex_gst === "inclusive of GST") {
            $("#price").val(jsonData.netprice);
            $("#netprice").val(jsonData.netprice);
            $("#ttprice").val(jsonData.price);
        } 
        // If the price is exclusive of GST, just use the price directly
        else if (jsonData.in_ex_gst === "exclusive of GST") {
            $("#price").val(jsonData.price); 
            $("#netprice").val(jsonData.netprice); 
        }
        $("#in_ex_gst").val(jsonData.in_ex_gst);
        $("#cess_rate").val(jsonData.cess_rate);
        $("#cess_amount").val(jsonData.cess_amt);
        $("#hsn_code").val(jsonData.hsn_code);
        $("#units").val(jsonData.units);
        $("#color").val(jsonData.color);
        $("#size").val(jsonData.size);
        $("#dno").val(jsonData.dno);

        // Trigger add_more after batch data is populated
        // add_more();
      }
    });
  });

  // Function to fetch price without batch details (for products without batches)
  function fetchPriceWithoutBatch(productname, productId) {
    $.ajax({
      url: 'getprice.php',
      method: 'GET',
      data: { productname: productname, productid: productId },
      success: function(data) {
        var jsonData = JSON.parse(data);

        // Populate fields for price and GST
        $("#gst").val(jsonData.gst);
        // $("#price").val(jsonData.netprice);
        // $("#netprice").val(jsonData.netprice);
        if (jsonData.in_ex_gst === "inclusive of GST") {
            $("#price").val(jsonData.netprice);
            $("#netprice").val(jsonData.netprice);
            $("#ttprice").val(jsonData.price);
        } 
        // If the price is exclusive of GST, just use the price directly
        else if (jsonData.in_ex_gst === "exclusive of GST") {
            $("#price").val(jsonData.price); 
            $("#netprice").val(jsonData.netprice); 
        }
        $("#in_ex_gst").val(jsonData.in_ex_gst);
        $("#cess_rate").val(jsonData.cess_rate);
        $("#cess_amount").val(jsonData.cess_amt);
        $("#hsn_code").val(jsonData.hsn_code);
        $("#units").val(jsonData.units);
        $("#color").val(jsonData.color);
        $("#size").val(jsonData.size);
        $("#dno").val(jsonData.dno);

        // Trigger add_more after product data is populated
        // add_more();
      }
    });
  }

});
</script>



  <script type="text/javascript">

// $(document).ready(function() {

//   $("#product_choice").change(function() {
// //      if (e.which === 13) {  // Enter key
// //             e.preventDefault();  // Prevent form submission or default behavior
// // }
//       var customer_s_state = $('#customer_s_state').val();
//       if (!customer_s_state) {
//         alert("Please select customer details before selecting a product.");
//         $("#customer_name_choice").focus();
//         $("#product_choice").val('');
//         return;
//       }

//       var productname = $(this).val();
//       var dataListOptions = document.getElementById('product').querySelectorAll('option');
//       var matched = false;
//       var productId = null;

//       for (var i = 0; i < dataListOptions.length; i++) {
//         if (dataListOptions[i].value === productname) {
//           matched = true;
//           productId = dataListOptions[i].getAttribute('data-productid');
//           break;
//         }
//       }

//   if (matched) {
//     $("#productid").val(productId);
//     $("#batchSelect").html('<option value="">Select Batch</option>').hide();

//     $.ajax({
//       url: 'get_product_batches.php',
//       method: 'GET',
//       data: { productId: productId },
//       success: function(response) {
//         var batches = JSON.parse(response);
//         console.log("gett product batches");
//         console.log(batches);
//         if (batches.length > 0) {
//           $("#batchSelect").show();
//           batches.forEach(function(batch) {
//             var expDate = new Date(batch.exp_date);
//             var formattedDate = expDate.getDate().toString().padStart(2, '0') + '-' + (expDate.getMonth() + 1).toString().padStart(2, '0') + '-' + expDate.getFullYear();
//             $("#batchSelect").append('<option value="' + batch.id + '">' + batch.batch_no  + '</option>');

//           });
//         } else {
//           fetchPriceWithoutBatch(productname, productId);
//         }
//         // Trigger add_more after assigning product details
//                     // add_more();
//       }
//     });
//   } else {

//     $.ajax({
//     url: 'get_product_by_barcode.php',
//     method: 'GET',
//     data: { barcode: productname },
//     success: function(response) {
//         var data = JSON.parse(response);    
//         console.log("from product by barcode");
//         console.log(data);
//         if (data.status === "success") {
//             $("#product_choice").val(data.product_name);
//             $("#productid").val(data.product_id);

//             if (data.is_batch_barcode) {
//     // Clear and show batch dropdown with only this batch
//     $("#batchSelect").html('<option value="">Select Batch</option>');
    
//     // var expDate = new Date(data.exp_date);
//     // var formattedDate = expDate.getDate().toString().padStart(2, '0') + '-' + (expDate.getMonth() + 1).toString().padStart(2, '0') + '-' + expDate.getFullYear();
    
//     $("#batchSelect").append('<option value="' + data.pbid + '" selected>' + data.batch_no + '</option>');
    
//     $("#batchSelect").show(); // ✅ Show dropdown with single batch

//     // Set batch number in hidden input
//     $("#hidden_batch_no").val(data.batch_no);

//     // Fill details
//     $("#gst").val(data.gst);
//     // $("#price").val(data.price);
//     // $("#netprice").val(data.netprice);
//     if (data.in_ex_gst === "inclusive of GST") {
          
//             var gstRate = parseFloat(data.gst);  // GST rate (in percentage)

         
//             $("#price").val(data.netprice);
//             $("#netprice").val(data.netprice);
//             $("#ttprice").val(data.price);
//         } 
//         // If the price is exclusive of GST, just use the price directly
//         else if (data.in_ex_gst === "exclusive of GST") {
//             $("#price").val(data.price); 
//             $("#netprice").val(data.netprice); 
//         }
//     $("#in_ex_gst").val(data.in_ex_gst);
//     $("#cess_rate").val(data.cess_rate);
//     $("#cess_amount").val(data.cess_amt);
//     $("#hsn_code").val(data.hsn_code);
//     $("#units").val(data.units);
//     $("#color").val(data.color);
//     $("#size").val(data.size);
//     $("#dno").val(data.dno);
//     // Trigger add_more after assigning product details
//          // add_more();           // add_more();
// }


//             else if (data.maintain_batch == 1) {
//                 // Load batches as usual
//                 $("#batchSelect").html('<option value="">Select Batch</option>').hide();

//                 $.ajax({
//                     url: 'get_product_batches.php',
//                     method: 'GET',
//                     data: { productId: data.product_id },
//                     success: function(batchResponse) {
//                         var batches = JSON.parse(batchResponse);
//                         console.log("get product batches php");
//                         console.log(batches);
//                         if (batches.length > 0) {
//                             $("#batchSelect").show();
//                             batches.forEach(function(batch) {
//                                 var expDate = new Date(batch.exp_date);
//                                 var formattedDate = expDate.getDate().toString().padStart(2, '0') + '-' + (expDate.getMonth() + 1).toString().padStart(2, '0') + '-' + expDate.getFullYear();
//                                 $("#batchSelect").append('<option value="' + batch.id + '">' + batch.batch_no + '</option>');
//                             });
//                         }
//                     }
//                 });
//             } else {
//                 // No batch maintained, fill directly
//                 $("#gst").val(data.gst);
//                 // $("#price").val(data.price);
//                 // $("#netprice").val(data.netprice);
//                 if (data.in_ex_gst === "inclusive of GST") {
          
//             var gstRate = parseFloat(data.gst);  // GST rate (in percentage)
//             $("#price").val(data.netprice);
//             $("#netprice").val(data.netprice);
//             $("#ttprice").val(data.price);
//         } 
//         // If the price is exclusive of GST, just use the price directly
//         else if (data.in_ex_gst === "exclusive of GST") {
//             $("#price").val(data.price); 
//             $("#netprice").val(data.netprice); 
//         }
//                 $("#in_ex_gst").val(data.in_ex_gst);
//                 $("#cess_rate").val(data.cess_rate);
//                 $("#cess_amount").val(data.cess_amt);
//                 $("#hsn_code").val(data.hsn_code);
//                 $("#units").val(data.units);
//                 $("#color").val(data.color);
//                 $("#size").val(data.size);
//                 $("#dno").val(data.dno);
//                 $("#batchSelect").hide();
//                 alert("from get product batch ");
//                 // add_more();
//             }
//         } else {
//             alert(data.message);
//             $("#product_choice").val('');
//         }
//     }
// });
//   }
// });


// $("#batchSelect").change(function() {
//   var productId = $("#productid").val();
//   var batchNo = $(this).val();
//   if (!batchNo) {
//     alert("Please select a batch.");
//     return;
//   }

//   $.ajax({
//     url: 'get_batch_data.php',
//     method: 'GET',
//     data: { productId: productId, batchNo: batchNo },
//     success: function(response) {
//       var jsonData = JSON.parse(response);

//      $("#hidden_batch_no").val(batchNo); 

//       $("#gst").val(jsonData.gst);
//       // $("#price").val(jsonData.price);
//       // $("#netprice").val(jsonData.netprice);
//       if (jsonData.in_ex_gst === "inclusive of GST") {
          
//             var gstRate = parseFloat(jsonData.gst);  // GST rate (in percentage)
//             $("#price").val(jsonData.netprice);
//             $("#netprice").val(jsonData.netprice);
//             $("#ttprice").val(jsonData.price);
//         } 
//         // If the price is exclusive of GST, just use the price directly
//         else if (jsonData.in_ex_gst === "exclusive of GST") {
//             $("#price").val(jsonData.price); 
//             $("#netprice").val(jsonData.netprice); 
//         }
//       $("#in_ex_gst").val(jsonData.in_ex_gst);
//       $("#cess_rate").val(jsonData.cess_rate);
//       $("#cess_amount").val(jsonData.cess_amt);
//       $("#hsn_code").val(jsonData.hsn_code);
//       $("#units").val(jsonData.units);
//       $("#color").val(jsonData.color);
//       $("#size").val(jsonData.size);
//       $("#dno").val(jsonData.dno);
 
// // add_more();
//     }
//   });
// });

// function fetchPriceWithoutBatch(productname, productId) {
//   $.ajax({
//     url: 'getprice.php',
//     method: 'GET',
//     data: { productname: productname, productid: productId },
//     success: function(data) {
//       console.log("from getprice"); 
//       console.log(data);
//       var jsonData = JSON.parse(data);
//       $("#gst").val(jsonData.gst);
//       // $("#price").val(jsonData.price);
//       // $("#netprice").val(jsonData.netprice);
//        if (jsonData.in_ex_gst === "inclusive of GST") {
          
//             var gstRate = parseFloat(jsonData.gst);  // GST rate (in percentage)
//             $("#price").val(jsonData.netprice);
//             $("#netprice").val(jsonData.netprice);
//             $("#ttprice").val(jsonData.price);
//         } 
//         // If the price is exclusive of GST, just use the price directly
//         else if (jsonData.in_ex_gst === "exclusive of GST") {
//             $("#price").val(jsonData.price); 
//             $("#netprice").val(jsonData.netprice); 
//         }
//       $("#in_ex_gst").val(jsonData.in_ex_gst);
//       $("#cess_rate").val(jsonData.cess_rate);
//       $("#cess_amount").val(jsonData.cess_amt);
//       $("#hsn_code").val(jsonData.hsn_code);
//       $("#units").val(jsonData.units);
//       $("#color").val(jsonData.color);
//       $("#size").val(jsonData.size);
//       $("#dno").val(jsonData.dno);
//       // add_more();
//     }
//   });
// }

// });



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