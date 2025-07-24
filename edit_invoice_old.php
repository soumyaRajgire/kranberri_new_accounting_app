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

        <?php
          function getinvoiceDetails($conn, $inv_id) {
            $invoiceId = $conn->real_escape_string($inv_id); // Sanitize input

            $query = "SELECT q.*, c.*, a.*, qi.*, im.in_ex_gst, im.net_price, im.gst_rate, im.price 
                      FROM invoice q
                      JOIN customer_master c ON q.customer_id = c.id
                      JOIN address_master a ON c.id = a.customer_master_id
                      JOIN invoice_items qi ON q.id = qi.invoice_id
                      JOIN inventory_master im ON qi.productid = im.id
                      WHERE q.id = '$invoiceId'";
            
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $invoiceData = $result->fetch_assoc();
                $invoiceItems = [];
                foreach ($result as $row) {
                   $netPriceArray = explode('|', $row['net_price']);

                   $invoiceItems[] = [
                    'itemnum' => $row['itemno'],
                    'product' => $row['product'],
                    'prod_desc' => $row['prod_desc'],
                    'price' => $row['price'],
                    'qty' => $row['qty'],
                    'line_total' => $row['line_total'],
                    'gst' => $row['gst'],
                    'cgst' => $row['cgst'],
                    'sgst' => $row['sgst'],
                    'igst' => $row['igst'],
                    'cess_rate' => $row['cess_rate'], // Add cess_rate
                    'cess_amount' => $row['cess_amount'], // Add cess_amount
                    'total' => $row['total'], // Add total
                    'in_ex_gst' => $row['in_ex_gst'],
                    'productid' => $row['productid'],
                    'invoice_items_id' => $row['id'],
              
                    ];
                }

                // Add invoice items array to the main invoice data
                $invoiceData['invoice_items'] = $invoiceItems;

                return $invoiceData;
            } else {
                return false; // invoice not found
            }
          }

          $inv_id = $_GET['inv_id'];
          $invoiceDetails = getinvoiceDetails($conn, $inv_id);
        ?>
