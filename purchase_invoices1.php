<!DOCTYPE html>
<?php
session_start(); 
if(!isset($_SESSION['LOG_IN'])){
   header("Location:login.php");
}
else
{
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
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

<style>
  .dropdown-card {
    display: none;
    margin-top: 10px;
  }

  .active-card {
    display: block;
  }

          #purchase-invoices-datatable th,
          #purchase-order-datatable th,
          #voucher-datatable th,
          #payroll-datatable th,
          #debit-note-datatable th,
          #party-wise-datatable th,
          #accounts-datatable th {
        text-transform: capitalize;
        font-size: 14px;
    }
</style>

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
                                <h4 class="m-b-10">Purchase Invoices</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Purchase Invoices</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>


    <div class="card" style="border-radius: 5px;">
        <div class="row">
            <div class="col-lg-12">
                <ul class="filter-list list-unstyled mt-3 mx-2">
                    <div class="row">
                        <div class="col-lg-2">
                            <li>
                            <div class="dropdown mx-2">
    <a class="btn btn-secondary dropdown-toggle" href="#" type="button" id="purchaseDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Purchase Invoices</a>
    <!-- Dropdown content -->
    <div class="dropdown-menu" aria-labelledby="purchaseDropdown">
        <a class="dropdown-item mfilter" data-filter="pi" onclick="selectDropdownOption(this, 'invoicesCard')">Purchase Invoices</a>
        <a class="dropdown-item mfilter" data-filter="po" onclick="selectDropdownOption(this, 'orderCard')">Purchase Order</a>
        <a class="dropdown-item mfilter" data-filter="vi" onclick="selectDropdownOption(this, 'voucherCard')">Voucher</a>
        <a class="dropdown-item mfilter" data-filter="pay" onclick="selectDropdownOption(this, 'PayrollCard')">Payroll</a>
        <a class="dropdown-item mfilter" data-filter="dn" onclick="selectDropdownOption(this, 'DebitNoteCard')">Debit Note</a>
        <a class="dropdown-item mfilter" data-filter="party-wise" onclick="selectDropdownOption(this, 'PartyWiseCard')">Party Wise Payable</a>
        <a class="dropdown-item mfilter" data-filter="acc_pay" onclick="selectDropdownOption(this, 'AccountsCard')">Accounts Payable</a>
        <!-- Add more options as needed -->
        <a class="dropdown-item mfilter" data-filter="sync_gstn" onclick="selectDropdownOption(this, 'SyncGSTNCard')">Sync from GSTN</a>
        <a class="dropdown-item mfilter" data-filter="import_excel" onclick="selectDropdownOption(this, 'ImportExcelCard')">Import Excel</a>
    </div>
</div>
                            </li>
                        </div>
<script>
    function selectDropdownOption(element) {
        var selectedOptionText = element.textContent;
        document.getElementById("purchaseDropdown").innerText = selectedOptionText;
    }
</script>
                        <div class="col-lg-2">
                            <a class="btn btn-success dropdown-toggle" href="" type="button" id="createDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Create</a>
                                    <!-- Dropdown content -->
                                    <div class="dropdown-menu" aria-labelledby="createDropdown">
                                        <!-- Dropdown items -->
                                        <a class="dropdown-item" target="_blank" href='create-purchase-invoice.php'>Purchase Invoice</a>
                                        <a class="dropdown-item" target="_blank" href='create-purchase-order.php'>Purchase Order</a>
                                        <a class="dropdown-item" onclick="openQuickVoucherModal()" target="_blank">Voucher Payment</a>
                                        <a class="dropdown-item" onclick="openSalaryPaymentModal()" target="_blank">Salary Payment</a>
                                        <a class="dropdown-item" onclick="openDebitNoteModal()">Debit Note</a>
                                    </div>
                        </div>
                        <div class="col-lg-4">
                           <ul class="nav mt-1" style="margin-left: -50px;">
                           <li class="nav-item">
                            <div class="input-group">
                             <div class="row">
                              <input type="text" class="form-control" style="width: 400px;" placeholder="Search Purchase Invoice..." id="generalSearch1">
                               <div class="input-group-append">
                                  <span class="input-group-text"><i class="fas fa-search"></i></span>
                              </div>
                             </div>
                            </div>
                           </li>
                           </ul>
                        </div>

   
                        
                        <div class="col-lg-2">
                            <li>
                                <div class="input-group pull-right date-range-picker mt-1" style="margin-left: 100px;">
                                <div class="row"> 
                                <input type="text" class="form-control form-control input date-filter bg-white"  style="margin-left: -30px;" readonly placeholder="Date range" />
                                </div>
                                <div class="input-group-append" style="margin-left: -10px;">
                                <span class="input-group-text"><i class="fa fa-calendar-check" ></i></span>
                                </div>
                            </div>
                            </li>
                        </div>

                        <div class="col-lg-1">
                            <li>
                                <div class="dropdown mt-1" data-toggle="tooltip" data-placement="top" title="Filter"  style="margin-left: 90px;">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  style="width: 70px;">
                                        <i class="fa fa-filter"></i> &nbsp; <span class="filter-text"></span>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item quick-filter" data-filter="All" href="#">All</a>
                                        <a class="dropdown-item quick-filter" data-filter="Paid" href="#">Paid</a>
                                        <a class="dropdown-item quick-filter" data-filter="Unpaid" href="#">Unpaid</a>
                                        <a class="dropdown-item quick-filter" data-filter="Part Paid" href="#">Part Paid</a>
                                        <a class="dropdown-item quick-filter" data-filter="DELETED" href="#">Deleted</a>
                                    </div>
                                </div>
                            </li>
                        </div>
                    </div>
                </ul>
            </div>
        </div>
    </div>

