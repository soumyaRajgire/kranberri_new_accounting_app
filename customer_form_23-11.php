
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

<!DOCTYPE html>

<html lang="en">

<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    .tab-button.active {
    background-color: #007bff;
    color: #fff;
}
.mandatory-symbol {
    color: red;
  }
  .error {
            color: red;
            size: 80%
        }

        .hidden {
            display: none;
        }

</style>
<style>
    .highlight-error {
        border: 2px solid red;
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
                                <h4 class="m-b-10">Add Customer</h4>
                                
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>


            <div class="card">
    <form action="contactsdb_customer.php" method="POST">
           

        <div class="modal-body">
       <div class="tabs">
       <div class="text-center">
    <button type="button" class="tab-button active btn btn-sm btn-info" onclick="openTab(event, 'tab1')">Information</button>
    <button type="button" class="tab-button btn btn-sm btn-info" onclick="openTab(event, 'tab2')">Banking & Taxes</button>
    <button type="button" class="tab-button btn btn-sm btn-info" onclick="openTab(event, 'tab3')">Shipping Address</button>
    <button type="button" class="tab-button btn btn-sm btn-info" onclick="openTab(event, 'tab4')">Billing Address</button>
</div>
    <!-- Tab content container for all tabs -->
    <div id="tab1" class="tab-content active">
       
<div class="row">
    <div class="col-md-6">
        <div class="input-group mb-1">
        <div class="input-group-prepend">
            <div class="did-floating-label-content">
                <select class="did-floating-select" name="title" id="title">
                 <option value="">Title</option>   
                <option value="mr">Mr.</option>
                <option value="mrs">Mrs.</option>
                <option value="miss">Miss</option>
                <option value="ms">Ms.</option>
                <option value="dr">Dr.</option>
                </select>
                <label class="did-floating-label">Title</label>
            </div>
        </div>
            <div class="did-floating-label-content">
                <input type="text" class="did-floating-input" placeholder="" name="name" id="name" required>   
                <label for="" class=" did-floating-label">Name<span class="mandatory-symbol">*</span></label></div>
                <div id="name_error" class="error hidden">Please enter a valid name.</div>
            </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
                <select id="entity_type" name="entity_type" class=" did-floating-select" >
                    <option value="">Select an Entity Type</option>
                    <option value="individual">Individual</option>
                    <option value="Propritorship">Propritorship</option>
                    <option value="Partnership">Partnership</option>
                    <option value="Hindu Undivided Family">Hindu Undivided Family</option>
                    <option value="Private Limited Company">Private Limited Company</option>
                    <option value="Public Limited Company">Public Limited Company</option>
                    <option value="One Person Company">One Person Company </option>
                    <option value="Society/Club/Trust/Association of Persons">Society/Club/Trust/Association of Persons</option>
                    <option value="Government Department">Government Department</option>
                    <option value="Public Sector Undertaking">Public Sector Undertaking</option>
                    <option value="Unlimited Company">Unlimited Company</option>
                    <option value="Limited Liability Partnership">Limited Liability Partnership</option> 
                    <option value="Local Authority">Local Authority</option>
                    <option value="Statutory Body">Statutory Body</option>
                    <option value="Foreign Limited Liability Partnership">Foreign Limited Liability Partnership</option>
                    <option value="Foreign Company Registered(in india)">Foreign Company Registered(in india)</option>
                    <option value="Others">Others</option>
                </select>
                 <label for="entity_type" class="did-floating-label">Entity Type</label>
        </div> 
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
            <input type="text" id="mobile_number" name="mobile_number" class="did-floating-input" pattern="^\d{10}$" placeholder="" oninput="validateNumericInput(this)" required>
             <label for="mobile_number" class="did-floating-label">Mobile Number<span class="mandatory-symbol">*</span></label>
             <div id="mobile_number_error" class="error hidden">Please enter a valid mobile number.</div>
            </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
            <input type="email" id="email" name="email" class="did-floating-input" placeholder="" required>
             <label for="email" class="did-floating-label">Email<span class="mandatory-symbol">*</span></label>
             <div id="email_error" class="error hidden">Please enter a valid email address.</div>
            </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
            <input type="text" id="customer_gstin" name="customer_gstin" class="did-floating-input" placeholder="">
             <label for="customer_gstin" class="did-floating-label">Customer GSTIN</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
        <input type="text" id="customer_registered_name" name="customer_registered_name" class="did-floating-input" placeholder="" readonly>
        <label for="customer_registered_name" class="did-floating-label">Customer GST Registered Name</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
         <div class="did-floating-label-content">
        <input type="text" id="business_name" name="business_name" class="did-floating-input" placeholder="">
        <label for="business_name" class="did-floating-label">Business Name</label>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
         <div class="did-floating-label-content">
        <input type="text" id="display_name" name="display_name" class="did-floating-input" placeholder="" >
        <label for="display_name" class="did-floating-label">Display Name<span class="mandatory-symbol">*</span></label>
        <div id="display_name_error" class="error hidden">Please enter a valid name.</div>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
        <input type="text" id="phone_number" name="phone_number" class="did-floating-input" placeholder="">
         <label for="phone_number" class="did-floating-label">Phone Number</label>
         <div id="phone_number_error" class="error hidden">Please enter a valid phone number.</div>
        </div>
    </div>
    <div class="mb-1 col-lg-6">
        <div class="did-floating-label-content">
        <input type="text" id="fax" name="fax" class="did-floating-input" placeholder="">
        <label for="fax" class="did-floating-label">Fax</label>
        </div>
    </div>
          
      </div>
      <button type="button" class="next-btn btn btn-md btn-info float-right" id="next-btn-1" onclick="openTab(event, 'tab2')">Next</button>
    </div>



    
    <div id="tab2" class="tab-content">
        <div class="row">
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="account_number" name="account_number" class="did-floating-input" placeholder=""  >
                <label for="account_number" class="did-floating-label">Account Number</label>
                </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="account_name" name="account_name" class="did-floating-input" placeholder="">
                <label for="account_name" class="did-floating-label">Account Name</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <select class="did-floating-select" name="bank_name" id="bank_name" >
                   <?php include("banks-dropdown.php");?>
                   </select>
                 <label for="bank_name" class="did-floating-label">Bank Name</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="ifsc_code" name="ifsc_code" class="did-floating-input" placeholder=""  >
                <label for="ifsc_code" class="did-floating-label">IFSC Code</label>
                </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <select class="did-floating-select" id="account_type" name="account_type" >
                    <!-- <option value="" disabled>Account Type</option> -->
                    <!-- <option value="">Please Select</option> -->
                    <option value="current account">Current Account</option>
                    <option value="savings account" selected="">Savings Account</option>
                    <option value="overdraft account">Overdraft Account</option>

                </select>
                 <label for="account_type" class="did-floating-label">Account Type</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="branch_name" name="branch_name" class="did-floating-input" placeholder="" >
                <label for="branch_name" class="did-floating-label">Branch Name</label>
            </div>
            </div>
            <h5 class="fs-4 p-4 col-lg-12">Tax information</h5>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="pan" name="pan" class="did-floating-input" placeholder="" >
                <label for="pan" class="did-floating-label">PAN</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <input type="text" id="tan" name="tan" class="did-floating-input" placeholder="">
                <label for="tan" class="did-floating-label">TAN</label>
                </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                   <select class="did-floating-select" name="tds_slab_rate" id="tds_slab_rate">
                    <!-- <option  disabled >TDS Slab Rate</option> -->
                    <option selected="" value="0">TDS Not Applicable</option>
                    <option value="1-194-O">1% - 194-O | Payment or credit of amount by the e-commerce operator to e-commerce participant</option>              
                    <option value="1-194C">1% - 194C | HUF/Individuals</option>
                    <option value="1-194-IA">1% - 194-IA | Payment on transfer of certain immovable property other than agricultural land</option>
                    <option value="1-194S">1% - 194S | TDS on payment for Virtual Digital Assets</option>
                    <option value="2-194C">2% - 194C | Others</option>
                    <option value="2-194-I">2% - 194-I | Plant and Machinery</option>
                    <option value="2-194J">2% - 194J | Sum paid or payable towards fees for technical services</option>
                    <option value="2">2% - 194J | Sum paid or payable towards royalty in the nature of consideration for sale, distribution or exhibition of cinematographic films</option>
                    <option value="2">2% - 194N | Cash withdrawal-in excess of Rs. 1crore</option>
                    <option value="2">2% - 194N | Cash withdrawal-aggregate of the amount of withdrawal exceeds Rs. 20lakhs</option>
                    <option value="5">5% - 194N | Cash withdrawal-aggregate of the amount of withdrawal exceeds Rs. 1crore</option>
                    <option value="5">5% - 194D | Insurance commission-Individuals</option>
                    <option value="5">5% - 194G | Commission on sale of lottery tickets</option>
                    <option value="5">5% - 194H | Commission or brokerage</option>
                    <option value="5">5% - 206AB | TDS on non-filters of ITR at higher rates</option>
                    <option value="5">5% - 194DA | The tax shall be deducted on the amount of income comprised in insurance pay-out</option>
                    <option value="5">5% - 194-IB | Payment of rent by individual or HUF not liable to tax audit</option>
                    <option value="5">5% - 194LB | Payment of interest on infrastructure debt fund to Non Resident</option>
                    <option value="5">5% - 194M | Payment of commission, brokerage, contactual fee, professional fee</option>
                    <option value="10">10% - 194R | TDS on benefit or prequisite of a business or profession</option>
                    <option value="10">10% - 194LBA | Interest received from a SPV or income received from renting or leasing or real estate</option>
                    <option value="10">10% - 194LBB | Investment fund paying an income to a unit holder</option>
                    <option value="10">10% - 194-IC | Payment of monetary consideartion under Joint Development Agreements</option>
                    <option value="10">10% - 194J | Any other sum</option>
                    <option value="10">10% - 194K | Income in respect of units payable to resident person</option>
                    <option value="10">10% - 194LA | Payment of compensation on acquisition of certain immovable property</option>
                    <option value="10">10% - 192A | Payment of accumulated balance of provident fund which is taxable in the hands of an employee</option>
                    <option value="10">10% - 193 | Interest on securities</option>
                    <option value="10">10% - 194 | Divident</option>
                    <option value="10">10% - 194A | Senior Citizen</option>
                    <option value="10">10% - 194A | Others</option>
                    <option value="10">10% - 194A | Interest other than "Interest on securities"</option>
                    <option value="10">10% - 194-I | Land or building or furniture or fitting</option>
                    <option value="10">10% - 194EE | Payment in respect of deposit under National Savings Scheme</option>
                    <option value="10">10% - 194D | Insurance commission-Companies</option>
                    <option value="20">20% - 194E | Payment to non-resident sportsmen/sports association</option>
                    <option value="20">20% - 206AA | TDS rate in case of Non availability of PAN</option>
                    <option value="20">20% - 194F | Payment on account of repurchase of unit by Mutual Fund or Unit Trust of India</option>
                    <option value="25">25% - 194LBC | Income in respect of investment made in a securitisation trust-HUF/Individuals</option>
                    <option value="30">30% - 194B | Winnings from lotteries, crossword puzzles, card games and other games of any sort</option>
                    <option value="30">30% - 194BB | Winnings from horse races</option>
                    <option value="30">30% - 194LBC | Income in respect of investment made in a securitisation trust-Others</option>
                </select>
                     <label for="tds_slab_ratio" class="did-floating-label">TDS Slab Ratio</label>
                </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
               <select class="did-floating-select" name="currency" id="currency">
                    <!-- <option disabled>Currency</option> -->
                    <option value="Indian Rupee" selected="">Indian Rupee</option>
                    <option value="US Dollar">US Dollar</option>
                    <option value="Euro">Euro</option>
                    <option value="British Pound">British Pound</option>
                    <option value="Australian Dollar">Australian Dollar</option>
                    <option value="Canadian Dollar">Canadian Dollar</option>
                    <option value="Singapore Dollar">Singapore Dollar</option>
                    <option value="Swiss Franc">Swiss Franc</option>
                    <option value="Malaysian Ringgit">Malaysian Ringgit</option>
                    <option value="Japanese Yen">Japanese Yen</option>
                    <option value="Chinese Yuan Renminbi">Chinese Yuan Renminbi</option>
                </select>
                <label for="currency" class="did-floating-label">Currency</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <select class="did-floating-select" name="terms_of_payment" id="terms_of_payment">
                    <option value="" selected=""> - Select Terms of Payment - </option>
                    <option value="PIA - Payment in advance">PIA - Payment in advance</option>
                    <option value="Net 7 - Payment seven days after invoice date">Net 7 - Payment seven days after invoice date</option>
                    <option value="Net 10 - Payment ten days after invoice date">Net 10 - Payment ten days after invoice date</option>
                    <option value="Net 30 - Payment 30 days after invoice date">Net 30 - Payment 30 days after invoice date</option>
                    <option value="Net 60 - Payment 60 days after invoice date">Net 60 - Payment 60 days after invoice date</option>
                    <option value="Net 90 - Payment 90 days after invoice date">Net 90 - Payment 90 days after invoice date</option>
                    <option value="EOM - End of month">EOM - End of month</option>
                    <option value="21 MFI - 21st of the month following invoice date">21 MFI - 21st of the month following invoice date</option>
                    <option value="COD - Cash on delivery">COD - Cash on delivery</option>
                    <option value="Cash account - Account conducted on a cash basis, no credit">Cash account - Account conducted on a cash basis, no credit</option>
                    <option value="CND - Cash next delivery">CND - Cash next delivery</option>
                    <option value="CBS - Cash before shipment">CBS - Cash before shipment</option>
                    <option value="CIA - Cash in advance">CIA - Cash in advance</option>
                    <option value="CWO - Cash with order">CWO - Cash with order</option>
                </select>
                <label for="terms_of_payment" class="did-floating-label">Terms of Payment</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
                <select id="reverse_charge" name="reverse_charge" class="did-floating-select">
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <label for="reverse_charge" class="did-floating-label">Apply Reverse Charge by Default?</label>
            </div>
            </div>
            <div class="mb-1 col-lg-4">
                <div class="did-floating-label-content">
               <select class="did-floating-select" id="export_type" name="export_type">
                    <option value="not applicable">Not Applicable</option>
                    <option value="exports">Exports</option>
                    <option value="deemed exports">Deemed Exports</option>
                    <option value="sez unit">SEZ Unit/Developer</option>
                </select>
                 <label for="export_type" class="did-floating-label">Export or SEZ Developer</label>
            </div>
            </div>  
        </div>
        <button type="button" class="next-btn btn btn-md btn-info float-right" id="next-btn-2" onclick="openTab(event, 'tab3')">Next</button>
    </div>
    <div id="tab3" class="tab-content">
    <div class="row">
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
            <input type="text" id="bill_address_line1" name="bill_address_line1" class="did-floating-input" placeholder="" >
            <label for="bill_address_line1" class="did-floating-label">Address Line 1</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
            <input type="text" id="bill_address_line2" name="bill_address_line2" class="did-floating-input" placeholder="" >
            <label for="bill_address_line2" class="did-floating-label">Address Line 2</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="bill_city" name="bill_city" class="did-floating-input" placeholder="" >
                <label for="bill_city" class="did-floating-label">City</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="bill_pin_code" name="bill_pin_code" class="did-floating-input" placeholder="" >
                <label for="bill_pin_code" class="did-floating-label">Pin Code</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <select id="bill_state" name="bill_state" class="did-floating-select" >
                     <?php include("states-dropdown.php");?>
                </select>
                 <label for="bill_state" class="did-floating-label">State</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
                <select id="bill_country" name="bill_country" class="did-floating-select" >
                    <?php include("country-dropdown.php");?>
                </select>
                <label for="bill_country" class="did-floating-label">Country</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="bill_branch_name" name="bill_branch_name" class="did-floating-input" placeholder="" >
                <label for="bill_branch_name" class="did-floating-label">Branch Name</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="bill_gstin" name="bill_gstin" class="did-floating-input" placeholder="">
                <label for="bill_gstin" class="did-floating-label">GSTIN</label>
            </div>
        </div>
    </div>
    <button type="button" class="next-btn btn btn-md btn-info float-right" id="next-btn-3" onclick="openTab(event, 'tab4')">Next</button>
    </div>
    <div id="tab4" class="tab-content">
        <div class="row">
      <div class="text-center">
        <label class="form-check-label">
        <input type="checkbox" id="checkbox_id" name="checkbox_name" class="form-check-input" checked>
        Shipping address is same as billing address</label>
    </div>
        </div>

    <div id="addressForm">
    <div class="row">
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
            <input type="text" id="ship_address_line1" name="ship_address_line1" class="did-floating-input" placeholder="" >
            <label for="ship_address_line1" class="did-floating-label">Address Line 1</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
            <input type="text" id="ship_address_line2" name="ship_address_line2" class="did-floating-input" placeholder="">
            <label for="ship_address_line2" class="did-floating-label">Address Line 2</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="ship_city" name="ship_city" class="did-floating-input" placeholder="">
                <label for="ship_city" class="did-floating-label">City</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="ship_pin_code" name="ship_pin_code" class="did-floating-input" placeholder="" >
                <label for="ship_pin_code" class="did-floating-label">Pin Code</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <select id="ship_state" name="ship_state" class="did-floating-select">
                    <?php include("states-dropdown.php");?>
                </select>
                 <label for="ship_state" class="did-floating-label">State</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
                <select id="ship_country" name="ship_country" class="did-floating-select">
                   <?php include("country-dropdown.php");?>
                </select>
                <label for="ship_country" class="did-floating-label">Country</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="ship_branch_name" name="ship_branch_name" class="did-floating-input" placeholder="">
                <label for="ship_branch_name" class="did-floating-label">Branch Name</label>
            </div>
        </div>
        <div class="mb-1 col-lg-6">
             <div class="did-floating-label-content">
                <input type="text" id="ship_gstin" name="ship_gstin" class="did-floating-input" placeholder="">
                <label for="ship_gstin" class="did-floating-label">GSTIN</label>
        </div>
        </div>
    </div>
    </div>
    <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <button type="submit" class="btn btn-primary" id="submit_btn">Submit</button>
    </div>
    </div>
</div>
</div> 

    </form>
</div>
<script>
    // function validateName(input_str) {
    //     var re = /^[a-zA-Z ]{2,30}$/;
    //     return re.test(input_str);
    // }
    // function validateDisplayName(input_str) {
    //     var re = /^[a-zA-Z ]{2,30}$/;
    //     return re.test(input_str);
    // }

    // function validateMobileNumber(input_str) {
    //     var re = /^(\+\d{1,3}[- ]?)?\d{10}$/;
    //     return re.test(input_str);
    // }

    // function validatePhoneNumber(input_str) {
    //     var re = /^(\+\d{1,3}[- ]?)?\d{10}$/;
    //     return re.test(input_str);
    // }

    // function validateEmail(input_str) {
    //     var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    //     return re.test(input_str);
    // }

    // function displayError(inputId, errorId, isValid) {
    //     var errorElement = document.getElementById(errorId);
    //     if (!isValid) {
    //         errorElement.classList.remove('hidden');
    //     } else {
    //         errorElement.classList.add('hidden');
    //     }
    // }

    // function validateAndDisplayError(inputId, validationFunction, errorId) {
    //     var inputValue = document.getElementById(inputId).value;
    //     var isValid = validationFunction(inputValue);
    //     displayError(inputId + '_error', errorId, isValid);
    // }

    // function validateForm(inputId, validationFunction, errorId) {
    //     validateAndDisplayError(inputId, validationFunction, errorId);
    // }

    function enableSubmitButton() {
        // No need to disable the submit button in this case
    }

    // document.getElementById('name').addEventListener('input', function() {
    //     validateForm('name', validateName, 'name_error');
    // });

    // document.getElementById('display_name').addEventListener('input', function() {
    //     validateForm('display_name', validateDisplayName, 'display_name_error');
    // });

    // document.getElementById('mobile_number').addEventListener('input', function() {
    //     validateForm('mobile_number', validateMobileNumber, 'mobile_number_error');
    // });

    // document.getElementById('email').addEventListener('input', function() {
    //     validateForm('email', validateEmail, 'email_error');
    // });

    // document.getElementById('phone_number').addEventListener('input', function() {
    //     validateForm('phone_number', validatePhoneNumber, 'phone_number_error');
    // });

    // document.getElementById('submit_btn').addEventListener('click', function(event) {
    //     // Validate all fields before submission
    //     validateForm('name', validateName, 'name_error');
    //     validateForm('display_name', validateDisplayName, 'display_name_error');
    //     validateForm('mobile_number', validateMobileNumber, 'mobile_number_error');
    //     validateForm('email', validateEmail, 'email_error');
    //     validateForm('phone_number', validatePhoneNumber, 'phone_number_error');

    //     // Prevent form submission if any field has an error
    //     if (document.querySelectorAll('.error:not(.hidden)').length > 0) {
    //         event.preventDefault();
    //     }
    // });

function validateNumericInput(input) {
    input.value = input.value.replace(/[^0-9]/g, ''); // Replace any non-numeric characters
}

function validateName(input_str) {
    var re = /^[a-zA-Z ]{2,30}$/;
    return re.test(input_str);
}

function validateDisplayName(input_str) {
    var re = /^[a-zA-Z ]{2,30}$/;
    return re.test(input_str);
}

function validateMobileNumber(input_str) {
    // Allow only digits and exactly 10 digits
    var re = /^\d{10}$/;
    return re.test(input_str);
}

function validatePhoneNumber(input_str) {
    // Allow only digits and exactly 10 digits
    var re = /^\d{10}$/;
    return re.test(input_str);
}

function validateEmail(input_str) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(input_str);
}

