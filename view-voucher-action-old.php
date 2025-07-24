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
require('fpdf/fpdf.php'); // Adjust the path if necessary

function generatePDF($voucherDetails) {
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Voucher', 0, 1, 'C');
    $pdf->Ln(10); // Line break

    // Issued By and Voucher Date
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 10, 'Issued By:', 0, 0, 'L');
    $pdf->Cell(95, 10, 'Voucher Date:', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 10, 'IIIQBETS', 0, 0, 'L');
    $pdf->Cell(95, 10, date("d-m-Y", strtotime($voucherDetails['voucherDate'])), 0, 1, 'R');

    $pdf->Ln(10); // Line break

    // Issued To and Voucher Number
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 10, 'Issued To:', 0, 0, 'L');
    $pdf->Cell(95, 10, 'Voucher Number:', 0, 1, 'R');

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 10, $voucherDetails['customer_name'], 0, 0, 'L');
    $pdf->Cell(95, 10, $voucherDetails['voucherNumber'], 0, 1, 'R');
    $pdf->Cell(95, 10, $voucherDetails['b_address_line1'], 0, 1, 'L');
    $pdf->Cell(95, 10, $voucherDetails['b_city'] . ', ' . $voucherDetails['b_state'] . ' - ' . $voucherDetails['b_Pincode'], 0, 1, 'L');

    $pdf->Ln(10); // Line break

    // Particulars and Amount
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(130, 10, 'Particulars', 0, 0, 'L');
    $pdf->Cell(60, 10, 'Amount (INR)', 0, 1, 'R');
    $pdf->Cell(190, 0, '', 'T'); // Top border

    $pdf->Ln(5); // Line break

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(130, 10, 'Account: ' . $voucherDetails['customer_name'], 0, 0, 'L');
    $pdf->Cell(60, 10, number_format($voucherDetails['amount'], 2), 0, 1, 'R');

    $pdf->Ln(5); // Line break

    // Through
    $pdf->Cell(190, 10, 'Through: ' . $voucherDetails['paymentMode'], 0, 1, 'L');

    $pdf->Ln(5); // Line break

    // Amount in Words
    $pdf->Cell(130, 10, 'Amount (In Words): ' . numberToWordsFloat($voucherDetails['amount']), 0, 0, 'L');
    $pdf->Cell(60, 10, number_format($voucherDetails['amount'], 2), 0, 1, 'R');

    $pdf->Ln(10); // Line break

    // Notes and Authorised Signatory
    $pdf->Cell(95, 10, 'Notes: ' . $voucherDetails['notes'], 0, 0, 'L');
    $pdf->Cell(95, 10, 'For IIIQBETS', 0, 1, 'R');

    $pdf->Ln(20); // Line break

    $pdf->Cell(95, 10, '', 0, 0, 'L');
    $pdf->Cell(95, 10, 'Authorised Signatory', 0, 1, 'R');

    $pdf->Ln(20); // Line break

    $pdf->Cell(190, 10, 'This is a computer generated voucher. Thank you!', 0, 1, 'C');

    // Save the PDF to a file
    $pdfFilePath = 'vouchers/voucher_' . $voucherDetails['v_id'] . '.pdf';
    $pdf->Output('F', $pdfFilePath);

    return $pdfFilePath;
}

include("config.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

function getVoucherDetails($conn, $voucherId) {
    if (empty($voucherId)) {
        echo "No voucher ID provided.";
        return false;
    }

    $voucherId = $conn->real_escape_string($voucherId); // Sanitize input

    $query = "SELECT v.*, c.*, a.*, v.bank_name, v.id AS v_id, c.customerName As cust_name FROM voucher v
              JOIN customer_master c ON v.customer_id = c.id
              JOIN address_master a ON a.customer_master_id = c.id
              WHERE v.id = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Failed to prepare statement: " . $conn->error;
        return false;
    }

    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        echo "Voucher not found or query failed.";
        return false; // Voucher not found
    }
}

