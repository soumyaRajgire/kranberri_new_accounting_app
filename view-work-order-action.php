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
   
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous"> -->

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
function getQuotationDetails($conn, $pinv_id) {
    $quotationId = $conn->real_escape_string($pinv_id); // Sanitize input

    // Your database query logic here to fetch data from the 'purchase_invoice' table
    $query = "SELECT pi.*, c.*, a.*, pii.*
              FROM purchase_invoice pi
              JOIN customer_master c ON pi.customer_id = c.id
              JOIN address_master a ON c.id = a.customer_master_id
              JOIN purchase_invoice_items pii ON pi.id = pii.pinvoice_id
              WHERE pi.id = '$quotationId'";
    
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
                'line_total' => $row['line_total'],
                'total' => $row['total'],
                'gst_amt' => $row['gst_amt'],
                'gst' => $row['gst'],
            ];
        }

        // Add quotation items array to the main quotation data
        $quotationData['quotation_items'] = $quotationItems;

        return $quotationData;
    } else {
        return false; // Quotation not found
    }
}

$pinv_id = $_GET['pinv_id'];
$quotationDetails = getQuotationDetails($conn, $pinv_id);

// Close the database connection
// $conn->close();
?>

     <?php include("menu.php");?>
    
   
    <!-- [ Header ] end -->
    

 <!-- [ breadcrumb ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
       
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">View  Invoice</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Purchase Invoice</a></li>
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
  <?php
}else if($quotationDetails['status'] == "pending" || $quotationDetails['status'] == "partial")
{
    ?>
<a href="#" data-toggle="modal" data-target="#receiptsModal"  class="btn border border-grey " data-toggle="tooltip" data-placement="top" title="Open Link">Reciepts</a>
<?php
}

          ?>
                    <div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-share-alt text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
        <a class="dropdown-item" href="#">Share Via WhatsApp</a>
        <!-- <a class="dropdown-item" href="#">Remind via SMS</a> -->
        <a class="dropdown-item" href="#">Share via Email</a>
    </div>
</div>
<div class="btn-group">
    <a href="#" class="btn border border-grey" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
    </a>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
   <a class="dropdown-item" href="update_purchase_invoice.php?id=<?php echo $pinv_id; ?>">Edit Purchase Invoice</a>
        <!-- <a class="dropdown-item" href="convert-invoice.php">Convert to Invoice</a> -->
        <!-- <a class="dropdown-item" href="#">esign Estimate</a> -->
        <!-- <a class="dropdown-item" href="#">Aadhaar eSign Estimate</a> -->
        <a class="dropdown-item" href="#">Cancel</a>
        <a class="dropdown-item" href="#" onclick="confirmDelete(<?php echo $pinv_id; ?>)">Delete</a>
        <!-- <a class="dropdown-item" href="#">Delete Permanent</a> -->
    </div>
    <script>
function confirmDelete(invoiceId) {
    if (confirm("Are you sure you want to delete this purchase invoice? This action cannot be undone.")) {
        window.location.href = "delete_purchase_invoice.php?id=" + invoiceId;
    }
}
</script>
    <a href="" class="btn border border-grey" onclick="printPDF('<?php echo $quotationDetails['pinvoice_file']?>')" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print text-grey" aria-hidden="true"></i> </a>
    <a href="<?php echo $quotationDetails['pinvoice_file']?>" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download"><i class="fa fa-download text-grey" aria-hidden="true"></i></a>
          
</div>


                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- <iframe id="pdfViewer" src="<?php echo $quotationDetails['pinvoice_file']; ?>" width="100%" height="600px"></iframe> -->