function validateGSTIN(input_str) {
    var re = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
    return re.test(input_str);
}

function displayError(inputId, errorId, isValid) {
    var errorElement = document.getElementById(errorId);
    if (!isValid) {
        errorElement.classList.remove('hidden');
    } else {
        errorElement.classList.add('hidden');
    }
}

// function validateAndDisplayError(inputId, validationFunction, errorId) {
//     var inputValue = document.getElementById(inputId).value.trim();
//     var isValid = validationFunction(inputValue) || inputValue === ""; // Validate only if not empty
//     displayError(inputId, errorId, isValid);
//     return isValid;  // Return the validation result
// }


//     function validateAndDisplayError(inputId, validationFunction, errorId) {
//         var inputValue = document.getElementById(inputId).value;
//         var isValid = validationFunction(inputValue);
//         displayError(inputId + '_error', errorId, isValid);
//     }
function validateAndDisplayError(inputId, validationFunction, errorId) {
    var inputValue = document.getElementById(inputId).value.trim();
    var isValid = (inputValue === "") || validationFunction(inputValue); // Validate only if not empty
    displayError(inputId, errorId, isValid);
    return isValid;
}

// function validateCurrentTab(tabId) {
//     let isValid = true;
//     const currentTab = document.getElementById(tabId);
//     const requiredFields = currentTab.querySelectorAll('[required]');
    
