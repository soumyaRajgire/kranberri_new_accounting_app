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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
</head>
<style>
    .switch {
  position: relative;
  display: inline-block;
  width: 46px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 24px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:checked + .slider:before {
  transform: translateX(22px);
}

/* Rounded slider */
.slider.round {
  border-radius: 34px;
}
.edit-icon {
        font-size: 1.2rem;
        cursor: pointer;
    }

    .edit-icon:hover {
        color: #007bff;
    }

    #buyerDetailsContent {
        font-size: 16px;
        padding: 10px;
        height: auto;
    }

    /* Optional styling to match the style */
    .form-check-label {
        margin-left: 5px;
    }
    
</style>
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
            <!-- Heading Section -->
            <div class="col-md-4">
                <div class="page-header-title">
                    <h4 class="m-b-10">Create Purchase</h4>
                </div>
            </div>
            <div class="col-md-8 text-right">
               
                <a href="purchase.php" class="btn btn-dark rounded-circle">
                    <i class="fas fa-arrow-left"></i>
                </a>
        
        </div>
        </div>
        
 
            
    </div>
</div>

            <hr>

            <div class="card" style="border-radius: 5px; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);">
                <div class="card-body">
         <!-- Purchase Details Section -->
         <div class="card">
    <div class="card-body">
        <div class="row">
            <!-- Left Section with Fields -->
            <div class="col-md-5">
                <form>
                    <!-- Tax Invoice Section -->
                    <div class="form-group">
                        <div class="d-flex">
                            <div class="form-check mr-3">
                                <input class="form-check-input" type="radio" name="invoiceType" id="taxInvoice" value="Tax Invoice" checked>
                                <label class="form-check-label" for="taxInvoice">Tax Invoice</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="invoiceType" id="billOfSupply" value="Bill of Supply">
                                <label class="form-check-label" for="billOfSupply">Bill of Supply</label>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Purchase Info -->
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="invoicePrefix">Invoice Purchase Prefix</label>
                            <input type="text" class="form-control" id="invoicePrefix" placeholder="">
                        </div>
                        <div class="col-md-6">
                            <label for="invoiceNumber">Invoice Purchase No.</label>
                            <input type="text" class="form-control" id="invoiceNumber" placeholder="">
                        </div>
                    </div>

                    <!-- Purchase Date -->
                    <div class="form-group">
                        <label for="purchaseDate">Purchase Date</label>
                        <input type="date" class="form-control" id="purchaseDate" value="">
                    </div>
                </form>
            </div>

            <!-- Right Section with Buyer Details -->
            <div class="col-md-7">
    <div class="card">
        <!-- Card Header with Edit Icon -->
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f1f3f5; font-weight: bold;">
            BUYER DETAILS
            <!-- Edit Icon -->
            <span class="edit-icon" data-toggle="modal" data-target="#editBuyerModal" style="cursor: pointer;">
                <i class="fas fa-edit"></i>
            </span>
        </div>
        
        <!-- Card Body -->
        <div class="card-body">
            <div id="buyerDetailsContent" class="form-control mt-2">
                My Company
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

<!-- Modal for Editing Buyer Details -->
<div class="modal fade" id="editBuyerModal" tabindex="-1" role="dialog" aria-labelledby="editBuyerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBuyerModalLabel">Edit Buyer Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for Editing Buyer Details -->
                <form id="buyerDetailsForm">
                    <div class="form-group">
                        <label for="buyerName">Buyer Name</label>
                        <input type="text" class="form-control" id="buyerName" placeholder="Enter Buyer Name" value="My Company">
                    </div>
                    <div class="form-group">
                        <label for="buyerEmail">Buyer Email</label>
                        <input type="email" class="form-control" id="buyerEmail" placeholder="Enter Buyer Email">
                    </div>
                    <button type="button" class="btn btn-primary" id="saveBuyerDetails">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>






        <!-- Seller Details Section -->
        <div class="row">
    <div class="col-md-12">
        <!-- Seller Details Card -->
        <div class="card card-section">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f1f3f5; font-weight: bold;">
                <!-- Seller Details Heading -->
                SELLER DETAILS
                <!-- Buttons aligned to the right -->
                <div>
                    <button class="btn btn-secondary btn-sm mx-2">Add New Seller</button>
                    <button class="btn btn-secondary btn-sm">Select Seller</button>
                </div>
            </div>
            <div class="card-body">
                <div class=" ">
                    <div>
                        <div class="row mx-3">
                            <div class="col-md-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="consigneeOption" id="sameAsAbove" value="same" checked>
                            <label class="form-check-label" for="sameAsAbove">Show consignee (same as above)</label>
                        </div>
                        </div>
                        <div class="col-md-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="consigneeOption" id="noConsignee" value="none">
                            <label class="form-check-label" for="noConsignee">Consignee not required</label>
                        </div>
                        </div>
                        <div class="col-md-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="consigneeOption" id="addConsignee" value="add">
                            <label class="form-check-label" for="addConsignee">Add Consignee (if different from above)</label>
                        </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Seller Details Card -->
    </div>