<section class="pcoded-main-container">
  <div class="pcoded-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
      <div class="page-block">
        <div class="row align-items-center">
          <div class="col-md-12">
            <div class="page-header-title">
              <h4 class="m-b-10">Edit Invoice</h4>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Invoice</a></li>
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
                    <form action="edit_invoicedb.php" method="POST" enctype="multipart/form-data">
                      <input type="text" name="inv_id" id="inv_id" value="<?php echo $inv_id;?>" hidden>
                      <input type="hidden" name="delete_item_ids" id="delete_item_ids" value="">
                      <div class="row border border-dark" >  
                        <div class="col-md-8 border-right border-dark" >
                          <h6 style="float:left;" class="pt-2">KRIKA MKB CORPORATION PRIVATE LIMITED<br/>120 Newport Center Dr, Newport Beach, CA 92660<br/>
                          Email: abhijith.mavatoor@gmail.com<br/>Phone: 9481024700<br/>GSTIN: 29AAICK7493G1ZX<br/>
                          </h6>
                        </div>
                        <div class="col-md-4 pt-1">
                          <div class="py-1 input-group">
                            <input class="form-control" type="text" id="purchaseNo" value="<?php echo $invoiceDetails['invoice_code'] ?>" name="purchaseNo" />
                            <label class="form-control col-sm-5" for="purchaseNo">Purchase No</label>
                          </div>
                          <div class="py-1 input-group">
                            <input class="form-control" type="date" id="purchaseDate" name="purchaseDate" value="<?php echo $invoiceDetails['invoice_date']?>" required/>
                            <label class="form-control col-sm-5" for="purchaseDate">Purchase Date</label>
                          </div>
                          <div class="py-1 input-group">
                            <input class="form-control" type="date" id="dueDate" name="dueDate" value="<?php echo $invoiceDetails['due_date']?>" required>
                            <label class="form-control col-sm-5" for="dueDate">Due Date</label>
                          </div>
                        </div>
                      </div>

                      <div class="row" id="customer_data">
                        <div class="col-md-4 border-left border-bottom border-dark p-3">
                          <div>
                            <h6>Customer Info</h6>
                            <span><?php echo $invoiceDetails['customerName'];?></span><br/>
                            <span>Business Name :<?php echo $invoiceDetails['business_name'] === "" ? "business name": $invoiceDetails['business_name'];?></span><br/>
                            <span><?php echo $invoiceDetails['s_state']?></span><br/>
                            <span>GSTIN :<?php echo $invoiceDetails['gstin'] === "" ? "": "gstin";?></span>
                          </div>
                        </div>
                        <input class="form-control" name="customer_name_choice" id="customer_name_choice" value="<?php echo $invoiceDetails['customerName'];?>" hidden/>
                        <input class="form-control" name="customer_email" id="customer_email" value="<?php echo $invoiceDetails['email'];?>" hidden/>
                        <input class="form-control" name="cst_mstr_id" id="cst_mstr_id" value="<?php echo $invoiceDetails['customer_id'];?>" hidden/>
                        <div class="col-md-4 border-left border-bottom border-dark p-3">
                          <div>
                            <h6>Billing Address</h6>
                            <span><?php echo $invoiceDetails['b_address_line1'] === "" ? '<span style="color:red;">Adress Line1</span>' : $invoiceDetails['b_address_line1'];?></span><br/>
                            <span><?php echo $invoiceDetails['b_address_line2'] === "" ? '<span style="color:red;">Adress Line2</span>' : $invoiceDetails['b_address_line2'];?></span><br/>
                            <span><?php echo ($invoiceDetails['b_city'] === "" ? '<span style="color:red;">City</span>' : $invoiceDetails['b_city']) . "-". ($invoiceDetails['b_Pincode'] === "" ? '<span style="color:red;">Pincode</span>': $invoiceDetails['b_Pincode']) ;?></span><br/>
                          </div>
                        </div>
                        <div class="col-md-4 border-left border-bottom border-right border-dark p-3">
                          <h6>Shipping Address</h6>
                          <span><?php echo $invoiceDetails['s_address_line1'] === "" ? '<span style="color:red;">Adress Line1</span>' : $invoiceDetails['s_address_line1'];?></span><br/>
                          <span><?php echo $invoiceDetails['s_address_line2'] === "" ? '<span style="color:red;">Adress Line2</span>' : $invoiceDetails['s_address_line2'];?></span><br/>
                          <span><?php echo ($invoiceDetails['s_city'] === "" ? '<span style="color:red;">City</span>' : $invoiceDetails['s_city']) . "-". ($invoiceDetails['s_Pincode'] === "" ? '<span style="color:red;">Pincode</span>': $invoiceDetails['s_Pincode']) ;?></span><br/>
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
                                $sql = "select * from inventory_master where  inventory_type ='Sales Catalog'";
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
               <input type="text" class="form-control" name="cess_rate" id="cess_rate" value="" hidden>
               <input type="text" class="form-control" name="cess_amount" id="cess_amount" value="" hidden>
                <!-- <input type="text" name="gst" id="gst" value="" hidden> -->
                <input type="text" name="in_ex_gst" id="in_ex_gst" value="" hidden>
              <!-- </div> -->
                        <div class="col-md-1 p-3 border-right border-bottom">
                          <button type="button" class="btn btn-success btn-sm" name="Addmore" id="addmore" onclick="add_more()">Add</button>
                        </div>
                      </div>                    
                      <div class="row border border-dark">
                        <table class="table table-bordered" id="item-list">
                        <colgroup>
                    <col width="18%">
                    <col width="25%">
                    <col width="1%">
                    <col width="10%">
                    <col width="1%">
                    <col width="7%">
                    <col width="6%">
                    <col width="6%">
                    <col width="7%">
                    <col width="1%">
                    <col width="1%">
                    <col width="15%">
                    <col width="15%">
                    
                   
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
        <th>Cess Rate</th>