//     requiredFields.forEach(function(field) {
//         const value = field.value.trim();
//         if (!value) {
//             isValid = false;
//             field.classList.add('highlight-error');
//         } else {
//             field.classList.remove('highlight-error');
//         }
//     });

//     return isValid;
// }

function validateCurrentTab(tabId) {
    let isValid = true;

    // Validate required fields
    isValid &= validateAndDisplayError('name', validateName, 'name_error');
    isValid &= validateAndDisplayError('mobile_number', validateMobileNumber, 'mobile_number_error');
    isValid &= validateAndDisplayError('email', validateEmail, 'email_error');

    // Optionally validate fields if they have a value
    isValid &= validateAndDisplayError('customer_gstin', validateGSTIN, 'customer_gstin_error');
    isValid &= validateAndDisplayError('phone_number', validatePhoneNumber, 'phone_number_error');
    isValid &= validateAndDisplayError('display_name', validateDisplayName, 'display_name_error');

    // Highlight fields with errors
    const requiredFields = document.getElementById(tabId).querySelectorAll('[required]');
    requiredFields.forEach(function(field) {
        const value = field.value.trim();
        if (!value) {
            isValid = false;
            field.classList.add('highlight-error');
        } else {
            field.classList.remove('highlight-error');
        }
    });

    return !!isValid;
}