</div>


        <!-- Products Section -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-section">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f1f3f5; font-weight: bold;">
                <!-- Products Heading -->
               PRODUCTS
                <!-- Buttons aligned to the right -->
                <div>
                    <button class="btn btn-secondary btn-sm mx-2">Add New Product</button>
                    <button class="btn btn-secondary btn-sm">Select Product</button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    
                </div>
            </div>
        </div>
    </div>
</div>


       <!-- Left and Right Columns for Transportation Details and Other Details -->
       <div class="row">
            <!-- Left Column: Transportation Details -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#collapseTransportation" aria-expanded="false" aria-controls="collapseTransportation" style="cursor: pointer; background-color: #f1f3f5; font-weight: bold;"">
                        TRANSPORTATION DETAILS
                        <span class="collapse-icon"><i class="fas fa-chevron-down"></i></span>
                    </div>

                    <div id="collapseTransportation" class="collapse" aria-labelledby="headingOne">
                        <div class="card-body">
                        <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="transportationMode" id="none" value="none" checked>
                    <label class="form-check-label" for="none">None</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="transportationMode" id="road" value="road">
                    <label class="form-check-label" for="road">Road</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="transportationMode" id="rail" value="rail">
                    <label class="form-check-label" for="rail">Rail</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="transportationMode" id="air" value="air">
                    <label class="form-check-label" for="air">Air</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="transportationMode" id="ship" value="ship">
                    <label class="form-check-label" for="ship">Ship/Road cum Ship</label>
                </div>
                            <!-- Additional Fields -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label>LR Number</label>
                                    <input type="text" class="form-control" placeholder="Enter LR Number">
                                </div>
                                <div class="col-md-6">
                                    <label>Vehicle Number</label>
                                    <input type="text" class="form-control" placeholder="Enter Vehicle Number">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label>Date of Supply</label>
                                    <input type="date" class="form-control" value="2024-10-22">
                                </div>
                                <div class="col-md-6">
                                    <label>Place of Supply</label>
                                    <input type="text" class="form-control" placeholder="Place of Supply">
                                </div>
                            </div>

                            <div class="card mt-3">
    <div class="card-header" style="background-color: #f1f3f5; font-weight: bold;">
        TRANSPORTER (OPTIONAL FIELD)
        <div class="float-right">
            <!-- Add buttons or icons if necessary here -->
            <button class="btn btn-sm btn-secondary"><i class="fas fa-plus"></i></button>
            <button class="btn btn-sm btn-secondary"><i class="fas fa-bars"></i></button>
        </div>
    </div>
</div>

<!-- Reduce the margin here by replacing 'mt-3' with 'mt-1' or removing it -->
<div class="card mt-1">
    <div class="card-header bg-light" style="border: 1px solid #ddd; font-weight: bold;">
        Optional Details
    </div>
    <div class="card-body" style="border: 1px solid #ddd; border-top: none;">
        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Enter Optional Field 1">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Enter Optional Value 1">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Enter Optional Field 2">
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Enter Optional Value 2">
            </div>
        </div>
    </div>
</div>


   
                        </div>
                    </div>
                </div>
            </div>

           <!-- Right Column: Other Details -->
