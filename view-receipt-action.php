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

function getReceiptInfo($conn, $receiptId) {
    if (empty($receiptId)) {
        echo "No receipt ID provided.";
        return false;
    }

    $receiptId = $conn->real_escape_string($receiptId); // Sanitize input

    // Your database query logic here to fetch data from the 'receipts' table
    $query = "SELECT r.*, c.*, a.* FROM receipts r
              JOIN customer_master c ON r.customer_id = c.id
              JOIN address_master a ON a.customer_master_id = c.id
              WHERE r.id = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Failed to prepare statement: " . $conn->error;
        return false;
    }

    $stmt->bind_param("s", $receiptId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        echo "Receipt not found or query failed.";
        return false; // Receipt not found
    }
}

$receiptId = isset($_GET['receiptId']) ? $_GET['receiptId'] : '';
$receiptDetails = getReceiptInfo($conn, $receiptId);

function numberToWordsFloat($number) {
    $integer = floor($number);
    $fraction = round($number - $integer, 2) * 100;
    
    $integerWords = convertNumberToWords($integer);
    $fractionWords = convertNumberToWords($fraction);

    return $integerWords . ' and ' . $fractionWords . ' paise';
}

function convertNumberToWords($number) {
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'forty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convertNumberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convertNumberToWords(abs($number));
    }

    $string = $fraction = null;

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convertNumberToWords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convertNumberToWords($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
?>

<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php");?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="" crossorigin="anonymous">
    <style>
        .receipt-header {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .receipt-subheader {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .receipt-content {
            font-size: 1rem;
        }
        .border-top-custom {
            border-top: 2px solid #dee2e6;
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
                                <h4 class="m-b-10">View Receipt</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">View Receipt</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card" style="padding: 5px;">
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <ul class="nav">
                            <li class="nav-item mt-2">
                                <h5><a href="#" class="customer_est_name text-primary"  style="font-size: 19px;">Customer: <?php echo $receiptDetails ? $receiptDetails['customerName'] : 'Not Found';?></a></h5>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                        <ul class="nav">
                            <li class="nav-item">
                                <div class="btn-group" role="group">
                                    <!-- <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-share-alt text-grey" aria-hidden="true"></i> &nbsp;
                                        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
                                    </a> -->
                                     <div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-share-alt text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
  

    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
    <a class="dropdown-item" href="#" id="shareWhatsApp" data-mobile="<?php echo htmlspecialchars($invoiceDetails['customerPhone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">Share Via WhatsApp</a>
    <a class="dropdown-item" href="#" id="shareEmail">Share via Email</a>
    <a href="<?php echo $invoiceDetails['invoice_file']; ?>" id="pdfLink" style="display: none;">Download PDF</a>
</div>

</div>

<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">

   <a href="#" data-toggle="modal" data-target="#editReceiptModal"  class="btn border border-grey " data-toggle="tooltip" data-placement="top" title="Open Link">Edit Reciept</a>

        
        <a class="dropdown-item" href="delete_receipt.php?inv_id=<?php echo $receiptId; ?>" onclick="return confirm('Are you sure you want to delete this receipt?');">Delete</a>

        
    </div>

   <a href="#" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Print" onclick="printPDF('<?php echo $receiptDetails ? $receiptDetails['pdf_file_path'] : '#'; ?>')"><i class="fa fa-print text-grey" aria-hidden="true"></i></a>
                                    <a href="<?php echo $receiptDetails ? $receiptDetails['pdf_file_path'] : '#'; ?>" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download"><i class="fa fa-download text-grey" aria-hidden="true"></i></a>        
</div>

    </div>
    
                                   
                                    
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

<?php include("edit_receipt.php"); ?>
         <div class="row">
             <div class="card col-md-8">
                <canvas id="pdf-canvas" style="width:600px"></canvas>
        <div id="controls">
            <button id="prev-page" class="btn">Previous</button>
            <span>Page: <span id="current-page">1</span> / <span id="total-pages">0</span></span>
            <button id="next-page" class="btn">Next</button>
        </div>
                <!--  <div class="card">
                     <!-- <div class="card-header text-center"><span class="receipt-header">Receipt</span></div> -->
                      <!-- <div class="card-body p-0" style="font-size:12px;"> -->
                              
                      <!-- </div> -->
                 <!-- </div> --> 
             </div>
             <div class="col-md-4">
                 <div class="card">
                      <div class="card-body"></div>
                 </div>
             </div>
         </div>
          
           
    </section>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#shareWhatsApp').on('click', function() {
            var pdfLink = $('#pdfLink').attr('href');
            var message = 'Check out this PDF: ' + pdfLink;
            var whatsappUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(message);
            window.open(whatsappUrl, '_blank');
        });

        $('#shareEmail').on('click', function() {
            var pdfLink = $('#pdfLink').attr('href');
            var emailUrl = 'path/to/your/email-script.php?pdf=' + encodeURIComponent(pdfLink);
            window.location.href = emailUrl;
        });
    });
    </script>
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

    const receiptId = getQueryParam("receiptId"); // Extract invoice_id from URL

    // Fetch the dynamic PDF path from the backend
    fetch(`fetch_receipt_path.php?receipt_id=${receiptId}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "success") {
                console.log(data);
                const url = data.receipt_file; // Fetch the file URL from the response

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
</body>
</html>
