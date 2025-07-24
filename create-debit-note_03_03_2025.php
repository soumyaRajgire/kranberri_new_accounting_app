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
  <title>iiiQbets - Debit Note</title>
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
                            <h4 class="m-b-10">Create Debit Note</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Create Debit Note</a></li>
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
              
            <form action="debitnotedb.php" method="POST" enctype="multipart/form-data">
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
<?php echo htmlspecialchars($user['name']); ?><br/>
<?php echo htmlspecialchars($user['address']); ?><br/>
Email: <?php echo htmlspecialchars($user['email']); ?><br/>
Phone: <?php echo htmlspecialchars($user['phone']); ?><br/>
GSTIN: <?php echo htmlspecialchars($user['gstin']); ?><br/>
<input type="text" name="business_state" id="business_state" value="<?php echo htmlspecialchars($user['state']); ?>" hidden>

</h6>
</div>
                         <div class="col-md-4 pt-1">
                    <!-- Debit Note Number -->
                    <div class="py-1 input-group">
                      <?php
                      $result1 = mysqli_query($conn, "SELECT id FROM debit_note WHERE id=(SELECT max(id) FROM debit_note)");
                      if ($row1 = mysqli_fetch_array($result1)) {
                        $id = $row1['id'] + 1;
                        $debit_note_code = "DNOTE" . str_pad($id, 5, '0', STR_PAD_LEFT);
                      } else {
                        $debit_note_code = "DNOTE00001";
                      }
                      ?>
                      <input class="form-control" type="text" id="debitNoteNo" value="<?php echo $debit_note_code; ?>" name="debitNoteNo"  required/>
                      <label class="form-control col-sm-5" for="debitNoteNo">Debit Note No</label>
                    </div>

                    <!-- Debit Note Date -->
                    <div class="py-1 input-group">
                      <input class="form-control" type="date" id="debitNoteDate" name="debitNoteDate" required />
                      <label class="form-control col-sm-5" for="debitNoteDate">Note Date</label>
                    </div>
                    
                    <!-- Due Date -->
                    <div class="py-1 input-group">
                    <select class="form-control" id="purchaseInvoiceDropdown" name="purchaseInvoiceDropdown">
    <option value="">Select Purchase Invoice</option>
    <?php
    $sql = "SELECT id, invoice_code FROM pi_invoice";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['id']}'>{$row['invoice_code']}</option>";
    }
    ?>
</select>

                        <label class="form-control col-sm-5" for="purchaseInvoiceDropdown">Purchase Invoice</label>
                    </div>
                </div>
                </div>

