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

if (!isset($_GET['qid']) || empty($_GET['qid'])) {
    die("Error: Quotation ID is missing.");
}

$qid = $_GET['qid'];

function getQuotationDetails($conn, $qid) {
    $quotationId = $conn->real_escape_string($qid);

    $query = "SELECT q.*, c.*, a.*, qi.*, im.in_ex_gst, im.net_price, im.gst_rate, im.price 
              FROM quotation q
              JOIN customer_master c ON q.customer_id = c.id
              JOIN address_master a ON c.id = a.customer_master_id
              JOIN quotation_items qi ON q.id = qi.quotation_id
              JOIN inventory_master im ON qi.product_id = im.id
              WHERE q.id = '$quotationId'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $quotationData = $result->fetch_assoc();
        $quotationItems = [];
        foreach ($result as $row) {
            $netPriceArray = explode('|', $row['net_price']);

            $quotationItems[] = [
                'itemnum' => $row['itemno'],
                'product' => $row['product'],
                'prod_desc' => $row['prod_desc'],
                'price' => $row['price'],
                'qty' => $row['qty'],
                'line_total' => $row['line_total'],
                'total' => $row['total'],
                'gst_amt' => $row['gst_amt'],
                'gst' => $row['gst'],
                'in_ex_gst' => $row['in_ex_gst'],
                'net_price' => $netPriceArray[0],
                'product_id' => $row['product_id'],
                'quotation_items_id' => $row['id'],
            ];
        }

        $quotationData['quotation_items'] = $quotationItems;
        return $quotationData;
    } else {
        return false;
    }
}

$quotationDetails = getQuotationDetails($conn, $qid);
if (!$quotationDetails) {
    die("Error: Quotation not found.");
}

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

