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
        function getQuotationDetails($conn, $bill_id) {
    $quotationId = $conn->real_escape_string($bill_id); // Sanitize input

    // Your database query logic here to fetch data from the 'quotation' table
    $query = "SELECT b.*, c.*, a.*, bi.*
              FROM bill_of_supply b
              JOIN customer_master c ON b.customer_id = c.id
              JOIN address_master a ON c.id = a.customer_master_id
              JOIN billsupply_items bi ON b.id = bi.bill_id
              WHERE b.id = '$quotationId'";
    
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $quotationData = $result->fetch_assoc();
        $quotationItems = [];
        foreach ($result as $row) {
            $quotationItems[] = [
                'itemno' => $row['itemno'],
                'product' => $row['product'],
                'prod_desc' => $row['prod_desc'],
                'price' => $row['price'],
                'qty' => $row['qty'],
                'line_total' => ($row['price'] * $row['qty']),
                'total' => $row['total'],
                 'discount' => $row['discount'],
                    'invoice_date'=>$row['bill_date']

            ];
        }

        // Add quotation items array to the main quotation data
        $quotationData['quotation_items'] = $quotationItems;

        return $quotationData;
    } else {
        return false; // Quotation not found
    }
}

$bill_id = $_GET['bill_id'];
$quotationDetails = getQuotationDetails($conn, $bill_id);
echo "<pre>";
//print_r($quotationDetails);
echo "</pre>";


// Close the database connection
// $conn->close();

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
                            <h4 class="m-b-10">View Bill Of Supply</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Bill Of Supply</a></li>
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
                        <?php
if($quotationDetails['status'] == "paid")
{
 ?>
   <a href="#" data-toggle="modal" data-target="#receiptsModal"  class="btn border border-grey d-none" data-toggle="tooltip" data-placement="top" title="Open Link">Reciepts</a>
   <a href="#" data-toggle="modal" data-target="#tdsModal"  class="btn border border-grey d-none" data-toggle="tooltip" data-placement="top" title="Open Link">TDS</a>

  <?php
}else if($quotationDetails['status'] == "pending" || $quotationDetails['status'] == "partial")
{
    ?>
<a href="#" data-toggle="modal" data-target="#receiptsModal"  class="btn border border-grey " data-toggle="tooltip" data-placement="top" title="Open Link">Reciepts</a>
   <a href="#" data-toggle="modal" data-target="#tdsModal"  class="btn border border-grey"  title="Open Link">TDS</a>

<?php
}

          ?>
          <div class="btn-group">

          <a href="#" data-toggle="modal" data-target="#tdsModal"  class="btn border border-grey d-none"  data-placement="top" title="Open Link">TDS</a>

      </div>
<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-share-alt text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
        <a class="dropdown-item" href="#" id="shareWhatsApp">Share Via WhatsApp</a>
        <!-- <a class="dropdown-item" href="#">Remind via SMS</a> -->
        <a class="dropdown-item" href="#" id="shareEmail">Share via Email</a>
        <!-- Your PDF file -->
<a href="<?php echo $quotationDetails['bill_file']?>" id="pdfLink" style="display: none;">Download PDF</a>

    </div>
</div>
<!-- <div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
    <a class="dropdown-item" href="edit_billsupply.php?bill_id=<?php echo $bill_id?>">Edit Bill of Supply</a>
        <!-- <a class="dropdown-item" href="convert-invoice.php">Convert to Invoice</a> -->
        <!-- <a class="dropdown-item" href="#">esign Estimate</a> -->
        <!-- <a class="dropdown-item" href="#">Aadhaar eSign Estimate</a> -->
        <!--<a class="dropdown-item" href="#">Cancel</a>-->
     <!--   <a class="dropdown-item" href="delete_billsupply.php?bill_id=<?php echo $bill_id; ?>" onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>
        <!--<a class="dropdown-item" href="#">Delete Permanent</a>-->
    <!--</div>

    <a href="" class="btn border border-grey" onclick="printPDF('<?php echo $quotationDetails['bill_file']?>')" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print text-grey" aria-hidden="true"></i> </a>
    <a href="<?php echo $quotationDetails['bill_file']?>" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download"><i class="fa fa-download text-grey" aria-hidden="true"></i></a>
          
