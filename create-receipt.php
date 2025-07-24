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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        .btn {
            cursor: pointer;
        }
        .create_tab.active {
            background-color: lightblue;
        }
        .reconcile_tab.active {
            background-color: lightgreen;
        }
        #create-receipt-datatable th {
            text-transform: capitalize;
            font-size: 14px;
        }
    </style>
</head>

<body class="">
    <?php include("menu.php");?>
    
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Create Receipt</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Create Receipt</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <form id="recieptForm" action="receiptdb.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="customer_id" name="customer_id" value="">
<input type="hidden" id="invoice_id" name="invoice_id" value="">
            <div id="createTabContent" style="display: block;">
                    <div class="row" id="create_tab">
                        <div class="col-lg-12">
                            <div class="card" style="border-radius: 5px;">
                                <div class="" role="document">
                                    <div class="dynbody">
                                        <div class="kt-container kt-container--fluid kt-grid_item kt-grid_item--fluid">
                                            <div class="kt-sec kt-container--fluid kt-grid_item kt-grid_item--fluid">
                                                <div class="row">
                                                    <div class="col-lg-12 ">
                                                        <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom:5px;">
                                                            <div class="kt-protlet__body p-3 row"> 
                                                                <div class="col-md-12">
                                                                    <ul style="display: flex;list-style: none;justify-content: flex-end;margin-top: 0 ! important;margin-bottom: 0 ! important;margin-left: 0 ! important;padding:0px;">
                                                                        <li style="width:50%;">
                                                                            <h5 style="margin-top: 5px;" id="receipt_title">Create Receipt</h5>
                                                                        </li>
                                                                        <li style="width:20%;margin-right: 250px;">
                                                                            <select class="form-control form-control-sm" id="notify_type" name="notification" style="">
                                                                                <option value="0">Select</option>
                                                                                <option value="1">Email only</option>
                                                                                <option value="2">SMS only</option>
                                                                                <option value="3" selected="">Email &amp; SMS</option>
                                                                                <option value="4">No Email &amp; SMS</option>
                                                                            </select>
                                                                        </li>
                                                                        <li style="list-style-type: none; width: 25%; margin-right: -100px;">
                                                                            <div class="btn-group btn-group-sm btn_filter pull-right tab_shift" role="group" aria-label="Large button group">
                                                                                <button type="button" class="btn btn-outline-primary add_cust_filter create_tab active" onclick="showCreateTab()">Create</button>
                                                                                <button type="button" class="btn btn-outline-primary add_cust_filter reconcile_tab" onclick="showReconcileTab()">Reconcile</button>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
                                                            <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                                                                <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                                                                    <div class="row" style="margin-right: 0px; margin-left: 0px;">
                                                                        <div class=" col-md-7" style="border-right: 0.1rem solid #ada7a7;">
                                                                            <div class="-icon" style="margin-top:10px;  margin-bottom:10px;">
                                                                                <div class="business_details">
                                                                                    <h5 class="line-height-70"><b id="seller_name" style=" color: blue;">KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</b></h5>
                                                                                    <h5 id="seller_add_1" class="line-height-70">120 Newport Center Dr, Newport Beach, CA 92660</h5>
                                                                                    <h5 id="seller_add_2" class="line-height-70"></h5>
                                                                                    <h5 id="seller_add_3" class="line-height-70">GST : 29AAICK7493G1ZX </h5>
                                                                                    <h5 id="seller_email" class="line-height-70"> Email: sales.usa@iiiqbets.com </h5>
                                                                                    <h5 id="seller_mobile" class="line-height-70">Phone: 91 7550705070 </h5>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class=" col-md-5">
                                                                            <div class="" style="padding-top: 12px;">
                                                                                <div class="kt-input-icon kt-input-icon--right">
                                                                                    <span>
                                                                                        <div class="m-input-icon m-input-icon--right">
                                                                                            <?php
                                                                                            $result1=mysqli_query($conn,"select id from receipts where id=(select max(id) from receipts)");
                                                                                            if($row1=mysqli_fetch_array($result1))
                                                                                            {
                                                                                                $id=$row1['id']+1;
                                                                                                $i=$row1['id'];
                                                                                                $s=preg_replace("/[^0-9]/", '', $i);
                                                                                                $invoice_code="RECT0".($s+1);
                                                                                            }
                                                                                            else{
                                                                                                $id = 0;
                                                                                                $invoice_code = "RECT0".(1);
                                                                                            }
                                                                                            ?>
                                                                                            <input style="color:black!important;font-weight:bold;" type="text" class="form-control m-input rec_no" placeholder="Receipt No" name="rec_no" value="<?php echo $invoice_code; ?>" readonly>
                                                                                            <span class="m-input-icon_icon m-input-icon_icon--right">
                                                                                                <span>
                                                                                                    <i class="la la-file"></i>
                                                                                                </span>
                                                                                            </span>
                                                                                        </div>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                            <div style="padding-top: 12px;" class="">
                                                                                <div class="">
                                                                                    <span>
                                                                                        <div class="date">
                                                                                            <div class="m-input-icon m-input-icon--right">
                                                                                                <input style="color:black !important;font-weight:bold;" type="date" class="form-control m-input rec_date " placeholder="Receipt Date" id="receipt_date" name="receipt_date" required>
                                                                                                <span class="m-input-icon_icon m-input-icon_icon--right">
                                                                                                    <span>
                                                                                                        <i class="la la-calendar"></i>
                                                                                                    </span>
                                                                                                </span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                            <div style="padding-top: 12px;">
                                                                                <div class="form-group">
                                                                                    <select class="form-control" id="paymentMode" name="paymentMode">
                                                                                        <option value="Direct Deposit" selected>Direct Deposit</option>
                                                                                        <option value="NEFT/RTGS">NEFT/RTGS</option>
                                                                                        <option value="Online Payment">Online Payment</option>
                                                                                        <option value="Credit Debit Card">Credit/Debit Card</option>
                                                                                        <option value="Demand Draft">Demand Draft</option>
                                                                                        <option value="Cheque">Cheque</option>
                                                                                        <option value="Cash">Cash</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                                                        <div class=" col-md-12" style="border-top: 0.1rem solid #ada7a7;">
                                                                            <div class="row" style="margin-top:10px">
                                                                                <div class="col-7">
                                                                                    <div class="form-group">
                                                                                        <h6 style="font-weight:400;">Customer 
                                                                                            <a class="add_cust_new_btn" style="float:right;font-size: 10px;" href="javascript:;">Add Customer</a>
                                                                                            <a class="add_cust_btn" style="float:right;font-size: 10px;display:none;" href="javascript:;" target="_blank">View Customer Dashboard</a>
                                                                                        </h6>
                                                                                        <div class="form-group" >
                                                                                            <input class="form-control" list="customer_name" name="customer_name_choice" id="customer_name_choice" onchange="checknamevalue(this.value)" autocomplete="off" />
                                                                                            <datalist name="customer_name" id="customer_name" placeholder="Select Customer" >
                                                                                           <?php
