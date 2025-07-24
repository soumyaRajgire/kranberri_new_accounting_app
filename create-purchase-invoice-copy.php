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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- Your JavaScript code -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>



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
                                <h4 class="m-b-10">Purchase Invoice</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Purchase Invoice</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>





        <div class="row">
            <div class="col-lg-9 card" style="height: 770px;">
                        <div style="margin-top: 10px; margin-left: 0px; border: 0.1rem solid #ada7a7;">
                            <div class="row" style="margin-right: 0px; border: 1px solid #ebedf2; margin-left: 0px;">
                                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" style="border-right: 0.1rem solid #ada7a7;">
                                    <div class="-icon" style="margin-top:10px;  margin-bottom:10px;">
                                        <div class="business_details">
                                            <h5><span id="business_name" style="color:black;display: block;"></span>
                                            </h5>
                                            <span id="business_add_1" class="switch_branch" style="color:rgb(66, 139, 202);"></span><br>
                                            <span id="business_add_2" class="switch_branch" style="color:rgb(66, 139, 202);"></span>
                                            <p class="mb-0 switch_branch"> <span id="business_city" style="color:rgb(66, 139, 202);"></span><span id="business_pincode" style="color:rgb(66, 139, 202);"></span></p>
                                            <p class="mb-0 switch_branch" style="margin-bottom: .5rem !important;"><span id="business_state" style="color:rgb(66, 139, 202);"></span><span id="business_country" style="color:rgb(66, 139, 202);"></span></p>
                                            <h6 class="line-height-70 switch_branch" id="cust_gst_div" style="color:rgb(66, 139, 202);">GSTIN: <span id="cust_gst"></span></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                    <div class="  input-group " style="padding-top: 15px;">
                                        <div class="input-group input-group-sm">
                                            <div class="kt-input-icon kt-input-icon--right" style="width:60%">
                                                <input type="text" class="form-control form-control-sm inv_no" placeholder=" ">
                                            </div>
                                            <div class="input-group-append" style="width:40%">
                                                <button class="btn btn-sm btn-secondary" type="button" style="width: 145px; font-weight:600"> <span class=""></span>
                                                    Purchase No </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="padding-top: 12px;" class="input-group">
                                        <input style="color:black!important;" type="text" class="form-control kt-input form-control-sm inv_date" placeholder="Purchase Invoice Date" id="" />
                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-secondary" type="button" style="font-weight:600"> Date </button>
                                        </div>
                                    </div>
                                    <div style="padding-top:12px;" class="input-group date">
                                        <input style="color:black !important;" type="text" class="form-control kt-input form-control-sm pay_due_date" placeholder="Due Date" id="" />
                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-secondary mb-2" type="button" style="font-weight:600"> Due Date</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-right: 0px; margin-left: 0px;" class="row">
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="border: 0.1rem solid #ada7a7;    border-right: none; border-left: none;">
                                    <div class="-icon" style="margin-top:10px;  margin-bottom:10px;">
                                        <h6 style="margin-top:10px;color:black;">Supplier Info <br>
                                            <a href="javascript:;" class="item_act_btn_edits" id="supplier_list" style="color: rgb(187, 17, 68);">Select Supplier</a>
                                        </h6>
                                    </div>
                                    <!-- <div id="cust_det_div" class="disp-none" style="margin-top:10px;  margin-bottom:10px;">
                                        <p class="mb-0 item_act_btn_edits" id="supp_name" title="Supplier Name">Supplier
                                            Name</p>
                                        <p class="mb-0 item_act_btn_edits" id="supp_name" title="Business Name">Business Name</p>
                                        <p class="mb-0 item_act_btn_edits"><span>POS: </span><span id="pos_name" title="Place of Supply"></span></p>
                                        <p class="mb-0 item_act_btn_edits"><span id="cust_gst_text">GSTIN: </span><span id="supplier_gst"></span></p>
                                        <p class="mb-0 item_act_btn_edits" id="supplier_add_1" title="Address Line 1">
                                            Address Line 1</p>
                                        <p class="mb-0 item_act_btn_edits" id="supplier_add_2" title="Address Line 2">
                                            Address Line 2</p>
                                        <p class="mb-0 item_act_btn_edits"><span id="supplier_city" title="City">Update
                                                City</span><span id="supplier_pincode" title="Pincode">Update Pincode
                                            </span></p>
                                        <p class="mb-0 item_act_btn_edits"><span id="supplier_state" title="State">State</span><span id="supplier_country" title="Country">Country</span></p>
                                    </div> -->
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="border: 0.1rem solid #ada7a7;border-right:none">
                                    <div id="cust_det_div1" class="disp-none" style="margin-top:10px;  margin-bottom:10px;">


                                        <h6 style="margin-top:10px;  margin-bottom:10px; color:black;">Billing Address
                                        </h6>
                                        <p class="mb-0 item_act_btn_edits" id="business_addr_1" title="Address Line 1">
                                        </p>
                                        <p class="mb-0 item_act_btn_edits" id="business_addr_2" title="Address Line 2">
                                        </p>
                                        <p class="mb-0 item_act_btn_edits"><span id="business_citys" title="City"></span><span id="business_pincodes" title="Pincode">
                                            </span></p>
                                        <p class="mb-0 item_act_btn_edits"><span id="business_states" title="State"></span><span id="business_countrys" title="Country"></span></p>

                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="border: 0.1rem solid #ada7a7;border-right:none;">
                                    <div id="cust_det_div2" class="disp-none" style="margin-top:10px;  margin-bottom:10px;">


                                        <h6 style="margin-top:10px;  margin-bottom:10px; color:black;">Shipping Address
                                        </h6>
                                        <p class="mb-0 item_act_btn_edits" id="shipping_addr_1" title="Address Line 1">
                                        </p>
                                        <p class="mb-0 item_act_btn_edits" id="shipping_addr_2" title="Address Line 2">
                                        </p>
                                        <p class="mb-0 item_act_btn_edits"><span id="shipping_citys" title="City"></span><span id="shipping_pincodes" title="Pincode"></span>
                                        </p>
                                        <p class="mb-0 item_act_btn_edits"><span id="shipping_states" title="State"></span><span id="shipping_countrys" title="Country"></span></p>

                                    </div>
                                </div>
                            </div>


                            <!--biling and shipping !-->


                            <div style="border: 0.1rem solid #ada7a7; border-right: none; border-left: none; border-top: none;">
                                <table id="item_master_table" style="background-color:white;display: block;white-space: nowrap;margin-bottom:0px" class="table kt-table table-striped table-bordered">
                                    <thead>
                                        <tr style="background-color:#f7f8fa;">
                                            <th style="width:350px;display:flex;"> Item
                                                <div class=" dropdown" style="width:auto;margin-left: 208px;">
                                                    <a class="dropdown-toggle" id="dropdownMenuButton" style="font-size: 10px;color:#5867dd;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="width:50%;">
                                                        New Item
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start">
                                                        <a class="dropdown-item add_product_btn" value="1" href="javascript:;"> Add Goods</a>
                                                        <a class="dropdown-item add_product_btn" value="2" href="javascript:;"> Add Services</a>
                                                    </div>
                                                </div>
                                            </th>
                                            <th> Qty </th>
                                            <th> Rate </th>
                                            <td id="gst_headtext" style="display:none"> GST </td>
                                            <th> Amount </th>
                                        </tr>
                                    </thead>
                                    <tbody id="item_master_table_body" style="background-color:#f7f8fa;">
                                        <tr class="tr_clone" id="item_std_tr" item-id=0>
                                            <td class="pr-pl-0" style="display:none">
                                                <input item-added="0" type="number" item_name="" item_total_amt="" item-inner-id=0 item_pid="" item_gst_rate="" class="pr-pl-0 tc   form-control kt-input item_sno" disabled="disabled" value="1">
                                            </td>
                                            <td style="width:350px">
                                                <select item-inner-id="0" placeholder="Select Item" class="form-control form-control-sm item_list kt-select2" style="width:100%;font-size:13px;margin-bottom:5px;margin-top:5px;">
                                                </select>
                                                <!-- <textarea class="form-control form-control-sm item_descr disp-none" item-inner-id=0 rows="2" style="width:100%;margin-bottom:5px;margin-top:5px;" placeholder="Description"></textarea>
                                                <select item-inner-id="0" class="form-control form-control-sm expense_list_pur disp-none" aria-invalid="false" style="width:100%;margin-bottom:5px;margin-top:5px;">
                                                    <option value='0'>Expense Type</option>
                                                </select> -->
                                                <select item-inner-id="0" placeholder="Sub Expense Type" class="form-control form-control-sm sub_expense kt-select2" style="width:100%;font-size:13px;margin-bottom:5px;margin-top:5px;display:none;">
                                                    <option value="">Add Sub-Account</option><select>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" class="form-control kt-input item_qty" item-inner-id=0 id="item_qty" placeholder="Qty">
                                                </div>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" item-inner-id=0 class="tc form-control item_rate mr-2" min="0" value="" placeholder="Rate" readonly>
                                                </div>
                                                <input type="text" item-inner-id=0 step="0.01" style="display:none;" class="pr-pl-0 tc form-control kt-input hide form-control-sm item_disc" min="0" placeholder="Discount" value="Discount">

                                                <input type="text" item-inner-id=0 step="0.01" style="display:none;" class="pr-pl-0 tc form-control kt-input hide form-control-sm item_per_rate" min="0" placeholder="Item per rate" value="">
                                            </td>
                                            <td class="pr-pl-0 gst" style="display:none">
                                                <input type="number" class="pr-pl-0 tc form-control  form-control-sm item_gst_amt" readonly="" min="0" value="">
                                                <p class="gst_text" style="margin:10px 0px; display:none;"> GST: <span class="gst gst_per_rate"> </span> </p>
                                                <p class="gst item_type_span"> <span class="item_code"></span> </p>

                                                <Select style="width:78px;display:none; " class="form-control kt-input form-control-sm item_type" item-inner-id=0 placeholder="Type">
                                                    <option class="dropdown-item" disabled selected>Type</option>
                                                    <option class="dropdown-item" value="1">Goods</option>
                                                    <option class="dropdown-item" value="2">Service</option>
                                                </Select>

                                                <input style="display:inline-block;float:right;display:none; " type="text" item-inner-id=0 class="form-control kt-input form-control-sm item_hsn" value="" placeholder="HSN | SAC">
                                                <input type="number" class="pr-pl-0 tc form-control form-control-sm  item_gst_per disp-none" min="0" value="" palceholder="GST">
                                                </div>
                                            </td>
                                            <td class="pr-pl-5 taxable_amt" style="display:none">
                                                <input type="number" item-inner-id=0 step="0.01" disabled="disabled" class="pr-pl-0 tc form-control kt-input form-control-sm item_non_taxable" min="0" value="">
                                            </td>
                                            <td class="pr-pl-5 taxable_amt" style="display:none">
                                                <input type="number" item-inner-id=0 step="0.01" class="pr-pl-0 tc form-control kt-input form-control-sm item_taxable" min="0" disabled="" value="">
                                            </td>
                                            <td class="pr-pl-0  " style="display:none">
                                                <input type="number" item-inner-id=0 step="0.01" class="pr-pl-0 tc form-control kt-input form-control-sm item_cgst_per" min="0" value="">
                                                <input type="number" item-inner-id=0 step="0.01" class="pr-pl-0 tc form-control kt-input form-control-sm item_cgst_amt" min="0" value="" placeholder="Rate">
                                            </td>

                                            <td class="pr-pl-0  " style="display:none">
                                                <input type="number" item-inner-id=0 step="0.01" class="pr-pl-0 tc form-control kt-input form-control-sm item_sgst_per" min="0" value="">
                                                <input type="number" item-inner-id=0 step="0.01" class="pr-pl-0 tc form-control kt-input form-control-sm item_sgst_amt" min="0" value="">
                                            </td>
                                            <td class="pr-pl-0  " style="display:none">
                                                <input type="number" item-inner-id=0 step="0.01" class="pr-pl-0 tc form-control kt-input  form-control-sm item_igst_per" min="0" value="">
                                                <input type="number" item-inner-id=0 step="0.01" class="pr-pl-0 tc form-control kt-input  form-control-sm item_igst_amt" min="0" value="">
                                            </td>
                                            <td class="pr-pl-5" style="display:none">
                                                <input type="number" item-inner-id=0 step="0.01" class="pr-pl-0 tc form-control kt-input  form-control-sm item_cess_amt" min="0">
                                            </td>
                                            <td style="position:relative">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="tc form-control item_total_amt mr-2" disabled="" min="0" value="" placeholder="Total Amount">
                                                    <!-- <div class="input-group-append">
                                                                <button style="display:none;" type="button" class="btn btn-danger btn-sm btn-icon item_act_btn_del">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div> -->
                                                </div>
                                                <div class="input-group input-group-sm">
                                                    <h6 style="margin: 10px;"><span class="item_act_btn_del" style="display:none;"> <a href="javascript:;" class="text-danger">Remove Item</a></span></h6>
                                                </div>
                                                <button style="margin:auto;float: right;position: absolute;bottom: 5px;right: 5px;display:none; " item-inner-id=0 type="button" class="btn btn-label-primary btn-sm item_act_btn_add  disp-none"><i class="fa fa-check"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pl-0 pr-0">
                                    <textarea class="form-control" id="notes" placeholder="Notes" aria-invalid="false" style="height:100%" rows="3" maxlength="990"></textarea>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pl-0 pr-0" style="border-left:  0.1rem solid #ada7a7;">
                                    <table style="background-color:#ECEFF1;border-right: 1px solid #ada7a7;width:100%;padding:0px;height:100%;margin-bottom:0px;" class="table table-bordered table-sm">
                                        <tbody>
                                            <tr class="cgsgt" style="display:none">
                                                <td style="width:80%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom:0px;">
                                                    CGST & SGST
                                                </td>
                                                <td style="width:35%;text-align:right;vertical-align: middle;" id="final_cgsts_amt">
                                                    0
                                                </td>
                                            </tr>

                                            <tr style="display:none">
                                                <td style="width:80%;vertical-align: middle;">Central Goods and Services Tax
                                                </td>
                                                <td style="width:35%;text-align:right;vertical-align: middle;" id="final_cgst_amt">
                                                    0
                                                </td>
                                            </tr>
                                            <tr style="display:none">
                                                <td style="width:80%;vertical-align: middle;">State Goods and Services Tax
                                                </td>
                                                <td style="width:35%;text-align:right;vertical-align: middle;" id="final_sgst_amt">
                                                    0
                                                </td>
                                            </tr>
                                            <tr class="integratedgs" style="display:none">
                                                <td style="width:80%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom:0px;">
                                                    Integrated Goods and Services
                                                    Tax </td>
                                                <td style="width:35%;text-align:right;vertical-align: middle;" id="final_igst_amt">
                                                    0
                                                </td>
                                            </tr>
                                            <tr class="Compensation" style="display:none">
                                                <td style="width:80%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom:0px;">
                                                    GST Compensation Cess</td>
                                                <td style="width:35%;text-align:right;vertical-align: middle;" id="final_cess_amt">
                                                    0
                                                </td>
                                            </tr>
                                            <tr id="adjust_div">
                                                <td style="width:80%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom:0px;">
                                                    Adjustment</td>
                                                <td style="width:10%;text-align:right;padding-right:0px;">
                                                    <input style="text-align:right;color:black;" type="number" class="form-control m-input form-control-sm" value="0" id="final_adjust_amt">
                                                </td>
                                            </tr>
                                            <tr class="duty_div" style="display:none;">
                                                <td style="width:80%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom:0px;">
                                                    Basic Customs Duty</td>
                                                <td style="width:10%;text-align:right;padding-right:0px;">
                                                    <input style="text-align:right;color:black;" type="number" class="form-control m-input form-control-sm" value="0" id="basic_custom_duty">
                                                </td>
                                            </tr>
                                            <tr class="duty_div" style="display:none;">
                                                <td style="width:80%;vertical-align: middle;border-right: 1px solid #ada7a7;border-bottom:0px;">
                                                    Countervailing Duty (CVD)</td>
                                                <td style="width:10%;text-align:right;padding-right:0px;">
                                                    <input style="text-align:right;color:black;" type="number" class="form-control m-input form-control-sm" value="0" id="counter_duty">
                                                </td>
                                            </tr>
                                            <tr class="duty_div" style="display:none;">
                                                <td style="width:80%;vertical-align: middle;border-right: 1px solid #ada7a7;">
                                                    Additional Customs Duty or
                                                    Special CVD</td>
                                                <td style="width:10%;text-align:right;padding-right:0px;">
                                                    <input style="text-align:right;color:black;" type="number" class="form-control m-input form-control-sm" value="0" id="counter_duty_add">
                                                </td>
                                            </tr>
                                            <tr id="add_charges_div">
                                                <td class="charge" style="width:80%;vertical-align: middle; border-right: 1px solid #ada7a7;border-bottom: 0px;">
                                                    <select class="form-control  form-control-sm additional_charges" id="additional_charges" aria-invalid="false" style="">
                                                        <option value="0">Select Additional Charges</option>
                                                    </select>
                                                </td>
                                                <td class="charge" style="width:10%;text-align:right;padding-right:0px;">
                                                    <input style="text-align:right;color:black;padding-right:4px;" type="number" class="form-control  form-control-sm" value="0" disabled>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:70%;vertical-align: middle;border-right: 1px solid #ada7a7;">
                                                    Total</td>
                                                <td style="width:30%;text-align:right;">
                                                    <h5 id="final_total_amt">0</h5>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pl-0 pr-0" style="border-top: 0.1rem solid #ada7a7;">
                                    <textarea class="form-control " id="terms_conditions" placeholder="Terms and Conditions" aria-invalid="false" style="margin: 0px;height: 100%;" maxlength="990" rows="5"></textarea>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pl-0 pr-0" style="border-top: 0.1rem solid #ada7a7;border-left:  0.1rem solid #ada7a7;">
                                    <h6 class="p-2" style="color:black;display: block;">For <span id="business_names"></span></h6>
                                    <h6 class="pl-2" style="padding-top: 75px; color:black;display: block;">Authorised
                                        Signatory</h6>
                                </div>
                            </div>
                    
               
            </div>
        </div>

        <div class="col-lg-3">
            <div class="kt-portlet kt-portlet--responsive-mobile page_1">
                <div class="kt-portlet__body p-2">
                    <div class="accordion accordion-toggle-plus" id="accordionExample4">
                        <div class="card" style="margin-top: -8px; border-radius: 5px;">
                            <div class="card-body " style="padding: 0rem;border-bottom: 1px solid #ebedf2;">
                                <table style="background-color:#f7f8fa;margin-bottom: 0rem;" class="table table-bordered table-sm ">
                                    <tbody>
                                        <tr class="type">
                                            <td style="width:55%;vertical-align: middle;"><label style="padding-top: 8px;color:black;" data-container="body" data-toggle="kt-tooltip" data-placement="right" data-skin="light">Type</label></td>
                                            <td>
                                                <div class="btn-group dropdown" style="width:auto;">
                                                    <button class="btn btn-sm btn-brand dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:50%;">
                                                        <span class="doc_types"> Purchase Invoice</span>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -98px, 0px);">
                                                        <a class="dropdown-item create purchase-order" href="create-purchase-order.php"> Purchase
                                                            Order</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="create_by">
                                            <td style="width:55%;vertical-align: middle;">
                                                <label style="padding-top: 8px;color:black;" data-container="body" data-toggle="kt-tooltip" data-placement="right" data-skin="light" title="Map the Purchase Invocie to an employee to track conversion ration,purchase and the payable by employees">Created
                                                    By</label>
                                            </td>
                                            <td>
                                                <select style="width:100%" class="form-control  form-control-sm" id="req_by" aria-invalid="false" style=""></select>
                                            </td>
                                        </tr>
                                        <tr class="create_by">
                                            <td style="width:55%;vertical-align: middle;">
                                                <label style="padding-top: 8px;color:black;" data-container="body" data-toggle="kt-tooltip" data-placement="right" data-skin="light" title="Type of notification to be sent to the Supplier creating this quote or Purchase Invoice">Notification</label>
                                            </td>
                                            <td>
                                                <select class="form-control  form-control-sm" id="notify_settings" aria-invalid="false">
                                                    <!-- <option value="1">Email Only</option> -->
                                                    <option value="2">SMS Only</option>
                                                    <!-- <option value="3" >Email &amp; SMS</option> -->
                                                    <option value="0" selected="selected" class="notify_doc_type">No Email &amp; SMS
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="tax">
                                            <td style="width:10%;vertical-align: middle;" colspan="2">
                                                <input type="checkbox" value="1" class="tax_mode" data="no_gst" id="special_supply" readonly>
                                                <label style="margin: 5px;" data-container="body" data-toggle="kt-tooltip" data-placement="right" data-skin="light" title="">Specialised Supply</label>
                                            </td>
                                        </tr>
                                        <tr class="tax">
                                            <td style="width:10%;vertical-align: middle;" colspan="2"><input type="checkbox" class="reverse-charge-status" value="1" id="reverse_charge">
                                                <label style="margin: 5px;" data-container="body" data-toggle="kt-tooltip" data-placement="right" data-skin="light">Apply Reverse Charge</label>
                                            </td>
                                        </tr>
                                        <tr class="tax">
                                            <td style="width:10%;vertical-align: middle;" colspan="2"><input type="checkbox" value="1" id="input_tax_select" checked>
                                                <label style="margin: 5px;" data-container="body" data-toggle="kt-tooltip" data-placement="right" data-skin="light">Claim ITC?</label>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                            
                        </div>
                    </div>
                    <button id="invoice-btn-copy" type="button" class="btn btn-success create-invoice-btn" style="width:100%;"><span class="invoice-btn-text" style="font-weight: 600;"> Create Purchase Invoice</span></button>
                </div>
            </div>
        </div>
   
    </div>
</section>           
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>