</div> -->
<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>

    <?php
    if (is_array($quotationDetails) && isset($quotationDetails['status'])) {
        $invoiceStatus = $quotationDetails['status'];
        $invId = htmlspecialchars($bill_id, ENT_QUOTES, 'UTF-8');
        $invCode = $quotationDetails['bill_code'];
        // ✅ Check if any payment exists for this invoice
        $query1 = "SELECT COUNT(*) as receipt_count FROM receipts WHERE invoice_id = '$bill_id' AND invoice_code ='$invCode' ";
        $result1 = $conn->query($query1);
        $receiptData = $result1->fetch_assoc();
        $receiptCount = $receiptData['receipt_count'];

        // ✅ Check if a Credit Note exists for this invoice
        $query2 = "SELECT COUNT(*) as credit_note_count FROM credit_note WHERE invoice_id = '$bill_id'";
        $result2 = $conn->query($query2);
        $creditNoteData = $result2->fetch_assoc();
        $creditNoteCount = $creditNoteData['credit_note_count'];

        // ✅ Check if E-Invoice and E-Way Bill have been generated
        // $query3 = "SELECT irn_no, e_way_bill_no FROM invoice WHERE id = '$inv_id'";
        // $result3 = $conn->query($query3);
        // $invoiceData = $result3->fetch_assoc();
        // $isEInvoiceGenerated = !empty($invoiceData['irn_no']);
        // $isEWayBillGenerated = !empty($invoiceData['e_way_bill_no']);

        // Disable Edit and Delete if E-Invoice and/or E-Way Bill are generated
        $disableEdit = ($invoiceStatus == 'paid' || $invoiceStatus == 'partial' || $receiptCount > 0 );
        $disableDelete = ($invoiceStatus == 'paid' || $invoiceStatus == 'partial' || $receiptCount > 0 || $creditNoteCount > 0 );

        echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">';

        if (!$disableEdit) {
            echo '<a class="dropdown-item" href="edit_billsupply.php?bill_id=' . $bill_id . '">Edit Bill of Supply</a>';
        } else {
            echo '<a class="dropdown-item text-muted disabled" href="#">Edit Disabled</a>';
        }

        if (!$disableDelete) {
            echo '<a class="dropdown-item text-danger" href="delete_billsupply.php?bill_id=' . $bill_id . '" onclick="return confirm(\'Are you sure you want to delete this invoice?\');">Delete</a>';
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
            <!-- <div id="status-overlay" class="status-label pb-1 pt-1 pl-3 pr-3"  style="position: absolute;top:45px;float:right;right:3px;z-index: 10;  border:2px solid red;color:red;font-weight:bold; display: none;">Not Paid</div> -->
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
        
        <canvas id="pdf-canvas" style="width:650px"></canvas>
        <div id="controls">
            <button id="prev-page" class="btn">Previous</button>
            <span>Page: <span id="current-page">1</span> / <span id="total-pages">0</span></span>
            <button id="next-page" class="btn">Next</button>
        </div>
    </div>
    </div>
    <?php
    $bill_code = $quotationDetails['bill_code'];
    $query1 = "SELECT * FROM receipts WHERE invoice_id = '$bill_id' AND invoice_code = '$bill_code' ";
$result1 = $conn->query($query1);

if ($result1->num_rows > 0) {
    
    $paymentData = $result1->fetch_all(MYSQLI_ASSOC);
//     echo "<pre>";
// print_r($paymentData);
// echo "</pre>";

    ?>
    <div class="col-md-4 mt-2">
        <div class="card">
            <!-- <div class="card"> -->
                <div class="" style="border-bottom:1px dashed gray"><h6 class="p-3">Payment Information</h6></div>
                <div class=" row pl-1 pr-1 ml-0 mr-0" style="border-bottom:1px dashed gray">
                    <div class="col-md-7 p-3">
                        <span>Invoiced</span><br/>
                        <span><?php echo "(On " . date('d-m-Y', strtotime(isset($quotationDetails['invoice_date']) ? $quotationDetails['invoice_date'] : $quotationDetails['bill_date']) ) ?></span>

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
                    <div class="col-md-7 p-3">
                         <?php
    $lastPaymentDate = isset($quotationDetails['invoice_date']) ? $quotationDetails['invoice_date'] : $quotationDetails['bill_date'];
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
    $ba = $total_amount - $tpa;
    ?>
    <span style="color:red"> INR <?= number_format($ba, 2) ?></span>
</div>

                </div>

            <!-- </div> -->
        </div>
    </div>
    <?php
} else {
    // Handle the case where no rows are found
    echo "No payment data found for the given invoice ID.";
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

    const billId = getQueryParam("bill_id"); // Extract invoice_id from URL

    // Fetch the dynamic PDF path from the backend
    fetch(`fetch_billsupply_path.php?bill_id=${billId}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "success") {
                
                const url = data.file_url; // Fetch the file URL from the response

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
});


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

</body>
</html>