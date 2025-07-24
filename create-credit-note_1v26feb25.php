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
?>

<html lang="en">

<head>
  <title>iiiQbets - Credit Note</title>
  <meta charset="utf-8">
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
</style>

</head>

<body class="">
  <?php include("customersModal.php");?>

<!-- Adding Services Module-->
                  
           <?php include("servicesModalPopup.php");?>
<!-- End Services Modal-->

<!-- Products Modal -->

<?php include("productsModalPopUp.php");?>
<!-- End of Products Modal-->
  <!-- [ Pre-loader ] start -->
  <?php include("menu.php"); ?>
  <!-- [ Header ] end -->

 <!-- [ breadcrumb ] start -->
 <section class="pcoded-main-container">
    <div class="pcoded-content">
       
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">Create Credit Note</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Create Credit Note</a></li>
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
          <!-- <div class="card-header">
            </div> -->

  <div class="card-body table-border-style">
    <div class="table-responsive">
      <div class="row">
        <div class="col-sm-12">
          <div class="">
            <div class="card-body">
              
            <form action="creditnotedb.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="status" value="Pending">
                <input type="hidden" name="created_by" value="<?php echo $_SESSION['name']; ?>">
               <!--  <input type="hidden" name="cst_mstr_id" id="cst_mstr_id">
                <input type="hidden" name="customer_email" id="customer_email">
                 <input type="hidden" name="customer_name_choice" id="customer_name_choice" value=""> -->
                <input type="hidden" name="branch_id" value="<?php echo isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : ''; ?>">

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
                    <!-- Debit Note Number -->
                    <div class="py-1 input-group">
                      <?php
                      $result1 = mysqli_query($conn, "SELECT id FROM credit_note WHERE id=(SELECT max(id) FROM credit_note)");
                      if ($row1 = mysqli_fetch_array($result1)) {
                        $id = $row1['id'] + 1;
                        $debit_note_code = "CNOTE" . str_pad($id, 5, '0', STR_PAD_LEFT);
                      } else {
                        $debit_note_code = "CNOTE00001";
                      }
                      ?>
                      <input class="form-control" type="text" id="debitNoteNo" value="<?php echo $debit_note_code; ?>" name="debitNoteNo"  required/>
                      <label class="form-control col-sm-5" for="debitNoteNo">Credit Note No</label>
                    </div>

                    <!-- Debit Note Date -->
                    <div class="py-1 input-group">
                      <input class="form-control" type="date" id="debitNoteDate" name="debitNoteDate" required />
                      <label class="form-control col-sm-5" for="debitNoteDate">Note Date</label>
                    </div>
                    
                    <!-- Due Date -->
                    <div class="py-1 input-group">
                 <select class="form-control" id="purchaseInvoiceDropdown" name="purchaseInvoiceDropdown">
    <option value="">Select Invoice</option>
    <?php
    $bid = $_SESSION['branch_id'];
    $sql = "
        SELECT i.id, i.invoice_code 
        FROM invoice i
        LEFT JOIN credit_note cn ON i.id = cn.invoice_id
        WHERE cn.invoice_id IS NULL AND i.branch_id='$bid'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['id']}'>{$row['invoice_code']}</option>";
    }
    ?>
</select>


                        <label class="form-control col-sm-5" for="purchaseInvoiceDropdown"> Invoice</label>
                    </div>
                </div>
                </div>
<!-- <div id="customer_data"></div>
<div id="customer_dp" style="display: none;">
    <p>Please select an invoice to display supplier details.</p>
</div>


<table class="table table-bordered" id="item-list">
    <thead>
        <tr>
            <th>Product</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
      
    </tbody>