<!-- Cards for each option -->
<div class="row">
   <div class="col-md-9" style="margin-top: -30px;">
<div id="invoicesCard" class="dropdown-card active-card">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Purchase Invoices</h5>
      <!-- Add your table content here -->
      <table class="table" id="purchase-invoices-datatable">
        <!-- Table content goes here -->
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Purchase Invoice</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">
                        <span>No records found</span>
                        </td>
                    </tr>
                </tbody>
      </table>
    </div>
  </div>
</div>

<div id="orderCard" class="dropdown-card">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Purchase Order</h5>
      <!-- Add your table content here -->
      <table class="table" id="purchase-order-datatable">
        <!-- Table content goes here -->
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Number</th>
                        <th>Amount</th>                 
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4">
                        <span>No records found</span>
                        </td>
                    </tr>
                </tbody>
      </table>
    </div>
  </div>
</div>

<div id="voucherCard" class="dropdown-card">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Voucher</h5>
      <!-- Add your table content here -->
      <table class="table" id="voucher-datatable">
        <!-- Table content goes here -->
                <thead>
                    <tr>
                        <th>Payee</th>
                        <th>Number</th>
                        <th>Amount</th>                 
                        <th>Accounting</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4">
                        <span>No records found</span>
                        </td>
                    </tr>
                </tbody>
      </table>     
    </div>
  </div>
</div>

<div id="PayrollCard" class="dropdown-card">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Payroll</h5>
      <!-- Add your table content here -->
      <table class="table" id="payroll-datatable">
        <!-- Table content goes here -->
                <thead>
                    <tr>
                        <th>Payee</th>
                        <th>Number</th>
                        <th>Amount</th>                 
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4">
                        <span>No records found</span>
                        </td>
                    </tr>
                </tbody>
      </table>     
    </div>
  </div>
</div>

<div id="DebitNoteCard" class="dropdown-card">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Debit Note</h5>
      <!-- Add your table content here -->
      <table class="table" id="debit-note-datatable">
        <!-- Table content goes here -->
                <thead>
                    <tr>
                        <th>Debit Amount</th>
                        <th>Supplier Name</th>
                        <th>Note Number</th>
                        <th>Document</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">
                        <span>No records found</span>
                        </td>
                    </tr>
                </tbody>
      </table>
    </div>
  </div>
</div>

<div id="PartyWiseCard" class="dropdown-card">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Party Wise Payable</h5>
      <!-- Add your table content here -->
      <table class="table" id="party-wise-datatable">
        <!-- Table content goes here -->
                <thead>
                    <tr>
                        <th>Balance Amount</th>
                        <th>Supplier Name</th>
                        <th>Paid Amount</th>
                        <th>Last Payment</th>
                        <th>GST ITC</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">
                        <span>No records found</span>
                        </td>
                    </tr>
                </tbody>
      </table>
    </div>
  </div>
</div>

<div id="AccountsCard"  class="dropdown-card">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Accounts Payable</h5>
      <!-- Add your table content here -->
      <table class="table" id="accounts-datatable">
        <!-- Table content goes here -->
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Purchase Invoice</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">
                        <span>No records found</span>
                        </td>
                    </tr>
                </tbody>
      </table>
    </div>
  </div>
</div>
</div>

<div class="col-md-3" style="margin-top: -20px;">
    <div class="card" style=" margin-left: -10px;">

    </div>
</div>
</div>
<!-- Add other card elements for remaining options -->


<!-- Vocher popup modal -->
<div class="modal fade" id="quickVoucherModal" tabindex="-1" role="dialog" aria-labelledby="quickVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 60%; margin-left: 25%;">
        <div class="modal-content">
        <div class="container mt-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet kt-portlet--responsive-mobile page_1 mb-3">
                <div class="kt-portlet__body p-3 row">
                    <div class="col-md-12">
                        <ul class="nav">
                         <li class="nav-item">
    <div class="btn-group dropdown">
        <h5 id="receipt_title">Create Voucher</h5>
    </div>
