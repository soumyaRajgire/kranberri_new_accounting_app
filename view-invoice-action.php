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
error_reporting(E_ALL);
ini_set('display_errors', 1);
$GLOBALS['invoiceFile'] = '';
$GLOBALS['customerEmail'] ='';
?>  
 

<html lang="en">
<head>
    <title>iiiQbets</title>

    <meta charset="utf-8">
    <?php include("header_link.php");?>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"  crossorigin="anonymous">
<!-- Add SweetAlert2 library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    .text-grey {
    color: grey; /* or any other shade of grey you prefer */
    background-color: white;
}
.tooltip-inner  {
    background-color: white;
    color: grey; /* Set text color to black or your preferred color */
    border-radius: 2px;
    font-size: 13px;
}

h5{
    font-size: 13px !important;
}
</style>

</head>


<body class="">
    <!-- [ Pre-loader ] start -->
        <?php
function getQuotationDetails($conn, $inv_id) {
    $quotationId = $conn->real_escape_string($inv_id); // Sanitize input

    
    $query = "SELECT q.*, c.*, a.*, qi.*
              FROM invoice q
              JOIN customer_master c ON q.customer_id = c.id
              JOIN address_master a ON c.id = a.customer_master_id
              LEFT JOIN invoice_items qi ON q.id = qi.invoice_id
              WHERE q.id = '$quotationId'";

    $result = $conn->query($query);

    // Check if the result contains any rows
    if ($result->num_rows > 0) {
        
       

        $quotationData =$result->fetch_assoc() ;
        $result->data_seek(0); // This resets the pointer back to the start
        $quotationItems = [];
        // Get the selected template from session or set a default
$selected_template = isset($_SESSION['template']) ? $_SESSION['template'] : 'template1';

// Define the template mapping
$template_columns = [
    'template1' => 'invoice_file_template1',
    'template2' => 'invoice_file_template2',
    'template3' => 'invoice_file_template3',
    'template4' => 'invoice_file_template4'
];

// Get the column name for the selected template
$selected_column = isset($template_columns[$selected_template]) ? $template_columns[$selected_template] : 'invoice_file_template1';
        while ($row = $result->fetch_assoc()) {
            
               $invoice_file_path = $row[$selected_column] ?? '';
$GLOBALS['invoiceFile'] = $row[$selected_column] ?? '';
$GLOBALS['customerEmail'] = isset($row['customer_email']) ? $row['customer_email'] : '';


            // If there are invoice items, process them
            $quotationItems[] = [
                'itemno' => $row['itemno'],
                'product' => $row['product'],
                'prod_desc' => $row['prod_desc'],
                'price' => $row['price'],
                'qty' => $row['qty'],
                'line_total' => ($row['price'] * $row['qty']),
                'total' => $row['total'],
                'gst_amt' => $row['total_gst'],
                'gst' => $row['gst'],
                'cess_rate' => $row['cess_rate'],
                'cess_amt' => $row['cess_amount'],
                'discount' => $row['discount'],
                'cgst' => $row['cgst'],
                'sgst' => $row['sgst'],
                'igst' => $row['igst'],
                'customer_id' =>$row['customer_id'],
                'customer_email'=>$row['customer_email']
                
            ];
        }

        // Add quotation items to the main quotation data
        $quotationData['quotation_items'] = $quotationItems;
       $customer_id = $quotationData['customer_id'];
  // Fetch the credit note amount for the invoice (if any)
        $creditNoteQuery = "SELECT SUM(total_amount) AS credit_note_total FROM credit_note WHERE invoice_id = '$quotationId' AND customer_id='$customer_id'";
        $creditNoteResult = $conn->query($creditNoteQuery);
        $creditNoteAmount = 0;
        if ($creditNoteResult->num_rows > 0) {
            $creditNoteData = $creditNoteResult->fetch_assoc();
            $creditNoteAmount = $creditNoteData['credit_note_total'];
        }

        // Calculate the payable amount: invoice total - credit note amount
        // $payableAmount = max(0, $quotationData['total'] - $creditNoteAmount); // Ensure payable amount doesn't go below 0
 if ($creditNoteAmount > 0) {
   
   
         $payableAmount = ($quotationData['grand_total'] - $creditNoteAmount); // Ensure payable amount doesn't go below 0
        } else {
           
            // If no credit note exists, the payable amount is just the invoice amount
            // $payableAmount = $row['grand_total'];
             $payableAmount = $quotationData['grand_total'];
        }
        // Add the payable amount to the quotation data to be used in the view
        $quotationData['payable_amount'] = $payableAmount;
        return $quotationData;
    } else {
        return false; // Quotation not found
    }
}


$inv_id = $_GET['inv_id'];
$quotationDetails = getQuotationDetails($conn, $inv_id);




 $payableAmount = $quotationDetails['payable_amount'];