<div class="row">
    

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
                        <h4 class="line-height-70" style="margin-top: 5px;">PURCHASE INVOICE</h4>
                        <h5 class="line-height-70"> <b> PURCHASE INVOICE #: <span id="pinv_no"><?php echo $quotationDetails['pinvoice_code']?></span></b></h5>
                        <h5 class="line-height-70">Date: <span id="pinv_date"><?php echo $quotationDetails['pinvoice_date']?></span></h5>
                        <h5 class="line-height-70">Due Date: <span id="pinv_due_date"><?php echo $quotationDetails['due_date']?></span></h5>
                        <p id="inv_cancel_status" style="display:none;">0</p>
                        <p id="inv_delete_status" style="display:none;">0</p>
                    <p id="inv_added_by">Created By: <?php echo $quotationDetails['created_by']?></p>
                        <?php 
                        if($quotationDetails['status'] == "pending")
                        {
                            ?>
                            <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid red;color:red;">Not Paid</span>
                            <?php
                        }else if($quotationDetails['status'] == "partial")
                        {
                            ?>
                            <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid red;color:red;">Part Paid</span>
                            <?php
                        }else if($quotationDetails['status'] == "paid")
                        {
                            ?>
                            <span class="pb-1 pt-1 pl-3 pr-3 " style="border:2px solid red;color:red;">Fully Paid</span>
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
                                    <th style="width: 20px;">Slno</th>
                                    <th colspan="2"  style="width: 200px;">Description</th>
                                    <th class="rate"  style="width: 300px;">Rate</th>
                                    <th  style="width: 300px;">Qty</th>
                                    <th  style="width: 300px;">Taxable Amt</th>
                                    <th style="width: 300px;">GST</th>
                                    <th  style="width: 300px;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($quotationDetails['quotation_items']) && is_array($quotationDetails['quotation_items'])) : ?>
         <?php  $tot_qty =0;
           $tot_amt =0;
           $gst_tot =0;
           $line_tot_amt =0;
       ?>
                               <?php foreach ($quotationDetails['quotation_items'] as $item) : ?>
                    <tr style="font-size: 16px;" >
                        <td><small><?php echo $item['itemno']; ?></small></td>
                        <td colspan="2" class="text-left description">
                            <span><small><?php echo $item['product']; ?></small></span><br>
                            <small style="padding: 0px; margin: 0px; font-size: 10px;"><?php echo $item['prod_desc']; ?></small>
                        </td>
                        <td><small><?php echo $item['price']; ?></small></td>
                        <td><small><?php echo $item['qty']; ?></small></td>
                        <td><span><small><?php echo $item['line_total']; ?></small></span></td>
                        <td><span><small><?php echo $item['gst_amt']; ?></small><small>(<?php echo $item['gst']; ?> %)</small></span></td>
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


// Example usage:
// $number = 123;
// echo numberToWords($number); // Output: "one hundred and twenty-three"
?>

                 <?php else : ?>
                <tr>
                    <td colspan="8">No quotation items found</td>
                </tr>
                   <?php var_dump($quotationDetails); ?>
            <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="no-border">
                                    <td colspan="4" style="border-bottom: 0px; text-align:center;"><strong>Grand Total</strong></td>
                                    <td style="border-bottom: 0px;" class="text-right"><b><?php echo $tot_qty;?></b></td>
                                    <td style="border-bottom: 0px;" class="text-right"><b><?php echo  $line_tot_amt;?></b></td>
                                    <td style="border-bottom: 0px;" class="text-right"><b><?php echo $gst_tot;?></b></td>

                                    <td style="border-bottom: 0px;" class="text-right"><b><i class="fas fa-rupee-sign"></i> <span id="tot_payable"><?php echo $tot_amt;?></span></b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-left" style="font-size: 16px;"><small>Online Link</small></td>
                                    <td colspan="3" class="text-left" style="padding: 5px;"><small><a href="" target="_blank">link</a></small></td>
                                    <td colspan="2" class="text-left" style="font-size: 16px;" ><small>Sub Total</small></td>
                                    <td class="text-right"><b><span><?php echo $line_tot_amt;?></span></b></td>
                                </tr>
                                <tr>
                                    <td colspan="5" rowspan="3">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-border-top no-border-bottom" style="padding: 5px; min-height: 100px;">
                                            <p style="text-align: left; font-size: 16px;"><small><b>Terms &amp; Conditions:</b><br></small><span><?php echo $quotationDetails['terms_condition']?></span></p>
                                        </div>
                                    </td>
                                    <td colspan="2" class="text-left" style="font-size: 16px;"><small>GST Total</small></td>
                                    <td class="text-right"><b><span><?php echo $gst_tot;?></span></b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-left" style="font-size: 16px;"><small>Grand Total</small></td>
                                    <td class="text-right"><b><span><?php echo $tot_amt?></span></b></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-left" style="font-size: 16px;"><small><b>Amount: <?php echo numberToWords($tot_amt)?>.</b></small></td>
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
  $query1 = "SELECT * FROM voucher WHERE invoice_id = '$inv_id'";
$result1 = $conn->query($query1);
// Fetch credit note adjustments for this invoice
    $query2 = "SELECT SUM(total_amount) AS total_credit_adjusted, dnote_date FROM debit_note 
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
                            <span><?php echo "(On " . date('d-m-Y', strtotime($payment['voucher_date'])) . ")"?></span>
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
                                <span>Debit Note Adjusted</span><br/>
                                <span><?php echo "(On " . date('d-m-Y', strtotime($creditNote['dnote_date'])) . ")" ?></span>
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