</li>


                            <li class="nav-item" style="margin-left: 150px;">
                                <select class="form-control form-control-sm" id="notify_settings" name="notification">
                                    <option value="0">Select</option>
                                    <option value="2">SMS only</option>
                                    <option value="4" selected>No Email & SMS</option>
                                </select>
                            </li>
                            <li class="nav-item" style="margin-left: 160px;">
                                <div class="btn-group btn-group-sm btn_filter">
                                    <button type="button" class="btn btn-outline-primary add_cust_filter create_tab active">Create</button>
                                    <button type="button" class="btn btn-outline-primary add_cust_filter reconcile_tab">Reconcile</button>
                                </div>
                            </li>
                            <!-- <li class="nav-item">
                                <button type="button" onclick="parent.modal_close();" class="close" style="margin-top: 5px;"><i class="fa fa-times"></i></button>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="create_tab">
        <div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px; border: 0.1rem solid #ada7a7;">
                <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                    <div class="row">
                        <div class="col-md-7 border-right">
                            <div class="-icon mt-3 mb-3">
                                <div class="business_details">
                                    <h5 class="text-dark"><span id="seller_name"></span></h5>
                                    <span id="seller_add_1" class="text-primary"></span><br>
                                    <span id="seller_add_2" class="text-primary"></span>
                                    <p class="mb-0"><span id="seller_city" class="text-primary"></span> - <span id="seller_pincode" class="text-primary"></span></p>
                                    <p class="mb-0" style="margin-bottom: .5rem !important;"><span id="seller_state" class="text-primary"></span>, <span id="seller_country" class="text-primary"></span></p>
                                    <h6 class="line-height-70" id="seller_gst_div" style="color:rgb(66, 139, 202);">GSTIN: <span id="seller_gst"></span></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group mt-3">
                                <input style="color:#495057;font-weight: 400;" type="text" class="form-control form-control-sm rec_no" placeholder="">
                                <button class="btn btn-sm btn-secondary" type="button" style="width: 145px; font-weight:600;color:white;">Voucher No</button>
                            </div>
                            <div class="input-group mt-3">
                                <input style="color:#495057;font-weight: 400;" type="text" class="form-control form-control-sm rec_date" placeholder="" />
                                <button class="btn btn-sm btn-secondary" type="button" style="width: 145px; font-weight:600;color:white;">Voucher Date</button>
                            </div>
                            <div style="padding-top: 12px;">
                            <div class="form-group">
    <select class="form-control select2-hidden-accessible" id="payment_mode" name="param" style="font-size: .875rem; width: 100%;" tabindex="-1" aria-hidden="true">
        <option value="direct_deposit">Direct Deposit</option>
        <option value="neft_rtgs">NEFT/RTGS</option>
        <option value="online_payment">Online Payment</option>
        <option value="credit_debit_card">Credit/Debit Card</option>
        <option value="demand_draft">Demand Draft</option>
        <option value="cheque">Cheque</option>
        <option value="cash">Cash</option>
    </select>
