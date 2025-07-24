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
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    	<![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <?php include("header_link.php");?>
   
    
    

</head>
<body class="">
	<!-- [ Pre-loader ] start -->
	 
     <?php include("menu.php");?>
	
	
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
                        <!-- <h5>Register Student Details for Admission Enquiry</h5> -->
                        <!-- <span class="d-block m-t-5">use class <code>table-striped</code> inside table element</span> -->
                        <a  href="view-quotation.php" class="btn btn-info" style="color: #fff !important;float:right;" /> View Quotation</a>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                          
  <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    
                    <div class="card-body">
                          <form action="quotationdb.php" method="POST" enctype="multipart/form-data">
          <div class="row">
           <div class="col-md-5 form-group">
              <label>Customer Name</label>
              <input class="form-control"list="customer_name" name="customer_name_choice" id="customer_name_choice" onchange="checknamevalue(this.value)"/>
              <datalist name="customer_name" id="customer_name" >
                <!-- <option value="Others"> -->
                  <?php
                     $sql="select * from customer_master";  
                    $result=$conn->query($sql);
                    if($result->num_rows>0)
                    {
                    while($row = mysqli_fetch_assoc($result)) 
                    {
                          ?>
                              <option value="<?php echo $row["customerName"]?>">
                               <?php
                      }
                    }
                        ?>
                       
                            </datalist><br/>
                            <!-- <input class="form-control" type="text" name="othercustomername" id="othercustomername"  placeholder="Name" style="display: none;" > -->
                        </div>
                        <div class="col-md-5 form-group">
                            <label>Customer Email</label>
                            <input class="form-control" type="email" name="customer_email"  placeholder="Email">
                        </div>
                       </div>       
                       
                    
                    
                            <hr>
        <h4>Add Product</h4>
        <div class="row col-md-12"  id="box_loop_1">
          <input type="number" name="itemno" id="itemno" select-group=""  data-count=1 hidden/>
            <!-- <div class="col-md-2"> 
                <input type="number" class="form-control" name="itemno" id="itemno" select-group=""  data-count=1  placeholder="Sl.No" hidden/>
            </div> -->
            <div class="col-md-3"> 
              <input class="form-control" list="product" name="product_choice" id="product_choice"  placeholder="Product" />
                <datalist name="product" id="product">
                               <!-- <option value="">Select Products </option> -->
                               <!-- <option value="Others"> -->
                               <?php
                           $sql="select * from inventory_master where inventory_type='Sales Catalog'";
                    
                    
                    $result=$conn->query($sql);
                    if($result->num_rows>0)
                    {
                    while($row = mysqli_fetch_assoc($result)) 
                    {
                          ?>
                              <option value="<?php echo $row["name"]?>">
                               <?php
                      }
                    }
                        ?>
                       
                            </datalist><br/>
                            <!-- <input type="text" style="display:none;" class="form-control" name="otherproduct" id="otherproduct" /><br/> -->
                
            </div>
             <div class="col-md-4">
               <textarea name="prod_desc" id="prod_desc" rows="1" class="form-control" cols="20" placeholder="Product description"></textarea>
             </div>
            <div class="col-md-2" style="width:13%;"> 
                <input class="form-control" type="number" name="qty" id="qty" placeholder="quantity">
            </div>
            <div class="col-md-2" id="pricevalbox"> 
               

               
            </div>
              
               <div class="col-md-1">
               <!-- <div class="control-group"> -->
                <button  type="button" class="btn btn-success" name="Addmore" id="addmore" onclick="add_more()">Add</button>
                <!-- </div> -->
              </div> 
            </div>
            <!-- <div class="row">
              <div class="col-md-6">
                <label class="control-label">Remark</label>
                <textarea class="form-control" name="remark" placeholder="Remark"></textarea>
              </div>
            </div> -->
<br/><br/>
            <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered" id="item-list">
              <colgroup>
                <!-- <col hidden> -->
                <col width="18%">
                <col width="37%">
                <col width="13%">
                <col width="14%">
                <col width="18%">
              </colgroup>
              <thead>
                <tr>
                  <!-- <th class="text-center" hidden></th> -->
                  <!-- <th class="text-center">Sl.No</th> -->
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
                  <th class="text-right" colspan="4">Sub Total</th>
                  <th class="text-right" id="sub_total">0</th>
                  <th><input type="hidden" name="sub_total" value="0"></th>
                </tr>
                <tr>
                  <th class="text-right" colspan="4">Tax Rate</th>
                   <!-- <th class="text-right" id="tax_rate"></th> -->
                  <th><input type="number" name="tax_rate"  id="tax_rate"value="2" onchange="calc_total();"></th>
                </tr>
                 <tr>
                  <th class="text-right" colspan="4">Tax Amount</th>
                   <!-- <th class="text-right" id="tax_rate"></th> -->
                  <th><input type="number" name="tax_amount"  id="tax_amount" value=""></th>
                </tr>
                <!-- <tr>
                  <th class="text-right" colspan="4">Tax</th>
                  <th class="text-right" id="tax">0</th>
                  <th></th>
                </tr> -->
                <tr>
                  <th class="text-right" colspan="4">Packing & Forwarding Extra</th>
                   <!-- <th class="text-right" id="tax_rate"></th> -->
                  <th><input type="number" name="pack_price"  id="pack_price" value="0" onchange="calc_total();"></th>
                </tr>
                <tr>
                  <th class="text-right" colspan="4">Grand Total</th>
                  <th class="text-right" id="gtotal">0</th>
                  <th><input type="hidden" name="total_amount" value="0"></th>
                </tr>
                <!-- <tr>
                  <th class="text-right" colspan="4">Note :<input type="text" name="note"/></th>
                 
                </tr> -->
              </tfoot>
            </table>
          </div>
        </div>
        <div class="row">
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
        </div>

            <br/><br/>
            <!-- <div class="row contai"> -->
               <!-- <a type="submit" class="btn btn-success"  href="preview_quotation_pdf.php">Preview</a> 
              <button  type="button" class="btn btn-success" id="prv" data-toggle="modal" data-target="#myModal">Preview</button>  -->
                   <input type="submit" class="btn btn-primary " name="submit"  value="Submit"/>
                        <input type="reset" class="btn btn-danger " name="cancel" value="Cancel"/>
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
<!-- [ Main Content ] end -->
    <!-- Warning Section start -->
    <!-- Older IE warning message -->
    <!--[if lt IE 11]>
        <div class="ie-warning">
            <h1>Warning!!</h1>
            <p>You are using an outdated version of Internet Explorer, please upgrade
               <br/>to any of the following web browsers to access this website.
            </p>
            <div class="iew-container">
                <ul class="iew-download">
                    <li>
                        <a href="http://www.google.com/chrome/">
                            <img src="assets/images/browser/chrome.png" alt="Chrome">
                            <div>Chrome</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.mozilla.org/en-US/firefox/new/">
                            <img src="assets/images/browser/firefox.png" alt="Firefox">
                            <div>Firefox</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.opera.com">
                            <img src="assets/images/browser/opera.png" alt="Opera">
                            <div>Opera</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.apple.com/safari/">
                            <img src="assets/images/browser/safari.png" alt="Safari">
                            <div>Safari</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="assets/images/browser/ie.png" alt="">
                            <div>IE (11 & above)</div>
                        </a>
                    </li>
                </ul>
            </div>
            <p>Sorry for the inconvenience!</p>
        </div>
    <![endif]-->
    <!-- Warning Section Ends -->
  <script type="text/javascript">
          count=1;
           itemno = 1;
          function add_more()
          {
           
            prod_desc = $('#prod_desc').val();
            product = $('#product_choice').val();
            // if(product === "Others")
            // {
            //   product = $('#otherproduct').val();
            // }
            qty = $('#qty').val();
            price = $('#price').val();
            // if(price === "Others")
            // {
            //   price = $('#otherprice').val();
            // }
            
            // alert(prod_desc);
            total= parseFloat(price) * parseFloat(qty);
            var html= '<tr><td>'+product+'</td><td>'+prod_desc+'</td><td>'+price+'</td><td>'+qty+'</td><td>'+total+'<textarea  name="proddesc[]" id="proddesc'+count+'" value="'+prod_desc+'" hidden >'+prod_desc+'</textarea><input type="number" name="itemnum[]" id="itemnumval'+count+'" value="'+itemno+'" hidden/><input  name="products[]" id="productsval'+count+'" value="'+product+'" hidden/><input type="number" name="qtyvalue[]" id="qtyvalueval'+count+'" value="'+qty+'" hidden/><input type="number" name="priceval[]" id="priceval'+count+'" value="'+price+'" hidden/><input type="number" name="total[]" id="total'+count+'" value="'+total+'" hidden/></td><td><button class="btn btn-sm btn-outline-danger" type="button" onclick="rem_item($(this))"><i class="fa fa-trash" style="color:red;"></i></button></td></tr>';
            // <td><button class="btn btn-sm btn-outline-info" type="button" onclick="edit_item($(this))"><i class="fa fa-trash" style="color:skyblue;"></i></button></td>
             $('#item-list tbody').append(html);
             $('#prod_desc').val('').trigger('change');
            $('#product_choice').val('').trigger('change');
            $('#qty').val('').trigger('change');
            $('#price').val('').trigger('change');
            count++;
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
          function calc_total(){
    var total = 0;
    var total1=0;
    var tax_rate = $('#tax_rate').val();
    var pack_price = $('#pack_price').val();
    console.log(tax_rate);
    var tax_rate = tax_rate /100;
    $('#item-list tbody tr').each(function(){
      var tr = $(this)
        total += parseFloat(tr.find('[name="total[]"]').val());
        console.log(total1);
      // total += $('#total').val();
       //console.log(total);
        //console.log(tr);
    })
    $('[name="sub_total"]').val(total)
    $('#sub_total').text(parseFloat(total).toLocaleString('en-US'))
    var pack_total = parseFloat(pack_price) +  parseFloat(total);
    var tax = parseFloat(pack_total) * parseFloat(tax_rate);
    var gtotal = parseFloat(tax) + parseFloat(pack_total) ;
     var gt_round =  Math.round(gtotal);
     // alert(gt_round);
     $('[name="total_amount"]').val(gt_round);
     var tax_amount_round =  Math.round(tax);
      $('[name="tax_amount"]').val(tax_amount_round);
    $('#tax_amount').text(parseFloat(tax).toLocaleString('en-US'))
    $('#tax').text(parseFloat(tax).toLocaleString('en-US'))
    $('#gtotal').text(parseFloat(gt_round).toLocaleString('en-US'))
    // var gtotal_round = gtotal.toPrecision();

    // $('[name="total_amount"]').val(gt_round);
    $('#tax').text();

  }

  function rem_item(_this){
    _this.closest('tr').remove();
    // c--;
    itemno --;
    calc_total();
  }

   function edit_item(_this){
alert("from edit item");
  var t = document.getElementById('item-list');
    var rows = t.rows; //rows collection - https://developer.mozilla.org/en-US/docs/Web/API/HTMLTableElement
    //for (var i=0; i<rows.length; i++) {
       this.onclick = function () {
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

  <script type="text/javascript">
//     function checkvalue(val)
// {

//     if(val === "Others")
//     {
//         if(document.getElementById('otherproduct').style.display === "none")
//         {
//         document.getElementById('otherproduct').style.display = 'block';
//         }
//     }
//     else
//     {
//   //    alert("from  check val different");
//        document.getElementById('otherproduct').style.display="none"; 
//     }
// }


// function checknamevalue(val)
// {
//     if(val === "Others")
//     {
//         if(document.getElementById('othercustomername').style.display === "none")
//         {
//         document.getElementById('othercustomername').style.display = 'block';
//         }
//     }
//     else
//     {
//        document.getElementById('othercustomername').style.display="none"; 
//     }
// }

// function checkpriceval(val) {
//   if(val === "Others")
//     {
//         if(document.getElementById('otherprice').style.display === "none")
//         {
//         document.getElementById('otherprice').style.display = 'block';
//         }
//     }
//     else
//     {
//        document.getElementById('otherprice').style.display="none"; 
//     }
// }
 </script>
<script type="text/javascript">
function remove(box_count)
  {
    jQuery("#box_loop_"+box_count).remove();
    var box_count= jQuery("#box_count").val();
    box_count--;
    jQuery("#box_count").val(box_count);

  }

</script>

<script type="text/javascript">
    $(document).ready(function(){  
  $("#product_choice").change(function() {   
  
    var productname = $(this).val();
    //var dataString = 'productname='+ productname ;   
    //alert(cat_type);  
    $.ajax({
      url: 'getprice.php',
      Type: "GET",
      //data:{"cat_id" : cat_id, "cat_type":cat_type}
      data: {"productname" : productname},  
      //cache: false,
      success: function(data) {
         
          $("#pricevalbox").html(data);
        //}     
      } 
    });
  }) 
});
  </script>
<script type="text/javascript">
   $(document).ready(function(){  
  $("#prv").click(function() {   
  alert("preview_quotation_pdf");
    //var productname = $(this).find(":selected").val();
    var dataString = 'productname='+ productname ;   
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
