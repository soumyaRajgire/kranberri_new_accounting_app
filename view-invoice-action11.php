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
    $quotationId = $conn->real_escape_string($inv_id); // Sanitize input

    // Your database query logic here to fetch data from the 'quotation' table
    $query = "SELECT q.*, c.*, a.*, qi.*
              FROM invoice q
              JOIN customer_master c ON q.customer_id = c.id
              JOIN address_master a ON c.id = a.customer_master_id
              JOIN invoice_items qi ON q.id = qi.invoice_id
              WHERE q.id = '$quotationId'";
    
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
                'gst_amt'=>$row['total_gst'],
                'gst' => $row['gst'],
                'cess_rate' => $row['cess_rate'],
                 'cess_amt' => $row['cess_amount'],
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
$quotationDetails = getQuotationDetails($conn, $inv_id);

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
        <a class="dropdown-item" href="#" id="shareWhatsApp" data-mobile="<?php echo htmlspecialchars($quotationDetails['customerPhone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">Share Via WhatsApp</a>
    <a class="dropdown-item" href="#" id="shareEmail">Share via Email</a>
    <a href="<?php echo $quotationDetails['invoice_file']; ?>" id="pdfLink" style="display: none;">Download PDF</a>
    </div>
</div>
<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
    <a class="dropdown-item" href="edit_invoice_new.php?inv_id=<?php echo $inv_id?>">Edit Invoice</a>
        <!-- <a class="dropdown-item" href="convert-invoice.php">Convert to Invoice</a> -->
        <!-- <a class="dropdown-item" href="#">esign Estimate</a> -->
        <!-- <a class="dropdown-item" href="#">Aadhaar eSign Estimate</a> -->
        <!--<a class="dropdown-item" href="#">Cancel</a>-->
        <a class="dropdown-item" href="delete_invoice.php?inv_id=<?php echo $inv_id; ?>" onclick="return confirm('Are you sure you want to delete this invoice?');">Delete</a>
        <!--<a class="dropdown-item" href="#">Delete Permanent</a>-->
    </div>

    <a href="" class="btn border border-grey" onclick="printPDF('<?php echo $quotationDetails['invoice_file']?>')" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print text-grey" aria-hidden="true"></i> </a>
    <a href="<?php echo $quotationDetails['invoice_file']?>" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download"><i class="fa fa-download text-grey" aria-hidden="true"></i></a>
          
</div>


                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- <iframe id="pdfViewer" src="<?php echo $quotationDetails['invoice_file']; ?>" width="100%" height="600px"></iframe> -->

