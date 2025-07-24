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
<style>
        .additional-charges-list {
        min-height: 60px; /* Ensures space is allocated even if empty */
    }
    .additional-charge-item {
        display: flex;
        justify-content: space-between;
        padding: 4px 0;
        font-size: 14px;
    }
    .additional-charge-item .remove-charge {
        color: red;
        cursor: pointer;
    }

    
  
/* Remove background and border from the section */
.charges-input-container {
    background-color: transparent !important; /* Transparent background */
    border: none;
    padding: 0;
}

/* Remove bold styling from the table footer headers */
/* Remove bold styling and decrease font size in table footer */
#item-list tfoot th {
    font-weight: normal;  /* Remove bold */
    font-size: 14px;      /* Decrease font size */
}
.form-control:disabled, .form-control[readonly] {
    background-color: #e9ecef;
    opacity: 1;
    padding-right: 77px;
}


/* Custom input field styling */
.charges-input {
    border: 1px  #ddd; /* Light gray border */
    border-radius: 5px;
    padding: 8px;
    transition: all 0.3s ease;
    background-color: transparent; /* Transparent background for inputs */
    box-shadow: none; /* Remove default shadow */
}

/* Hover effect for inputs */
.charges-input:hover,
.charges-input:focus {
    border-color: #007bff; /* Change border color on focus */
    outline: none;
}

/* Consistent label styling */
.row strong {
    font-size: 14px;
    margin-bottom: 8px;
    display: inline-block;
}

/* Adjusting input margin for better spacing */
.mb-2 {
    margin-bottom: 12px !important;
}

/* Vertical line styling */
.vertical_line {
    border-left: 1px solid black;
    height: 300px;
    position: absolute;
    left: 70%;
    margin-left: -3px;
    top: 0;
}

/* Remove background color for header */
.invoice-compliance-header {
    background: #f6f6f6; /* Remove background color */
    font-weight: bold;
    cursor: pointer;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
    height: 0px;
}

/* Header hover effect */


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

/* Make the table scrollable horizontally */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    width: 100%;
}

/* Ensure text areas and other fields do not break out of their container */
textarea, input[type="number"] {
    max-width: 100%;
    box-sizing: border-box;
}

/* Ensure the button is fully responsive */
.w-100 {
    width: 100%;
}

/* Media Queries for Different Screen Sizes */

/* Mobile (up to 480px) */
@media (max-width: 480px) {
    .invoice-compliance-header {
        padding: 8px 12px;
    }

    .charges-input-container {
        padding: 0 10px;
    }

    .table-responsive {
        margin-bottom: 15px;
    }

    .table th, .table td {
        font-size: 12px;
        padding: 8px;
    }

    .row strong {
        font-size: 12px;
    }

    .vertical_line {
        height: 200px;
    }

    /* Ensure the button takes full width on mobile */
    .btn-sm {
        width: 100%;
    }

    /* Adjust input fields */
    .charges-input, input[type="number"], textarea {
        font-size: 14px;
        padding: 6px;
    }
}

/* Tablet (up to 768px) */
@media (max-width: 768px) {
    .invoice-compliance-header {
        padding: 10px 15px;
    }

    .charges-input-container {
        padding: 0 15px;
    }

    .table-responsive {
        margin-bottom: 20px;
    }

    .table th, .table td {
        font-size: 14px;
        padding: 10px;
    }

    .vertical_line {
        height: 250px;
    }
    
    button:not(:disabled), [type="button"]:not(:disabled), [type="reset"]:not(:disabled), [type="submit"]:not(:disabled) {
    cursor: pointer;
    margin-top: 4px;
    margin-bottom: 9px;
}

.border-bottom {
    border-bottom: 2px solid #e3eaef !important;
    width: 50%;
}

.b
    /* Adjust the table and form input fields */
    input[type="number"], textarea, .charges-input {
        font-size: 14px;
        padding: 8px;
    }

    .btn-sm {
        width: 100%;
    }
}