document.getElementById('name').addEventListener('input', function() {
    validateAndDisplayError('name', validateName, 'name_error');
});

document.getElementById('display_name').addEventListener('input', function() {
    validateAndDisplayError('display_name', validateDisplayName, 'display_name_error');
});

document.getElementById('mobile_number').addEventListener('input', function() {
    validateAndDisplayError('mobile_number', validateMobileNumber, 'mobile_number_error');
});

document.getElementById('email').addEventListener('input', function() {
    validateAndDisplayError('email', validateEmail, 'email_error');
});

document.getElementById('phone_number').addEventListener('input', function() {
    validateAndDisplayError('phone_number', validatePhoneNumber, 'phone_number_error');
});

document.getElementById('customer_gstin').addEventListener('input', function() {
    validateAndDisplayError('customer_gstin', validateGSTIN, 'customer_gstin_error');
});


document.getElementById('submit_btn').addEventListener('click', function(event) {
    let isFormValid = true;

    // Validate required fields
    isFormValid &= validateAndDisplayError('name', validateName, 'name_error');
    isFormValid &= validateAndDisplayError('display_name', validateDisplayName, 'display_name_error');
    isFormValid &= validateAndDisplayError('mobile_number', validateMobileNumber, 'mobile_number_error');
    isFormValid &= validateAndDisplayError('email', validateEmail, 'email_error');
    
    // Validate optional fields only if they have a value
    // isFormValid &= validateAndDisplayError('phone_number', validatePhoneNumber, 'phone_number_error');
    // isFormValid &= validateAndDisplayError('customer_gstin', validateGSTIN, 'customer_gstin_error');
    
    if (!isFormValid) {
        event.preventDefault();
    }
});

