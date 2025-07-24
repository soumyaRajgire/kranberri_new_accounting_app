<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

session_start(); 
if(!isset($_SESSION['name']) && $_SESSION['ROLE'] != '1') {
   // Check if the user is logged in
// if(!isset($_SESSION['LOG_IN'])){
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

if(isset($_POST['submit'])) {
    include("fpdf/fpdf.php");

    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id']);
    $sub_total = floatval(mysqli_real_escape_string($conn, $_POST['sub_total']));
    $pack_price = floatval(mysqli_real_escape_string($conn, $_POST['pack_price']));
    $total_amount = floatval(mysqli_real_escape_string($conn, $_POST['total_amount']));
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    $invoice_code = mysqli_real_escape_string($conn, $_POST['purchaseNo']);
    $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
    $purchaseDate = mysqli_real_escape_string($conn, $_POST['purchaseDate']);
    $terms = mysqli_real_escape_string($conn, $_POST['terms_condition']);
    $created_by = $_SESSION['name'];

    date_default_timezone_set('Asia/Kolkata');
    $date1 = date("d-m-Y");
    $time1 = date("h:i:sa");

    // Generate ID for the new quotation
    $result1 = mysqli_query($conn, "SELECT id FROM quotation ORDER BY id DESC LIMIT 1");
    $id = ($row1 = mysqli_fetch_array($result1)) ? $row1['id'] + 1 : 1;

    // Generate PDF using FPDF
    class PDF extends FPDF {
        function plot_table($widths, $lineheight, $table, $border, $aligns = array(), $fills = array(), $links = array()) {
            foreach ($table as $line) {
                foreach ($line as $key => $cell) {
                    $this->Cell($widths[$key], $lineheight, $cell, $border, 0, $aligns[$key] ?? '', $fills[$key] ?? false, $links[$key] ?? '');
                }
                $this->Ln();
            }
        }
    }

    $pdf = new PDF('P', 'mm', 'A4');
    $file_name = md5(rand()) . '.pdf';
    $pdf->AddPage();
    $pdf->SetFont("Arial", "", 14);
    $pdf->Image('img/logo.png', 160, 20, 25, 15);
    $pdf->Ln(28);
    $pdf->SetFont("Arial", "", 10);
    $pdf->Cell(0, 6, "KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)", 0, 1, 'L');
    $pdf->Cell(0, 6, "ESTIMATE #: $invoice_code", 0, 1, 'L');
    $pdf->Cell(0, 6, "Date : $purchaseDate", 0, 1, 'L');
    $pdf->Cell(0, 6, "Validity Date: $dueDate", 0, 1, 'L');
    $pdf->Cell(0, 6, "Created By : $created_by", 0, 1, 'L');
    $pdf->Ln(3);

    // Customer, Billing, and Shipping Information
    $pdf->SetFont("Arial", "B", 8);
    $pdf->Cell(65, 10, "Customer", 0, 0, 'L');
    $pdf->Cell(65, 10, "Billing Address", 0, 0, 'L');
    $pdf->Cell(65, 10, "Shipping Address", 0, 1, 'L');

    $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id WHERE customer_master.id = '$cst_mstr_id'");
    if($row1 = mysqli_fetch_array($result1)) {
        $table = array(
            array($row1['business_name'], $row1['b_address_line1'], $row1['s_address_line1']),
            array($row1['mobile'], $row1['b_address_line2'], $row1['s_address_line2']),
            array($row1['email'], $row1['b_city']."-".$row1['b_Pincode'], $row1['s_city']."-".$row1['s_Pincode']),
            array("Place of Supply: ".$row1['b_state'], $row1['b_state'], $row1['s_state'])
        );
        $widths = array(65, 65, 65);
        $pdf->plot_table($widths, 5, $table, 0, array('L','L','L'));
    }

    $pdf->Ln(20);
    $pdf->Cell(6, 10, "#", 1, 0, 'C');
    $pdf->Cell(78, 10, "Product Description", 1, 0, 'C');
    $pdf->Cell(12, 10, "GST", 1, 0, 'C');
    $pdf->Cell(15, 10, "RATE", 1, 0, 'C');
    $pdf->Cell(10, 10, "QTY", 1, 0, 'C');
    $pdf->Cell(19, 10, "Taxable Amt", 1, 0, 'C');
    $pdf->Cell(15, 10, "CGST", 1, 0, 'C');
    $pdf->Cell(15, 10, "SGST", 1, 0, 'C');
    $pdf->Cell(21, 10, "TOTAL", 1, 1, 'C'); 

    $cgsttotal = 0;
    $sgsttotal = 0;
    $pricevaltot = 0;
    $tot_total = 0;
    $tot_qty = 0;

    foreach ($_POST["products"] as $key => $val) {
        $qtyvalue = floatval(mysqli_real_escape_string($conn, $_POST['qtyvalue'][$key])); 
        $priceval = floatval(mysqli_real_escape_string($conn, $_POST['priceval'][$key])); 
        $gstval = floatval(mysqli_real_escape_string($conn, $_POST['gstval'][$key])); 
        $cgstval = $gstval / 2; 
        $sgstval = $gstval / 2; 
        $total = $priceval * $qtyvalue;
        $netprice = $total + $gstval;

        $cgsttotal += $cgstval;
        $sgsttotal += $sgstval;
        $pricevaltot += $total;
        $tot_total += $total;

        $table = array(array($key + 1, $val, $gstval, $priceval, $qtyvalue, $total, $cgstval, $sgstval, $netprice));
        $widths = array(6, 78, 12, 15, 10, 19, 15, 15, 21);
        $pdf->plot_table($widths, 7, $table, 1, array('C','L','C','C','C','C','C','C','C'));

        $line_tot = $qtyvalue * $priceval;
        $gstamt = ($gstval / 2) * $total / 100;

        mysqli_query($conn, "INSERT INTO `quotation_items` (`id`, `itemno`, `quotation_id`, `product_id`, `product`, `prod_desc`, `qty`, `price`, `line_total`, `gst`, `gst_amt`, `total`, `created_by`) VALUES (NULL, '$key', '$id', '$productid', '$val','$proddesc','$qtyvalue','$priceval', '$line_tot', '$gstval', '$gstamt', '$netprice', '$created_by')");
    }

    $gsttot = $cgsttotal + $sgsttotal;
    $tot_amt = $gsttot + $tot_total;

    $pdf->Cell(111, 10, "Grand Total", 1, 0, 'C');
    $pdf->Cell(10, 10, "$tot_qty", 1, 0, 'C');
    $pdf->Cell(19, 10, "$pricevaltot", 1, 0, 'C');
    $pdf->Cell(15, 10, "$cgsttotal", 1, 0, 'C');
    $pdf->Cell(15, 10, "$sgsttotal", 1, 0, 'C');
    $pdf->Cell(21, 10, $tot_amt, 1, 1, 'C');

    $filename = "pdf/".$file_name;
    $pdfdoc = $pdf->Output('S');
    file_put_contents($filename, $pdfdoc);

    // $sql = "INSERT INTO `quotation` (`id`, `invoice_code`, `quotation_file`, `customer_id`, `email`, `quotation_date`, `due_date`, `total_amount`, `total_tax`, `grand_total`, `terms_condition`, `note`, `status`, `created_by`) VALUES ('$id', '$invoice_code','$filename','$cst_mstr_id', '$customer_email', '$purchaseDate', '$dueDate', '$pricevaltot', '$gsttot','$tot_amt', '$terms', '$note','Not Converted', '$created_by')";

    if ($conn->query($sql) === TRUE) {
        ?>
        <script>
            window.location = "view-quotation.php";
            alert("Successfully Created Quotation");
        </script>
        <?php
    } else {
        ?>
        <script>
            window.location = "quotation.php";
            alert("Unable to create Quotation, try again");
        </script>
        <?php
    }
}
?>