</div>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top">
                        <div class="col-md-12">
                            <div class="row mt-3">
                                <div class="col-7">
                                    <div class="form-group">
                                        <h6 style="font-weight:400;">Contact Name<a class="add_supplier_btn" style="float:right;font-size: 10px;" href="javascript:;">Add Supplier</a></h6>
                                        <select placeholder="Select Supplier" class="form-control m-select2 contact_list" style="width:100%;" id="contact_list"></select>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <h6 style="font-weight:400;">Amount</h6>
                                        <div class="input-group input-group-sm">
                                            <input type="number" min="0" step="0.01" id="amount" class="form-control total_amt" placeholder="Amount" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 border-top border-left border-bottom">
                            <textarea class="form-control" id="notes" placeholder="Note" aria-invalid="false" style="margin: 0px;height: 100%;" maxlength="990" rows="5"></textarea>
                        </div>
                        <div class="col-md-5 border-top border-left border-bottom">
                            <h6 class="p-2 text-dark">For <span id="seller_names"></span></h6>
                            <h6 class="pl-2" style="padding-top: 75px; color:black;display: block;">Authorised Signatory</h6>
                        </div>
                    </div>
                    <br>
                    <div class="row" style="margin-left: 0px;margin-right: 0px;">
                      <div class="col-12" data-toggle="collapse" data-target="#collapseTwo4">
                          <h5 style="font-weight:400;" class="dropdown-toggle">For Internal Use</h5>
                      </div>
                    </div>
                    <div id="collapseTwo4" class="collapse show">
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">
                            <div class="col-6">
                                <div class="form-group" style="font-size: .875rem;">
                                    <h6 style="font-weight:400;" id="ref">Bank Accounts</h6>
                                    <select class="form-control" id="busi_bank_list" name="param">
                                        <option value="">Select Bank</option>
                                    </select>
                                    <select class="form-control" id="cust_bank_list" name="param" style="display:none;">
                                        <option value="">Select Bank</option>
                                    </select>
                                    <select class="form-control" style="display:none;width:100%;" placeholder="Select Branch" id="cash_branch_list" name="param">
                                        <option value=''>Select Branch</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <h6 style="font-weight:400;" class="ref_no">Transaction No</h6>
                                    <h6 class="collected_by" style="font-weight:400;display:none;">Collected By</h6>
                                    <select style="font-size: .875rem;display:none;width:100%;" class="form-control" id="collected_by">
                                        <option value='0'>Select</option>
                                    </select>
                                    <input style="height: calc(2.55rem + 2px);" type="text" class="form-control m-input form-control-sm " id="bank_ref_no" name="bank_ref_no" placeholder="Transaction No">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                            <div class="col-6">
                                <div class="form-group">
                                    <h6 style="font-weight:400;">Expense Type</h6>
                                    <select style="font-weight:bold;font-size: 0.9rem;" class="form-control expense_list_pur" item-inner-id="0">
                                        <option value=''>Select Expense</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <h6 style="font-weight:400;" class="cust_alter">Reconcile <span id="recon_amount" style="float:right;font-size: 10px;"> </span></h6>
                                    <select style="font-size:.875rem; " class="form-control cust_alter" id="reconcile_type" name="reconcile">
                                        <option value=0 selected>Select</option>
                                        <option value=1>Reconcile to oldest purchase invoice</option>
                                        <option value=2>Reconcile to recent purchase invoice</option>
                                        <option value=4>Reconcile with selected purchase invoice</option>
                                        <option value=3>Do not reconcile</option>
                                    </select>
                                </div>
                                <span style="display:none;float:right;color:#5867dd;" id="recon_amount"> </span>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                            <div class="col-6" style="display:none;">
                                <div class="form-group">
                                    <h6 style="font-weight:400;">Sub-Account</h6>
                                    <select placeholder="Sub Expense Type" class="form-control sub_expense">
                                        <option value="">Add Sub-Account</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6" id='inv_pur' style="display:none;">
                                <div class="form-group">
                                    <h6 style="font-weight:400;">Purchase Invoices <span style="float:right;color:#5867dd;" id="recon_amount"> </span></h6>
                                    <select class="form-control kt_select2  inv_list" id="kt_select2_3" name="param" style="width: 100%;overflow: visible;" multiple="multiple"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
        <div class="col-md-12" id="reconcile_tab" style="display:none;">
        <div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px; border: 0.1rem solid #ada7a7;">
                <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                    <div class="row">
                        <div class="col-12" id="reconcile_table" style="padding: 0px;">
                            <div class="table-responsive">
                                <table class="table table-bordered newtable text-center" style="font-size: smaller; margin: 0px;">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Date</th>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Details</th>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Debit</th>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Credit</th>
                                            <th style="position: sticky; top: 0; background-color: #ededed;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="total_inv">
                                        <tr>
                                            <td colspan="5" style="text-align: center;">No Records found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 text-center" style="padding-right: 0px;" id="receipt_balance_tab">
                            <span id="receipt_balance"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>
        <div class="col-md-12">
            <ul class="nav justify-content-end mb-2">
                <li class="nav-item col-md-5" >
                    <button type="button" class="btn btn-sm btn-success create-invoice-btn" style="margin-left: 83px;"><i class="fa fa-plus"></i>&nbsp;<span class="voucher-text">Create Voucher</span></button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" onclick="parent.modal_close();"><i class="fa fa-times"></i>&nbsp;<span class="voucher-text">Close</span></button>
                </li>
            </ul>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

<script>
    function openQuickVoucherModal() {
        $('#quickVoucherModal').modal('show');
    }

    $(document).ready(function () {
        // Initial setup
        $("#create_tab").show();
        $("#reconcile_tab").hide();

        // Switching tabs
        $(".create_tab").on("click", function () {
            $("#create_tab").show();
            $("#reconcile_tab").hide();

            // Change background color for the selected tab
            $(".add_cust_filter").removeClass("active");
            $(this).addClass("active");

            // Add code to load data for the "Create" tab
            // Example: loadDataForCreateTab();
        });

        $(".reconcile_tab").on("click", function () {
            $("#create_tab").hide();
            $("#reconcile_tab").show();

            // Change background color for the selected tab
            $(".add_cust_filter").removeClass("active");
            $(this).addClass("active");

            // Add code to load data for the "Reconcile" tab
            // Example: loadDataForReconcileTab();
        });
    });