/* Desktop (Above 768px, typically larger than tablet sizes) */
@media (min-width: 768px) {
    .invoice-compliance-header {
        padding: 15px 20px;
    }

    .charges-input-container {
        padding: 0 20px;
    }

    .table-responsive {
        margin-bottom: 30px;
    }

    .table th, .table td {
        font-size: 16px;
        padding: 12px;
    }

    .vertical_line {
        height: 300px;
    }

    .btn-sm {
        width: auto;
    }
    .btn-success {
    color: #fff;
    background-color: #2ecc71;
    border-color: #2ecc71;
    margin-left: -21px;
}

    .charges-input, input[type="number"], textarea {
        font-size: 16px;
        padding: 10px;
    }
}

/* Large Screens/Desktops (for very large monitors) */
@media (min-width: 1200px) {
    .invoice-compliance-header {
        padding: 20px 30px;
    }

    .table th, .table td {
        font-size: 18px;
        padding: 14px;
    }

    .charges-input, input[type="number"], textarea {
        font-size: 18px;
        padding: 12px;

    }
    .btn-success {
    color: #fff;
    background-color: #2ecc71;
    border-color: #2ecc71;
    margin-left: -2px;
}
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
              <form action="invoicedb.php" method="POST" enctype="multipart/form-data">

                <div class="row border border-dark" >  
                  <?php include 'fetch_user_data.php'; ?>


<div class="col-md-8 border-right border-dark">
<h6 style="float:left;" class="pt-2">
    <?php echo htmlspecialchars($user['name'] ?? ''); ?><br/>
    <?php echo htmlspecialchars($user['address'] ?? ''); ?><br/>
    Email: <?php echo htmlspecialchars($user['email'] ?? ''); ?><br/>
    Phone: <?php echo htmlspecialchars($user['phone'] ?? ''); ?><br/>
    GSTIN: <?php echo htmlspecialchars($user['gstin'] ?? ''); ?><br/>
    <input type="text" name="business_state" id="business_state" 
           value="<?php echo htmlspecialchars($user['state']); ?>" hidden>
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
                     <td style="text-align:right;" id="final_taxable_amt"></td>
                </tr> 
          
                <tr>
                    <td class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;">Total GST</td>
                    <td style="text-align:right;" id="final_gst_amount">ss</td>
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
                      <th class="" style="width: 60%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom: 0px;" >Grand Total</th>
                     <th class="text-right">
                <span id="gtotal">0.00</span>
                <input type="hidden" name="total_amount" value="0">
            </th>
                    </tr>
               
            </table>
               
                </div>
            </div>
            <div class="row border border-dark mt-3">
    <!-- Transportation Details -->
    <div class="col-md-6 col-12 p-0">
        <div class="invoice-compliance-header" onclick="toggleSection('transportDetails', this)">
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
                                <input type="text" class="form-control" id="roadVehicleNumber" placeholder="Enter Vehicle Number">
                            </div>
                            <div class="col-md-6">
                                <label for="driverName">Driver Name</label>
                                <input type="text" class="form-control" id="driverName" placeholder="Enter Driver Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="licenseNumber">Driver License Number</label>
                                <input type="text" class="form-control" id="licenseNumber" placeholder="Enter License Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadFreightCharges">Freight Charges (USD)</label>
                                <input type="number" class="form-control" id="roadFreightCharges" placeholder="Enter Charges">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadInsurance">Insurance Details</label>
                                <input type="text" class="form-control" id="roadInsurance" placeholder="Enter Insurance Details">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadPermit">Permit Number</label>
                                <input type="text" class="form-control" id="roadPermit" placeholder="Enter Permit Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadContact">Driver Contact</label>
                                <input type="text" class="form-control" id="roadContact" placeholder="Enter Contact Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="roadDistance">Distance (km)</label>
                                <input type="number" class="form-control" id="roadDistance" placeholder="Enter Distance">
                            </div>
                            <!-- Optional Fields (add this block below each transport-specific div) -->
<!-- Transporter Header with Toggle Button -->
<!-- Centered Transporter Header with Toggle Button -->
<!-- Centered Transporter Header with Toggle Button -->
<div class="d-flex justify-content-center mt-3">
    <div class="border p-2 d-flex align-items-center" id="transporterHeader">
        <span class="mr-2">TRANSPORTER (OPTIONAL FIELD)</span>
        <button id="toggleButton" class="btn btn-sm">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>

<!-- Optional Fields Section -->
<div id="optionalFields" class="mt-3 d-none text-center">
    <div class="container-invoice-new">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <label for="optionalField1">Optional Field 1</label>
                <input type="text" class="form-control" id="optionalField1" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-12">
                <label for="optionalValue1">Optional Value 1</label>
                <input type="text" class="form-control" id="optionalValue1" placeholder="Enter Optional Value 1">
            </div>
        </div>
    </div>
</div>

                        </div>
                    </div>

                    <!-- Rail Selected -->
                    <div id="railData" class="transport-mode-data d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="trainNumber">Train Number</label>
                                <input type="text" class="form-control" id="trainNumber" placeholder="Enter Train Number">
                            </div>
                            <div class="col-md-6">
                                <label for="railwayStation">Departure Station</label>
                                <input type="text" class="form-control" id="railwayStation" placeholder="Enter Station">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="arrivalStation">Arrival Station</label>
                                <input type="text" class="form-control" id="arrivalStation" placeholder="Enter Arrival Station">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railwayBooking">Booking Reference</label>
                                <input type="text" class="form-control" id="railwayBooking" placeholder="Enter Booking Reference">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railFreightCharges">Freight Charges (USD)</label>
                                <input type="number" class="form-control" id="railFreightCharges" placeholder="Enter Charges">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railwayCoach">Coach Number</label>
                                <input type="text" class="form-control" id="railwayCoach" placeholder="Enter Coach Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railwaySeat">Seat Number</label>
                                <input type="text" class="form-control" id="railwaySeat" placeholder="Enter Seat Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="railDepartureTime">Departure Time</label>
                                <input type="time" class="form-control" id="railDepartureTime">
                            </div>
                            <div class="d-flex justify-content-center mt-3">
    <div class="border p-2 d-flex align-items-center" id="transporterHeader">
        <span class="mr-2">TRANSPORTER (OPTIONAL FIELD)</span>
        <button id="toggleButton" class="btn btn-sm">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>

<!-- Optional Fields Section -->
<div id="optionalFields" class="mt-3 d-none text-center">
    <div class="container-invoice-new">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <label for="optionalField1">Optional Field 1</label>
                <input type="text" class="form-control" id="optionalField1" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-12">
                <label for="optionalValue1">Optional Value 1</label>
                <input type="text" class="form-control" id="optionalValue1" placeholder="Enter Optional Value 1">
            </div>
        </div>
    </div>
</div>
                        </div>
                    </div>

                    <!-- Air Selected -->
                    <div id="airData" class="transport-mode-data d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="flightNumber">Flight Number</label>
                                <input type="text" class="form-control" id="flightNumber" placeholder="Enter Flight Number">
                            </div>
                            <div class="col-md-6">
                                <label for="departureAirport">Departure Airport</label>
                                <input type="text" class="form-control" id="departureAirport" placeholder="Enter Airport Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="arrivalAirport">Arrival Airport</label>
                                <input type="text" class="form-control" id="arrivalAirport" placeholder="Enter Airport Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airwayBill">Airway Bill Number</label>
                                <input type="text" class="form-control" id="airwayBill" placeholder="Enter Bill Number">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airFreightCharges">Freight Charges (USD)</label>
                                <input type="number" class="form-control" id="airFreightCharges" placeholder="Enter Charges">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airCargoType">Cargo Type</label>
                                <input type="text" class="form-control" id="airCargoType" placeholder="Enter Cargo Type">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airlineName">Airline Name</label>
                                <input type="text" class="form-control" id="airlineName" placeholder="Enter Airline Name">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="airETA">Estimated Time of Arrival</label>
                                <input type="time" class="form-control" id="airETA">
                            </div>
                            <div class="d-flex justify-content-center mt-3">
    <div class="border p-2 d-flex align-items-center" id="transporterHeader">
        <span class="mr-2">TRANSPORTER (OPTIONAL FIELD)</span>
        <button id="toggleButton" class="btn btn-sm">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>

<!-- Optional Fields Section -->
<div id="optionalFields" class="mt-3 d-none text-center">
    <div class="container-invoice-new">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <label for="optionalField1">Optional Field 1</label>
                <input type="text" class="form-control" id="optionalField1" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-12">
                <label for="optionalValue1">Optional Value 1</label>
                <input type="text" class="form-control" id="optionalValue1" placeholder="Enter Optional Value 1">
            </div>
        </div>
    </div>
</div>
                        </div>
                    </div>
                </div>
                <!-- Ship Selected -->
<div id="shipData" class="transport-mode-data d-none">
    <div class="row">
        <div class="col-md-6">
            <label for="shipVesselName">Vessel Name</label>
            <input type="text" class="form-control" id="shipVesselName" placeholder="Enter Vessel Name">
        </div>
        <div class="col-md-6">
            <label for="shipVoyageNumber">Voyage Number</label>
            <input type="text" class="form-control" id="shipVoyageNumber" placeholder="Enter Voyage Number">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipContainerNumber">Container Number</label>
            <input type="text" class="form-control" id="shipContainerNumber" placeholder="Enter Container Number">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipBillOfLading">Bill of Lading Number</label>
            <input type="text" class="form-control" id="shipBillOfLading" placeholder="Enter Bill of Lading Number">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipPortOfLoading">Port of Loading</label>
            <input type="text" class="form-control" id="shipPortOfLoading" placeholder="Enter Port of Loading">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipPortOfDischarge">Port of Discharge</label>
            <input type="text" class="form-control" id="shipPortOfDischarge" placeholder="Enter Port of Discharge">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipFreightCharges">Freight Charges (USD)</label>
            <input type="number" class="form-control" id="shipFreightCharges" placeholder="Enter Charges">
        </div>
        <div class="col-md-6 mt-3">
            <label for="shipEstimatedArrival">Estimated Time of Arrival (ETA)</label>
            <input type="date" class="form-control" id="shipEstimatedArrival">
        </div>
        <div class="d-flex justify-content-center mt-3">
    <div class="border p-2 d-flex align-items-center" id="transporterHeader">
        <span class="mr-2">TRANSPORTER (OPTIONAL FIELD)</span>
        <button id="toggleButton" class="btn btn-sm" onclick="toggleOptionalFields()">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>

<!-- Optional Fields Section -->
<div id="optionalFields" class="mt-3 d-none text-center">
    <div class="container-invoice-new">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <label for="optionalField1">Optional Field 1</label>
                <input type="text" class="form-control" id="optionalField1" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-12">
                <label for="optionalValue1">Optional Value 1</label>
                <input type="text" class="form-control" id="optionalValue1" placeholder="Enter Optional Value 1">
            </div>
        </div>
    </div>
</div>


<!-- Optional Fields Section -->
<div id="optionalFields" class="mt-3 d-none text-center">
    <div class="container-invoice-new">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <label for="optionalField1">Optional Field 1</label>
                <input type="text" class="form-control" id="optionalField1" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-12">
                <label for="optionalValue1">Optional Value 1</label>
                <input type="text" class="form-control" id="optionalValue1" placeholder="Enter Optional Value 1">
            </div>
        </div>
    </div>
</div>
    </div>
</div>

            </div>
        </div>
    </div>
   <!-- Other Details Section -->
<div class="col-md-6 col-12 p-0">
    <div class="invoice-compliance-header" onclick="toggleSection('otherDetails', this)">
        <span>OTHER DETAILS</span>
        <i class="fas fa-chevron-down rotate-icon"></i>
    </div>
    <div id="otherDetails" class="collapse-content">
        <!-- Input Fields for Other Details -->
        <div class="p-3">
            <div class="row">
                <!-- PO Number and PO Date -->
                <div class="col-md-6 mb-3">
                    <label for="poNumber">PO Number</label>
                    <input type="text" id="poNumber" class="form-control" placeholder="Enter PO Number">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="poDate">PO Date</label>
                    <input type="date" id="poDate" class="form-control" placeholder="dd-mm-yyyy">
                </div>
                <!-- Challan Number and Due Date -->
                <div class="col-md-6 mb-3">
                    <label for="challanNumber">Challan Number</label>
                    <input type="text" id="challanNumber" class="form-control" placeholder="Enter Challan Number">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="dueDate">Due Date</label>
                    <input type="date" id="dueDate" class="form-control" placeholder="dd-mm-yyyy">
                </div>
                <!-- EwayBill No and Sales Person -->
                <div class="col-md-6 mb-3">
                    <label for="ewayBill">EwayBill No.</label>
                    <input type="text" id="ewayBill" class="form-control" placeholder="Enter EwayBill No">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="salesPerson">Sales Person</label>
                    <input type="text" id="salesPerson" class="form-control" placeholder="Sales Person">
                </div>
                <!-- Reverse Charge Checkbox -->
                <div class="col-12 mb-3">
                    <input type="checkbox" id="reverseCharge">
                    <label for="reverseCharge">Is transaction applicable for Reverse Charge?</label>
                </div>
                <!-- TCS Value and TCS Tax -->
                <div class="col-md-6 mb-3">
                    <label for="tcsValue">TCS Value</label>
                    <input type="text" id="tcsValue" class="form-control" placeholder="Enter TCS Value">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tcsTax">Enter TCS Tax</label>
                    <select id="tcsTax" class="form-control">
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
 const  cess_rate = $('#cess_rate').val();
 const cess_amount = $('#cess_amount').val();
    let total = 0;
    let cgst = 0;
    let sgst = 0;
    let igst = 0;
    let tot_taxable = 0; 
    let cess_total =0;
    let tol_gst = 0;

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
        tol_gst = parseFloat(tol_gst) + parseFloat(cgst) + parseFloat(sgst);
        console.log("gst amt"+tol_gst);
    } else if (gst_type === "IGST") {
        igst = gstAmount.toFixed(2); // IGST is the full GST amount
        tol_gst = tol_gst +igst ;
    }

    // Total after discount (inclusive price should be used here)
    total = (discountedPrice * parseFloat(qty));  // Total after discount (inclusive price)
} else if (in_ex_gst === "exclusive of GST") {
    gstAmount = (discountedBasePrice * parseFloat(gst)) / 100; // GST amount = Price * GST rate

    // Split GST into CGST/SGST or IGST
    if (gst_type === "CGST/SGST") {
        cgst = (gstAmount / 2).toFixed(2); // CGST is half of the GST amount
        sgst = (gstAmount / 2).toFixed(2); // SGST is half of the GST amount
        tol_gst = tol_gst + cgst + sgst;
    } else if (gst_type === "IGST") {
        igst = gstAmount.toFixed(2); // IGST is the full GST amount
         tol_gst = tol_gst +igst ;
    }

    // Total after discount + GST (for exclusive GST)
    total = (discountedBasePrice + gstAmount) * parseFloat(qty);  // Total after discount + GST
}