</script>
<script>
    // Ensure tab1 is displayed initially
    document.getElementById('tab1').style.display = 'block';

    // function openTab(evt, tabName) {
    //     var i, tabcontent, tablinks;
    //     tabcontent = document.getElementsByClassName("tab-content");
    //     for (i = 0; i < tabcontent.length; i++) {
    //         tabcontent[i].style.display = "none";
    //     }
    //     tablinks = document.getElementsByClassName("tab-button");
    //     for (i = 0; i < tablinks.length; i++) {
    //         tablinks[i].className = tablinks[i].className.replace(" active", "");
    //     }
    //     document.getElementById(tabName).style.display = "block";
    //     evt.currentTarget.className += " active";
    // }


function enableOrDisableNextButton(tabId, nextButtonId) {
    const isTabValid = validateCurrentTab(tabId);
    const nextButton = document.getElementById(nextButtonId);
    if (nextButton) {
        nextButton.disabled = !isTabValid;  // Disable the button if the tab is not valid
    }
}

// Attach event listeners to inputs
document.querySelectorAll('.tab-content input, .tab-content select').forEach(function(input) {
    input.addEventListener('input', function() {
        const currentTabId = input.closest('.tab-content').id;
        const nextButton = input.closest('.tab-content').querySelector('.next-btn');
        if (nextButton) {
            enableOrDisableNextButton(currentTabId, nextButton.id);
        }
    });
});