<div class="col-md-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#collapseOtherDetails" aria-expanded="false" aria-controls="collapseOtherDetails" style="cursor: pointer;background-color: #f1f3f5; font-weight: bold;">
            OTHER DETAILS
            <span class="collapse-icon"><i class="fas fa-chevron-down"></i></span>
        </div>

        <div id="collapseOtherDetails" class="collapse">
            <div class="card-body" style="border: 1px solid #ddd; border-top: none;">
                <!-- PO Number and Date -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>PO Number</label>
                            <input type="text" class="form-control" placeholder="Enter PO Number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>PO Date</label>
                            <input type="date" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Challan Number and ewayBill -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Challan Number</label>
                            <input type="text" class="form-control" placeholder="Enter Challan Number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ewayBill Number</label>
                            <input type="text" class="form-control" placeholder="Enter ewayBill Number">
                        </div>
                    </div>
                </div>

                <!-- Reverse Charge Checkbox -->
                <div class="form-check mb-3 mt-3">
                    <input class="form-check-input" type="checkbox" id="reverseCharge">
                    <label class="form-check-label" for="reverseCharge">
                        Is transaction applicable for reverse Charge?
                    </label>
                </div>

                <!-- TCS/TDS Radio Buttons -->
                <div class="form-group mt-2 mb-4">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="taxType" id="tcs" value="TCS">
                        <label class="form-check-label" for="tcs">TCS</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="taxType" id="tds" value="TDS">
                        <label class="form-check-label" for="tds">TDS</label>
                    </div>
                </div>

                <!-- Freight, Insurance, Loading, Packing, and Other Charges -->
                <div class="form-group">
                    <label>Freight Charge</label>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Taxable Amount">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="GST(%)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Amount">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Insurance Charge</label>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Taxable Amount">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="GST(%)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Amount">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Loading Charge</label>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Taxable Amount">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="GST(%)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Amount">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Packing Charge</label>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Taxable Amount">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="GST(%)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Amount">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Other Charge</label>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Other Charge Name">
                        </div>
                        </div>
                        <div class="row mt-2">
                        <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Taxable Amount">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="GST(%)">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Amount">
                        </div>
                    </div>
                </div>

                <!-- Optional Fields Section -->
                <div class="card mt-3">
                    <div class="card-header bg-light" style="border: 1px solid #ddd; font-weight: bold;">
                        Optional Fields
                    </div>
                    <div class="card-body" style="border: 1px solid #ddd; border-top: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Enter Optional Field 1">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Enter Optional Value 1">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Enter Optional Field 2">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Enter Optional Value 2">
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

        </div>
        <div class="card">
    <div class="card-body">
        <!-- Checkbox Options Section -->
        <div class="row mx-4">
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="originalRecipient">
                    <label class="form-check-label" for="originalRecipient">
                        Original for Recipient
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="duplicateTransporter">
                    <label class="form-check-label" for="duplicateTransporter">
                        Duplicate for Transporter
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="triplicateSupplier">
                    <label class="form-check-label" for="triplicateSupplier">
                        Triplicate for Supplier
                    </label>
                </div>
            </div>
        </div>
    </div> <!-- End of card-body -->
</div> <!-- End of card -->
<!-- Bank Details Card with Switch -->
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f1f3f5; font-weight: bold;">
        BANK DETAILS
        <label class="switch">
            <input type="checkbox" id="bankDetailsToggle" onclick="toggleBankDetails()">
            <span class="slider round"></span>
        </label>
    </div>
    
    <!-- Hidden Bank Details Form -->
    <div class="card-body" id="bankDetailsForm" style="display: none;">
        <div class="row">
            <div class="col-md-6">
                <label for="accountNumber">Account Number</label>
                <input type="text" class="form-control" id="accountNumber" placeholder="Enter Account Number">
            </div>
            <div class="col-md-6">
                <label for="accountHolderName">Account Holder Name</label>
                <input type="text" class="form-control" id="accountHolderName" placeholder="Enter Account Holder Name">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="bankName">Bank Name</label>
                <input type="text" class="form-control" id="bankName" placeholder="Enter Bank Name">
            </div>
            <div class="col-md-6">
                <label for="ifscCode">IFSC Code</label>
                <input type="text" class="form-control" id="ifscCode" placeholder="Enter IFSC Code">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="branchName">Branch Name</label>
                <input type="text" class="form-control" id="branchName" placeholder="Branch Name">
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Toggle the Bank Details Form -->
<script>
function toggleBankDetails() {
    var bankForm = document.getElementById("bankDetailsForm");
    var toggleSwitch = document.getElementById("bankDetailsToggle");
    
    if (toggleSwitch.checked) {
        bankForm.style.display = "block";
    } else {
        bankForm.style.display = "none";
    }
}
</script>
<!-- Terms & Conditions Section -->
<!-- Editable Terms & Conditions Card -->
<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title">Terms & Conditions</h5>
        <!-- Non-editable text by default -->
        <p class="card-text" id="termsText" onclick="editTerms()">
            This is an electronically generated document
        </p>
        <!-- Hidden text area for editing -->
        <textarea id="termsTextarea" class="form-control" rows="2" style="display:none;" 
                  onblur="saveTerms()"></textarea>
    </div>
