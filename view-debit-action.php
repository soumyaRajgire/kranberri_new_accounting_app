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
        function getdebitNoteDetails($conn, $inv_id) {
    $quotationId = $conn->real_escape_string($inv_id); // Sanitize input
$bid  = $_SESSION['branch_id'];
    // Your database query logic here to fetch data from the 'quotation' table
    $query = "SELECT q.*, c.*, a.*, qi.*
              FROM debit_note q
              JOIN customer_master c ON q.customer_id = c.id
              JOIN address_master a ON c.id = a.customer_master_id
              JOIN  debit_note_items qi ON q.id = qi.dnote_id
              WHERE q.id = '$quotationId' AND q.branch_id = '$bid'";
    
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $quotationData = $result->fetch_assoc();
        $quotationItems = [];
        foreach ($result as $row) {
            $quotationItems[] = [
              
                'product' => $row['product'],
                'prod_desc' => $row['prod_desc'],
                'price' => $row['price'],
                'qty' => $row['qty'],
                'line_total' => $row['line_total'],
                'total' => $row['total'],
                'gst_amt'=>$row['gst_amt'],
                'gst' => $row['gst'],
                'cess_rate' => $row['cess_rate'],
                 'cess_amt' => $row['cess_amt'],
                 'discount' => $row['discount'],
                  'cgst' => $row['cgst'],
                   'sgst' => $row['sgst'],
                    'igst' => $row['igst'],

            ];
        }

        // Add quotation items array to the main quotation data
        $quotationData['quotation_items'] = $quotationItems;

        return $quotationData;
    } else {
        return false; // Quotation not found
    }
}

$inv_id = $_GET['inv_id'];
$debitNoteDetails = getdebitNoteDetails($conn, $inv_id);

// Close the database connection
// $conn->close();

        ?>   


<?php 

// DELETE PERMENant 
// DELETE PERMANENT CREDIT NOTE
// DELETE PERMANENTLY
if (isset($_GET['action']) && $_GET['action'] === 'delete_permanent' && isset($_GET['inv_id'])) {
    $inv_id = $conn->real_escape_string($_GET['inv_id']);

    // Begin Transaction
    $conn->begin_transaction();

    try {
        // Fetch Debit Note Items
        $query = "SELECT productid, qty FROM debit_note_items WHERE dnote_id = '$inv_id'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $product_id = $row['productid'];
                $quantity = $row['qty'];

                // **Reverse Inventory Update (Decrease stock_out & Recalculate balance_stock)**
                $updateInventory = "UPDATE inventory_master 
                                    SET stock_out = GREATEST(stock_out - $quantity, 0), 
                                        balance_stock = (opening_stock + stock_in) - GREATEST(stock_out - $quantity, 0)
                                    WHERE id = '$product_id'";

                if (!$conn->query($updateInventory)) {
                    throw new Exception("Error updating inventory: " . $conn->error);
                }
            }
        }

        // Remove Ledger Entry
        $deleteLedgerQuery = "DELETE FROM ledger WHERE voucher_id = '$inv_id' AND transaction_type = 'Debit Note'";
        if (!$conn->query($deleteLedgerQuery)) {
            throw new Exception("Error deleting from ledger: " . $conn->error);
        }

        // Fetch PDF File Path Before Deleting
        $query = "SELECT dnote_file FROM debit_note WHERE id = '$inv_id'";
        $result = $conn->query($query);
        $pdfFilePath = ($result && $row = $result->fetch_assoc()) ? $row['dnote_file'] : "";

        // Delete Related Items
        $deleteItemsQuery = "DELETE FROM debit_note_items WHERE dnote_id = '$inv_id'";
        if (!$conn->query($deleteItemsQuery)) {
            throw new Exception("Error deleting debit note items: " . $conn->error);
        }

        // Delete the Debit Note
        $deleteNoteQuery = "DELETE FROM debit_note WHERE id = '$inv_id'";
        if (!$conn->query($deleteNoteQuery)) {
            throw new Exception("Error deleting debit note: " . $conn->error);
        }

        // Delete the PDF File if it Exists
        if (!empty($pdfFilePath) && file_exists($pdfFilePath)) {
            unlink($pdfFilePath);
        }

        // Commit Transaction
        $conn->commit();

        echo "<script>alert('Debit Note permanently deleted.'); window.location.href='manage-debitnote.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Delete Permanent Error: " . $e->getMessage());
        echo "<script>alert('Error deleting Debit Note: " . $e->getMessage() . "'); window.location.href='manage-debitnote.php';</script>";
    }
}