</table>
 -->
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
                           <!--  <datalist name="product" id="product">
                              <option value="">Select Items </option>
                             
                                <?php
                                $sql = "select * from inventory_master where  inventory_type ='Sales Catalog'";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                  while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                           
                  <option value="<?php echo $row["name"]?>" data-productid="<?php echo $row["id"]?>">
                            <?php
                                  }
                                }
                            ?>

                            </datalist> -->
                            <input type="text" name="productid" id="productid" value="" hidden/>
                            <textarea name="prod_desc" id="prod_desc" rows="1" class="form-control" cols="20" placeholder="Product description"></textarea>
                 </div>
            
             
              <div class="col-md-2 p-1 border-right border-bottom">
                 <!-- <label for="qty">Quantity</label> -->
                 <!-- <input class="form-control" type="number" min="1" name="qty" id="qty" value="1"> -->
              </div>
          
              <div class="col-md-2 p-1 border-right border-bottom" id="pricevalbox">
                 <!-- <label for="price">Price</label> -->
                <!-- <input type="number" class="form-control" name="price" id="price" value="" > -->
              </div>
              <div class="col-md-2 p-1 border-right border-bottom" >
                 <!-- <label for="discount">Discount</label> -->
                 
                <!-- <input type="number" class="form-control" name="discount" id="discount" value="" min="0"> -->
              </div>
               <div class="col-md-2 p-1 border-right border-bottom" >
                 <!-- <label for="gst">GST</label> -->
                
                   <!-- <input type="number" min="0" class="form-control" name="gst" id="gst" value=""> -->
                
              </div>
              <!-- <div class="col-md-2 p-1 border-right border-bottom" id="pricevalbox"> -->
             <!--    <input type="text" class="form-control" name="netprice" id="netprice" value="" hidden >
               <input type="text" class="form-control" name="ttprice" id="ttprice" value="" hidden>
               <input type="text" class="form-control" name="cess_rate" id="cess_rate" value="" hidden>
               <input type="text" class="form-control" name="cess_amount" id="cess_amount" value="" hidden> -->
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
            <th data-field="product" data-editable="true">Product</th>
            <th data-field="prod_desc" data-editable="true">Product Desc</th>
            <th data-field="qty" data-editable="true">Quantity</th>
            <th data-field="price" data-editable="true">Price</th>
            <th data-field="discount" data-editable="true">Discount</th>
            <th data-field="gst" data-editable="true">GST</th>
            <th data-field="cgst" data-editable="true">CGST</th>
            <th data-field="sgst" data-editable="true">SGST</th>
            <th data-field="igst" data-editable="true">IGST</th>
            <th data-field="cess" data-editable="true">Cess</th>
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
                        <select class="form-control" id="additional_charges" style="margin-left:3px;width:97%;height:33px;" disabled onchange="addCharge();">
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
                
                        <!-- <a type="submit" class="btn btn-success"  href="preview_quotation_pdf.php">Preview</a> 
              <button  type="button" class="btn btn-success" id="prv" data-toggle="modal" data-target="#myModal">Preview</button>  -->
              <div class="row col-md-12 justify-content-end pt-3 mx-4">
              <button type="submit" class="btn btn-sm btn-success" >
  <i class="fa fa-plus"></i>&nbsp;<span class="pinvoice-text">Create Credit Note</span>