<th>Cess Amount</th>
<th>Total</th>
<th>Action</th>
    </tr>
                          </thead>
                          <tbody>
                            <?php
                              $c =1;
                              $tot_amt =0;
                              $index =0;
                              foreach ($invoiceDetails['invoice_items'] as $item) {
                                $cgst = ($item['gst'] / 2) * ($item['line_total'] / 100);
                                $sgst = ($item['gst'] / 2) * ($item['line_total'] / 100);

                                // Formatting to 2 decimal places
                                $cgst = number_format((float)$cgst, 2, '.', '');
                                $sgst = number_format((float)$sgst, 2, '.', '');
                            ?>
                            <tr>
                              <td>
                                <select class="form-control product" name="products[<?php echo $index; ?>][pname]">
                                  <option value="<?php echo $item['product']; ?>"><?php echo $item['product']; ?> </option>
                                  <?php
                                    $sql2="select * from inventory_master where inventory_type='Sales Catalog'";
                                    $result2=$conn->query($sql2);
                                    if($result2->num_rows>0) {
                                      while($row2 = mysqli_fetch_assoc($result2)) {
                                  ?>
                                  <option value="<?php echo $row2["name"]?>"><?php echo $row2["name"]?></option>
                                  <?php
                                      }
                                    }
                                  ?>
                                </select> 
                              </td>
                              <td><textarea class="form-control" name="products[<?php echo $index; ?>][pdesc]" value="<?php echo $item['prod_desc']; ?>"><?php echo $item['prod_desc']; ?></textarea></td>
                              <td> <input type="number" class="form-control" name="products[<?php echo $index; ?>][pqty]" value="<?php echo $item['qty']; ?>" ></td>
                              <td> <input type="text" class="form-control" name="products[<?php echo $index; ?>][price]" value="<?php echo $item['price']; ?>"></td>
                              <td> <input type="text" class="form-control" name="products[<?php echo $index; ?>][gst]" value="<?php echo $item['gst']; ?>"></td>
                              <td><?php echo $item['cgst']; ?> <input type="hidden" class="form-control" name="products[<?php echo $index; ?>][cgst]" value="<?php echo $item['cgst']; ?>"></td>
                              <td> <?php echo $item['sgst']; ?><input type="hidden" class="form-control" name="products[<?php echo $index; ?>][sgst]" value="<?php echo $item['sgst']; ?>"></td>
                              <td> <?php echo $item['igst']; ?><input type="hidden" class="form-control" name="products[<?php echo $index; ?>][igst]" value="<?php echo $item['igst']; ?>"></td>
                              <td><input type="text" class="form-control" name="products[<?php echo $index; ?>][cess_rate]" value="<?php echo $item['cess_rate']; ?>" readonly></td>
<td><input type="text" class="form-control" name="products[<?php echo $index; ?>][cess_amount]" value="<?php echo $item['cess_amount']; ?>" readonly></td>
<td><input type="text" class="form-control" name="products[<?php echo $index; ?>][cess_amount]" value="<?php echo $item['cess_amount']; ?>" readonly></td>


                              <td><input class="form-control" type="number" name="products[<?php echo $index; ?>][ptotal]" value="<?php echo $item['line_total']; ?>" readonly></td>

                              <input type="hidden" name="products[<?php echo $index; ?>][pitemno]" value="<?php echo $item['itemnum']; ?>">
                              <input type="hidden" name="products[<?php echo $index; ?>][pprice]" value="<?php echo $item['price']; ?>">
                              <input type="hidden" name="products[<?php echo $index; ?>][pgst]" value="<?php echo $item['gst']; ?>">
                              <input type="hidden" name="products[<?php echo $index; ?>][pproductid]" value="<?php echo $item['productid']; ?>">
                              <input type="hidden" name="products[<?php echo $index; ?>][pcgst]" value="<?php echo $cgst; ?>">
                              <input type="hidden" name="products[<?php echo $index; ?>][psgst]" value="<?php echo $sgst; ?>">
                              <input type="hidden" name="products[<?php echo $index; ?>][pin_ex_gst]" value="<?php echo $item['in_ex_gst']; ?>">
                              <input name="products[<?php echo $index; ?>][attr_id]" value="<?php echo $item['invoice_items_id']?>" hidden/>
         
                              <td><button class="btn btn-sm btn-outline-danger delete-item" type="button" data-itemid="<?php echo $item['invoice_items_id']; ?>"><i class="fa fa-trash" style="color:red;"></i></button></td>
                            </tr>
                            <?php
                              $tot_amt += $item['line_total']; 
                              $index++;
                              $c++;
                              }
                            ?>
                            <input name="i_id" id="i_id" value="<?php echo ($c);?>" hidden/> 
                          </tbody>
                          <!-- <tfoot>
                            <tr>
                              <th colspan="2" rowspan="3"><textarea class="form-control" placeholder="Note" name="note" id="note" cols="20" style="width: -webkit-fill-available;height: 112px;"><?php echo isset($note) ? $note : ''; ?></textarea></th>
                              <th class="text-right" colspan="2">Sub Total</th>
                              <th class="text-right" id="sub_total"><?php echo $tot_amt;?>
                                <input type="text" name="sub_total" value="<?php echo $tot_amt;?>" hidden>
                              </th>
                            </tr>
                            <tr>
                              <th class="text-right" colspan="2">Additional Payable</th>
                              <th><input type="number" class="form-control" name="pack_price" id="pack_price" value="0" onchange="calc_total();"></th>
                            </tr>
                            <tr>
                              <th class="text-right" colspan="2">Grand Total</th>
                              <th class="text-right" id="gtotal"><?php echo $tot_amt;?></th>
                              <input type="hidden" name="total_amount" value="<?php echo $tot_amt;?>">
                            </tr>
                          </tfoot> -->
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
                     <td style="text-align:right;" id="final_taxable_amt"><?php echo $invoiceDetails['total_amount']?> </td>
                   
                </tr> 
          
                <tr>
                    <td class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Total GST</td>
                    <td style="text-align:right;" id="final_gst_amount" > <?php echo $invoiceDetails['total_gst']?></td>
                     
                </tr>

                <tr>
                    <td class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Total Cess</td>
                    <td style="text-align:right;" id="final_cess_amount" ><?php echo $invoiceDetails['total_cess']?></td>
                    
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
                        <div class="col-md-2"><input type="submit" class="btn btn-primary " name="update" value="update" /></div>
                        <div class="col-md-2"><input type="reset" class="btn btn-danger " name="cancel" value="Cancel" /></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script type="text/javascript">
   $(document).ready(function () {
    console.log("Script loaded and running");

    // Function to update line total for a product row
    function updateLineTotal(tr) {
        let qty = parseFloat(tr.find('input[name*="[pqty]"]').val()) || 0;
        let price = parseFloat(tr.find('input[name*="[pprice]"]').val()) || 0;
        let discount = parseFloat(tr.find('input[name*="[discount]"]').val()) || 0;
        let gst = parseFloat(tr.find('input[name*="[gst]"]').val()) || 0;
        let cessRate = parseFloat(tr.find('input[name*="[cess_rate]"]').val()) || 0;

        let taxableAmount = qty * price - discount;
        let gstAmount = (gst / 100) * taxableAmount;
        let cessAmount = (cessRate / 100) * taxableAmount;
        let rowTotal = taxableAmount + gstAmount + cessAmount;

        // Update row values
        tr.find('input[name*="[ptotal]"]').val(rowTotal.toFixed(2));
        tr.find('input[name*="[cess_amount]"]').val(cessAmount.toFixed(2));

        // Recalculate grand total
        calc_total();
    }

    // Function to calculate grand total
    function calc_total() {
      let totalTaxable = 0, totalGST = 0, totalCess = 0, grandTotal = 0;
        let packPrice = parseFloat($('#pack_price').val()) || 0;

        // Sum all line totals
        $('#item-list tbody tr').each(function () {
          const tr = $(this);
            const qty = parseFloat(tr.find('input[name*="[pqty]"]').val()) || 0;
            const price = parseFloat(tr.find('input[name*="[pprice]"]').val()) || 0;
            const discount = parseFloat(tr.find('input[name*="[discount]"]').val()) || 0;
            const gst = parseFloat(tr.find('input[name*="[gst]"]').val()) || 0;
            const cessRate = parseFloat(tr.find('input[name*="[cess_rate]"]').val()) || 0;

            const taxableAmount = qty * price - discount;
            const gstAmount = (gst / 100) * taxableAmount;
            const cessAmount = (cessRate / 100) * taxableAmount;
            const rowTotal = taxableAmount + gstAmount + cessAmount;

            totalTaxable += taxableAmount;
            totalGST += gstAmount;
            totalCess += cessAmount;
            grandTotal += rowTotal;
        });

        // Update the totals in the UI
        $('#final_taxable_amt').text(totalTaxable.toFixed(2));
        $('#final_gst_amount').text(totalGST.toFixed(2));
        $('#final_cess_amount').text(totalCess.toFixed(2));
        $('#gtotal').text(grandTotal.toFixed(2));

        // Update hidden inputs
        $('#final_taxable_amt_field').val(totalTaxable.toFixed(2));
        $('#final_gst_amount_field').val(totalGST.toFixed(2));
        $('#final_cess_amount_field').val(totalCess.toFixed(2));
        $('#total_amount').val(grandTotal.toFixed(2));
    }

    // Function to add a new row
    function add_more() {
      const count = $('#item-list tbody tr').length;
    const product = $('#product_choice').val();
    const prodDesc = $('#prod_desc').val();
    const qty = parseFloat($('#qty').val()) || 0;
    const price = parseFloat($('#price').val()) || 0;
    const discount = parseFloat($('#discount').val()) || 0;
    const gst = parseFloat($('#gst').val()) || 0;
    const cessRate = parseFloat($('#cess_rate').val()) || 0;

        // Validate product selection
      
      if (!product || !price) {
            alert('Please select a valid product and enter price.');
            return;
        }

        // Calculate row totals
        const taxableAmount = qty * price - discount;
    const gstAmount = (gst / 100) * taxableAmount;
    const cessAmount = (cessRate / 100) * taxableAmount;
    const rowTotal = taxableAmount + gstAmount + cessAmount;



        // Add row HTML
        const newRow = `
        <tr>
            <td>${product}<input type="hidden" name="products[${count}][pname]" value="${product}"></td>
            <td>${prodDesc}<input type="hidden" name="products[${count}][pdesc]" value="${prodDesc}"></td>
            <td><input type="number" class="form-control" name="products[${count}][pqty]" value="${qty}"></td>
            <td><input type="number" class="form-control" name="products[${count}][pprice]" value="${price}"></td>
            <td><input type="number" class="form-control" name="products[${count}][discount]" value="${discount}"></td>
            <td><input type="number" class="form-control" name="products[${count}][gst]" value="${gst}"></td>
            <td><input type="number" class="form-control" name="products[${count}][cgst]" value="${(gst / 2).toFixed(2)}" readonly></td>
            <td><input type="number" class="form-control" name="products[${count}][sgst]" value="${(gst / 2).toFixed(2)}" readonly></td>
            <td><input type="number" class="form-control" name="products[${count}][igst]" value="0.00" readonly></td>
            <td><input type="number" class="form-control" name="products[${count}][cess_rate]" value="${cessRate}"></td>
            <td><input type="number" class="form-control" name="products[${count}][cess_amount]" value="${cessAmount.toFixed(2)}" readonly></td>
            <td><input type="number" class="form-control" name="products[${count}][ptotal]" value="${rowTotal.toFixed(2)}" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">Remove</button></td>
        </tr>
    `;

        // Append new row
        $('#item-list tbody').append(newRow);

    // Clear input fields
    $('#product_choice').val('');
    $('#prod_desc').val('');
    $('#qty').val(1);
    $('#price').val('');
    $('#discount').val('');
    $('#gst').val('');
    $('#cess_rate').val('');

        // Recalculate grand total
        calc_total();
    }

    // Function to remove a row
    function rem_item(event) {
        $(event).closest('tr').remove();
        calc_total();
    }

    // Event listeners
    $('#item-list').on('input', 'input', function () {
        let tr = $(this).closest('tr');
        updateLineTotal(tr);
    });

    $('#pack_price').on('input', calc_total);
    $('#addmore').click(add_more);

    // Product selection and price fetching
    $("#product_choice").change(function () {
        let productName = $(this).val();
        let dataListOptions = document.getElementById('product').querySelectorAll('option');
        let productId = '';

        for (let option of dataListOptions) {
            if (option.value === productName) {
                productId = option.getAttribute('data-productid');
                break;
            }
        }

        $("#productid").val(productId);

        // Fetch product details via AJAX
        $.ajax({
            url: 'getprice.php',
            type: "GET",
            data: { productname: productName, productid: productId },
            success: function (data) {
                let jsonData = JSON.parse(data);
                $("#gst").val(jsonData.gst);
                $("#price").val(jsonData.in_ex_gst === "inclusive of GST" ? jsonData.netprice : jsonData.price);
                $("#netprice").val(jsonData.netprice);
                $("#in_ex_gst").val(jsonData.in_ex_gst);
            }
        });
    });

    // Initial calculation
    calc_total();
});

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
        var gstAmount = (price * gstRate) / 100;
        var netPrice = price - gstAmount - nonTaxable;
        netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
      } else if (inclusiveGst === "exclusive of GST" && price > 0) {
        var gstAmount = (price * gstRate) / 100;
        var netPrice = price-nonTaxable;
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
$(document).ready(function() {
    $('.delete-item').click(function() {
        var itemId = $(this).data('itemid');
        var deleteItemIds = $('#delete_item_ids').val();

        if (confirm('Are you sure you want to delete this item?')) {
            if (deleteItemIds) {
                deleteItemIds += ',' + itemId;
            } else {
                deleteItemIds = itemId;
            }
            $('#delete_item_ids').val(deleteItemIds);
            $(this).closest('tr').remove();
        }
    });
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
        let qty = parseFloat(row.find('input[name*="[pqty]"]').val()) || 0; // Quantity
        let price = parseFloat(row.find('input[name*="[pprice]"]').val()) || 0; // Price
        let discount = parseFloat(row.find('input[name*="[discount]"]').val()) || 0; // Discount
        let gstRate = parseFloat(row.find('input[name*="[gst]"]').val()) || 0; // GST %
        let cessRate = parseFloat(row.find('input[name*="[cess_rate]"]').val()) || 0; // Cess Rate %

        // Calculate taxable amount after discount
        const grossAmount = price * qty;
        const discountAmount = discount;
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

        // Calculate Cess amount
        const cessAmount = taxableAmount * (cessRate / 100);

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
        row.find('input[name*="[cgst]"]').val(cgst.toFixed(2));
        row.find('input[name*="[sgst]"]').val(sgst.toFixed(2));
        row.find('input[name*="[igst]"]').val(igst.toFixed(2));
        row.find('input[name*="[cess_amount]"]').val(cessAmount.toFixed(2));
        row.find('input[name*="[ptotal]"]').val(rowTotal.toFixed(2));
    });

    // Add Freight Charges
    const freightCharges = [
        parseFloat($('#roadFreightCharges').val()) || 0,
        parseFloat($('#railFreightCharges').val()) || 0,
        parseFloat($('#airFreightCharges').val()) || 0,
        parseFloat($('#shipFreightCharges').val()) || 0,
    ].reduce((sum, charge) => sum + charge, 0);
    grandTotal += freightCharges;

    // Add Additional Charges
    const additionalCharges = Array.from(document.querySelectorAll('.charge-input'))
        .reduce((acc, input) => acc + (parseFloat(input.value) || 0), 0);
    grandTotal += additionalCharges;

    // Add TCS
    const tcsTaxPercent = parseFloat($('#tcsTax').val()) || 0;
    const tcsValue = totalTaxable * (tcsTaxPercent / 100);
    grandTotal += tcsValue;

    // Update totals in the UI
    $('#final_taxable_amt').text(totalTaxable.toFixed(2));
    $('#final_gst_amount').text((totalCGST + totalSGST + totalIGST).toFixed(2));
    $('#final_cess_amount').text(totalCess.toFixed(2));

    $('#final_taxable_amt_field').val(totalTaxable.toFixed(2));
    $('#final_gst_amount_field').val((totalCGST + totalSGST + totalIGST).toFixed(2));
    $('#final_cess_amount_field').val(totalCess.toFixed(2));

    $('#gtotal').text(grandTotal.toFixed(2));
    $('#total_amount').val(grandTotal.toFixed(2));

    console.log(`Grand Total: ${grandTotal.toFixed(2)}`);
}