$sql = "SELECT cm.id, cm.customerName, cm.mobile 
        FROM customer_master cm 
        JOIN invoice i ON cm.id = i.customer_id 
        WHERE cm.contact_type = 'Customer'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>
        <option value="<?php echo $row['customerName'] . ' | ' . $row['mobile']; ?>" data-customerid="<?php echo $row['id']; ?>">
<?php
    }
} else {
?>
    <option value="No Match Found" disabled>
<?php
}
?>

                                                                                            </datalist><br />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-5">
                                                                                    <div class="form-group">
                                                                                        <h6 style="font-weight:400;">Amount  </h6>
                                                                                        <div class="input-group input-group-sm">
                                                                                            <div class="kt-input-icon kt-input-icon--right" style="width:30%">
                                                                                                <select class="form-control form-control-sm m-select2 m-select2-general currency_list select2-hidden-accessible" style="opacity:1;width:100%" name="param" id="currency_list" data-select2-id="currency_list" tabindex="-1" aria-hidden="true">
                                                                                                    <option value="AED">AED</option>
                                                                                                    <option value="AFN">AFN</option>
                                                                                                    <option value="ALL">ALL</option>
                                                                                                    <option value="AMD">AMD</option>
                                                                                                    <option value="ANG">ANG</option>
                                                                                                    <option value="AOA">AOA</option>
                                                                                                    <option value="ARS">ARS</option>
                                                                                                    <option value="AUD">AUD</option>
                                                                                                    <option value="AWG">AWG</option>
                                                                                                    <option value="AZN">AZN</option>
                                                                                                    <option value="BAM">BAM</option>
                                                                                                    <option value="BBD">BBD</option>
                                                                                                    <option value="BDT">BDT</option>
                                                                                                    <option value="BGN">BGN</option>
                                                                                                    <option value="BHD">BHD</option>
                                                                                                    <option value="BIF">BIF</option>
                                                                                                    <option value="BMD">BMD</option>
                                                                                                    <option value="BND">BND</option>
                                                                                                    <option value="BOB">BOB</option>
                                                                                                    <option value="BRL">BRL</option>
                                                                                                    <option value="BSD">BSD</option>
                                                                                                    <option value="BTC">BTC</option>
                                                                                                    <option value="BTN">BTN</option>
                                                                                                    <option value="BWP">BWP</option>
                                                                                                    <option value="BYN">BYN</option>
                                                                                                    <option value="BYR">BYR</option>
                                                                                                    <option value="BZD">BZD</option>
                                                                                                    <option value="CAD">CAD</option>
                                                                                                    <option value="CDF">CDF</option>
                                                                                                    <option value="CHF">CHF</option>
                                                                                                    <option value="CLF">CLF</option>
                                                                                                    <option value="CLP">CLP</option>
                                                                                                    <option value="CNY">CNY</option>
                                                                                                    <option value="COP">COP</option>
                                                                                                    <option value="CRC">CRC</option>
                                                                                                    <option value="CUC">CUC</option>
                                                                                                    <option value="CUP">CUP</option>
                                                                                                    <option value="CVE">CVE</option>
                                                                                                    <option value="CZK">CZK</option>
                                                                                                    <option value="DJF">DJF</option>
                                                                                                    <option value="DKK">DKK</option>
                                                                                                    <option value="DOP">DOP</option>
                                                                                                    <option value="DZD">DZD</option>
                                                                                                    <option value="EGP">EGP</option>
                                                                                                    <option value="ERN">ERN</option>
                                                                                                    <option value="ETB">ETB</option>
                                                                                                    <option value="EUR">EUR</option>
                                                                                                    <option value="FJD">FJD</option>
                                                                                                    <option value="FKP">FKP</option>
                                                                                                    <option value="GBP">GBP</option>
                                                                                                    <option value="GEL">GEL</option>
                                                                                                    <option value="GGP">GGP</option>
                                                                                                    <option value="GHS">GHS</option>
                                                                                                    <option value="GIP">GIP</option>
                                                                                                    <option value="GMD">GMD</option>
                                                                                                    <option value="GNF">GNF</option>
                                                                                                    <option value="GTQ">GTQ</option>
                                                                                                    <option value="GYD">GYD</option>
                                                                                                    <option value="HKD">HKD</option>
                                                                                                    <option value="HNL">HNL</option>
                                                                                                    <option value="HRK">HRK</option>
                                                                                                    <option value="HTG">HTG</option>
                                                                                                    <option value="HUF">HUF</option>
                                                                                                    <option value="IDR">IDR</option>
                                                                                                    <option value="ILS">ILS</option>
                                                                                                    <option value="IMP">IMP</option>
                                                                                                    <option value="INR" selected="selected" data-select2-id="2">INR</option>
                                                                                                    <option value="IQD">IQD</option>
                                                                                                    <option value="IRR">IRR</option>
                                                                                                    <option value="ISK">ISK</option>
                                                                                                    <option value="JEP">JEP</option>
                                                                                                    <option value="JMD">JMD</option>
                                                                                                    <option value="JOD">JOD</option>
                                                                                                    <option value="JPY">JPY</option>
                                                                                                    <option value="KES">KES</option>
                                                                                                    <option value="KGS">KGS</option>
                                                                                                    <option value="KHR">KHR</option>
                                                                                                    <option value="KMF">KMF</option>
                                                                                                    <option value="KPW">KPW</option>
                                                                                                    <option value="KRW">KRW</option>
                                                                                                    <option value="KWD">KWD</option>
                                                                                                    <option value="KYD">KYD</option>
                                                                                                    <option value="KZT">KZT</option>
                                                                                                    <option value="LAK">LAK</option>
                                                                                                    <option value="LBP">LBP</option>
                                                                                                    <option value="LKR">LKR</option>
                                                                                                    <option value="LRD">LRD</option>
                                                                                                    <option value="LSL">LSL</option>
                                                                                                    <option value="LTL">LTL</option>
                                                                                                    <option value="LVL">LVL</option>
                                                                                                    <option value="LYD">LYD</option>
                                                                                                    <option value="MAD">MAD</option>
                                                                                                    <option value="MDL">MDL</option>
                                                                                                    <option value="MGA">MGA</option>
                                                                                                    <option value="MKD">MKD</option>
                                                                                                    <option value="MMK">MMK</option>
                                                                                                    <option value="MNT">MNT</option>
                                                                                                    <option value="MOP">MOP</option>
                                                                                                    <option value="MRO">MRO</option>
                                                                                                    <option value="MUR">MUR</option>
                                                                                                    <option value="MVR">MVR</option>
                                                                                                    <option value="MWK">MWK</option>
                                                                                                    <option value="MXN">MXN</option>
                                                                                                    <option value="MYR">MYR</option>
                                                                                                    <option value="MZN">MZN</option>
                                                                                                    <option value="NAD">NAD</option>
                                                                                                    <option value="NGN">NGN</option>
                                                                                                    <option value="NIO">NIO</option>
                                                                                                    <option value="NOK">NOK</option>
                                                                                                    <option value="NPR">NPR</option>
                                                                                                    <option value="NZD">NZD</option>
                                                                                                    <option value="OMR">OMR</option>
                                                                                                    <option value="PAB">PAB</option>
                                                                                                    <option value="PEN">PEN</option>
                                                                                                    <option value="PGK">PGK</option>
                                                                                                    <option value="PHP">PHP</option>
                                                                                                    <option value="PKR">PKR</option>
                                                                                                    <option value="PLN">PLN</option>
                                                                                                    <option value="PYG">PYG</option>
                                                                                                    <option value="QAR">QAR</option>
                                                                                                    <option value="RON">RON</option>
                                                                                                    <option value="RSD">RSD</option>
                                                                                                    <option value="RUB">RUB</option>
                                                                                                    <option value="RWF">RWF</option>
                                                                                                    <option value="SAR">SAR</option>
                                                                                                    <option value="SBD">SBD</option>
                                                                                                    <option value="SCR">SCR</option>
                                                                                                    <option value="SDG">SDG</option>
                                                                                                    <option value="SEK">SEK</option>
                                                                                                    <option value="SGD">SGD</option>
                                                                                                    <option value="SHP">SHP</option>
                                                                                                    <option value="SLL">SLL</option>
                                                                                                    <option value="SOS">SOS</option>
                                                                                                    <option value="SRD">SRD</option>
                                                                                                    <option value="STD">STD</option>
                                                                                                    <option value="SVC">SVC</option>
                                                                                                    <option value="SYP">SYP</option>
                                                                                                    <option value="SZL">SZL</option>
                                                                                                    <option value="THB">THB</option>
                                                                                                    <option value="TJS">TJS</option>
                                                                                                    <option value="TMT">TMT</option>
                                                                                                    <option value="TND">TND</option>
                                                                                                    <option value="TOP">TOP</option>
                                                                                                    <option value="TRY">TRY</option>
                                                                                                    <option value="TTD">TTD</option>
                                                                                                    <option value="TWD">TWD</option>
                                                                                                    <option value="TZS">TZS</option>
                                                                                                    <option value="UAH">UAH</option>
                                                                                                    <option value="UGX">UGX</option>
                                                                                                    <option value="USD">USD</option>
                                                                                                    <option value="UYU">UYU</option>
                                                                                                    <option value="UZS">UZS</option>
                                                                                                    <option value="VEF">VEF</option>
                                                                                                    <option value="VND">VND</option>
                                                                                                    <option value="VUV">VUV</option>
                                                                                                    <option value="WST">WST</option>
                                                                                                    <option value="XAF">XAF</option>
                                                                                                    <option value="XAG">XAG</option>
                                                                                                    <option value="XAU">XAU</option>
                                                                                                    <option value="XCD">XCD</option>
                                                                                                    <option value="XDR">XDR</option>
                                                                                                    <option value="XOF">XOF</option>
                                                                                                    <option value="XPF">XPF</option>
                                                                                                    <option value="YER">YER</option>
                                                                                                    <option value="ZAR">ZAR</option>
                                                                                                    <option value="ZMK">ZMK</option>
                                                                                                    <option value="ZMW">ZMW</option>
                                                                                                    <option value="ZWL">ZWL</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="input-group-append" style="width:70%">
                                                                                             amount after deducting previous recipets   <input type="number" min="0" step="0.01" id="amount" class="form-control total_amt" placeholder="Amount" name="amount" value="">
                                                                                            </div>
                                                                                        </div>      
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-left: 0px;margin-right: 0px;margin-bottom: 10px;">
                                                                        <div class=" col-md-7" style="padding: 0px;border-top: 0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
                                                                            <textarea class="form-control " id="notes" name="notes" placeholder="Note" aria-invalid="false" style="margin: 0px;height: 100%;" maxlength="990" rows="5"></textarea>
                                                                        </div>
                                                                        <div class=" col-md-5" style="border-top: 0.1rem solid #ada7a7;border-left:  0.1rem solid #ada7a7;border-bottom: 0.1rem solid #ada7a7;">
                                                                            <h6 class="p-2" style="color:black;display: block;">For <span id="seller_names">VAMSHI KRISHNA  PATTABHI</span></h6>
                                                                            <h6 class="pl-2" style="padding-top: 75px; color:black;display: block;">Authorised Signatory</h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-left: 0px;margin-right: 0px;" id="payment_mode">
                                                                        <div class="col-6" id="collected_by_tab" style="display: none;">
                                                                            <div class="form-group">
                                                                                <h6 style="font-weight:400;">Collected BY <span id="" style="color:red;display:none;"></span></h6>
                                                                                <input  type="text" class="form-control m-input form-control-sm" id="collected_by" name="collected_by" placeholder="Collected BY">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6" id="bank_name_tab" style="display: none;">
                                                                            <div class="form-group">
                                                                                <h6 style="font-weight:400;">Bank Name <span id="" style="color:red;display:none;"></span></h6>
                                                                                <input type="text" class="form-control m-input form-control-sm" id="bank_name" name="bank_name" placeholder="Bank Name">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6" id="trans_no_tab" style="display: none;">
                                                                            <div class="form-group">
                                                                                <h6 style="font-weight:400;">Transaction No  <span id="remind" style="color:red;display:none;"></span></h6>
                                                                                <input type="text" class="form-control m-input form-control-sm" id="trans_no" name="transaction_no" placeholder="Transaction No">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6" id="cheque_no_tab" style="display: none;">
                                                                            <div class="form-group">
                                                                                <h6 style="font-weight:400;">Cheque No  <span id="" style="color:red;display:none;"></span></h6>
                                                                                <input  type="text" class="form-control m-input form-control-sm" id="cheque_no" name="cheque_no" placeholder="Cheque No">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6" id="dd_no_tab" style="display: none;">
                                                                            <div class="form-group">
                                                                                <h6 style="font-weight:400;">Demand Draft No<span id="" style="color:red;display:none;"></span></h6>
                                                                                <input type="text" class="form-control m-input form-control-sm" id="dd_no" name="dd_no" placeholder="DD No">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6" id="credit_debit_card_tab" style="display: none;">
                                                                            <div class="form-group">
                                                                                <h6 style="font-weight:400;">Card last 4 digit No<span id="" style="color:red;display:none;"></span></h6>
                                                                                <input  type="text" class="form-control m-input form-control-sm" id="card_last_no"  name="card_last_no" placeholder="Card last 4 digit No">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6" id="transaction_date_tab" style="display: none;">
                                                                            <div class="form-group">
                                                                                <h6 style="font-weight:400;">Trasaction Date<span id="" style="color:red;display:none;"></span></h6>
                                                                                <input  type="date" class="form-control m-input form-control-sm" id="transaction_date" name="transaction_date" placeholder="Transaction Date">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" id="reconcile_tab" style="display:none;">
                                                        <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
                                                            <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                                                                <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                                                                    <div class="col-12" id="reconcile_table" style="padding: 0px;">
                                                                        <div style="padding:0px;overflow-y: scroll;  max-height: 557px;">
                                                                            <table class="table table-bordered newtable" style="  text-align: center;font-size: smaller;margin: 0px;">
                                                                                <thead> 
                                                                                    <tr>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Date </th>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Details </th>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Debit</th>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Credit</th>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Status</th>
                                                                                    </tr> 
                                                                                </thead> 
                                                                                <tbody id="total_inv"> 
                                                                                    <tr><td style="text-align:center;" colspan="5">No Records found</td></tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-12" style="padding-right: 0px;display:none;text-align: center;" id="receipt_balance_tab">
                                                                            <span id="receipt_balance"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12" id="custom_reconcile_table" style="padding-top: 15px; padding-bottom: 15px; display: none;">
                                                                        <div style="padding:0px;overflow-y: scroll;  max-height: 450px;margin-top: 10px;">
                                                                            <table class="table table-bordered custom_rec_tables" style="  text-align: center;font-size: smaller;margin: 0px;margin: 0px;">
                                                                                <thead> 
                                                                                    <tr>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Date </th>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Invoices </th>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Total</th>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Balance</th>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Amount</th>
                                                                                        <th style="background-color: #ededed;position: sticky;top: 0;">Status</th>
                                                                                    </tr> 
                                                                                </thead> 
                                                                                <tbody id="custom_reconcile_table_data"> 
                                                                                    <tr><td style="text-align:center;" colspan="5">No Records found</td></tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" id="reconcile_page_tab" style="display:none;">
                                                        <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
                                                            <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                                                                <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                                                                    
                                                                </div>  
                                                            </div>  
                                                        </div>  
                                                    </div>
                                                    <div class="col-md-12" id="bulk_reconcile_page_tab" style="display:none;">
                                                        <div class="kt-portlet kt-portlet--responsive-mobile page_1" style="margin-bottom: 10px;">
                                                            <div class="kt-portlet__body p-3" style="padding-top: 0px !important;">
                                                                <div style="margin-right: 0px; margin-left: 0px; border: 0.1rem solid #ada7a7">
                                                                    <div class="col-12 row" id="blk_rec_cust_tab" style="margin-top:15px;margin-bottom:15px;margin-left: 0px;margin-right: 0px;padding: 0px;">
                                                                        <div class="col-9" style="margin-bottom: 15px;">
                                                                            <div class="btn-group btn_filter btn-group-sm pull-right" role="group" aria-label="Large button group">
                                                                                <button type="button" class="btn btn-outline-primary filter bulk_reconcile_old_inv_tab active">Reconcile to oldest invoices</button>
                                                                                <button type="button" class="btn btn-outline-primary filter bulk_reconcile_new_inv_tab">Reconcile to newest invoices</button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12" id="bulk_reconcile_page_table" style="padding-top: 15px;padding-bottom: 15px;">
                                                                            <div style="padding:0px;overflow-y: scroll;  max-height: 450px;margin-top: 10px;">
                                                                                <table class="table table-bordered newtable" style="  text-align: center;font-size: smaller;margin: 0px;margin: 0px;">
                                                                                    <thead> 
                                                                                        <tr>
                                                                                            <th style="background-color: #ededed;position: sticky;top: 0;">Client Name </th>
                                                                                            <th style="background-color: #ededed;position: sticky;top: 0;">Receivable </th>
                                                                                            <th style="background-color: #ededed;position: sticky;top: 0;">Unapplied Payments </th>
                                                                                            <th style="background-color: #ededed;position: sticky;top: 0;">Action </th>
                                                                                        </tr> 
                                                                                    </thead> 
                                                                                    <tbody id="bulk_reconcile_page_table_data"> 
                                                                                        <tr><td style="text-align:center;" colspan="5">No Records found</td></tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12" style="padding-right: 0px;display:none;text-align: center;" id="bulk_reconcile_balance_tab">
                                                                            <span id="bulk_reconcile_balance"></span>
                                                                        </div>
                                                                    </div>  
                                                                </div>  
                                                            </div>  
                                                        </div>  
                                                    </div>
                                                    <div class="col-md-12" id="final_div" style="">
                                                        <ul style="display: flex;list-style: none;justify-content: flex-end;margin-top: 0 ! important;margin-bottom: 0 ! important;margin-left: 0 ! important;padding:0px;">
                                                            <li style="width:100%;margin-right: 10px;">
                                                                <button type="submit" class="btn m-btn btn-sm btn-success create-receipt-btn pull-right float-right mx-1"><i class="fa fa-plus"></i>&nbsp;Create Receipt</button>
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
                    </div>
                </div>
            </form>
            <div id="reconcileTabContent" style="display: none;">
                <div class="row" id="reconcile_tab">
                    <div class="col-lg-12">
                        <div class="card" style="border-radius: 5px;">
                            <ul style="display: flex;list-style: none;justify-content: flex-end;margin-top: 10px; ! important;margin-bottom: 0 ! important;margin-left: 10px; ! important;padding:0px;">
                                <li style="list-style-type: none; width: 25%; margin-right: -100px;">
                                    <div class="btn-group btn-group-sm btn_filter pull-right tab_shift" role="group" aria-label="Large button group">
                                        <button type="button" class="btn btn-outline-primary add_cust_filter create_tab" onclick="showCreateTab()">Create</button>
                                        <button type="button" class="btn btn-outline-primary add_cust_filter reconcile_tab active" onclick="showReconcileTab()">Reconcile</button>
                                    </div>
                                </li>
                            </ul>
                            <br>
                            <div class="container-fluid">
                                <table class="table table-bordered" id="create-receipt-datatable">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>Date</th>
                                            <th>Details</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr  style="text-align: center;">
                                            <td colspan="5">No records found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12 mb-3 mx-3" id="final_div">
                                <ul style="display: flex;list-style: none;justify-content: flex-end;margin-top: 0 ! important;margin-bottom: 0 ! important;margin-left: 0 ! important;padding:0px;">
                                    <li style="width:100%;margin-right: 10px;">
                                        <button type="button" class="btn m-btn btn-sm btn-success create-receipt-btn pull-right float-right mx-1"><i class="fa fa-plus"></i>&nbsp;Create Receipt</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blank page content goes here -->
            </div>
            <script>