<div class="row" id="customer_data"></div>


            <div class="row" id="customer_dp">
              <div class="col-md-4 border-left border-bottom border-dark p-3">
                <div>
                   
                  <h6>Supplier info</h6>
                    <div class="form-group" >
    <!-- <input class="form-control" list="customer_name" name="customer_name_choice" id="customer_name_choice" onchange="checknamevalue(this.value)" autocomplete="off" /> -->
                            <!-- <datalist name="customer_name" id="customer_name" placeholder="Select Customer" >
                             
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
                            <input type="hidden" name="cst_mstr_id" id="cst_mstr_id"  value="<?php echo $row["id"] ?>"> -->
                          
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
  <i class="fa fa-plus"></i>&nbsp;<span class="pinvoice-text">Create Debit Note</span>
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
    $('#customer_name_choice, #product_choice, #addmore').prop('disabled', true);

    // When an invoice is selected, fetch data and enable fields
    $('#purchaseInvoiceDropdown').change(function () {
        const invoiceId = $(this).val();
        if (invoiceId) {
            fetchInvoiceData(invoiceId);
        } else {
            resetFields(); // Reset fields if no invoice is selected
        }
    });

    function fetchInvoiceData(invoiceId) {
        $.ajax({
            url: 'get_debit_invoice_data.php',
            type: 'GET',
            data: { invoiceID: invoiceId },
            success: function (response) {
                try {
                    if (response.status === 'error') {
                        console.error("Error:", response.message);
                        alert(response.message);
                        return;
                    }

                    // Populate supplier and product list
                    populateCustomerData(response.invoice);
                    populateProductList(response.items, response.additional_charges, response.invoice);

                } catch (error) {
                    console.error("Error processing response:", error);
                    alert("An error occurred while processing the response. Please try again.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching invoice data:", error);
                console.log("Response text:", xhr.responseText);
            }
        });
    }

      function populateCustomerData(customer) {
        $('#customer_dp').hide();
        $('#customer_data').html(`
           <div class="col-md-4 border-left border-bottom border-dark p-3">
                <h6>Customer Info</h6>
 <input type="hidden" name="cst_mstr_id" id="cst_mstr_id" value="${customer.customer_id}">
                <input type="hidden" name="customer_email" id="customer_email" value="${customer.email}">
                 <input type="hidden" name="customer_name_choice" id="customer_name_choice" value="${customer.customerName}">
                <span>${customer.customerName}</span><br/>
                <span>Email: ${customer.email}</span><br/>
                <span>Phone: ${customer.mobile}</span><br/>
                <span>GSTIN: ${customer.gstin || "N/A"}</span>
            </div>
            <div class="col-md-4 border-left border-bottom border-dark p-3">
                <h6>Billing Address</h6>
                <span>${customer.b_address_line1 || '<span style="color:red;">Address Line1</span>'}</span><br/>
                <span>${customer.b_address_line2 || '<span style="color:red;">Address Line2</span>'}</span><br/>
                <span>${(customer.b_city || '<span style="color:red;">City</span>')} - ${(customer.b_pincode || '<span style="color:red;">Pincode</span>')}</span><br/>
                <span>State: ${customer.b_state || '<span style="color:red;">State</span>'}</span>
          <input type="hidden" name="customer_billing_state" id="customer_billing_state" value="${customer.b_state || ''}">
     
            </div>
            <div class="col-md-4 border-left border-bottom border-right border-dark p-3">
                <h6>Shipping Address</h6>
                <span>${customer.s_address_line1 || '<span style="color:red;">Address Line1</span>'}</span><br/>
                <span>${customer.s_address_line2 || '<span style="color:red;">Address Line2</span>'}</span><br/>
                <span>${(customer.s_city || '<span style="color:red;">City</span>')} - ${(customer.s_pincode || '<span style="color:red;">Pincode</span>')}</span><br/>
                <span>State: ${customer.s_state || '<span style="color:red;">State</span>'}</span>
        <input type="hidden" name="customer_shipping_state" id="customer_shipping_state" value="${customer.s_state || ''}">
     
            </div>
        `);
    }

function populateProductList(items, additionalCharges, invoice) {
    $('#box_loop_1').hide();

    // Populate items
    $("#item-list tbody").empty();
    items.forEach((item, index) => { // Add `index` as the second argument
        const rowHtml = `
            <tr>
                <td data-field="product">${item.product}</td>
                <td data-field="prod_desc">${item.prod_desc}</td>
                   <input type="hidden" name="products[${index}][product]" value="${item.product}">
                    <input type="hidden" name="products[${index}][prod_desc]" value="${item.prod_desc}">
              
                <td data-field="qty">
                    <span>${parseFloat(item.qty).toFixed(2)}</span>
                    <input type="hidden" class="form-control" name="products[${index}][qty]" value="${parseFloat(item.qty).toFixed(2)}" data-original="${item.qty}" >
                </td>
                <td data-field="price">
    <span>${parseFloat(item.price).toFixed(2)}</span>
  
    <input type="hidden" name="products[${index}][price]" value="${parseFloat(item.price).toFixed(2)}">
</td>
                <td data-field="discount">
                  <span>  ${parseFloat(item.discount).toFixed(2)}</span>
                    <input type="hidden" name="products[${index}][discount]" value="${parseFloat(item.discount).toFixed(2)}">
                </td>
                <td data-field="gst">
                   <span> ${parseFloat(item.gst).toFixed(2)}</span>
                    <input type="hidden" name="products[${index}][gst]" value="${parseFloat(item.gst).toFixed(2)}">
                     <input type="hidden" name="products[${index}][gst_amt]" value="${parseFloat(item.gst_amt).toFixed(2)}">
                </td>
                <td data-field="cgst">
                   <span> ${parseFloat(item.cgst).toFixed(2)}</span>
                    <input type="hidden" name="products[${index}][cgst]" value="${parseFloat(item.cgst).toFixed(2)}">
                </td>
                <td data-field="sgst">
                   <span> ${parseFloat(item.sgst).toFixed(2)}</span>
                    <input type="hidden" name="products[${index}][sgst]" value="${parseFloat(item.sgst).toFixed(2)}">
                </td>
                <td data-field="igst">
                   <span> ${parseFloat(item.igst).toFixed(2)}</span>
                    <input type="hidden" name="products[${index}][igst]" value="${parseFloat(item.igst).toFixed(2)}">
                </td>
                <td data-field="cess_rate">
                   <span> ${parseFloat(item.cess_rate).toFixed(2)}</span>
                    <input type="hidden" name="products[${index}][cess_rate]" value="${parseFloat(item.cess_rate).toFixed(2)}">
                     <input type="hidden" name="products[${index}][cess_amt]" value="${parseFloat(item.cess_amt).toFixed(2)}">
                </td>
                <td data-field="total">
                   <span> ${parseFloat(item.total).toFixed(2)}</span>
                    <input type="hidden" name="products[${index}][total]" value="${parseFloat(item.total).toFixed(2)}">
                </td>
                <td>
                    <a><i class="fa fa-edit" style="color:blue; cursor:pointer;"></i></a>
                    <a><i class="fa fa-trash" style="color:red; cursor:pointer;"></i></a>
                </td>
            </tr>
        `;
        $("#item-list tbody").append(rowHtml);
    });

    // Populate additional charges
    const additionalChargesList = $('.additional-charges-list');
    additionalChargesList.empty();
    additionalCharges.forEach((charge) => {
        additionalChargesList.append(`<p>${charge.charge_type}: ${parseFloat(charge.charge_price).toFixed(2)}</p>`);
    });

    // Populate totals
    $('#final_taxable_amt').text(parseFloat(invoice.total_amount).toFixed(2));
    $('#final_gst_amount').text(parseFloat(invoice.total_gst).toFixed(2));
    $('#final_cess_amount').text(parseFloat(invoice.total_cess).toFixed(2));
    $('#gtotal').text(parseFloat(invoice.grand_total).toFixed(2));

    // Add hidden inputs for totals
    $('#item-list').append(`
        <input type="hidden" name="total_amount" value="${parseFloat(invoice.total_amount).toFixed(2)}">
        <input type="hidden" name="total_gst" value="${parseFloat(invoice.total_gst).toFixed(2)}">
        <input type="hidden" name="total_cess" value="${parseFloat(invoice.total_cess).toFixed(2)}">
        <input type="hidden" name="grand_total" value="${parseFloat(invoice.grand_total).toFixed(2)}">
    `);
}


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

    // Save functionality
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
          const discount_amt  = price*discount/100 ;
            const lineTotal = qty * price - discount_amt;
            const gstAmt = (lineTotal * gstRate) / 100;
            const cessAmt = (lineTotal * cessRate) / 100;

            // Determine CGST/SGST or IGST
            const customerState = $('#customer_billing_state').val();
             const businessState = $('#business_state').val();
             console.log(customerState);
             console.log(businessState);
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


    // row.find('td[data-field="disocunt"] span').text(disocunt.toFixed(2));
    // row.find('td[data-field="discount"] input[name*="[discount]"]').val(discount.toFixed(2));
     
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
          

        });
        // Update UI

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