</button>
</div>

                       
                        <!-- </div> -->
                      </form>
                    </div>
                  </div>
                </div>
                <!-- [ form-element ] start -->

                <!-- [ form-element ] end -->
              </div>
              <!-- [ Main Content ] end -->
            </div>


  </section>
  
  <script type="text/javascript">


    function calc_total() {
      var total = 0;
      var total1 = 0;
      // var tax_rate = $('#tax_rate').val();
      var pack_price = $('#pack_price').val();
      // console.log(tax_rate);
      // var tax_rate = tax_rate / 100;
      $('#item-list tbody tr').each(function() {
        var tr = $(this)
        total += parseFloat(tr.find('[name="total[]"]').val());
        console.log(total1);
        // total += $('#total').val();
        //console.log(total);
        //console.log(tr);
      })
      $('[name="sub_total"]').val(total)
      $('#sub_total').text(parseFloat(total).toLocaleString('en-US'))
      var pack_total = parseFloat(pack_price) + parseFloat(total);
      // var tax = parseFloat(pack_total) * parseFloat(tax_rate);
      var gtotal =  parseFloat(pack_total);
      var gt_round = Math.round(gtotal);
      // alert(gt_round);
      $('[name="total_amount"]').val(gt_round);
      // var tax_amount_round = Math.round(tax);
      // $('[name="tax_amount"]').val(tax_amount_round);
      // $('#tax_amount').text(parseFloat(tax).toLocaleString('en-US'))
      // $('#tax').text(parseFloat(tax).toLocaleString('en-US'))
      $('#gtotal').text(parseFloat(gt_round).toLocaleString('en-US'))
      // var gtotal_round = gtotal.toPrecision();

      // $('[name="total_amount"]').val(gt_round);
      // $('#tax').text();

    }

    function rem_item(_this) {
      _this.closest('tr').remove();
      // c--;
      itemno--;
      calc_total();
    }

    function edit_item(_this) {
      alert("from edit item");
      var t = document.getElementById('item-list');
      var rows = t.rows; //rows collection - https://developer.mozilla.org/en-US/docs/Web/API/HTMLTableElement
      //for (var i=0; i<rows.length; i++) {
      this.onclick = function() {
        if (this.parentNode.nodeName == 'THEAD') {
          return;
        }
        alert("tr");
        var cells = this.cells; //cells collection
        var f1 = document.getElementById('prod_desc');
        var f2 = document.getElementById('product');
        var f3 = document.getElementById('qty');
        var f4 = document.getElementById('price');
        // var f5 = document.getElementById('discount');
        // var f6 = document.getElementById('diff');
        f1.value = cells[1].innerHTML;
        f2.value = cells[2].innerHTML;
        f3.value = cells[3].innerHTML;
        f4.value = cells[4].innerHTML;
        // f5.value = cells[5].innerHTML;
        console.log(f1.value);
        console.log(f2.value);
        console.log(f3.value);
        _this.closest('tr').remove();
        calc_total();

      };
      // }
    }
  </script>


  <!-- Required Js -->
  <script src="assets/js/vendor-all.min.js"></script>
  <script src="assets/js/plugins/bootstrap.min.js"></script>
  <script src="assets/js/pcoded.min.js"></script>


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
        // var gstAmount = (price * gstRate) / (100 + gstRate);
     var gstAmount = (price * gstRate) / (100 );

        var netPrice = price - gstAmount - nonTaxable;
        netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
        console.log(netPrice);
    } else if (inclusiveGst === "exclusive of GST" && price > 0) {
        var gstAmount = (price * gstRate) / 100;
        var netPrice = price-nonTaxable;
        netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
        console.log(netPrice);
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
        alert(" d r ");
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
      $("#product_choice").change(function() {

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
            // $("#price").val(data);
             var jsonData = JSON.parse(data);
             console.log(jsonData);
    $("#gst").val(jsonData.gst);
          if((jsonData.in_ex_gst) === "inclusive of GST")
          {
            console.log("from inclusive");
            $("#price").val(jsonData.netprice);
          }
          else if((jsonData.in_ex_gst) === "exclusive of GST")
          {
            console.log("from exclusive");
            $("#price").val(jsonData.price);
          }
              
                $("#netprice").val(jsonData.netprice);
                $("#in_ex_gst").val(jsonData.in_ex_gst);



            // $("#pricevalbox").html(data);
            //}     
          }
        });
      })
    });
  </script>
  