<!-- <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 mt-2">
        <div class="card">
        <div class="panel panel-default" >
            <div class="panel-body" style="border: 1px solid black;padding: 10px;border-radius: 4px;">
                <div class="row">
                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 text-left mt-3">
                        <a style="text-decoration: none !important;">
                            <h5 class="line-height-70"><b id="seller_name" style=" color: blue;">KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</b></h5>
                        </a>
                        <h5 id="seller_add_1" class="line-height-70">120 Newport Center Dr, Newport Beach, CA 92660</h5>
                        <h5 id="seller_add_2" class="line-height-70"></h5>
                        <h5 id="seller_add_3" class="line-height-70">GST : 29AAICK7493G1ZX </h5>
                        <h5 id="seller_email" class="line-height-70"> Email: sales.usa@iiiqbets.com </h5>
                        <h5 id="seller_mobile" class="line-height-70">Phone: 91 7550705070 </h5>
                    </div>
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-right">
                        <h4 class="line-height-70" style="margin-top: 5px;">INVOICE</h4>
                        <h5 class="line-height-70"> <b>INVOICE #: <span id="inv_no"><?php echo $quotationDetails['invoice_code']?></span></b></h5>
                        <h5 class="line-height-70">Date: <span id="inv_date"><?php echo $quotationDetails['invoice_date']?></span></h5>
                        <h5 class="line-height-70">Due Date: <span id="inv_due_date"><?php echo $quotationDetails['due_date']?></span></h5>
                        <p id="inv_cancel_status" style="display:none;">0</p>
                        <p id="inv_delete_status" style="display:none;">0</p>
                    <p id="inv_added_by">Created By: <?php echo $quotationDetails['created_by']?></p>
                        <?php 
                        if($quotationDetails['status'] == "pending")
                        {
                            ?>
                            <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid red;color:red;font-weight:bold;">Not Paid</span>
                            <?php
                        }else if($quotationDetails['status'] == "partial")
                        {
                            ?>
                            <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid #3498db;color:#3498db;font-weight:bold;">Part Payment</span>
                            <?php
                        }else if($quotationDetails['status'] == "paid")
                        {
                            ?>
                            <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid green;color:green;font-weight:bold;">Fully Paid</span>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <hr style="margin-top: 11px; margin-bottom: 0px; color: black; border-color: #676767;">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-left mt-3">
                        <h4><b>Customer</b></h4>
                        <h6><span class="" id="cust_name"><?php echo $quotationDetails['customerName']?></span></h6>
                        <h6><span class="line-height-70" id="cust_email"><?php echo $quotationDetails['email'];?></span></h6>
                        <h6><span class=""><span>GSTIN : </span><?php echo $quotationDetails['gstin']?></span></h6>
                        <h6><span class="" id="cust_supply_state"><span>Place of Supply :</span> <?php echo $quotationDetails['s_state']?> </span></h6>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"></div>
                </div>
                <div class="row mt-1" style="padding: 1px;">
                    <div id="charges_div" class="col-xs-12 col-md-12 col-lg-12">
                        <table class="table-responsive table-condensed table table-bordered" style="text-size: 15px;">
                            <thead class="thead-default" style="background-color: lightgrey;">
                                <tr>
                                    <th>Slno</th>
                                    <th colspan="2"  style="width: 200px;">Description</th>
                                    <th class="rate"  style="width: 300px;">Rate</th>
                                    <th>Qty</th>
                                    <th>Discount</th>
                                    <th>Total Rate</th>
                                    <th>GST</th>
                                    <th>CGST</th>
                                     <th>SGST</th>
                                      <th>IGST</th>
                                    <th>Cess</th>
                                    <th >Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($quotationDetails['quotation_items']) && is_array($quotationDetails['quotation_items'])) : ?>
                                    <?php
                $slno = 1; // Initialize the counter variable
                $tot_qty = 0;
                $tot_amt = 0;
                $gst_tot = 0;
                $line_tot_amt = 0;
            ?>
            <?php foreach ($quotationDetails['quotation_items'] as $item) : ?>
                <tr style="font-size: 16px;">
                    <td><small><?php echo $slno++; ?></small></td> 
                        <td colspan="2" class="text-left description">
                            <span><small><?php echo $item['product']; ?></small></span><br>
                            <small style="padding: 0px; margin: 0px; font-size: 10px;"><?php echo $item['prod_desc']; ?></small>
                        </td>
                        <td><small><?php echo $item['price']; ?></small></td>
                        <td><small><?php echo $item['qty']; ?></small></td>
                         <td><small><?php echo $item['discount']; ?></small></td>
                        <td><span><small><?php echo ($item['price'] * $item['qty']); ?></small></span></td>
                        <td><span><small><?php echo $item['gst']; ?> %</small></span></td>
                         <td><span><small><?php echo $item['cgst']; ?></small></span></td>
                          <td><span><small><?php echo $item['sgst']; ?></small></span></td>
                           <td><span><small><?php echo $item['igst']; ?> </small></span></td>
                            <td><span><small><?php echo $item['cess_amt']?></small><small>(<?php echo $item['cess_rate']; ?> %)</small></span></td>
                        <td><span><small><?php echo $item['total']; ?></small></span></td>
                    </tr>
                    <?php 
                $tot_qty += intval($item['qty']);
                $line_tot_amt += floatval($item['line_total']);
                $gst_tot += floatval($item['gst_amt']);
                $tot_amt += floatval($item['total']);
                

                    ?>
                <?php endforeach; ?>

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

                 <?php else : ?>
                <tr>
                    <td colspan="8">No quotation items found</td>
                </tr>
                   <?php var_dump($quotationDetails); ?>
            <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <!-- <tr class="no-border">
                                    <td colspan="4" style="border-bottom: 0px; text-align:center;"><strong>Grand Total</strong></td>
                                    <td style="border-bottom: 0px;" class="text-right"><b><?php echo $tot_qty;?></b></td>
                                    <td style="border-bottom: 0px;" class="text-right"><b><?php echo  $line_tot_amt;?></b></td>
                                    <td></td><td></td><td></td><td></td><td></td>
                                    <td style="border-bottom: 0px;" class="text-right"><b><?php echo $gst_tot;?></b></td>

                                    <td style="border-bottom: 0px;" class="text-right"><b><i class="fas fa-rupee-sign"></i> <span id="tot_payable"><?php echo $quotationDetails['grand_total'];?></span></b></td>
                                </tr> -->
                               <!-- <tr>
                                    <td colspan="2" class="text-left" style="font-size: 16px;"><small>Online Link</small></td>
                                    <td colspan="4" class="text-left" style="padding: 5px;"><small><a href="" target="_blank">link</a></small></td>
                                    <td colspan="6" class="text-left" style="font-size: 16px;" ><small>Sub Total</small></td>
                                    <td class="text-right"><b><span><?php echo $line_tot_amt;?></span></b></td>
                                </tr>
                                <tr>
                                    <td colspan="6" rowspan="3">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-border-top no-border-bottom" style="padding: 5px; min-height: 100px;">
                                            <p style="text-align: left; font-size: 16px;"><small><b>Terms &amp; Conditions:</b><br></small><span><?php echo $quotationDetails['terms_condition']?></span></p>
                                        </div>
                                    </td>
                                    <td colspan="6" class="text-left" style="font-size: 16px;"><small>GST Total</small></td>
                                    <td class="text-right"><b><span><?php echo $gst_tot;?></span></b></td>
                                </tr>
                                <tr>
    <td colspan="6" class="text-left" style="font-size: 16px;"><small>Grand Total</small></td>
    <td class="text-right"><b><span><?php echo $quotationDetails['grand_total']; ?></span></b></td>
