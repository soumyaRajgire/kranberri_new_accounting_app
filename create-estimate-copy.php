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
  <title>iiiQbets</title>
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
                    <div class="col-md-8 border-right border-dark" >
                        <h6 style="float:left;" class="pt-2">KRIKA MKB CORPORATION PRIVATE LIMITED<br/>120 Newport Center Dr, Newport Beach, CA 92660<br/>
                        Email: abhijith.mavatoor@gmail.com<br/>
Phone: 9481024700<br/>
GSTIN: 29AAICK7493G1ZX<br/>
                        </h6> </div>
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
    
        <div class="row border-dark border-right border-left" id="box_loop_1">
           <!-- <div class="btn-group float-right"> -->
   
<!-- </div>  -->
            <div class="col-md-3 p-3 border-right border-left border-bottom">
                 <button type="button" class="btn btn-sm dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0px;
    /* height: 23px; */
    margin-top: -17px;
       margin-bottom: 2px;
    margin-right: -12px;
    font-size: 11px;
    font-weight: 900;
    color: blue;"><i class="fa fa-plus"></i> New Item</button>

    <div class="dropdown-menu">
        <a class="dropdown-item" href="#" data-value="products">Products</a>
        <a class="dropdown-item" href="#" data-value="services">Services</a>
    </div>

            <!--    <div class="btn-group float-right">
    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">New Item</button>

    <div class="dropdown-menu">
        <a class="dropdown-item" href="#" data-value="products">Products</a>
        <a class="dropdown-item" href="#" data-value="services">Services</a>
    </div>