</div>

<script>
    function editTerms() {
        // Hide the paragraph and show the textarea for editing
        const termsText = document.getElementById('termsText');
        const termsTextarea = document.getElementById('termsTextarea');
        
        termsTextarea.value = termsText.innerText;
        termsText.style.display = 'none';
        termsTextarea.style.display = 'block';
        termsTextarea.focus();  // Automatically focus on the textarea for editing
    }

    function saveTerms() {
        // Save the content from textarea back to the paragraph
        const termsText = document.getElementById('termsText');
        const termsTextarea = document.getElementById('termsTextarea');
        
        if (termsTextarea.value.trim() !== '') {
            termsText.innerText = termsTextarea.value;
        }

        // Toggle back to non-editable mode
        termsText.style.display = 'block';
        termsTextarea.style.display = 'none';
    }
</script>



    <!-- UPLOAD SIGNATURE (optional) Section -->
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f1f3f5; font-weight: bold;">
        UPLOAD SIGNATURE (optional)
        <!-- Toggle Switch -->
        <label class="switch">
            <input type="checkbox" id="signatureToggle" onclick="toggleSignatureSection()" checked>
            <span class="slider round"></span>
        </label>
    </div>

   <!-- Signature Upload Form -->
<div class="card-body" id="signatureForm">
    <div class="row">
        <!-- Text Area for Signature Upload on the Left -->
        <div class="col-md-6">
            <div class="upload-box">
                <textarea class="form-control" id="signatureText" placeholder="Signature File" rows="4" style="border: none; outline: none;" readonly></textarea>
            </div>
        </div>

        <!-- Buttons on the Right -->
        <div class="col-md-2 d-flex flex-column justify-content-center">
            <input type="file" id="signatureUpload" style="display: none;" />
            <button class="btn btn-primary mb-2" type="button" onclick="document.getElementById('signatureUpload').click()">Upload</button>
            <button class="btn btn-danger" type="button" onclick="removeSignature()">Remove</button>
        </div>
    </div>
</div>
</div>

<!-- JavaScript to Toggle the Signature Upload Form -->
<script>
function toggleSignatureSection() {
    var signatureForm = document.getElementById("signatureForm");
    var toggleSwitch = document.getElementById("signatureToggle");

    if (toggleSwitch.checked) {
        signatureForm.style.display = "block";
    } else {
        signatureForm.style.display = "none";
    }
}
</script>
<script>
    // Automatically update the text area with file name when a file is selected
    document.getElementById('signatureUpload').addEventListener('change', function() {
        const fileName = this.files[0] ? this.files[0].name : '';
        document.getElementById('signatureText').value = fileName;
    });

    // Clear the text area and reset file input when Remove button is clicked
    function removeSignature() {
        document.getElementById('signatureText').value = '';
        document.getElementById('signatureUpload').value = '';
    }
</script>
<div class="col-md-12 text-right">
                <!-- Cancel and Submit Buttons (Right Side) -->
                <button type="button" class="btn btn-secondary mr-2" onclick="cancelAction()">Cancel</button>
                <button type="submit" class="btn btn-primary" onclick="submitAction()">Submit</button>
            </div>
    </div>
    
    </div>
    </div>
    </section>
    <script>
    // When the Save button is clicked inside the modal
    document.getElementById('saveBuyerDetails').addEventListener('click', function() {
        // Get the updated details from the form fields
        var buyerName = document.getElementById('buyerName').value;
        var buyerEmail = document.getElementById('buyerEmail').value;

        // Update the Buyer Details card body with the new content
        var buyerDetailsContent = document.getElementById('buyerDetailsContent');
        buyerDetailsContent.innerHTML = `
            <strong>Buyer Name:</strong> ${buyerName}<br>
            <strong>Buyer Email:</strong> ${buyerEmail}
        `;

        // Close the modal
        $('#editBuyerModal').modal('hide');
    });
</script>
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>