<script>
$(document).ready(function () {
    // Disable supplier and product-related inputs initially
 

    // When an invoice is selected, fetch data and enable fields
    $('#purchaseInvoiceDropdown').change(function () {
        const invoiceId = $(this).val();
        if (invoiceId) {
            fetchInvoiceData(invoiceId);
        } else {
            resetFields(); // Reset fields if no invoice is selected
        }
    });

    

   

    // Edit functionality
$(document).on('click', '.fa-edit', function () {
    const row = $(this).closest('tr');


     row.find('td[data-field="qty"] input').attr('type', 'number');


    // Show `input` fields and hide `span` for editable fields only
      row.find('td[data-field="qty"] span').hide();
    // row.find('td[data-field="qty"] input[type="number"]').show();

    row.find('td[data-field="price"] input').attr('type', 'number');

    // Hide the span and show the input field
    row.find('td[data-field="price"] span').hide();
    // row.find('td[data-field="price"] input').show();


    // Change the button icon and color
    $(this).removeClass('fa-edit').addClass('fa-save').css('color', 'green');
});



// $(document).on('click', '.fa-edit', function () {
//     const row = $(this).closest('tr');

//     // Toggle visibility for editable fields (add more as needed)
//     ['qty', 'price', 'discount', 'gst', 'cgst', 'sgst', 'igst', 'cess_rate', 'total'].forEach(field => {
//         row.find(`td[data-field="${field}"] span`).hide();
//         row.find(`td[data-field="${field}"] input[type="number"]`).show();
//     });

//     // Change the button to save mode
//     $(this).removeClass('fa-edit').addClass('fa-save').css('color', 'green');
// });


    // Save functionality
// $(document).on('click', '.fa-save', function () {
//     const row = $(this).closest('tr');
//     let isValid = true;
//     let qty = parseFloat(row.find('input[name*="[qty]"]').val()) || 0;
//     let price = parseFloat(row.find('input[name*="[price]"]').val()) || 0;

//     if (qty <= 0 || price <= 0) {
//         alert('Quantity and Price must be greater than 0.');
//         isValid = false;
//     }

//     if (isValid) {
//         const total = qty * price;

//         row.find('[data-field="qty"] span').text(qty.toFixed(2));
//         row.find('[data-field="price"] span').text(price.toFixed(2));
//         row.find('[data-field="total"] span').text(total.toFixed(2));
//         row.find('[data-field="total"] input').val(total.toFixed(2));

//         row.find('span').show(); // Show text spans
//         row.find('input[type="number"]').hide(); // Hide input fields
//         $(this).removeClass('fa-save').addClass('fa-edit').css('color', 'blue');
//         recalculateTotals();
//     }
   // $(document).on('click', '.fa-edit', function () {
   //      const row = $(this).closest('tr');
   //      row.find('span').hide();
   //      row.find('input').show();
   //      $(this).removeClass('fa-edit').addClass('fa-save').css('color', 'green');
   //  });

    $(document).on('click', '.fa-save', function () {
        const row = $(this).closest('tr');
              let grandTotal = 0;
        let taxableAmount = 0;
        let totalGstAmount = 0;
        let totalCessAmount = 0;
 // Get updated values
    const updatedQty = parseFloat(row.find('td[data-field="qty"] input').val()) || 0;
    const updatedPrice = parseFloat(row.find('td[data-field="price"] input').val()) || 0;

    // Update the display span and the hidden input field
    row.find('td[data-field="qty"] span').text(updatedQty.toFixed(2)).show();
    row.find('td[data-field="qty"] input').val(updatedQty.toFixed(2)).attr('type', 'hidden');

    row.find('td[data-field="price"] span').text(updatedPrice.toFixed(2)).show();
    row.find('td[data-field="price"] input').val(updatedPrice.toFixed(2)).attr('type', 'hidden');

  
        const qty = parseFloat(row.find('input[name*="[qty]"]').val()) || 0;
        const price = parseFloat(row.find('input[name*="[price]"]').val()) || 0;
        const discount = parseFloat(row.find('input[name*="[discount]"]').val()) || 0;
        const gstRate = parseFloat(row.find('input[name*="[gst]"]').val()) || 0;
         const cessRate = parseFloat($(this).find('input[name*="[cess_rate]"]').val()) || 0;
        if (qty > 0 && price > 0) {
            const lineTotal = qty * price - ((price*discount)/100);
            const gstAmt = (lineTotal * gstRate) / 100;
            const cessAmt = (lineTotal * cessRate) / 100;

            // Determine CGST/SGST or IGST
            const customerState = $('#customer_billing_state').val();
             const businessState = $('#business_state').val();
            let cgstAmt = 0, sgstAmt = 0, igstAmt = 0;
            if (customerState === businessState) {
                cgstAmt = gstAmt / 2;
                sgstAmt = gstAmt / 2;
            } else {
                igstAmt = gstAmt;
            }

            const total = lineTotal + gstAmt + cessAmt;


       // Update visible <td> spans and hidden <input> fields
     
    // Update visible <span> and hidden <input> values
    row.find('td[data-field="qty"] span').text(qty.toFixed(2));
    row.find('td[data-field="qty"] input[name*="[qty]"]').val(qty.toFixed(2));

    row.find('td[data-field="price"] span').text(price.toFixed(2));
    row.find('td[data-field="price"] input[name*="[price]"]').val(price.toFixed(2));

 row.find('td[data-field="gst"] span').text(gstRate.toFixed(2));
  row.find('td[data-field="gst"] input[name*="[gst]"]').val(gstRate.toFixed(2));
    row.find('td[data-field="gst"] input[name*="[gst_amt]"]').val(gstAmt.toFixed(2));
      
    row.find('td[data-field="cgst"] span').text(cgstAmt.toFixed(2));
    row.find('td[data-field="cgst"] input[name*="[cgst]"]').val(cgstAmt.toFixed(2));

    row.find('td[data-field="sgst"] span').text(sgstAmt.toFixed(2));
    row.find('td[data-field="sgst"] input[name*="[sgst]"]').val(sgstAmt.toFixed(2));

    row.find('td[data-field="igst"] span').text(igstAmt.toFixed(2));
    row.find('td[data-field="igst"] input[name*="[igst]"]').val(igstAmt.toFixed(2));

    row.find('td[data-field="cess_rate"] span').text(cessAmt.toFixed(2));
    row.find('td[data-field="cess_rate"] input[name*="[cess_rate]"]').val(cessRate.toFixed(2));
 row.find('td[data-field="cess_rate"] input[name*="[cess_amt]"]').val(cessAmt.toFixed(2));

    row.find('td[data-field="total"] span').text(total.toFixed(2));
    row.find('td[data-field="total"] input[name*="[total]"]').val(total.toFixed(2));


console.log('CGST Input:', row.find('td[data-field="cgst"] input[name*="[cgst]"]').val());
console.log('SGST Input:', row.find('td[data-field="sgst"] input[name*="[sgst]"]').val());
console.log('IGST Input:', row.find('td[data-field="igst"] input[name*="[igst]"]').val());
console.log('Cess Input:', row.find('td[data-field="cess_rate"] input[name*="[cess_rate]"]').val());
console.log('Total Input:', row.find('td[data-field="total"] input[name*="[total]"]').val());


            taxableAmount += lineTotal;
            totalGstAmount += gstAmt;
            totalCessAmount += cessAmt;
            grandTotal += total;



        // Switch back to view mode
        row.find('span').show();
        row.find('input').hide();
        $(this).removeClass('fa-save').addClass('fa-edit').css('color', 'blue');
 $('#final_taxable_amt').text(taxableAmount.toFixed(2));
        $('#final_gst_amount').text(totalGstAmount.toFixed(2));
        $('#final_cess_amount').text(totalCessAmount.toFixed(2));
        $('#gtotal').text(grandTotal.toFixed(2));

            // Update hidden inputs
    $('input[name="total_amount"]').val(taxableAmount.toFixed(2));
    $('input[name="total_gst"]').val(totalGstAmount.toFixed(2));
    $('input[name="total_cess"]').val(totalCessAmount.toFixed(2));
    $('input[name="grand_total"]').val(grandTotal.toFixed(2));

        
            recalculateTotals();
        }
    });

    // Delete functionality
    $(document).on('click', '.fa-trash', function () {
        if (confirm('Are you sure you want to delete this item?')) {
            $(this).closest('tr').remove();
            recalculateTotals();
        }
    });

      


// function recalculateTotals() {
//     let grandTotal = 0;
//     let taxableAmount = 0;

//     $('#item-list tbody tr').each(function () {
//         const qty = parseFloat($(this).find('input[name*="[qty]"]').val()) || 0;
//         const price = parseFloat($(this).find('input[name*="[price]"]').val()) || 0;
//         const discount = parseFloat($(this).find('input[name*="[discount]"]').val()) || 0;
//         const gst = parseFloat($(this).find('input[name*="[gst]"]').val()) || 0;

//         // Calculate line total (taxable amount)
//         const lineTotal = qty * price - discount;
//         taxableAmount += lineTotal;

//         // Add GST to calculate grand total
//         const lineTotalWithGst = lineTotal + gst;
//         grandTotal += lineTotalWithGst;

//         // Update the total in the row
//         $(this).find('[data-field="total"] span').text(lineTotalWithGst.toFixed(2));
//         $(this).find('[data-field="total"] input').val(lineTotalWithGst.toFixed(2));
//     });

//     // Update the UI elements for totals
//     $('#final_taxable_amt').text(taxableAmount.toFixed(2)); // Taxable Amount
//     $('#gtotal').text(grandTotal.toFixed(2)); // Grand Total
// }


    function recalculateTotals() {
        const businessState = $('#business_state').val();
        let grandTotal = 0;
        let taxableAmount = 0;
        let totalGstAmount = 0;
        let totalCessAmount = 0;

        $('#item-list tbody tr').each(function () {
            const qty = parseFloat($(this).find('input[name*="[qty]"]').val()) || 0;
            const price = parseFloat($(this).find('input[name*="[price]"]').val()) || 0;
            const discount = parseFloat($(this).find('input[name*="[discount]"]').val()) || 0;
            const gstRate = parseFloat($(this).find('input[name*="[gst]"]').val()) || 0;
            const cessRate = parseFloat($(this).find('input[name*="[cess_rate]"]').val()) || 0;

            const lineTotal = qty * price - discount;
            const gstAmt = (lineTotal * gstRate) / 100;
            const cessAmt = (lineTotal * cessRate) / 100;

            // Determine CGST/SGST or IGST
            const customerState = $('#customer_billing_state').val();
            let cgstAmt = 0, sgstAmt = 0, igstAmt = 0;
            if (customerState === businessState) {
                cgstAmt = gstAmt / 2;
                sgstAmt = gstAmt / 2;
            } else {
                igstAmt = gstAmt;
            }

            const total = lineTotal + gstAmt + cessAmt;

            taxableAmount += lineTotal;
            totalGstAmount += gstAmt;
            totalCessAmount += cessAmt;
            grandTotal += total;

            alert("cess amount" + cessAmt);
            alert("sgst" + sgstAmt);
            alert("cgst "+ cgstAmt);
           
  // Update row UI
        // $(this).find('td[data-field="qty"]').text(qty.toFixed(2));
        // $(this).find('td[data-field="price"]').text(price.toFixed(2));
        // $(this).find('td[data-field="cgst"]').text(cgstAmt.toFixed(2));
        // $(this).find('td[data-field="sgst"]').text(sgstAmt.toFixed(2));
        // $(this).find('td[data-field="igst"]').text(igstAmt.toFixed(2));
        // $(this).find('td[data-field="cess"]').text(cessAmt.toFixed(2));
        // $(this).find('td[data-field="total"]').text(total.toFixed(2));
 
    // Update hidden inputs
    
    //     $(this).find('input[name*="[cgst]"]').val(cgstAmt.toFixed(2));
    //     $(this).find('input[name*="[sgst]"]').val(sgstAmt.toFixed(2));
    //     $(this).find('input[name*="[igst]"]').val(igstAmt.toFixed(2));
    //     $(this).find('input[name*="[cess_rate]"]').val(cessAmt.toFixed(2));
    //     $(this).find('input[name*="[total]"]').val(total.toFixed(2));


    //     $(this).find('input[name*="[cgst]"]').each(function () {
    // console.log("CGST Input Found: ", $(this).attr('name'));
// });

        });
        // Update UI
 
        // $('#final_taxable_amt').text(taxableAmount.toFixed(2));
        // $('#final_gst_amount').text(totalGstAmount.toFixed(2));
        // $('#final_cess_amount').text(totalCessAmount.toFixed(2));
        // $('#gtotal').text(grandTotal.toFixed(2));
    }

    function resetFields() {
        $('#customer_dp').show();
        $('#customer_data').empty();
        $("#item-list tbody").empty();
        $('#sub_total').text('0.00');
        $('#gtotal').text('0.00');
    }
});



</script>
</body>

</html>