</div> -->
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
                            <!-- <input type="text" style="display:none;" class="form-control" name="description" id="description" /> -->
                                       </div>
              <div class="col-md-4 p-3 border-right border-bottom">
                <textarea name="prod_desc" id="prod_desc" rows="1" class="form-control" cols="20" placeholder="Product description"></textarea>
              </div>
              <div class="col-md-2 p-3 border-right border-bottom">
                <input class="form-control" type="number" min="1" name="qty" id="qty" value="1" placeholder="quantity">
              </div>
              <div class="col-md-2 p-3 border-right border-bottom" id="pricevalbox">
                <input type="text" class="form-control" name="netprice" id="netprice" value="" hidden >
               <input type="text" class="form-control" name="price" id="price" value="" >
               
                <input type="text" name="gst" id="gst" value="" hidden>
                <input type="text" name="in_ex_gst" id="in_ex_gst" value="" hidden>
              </div>

              <div class="col-md-1 p-3 border-right border-bottom">
                <button type="button" class="btn btn-success btn-sm" name="Addmore" id="addmore" onclick="add_more()">Add</button>
              </div>
            </div>                    
             <div class="row border border-dark">
                <table class="table table-bordered" id="item-list">
                  <colgroup>
                    <col width="18%">
                    <col width="37%">
                    <col width="13%">
                    <col width="14%">
                    <col width="18%">
                  </colgroup>
                  <thead>
                    <tr>
                      <th class="text-center">Product </th>
                      <th class="text-center">Product Desc </th>
                      <th class="text-center">Price</th>
                      <th class="text-center">Quantity</th>
                      <th class="text-center">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>

                    <tr>
                        <th colspan="2" rowspan="3"><textarea class="form-control" placeholder="Note" name="note" id="note" cols="20" style="width: -webkit-fill-available;height: 112px;"><?php echo isset($note) ? $note : ''; ?></textarea></th>
                      <th class="text-right" colspan="2">Sub Total</th>
                      <th class="text-right" id="sub_total">0
                        <input type="hidden" name="sub_total" value="0">
                      </th>
                    </tr>
                 
                    <tr>
                      <th class="text-right" colspan="2">Additional Payable</th>
                      <!-- <th class="text-right" id="tax_rate"></th> -->
                      <th><input type="number" class="form-control" name="pack_price" id="pack_price" value="0" onchange="calc_total();"></th>
                    </tr>
                    <tr>
                      <th class="text-right" colspan="2">Grand Total</th>
                      <th class="text-right" id="gtotal">0</th>
                      <input type="hidden" name="total_amount" value="0">
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
                        <!-- <div class="row">
                          <div class="col-md-7">
                            <div class="form-group">
                              <label for="remarks" class="control-label">Note</label>
                              <textarea name="note" id="" cols="15" rows="1" class="form-control form no-resize summernote"><?php echo isset($note) ? $note : ''; ?></textarea>
                            </div>
                          </div>
                          <div class="col-md-7">
                            <div class="form-group">
                              <label for="remarks" class="control-label">Remarks</label>
                              <textarea name="remarks" id="" cols="30" rows="2" class="form-control form no-resize summernote"><?php echo isset($remarks) ? $remarks : ''; ?></textarea>
                            </div>
                          </div>
                        </div> -->

                        <!-- <div class="container">
                          <div class="row">
                            <div class="col-md-5 border border-dark p-3">
                              <div style="height:150px;">
                                <h2 style="font-size:20px;">
                                  Notes
                                </h2>
                              </div>
                            </div>
                            <div class="col-md-5 border border-dark p-3">
                              <div style="height:150px;">
                                <h2 style="font-size:20px;" class="pt-3">
                                  Adjustment
                                </h2>

                                <div class="pt-3">
                                  <select id="additionalCharges" name="additionalCharges" style="height:30px;width:300px;">
                                    <option value="Additional Chargese">Additional Charges</option>
                                    <option value="shipping">Shipping</option>
                                    <option value="tax">Tax</option>
                                    <option value="handling">Handling</option>
                                    <!-- Add more options as needed -->
                                  <!--</select>
                                </div>

                                <h2 class="pt-3" style="font-size:20px;">Total</h2>

                              </div>
                            </div>

                            <div class="col-md-2 border border-dark p-3">
                              <div class="input-row">
                                <input type="text" class="pt-3" id="additionalCharges" name="additionalCharges" placeholder="0"></br></br>
                                <input type="text" class="pt-3" id="shipping" name="shipping" placeholder="0"></br></br>
                                <input type="text" class="pt-3 " id="tax" name="tax" placeholder="0"> 
                              </div>
                            </div>
                          </div>
                        </div> -->

                        <!-- <div class="row contai"> -->
                        <!-- <a type="submit" class="btn btn-success"  href="preview_quotation_pdf.php">Preview</a> 
              <button  type="button" class="btn btn-success" id="prv" data-toggle="modal" data-target="#myModal">Preview</button>  -->
                <div class="row col-md-12 text-center pt-3">
                    <div class="col-md-2"><input type="submit" class="btn btn-primary " name="submit" value="Submit" /></div>
                     <div class="col-md-2"><input type="reset" class="btn btn-danger " name="cancel" value="Cancel" /></div>
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
    count = 1;
    itemno = 1;

    function add_more() {

      prod_desc = $('#prod_desc').val();
      product = $('#product_choice').val();
      productid = $('#productid').val();
      // if (product === "Others") {
      //   product = $('#otherproduct').val();
      // }
      qty = $('#qty').val();
      price = $('#price').val();
      gst = $('#gst').val();
      netprice =$('#netprice').val();
      in_ex_gst =$('#in_ex_gst').val();
      productid = $('#productid').val();
      // if (price === "Others") {
      //   price = $('#otherprice').val();
      // }

      // alert(prod_desc);
      if(in_ex_gst === "inclusive of GST")
      {
 total = parseFloat(netprice) * parseFloat(qty);
      }
      else if(in_ex_gst === "exclusive of GST")
      {
         total = parseFloat(price) * parseFloat(qty);
      }
      total = parseFloat(price) * parseFloat(qty);
      cgst = ((parseFloat(gst) / 2) * parseFloat(total) / 100).toFixed(2);
      console.log(cgst);
sgst = ((gst / 2) * total / 100).toFixed(2);
console.log(sgst);


      var html = '<tr><td>' + product + '</td><td>' + prod_desc + '</td><td>' + price + '</td><td>' + qty + '</td><td>' + total + '<textarea  name="proddesc[]" id="proddesc' + count + '" value="' + prod_desc + '" hidden >' + prod_desc + '</textarea><input type="number" name="itemnum[]" id="itemnumval' + count + '" value="' + itemno + '" hidden/><input type="number" name="gstval[]" id="gstval' + count + '" value="' + gst + '" hidden/><input type="number" name="netpriceval[]" id="netpriceval' + count + '" value="' + netprice + '" hidden/><input type="text" name="in_ex_gst_val[]" id="in_ex_gst_val' + count + '" value="' + in_ex_gst + '" hidden/><input type="number" name="cgstval[]" id="cgstval' + count + '" value="' + cgst + '" hidden/><input type="number" name="sgstval[]" id="sgstval' + count + '" value="' + sgst + '" hidden/><input  name="products[]" id="productsval' + count + '" value="' + product + '" hidden/><input  name="productids[]" id="productidsval' + count + '" value="' + productid + '" hidden/><input type="number" name="qtyvalue[]" id="qtyvalueval' + count + '" value="' + qty + '" hidden/><input type="number" name="priceval[]" id="priceval' + count + '" value="' + price + '" hidden/><input type="number" name="total[]" id="total' + count + '" value="' + total + '" hidden/></td><td><button class="btn btn-sm btn-outline-danger" type="button" onclick="rem_item($(this))"><i class="fa fa-trash" style="color:red;"></i></button></td></tr>';
      // <td><button class="btn btn-sm btn-outline-info" type="button" onclick="edit_item($(this))"><i class="fa fa-trash" style="color:skyblue;"></i></button></td>
      $('#item-list tbody').append(html);
      $('#prod_desc').val('').trigger('change');
      $('#product_choice').val('').trigger('change');
      $('#qty').val(1).trigger('change');
      $('#price').val('').trigger('change');
      count++;
      console.log(count);
      // alert(count);
      itemno++;
      // alert(products[]);
      // alert(itemnum[]);
      // alert(qtyvalue[]);
      calc_total();
      // p= $('#products').val();
      //console.log(p);
      //var input = document.getElementById('products').value;

      // for (var i = 0; i < input.length; i++) {
      //     var a = input[i];
      //     k = k + "products[" + i + "].value= "
      //                        + a.value + " ";
      // }

      //document.getElementById("arrPrint").innerHTML = input;
      // document.getElementById("po").innerHTML = "Output";

    }

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
        $.ajax({
          url: 'get_customer_data.php',
          Type: "GET",
          //data:{"cat_id" : cat_id, "cat_type":cat_type}
          data: {
            "customername": customername,"mobileNumber":mobileNumber
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

                // Assign GST and price values to their respective input fields
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

</body>

</html>