cess_total += cess_total;
//tot_taxable = tot_taxable + netprice;

   
   document.getElementById('final_cess_amount').innerText = cess_total.toFixed(2);
  // document.getElementById('final_taxable_amt').innerText = tot_taxable.toFixed(2);
   document.getElementById('final_gst_amount').innerText =  parseFloat(tol_gst).toFixed(2);

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
                 <input type="text" class="form-control" name="cessrateval[]" id="cessrate${itemno}" value="${cess_rate}" hidden>
               <input type="text" class="form-control" name="cessamountval[]" id="cessamount${itemno}" value="${cess_amount}" hidden>
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


     updateFooter(); // Call this function whenever the table is updated

}

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
        $("#cess_rate").val(jsonData.cess_rate);
        $("#cess_amount").val(jsonData.in_ex_gst);
           
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
       // Toggle section visibility
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
    document.getElementById("additional_charges").addEventListener("change", function() {
        addCharge();
    });

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

            // Create new row for the additional charge
            var tbody = document.getElementById("additional-charges-container");
            var row = document.createElement("tr");
            row.id = "charge_" + selectedOption.value;

            row.innerHTML = `
                <th class="text-right" colspan="2">${chargeName}</th>
                <th><input type="number" class="form-control" value="${chargeValue}" readonly></th>
                <th><button type="button" onclick="removeCharge('${row.id}')" class="btn btn-link text-danger">Remove</button></th>
            `;

            tbody.appendChild(row);

            // Clear the dropdown selection
            select.selectedIndex = 0;

            // Update total
            calculateTotal();
        }
    }

    function removeCharge(rowId) {
        var row = document.getElementById(rowId);
        row.parentNode.removeChild(row);

        // Update total
        calculateTotal();
    }

    function calculateTotal() {
        var taxableAmount = parseFloat(document.getElementById("taxable_amount").innerText) || 0;
        var cgstSgst = parseFloat(document.getElementById("cgst_sgst").innerText) || 0;
        var adjustment = parseFloat(document.getElementById("adjustment").value) || 0;

        // Sum additional charges
        var additionalCharges = 0;
        var additionalChargesContainer = document.getElementById("additional-charges-container");
        var chargeInputs = additionalChargesContainer.getElementsByTagName("input");
        for (var i = 0; i < chargeInputs.length; i++) {
            additionalCharges += parseFloat(chargeInputs[i].value) || 0;
        }

        // Calculate total
        var total = taxableAmount + cgstSgst + adjustment + additionalCharges;
        document.getElementById("total_amount").innerText = total.toFixed(2);
    }
    document.getElementById("additional_charges").addEventListener("change", function() {
        addCharge();
    });

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

            // Create new row for the additional charge
            var tbody = document.getElementById("additional-charges-container");
            var row = document.createElement("tr");
            row.id = "charge_" + selectedOption.value;

            row.innerHTML = `
                <th class="text-right" colspan="2">${chargeName}</th>
                <th>
                    <input type="number" class="form-control charge-input" value="${chargeValue}" style="width: 100%;" readonly>
                </th>
                <th>
                    <button type="button" onclick="editCharge('${row.id}')" class="btn btn-link">Edit</button>
                    <button type="button" onclick="removeCharge('${row.id}')" class="btn btn-link text-danger">Remove</button>
                </th>
            `;

            tbody.appendChild(row);

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

    function calculateTotal() {
        var taxableAmount = parseFloat(document.getElementById("taxable_amount").innerText) || 0;
        var cgstSgst = parseFloat(document.getElementById("cgst_sgst").innerText) || 0;
        var adjustment = parseFloat(document.getElementById("adjustment").value) || 0;

        // Sum additional charges
        var additionalCharges = 0;
        var additionalChargesContainer = document.getElementById("additional-charges-container");
        var chargeInputs = additionalChargesContainer.getElementsByClassName("charge-input");
        for (var i = 0; i < chargeInputs.length; i++) {
            additionalCharges += parseFloat(chargeInputs[i].value) || 0;
        }

        // Calculate total
        var total = taxableAmount + cgstSgst + adjustment + additionalCharges;
        document.getElementById("total_amount").innerText = total.toFixed(2);
    }
        function addCharge() {
        const select = document.getElementById('additional_charges');
        const selectedOption = select.options[select.selectedIndex];
        const chargeName = selectedOption.text;
        const chargeValue = selectedOption.getAttribute('data-charge');
        
        if (chargeValue) {
            const container = document.querySelector('.additional-charges-list');
            const chargeItem = document.createElement('div');
            chargeItem.classList.add('additional-charge-item');
            chargeItem.innerHTML = `
                <span>${chargeName}</span>
                <span class="remove-charge" onclick="removeCharge(this)">Remove</span>
                <span>${chargeValue}</span>
            `;
            container.appendChild(chargeItem);
            calculateTotal();
        }
        select.value = ''; // Reset select box
    }

    function removeCharge(element) {
        element.parentElement.remove();
        calculateTotal();
    }

    function calculateTotal() {
        // Recalculate the total amount logic
    }
    function toggleEdit(fieldId) {
        const valueElement = document.getElementById(`${fieldId}_value`);
        const inputElement = document.getElementById(`${fieldId}_input`);
        const isHidden = inputElement.classList.contains('d-none');

        if (isHidden) {
            valueElement.classList.add('d-none');
            inputElement.classList.remove('d-none');
        } else {
            valueElement.classList.remove('d-none');
            inputElement.classList.add('d-none');
        }
    }

    function updateAmounts() {
        const taxableAmount = parseFloat(document.getElementById('taxable_amount_input').value) || 0;
        const cgstSgst = parseFloat(document.getElementById('cgst_sgst_input').value) || 0;
        const adjustment = parseFloat(document.getElementById('adjustment_input').value) || 0;

        document.getElementById('taxable_amount_value').textContent = taxableAmount.toFixed(2);
        document.getElementById('cgst_sgst_value').textContent = cgstSgst.toFixed(2);
        document.getElementById('adjustment_value').textContent = adjustment.toFixed(2);

        calculateTotal();
    }

    function addCharge() {
        const select = document.getElementById('additional_charges');
        const selectedOption = select.options[select.selectedIndex];
        const chargeName = selectedOption.text;
        const chargeValue = parseFloat(selectedOption.getAttribute('data-charge')) || 0;

        if (chargeValue) {
            const container = document.querySelector('.additional-charges-list');
            const chargeItem = document.createElement('div');
            chargeItem.classList.add('additional-charge-item');
            chargeItem.innerHTML = `
                <span>${chargeName}</span>
                <span class="remove-charge" style="color: red; cursor: pointer;" onclick="removeCharge(this, ${chargeValue})">Remove</span>
                <span>${chargeValue.toFixed(2)}</span>
            `;
            container.appendChild(chargeItem);
            calculateTotal();
        }
        select.value = ''; // Reset select box
    }

    function removeCharge(element, chargeValue) {
        element.parentElement.remove();
        calculateTotal();
    }

    function calculateTotal() {
        const taxableAmount = parseFloat(document.getElementById('taxable_amount_input').value) || 0;
        const cgstSgst = parseFloat(document.getElementById('cgst_sgst_input').value) || 0;
        const adjustment = parseFloat(document.getElementById('adjustment_input').value) || 0;
        
        let additionalCharges = 0;
        document.querySelectorAll('.additional-charge-item').forEach(item => {
            const chargeValue = parseFloat(item.lastChild.textContent) || 0;
            additionalCharges += chargeValue;
        });

        const total = taxableAmount + cgstSgst + adjustment + additionalCharges;
        document.getElementById('total_amount').textContent = total.toFixed(2);
    }
    function addCharge() {
        const select = document.getElementById('additional_charges');
        const chargeName = select.value;
        if (chargeName) {
            const container = document.querySelector('.additional-charges-list');
            const chargeItem = document.createElement('div');
            chargeItem.classList.add('additional-charge-item');
            chargeItem.innerHTML = `
                <input type="text" class="form-control text-left charge-name" value="${chargeName}" readonly>
                <input type="number" class="form-control text-right charge-value" value="0" onchange="calculateTotal();">
                <span class="remove-charge" style="color: red; cursor: pointer;" onclick="removeCharge(this)">Remove</span>
            `;
            container.appendChild(chargeItem);
            calculateTotal();
        }
        select.value = ''; // Reset select box
    }

    function removeCharge(element) {
        element.parentElement.remove();
        calculateTotal();
    }

    function calculateTotal() {
        const taxableAmount = parseFloat(document.getElementById('taxable_amount').value) || 0;
        const cgstSgst = parseFloat(document.getElementById('cgst_sgst').value) || 0;
        const adjustment = parseFloat(document.getElementById('adjustment').value) || 0;
        
        let additionalCharges = 0;
        document.querySelectorAll('.charge-value').forEach(input => {
            additionalCharges += parseFloat(input.value) || 0;
        });

        const total = taxableAmount + cgstSgst + adjustment + additionalCharges;
        document.getElementById('total_amount').textContent = total.toFixed(2);
    }
    function toggleOptionalFields() {
        const optionalFields = document.getElementById('optionalFields');
        optionalFields.classList.toggle('d-none'); // Toggle the 'd-none' class to show/hide the fields
    }
    

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
            <button type="button" onclick="removeCharge('${row.id}')" class="btn btn-link text-danger" style="padding:8px 11.5px;border-right:1px solid #ada7a7;">Remove</button>
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