</script>

<!-- Vocher popup modal -->

<!-- Salary payment popup modal -->
<style>
    /* Add your styles here */
    .active {
      background-color: #007bff; /* Change this to your desired background color */
    }
  </style>
<!-- Modal Content -->
<div class="modal fade" id="salaryPaymentModal" tabindex="-1" role="dialog" aria-labelledby="salaryPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 60%; margin-left: 25%;">
        <div class="modal-content">
        <div class="container">
  <div class="row">
    <div class="col-lg-12">
      <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 5px;">
        <div class="kt-protlet__body p-3 row">
          <div class="col-md-12">
            <ul class="nav">
            <li class="nav-item" style="width:50%;">
    <div class="btn-group">
        <h5 id="receipt_title">Create Salary Payment</h5>
    </div>
</li>
              <li class="nav-item" style="width:45%;">
                <div class="btn-group btn-group-sm btn_filter pull-right tab_shift" role="group" aria-label="Large button group">
                  <button type="button" class="btn btn-outline-primary add_cust_filter created_tab active">Create</button>
                  <button type="button" class="btn btn-outline-primary add_cust_filter deductions_tab">Deductions</button>
                </div>
              </li>
              <!-- <li class="nav-item" style="width:5%">
                <button type="button" onclick="parent.modal_close();" class="close"><i class="fa fa-times"></i></button>
              </li> -->
            </ul>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-12" id="created_tab">
      <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
        <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
        <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                        <div class="row" style="margin-right: 0px; margin-left: 0px;">
                            <div class=" col-md-7" style="border-right: 0.1rem solid #ada7a7;">
                                <div class="-icon" style="margin-top:10px;  margin-bottom:10px;">
                                    <div class="business_details">
                                      <h5 ><span id="seller_name" style="color:black;display: block;"></span></h5>
                                      <span id="seller_add_1" style="color:rgb(66, 139, 202);"></span><br>
                                      <span id="seller_add_2" style="color:rgb(66, 139, 202);"></span>
                                      <p class="mb-0"> <span id="seller_city" style="color:rgb(66, 139, 202);"></span> - <span id="seller_pincode" style="color:rgb(66, 139, 202);"></span></p>
                                      <p class="mb-0" style="margin-bottom: .5rem !important;"><span id="seller_state" style="color:rgb(66, 139, 202);"></span> , <span id="seller_country" style="color:rgb(66, 139, 202);"></span></p>
                                      <h6  class="line-height-70" id="seller_gst_div" style="color:rgb(66, 139, 202);">GSTIN: <span  id="seller_gst"></span></h6>
                                    </div>
                                </div>
                            </div>
                            <div class=" col-md-5">
                                <div class="  input-group "  style="margin-top:10px;">
                                  <div class="input-group input-group-sm">
                                    <div class="kt-input-icon kt-input-icon--left" style="width:60%">
                                      <input  type="text"
                                      class="form-control form-control-sm rec_no" name="voucher_number" id="voucher_number" style="color:#495057;font-weight: 400;" placeholder="" disabled="">  
                                    </div>
                                    <div class="input-group-append" style="width:40%">
                                      <button class="btn btn-sm btn-secondary" type="button" style="width: 145px; font-weight:600;color:white;"> <span class=""></span>
                                      Voucher No </button>
                                    </div>
                                  </div>
                                </div>
                                <div class="input-group payment_date"  style="margin-top:10px;">
                                  <div class="input-group input-group-sm">
                                    <div class="kt-input-icon kt-input-icon--left" style="width:60%">
                                        <input  type="text" class="form-control form-control-sm rec_date" style="color:#495057;font-weight: 400;" placeholder=" " id="payment_date">  
                                    </div>
                                    <div class="input-group-append" style="width:40%">
                                          <button class="btn btn-sm btn-secondary" type="button" style="width: 145px; font-weight:600;color:white;"> <span class=""></span>
                                          Payment Date </button>
                                    </div>
                                  </div>
                                </div>
                                <div class="input-group payment_date"  style="margin-top:10px;">
                                  <div class="input-group input-group-sm">
                                    <div class="kt-input-icon kt-input-icon--left" style="width:60%">
                                          <input  type="text" class="form-control form-control-sm" style="color:#495057;font-weight: 400;" placeholder=" " id="salary_month">  
                                      </div>
                                      <div class="input-group-append" style="width:40%">
                                          <button class="btn btn-sm btn-secondary" type="button" style="width: 145px; font-weight:600;color:white;"> <span class=""></span>
                                          Salary Month </button>
                                      </div>
                                  </div>
                                </div>
                                <div class="" style="margin-top:10px;margin-bottom:10px;">
                                    <select style="color:black!important;font-weight:bold;" class="form-control" id="payment_mode" name="param">
                                        <option value='' >Select Payment Mode</option>
                                        <option  value='Cash'>Cash</option>
                                        <option value='Payable' >Payable</option>
                                        <option value='Bank Transfer' >Bank Transfer</option>
                                    </select>
                                </div>
                              </div>
                            </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">
                            <div class=" col-md-12" style="border-top: 0.1rem solid #ada7a7;">
                                <div class="row" style="margin-top:10px">
                                    <div class="col-7">
                                        <div class="form-group">
                                        <h6 style="font-weight:400;">Employee<a class="add_employee_btn" style="float:right;font-size: 10px;"
                                        href="javascript:;" >Add Employee</a></h6>
                                        <select style="width:100%;"  placeholder="Select Employee"   class="form-control m-select2 select_employee" id="select_employee"></select>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="form-group">
                                        <h6 style="font-weight:400;">Amount </h6>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-append" style="width:100%">
                                                    <input type="number" min="0" step="0.01" id="amount" onkeypress="return isNumberKey(event)" maxlength="8" required="" class="form-control" placeholder="Amount" value=""  >
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;margin-bottom: 10px;">
                            <div class=" col-md-7" style="padding: 0px;border-top: 0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
                                <textarea class="form-control " id="notes" placeholder="Note"  aria-invalid="false" style="margin: 0px;height: 100%;" maxlength="990" rows="5" disabled="">Employee Salary</textarea>
                            </div>
                            <div class=" col-md-5" style="border-top: 0.1rem solid #ada7a7;border-left:  0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
                                <h6 class="p-2" style="color:black;display: block;">For <span id="seller_names" ></span></h6>
                                <h6 class="pl-2" style="padding-top: 75px; color:black;display: block;">Authorised Signatory</h6>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">
                            <div class="col-12" data-toggle="collapse" data-target="#collapseTwo4">
                                <h5 style="font-weight:400;" class="dropdown-toggle">For Internal Use</h5>
                            </div>
                        </div>
                      <div id="collapseTwo4" class="collapse show" style="margin-top: 10px;">
                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                          <div class="col-6">
                            <div class="form-group">
                            <h6 style="font-weight:400;">Net Pay</h6>
                                <input type="number" id="netpay" class="form-control m-input" style="padding: 6px;" disabled="" value="0"  placeholder="Net Pay" maxlength="8">
                            </div> 
                          </div>
                          <div class="col-6" >
                            <div class="form-group">
                              <h6 style="font-weight:400;">CTC</h6>
                                <input type="number"  id="ctc_pay" class="form-control m-input" style="padding: 6px;" disabled="" value="0" placeholder="CTC" maxlength="8">
                            </div>
                          </div> 
                        </div>
                        <!-- <div class="row"  style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                          <div class="col-12">
                            <div class="form-group bank_display">
                                <h6 style="font-weight:400;">Bank List<span style="float:right;color:#5867dd;" id="recon_amount"> </span></h6>
                                <select   style=" opacity: 10;width=100%;"    class="form-control col-md-12 m-input form-control-sm m-select2 bank_list" id="bank_list"></select>
                            </div>
                            <div class="form-group employee_display">
                                <h6 style="font-weight:400;">Employee List<span style="float:right;color:#5867dd;" id="recon_amount"> </span></h6>
                                <select style=" opacity: 10;width=100%;"     class="form-control col-md-12 m-input form-control-sm m-select2 employee_list" id="employee_list"></select>
                            </div>
                          </div>
                        </div>      -->
                      </div>
                    </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-12" id="deductions_tab" style="display:none;">
      <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
        <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
       <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                      <!-- <div class="row" id="invoice" style="margin-left: 0px;margin-right: 0px;"> -->
                        <div class="col-12" id="reconcile_table" style="padding: 0px;margin-top:20px;">
                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                            <div class="col-6" >
                                  <div class="form-group"  style="font-size: .875rem; ">
                                  <!-- <h6 style="font-weight:400;">TDS Deduction</h6> -->
                                      <input type="number" name="tds" id="tds" placeholder="TDS Deduction" class="form-control m-input" style="padding: 6px;" required="" value=""  maxlength="8">
                                  </div>
                              </div>
                            <div class="col-6" >
                              <div class="form-group">
                              <!-- <h6 style="font-weight:400;">Professional Tax Deduction</h6> -->
                                  <input type="number" name="p_tax" id="p_tax" class="form-control m-input" placeholder="Professional Tax Deduction" style="padding: 6px;" required="" value=""  maxlength="8">
                              </div>
                            </div>
                          </div>
                          <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                            <div class="col-6">
                                <div class="form-group">
                                <!-- <h6 style="font-weight:400;">Employee PF</h6> -->
                                    <input type="number" name="pf" id="pf" class="form-control m-input" placeholder="Employee PF" style="padding: 6px;" required="" value=""  maxlength="8">
                                </div> 
                            </div>
                            <div class="col-6" >
                                <div class="form-group">
                                <!-- <h6 style="font-weight:400;">Employer PF</h6> -->
                                    <input type="number" name="er_pf" id="er_pf" class="form-control m-input" placeholder="Employer PF" style="padding: 6px;" required="" value=""  maxlength="8">
                                </div>
                            </div>
                          </div>
                          <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                            <div class="col-6">
                                <div class="form-group">
                                <!-- <h6 style="font-weight:400;">Employee ESI</h6> -->
                                    <input type="number" name="esi" id="esi" class="form-control m-input" placeholder="Employee ESI" style="padding: 6px;" required="" value=""  maxlength="8">
                                </div> 
                            </div>
                            <div class="col-6" >
                                <div class="form-group">
                                <!-- <h6 style="font-weight:400;">Employer ESI</h6> -->
                                    <input type="number" name="er_esi" id="er_esi" class="form-control m-input" placeholder="Employer ESI" style="padding: 6px;" required="" value=""  maxlength="8">
                                </div>
                            </div>
                          </div>
                          <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                            <div class="col-6" >
                                <div class="form-group">
                                <!-- <h6 style="font-weight:400;">Labour Welfare Fund</h6> -->
                                    <input type="number" name="welfare" id="welfare" class="form-control m-input" style="padding: 6px;" placeholder="Labour Welfare Fund" required="" value=""  maxlength="8">
                                </div> 
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                <!-- <h6 style="font-weight:400;">Others</h6> -->
                                    <input type="number" name="others" id="others" class="form-control m-input" style="padding: 6px;" placeholder="Others" required="" value=""  maxlength="8">
                                </div>
                            </div>
                          </div>
                        </div>
                      <div class="col-12" style="padding-right: 0px; text-align: center;" id="receipt_balance_tab">
                        <span id="receipt_balance"></span>
                      </div>
                    </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-12 mx-5">
            <ul class="nav justify-content-end mb-2">
                <li class="nav-item col-md-5" >
                    <button type="button" class="btn btn-sm btn-success create-invoice-btn"><i class="fa fa-plus"></i>&nbsp;<span class="voucher-text">Create Salary Voucher</span></button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" onclick="parent.modal_close();"><i class="fa fa-times"></i>&nbsp;<span class="voucher-text">Close</span></button>
                </li>
            </ul>
        </div>
  </div>