// document.querySelectorAll('.tab-content input, .tab-content select').forEach(function(input) {
//     input.addEventListener('input', function() {
//         const currentTabId = input.closest('.tab-content').id;
//         const nextButton = input.closest('.tab-content').querySelector('.next-btn');
//         if (nextButton) {
//             enableOrDisableNextButton(currentTabId, nextButton.id);
//         }
//     });
// });


function openTab(evt, tabName) {
    const currentTabId = evt.currentTarget.closest('.tab-content').id;
    const isTabValid = validateCurrentTab(currentTabId);

    if (!isTabValid) {
        evt.preventDefault();
        document.getElementById('top-error-message').innerText = 'Please fill out all required fields before proceeding.';
        document.getElementById('top-error-message').classList.remove('hidden');
        return;
    }

    // Hide all tabs
    const tabcontent = document.getElementsByClassName("tab-content");
    for (let i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove "active" class from all buttons
    const tablinks = document.getElementsByClassName("tab-button");
    for (let i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";

    // Hide top error message when switching tabs
    document.getElementById('top-error-message').classList.add('hidden');
}

document.getElementById('submit_btn').addEventListener('click', function(event) {
    const isFormValid = validateAllTabs();

    // Prevent form submission if any field has an error
    if (!isFormValid) {
        event.preventDefault();
    }
});

// Enable "Next" button for the first tab on page load
// Enable "Next" button for the first tab on page load
// Enable "Next" button for the first tab on page load
document.addEventListener("DOMContentLoaded", function() {
    enableOrDisableNextButton('tab1', 'next-btn-1');
});


</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var checkbox = document.getElementById("checkbox_id");
        var addressForm = document.getElementById("addressForm");

        function toggleAddressForm() {
            if (checkbox.checked) {
                addressForm.style.display = "none";
            } else {
                addressForm.style.display = "block";
            }
        }

        toggleAddressForm(); // Initial call to set the form's display

        checkbox.addEventListener("change", toggleAddressForm);
    });
