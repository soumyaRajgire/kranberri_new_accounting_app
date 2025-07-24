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
?>  
 


<html lang="en">
<head>
    <title>iiiQbets</title>

    <meta charset="utf-8">
    <?php include("header_link.php");?>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"  crossorigin="anonymous">

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
    // Sanitize input and fetch branch ID from session
    $quotationId = $conn->real_escape_string($inv_id); 
    $bid = isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : null;

    // Validate that branch ID exists
    if (!$bid) {
        return false; // Branch ID is missing in the session
    }

    // Query to fetch data from the 'credit_note' and related tables
    $query = "SELECT q.*, c.*, a.*, qi.*
              FROM credit_note q
              JOIN customer_master c ON q.customer_id = c.id
              JOIN address_master a ON c.id = a.customer_master_id
              JOIN credit_note_items qi ON q.id = qi.cnote_id
              WHERE q.id = '$quotationId' AND q.branch_id = '$bid'";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        // Fetch the first row as main data
        $quotationData = $result->fetch_assoc();
        $quotationItems = [];

        // Collect all items
        foreach ($result as $row) {
            $quotationItems[] = [
                'product' => $row['product'],
                'prod_desc' => $row['prod_desc'],
                'price' => $row['price'],
                'qty' => $row['qty'],
                'line_total' => $row['line_total'],
                'total' => $row['total'],
                'gst_amt' => $row['gst_amt'],
                'gst' => $row['gst'],
                'cess_rate' => $row['cess_rate'],
                'cess_amt' => $row['cess_amt'],
                'discount' => $row['discount'],
                'cgst' => $row['cgst'],
                'sgst' => $row['sgst'],
                'igst' => $row['igst'],
            ];
        }

        // Add quotation items array to the main data
        $quotationData['quotation_items'] = $quotationItems;

        return $quotationData;
    } else {
        return false; // Quotation not found
    }
}

// Usage
$inv_id = isset($_GET['inv_id']) ? $_GET['inv_id'] : null;
if (!$inv_id) {
    die("Invoice ID is missing!");
}

$quotationDetails = getQuotationDetails($conn, $inv_id);

// Handle missing data
if (!$quotationDetails) {
    echo "<p class='text-danger'>No data found for the provided Credit Note ID.</p>";
    exit;
}
?>



           <?php 

        // DELETE PERMENant 
if (isset($_GET['action']) && $_GET['action'] === 'delete_permanent' && isset($_GET['inv_id'])) {
    $inv_id = $conn->real_escape_string($_GET['inv_id']);
    
    // Begin transaction
    $conn->begin_transaction();

    try {
         $query = "SELECT invoice_id, product_id, qty, total_amount 
              FROM credit_note_items 
              WHERE cnote_id = '$inv_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Restore inventory
            $updateInventory = "UPDATE inventory 
                                SET stock = stock + {$row['qty']} 
                                WHERE product_id = {$row['product_id']}";
            $conn->query($updateInventory);
        }
    }
        // Delete related items
        $deleteItemsQuery = "DELETE FROM credit_note_items WHERE cnote_id = '$inv_id'";
        $conn->query($deleteItemsQuery);

        // Delete the debit note
        $deleteNoteQuery = "DELETE FROM credit_note WHERE id = '$inv_id'";
        $conn->query($deleteNoteQuery);

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Credit note permanently deleted.'); window.location.href='credit_note_list.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error permanently deleting credit note.'); window.location.href='credit_note_list.php';</script>";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['inv_id'])) {
    $inv_id = $conn->real_escape_string($_GET['inv_id']);
       // Fetch the debit note details
    $query = "SELECT invoice_id, product_id, qty, total_amount 
              FROM credit_note_items 
              WHERE cnote_id = '$inv_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Restore inventory
            $updateInventory = "UPDATE inventory 
                                SET stock = stock + {$row['qty']} 
                                WHERE product_id = {$row['product_id']}";
            $conn->query($updateInventory);
        }
    }

    $query = "UPDATE credit_note SET is_deleted = 1 WHERE id = '$inv_id'";
    if ($conn->query($query)) {

        echo "<script>alert('Credit note deleted successfully.'); window.location.href='credit_note_list.php';</script>";
    } else {
        echo "<script>alert('Error deleting Credit note.'); window.location.href='credit_note_list.php';</script>";
    }
}


        ?>
     <?php include("menu.php");?>
    
    <?php include("createReceiptModal.php");?>

     <?php include("createTDSModal.php");?>
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
                            <h4 class="m-b-10">Credit Note</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <!-- <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li> -->
                            <!-- <li class="breadcrumb-item"><a href="#">View credit note</a></li> -->
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
        <div class="col-md-6 col-lg-6">
            <ul class="nav">
                <li class="nav-item mt-2">
                    <h5><a href="#" class="customer_est_name text-primary"  style="font-size: 19px;">Customer: <?php echo $quotationDetails['customerName'];?></a></h5>
                </li>
            </ul>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center">
            <ul class="nav">
                <li class="nav-item">
                    <div class="btn-group" role="group">
    
