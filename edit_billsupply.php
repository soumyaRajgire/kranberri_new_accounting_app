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
#transportDetails{

      border-right: 1px solid black;
}

</style>
  <style>
   /* .vertical_line {
      border-left: 1px solid black;
      height: 300px;
      position: absolute;
      left: 70%;
      margin-left: -3px;
      top: 0;
    }*/
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
              <h4 class="m-b-10">Edit Bill Of Supply</h4>
            </div>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Bill Of Supply</a></li>
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
        <?php

          function getinvoiceDetails($conn, $bill_id) {
            $billId = $conn->real_escape_string($bill_id); // Sanitize input

            $query = "SELECT q.*, c.*, a.*, qi.*
            FROM bill_of_supply q
            JOIN customer_master c ON q.customer_id = c.id
            JOIN address_master a ON c.id = a.customer_master_id
            JOIN billsupply_items qi ON q.id = qi.bill_id
            LEFT JOIN inventory_master im ON qi.product_id = im.id
            WHERE q.id = '$billId'";
  
            
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $invoiceData = $result->fetch_assoc();
                $invoiceItems = [];
                foreach ($result as $row) {
                   $PriceArray = explode('|', $row['price']);

                    $invoiceItems[] = [
                        'itemnum' => $row['itemno'],
                        'product' => $row['product'],
                        'prod_desc' => $row['prod_desc'],
                        'price' => $row['price'],
                        'qty' => $row['qty'],
                        'line_total' => $row['line_total'],
                        'discount' => $row['discount'],
                        'total' => $row['total'],
                        // 'gst_amt'=>$row['gst_amt'],
                        // 'gst' => $row['gst'],
                        // 'in_ex_gst' => $row['in_ex_gst'],
                        //  'net_price' => $netPriceArray[0],
                        'product_id' => $row['product_id'],
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

          $bill_id = $_GET['bill_id'];
          $invoiceDetails = getinvoiceDetails($conn, $bill_id);
        ?>

        <div class="card-body table-border-style">
          <div class="table-responsive">
            <div class="row">
              <div class="col-sm-12">
                <div class="">
                  <div class="card-body">
                    <form action="edit_billsupplydb.php" method="POST" enctype="multipart/form-data">
                      <input type="text" name="bill_id" id="bill_id" value="<?php echo $bill_id;?>" hidden>
                      <input type="hidden" name="delete_item_ids" id="delete_item_ids" value="">
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
                            <input class="form-control" type="text" id="purchaseNo" value="<?php echo $invoiceDetails['bill_code'] ?>" name="purchaseNo" />
                            <label class="form-control col-sm-5" for="purchaseNo">Bill No</label>
                          </div>
                          <div class="py-1 input-group">
                            <input class="form-control" type="date" id="purchaseDate" name="purchaseDate" value="<?php echo $invoiceDetails['bill_date']?>" required/>
                            <label class="form-control col-sm-5" for="purchaseDate">Bill Date</label>
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
            
                      <div class="row border-dark border-right border-left border-top border-bottom" id="box_loop_1">
          <div class="col-md-3 p-1 border-right border-left border-bottom">Item
              <button type="button" class="btn btn-sm dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 11px; font-weight: 900; color: blue;"><i class="fa fa-plus"></i> New Item</button>

              <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" data-value="products">Products</a>
                  <a class="dropdown-item" href="#" data-value="services">Services</a>
              </div>

          </div>
           <div class="col-md-2 p-1 border-right border-bottom" >
              
                Product Description
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
                            <input type="hidden" name="productid" id="productid" value="<?php echo $row["id"]?>" />
                           
                 </div>
            
              <div class="col-md-2 p-1 border-right border-bottom" >
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
                            <col width="16%">
                            <col width="30%">
                            <col width="13%">
                            <col width="14%">
                             <col width="14%">
                            <col width="18%">
                          </colgroup>
                          <thead>
                            <tr>
                              <th class="text-center">Product </th>
                              <th class="text-center">Product Desc </th>
                              <th class="text-center">Price</th>
                              <th class="text-center">Quantity</th>
                               <th class="text-center">Discount</th>
                              <th class="text-center">Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $c =1;
                              $tot_amt =0;
                              $index =0;
                              foreach ($invoiceDetails['invoice_items'] as $item) {
                                // $cgst = ($item['gst'] / 2) * ($item['line_total'] / 100);
                                // $sgst = ($item['gst'] / 2) * ($item['line_total'] / 100);

                                // Formatting to 2 decimal places
                                // $cgst = number_format((float)$cgst, 2, '.', '');
                                // $sgst = number_format((float)$sgst, 2, '.', '');
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
                              <td><textarea class="form-control" name="products[<?php echo $index; ?>][pprod_desc]" value="<?php echo $item['prod_desc']; ?>"><?php echo $item['prod_desc']; ?></textarea></td>
                              <td> <input type="text" class="form-control" name="products[<?php echo $index; ?>][pprice]" value="<?php echo $item['price']; ?>"></td>
                              <td> <input type="number" class="form-control" name="products[<?php echo $index; ?>][pqty]" value="<?php echo $item['qty']; ?>" ></td>
                                 <td> <input type="number" class="form-control" name="products[<?php echo $index; ?>][discountval]" value="<?php echo $item['discount']; ?>" ></td>
                              <td><input class="form-control" type="number" name="products[<?php echo $index; ?>][ptotal]" value="<?php echo $item['total']; ?>" readonly></td>

                              <input type="hidden" name="products[<?php echo $index; ?>][pitemno]" value="<?php echo $item['itemnum']; ?>">
                             
                              <!-- <input type="hidden" name="products[<?php echo $index; ?>][pgst]" value="<?php echo $item['gst']; ?>"> -->
                              <input type="text" name="products[<?php echo $index; ?>][pproductid]" value="<?php echo $item['product_id']; ?>" hidden>
                              <!-- <input type="hidden" name="products[<?php echo $index; ?>][pcgst]" value="<?php echo $cgst; ?>"> -->
                              <!-- <input type="hidden" name="products[<?php echo $index; ?>][psgst]" value="<?php echo $sgst; ?>"> -->
                              <!-- <input type="hidden" name="products[<?php echo $index; ?>][pin_ex_gst]" value="<?php echo $item['in_ex_gst']; ?>"> -->
                              <input name="products[<?php echo $index; ?>][attr_id]" value="<?php echo $item['invoice_items_id']?>" hidden/>
         
                              <td><button class="btn btn-sm btn-outline-danger delete-item" type="button" data-itemid="<?php echo $item['invoice_items_id']; ?>"><i class="fa fa-trash" style="color:red;"></i></button></td>
                            </tr>
                            <?php
                              // $tot_amt += $item['line_total']; 
                              $index++;
                              $c++;
                              }
                            ?>
                            <input name="i_id" id="i_id" value="<?php echo ($c);?>" hidden/> 
                          </tbody>
                          <tfoot>
                            <tr>
                            <th colspan="2" rowspan="3">
    <textarea class="form-control" placeholder="Note" name="note" id="note" cols="20" style="width: -webkit-fill-available; height: 112px;">
        <?php 
            if ($invoiceDetails) {
                echo isset($invoiceDetails['note']) ? htmlspecialchars($invoiceDetails['note']) : ''; 
            }
        ?>
    </textarea>
</th>
                              <th class="text-right" colspan="3">Sub Total</th>
                              <th class="text-right" colspan="2" id="sub_total"><?php echo $tot_amt;?>
                                <input type="text" name="sub_total" value="<?php echo $tot_amt;?>" hidden>
                              </th>
                            </tr>
                            <tr>
                              <th class="text-right" colspan="3">Additional Payable</th>
                              <th colspan="2"><input type="number" class="form-control" name="pack_price" id="pack_price" value="0" onchange="calc_total();"></th>
                            </tr>
                            <tr>
                              <th class="text-right" colspan="3">Grand Total</th>
                              <th class="text-right" colspan="2" id="gtotal"><?php echo $tot_amt;?></th>
                              <input type="hidden" name="total_amount" value="<?php echo $tot_amt;?>">
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    <!-- </div> -->
   <div class="row border-dark border-right border-left border-bottom">
    <!-- Transportation Details -->
    <div class="col-md-6 col-12 p-0">
        <div class="p-2 invoice-compliance-header" style="background-color: #efefef;border-right: 1px solid black;" onclick="toggleSection('transportDetails', this)">
            <span>TRANSPORTATION DETAILS</span>
            <i class="fas fa-chevron-down rotate-icon"></i>
        </div>
        <div id="transportDetails" class="collapse-content">
            <div class="p-3">
                <!-- Transport Mode Selection -->
                <div class="mb-3">
                    <label class="form-label">Select Transport Mode:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="transportMode" id="none" value="None" checked onchange="showTransportDetails(this.value)">
                        <label class="form-check-label" for="none">None</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="transportMode" id="road" value="Road" onchange="showTransportDetails(this.value)">
                        <label class="form-check-label" for="road">Road</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="transportMode" id="rail" value="Rail" onchange="showTransportDetails(this.value)">
                        <label class="form-check-label" for="rail">Rail</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="transportMode" id="air" value="Air" onchange="showTransportDetails(this.value)">
                        <label class="form-check-label" for="air">Air</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="transportMode" id="ship" value="Ship" onchange="showTransportDetails(this.value)">
                        <label class="form-check-label" for="ship">Ship/Road cum Ship</label>
                    </div>
                </div>

                <!-- Dynamic Content Based on Transport Mode -->
                <div id="transportData">
                    <!-- None Selected -->
                    <div id="noneData" class="transport-mode-data d-none">
                    
                    </div>

                    <!-- Road Selected -->
                    <div id="roadData" class="transport-mode-data d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="roadVehicleNumber">Vehicle Number</label>
                                <input type="text" class="form-control" id="roadVehicleNumber"  name="roadVehicleNumber" placeholder="Enter Vehicle Number">
                            </div>
                            <div class="col-md-6">
                                <label for="driverName">Driver Name</label>
                                <input type="text" class="form-control" id="driverName" name="driverName"  placeholder="Enter Driver Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="licenseNumber">Driver License Number</label>
                                <input type="text" class="form-control" id="licenseNumber" name="licenseNumber" placeholder="Enter License Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadFreightCharges">Freight Charges (USD)</label>
                                   <input type="number" class="form-control" id="roadFreightCharges" name="roadFreightCharges" placeholder="Enter Charges" oninput="calculate_totals()">

                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadInsurance">Insurance Details</label>
                                <input type="text" class="form-control" id="roadInsurance" name="roadInsurance" placeholder="Enter Insurance Details">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadPermit">Permit Number</label>
                                <input type="text" class="form-control" id="roadPermit" name="roadPermit" placeholder="Enter Permit Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadContact">Driver Contact</label>
                                <input type="text" class="form-control" id="roadContact" name="driver_contact" placeholder="Enter Contact Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadDistance">Distance (km)</label>
                                <input type="number" class="form-control" id="roadDistance" name="roadDistance"  placeholder="Enter Distance">
                            </div>
                        </div>
                    </div>

                    <!-- Rail Selected -->
                    <div id="railData" class="transport-mode-data d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="trainNumber">Train Number</label>
                                <input type="text" class="form-control" id="trainNumber" name="trainNumber" placeholder="Enter Train Number">
                            </div>
                            <div class="col-md-6">
                                <label for="railwayStation">Departure Station</label>
                                <input type="text" class="form-control" id="railwayStation" name="railwayStation" placeholder="Enter Station">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="arrivalStation">Arrival Station</label>
                                <input type="text" class="form-control" id="arrivalStation" name="arrivalStation" placeholder="Enter Arrival Station">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railwayBooking">Booking Reference</label>
                                <input type="text" class="form-control" id="railwayBooking" name="railwayBooking" placeholder="Enter Booking Reference">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railFreightCharges">Freight Charges </label>
                                 <input type="number" class="form-control" id="railFreightCharges" name="railFreightCharges" placeholder="Enter Charges" oninput="calculate_totals()">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railwayCoach">Coach Number</label>
                                <input type="text" class="form-control" id="railwayCoach" name="railwayCoach" placeholder="Enter Coach Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railwaySeat">Seat Number</label>
                                <input type="text" class="form-control" id="railwaySeat" name="railwaySeat"  placeholder="Enter Seat Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railDepartureTime">Departure Time</label>
                                <input type="time" class="form-control" id="railDepartureTime" name="railDepartureTime">
                            </div>
                         </div>
                    </div>

                    <!-- Air Selected -->
                    <div id="airData" class="transport-mode-data d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="flightNumber">Flight Number</label>
                                <input type="text" class="form-control" id="flightNumber"  name="flightNumber" placeholder="Enter Flight Number">
                            </div>
                            <div class="col-md-6">
                                <label for="departureAirport">Departure Airport</label>
                                <input type="text" class="form-control" id="departureAirport" name="departureAirport" placeholder="Enter Airport Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="arrivalAirport">Arrival Airport</label>
                                <input type="text" class="form-control" id="arrivalAirport" name="arrivalAirport" placeholder="Enter Airport Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airwayBill">Airway Bill Number</label>
                                <input type="text" class="form-control" id="airwayBill" name="airwayBill" placeholder="Enter Bill Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airFreightCharges">Freight Charges </label>
                                  <input type="number" class="form-control" id="airFreightCharges" name="airFreightCharges" placeholder="Enter Charges" oninput="calculate_totals()">

                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airCargoType">Cargo Type</label>
                                <input type="text" class="form-control" id="airCargoType" name="airCargoType" placeholder="Enter Cargo Type">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airlineName">Airline Name</label>
                                <input type="text" class="form-control" id="airlineName" name="airlineName" placeholder="Enter Airline Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airETA">Estimated Time of Arrival</label>
                                <input type="time" class="form-control" id="airETA" name="airETA">
                            </div>
                          </div>
                    </div>
                </div>
                <!-- Ship Selected -->
<div id="shipData" class="transport-mode-data d-none">
    <div class="row">
        <div class="col-md-6">
            <label for="shipVesselName">Vessel Name</label>
            <input type="text" class="form-control" id="shipVesselName" name="shipVesselName" placeholder="Enter Vessel Name">
        </div>
        <div class="col-md-6">
            <label for="shipVoyageNumber">Voyage Number</label>
            <input type="text" class="form-control" id="shipVoyageNumber" name="shipVoyageNumber" placeholder="Enter Voyage Number">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipContainerNumber">Container Number</label>
            <input type="text" class="form-control" id="shipContainerNumber" name="shipContainerNumber" placeholder="Enter Container Number">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipBillOfLading">Bill of Lading Number</label>
            <input type="text" class="form-control" id="shipBillOfLading"  name="shipBillOfLading"  placeholder="Enter Bill of Lading Number">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipPortOfLoading">Port of Loading</label>
            <input type="text" class="form-control" id="shipPortOfLoading" name="shipPortOfLoading" placeholder="Enter Port of Loading">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipPortOfDischarge">Port of Discharge</label>
            <input type="text" class="form-control" id="shipPortOfDischarge" name="shipPortOfDischarge" placeholder="Enter Port of Discharge">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipFreightCharges">Freight Charges </label>
              <input type="number" class="form-control" id="shipFreightCharges" name="shipFreightCharges" placeholder="Enter Charges" oninput="calculate_totals()">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipEstimatedArrival">Estimated Time of Arrival (ETA)</label>
            <input type="date" class="form-control" id="shipEstimatedArrival" name="shipEstimatedArrival">
        </div>

    </div>
</div>

            </div>
        </div>
    </div>
   <!-- Other Details Section -->
<div class="col-md-6 col-12 p-0">
    <div class="p-2 invoice-compliance-header" style="background-color: #efefef;" onclick="toggleSection('otherDetails', this)">
        <span>OTHER DETAILS</span>
        <i class="fas fa-chevron-down rotate-icon"></i>
    </div>
    <div id="otherDetails" class="collapse-content">
        <!-- Input Fields for Other Details -->
        <div class="p-3">
            <div class="row">
                <!-- PO Number and PO Date -->
                <div class="col-md-6 mb-3">
                    <label for="other_poNumber">PO Number</label>
                    <input type="text" id="other_poNumber" name="other_poNumber" class="form-control" placeholder="Enter PO Number">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="other_poDate">PO Date</label>
                    <input type="date" id="other_poDate" name="other_poDate" class="form-control" placeholder="dd-mm-yyyy">
                </div>
                <!-- Challan Number and Due Date -->
                <div class="col-md-6 mb-3">
                    <label for="challanNumber">Challan Number</label>
                    <input type="text" id="challanNumber" name="challanNumber" class="form-control" placeholder="Enter Challan Number">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="other_dueDate">Due Date</label>
                    <input type="date" id="other_dueDate" name="other_dueDate" class="form-control" placeholder="dd-mm-yyyy">
                </div>
                <!-- EwayBill No and Sales Person -->
                <div class="col-md-6 mb-3">
                    <label for="ewayBill">EwayBill No.</label>
                    <input type="text" id="ewayBill" name="ewayBill" class="form-control" placeholder="Enter EwayBill No">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="salesPerson">Sales Person</label>
                    <input type="text" id="salesPerson" name="salesPerson" class="form-control" placeholder="Sales Person">
                </div>
                <!-- Reverse Charge Checkbox -->
                <div class="col-12 mb-3">
                    <input type="checkbox" id="reverseCharge" name="reverseCharge" value="1">
                    <label for="reverseCharge">Is transaction applicable for Reverse Charge?</label>
                </div>
                <!-- TCS Value and TCS Tax -->
                <div class="col-md-6 mb-3">
                    <label for="tcsValue">TCS Value</label>
                    <input type="text" id="tcsValue" name="tcsValue" class="form-control" placeholder="Enter TCS Value">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tcsTax">Enter TCS Tax</label>
                    <select id="tcsTax" name="tcsTax" class="form-control">
                        <option value="">Percent Wise on taxable...</option>
                        <option value="5">5%</option>
                        <option value="10">10%</option>
                    </select>
                </div>
                <!-- Charges Header -->
<!-- Charges Section -->



            </div>
        </div>
    </div>
</div>


</div>
  <div class="row">
                      <div class="col-md-6 border-left border-right border-bottom border-dark p-3">
    <textarea class="form-control" placeholder="Terms and Conditions" name="terms_condition" id="terms_condition" cols="20" style="width: -webkit-fill-available; height: 112px;">
        <?php 
            // Fetching Terms and Conditions
            if ($invoiceDetails) {
                echo isset($invoiceDetails['terms_condition']) ? htmlspecialchars($invoiceDetails['terms_condition']) : '';
            }
        ?>
    </textarea>
</div>

                        <div class="col-md-6 border-right border-bottom border-dark p-3">
                          <div >
                            <h6>For </h6><br/>
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

function add_more() {
    var count = $('#item-list tbody tr').length;
    var product = $('#product_choice').val();
    var prod_desc = $('#prod_desc').val();
    var qty = $('#qty').val();
    var price = $('#price').val();
    var netprice = $('#netprice').val();
    var discount = $('#discount').val();
    var in_ex_gst = $('#in_ex_gst').val();
    var productid = $('#productid').val();

    if (in_ex_gst === "inclusive of GST") {
        var total = parseFloat(netprice) * parseFloat(qty);
    } else if (in_ex_gst === "exclusive of GST") {
        var total = parseFloat(price) * parseFloat(qty);
    }

    // var cgst = ((parseFloat(gst) / 2) * parseFloat(total) / 100).toFixed(2);
    // var sgst = ((parseFloat(gst) / 2) * parseFloat(total) / 100).toFixed(2);

    if (productid === '') {
        alert('Product ID is not set. Please select a valid product.');
        return;
    }

    var rowHtml = '<tr>' +
        '<td>' + product + '<input type="hidden" name="products[' + count + '][pname]" value="' + product + '"></td>' +
        '<td>' + prod_desc + '<input type="hidden" name="products[' + count + '][pdesc]" value="' + prod_desc + '"></td>' +
        '<td>' + price + '<input type="hidden" name="products[' + count + '][pprice]" value="' + price + '"></td>' +
        '<td>' + qty + '<input type="hidden" name="products[' + count + '][pqty]" value="' + qty + '"></td>' + '<td>' + discount + '<input type="hidden" name="products[' + count + '][pqty]" value="' + qty + '"><input type="hidden" name="products[' + count + '][discountval]" value="' + discount + '"></td>' + '<td>' + total + '<input type="hidden" name="products[' + count + '][ptotal]" value="' + total + '"></td>' +
        '<input type="hidden" name="products[' + count + '][pnetprice]" value="' + netprice + '">' +
        '<input type="hidden" name="products[' + count + '][pproductid]" value="' + productid + '">' +
        '<input type="hidden" name="products[' + count + '][pitemno]" value="' + count + '">' +
        '<td><button class="btn btn-sm btn-outline-danger remove-item" type="button"><i class="fa fa-trash" style="color:red;"></i></button></td>' +
        '</tr>';

    $('#item-list tbody').append(rowHtml);

    $('#prod_desc').val('');
    $('#product_choice').val('');
    $('#qty').val(1);
    $('#price').val('');
     $('#discount').val('');

    calc_total();
}

// Event delegation for dynamically added rows
$(document).on('click', '.delete-item', function () {
  if (confirm('Are you sure you want to delete this item?')) {
            $(this).closest('tr').remove();
           calc_total();
        }
   
    // $(this).closest('tr').remove();
    // calc_total();
});


        function calc_total() {
            var total = 0;
            var pack_price = parseFloat($('#pack_price').val()) || 0;

            $('#item-list tbody tr').each(function() {
               var lineTotal = parseFloat($(this).find('input[name*="[ptotal]"]').val()) || 0;
                // var lineTotal = parseFloat($(this).find('input[name^="products["][name$="[ptotal]"]').val());
                if (!isNaN(lineTotal)) {
                    total += lineTotal;
                }
            });

            console.log("Total before additional payable: " + total);
            total += pack_price;
            console.log("Total after additional payable: " + total);

            $('[name="sub_total"]').val(total);
            $('#sub_total').text(parseFloat(total).toLocaleString('en-US'));
            var pack_total = parseFloat(pack_price) + parseFloat(total);
            var gtotal = parseFloat(pack_total);
            var gt_round = Math.round(gtotal);
            $('[name="total_amount"]').val(gt_round);
            $('#gtotal').text(parseFloat(gt_round).toLocaleString('en-US'));
        }

    $(document).ready(function() {
        console.log("Script loaded and running");

        function updateLineTotal(tr) {
            var qty = parseFloat(tr.find('input[name*="[pqty]"]').val()) || 0;
            var price = parseFloat(tr.find('input[name*="[pprice]"]').val()) || 0;
            var lineTotal = qty * price;

            lineTotal = parseFloat(lineTotal.toFixed(2));

            tr.find('input[name*="[ptotal]"]').val(lineTotal);
            calc_total();
        }

     
        
        // function rem_item(event) {
        //     $(event.target).closest('tr').remove();
        //     calc_total();
        // }

        $('#item-list').on('input', 'input[name*="[pqty]"], input[name*="[pprice]"]', function() {
            var tr = $(this).closest('tr');
            updateLineTotal(tr);
        });

        $('#pack_price').on('input', function() {
            calc_total();
        });

        // Attach delete event to all delete buttons, including existing ones
        $('#item-list').on('click', '.rem_item', function(event) {
            rem_item(event);
        });

        calc_total();  // Initial calculation

        $("#product_choice").change(function() {
            var productname = $(this).val();
            var dataListOptions = document.getElementById('product').querySelectorAll('option');
            var productId = '';

            for (var i = 0; i < dataListOptions.length; i++) {
                if (dataListOptions[i].value === productname) {
                    productId = dataListOptions[i].getAttribute('data-productid');
                    break;
                }
            }
            $("#productid").val(productId);
            $.ajax({
                url: 'getprice.php',
                type: "GET",
                data: {
                    "productname": productname,
                    "productid": productId
                },
                success: function(data) {
                    console.log(data);
                    var jsonData = JSON.parse(data);
                    $("#gst").val(jsonData.gst);
                    if (jsonData.in_ex_gst === "inclusive of GST") {
                        $("#price").val(jsonData.netprice);
                    } else if (jsonData.in_ex_gst === "exclusive of GST") {
                        $("#price").val(jsonData.price);
                    }
                    $("#netprice").val(jsonData.netprice);
                    $("#in_ex_gst").val(jsonData.in_ex_gst);
                }
            });
        });

        // $('#addmore').click(add_more);
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
// document.getElementById('toggleButton').addEventListener('click', function () {
//         const optionalFields = document.getElementById('optionalFields');
//         const icon = this.querySelector('i');

//         optionalFields.classList.toggle('d-none');
//         icon.classList.toggle('fa-plus');
//         icon.classList.toggle('fa-minus');
//     });
</script>
  <script type="text/javascript">
    $(document).ready(function() {
     $('.delete-item').click(function() {
    var itemId = $(this).data('itemid');
    var deleteItemIds = $('#delete_item_ids').val();

    if (confirm('Are you sure you want to delete this item?')) {
        // Add the itemId to the deleteItemIds field
        if (deleteItemIds) {
            deleteItemIds += ',' + itemId;
        } else {
            deleteItemIds = itemId;
        }
        $('#delete_item_ids').val(deleteItemIds);

        // Remove the row
        $(this).closest('tr').remove();

        // Recalculate totals after the row is deleted
        calc_total();  // Recalculates the total
    }
});
});
// $(document).ready(function() {
//     $('.delete-item').click(function() {
//         var itemId = $(this).data('itemid');
//         var deleteItemIds = $('#delete_item_ids').val();

//         if (confirm('Are you sure you want to delete this item?')) {
//             if (deleteItemIds) {
//                 deleteItemIds += ',' + itemId;
//             } else {
//                 deleteItemIds = itemId;
//             }
//             $('#delete_item_ids').val(deleteItemIds);
//             $(this).closest('tr').remove();
//         }
//     });
// });
</script>

</body>
</html>