<!DOCTYPE html>
<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php");?>
    <style>
        .text-grey {
            color: grey; 
            background-color: white;
        }
        .tooltip-inner  {
            background-color: white;
            color: grey; 
            border-radius: 2px;
            font-size: 13px;
        }
        h5 {
            font-size: 13px !important;
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
                                <h4 class="m-b-10">View Quotation</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">View Quotation</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>View Quotation Details</h5>
                        <a href="create-estimate.php" class="btn btn-info" style="color: #fff !important; float: right;">Create</a>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="panel panel-default" style="border-color:#676767 !important">
                                        <div class="panel-body" style="padding:10px;">
                                            <div class="row">
                                                <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 text-left">
                                                    <h5 class="line-height-70"><b id="seller_name"><?php echo $quotationDetails['customerName']; ?></b></h5>
                                                    <h5 id="seller_add_1" class="line-height-70"><?php echo $quotationDetails['s_address_line1']; ?></h5>
                                                    <h5 id="seller_add_2" class="line-height-70"><?php echo $quotationDetails['s_address_line2']; ?></h5>
                                                    <h5 id="seller_add_3" class="line-height-70"><?php echo $quotationDetails['s_city'] . " - " . $quotationDetails['s_Pincode']; ?></h5>
                                                    <h5 id="seller_email" class="line-height-70"> Email: <?php echo $quotationDetails['email']; ?> </h5>
                                                    <h5 id="seller_mobile" class="line-height-70">Phone: <?php echo $quotationDetails['phone']; ?></h5>
                                                </div>
                                                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-right">
                                                    <h4 class="line-height-70" style="margin-top: 5px;">ESTIMATE</h4>
                                                    <h5 class="line-height-70"><b>ESTIMATE #: <span id="inv_no"><?php echo $quotationDetails['invoice_code']; ?></span></b></h5>
                                                    <h5 class="line-height-70">Date: <span id="inv_date"><?php echo $quotationDetails['quotation_date']; ?></span></h5>
                                                    <h5 class="line-height-70">Validity: <span id="inv_due_date"><?php echo $quotationDetails['due_date']; ?></span></h5>
                                                    <p id="inv_cancel_status" style="display:none;">0</p>
                                                    <p id="inv_delete_status" style="display:none;">0</p>
                                                    <p id="inv_added_by">Created By: <?php echo $quotationDetails['created_by']; ?></p>
                                                </div>
                                            </div>
                                            <hr style="margin-top: 11px; margin-bottom: 0px; color: black; border-color: #676767;">
                                            <div class="row">
                                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-left">
                                                    <h5><b>Customer</b></h5>
                                                    <h6 style="font-size: 14px;"><span class="" id="cust_name"><?php echo $quotationDetails['customerName']; ?></span></h6>
                                                    <h6><span class="line-height-70" id="cust_name"><?php echo $quotationDetails['email']; ?></span></h6>
                                                    <h6 style="font-size: 14px;"><span class=""><span>GSTIN: </span><?php echo $quotationDetails['gstin']; ?></span></h6>
                                                    <h6><span class="" id="cust_supply_state"><span>Place of Supply: </span><?php echo $quotationDetails['s_state']; ?></span></h6>
                                                </div>
                                            </div>
                                            <div class="row" style="padding: 1px;">
                                                <div id="charges_div" class="col-xs-12 col-md-12 col-lg-12">
                                                    <table class="table-responsive table-condensed table table-bordered" style="font-size: 15px;">
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
                                                            <?php
                                                            $total_qty = 0;
                                                            $line_total_amt = 0;
                                                            $gst_total = 0;
                                                            $total_amt = 0;

                                                            if (isset($quotationDetails['quotation_items']) && !empty($quotationDetails['quotation_items'])) {
                                                                foreach ($quotationDetails['quotation_items'] as $index => $item) {
                                                                    $total_qty += $item['qty'];
                                                                    $line_total_amt += $item['line_total'];
                                                                    $gst_total += $item['gst_amt'];
                                                                    $total_amt += $item['line_total'] + $item['gst_amt'];
                                                                    echo "<tr>
                                                                        <td><small>" . ($index + 1) . "</small></td>
                                                                        <td colspan='2' class='text-left description'><span><small>{$item['product']}</small></span><br><small style='padding: 0px; margin: 0px; font-size: 10px;'>{$item['prod_desc']}</small></td>
                                                                        <td><small>{$item['price']}</small></td>
                                                                        <td><small>{$item['qty']}</small></td>
                                                                        <td><span><small>{$item['line_total']}</small></span></td>
                                                                        <td><span><small>{$item['gst_amt']}</small></span></td>
                                                                        <td><span><small>{$item['total']}</small></span></td>
                                                                    </tr>";
                                                                }
                                                            } else {
                                                                echo "<tr><td colspan='8'>No quotation items found</td></tr>";
                                                            }
                                                            ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="no-border">
                                                                <td colspan="4" style="border-bottom: 0px;"><b>Grand Total</b></td>
                                                                <td style="border-bottom: 0px;" class="text-right"><b><?php echo $total_qty; ?></b></td>
                                                                <td style="border-bottom: 0px;" class="text-right"><b><?php echo number_format($line_total_amt, 2); ?></b></td>
                                                                <td style="border-bottom: 0px;" class="text-right"><b><?php echo number_format($gst_total, 2); ?></b></td>
                                                                <td style="border-bottom: 0px;" class="text-right"><b><?php echo number_format($total_amt, 2); ?></b></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="text-left"><small>Online Link</small></td>
                                                                <td colspan="3" class="text-left" style="padding: 5px;"><small><a href="https://ledgr.in/" target="_blank">ledgr.in/H3WUXb_U4RVr</a></small></td>
                                                                <td colspan="1" class="text-left"><small>Sub Total</small></td>
                                                                <td class="text-right"><b><?php echo number_format($line_total_amt, 2); ?></b></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="5" rowspan="3">
                                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-border-top no-border-bottom" style="padding: 5px; min-height: 100px;">
                                                                        <p style="text-align: left;"><small><b>Terms & Conditions:</b><br><?php echo $quotationDetails['terms_condition']; ?></small></p>
                                                                    </div>
                                                                </td>
                                                                <td colspan="1" class="text-left"><small>GST Total</small></td>
                                                                <td class="text-right"><b><?php echo number_format($gst_total, 2); ?></b></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="1" class="text-left"><small>Grand Total</small></td>
                                                                <td class="text-right"><b><?php echo number_format($total_amt, 2); ?></b></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class="text-left"><small><b>Amount: <?php echo numberToWordsFloat($total_amt); ?> only.</b></small></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                    <hr style="margin-top: 1px; margin-bottom: 0px; color: black; border-color: #000;">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <p class="text-center" style="padding-top: 10px;">Thank you for your business!</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>        
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12 desk_payment no-print" style="display: none;">
                                    <div class="panel panel-default" style="box-shadow: 0px 10px 13px rgba(0,0,0,.05); border: 2px solid #ddd;">
                                        <div class="panel-body" style="padding: 0px 0px 0px 0px;">
                                            <div class="row">
                                                <div class="col-md-12 pay_col" style="position: relative; margin-top: 0px; padding-bottom: 5px;">                          
                                                    <h6 class="pay_header"><span>Customer</span></h6>
                                                    <span style="position: absolute; top: 5px; right: 8px;">
                                                        <div class="dropdown drop_opt">
                                                            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 3px 3px; line-height: 0.5;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                                        <circle fill="#5D78FF" cx="12" cy="5" r="2"></circle>
                                                                        <circle fill="#5D78FF" cx="12" cy="12" r="2"></circle>
                                                                        <circle fill="#5D78FF" cx="12" cy="19" r="2"></circle>
                                                                    </g>
                                                                </svg>
                                                            </button>
                                                            <ul class="dropdown-menu drop_menu_sec dropdown-menu-right">
                                                                <li><a href="javascript:;" class="resend_whatsapp_btn" data-action="share/whatsapp/share">Whatsapp Estimate</a></li>
                                                                <li><a href="javascript:;" id="save_btn">Download Estimate</a></li>
                                                                <li><a href="javascript:;" id="print_btn">Print Estimate</a></li>
                                                                <li><a href="javascript:;" class="resend_mail_btn">Email Estimate</a></li>
                                                            </ul>
                                                        </div>
                                                    </span>
                                                    <table class="table py_tb">
                                                        <tbody>
                                                            <tr>
                                                                <th style="padding-bottom: 0px;"><?php echo $quotationDetails['customerName']; ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th style="padding-bottom: 0px;"><?php echo $quotationDetails['email']; ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th style="padding-bottom: 0px;"><?php echo $quotationDetails['phone']; ?></th>
                                                            </tr> 
                                                        </tbody>
                                                    </table>                            
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(window).bind("load", function () {
            var charges_ht = $("#charges_div").height();
            var notes_ht = $("#notes_div").height(178);
        });

        // SEND WHATSAPP AND EMAIL FUNCTION
        $('.resend_whatsapp_btn').on('click', function(){
            var phone = '<?php echo $quotationDetails['phone']; ?>';
            var url = window.location.href;
            window.open("https://web.whatsapp.com/send?phone=" + phone + "&text=" + encodeURIComponent(url), "_blank");            
        });

        $('.resend_mail_btn').on('click', function(){
            var url = window.location.href;
            var email = "<?php echo $quotationDetails['email']; ?>";
            var inv_num = '<?php echo $quotationDetails['invoice_code']; ?>';
            window.location = 'mailto:' + email + '?subject=Invoice - ' + inv_num + '&body=' + encodeURIComponent(url);               
        });
    </script>

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });

        $('#dataTables-example').dataTable({
            "orderFixed": [3, 'asc']
        });
    </script>
</body>
</html>