document.getElementById('customer_name_choice').addEventListener('change', function() {
    const selectedCustomer = this.value;
    const customerId = getCustomerIdFromSelection(selectedCustomer);
    const invoiceId = getInvoiceIdFromSelection(customerId);

    document.getElementById('customer_id').value = customerId;
    document.getElementById('invoice_id').value = invoiceId;
});

function getCustomerIdFromSelection(value) {
    const customerData = document.querySelector(`#customer_name option[value="${value}"]`);
    return customerData ? customerData.getAttribute('data-customerid') : '';
}

function getInvoiceIdFromSelection(customerId) {
    let invoiceId = '';
    $.ajax({
        url: 'fetch_invoice.php',
        type: 'POST',
        data: { customer_id: customerId },
        async: false,
        success: function(response) {
            const data = JSON.parse(response);
            invoiceId = data.invoice_id; // Adjust based on your response format
        }
    });
    return invoiceId;
}
    </script>
<script>
    function showCreateTab() {
        document.querySelector('.create_tab').classList.add('active');
        document.querySelector('.reconcile_tab').classList.remove('active');
        document.getElementById('reconcileTabContent').style.display = 'none';
        document.getElementById('createTabContent').style.display = 'block';
    }

    function showReconcileTab() {
        document.querySelector('.reconcile_tab').classList.add('active');
        document.querySelector('.create_tab').classList.remove('active');
        document.getElementById('reconcileTabContent').style.display = 'block';
        document.getElementById('createTabContent').style.display = 'none';
    }
</script>
<script>
$(document).ready(function() {
    function showTab(selectedOption) {
        $('#collected_by_tab, #bank_name_tab, #trans_no_tab, #cheque_no_tab, #dd_no_tab, #credit_debit_card_tab, #transaction_date_tab').hide();

        if (selectedOption === 'Cash') {
            $('#collected_by_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Cheque') {
            $('#bank_name_tab, #cheque_no_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Direct Deposit') {
            $('#bank_name_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Demand Draft') {
            $('#bank_name_tab, #dd_no_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Credit Debit Card') {
            $('#credit_debit_card_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'Online Payment') {
            $('#trans_no_tab, #transaction_date_tab').show();
        } else if (selectedOption === 'NEFT/RTGS') {
            $('#bank_name_tab, #transaction_date_tab').show();
        }
    }

    var selectedOption = $('#paymentMode').val();
    showTab(selectedOption);

    $('#paymentMode').change(function() {
        var selectedOption = $(this).val();
        showTab(selectedOption);
    });
});
</script>
        <script src="assets/js/vendor-all.min.js"></script>
        <script src="assets/js/plugins/bootstrap.min.js"></script>
        <script src="assets/js/pcoded.min.js"></script>
        <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
</body>
</html>