?>
     <?php include("menu.php");?>
    
    <?php include("createReceiptModal.php");?>

     <?php include("createTDSModal.php");?>
     
   <?php include("creategstCredentialsModal.php");?>  
     
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
                            <h4 class="m-b-10">View Invoice</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Invoice</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
  
<div class="card" style="padding: 5px;">
    <div class="row">
        <div class="col-md-5 col-lg-5">
            <ul class="nav">
                <li class="nav-item mt-2">
                    <h5><a href="#" class="customer_est_name text-primary"  style="font-size: 19px;">Customer: <?php echo $quotationDetails['customerName'];?></a></h5>
                </li>
            </ul>
        </div>
        <div class="col-md-7 d-flex justify-content-end align-items-center">
            <ul class="nav">
                <li class="nav-item">
                    <div class="btn-group" role="group">
<?php
// Assuming that the $inv_id is passed and the invoice contains either products or services

// Fetch the invoice items (product or service) to determine if both are present
$query = "SELECT DISTINCT im.catlog_type FROM invoice_items ii JOIN inventory_master im ON ii.productid = im.id WHERE ii.invoice_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $inv_id);
$stmt->execute();
$result = $stmt->get_result();

// Flags to check if invoice contains products and services
$containsProduct = false;
$containsService = false;

// Loop through the result to set the flags
while ($row = $result->fetch_assoc()) {
    if ($row['catlog_type'] == 'products') {
        $containsProduct = true;
    } elseif ($row['catlog_type'] == 'services') {
        $containsService = true;
    }
}

$stmt->close();

// Check if E-Invoice and E-Way Bill have already been generated for the invoice
$query = "SELECT irn_no, e_way_bill_no, e_way_bill_date, status, customer_id FROM invoice WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $inv_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($irn_no, $eway_bill_no, $eway_bill_date, $invoice_status, $customer_id);
$stmt->fetch();

// Flags to track if E-Invoice and E-Way Bill are already generated
$isEInvoiceGenerated = !empty($irn_no);
$isEWayBillGenerated = !empty($eway_bill_no);