// Function to remove an item
function remove_item(button) {
    $(button).closest('tr').remove(); // Remove the selected row
    calculate_totals(); // Recalculate totals
}
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
    function calculateGrandTotal() {
    // Get values from the DOM
    const taxableAmount = parseFloat(document.getElementById('final_taxable_amt').textContent) || 0;
    const totalGST = parseFloat(document.getElementById('final_gst_amount').textContent) || 0;
    const totalCess = parseFloat(document.getElementById('final_cess_amount').textContent) || 0;

    // Calculate additional charges
    let additionalCharges = 0;
    document.querySelectorAll('.charge-input').forEach(input => {
        additionalCharges += parseFloat(input.value) || 0;
    });

    // Calculate the grand total
    const grandTotal = taxableAmount + totalGST + totalCess + additionalCharges;

    // Update the DOM
    document.getElementById('gtotal').textContent = grandTotal.toFixed(2);
    document.getElementById('total_amount').value = grandTotal.toFixed(2);

    // Debugging in console
    console.log(`Taxable Amount: ${taxableAmount}`);
    console.log(`Total GST: ${totalGST}`);
    console.log(`Total Cess: ${totalCess}`);
    console.log(`Additional Charges: ${additionalCharges}`);
    console.log(`Grand Total: ${grandTotal}`);
}

// Trigger the calculation when additional charges or other values are updated
document.querySelectorAll('.charge-input, #final_taxable_amt, #final_gst_amount, #final_cess_amount').forEach(element => {
    element.addEventListener('input', calculateGrandTotal);
});

// Initial calculation on page load
document.addEventListener('DOMContentLoaded', () => {
    calculateGrandTotal();
});

</script>

</body>
</html>