</div>
</div>
</div>
</div>
<script>
  function openSalaryPaymentModal() {
    // Trigger the modal to open
    $('#salaryPaymentModal').modal('show');
  }

  $(document).ready(function () {
    // Initial setup
    $("#created_tab").show();
    $("#deductions_tab").hide();

    // Switching tabs
    $(".created_tab").on("click", function () {
      $("#created_tab").show();
      $("#deductions_tab").hide();

      // Change background color for the selected tab
      $(".add_cust_filter").removeClass("active");
      $(this).addClass("active");

      // Add code to load data for the "Create" tab
      // Example: loadDataForCreateTab();
    });

    $(".deductions_tab").on("click", function () {
      $("#created_tab").hide();
      $("#deductions_tab").show();

      // Change background color for the selected tab
      $(".add_cust_filter").removeClass("active");
      $(this).addClass("active");

      // Add code to load data for the "Reconcile" tab
      // Example: loadDataForReconcileTab();
    });
  });
</script>
<!-- Salary payment popup modal -->
<!-- Debit Note popup modal -->
<div class="modal" id="debitNoteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 60%; margin-left: 25%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Debit Note</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <!-- Your content here -->
                <div class="row">
                    <!-- Copy your content here -->
                    <div class="col-lg-12">
                        <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom:5px;">
                        <div class="col-md-12" id="create_tab">
                <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
                  <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                    <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                        <div class="row" style="margin-right: 0px; margin-left: 0px;">
                            <div class=" col-md-7" style="border-right: 0.1rem solid #ada7a7;">
                                <div class="-icon" style="margin-top:10px;  margin-bottom:10px;">
                                    <div class="business_details">
                                      <h5 ><span id="seller_name" style="color:black;display: block;"></span></h5>
                                      <span id="seller_add_1" style="color:rgb(66, 139, 202);"></span><br>
                                      <span id="seller_add_2" style="color:rgb(66, 139, 202);"></span>
                                      <p class="mb-0"> <span id="seller_city" style="color:rgb(66, 139, 202);"></span> - <span id="seller_pincode" style="color:rgb(66, 139, 202);"></span></p>
                                      <p class="mb-0" style="margin-bottom: .5rem !important;"><span id="seller_state" style="color:rgb(66, 139, 202);"></span> , <span id="seller_country" style="color:rgb(66, 139, 202);"></span></p>
                                      <h6  class="line-height-70" id="seller_gst_div" style="color:rgb(66, 139, 202);">GSTIN: <span  id="seller_gst"></span></h6>
                                    </div>
                                </div>
                            </div>
                            <div class=" col-md-5">
                                <div class="" style="padding-top: 12px;">
                                    <div class="kt-input-icon kt-input-icon--right" >
                                        <span>
                                            <div class="m-input-icon m-input-icon--right">
                                                <input style="color:black!important;font-weight:bold;" type="text"
                                                class="form-control m-input note_no" name="Debit_number" placeholder="Debit Note" disabled="">
                                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                    <span>
                                                        <i class="la la-file"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                                <div class="" style="padding-top: 12px;">
                                    <div class="kt-input-icon kt-input-icon--right" >
                                        <span>
                                            <div class="m-input-icon m-input-icon--right payment_date">
                                                <input style="color:black!important;font-weight:bold;" type="text"
                                                class="form-control m-input note_date" placeholder="Note Date" >
                                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                    <span>
                                                        <i class="la la-calendar"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">
                            <div class=" col-md-12" style="border-top: 0.1rem solid #ada7a7;">
                                <div class="row" style="margin-top:10px">
                                    <div class="col-7">
                                        <div class="form-group">
                                        <h6 style="font-weight:400;">Supplier<a class="add_supplier_btn" style="float:right;font-size: 10px;"
                                        href="javascript:;" >Add Supplier</a></h6>
                                        <select style="width:100%;"  placeholder="Select Employee"   class="form-control m-select2 contact_list " id="contact_list"></select>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="form-group">
                                        <h6 style="font-weight:400;">Amount </h6>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-append" style="width:100%">
                                                    <input type="number" min="0" step="0.01" id="amount" onkeypress="return isNumberKey(event)" maxlength="8" required="" class="form-control total_amt" placeholder="Amount" value=""  >
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;margin-bottom: 10px;">
                            <div class=" col-md-7" style="padding: 0px;border-top: 0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
                                <textarea class="form-control " id="notes"   name="notes" aria-invalid="false" style="margin: 0px;height: 100%;" maxlength="990" rows="5" placeholder="Notes to Supplier"></textarea>
                            </div>
                            <div class=" col-md-5" style="border-top: 0.1rem solid #ada7a7;border-left:  0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
                                <h6 class="p-2" style="color:black;display: block;">For <span id="seller_names" ></span></h6>
                                <h6 class="pl-2" style="padding-top: 75px; color:black;display: block;">Authorised Signatory</h6>
                            </div>
                        </div>
                        <div class="row" style="margin-left: 0px;margin-right: 0px;">
                            <div class="col-12" data-toggle="collapse" data-target="#collapseTwo4">
                                <h5 style="font-weight:400;" class="dropdown-toggle">For Internal Use</h5>
                            </div>
                        </div>
                      <div id="collapseTwo4" class="collapse show" style="margin-top: 10px;">
                        <div class="row" style="margin-bottom: 10px;margin-left: 0px;margin-right: 0px;">
                            <div class="col-12">
                                <div class="form-group">
                                    <h6 style="font-weight:400;">Purchase Invoices</h6>
                                    <select   style=" opacity: 10;width=100%;"    class="form-control col-md-12 m-input form-control-sm m-select2 inv_list" id="m_select2_3"></select>
                                </div> 
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
            <ul class="nav justify-content-end mb-2">
                <li class="nav-item col-md-5" >
                    <button type="button" class="btn btn-sm btn-success create-invoice-btn" style="margin-left: 39px;"><i class="fa fa-plus"></i>&nbsp;<span class="voucher-text">Create Debit Note</span></button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" onclick="parent.modal_close();"><i class="fa fa-times"></i>&nbsp;<span class="voucher-text">Close</span></button>
                </li>
            </ul>
        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openDebitNoteModal() {
        // Show the hidden modal
        $('#debitNoteModal').modal('show');
    }
</script>
<!-- Debit Note  popup modal -->
<!-- JavaScript to handle card visibility -->

<script>
    function selectDropdownOption(element, cardId) {
        var selectedOptionText = element.textContent;
        document.getElementById("purchaseDropdown").innerText = selectedOptionText;
        showCard(cardId);
    }

    function showCard(cardId) {
        // Hide all cards
        document.querySelectorAll('.dropdown-card').forEach(card => {
            card.classList.remove('active-card');
        });

        // Show the selected card
        const selectedCard = document.getElementById(cardId);
        if (selectedCard) {
            selectedCard.classList.add('active-card');
        }

        // Update URL with card parameter
        const newUrl = window.location.pathname + '?' + cardId;
        window.history.pushState({}, '', newUrl);
    }
</script>
        </div>
</section>           
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>

    