// Fetch the GSTIN number for the customer
$customerQuery = "SELECT gstin FROM customer_master WHERE id = ?";
$stmt = $conn->prepare($customerQuery);
$stmt->bind_param('i', $customer_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($gstin);
$stmt->fetch();
$stmt->close();

// Check if GSTIN is available
$isGSTINAvailable = !empty($gstin);

// Check if cancellation is allowed based on the time since E-Way Bill was generated
$cancelAllowed = false;
if ($isEWayBillGenerated && $eway_bill_date) {
    // Get the timestamp of when the E-Way Bill was generated
    $ewayBillTimestamp = strtotime($eway_bill_date);

    // Calculate the time difference in hours
    $timeDiff = (time() - $ewayBillTimestamp) / 3600;

    // Allow cancellation within 48 hours (adjust as per your requirements)
    if ($timeDiff <= 48) {
        $cancelAllowed = true;
    }
}

// Check GSTIN availability
if (!$isGSTINAvailable) {
    $gstinMessage = "E-invocie & E-way Bill";
    $gstinTooltip = "Click here for more details about GSTIN requirement for E-Invoice and E-Way Bill";
} else {
    $gstinMessage = "E-invocie & E-way Bill";
    $gstinTooltip = "GSTIN is available for generating E-Invoice and E-Way Bill";
}

?>

<?php
if (is_array($quotationDetails) && isset($quotationDetails['status'])) {
    if ($quotationDetails['status'] == "paid") {
        ?>
        <a href="#" data-toggle="modal" data-target="#receiptsModal" class="btn border border-grey d-none" data-toggle="tooltip" data-placement="top" title="Open Link">Receipts</a>
       <!-- <a href="#" data-toggle="modal" data-target="#tdsModal" class="btn border border-grey d-none" data-toggle="tooltip" data-placement="top" title="Open Link">TDS</a>-->
        <a href="view-esign-action.php?inv_id=<?php echo $inv_id; ?>" title="Open Link" class="btn border border-grey" target="_blank">E-Sign</a>

        <?php
        // Single button for both E-Invoice and E-Way Bill
        if ($containsProduct || $containsService) {
            if ($isGSTINAvailable) {
                if ($containsProduct && !$isEInvoiceGenerated && !$isEWayBillGenerated) {
                    // If there are products and neither E-Invoice nor E-Way Bill is generated, show the button
                    ?>
                    <a href="#" data-toggle="modal" data-target="#gstCredentialsModal" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Generate E-Invoice and E-Way Bill" onclick="generateInvoiceAndEwayBill(<?php echo $inv_id; ?>);">E-Invoice & E-Way Bill</a>
                    <?php
                } elseif ($isEInvoiceGenerated && $isEWayBillGenerated) {
                    // If both E-Invoice and E-Way Bill are already generated, show "Cancel" option if within 48 hours
                    ?>
                    <?php if ($cancelAllowed) { ?>
                        <a href="cancel_invoice.php?inv_id=<?php echo $inv_id; ?>" class="btn border border-grey text-danger" onclick="return confirm('Are you sure you want to cancel the E-Way Bill?');">Cancel E-Way Bill</a>
                    <?php } else { ?>
                        <a href="#" class="btn border border-grey text-muted" disabled>Cancel E-Way Bill (Disabled after 48 hours)</a>
                    <?php } ?>
                    <?php
                }
            } else {
                // If GSTIN is not available, show an alert and disable the E-Invoice and E-Way Bill generation button
                ?>
                  <button class="btn border border-grey text-muted"  data-toggle="tooltip" title="<?php echo $gstinTooltip; ?>" onclick="showGSTINAlert();">
                    <?php echo $gstinMessage; ?>
                </button>
                <?php
            }
        }
    } else if ($quotationDetails['status'] == "pending" || $quotationDetails['status'] == "partial") {
        ?>
        <a href="#" data-toggle="modal" data-target="#receiptsModal" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Open Link">Receipts</a>
        <!--<a href="#" data-toggle="modal" data-target="#tdsModal" class="btn border border-grey" title="Open Link">TDS</a>-->
        <a href="view-esign-action.php?inv_id=<?php echo $inv_id; ?>" class="btn border border-grey" target="_blank" title="Open Link">E-Sign</a>

        <?php
        // Single button for both E-Invoice and E-Way Bill
        if ($containsProduct || $containsService) {
            if ($isGSTINAvailable) {
                if ($containsProduct && !$isEInvoiceGenerated && !$isEWayBillGenerated) {
                    // If there are products and neither E-Invoice nor E-Way Bill is generated, show the button
                    ?>
                    <a href="#" data-toggle="modal" data-target="#gstCredentialsModal" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Generate E-Invoice and E-Way Bill" onclick="generateInvoiceAndEwayBill(<?php echo $inv_id; ?>);">Generate E-Invoice and E-Way Bill</a>
                    <?php
                } elseif ($isEInvoiceGenerated && $isEWayBillGenerated) {
                    // If both E-Invoice and E-Way Bill are already generated, show "Cancel" option if within 48 hours
                    ?>
                    <?php if ($cancelAllowed) { ?>
                        <a href="cancel_invoice.php?inv_id=<?php echo $inv_id; ?>" class="btn border border-grey text-danger" onclick="return confirm('Are you sure you want to cancel the E-Way Bill?');">Cancel E-Way Bill</a>
                    <?php } else { ?>
                        <a href="#" class="btn border border-grey text-muted" disabled>Cancel E-Way Bill (Disabled after 48 hours)</a>
                    <?php } ?>
                    <?php
                }
            } else {
                // If GSTIN is not available, show an alert and disable the E-Invoice and E-Way Bill generation button
                ?>
                <!-- <button class="btn border border-grey text-muted" disabled>GSTIN is required to generate E-Invoice and E-Way Bill</button> -->
               <button class="btn border border-grey text-muted"  data-toggle="tooltip" title="<?php echo $gstinTooltip; ?>" onclick="showGSTINAlert();">
                    <?php echo $gstinMessage; ?>
                </button>
            

                <?php
            }
        }
    }
} else {
    echo "Invalid quotation details.";
}
?>

<script>
 function generateInvoiceAndEwayBill(invId) {
    // Perform an AJAX request to check for missing transportation details
    $.ajax({
        url: 'check_transportation_details.php',  // Create this new file to handle the check
        type: 'POST',
        data: { invoice_id: invId },
        success: function(response) {
            if (response === 'missing') {
                // If transportation details are missing, show a popup
                showTransportationDetailsPopup(invId);
            } else {
                // If everything is fine, proceed with generating the E-Invoice and E-Way Bill
                alert("Generating E-Invoice and E-Way Bill for invoice ID: " + invId);
                // Call the necessary function to generate the invoice here
            }
        },
        error: function(xhr, status, error) {
            // Handle error if AJAX request fails
            console.log(error);
        }
    });
}

function showTransportationDetailsPopup(invId) {
    Swal.fire({
        title: 'Enter Missing Transportation Details',
        html: `
            <form id="transportationForm">
                <label for="vehicle_number">Vehicle Number</label>
                <input type="text" id="vehicle_number" class="swal2-input" placeholder="Enter Vehicle Number" required>
                <label for="distance">Distance (km)</label>
                <input type="number" id="distance" class="swal2-input" placeholder="Enter Distance" required>
                <label for="driver_name">Driver Name</label>
                <input type="text" id="driver_name" class="swal2-input" placeholder="Enter Driver Name" required>
                <label for="license_number">License Number</label>
                <input type="text" id="license_number" class="swal2-input" placeholder="Enter License Number" required>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Save Details',
        preConfirm: () => {
            // Collect form data and send it to the server
            const formData = {
                invoice_id: invId,
                vehicle_number: document.getElementById('vehicle_number').value,
                distance: document.getElementById('distance').value,
                driver_name: document.getElementById('driver_name').value,
                license_number: document.getElementById('license_number').value
            };

            $.ajax({
                url: 'save_transportation_details.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire('Success', 'Transportation details saved successfully.', 'success');
                        // Now trigger the credentials popup after saving the details
                        showCredentialsPopup(invId);
                    } else {
                        Swal.fire('Error', 'There was an error saving the details. Please try again.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
    });
}


    function generateEInvoice(invId) {
        // Logic to generate only E-Invoice
        alert("Generating E-Invoice for invoice ID: " + invId);
        // Perform necessary actions to generate the E-Invoice
    }

     function showGSTINAlert() {
        Swal.fire({
            title: 'GSTIN Required',
            text: 'The GSTIN is required to generate both E-Invoice and E-Way Bill. Please ensure the customer has a GSTIN number.Please update the customer Details ',
            icon: 'warning',
            confirmButtonText: 'Close'
        });
    }
</script>

<!-- 
<div class="btn-group">
          <a href="#" data-toggle="modal" data-target="#tdsModal"  class="btn border border-grey d-none"  data-placement="top" title="Open Link">TDS</a>
      </div> -->
<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-share-alt text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
        <a class="dropdown-item" href="#" id="shareWhatsApp" data-mobile="<?php echo htmlspecialchars($quotationDetails['customerPhone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">Share Via WhatsApp</a>
    <a class="dropdown-item" href="#" id="shareEmail">Share via Email</a>
    <!-- <a href="<?php echo $quotationDetails['invoice_file']; ?>" id="pdfLink" style="display: none;">Download PDF</a> -->

    </div>
</div>

<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>

    <?php
    if (is_array($quotationDetails) && isset($quotationDetails['status'])) {
        $invoiceStatus = $quotationDetails['status'];
        $invId = htmlspecialchars($inv_id, ENT_QUOTES, 'UTF-8');
        $invCode = $quotationDetails['invoice_code'];
        // ✅ Check if any payment exists for this invoice
        $query1 = "SELECT COUNT(*) as receipt_count FROM receipts WHERE invoice_id = '$inv_id' AND invoice_code ='$invCode' ";
        $result1 = $conn->query($query1);
        $receiptData = $result1->fetch_assoc();
        $receiptCount = $receiptData['receipt_count'];

        // ✅ Check if a Credit Note exists for this invoice
        $query2 = "SELECT COUNT(*) as credit_note_count FROM credit_note WHERE invoice_id = '$inv_id'";
        $result2 = $conn->query($query2);
        $creditNoteData = $result2->fetch_assoc();
        $creditNoteCount = $creditNoteData['credit_note_count'];

        // ✅ Check if E-Invoice and E-Way Bill have been generated
        $query3 = "SELECT irn_no, e_way_bill_no FROM invoice WHERE id = '$inv_id'";
        $result3 = $conn->query($query3);
        $invoiceData = $result3->fetch_assoc();
        $isEInvoiceGenerated = !empty($invoiceData['irn_no']);
        $isEWayBillGenerated = !empty($invoiceData['e_way_bill_no']);

        // Disable Edit and Delete if E-Invoice and/or E-Way Bill are generated
        $disableEdit = ($invoiceStatus == 'paid' || $invoiceStatus == 'partial' || $receiptCount > 0 || $isEInvoiceGenerated || $isEWayBillGenerated);
        $disableDelete = ($invoiceStatus == 'paid' || $invoiceStatus == 'partial' || $receiptCount > 0 || $creditNoteCount > 0 || $isEInvoiceGenerated || $isEWayBillGenerated);

        echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">';

        if (!$disableEdit) {
            echo '<a class="dropdown-item" href="edit_invoice.php?inv_id=' . $invId . '">Edit Invoice</a>';
        } else {
            echo '<a class="dropdown-item text-muted disabled" href="#">Edit Disabled</a>';
        }

        if (!$disableDelete) {
            echo '<a class="dropdown-item text-danger" href="delete_invoice.php?inv_id=' . $invId . '" onclick="return confirm(\'Are you sure you want to delete this invoice?\');">Delete</a>';
        } else {
            echo '<a class="dropdown-item text-muted disabled" href="#">Delete Disabled</a>';
        }

        echo '</div>';
    }
    ?>

    <!-- Print Button -->
    <a href="#" class="btn border border-grey" id="print-invoice" data-toggle="tooltip" data-placement="top" title="Print">
        <i class="fa fa-print text-grey" aria-hidden="true"></i>
    </a>

    <!-- Download Button -->
    <a href="#" id="download-invoice" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download">
        <i class="fa fa-download text-grey" aria-hidden="true"></i>
    </a>

</div>


                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- <iframe id="pdfViewer" src="<?php echo $quotationDetails['invoice_file']; ?>" width="100%" height="600px"></iframe> -->



                <?php
function numberToWords($number) {
    $words = [
        'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
        'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    ];

    $tens = [
        '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
    ];

    if ($number < 20) {
        return $words[$number];
    } elseif ($number < 100) {
        return $tens[(int)($number / 10)] . (($number % 10 != 0) ? ' ' . $words[$number % 10] : '');
    } elseif ($number < 1000) {
        return $words[(int)($number / 100)] . ' hundred' . (($number % 100 != 0) ? ' and ' . numberToWords($number % 100) : '');
    } elseif ($number < 1000000) {
        return numberToWords((int)($number / 1000)) . ' thousand' . (($number % 1000 != 0) ? ' and ' . numberToWords($number % 1000) : '');
    } elseif ($number < 1000000000) {
        return numberToWords((int)($number / 1000000)) . ' million' . (($number % 1000000 != 0) ? ' and ' . numberToWords($number % 1000000) : '');
    } elseif ($number < 1000000000000) {
        return numberToWords((int)($number / 1000000000)) . ' billion' . (($number % 1000000000 != 0) ? ' and ' . numberToWords($number % 1000000000) : '');
    } elseif ($number < 1000000000000000) {
        return numberToWords((int)($number / 1000000000000)) . ' trillion' . (($number % 1000000000000 != 0) ? ' and ' . numberToWords($number % 1000000000000) : '');
    } elseif ($number < 1000000000000000000) {
        return numberToWords((int)($number / 1000000000000000)) . ' quadrillion' . (($number % 1000000000000 != 0) ? ' and ' . numberToWords($number % 1000000000000000) : '');
    } else {
        return 'Number is out of range for this example.';
    }
}

function numberToWordsFloat($number) {
    $whole = floor($number);
    $fraction = round(($number - $whole) * 100); // get the decimal part as whole number

    $words = numberToWords($whole);
    if ($fraction > 0) {
        $words .= ' and ' . numberToWords($fraction) . ' cents';
    }

    return $words;
}
?>

           
<div class="row">
    <div class="col-md-8">
        
        <div id="pdf-container" style="position: relative;">
             <?php 
                        if($quotationDetails['status'] == "pending")
                        {
                            ?>
            <div id="status-overlay" class="status-label"  style="position: absolute; top: 5%; right: 5%; 
                       z-index: 10; border:2px solid red; 
                       color:red; font-weight:bold; 
                       background:white; padding: 5px 10px; 
                       display: block; font-size: 14px;">Not Paid</div>
                            <!-- <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid red;color:red;font-weight:bold;">Not Paid</span> -->
                            <?php
                        }else if($quotationDetails['status'] == "partial")
                        {
                            ?>
        <div id="status-overlay"  class="status-label pb-1 pt-1 pl-3 pr-3"   style="position: absolute;top:45px;float:right;right:3px;z-index: 10; border:2px solid #3498db;color:#3498db;font-weight:bold; display: none;">Part Payment</div>
                            <!-- <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid #3498db;color:#3498db;font-weight:bold;">Part Payment</span> -->
                            <?php
                        }else if($quotationDetails['status'] == "paid")
                        {
                            ?>
                <div id="status-overlay" class="status-label pb-1 pt-1 pl-3 pr-3"  style="position: absolute; top:45px;float:right;right:3px; z-index: 10;border:2px solid green;color:green;font-weight:bold; display: none;">Fully Paid</div>
                            <!-- <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid green;color:green;font-weight:bold;">Fully Paid</span> -->
                            <?php
                        }
                        ?>
        
        <canvas id="pdf-canvas" style="width:100%; max-width: 700px;"></canvas>
        <div id="controls">
            <button id="prev-page" class="btn">Previous</button>
            <span>Page: <span id="current-page">1</span> / <span id="total-pages">0</span></span>
            <button id="next-page" class="btn">Next</button>
        </div>
    </div>
    </div>
   <?php
    $query1 = "SELECT * FROM receipts WHERE invoice_id = '$inv_id'";
$result1 = $conn->query($query1);
// Fetch credit note adjustments for this invoice
    $query2 = "SELECT SUM(total_amount) AS total_credit_adjusted, cnote_date FROM credit_note 
               WHERE invoice_id = '$inv_id' AND is_deleted = 0";
    $result2 = $conn->query($query2);
    $creditNote = $result2->fetch_assoc();
    $credit_adjusted = floatval($creditNote['total_credit_adjusted'] ?? 0); // Default to 0 if no credit note

if ($result1->num_rows > 0) {
    
    $paymentData = $result1->fetch_all(MYSQLI_ASSOC);
    ?>
    <div class="col-lg-4 col-md-4 col-sm-4 mt-2">
        <div class="card">
            <div class="card">
                <div class="" style="border-bottom:1px dashed gray"><h6 class="p-3">Payment Information</h6></div>
                <div class=" row pl-1 pr-1 ml-0 mr-0" style="border-bottom:1px dashed gray">
                    <div class="col-md-7 p-3">
                        <span>Invoiced</span><br/>
                         <span><?php echo "(On " . date('d-m-Y', strtotime($quotationDetails['invoice_date'])) . ")"?></span> 
                    </div>
                    <div class="col-md-5 p-3">
                        <span style="color:blue"> INR <?= number_format($paymentData[0]['total_amount'], 2) ?>
                            </span>
                    </div>
                </div>

                <?php $tpa =0;
                foreach ($paymentData as $payment): ?>
                    <div class=" row pl-1 pr-1 ml-0 mr-0" style="border-bottom:1px dashed gray">
                        <div class="col-md-7 p-3">
                            <span>Paid</span><br/>
                            <span><?php echo "(On " . date('d-m-Y', strtotime($payment['receipt_date'])) . ")"?></span>
                        </div>
                        <div class="col-md-5 p-3">
                            <span style="color:blue"> INR <?= number_format($payment['paid_amount'], 2) ?>
                            </span>
                        </div>
                    </div>
                   <?php $tpa += $payment['paid_amount']; ?>
                <?php endforeach; ?>
                                <div class="row pl-3 pr-3">
                        <?php if ($credit_adjusted > 0): // Show only if credit note has value ?>
                            <div class="col-md-7 p-3">
                                <span>Credit Note Adjusted</span><br/>
                                <span><?php echo "(On " . date('d-m-Y', strtotime($creditNote['cnote_date'])) . ")" ?></span>
                            </div>
                    <div class="col-md-5 p-3">
                        <span style="color:blue"> INR <?= number_format($credit_adjusted, 2) ?></span>
                    </div>
                <?php endif; ?>
</div>
                <div class="row pl-3 pr-3">
                    <div class="col-md-7 p-3">
                         <?php
    $lastPaymentDate = $quotationDetails['invoice_date'];
    $dueDate = date('Y-m-d', strtotime($quotationDetails['due_date'] . ' +1 day')); // Consider entire day on due date
    $remainingDays = max(0, strtotime($dueDate) - time()) / (60 * 60 * 24);
    $remainingDays = round($remainingDays);
    ?>
                        <span>Balanced</span><br/>
                        <span>( Overdue <?php echo $remainingDays?> days)</span>
                      
                    </div>
                    <div class="col-md-5 p-3">
    <?php 
    // Ensure total_amount and tpa are treated as floats
    $total_amount = floatval($paymentData[0]['total_amount']);
    $tpa = floatval($tpa);

    // Calculate the balance amount
    $ba = $total_amount - ($tpa + $credit_adjusted);
    ?>
    <span style="color:red"> INR <?= number_format($ba, 2) ?></span>
</div>

                </div>

            </div>
        </div>
    </div>
    <?php
} else {
    // Handle the case where no rows are found
  //  echo "No payment data found for the given invoice ID.";
}
?> 


</div>


</div>
<script>
  function printPDF(pdfFilePath) {
        var printWindow = window.open(pdfFilePath, '_blank');
        printWindow.onload = function() {
            printWindow.print();
        };
    }
</script>

<script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
       <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
    
   document.addEventListener("DOMContentLoaded", () => {
    let invoiceFileUrl = ""; // Global variable to store the fetched PDF path

    const statusOverlay = document.getElementById("status-overlay");

    // Set the status label visibility
    if (statusOverlay) {
        statusOverlay.style.display = "block"; // Ensure the label is visible
    }

    // Fetch the `invoice_id` dynamically from the URL query parameters
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const invoiceId = getQueryParam("inv_id"); // Extract invoice_id from URL


    // Fetch the dynamic PDF path from the backend
    fetch(`fetch_invoice_path.php?invoice_id=${invoiceId}`)
        .then((response) => response.json())
        .then((data) => {
              console.log(data);
            if (data.status === "success") {
              
                const url = data.file_url; // Fetch the file URL from the response
                invoiceFileUrl = data.file_url;
  // Update the Download Button
                const downloadBtn = document.getElementById("download-invoice");
                if (downloadBtn) {
                    downloadBtn.href = invoiceFileUrl;
                } else {
                    console.error("Download button not found in the DOM.");
                }

                // Update the Print Button
                const printBtn = document.getElementById("print-invoice");
                if (printBtn) {
                    printBtn.addEventListener("click", (e) => {
                        e.preventDefault();
                        printInvoice(invoiceFileUrl);
                    });
                } else {
                    console.error("Print button not found in the DOM.");
                }



                const pdfjsLib = window["pdfjs-dist/build/pdf"];
                pdfjsLib.GlobalWorkerOptions.workerSrc =
                    "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";

                let pdfDoc = null,
                    pageNum = 1,
                    pageRendering = false,
                    pageNumPending = null;

                const scale = 1.5,
                    canvas = document.getElementById("pdf-canvas"),
                    ctx = canvas.getContext("2d");

                // Render the page
                const renderPage = (num) => {

                    pageRendering = true;
                    pdfDoc.getPage(num).then((page) => {
                        const viewport = page.getViewport({ scale });
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: ctx,
                            viewport: viewport,
                        };
                        const renderTask = page.render(renderContext);

                        renderTask.promise.then(() => {
                            pageRendering = false;

                            if (pageNumPending !== null) {
                                renderPage(pageNumPending);
                                pageNumPending = null;
                            }
                        });
                    });

                    document.getElementById("current-page").textContent = num;
                };

                const queueRenderPage = (num) => {
                    if (pageRendering) {
                        pageNumPending = num;
                    } else {
                        renderPage(num);
                    }
                };

                const onPrevPage = () => {
                    if (pageNum <= 1) {
                        return;
                    }
                    pageNum--;
                    queueRenderPage(pageNum);
                };

                const onNextPage = () => {
                    if (pageNum >= pdfDoc.numPages) {
                        return;
                    }
                    pageNum++;
                    queueRenderPage(pageNum);
                };

                document
                    .getElementById("prev-page")
                    .addEventListener("click", onPrevPage);
                document
                    .getElementById("next-page")
                    .addEventListener("click", onNextPage);

                // Load the PDF dynamically
                pdfjsLib.getDocument(url).promise.then((pdfDoc_) => {
                    pdfDoc = pdfDoc_;
                    document.getElementById("total-pages").textContent =
                        pdfDoc.numPages;
                    renderPage(pageNum);
                });
            } else {
                alert("Failed to fetch invoice file: " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error fetching invoice file:", error);
            alert("Unable to fetch PDF file.");
        });
         // Function to Print the Invoice
// Function to Print the Invoice
    function printInvoice(url) {
        if (!url) {
            alert("Invoice file not available.");
            return;
        }
        const printWindow = window.open(url, "_blank");
        printWindow.onload = function () {
            printWindow.print();
        };
    }
});


</script>
    <script>
      
    </script>

<!-- <script>
  // Function to show/hide tabs based on the selected option
  $(document).ready(function() {
    $('#payment_mode').on('change', function() {
      var selectedOption = $(this).val();

      // Hide all tabs
      $('.tab-content').hide();

      // Show the selected tab
      $('#' + selectedOption + 'Tab').show();
    });
  });
</script> -->

<script>
// $(document).ready(function() {
//     $('#payment_mode').change(function() {
//         var selectedOption = $(this).val();
        
//         // Hide all related fields
//         $('#collected_by_tab, #bank_name_tab, #trans_no_tab, #cheque_no_tab,#dd_no_tab,#credit_debit_card_tab').hide();
        
//         // Show the relevant field based on the selected option
//         if (selectedOption === 'cash') {
//             $('#collected_by_tab,#transaction_date_tab').show();
//         } else if (selectedOption === 'cheque') {
//             $('#bank_name_tab, #cheque_no_tab,#transaction_date_tab').show();
//         } else if (selectedOption === 'direct_deposit') {
//              $('#bank_name_tab,#transaction_date_tab').show();
//         }else if (selectedOption === 'demand_draft') {
//              $('#bank_name_tab,#dd_no_tab,#transaction_date_tab').show();
//         }else if (selectedOption === 'credit_debit_card') {
//              $('#credit_debit_card_tab,#transaction_date_tab').show();
//         }else if (selectedOption === 'online_payment') {
//              $('#trans_no_tab,#transaction_date_tab').show();
//         }else if (selectedOption === 'neft_rtgs') {
//              $('#bank_name_tab,#transaction_date_tab').show();
//         }

//         // Add more conditions as needed for other options
//     });
// });
</script>
<script>
$(document).ready(function() {
    // Function to show the relevant tab based on the selected option
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

    // Initial setup to show the default tab
    var selectedOption = $('#payment_mode').val();
    showTab(selectedOption);

    // Change event handler for the dropdown
    $('#payment_mode').change(function() {
        var selectedOption = $(this).val();
        showTab(selectedOption);
    });
});
</script>


<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Modal for Composing Email -->
<!-- Email Modal -->
<div id="shareModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
    <div class="modal-content" style="background: white; padding: 20px; border-radius: 8px; width: 400px; max-width: 90%;">
        <span id="closeModal" class="close" style="float: right; cursor: pointer; font-size: 20px;">&times;</span>
        <h3>Compose Email</h3>
        <form id="emailForm" action="send_invoice_email.php" method="POST" enctype="multipart/form-data">
            <!-- HTML Form with PHP for displaying values -->
<!-- <input type="hidden" name="invoice_file" id="invoice_file" value="<?php echo htmlspecialchars($GLOBALS['invoiceFile'], ENT_QUOTES, 'UTF-8'); ?>" /> -->
<label for="customer_email">To:</label>
<input type="email" class="form-control" id="customer_email1" name="customer_email1" value="<?php echo htmlspecialchars($GLOBALS['customerEmail'], ENT_QUOTES, 'UTF-8'); ?>" required />
            <label for="subject">Subject:</label>
            <input type="text" class="form-control" id="subject" name="subject" value="Invoice #<?php echo htmlspecialchars($inv_id, ENT_QUOTES, 'UTF-8'); ?>" required />
            <label for="message">Message:</label>
            <textarea class="form-control" id="message" name="message" rows="4" required>Dear Customer, please find attached the invoice #<?php echo htmlspecialchars($inv_id, ENT_QUOTES, 'UTF-8'); ?>.</textarea>
            <button type="submit" class="btn btn-primary" name="sendMail" id="sendMail">Send Mail</button>
        </form>
    </div>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const shareModal = document.getElementById("shareModal");
        const closeModal = document.getElementById("closeModal");

        // Open email modal
        document.getElementById("shareEmail").addEventListener("click", function (event) {
            event.preventDefault();
            shareModal.style.display = "flex";
        });

        // Close email modal
        closeModal.onclick = function () {
            shareModal.style.display = "none";
        };

        // Close modal on outside click
        window.onclick = function (event) {
            if (event.target == shareModal) {
                shareModal.style.display = "none";
            }
        };

        // WhatsApp Modal functionality
        const whatsAppModal = document.getElementById("whatsAppModal");
        const closeWhatsAppModal = document.getElementById("closeWhatsAppModal");
        const mobileInput = document.getElementById("mobile");

        // Open WhatsApp modal
        document.getElementById("shareWhatsApp").addEventListener("click", function (event) {
            event.preventDefault();
            const phoneNumber = this.getAttribute("data-mobile");
            if (phoneNumber) {
                mobileInput.value = phoneNumber;
            }
            whatsAppModal.style.display = "flex";
        });

        // Close WhatsApp modal
        closeWhatsAppModal.onclick = function () {
            whatsAppModal.style.display = "none";
        };

        // Close modal on outside click
        window.onclick = function (event) {
            if (event.target == whatsAppModal) {
                whatsAppModal.style.display = "none";
            }
        };
    });
</script>



<script type="text/javascript">
    
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

<script>
$(document).ready(function(){
    // Function to calculate and update the amount
    function calculateAmount() {
        // Get the selected TDS rate
        var selectedRate = $("#tds_section1 option:selected").data("tds_rate");
        
        // Assuming grand total is stored in a variable named grandTotal, replace it with your actual variable
        var grandTotal = <?php echo $tot_amt?>; // Example value, replace it with your actual variable
        
        // Calculate the amount based on the selected TDS rate
        var tdsDeductible = (selectedRate / 100) * grandTotal;
        
        // Update the TDS Deductible input field
        $("#tds_deductable").val(tdsDeductible.toFixed(2));
    }

    // Attach the calculateAmount function to the change event of the TDS dropdown
    $("#tds_section1").change(function() {
        calculateAmount();
    });

    // Trigger the calculation on page load
    calculateAmount();
});
</script>
<script>
// $(document).ready(function() {
//     $('#addreceiptForm').submit(function(e) {
//         e.preventDefault();
//         $.ajax({
//             type: 'POST',
//             url: 'receiptdb.php',
//             data: $(this).serialize(),
//             success: function(response) {
//                 alert(response);
//                 alert("Receipt generated successfully");
//                 // Handle success, e.g., show a success message, redirect, etc.
//             },
//             error: function(error) {
//                 alert('Error: ' + error.responseText);
//             }
//         });
//     });
// });
</script>
<!-- JavaScript to Handle Modal Behavior -->





</body>
</html>