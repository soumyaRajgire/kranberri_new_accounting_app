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

function getReceiptDetails($conn, $receiptId) {
    if (empty($receiptId)) {
        echo "No receipt ID provided.";
        return false;
    }

    $receiptId = $conn->real_escape_string($receiptId); // Sanitize input

    // Your database query logic here to fetch data from the 'receipts' table
    $query = "SELECT r.*, c.*, a.* FROM voucher r
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

$receiptId = isset($_GET['voucherId']) ? $_GET['voucherId'] : '';
$receiptDetails = getReceiptDetails($conn, $receiptId);

function numberToWordsFloat($number) {
    $integer = (int) floor($number);  // Use floor() to ensure proper conversion
    $fraction = round(($number - $integer) * 100);  // Ensure proper rounding to avoid precision loss

    $integerWords = convertNumberToWords($integer);
    $fractionWords = ($fraction > 0) ? convertNumberToWords($fraction) . ' paise' : 'zero paise';

    return $integerWords . ' and ' . $fractionWords;
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
        trigger_error(
            'convertNumberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convertNumberToWords(abs($number));
    }

    $string = null;

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
            $hundreds  = (int) ($number / 100);
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
                                <h5><a href="#" class="customer_est_name text-primary"  style="font-size: 19px;">Supplier: <?php echo $receiptDetails ? $receiptDetails['customerName'] : 'Not Found';?></a></h5>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                        <ul class="nav">
                            <li class="nav-item">
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-share-alt text-grey" aria-hidden="true"></i> &nbsp;
                                        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
                                        <a class="dropdown-item" href="#" id="shareWhatsApp">Share Via WhatsApp</a>
                                        <a class="dropdown-item" href="#" id="shareEmail">Share via Email</a>
                                        <a href="<?php echo $receiptDetails ? $receiptDetails['pdf_file_path'] : '#'; ?>" id="pdfLink" style="display: none;">Download PDF</a>
                                    </div>
                                    <div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">

   <a href="#" data-toggle="modal" data-target="#editVoucherModal"  class="btn border border-grey " data-toggle="tooltip" data-placement="top" title="Open Link">Edit Voucher</a>

        
      <a class="dropdown-item" href="delete_voucher.php?inv_id=<?php echo $receiptId; ?>" onclick="return confirm('Are you sure you want to delete this voucher?');">Delete</a>

        
    </div>

   <a href="#" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Print" onclick="printPDF('<?php echo $receiptDetails ? $receiptDetails['pdf_file_path'] : '#'; ?>')"><i class="fa fa-print text-grey" aria-hidden="true"></i></a>
                                    <a href="<?php echo $receiptDetails ? $receiptDetails['pdf_file_path'] : '#'; ?>" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download"><i class="fa fa-download text-grey" aria-hidden="true"></i></a>        
</div>
                                   
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php include("edit_vochure.php"); ?>
         <div class="row">
             <div class="col-md-8">
                 <div class="card">
                     <div class="card-header text-center"><span class="receipt-header">Voucher</span></div>
                      <div class="card-body p-0" style="font-size:12px;">
                            <table class="table-bordered p-3 table-responsive">
                            <tbody>
                                <tr>
                        <td rowspan="2" style="vertical-align: top;" class="p-1" width="38%"> 
                        <h6 class=""> Issued By: </h6>

                            <span class=""> KRIKA MKB CORPORATION PRIVATE LIMITED </span>
                            <span id="seller_add_1" class="line-height-70">SKYLINE BEVERLY PARK, # D 402, AMRUTHAHALLI MAIN ROAD, AMRUTHAHALLI</span>
                            <span id="seller_add_2" class="line-height-70">AMRUTHAL</span>
                            <span id="seller_add_3" class="line-height-70">BANGALORE - 560092</span>
                            <span id="seller_add_4" class="line-height-70">KARNATAKA - INDIA</span>
 
                        </td>
                        <td rowspan="2" style="vertical-align: top;" class="p-1" width="38%">
                        <h6 class=""> Issued To: </h6>

                                <span class=""> <?php echo $receiptDetails ? $receiptDetails['customerName'] : ''; ?></span><br/>
                            <span id="seller_add_1" class="line-height-70"> <?php echo $receiptDetails ? $receiptDetails['b_address_line1'] : ''; ?></span><br/>
                            <span id="seller_add_2" class="line-height-70"> <?php echo $receiptDetails ? $receiptDetails['b_address_line2'] : ''; ?></span><br/>
                            <span id="seller_add_3" class="line-height-70"> <?php echo $receiptDetails ? $receiptDetails['b_city'] :'';?> - <?php echo $receiptDetails['b_Pincode'] ? $receiptDetails['b_Pincode'] : '';?></span><br/>
                            <span id="seller_add_3" class="line-height-70"><?php echo $receiptDetails['b_state'] ? $receiptDetails['b_state'] : '';?></span>
                             
                        </td>
                        <td width="24%" style="vertical-align: top;" class="p-1">
                        <h6 class="line-height-72" style="text-align:left;"> Voucher Date:  </h6> 
                         <h6 class="line-height-70" style="text-align:left;"> <span id="rec_date"><?php echo $receiptDetails ? $receiptDetails['voucher_date'] : ''; ?></span> </h6>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;" class="p-1">
                         <h6 class="line-height-72" style="text-align:left;"> Voucher Number::  </h6> 
                         <h6 class="line-height-70" style="text-align:left;"> <span id="rec_no"><?php echo $receiptDetails ? $receiptDetails['voucher_id'] : ''; ?></span> </h6>
                        </td>
                    </tr>

                    <tr class="thead-default">
                    <th colspan="2" style="font-weight: 600;text-align:center;font-size: 14px ! important;margin-top: 5px;margin-bottom: 5px;margin-right: 5px;" class="line-height-73" width="33%">Particulars</th>
                    <th style=" font-weight: 600;font-size: 14px ! important;margin-top: 5px;margin-bottom: 5px;margin-right: 5px;" width="25%" class="line-height-73">Amount (INR)</th>
                    </tr>


                    <tr>
                         <td colspan="2" style="border-bottom: none ! important; ">
                                
                         <h6 class="line-height-72" style="font-weight:600"> Account: </h6>
                         <h6 class="line-height-70"><?php echo $receiptDetails['customerName']?>  </h6>
                         </td>
                        <td style="border-bottom: none ! important; ">
                         <h6 class="line-height-72"> &nbsp;   </h4><h4>
                         </h6><h6 class="line-height-70" style="text-align:right ! important;">
                        <?php echo $receiptDetails ? number_format($receiptDetails['paid_amount'], 2) : ''; ?> </h6>
                         </td>
                     </tr>

                     <tr>
                      <td colspan="2" style="border-top: none ! important;border-bottom: none ! important;   ">
                    
                      <h6 class="line-height-72" style="font-weight:600"> Through: </h6>
                     <h6 class="line-height-70"> <?php echo $receiptDetails ? $receiptDetails['payment_mode'] : ''; ?>  </h6>
                 
                     </td>
                       <td style="border-top: none ! important;border-bottom: none ! important; ">

                       </td>
                      </tr>

                      <tr>
                      <td colspan="2" style="border-top: none ! important;border-bottom: none ! important; ">
                
                     <!-- <h6 class="line-height-72" style="font-weight:600"> Bank Transaction Details: </h6>-->
                      <?php
                          // switch ($receiptDetails['payment_mode'])
                          // case 'Direct Deposit':
                          //        echo '<h6 class="line-height-70">'.{$receiptDetails["bank_name"]}.' </h6>';
                          //        break;
                          //  case 'NEFT/RTGS':
                          //        echo '<h6 class="line-height-70">'.{$receiptDetails["bank_name"]}.' </h6>'; 
                          //        break;
                          //   case 'Online Payment':
                          //        echo '<h6 class="line-height-70">'.{$receiptDetails["transactionid"]}.' </h6>'; 
                          //        break;
                          //   case 'Credit Debit Card':
                          //        echo '<h6 class="line-height-70">'.{$receiptDetails["bank_name"]}.' </h6>'; 
                          //        break;
                          //   case 'Demand Draft':
                          //        echo '<h6 class="line-height-70">'.{$receiptDetails["bank_name"]}.' </h6>'; 
                          //        break;
                          //   case 'Cheque':
                          //        echo '<h6 class="line-height-70">'.{$receiptDetails["bank_name"]}.' </h6>'; 
                          //        break;
                          //   case 'Cash':
                          //        echo '<h6 class="line-height-70">'.{$receiptDetails["bank_name"]}.' </h6>';     
                          //        break;  
                      ?>

                    
                 
                  
                     </td>
                     <td style="border-top: none ! important;border-bottom: none ! important; "> </td>
                      </tr>

                      <tr>
                      <td colspan="2" style="border-top: none !important;">
    <h6 class="line-height-72" style="font-weight:600">Amount (In Words): </h6>
    <h6 class="line-height-70">
        <?php 
        if ($receiptDetails && isset($receiptDetails['paid_amount'])) {
            $paidAmount = (float) $receiptDetails['paid_amount']; // Explicit float conversion
            echo numberToWordsFloat($paidAmount); 
        } else {
            echo '';
        }
        ?>
    </h6>
</td>

                       <td style=" "><h4 class=" line-height-72">&nbsp;  </h4>
                       <h6 class="line-height-72" style="text-align:right  ! important;"><?php echo $receiptDetails ? number_format($receiptDetails['paid_amount'], 2) : ''; ?></h6>
                       </td>
                      </tr>

            <tr>
                <td colspan="3" style="    padding: 0px;">
                    <div class="row" style="display:flex;">
                        <div class="col-xs-6 col-md-6 col-lg-6 col-sm-6" style="flex:1;">
                            <div style="position:relative;bottom:0px;margin-top:10px"> 
                              <h6 class="line-height-72"> Notes: </h6> <p> <span class="line-height-70"> <?php echo $receiptDetails ? $receiptDetails['notes'] : ''; ?></span></p>
                            </div>                      
                        </div> 
                        <div class="test1 col-xs-6 col-md-6 col-lg-6 col-sm-6" style="flex:1;">
                            <div id="seller_div" style="margin-top:10px">
                                <p style="text-align: right;" class="line-height-73"> For KRIKA MKB CORPORATION PRIVATE LIMITED </p>
                            </div>
                            <div id="signatory_div" style="padding-top:100px">
                                <p style="text-align: right" class="line-height-73">  Authorised Signatory  </p>
                            </div>
                        </div>
                    </div>
                </td>
                      </tr>

                      <tr>  
                      <td colspan="3">  <p></p><h5 class="line-height-70" style="text-align:center;">This is a computer generated receipt. Thank you!</h5><p></p>
                                </td>
                      </tr>
                            </tbody>
                        </table>
                      </div>
                 </div>
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
</body>
</html>