$voucherId = isset($_GET['voucherId']) ? $_GET['voucherId'] : '';
$voucherDetails = getVoucherDetails($conn, $voucherId);
$pdfFilePath = $voucherDetails ? generatePDF($voucherDetails) : '';

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .voucher-header {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .voucher-subheader {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .voucher-content {
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
                                <h4 class="m-b-10">View Voucher</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">View Voucher</a></li>
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
                                <h5><a href="#" class="customer_est_name text-primary" style="font-size: 19px;">Customer: <?php echo $voucherDetails ? $voucherDetails['cust_name'] : 'Not Found';?></a></h5>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                        <ul class="nav">
                            <li class="nav-item">
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn border border-grey" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-share-alt text-grey" aria-hidden="true"></i> &nbsp;
                                        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
                                        <a class="dropdown-item" href="#" id="shareWhatsApp">Share Via WhatsApp</a>
                                        <a class="dropdown-item" href="#" id="shareEmail">Share via Email</a>
                                        <a href="<?php echo $voucherDetails ? $voucherDetails['pdf_file_path'] : '#'; ?>" id="pdfLink" style="display: none;">Download PDF</a>
                                    </div>
                                    <a href="javascript:void(0);" onclick="printPDF()" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Print">
                                        <i class="fa fa-print text-grey" aria-hidden="true"></i>
                                    </a>
                                    <a href="<?php echo $pdfFilePath; ?>" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download">
                                        <i class="fa fa-download text-grey" aria-hidden="true"></i>
                                    </a>

                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card" style="padding: 10px;">
                <div class="card-header text-center">
                    <span class="voucher-header">Voucher</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="voucher-subheader">Issued By:</div>
                            <div class="voucher-content">IIIQBETS</div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="voucher-subheader">Voucher Date:</div>
                            <div class="voucher-content">
                                <?php 
                                if ($voucherDetails && isset($voucherDetails['voucherDate'])) {
                                    $originalDate = $voucherDetails['voucherDate'];
                                    $formattedDate = date("d-m-Y", strtotime($originalDate));
                                    echo $formattedDate;
                                } else {
                                    echo '';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="voucher-subheader">Issued To:</div>
                            <div class="voucher-content">
                                <?php echo $voucherDetails ? $voucherDetails['cust_name'] : ''; ?><br>
                                <?php echo $voucherDetails ? $voucherDetails['b_address_line1'] : ''; ?><br>
                                <?php echo $voucherDetails ? $voucherDetails['b_city'] . ', ' . $voucherDetails['b_state'] . ' - ' . $voucherDetails['b_Pincode'] : ''; ?>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="voucher-subheader">Voucher Number:</div>
                            <div class="voucher-content"><?php echo $voucherDetails ? $voucherDetails['voucherNumber'] : ''; ?></div>
                        </div>
                    </div>
                    <div class="row mb-2 border-top-custom pt-2">
                        <div class="col-9">
                            <div class="voucher-subheader">Particulars</div>
                        </div>
                        <div class="col-3 text-right">
                            <div class="voucher-subheader">Amount (INR)</div>
                        </div>
                    </div>
                   
                    <div class="row mb-3 border-top-custom">
                        <div class="col-9 mt-3">
                            <div class="voucher-content"><b>Account:</b> <?php echo $voucherDetails ? $voucherDetails['bank_name'] : ''; ?></div>
                        </div>
                        <div class="col-3 mt-3 text-right">
                            <div class="voucher-content"><?php echo $voucherDetails ? number_format($voucherDetails['amount'], 2) : ''; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="voucher-content"><b>Through:</b> <?php echo $voucherDetails ? $voucherDetails['paymentMode'] : ''; ?></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-9">
                            <div class="voucher-content"><b>Amount (In Words):</b> <?php echo $voucherDetails ? numberToWordsFloat($voucherDetails['amount']) : ''; ?></div>
                        </div>
                        <div class="col-3 text-right">
                            <div class="voucher-content"><b><?php echo $voucherDetails ? number_format($voucherDetails['amount'], 2) : ''; ?></b></div>
                        </div>
                    </div>
                   
                    <div class="row mb-3 border-top-custom">
                        <div class="col-6 mt-3">
                            <div class="voucher-content"><b>Notes:</b> <?php echo $voucherDetails ? $voucherDetails['notes'] : ''; ?></div>
                        </div>
                        <div class="col-6 mt-3 text-right">
                            <div class="voucher-content"><b>For IIIQBETS</b></div>
                            <div class="voucher-content mt-5"><b>Authorised Signatory</b></div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12 text-center">
                            <span class="voucher-content">This is a computer generated voucher. Thank you!</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function printPDF() {
        var pdfPath = '<?php echo $pdfFilePath; ?>';
        var printWindow = window.open(pdfPath, '_blank');
        printWindow.addEventListener('load', function() {
            printWindow.print();
        }, true);
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
</body>
</html>