<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
    <?php
// Sanitize and escape the `inv_id` variable to ensure it is safe for output
$escaped_inv_id = htmlspecialchars($inv_id, ENT_QUOTES, 'UTF-8');
?>
<a class="dropdown-item" href="edit_credit_note.php?inv_id=<?php echo $escaped_inv_id; ?>">Edit Credit Note</a>

        <!-- <a class="dropdown-item" href="delete_invoice.php?inv_id=<?php echo $inv_id; ?>" onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a> -->
         <a class="dropdown-item" href="view-credit-action.php?action=delete&inv_id=<?php echo $inv_id; ?>" 
       onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>

    <!-- Permanent Delete -->
    <a class="dropdown-item text-danger" href="view-credit-action.php?action=delete_permanent&inv_id=<?php echo $inv_id; ?>" 
       onclick="return confirm('Are you sure you want to permanently delete this invoice? This action cannot be undone.');">
       Delete Permanently</a>
     
    </div>

    <a href="" class="btn border border-grey" onclick="printPDF('<?php echo $quotationDetails['cnote_file']?>')" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print text-grey" aria-hidden="true"></i> </a>
    <a href="<?php echo $quotationDetails['cnote_file']?>" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download"><i class="fa fa-download text-grey" aria-hidden="true"></i></a>
          
</div>


                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- <iframe id="pdfViewer" src="<?php echo $quotationDetails['cnote_file']; ?>" width="100%" height="600px"></iframe> -->



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
            
        
        <canvas id="pdf-canvas" style="width:700px"></canvas>
        <div id="controls">
            <button id="prev-page" class="btn">Previous</button>
            <span>Page: <span id="current-page">1</span> / <span id="total-pages">0</span></span>
            <button id="next-page" class="btn">Next</button>
        </div>
    </div>
    </div>
    <?php
function getRelatedPurchaseInvoices($conn, $creditNoteId) {
    $creditNoteId = $conn->real_escape_string($creditNoteId); // Sanitize input

    // Query to fetch related purchase invoices
    $query = "SELECT pi.id, pi.invoice_code, pi.grand_total, pi.invoice_date
              FROM invoice pi
              JOIN credit_note cn ON pi.id = cn.invoice_id
              WHERE cn.id = '$creditNoteId'";

    $result = $conn->query($query);

    $purchaseInvoices = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $purchaseInvoices[] = $row;
        }
    }

    return $purchaseInvoices;
}

$relatedInvoices = getRelatedPurchaseInvoices($conn, $inv_id);

    ?>

    <div class="col-lg-4 col-md-4 col-sm-4 mt-2">
        <div class="card">
            <div class="card">
                <div class="" style="border-bottom:1px dashed gray"><h6 class="p-3">Related Purchase Invoice</h6></div>

                    <div class="card-body">
            <?php if (!empty($relatedInvoices)): ?>
                <ul class="list-group">
                    <?php foreach ($relatedInvoices as $invoice): ?>
                        <li class="list-group-item">
                            <a href="view-invoice-action.php?inv_id=<?php echo $invoice['id']; ?>" ><?php echo $invoice['invoice_code']; ?></a>
                            <!-- <strong>Vendor: </strong><?php echo $invoice['vendor_name']; ?><br> -->
                            <!-- <strong>Date: </strong><?php echo $invoice['invoice_date']; ?><br> -->
                            <a href="" style="color:#00acc1;float:right;">INR. <?php echo number_format($invoice['grand_total'], 2); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">No related purchase invoices found.</p>
            <?php endif; ?>
        </div>
                
            </div>
        </div>
    </div>
   
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

    // Construct the dynamic URL for the PDF file from PHP variable
    const url = "<?php echo $quotationDetails['cnote_file']; ?>"; // Make sure it is echoed as a string

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
        document.getElementById("total-pages").textContent = pdfDoc.numPages;
        renderPage(pageNum);
    }).catch((error) => {
        console.error("Error loading PDF:", error);
        alert("Unable to load PDF file.");
    });
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
<script>
$(document).ready(function() {
    // WhatsApp sharing
    $('#shareWhatsApp').on('click', function() {
        var pdfLink = $('#pdfLink').attr('href');
        var message = 'Check out this PDF: ' + pdfLink;
        var whatsappUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(message);

        window.open(whatsappUrl, '_blank');
    });

    // Email sharing
    $('#shareEmail').on('click', function() {
        var pdfLink = $('#pdfLink').attr('href');
        var emailUrl = 'path/to/your/email-script.php?pdf=' + encodeURIComponent(pdfLink);

        window.location.href = emailUrl;
    });
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
</body>
</html>