</tr>
<tr>
    <td colspan="3" class="text-left" style="font-size: 16px;">
        <small><b>Amount: <?php echo numberToWordsFloat($quotationDetails['grand_total']); ?>.</b></small>
    </td>
</tr>

                            </tfoot>
                        </table>
                        <hr style="margin-top: 1px; margin-bottom: 0px; color: black; border-color: #000;">
                    </div>
                </div>
                <div class="row">
                <div class="col-xs-12 mx-auto" style="width: 300px;">
            <p class="text-center my-3">Thank you for your business!</p>
        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$query1 = "SELECT * FROM receipts WHERE invoice_id = '$inv_id'";
$result1 = $conn->query($query1);

if ($result1->num_rows > 0) {
    // Fetch all rows from the result set
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
                            <span><?php echo "(On " . date('d-m-Y', strtotime($payment['transaction_date'])) . ")"?></span>
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
    $ba = $total_amount - $tpa;
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
    echo "No payment data found for the given invoice ID.";
}
?>

    </div> -->

<div class="row">
    <div class="col-md-8">
        
        <div id="pdf-container" style="position: relative;">
             <?php 
                        if($quotationDetails['status'] == "pending")
                        {
                            ?>
            <div id="status-overlay" class="status-label pb-1 pt-1 pl-3 pr-3"  style="position: absolute;top:45px;float:right;right:3px;z-index: 10;  border:2px solid red;color:red;font-weight:bold; display: none;">Not Paid</div>
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
        
        <canvas id="pdf-canvas" style="width:700px"></canvas>
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

if ($result1->num_rows > 0) {
    // Fetch all rows from the result set
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
                            <span><?php echo "(On " . date('d-m-Y', strtotime($payment['transaction_date'])) . ")"?></span>
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
    $ba = $total_amount - $tpa;
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

    const invoiceId = getQueryParam("inv_id"); // Extract invoice_id from URL

    // Fetch the dynamic PDF path from the backend
    fetch(`fetch_invoice_path.php?invoice_id=${invoiceId}`)
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
            <input type="hidden" name="invoice_file" id="invoice_file" value="<?php echo htmlspecialchars($invoiceDetails['invoice_file'], ENT_QUOTES, 'UTF-8'); ?>" />
            <label for="customer_email">To:</label>
            <input type="email" class="form-control" id="customer_email" name="customer_email" value="<?php echo htmlspecialchars($invoiceDetails['customerEmail'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required />
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
</body>
</html>