</script>

<div id="top-error-message" class="error hidden" style="text-align: center; margin-bottom: 20px;"></div>

<script>
    function validateAllTabs() {
        let isValid = true;
        const requiredFields = [
            { id: 'name', validator: validateName, errorId: 'name_error' },
            // { id: 'display_name', validator: validateDisplayName, errorId: 'display_name_error' },
            { id: 'mobile_number', validator: validateMobileNumber, errorId: 'mobile_number_error' },
            { id: 'email', validator: validateEmail, errorId: 'email_error' },
            // { id: 'phone_number', validator: validatePhoneNumber, errorId: 'phone_number_error' },
            // Add other required fields here
        ];

        requiredFields.forEach(function(field) {
            const inputValue = document.getElementById(field.id).value;
            const fieldIsValid = field.validator(inputValue);
            displayError(field.id, field.errorId, fieldIsValid);

            if (!fieldIsValid) {
                isValid = false;
            }
        });

        if (!isValid) {
            document.getElementById('top-error-message').innerText = 'Please fill out all required fields.';
            document.getElementById('top-error-message').classList.remove('hidden');
        } else {
            document.getElementById('top-error-message').classList.add('hidden');
        }

        return isValid;
    }

    document.getElementById('submit_btn').addEventListener('click', function(event) {
        const isFormValid = validateAllTabs();

        // Prevent form submission if any field has an error
        if (!isFormValid) {
            event.preventDefault();
        }
    });
</script>
<script src="assets/js/myscript.js"></script>
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>
<script src="assets/js/pcoded.min.js"></script>
</body>
</html>