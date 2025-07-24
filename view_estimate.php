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

    <meta charset="utf-8">
    <?php include("header_link.php");?>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

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
</style>
<style type="text/css">
/* Updated Modal Styles */
#shareModal {
      position: fixed;
    z-index: 1;
    bottom: 10px;
    right: 0px;
    width: 550px;
    background-color: #fefefe;
    padding: 20px;
    border: 1px solid #888;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    top: 0px;
    z-index: 10000;
}

/* Close button */
.modal-content h3 {
    background-color: #665dc3;
    color: white;
    padding: 10px;
    margin: -20px -20px 20px -20px;
    text-align: left;
}

.close {
    color: #aaa;
    float: right;
    font-size: 20px;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

button#sendEmailBtn {
    background-color: #665dc3;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button#sendEmailBtn:hover {
    background-color: #554da0;
}

</style>
</head>

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
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">View Estimate</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Estimate</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <?php
        function getQuotationDetails($conn, $qid) {
            $quotationId = $conn->real_escape_string($qid); // Sanitize input

            // Your database query logic here to fetch data from the 'quotation' table
            $query = "SELECT q.*, c.*, c.email AS customerEmail, a.*, qi.*
                    FROM quotation q
                    JOIN customer_master c ON q.customer_id = c.id
                    JOIN address_master a ON c.id = a.customer_master_id
                    JOIN quotation_items qi ON q.id = qi.invoice_id
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

        $qid = $_GET['qid'];
        $quotationDetails = getQuotationDetails($conn, $qid);

        // Close the database connection
        $conn->close();
        ?>
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
                                <a href="#" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Open Link">
                                    <i class="fa fa-link text-grey" aria-hidden="true"></i>
                                </a>
                                <div class="btn-group">
                                    <a href="#" class="btn border border-grey" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-share-alt text-grey" aria-hidden="true"></i> &nbsp;
                                        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
                                        <a class="dropdown-item" href="#">Remind via WhatsApp</a>
                                        <a class="dropdown-item" href="#">Remind via SMS</a>
                                        <!--<a class="dropdown-item" href="#">Remind via Email</a>-->
                                        <a class="dropdown-item" href="javascript:void(0);" id="shareEmail">Remind via Email</a>
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <a href="#" class="btn border border-grey" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-list-ul text-grey" aria-hidden="true"></i> &nbsp;
                                        <i class="fa fa-caret-down text-grey" aria-hidden="true"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" style="margin-right: 55px;">
                                        <a class="dropdown-item" href="edit_estimate.php?qid=<?php echo $qid?>">Edit Estimate</a>
                                        <a class="dropdown-item" href="convert-invoice.php?qid=<?php echo $qid?>">Convert to Invoice</a>
                                        <!-- <a class="dropdown-item" href="#">esign Estimate</a> -->
                                        <!-- <a class="dropdown-item" href="#">Aadhaar eSign Estimate</a> -->
                                        <!-- <a class="dropdown-item" href="#">Cancel</a> -->
                                        <a class="dropdown-item" href="delete_estimate.php?qid=<?php echo $qid?>" onclick="return confirm('Are you sure you want to delete this estimate?');">Delete</a>
                                        <!-- <a class="dropdown-item" href="#">Delete Permanent</a> -->
                                    </div>
                                    <a href="" class="btn border border-grey" onclick="printPDF('<?php echo $quotationDetails['quotation_file']?>')" data-toggle="tooltip" data-placement="top" title="Print"><i class="fa fa-print text-grey" aria-hidden="true"></i> </a>
                                    <a href="<?php echo $quotationDetails['quotation_file']?>" target="_blank" class="btn border border-grey" data-toggle="tooltip" data-placement="top" title="Download"><i class="fa fa-download text-grey" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                <div class="panel panel-default">
                    <div class="panel-body" style="border: 1px solid black;padding: 10px;border-radius: 4px;">
                        <div class="row">
                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 text-left mt-3">
                                <a style="text-decoration: none !important;">
                                    <h5 class="line-height-70"><b id="seller_name" style=" color: blue;">KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)</b></h5>
                                </a>
                                <h5 id="seller_add_1" class="line-height-70">120 Newport Center Dr, Newport Beach, CA 92660</h5>
                                <!-- <h5 id="seller_add_2" class="line-height-70"></h5> -->
                                <h5 id="seller_add_3" class="line-height-70"> GST : 29AAICK7493G1ZX</h5>
                                <h5 id="seller_email" class="line-height-70"> Email: sales.usa@iiiqbets.com </h5>
                                <h5 id="seller_mobile" class="line-height-70">Phone: 91 7550705070</h5>
                            </div>
                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-right">
                                <h4 class="line-height-70" style="margin-top: 5px;">ESTIMATE</h4>
                                <h5 class="line-height-70"> <b>ESTIMATE #: <span id="inv_no"><?php echo $quotationDetails['invoice_code']?></span></b></h5>
                                <h5 class="line-height-70">Date: <span id="inv_date"><?php echo $quotationDetails['quotation_date']?></span></h5>
                                <h5 class="line-height-70">Validity: <span id="inv_due_date"><?php echo $quotationDetails['due_date']?></span></h5>
                                <p id="inv_cancel_status" style="display:none;">0</p>
                                <p id="inv_delete_status" style="display:none;">0</p>
                                <p id="inv_added_by">Created By: <?php echo $quotationDetails['created_by'];?></p>
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
                                            <th  style="width: 300px;">Total Rate</th>
                                            <th style="width: 300px;">GST</th>
                                            <th  style="width: 300px;">Total</th>
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
                                                    <td><span><small><?php echo $item['line_total']; ?></small></span></td>
                                                    <td><span><small><?php echo $item['gst_amt']; ?></small><small>(<?php echo $item['gst']; ?> %)</small></span></td>
                                                    <td><span><small><?php echo $item['total']; ?></small></span></td>
                                                </tr>
                                                <?php
                                                $tot_qty += floatval($item['qty']);
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
        return $tens[floor($number / 10)] . (($number % 10 != 0) ? ' ' . $words[$number % 10] : '');
    } elseif ($number < 1000) {
        return $words[floor($number / 100)] . ' hundred' . (($number % 100 != 0) ? ' and ' . numberToWords($number % 100) : '');
    } elseif ($number < 1000000) {
        return numberToWords(floor($number / 1000)) . ' thousand' . (($number % 1000 != 0) ? ' ' . numberToWords($number % 1000) : '');
    } elseif ($number < 1000000000) {
        return numberToWords(floor($number / 1000000)) . ' million' . (($number % 1000000 != 0) ? ' ' . numberToWords($number % 1000000) : '');
    } elseif ($number < 1000000000000) {
        return numberToWords(floor($number / 1000000000)) . ' billion' . (($number % 1000000000 != 0) ? ' ' . numberToWords($number % 1000000000) : '');
    } elseif ($number < 1000000000000000) {
        return numberToWords(floor($number / 1000000000000)) . ' trillion' . (($number % 1000000000000 != 0) ? ' ' . numberToWords($number % 1000000000000) : '');
    } elseif ($number < 1000000000000000000) {
        return numberToWords(floor($number / 1000000000000000)) . ' quadrillion' . (($number % 1000000000000000 != 0) ? ' ' . numberToWords($number % 1000000000000000) : '');
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
                                        <tr class="no-border">
                                            <td colspan="4" style="border-bottom: 0px; text-align:center;"><strong>Grand Total</strong></td>
                                            <td style="border-bottom: 0px;" class="text-right"><b><?php echo number_format($tot_qty, 2);?></b></td>
                                            <td style="border-bottom: 0px;" class="text-right"><b><?php echo number_format($line_tot_amt, 2);?></b></td>
                                            <td style="border-bottom: 0px;" class="text-right"><b><?php echo number_format($gst_tot, 2);?></b></td>
                                            <td style="border-bottom: 0px;" class="text-right"><b><i class="fas fa-rupee-sign"></i> <span id="tot_payable"><?php echo number_format($tot_amt, 2);?></span></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-left" style="font-size: 16px;"><small>Online Link</small></td>
                                            <td colspan="3" class="text-left" style="padding: 5px;"><small><a href="" target="_blank">link</a></small></td>
                                            <td colspan="2" class="text-left" style="font-size: 16px;" ><small>Sub Total</small></td>
                                            <td class="text-right"><b><span><?php echo number_format($tot_amt, 2);?></span></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" rowspan="3">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-border-top no-border-bottom" style="padding: 5px; min-height: 100px;">
                                                    <p style="text-align: left; font-size: 16px;"><small><b>Terms &amp; Conditions:</b><br></small><span><?php echo $quotationDetails['terms_condition']?></span></p>
                                                </div>
                                            </td>
                                            <td colspan="2" class="text-left" style="font-size: 16px;"><small>GST Total</small></td>
                                            <td class="text-right"><b><span><?php echo number_format($gst_tot, 2);?></span></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-left" style="font-size: 16px;"><small>Grand Total</small></td>
                                            <td class="text-right"><b><span><?php echo number_format($tot_amt, 2);?></span></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-left" style="font-size: 16px;"><small><b>Amount: <?php echo numberToWordsFloat($tot_amt)?>.</b></small></td>
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
             <div id="shareModal" style="display: none;">
    <div class="modal-content" style="border:none !important;">
        <span id="closeModal" class="close">&times;</span>
        <h3>Compose Email</h3>
        <form id="emailForm" action="send_quotation_email.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="customer_email" id="customer_email" value="<?php echo isset($quotationDetails['customerEmail']) ? $quotationDetails['customerEmail'] : ''; ?>" />
            <input type="hidden" name="quotation_file" id="quotation_file" value="<?php echo isset($quotationDetails['quotation_file']) ? $quotationDetails['quotation_file'] : ''; ?>" />
            <label for="email">To:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($quotationDetails['customerEmail']) ? $quotationDetails['customerEmail'] : ''; ?>" readonly />
            <label for="subject">Subject:</label>
            <input type="text" class="form-control" id="subject" name="subject" value="Quotation #<?php echo $qid; ?>" />
            <label for="message">Message:</label>
            <textarea class="form-control" id="message" name="message">Dear Customer, please find attached the quotation #<?php echo $qid; ?>.</textarea>
            <button type="submit" class="btn btn-primary mt-2" name="sendMail" id="sendMail">Send Mail</button>
        </form>
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
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function() {
    var modal = document.getElementById("shareModal");
    var closeModal = document.getElementById("closeModal");

    // Function to show the popup with the customer's email
    function showPopup(customerEmail) {
        if (customerEmail) {
            document.getElementById("email").value = customerEmail;
        } else {
            document.getElementById("email").value = "";
        }
        modal.style.display = "block";
    }

    // Close the modal
    closeModal.onclick = function() {
        modal.style.display = "none";
    }

    // Event listener for the Share Email dropdown item
    document.getElementById("shareEmail").addEventListener("click", function() {
        var customerEmail = "<?php echo isset($quotationDetails['customerEmail']) ? $quotationDetails['customerEmail'] : ''; ?>";
        showPopup(customerEmail);
    });
});
</script>
        <script type="text/javascript">
           $(document).ready(function() {
        // Initialize Summernote editor
        $('#message').summernote({
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']]
            ]
        });

    
    

    // Send Email (Replace with actual sending logic)
    // $("#sendEmailBtn").click(function() {
    //     var to = $("#to").val();
    //     var subject = $("#subject").val();
    //     var message = $('#summernote').val(); // Get the content from Summernote
        
    //     if (to) {
    //         // Perform AJAX or form submission for email sending
    //         alert("Email sent successfully to " + to);
    //         $("#shareModal").hide();
    //     } else {
    //         alert("Please enter an email address.");
    //     }
    // });
});

        </script>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="assets/js/vendor-all.min.js"></script>
        <script src="assets/js/plugins/bootstrap.min.js"></script>
        <script src="assets/js/pcoded.min.js"></script>
        <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>

    </body>
</html>