// SOFT DELETE CREDIT NOTE
// SOFT DELETE
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['inv_id'])) {
    $inv_id = $conn->real_escape_string($_GET['inv_id']);

    // Begin Transaction
    $conn->begin_transaction();

    try {
        // Fetch Debit Note Items
        $query = "SELECT productid, qty FROM debit_note_items WHERE dnote_id = '$inv_id'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $product_id = $row['productid'];
                $quantity = $row['qty'];

                // **Reverse Inventory Update (Decrease stock_out & Recalculate balance_stock)**
                $updateInventory = "UPDATE inventory_master 
                                    SET stock_out = GREATEST(stock_out - $quantity, 0), 
                                        balance_stock = (opening_stock + stock_in) - GREATEST(stock_out - $quantity, 0)
                                    WHERE id = '$product_id'";

                if (!$conn->query($updateInventory)) {
                    throw new Exception("Error updating inventory: " . $conn->error);
                }
            }
        }

        // Soft Delete the Debit Note
        $query = "UPDATE debit_note SET is_deleted = 1 WHERE id = '$inv_id'";
        if (!$conn->query($query)) {
            throw new Exception("Error marking debit note as deleted: " . $conn->error);
        }

        // Commit Transaction
        $conn->commit();

        echo "<script>alert('Debit Note deleted successfully.'); window.location.href='manage-debitnote.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Soft Delete Error: " . $e->getMessage());
        echo "<script>alert('Error deleting Debit Note: " . $e->getMessage() . "'); window.location.href='manage-debitnote.php';</script>";
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
                            <h4 class="m-b-10">Debit Note</h4>
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
                    <h5><a href="#" class="customer_est_name text-primary"  style="font-size: 19px;">Supplier: <?php echo $debitNoteDetails['customerName'];?></a></h5>
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
<a class="dropdown-item" href="edit_debit_note.php?inv_id=<?php echo $escaped_inv_id; ?>">Edit Debit Note</a>

     <?php
// Fetch created_at as UNIX timestamp
$query = "SELECT UNIX_TIMESTAMP(created_at) as created_date FROM debit_note WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $inv_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$created_timestamp = $row['created_date']; // Ensure this is a UNIX timestamp
$stmt->close();

// Get current timestamp
$current_timestamp = time(); // This gives the current UNIX timestamp

// Calculate difference in days
$diff_in_days = ($current_timestamp - $created_timestamp) / (60 * 60 * 24);

// Allow deletion only if it's within 2 days
$allow_delete = ($diff_in_days <= 2);

// echo "Created Date: $created_timestamp <br>";
// echo "Current Date: $current_timestamp <br>";
// echo "Allowed: " . ($allow_delete ? "Yes" : "No") . "<br>";

// if ($allow_delete) {
//     echo '<a class="dropdown-item text-danger" href="view-credit-action.php?action=delete_permanent&inv_id=' . $inv_id . '" onclick="return confirm(\'Are you sure you want to permanently delete this invoice? This action cannot be undone.\');">
//        Delete Permanently</a>';
// } else {
//     echo '<p class="text-muted">Delete option disabled after 2 days</p>';
// }
?>

      <?php if ($allow_delete): ?>
            <!-- Delete Option (Enabled within 2 Days) -->
            <a class="dropdown-item" href="view-debit-action.php?action=delete&inv_id=<?php echo $inv_id; ?>" 
            onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>

            <!-- Permanent Delete -->
            <a class="dropdown-item text-danger" href="view-debit-action.php?action=delete_permanent&inv_id=<?php echo $inv_id; ?>" 
            onclick="return confirm('Are you sure you want to permanently delete this invoice? This action cannot be undone.');">
            Delete Permanently</a>
        <?php else: ?>
            <!-- Disabled Delete Option (After 2 Days) -->
            <a class="dropdown-item text-muted" style="pointer-events: none; opacity: 0.5;">Delete (Disabled)</a>
            <a class="dropdown-item text-muted" style="pointer-events: none; opacity: 0.5;">Delete Permanently (Disabled)</a>
        <?php endif; ?>
        <!-- <a class="dropdown-item" href="delete_invoice.php?inv_id=<?php echo $inv_id; ?>" onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a> -->
        <!--  <a class="dropdown-item" href="view-credit-action.php?action=delete&inv_id=<?php echo $inv_id; ?>" 
       onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>

    <!-- Permanent Delete -->
 <!--   <a class="dropdown-item text-danger" href="view-credit-action.php?action=delete_permanent&inv_id=<?php echo $inv_id; ?>" 
       onclick="return confirm('Are you sure you want to permanently delete this invoice? This action cannot be undone.');">
       Delete Permanently</a> -->
     
    </div>
    </div>

    <a href="" class="btn border border-grey" onclick="printPDF('<?php echo $debitNoteDetails['dnote_file']?>')" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print text-grey" aria-hidden="true"></i> </a>
    <a href="<?php echo $debitNoteDetails['dnote_file']?>" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download"><i class="fa fa-download text-grey" aria-hidden="true"></i></a>
          
</div>


                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- <iframe id="pdfViewer" src="<?php echo $debitNoteDetails['cnote_file']; ?>" width="100%" height="600px"></iframe> -->



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
function getRelatedPurchaseInvoices($conn, $debitNoteId) {
    $debitNoteId = $conn->real_escape_string($debitNoteId); // Sanitize input

    // Query to fetch related purchase invoices
    $query = "SELECT pi.id, pi.invoice_code, pi.grand_total, pi.invoice_date
              FROM pi_invoice pi
              JOIN debit_note cn ON pi.id = cn.purchase_invoice_id
              WHERE cn.id = '$debitNoteId'";

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
                            <a href="view-pinvoice-action.php?inv_id=<?php echo $invoice['id']; ?>" ><?php echo $invoice['invoice_code']; ?></a>
                            <!-- <strong>Vendor: </strong><?php echo $invoice['vendor_name']; ?><br> -->
                            <!-- <strong>Date: </strong><?php echo $invoice['invoice_date']; ?><br> -->
                            <a href="" style="color:#00acc1;float:right">INR. <?php echo number_format($invoice['grand_total'], 2); ?>
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
    const url = "<?php echo $debitNoteDetails['dnote_file']; ?>"; // Make sure it is echoed as a string

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


</body>
</html>