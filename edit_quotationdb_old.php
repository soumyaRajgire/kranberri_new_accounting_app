<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

error_reporting(E_ALL);
session_start(); 
if(!isset($_SESSION['name']) && $_SESSION['ROLE']!='1'){
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

if(isset($_POST['update']))
{
    include("config.php");
    include("fpdf/fpdf.php");

    $customer_name = mysqli_real_escape_string($conn,$_POST['customer_name_choice']);
    $customer_email = mysqli_real_escape_string($conn,$_POST['customer_email']);
    $cst_mstr_id = mysqli_real_escape_string($conn,$_POST['cst_mstr_id']);
    $sub_total = isset($_POST['sub_total']) ? mysqli_real_escape_string($conn,$_POST['sub_total']) : 0;
    $pack_price = isset($_POST['pack_price']) ? mysqli_real_escape_string($conn,$_POST['pack_price']) : 0;
    $total_amount = isset($_POST['total_amount']) ? mysqli_real_escape_string($conn,$_POST['total_amount']) : 0;
    $remarks = isset($_POST['remarks']) ? mysqli_real_escape_string($conn,$_POST['remarks']) : '';
    $note = isset($_POST['note']) ? mysqli_real_escape_string($conn,$_POST['note']) : '';
    $invoice_code = mysqli_real_escape_string($conn, $_POST['purchaseNo']);
    $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
    $purchaseDate = mysqli_real_escape_string($conn,$_POST['purchaseDate']);
    $terms = mysqli_real_escape_string($conn,$_POST['terms_condition']);
    $quot_id = mysqli_real_escape_string($conn,$_POST['quot_id']);

    date_default_timezone_set('Asia/Kolkata');
    $date1 = date("d-m-Y");
    $time1 = date("h:i:sa");
    $created_by = $_SESSION['name'];

    class PDF extends FPDF{
        function plot_table($widths, $lineheight, $table, $border, $aligns=array(), $fills=array(), $links=array()){
          $func = function($text, $c_width){
            $len = strlen($text);
            $twidth = $this->GetStringWidth($text);
            $split = 0;
            if ($twidth != 0 && $c_width != 0) {
                $split = floor($c_width * $len / $twidth);
                if ($split == 0) {
                    $split = 1; // Set a minimum value for split to avoid zero
                }
            }
            $w_text = explode("\n", wordwrap($text, $split, "\n", true));
            return $w_text;
        };
        
            foreach ($table as $line){
                if (!is_array($widths)) {
                    $widths = array_fill(0, count($line), $widths);
                }
                $line = array_map($func, $line, $widths);
                $maxlines = max(array_map("count", $line));
                foreach ($line as $key => $cell){
                    $x_axis = $this->getx();
                    $height = 0;
                    if (count($cell) != 0) {
                        $height = $lineheight * $maxlines / count($cell);
                    }
                    $len = count($line);
                    $width = (isset($widths[$key]) === TRUE ? $widths[$key] : $widths / count($line));
                    $align = (isset($aligns[$key]) === TRUE ? $aligns[$key] : '');
                    $fill = (isset($fills[$key]) === TRUE ? $fills[$key] : false);
                    $link = (isset($links[$key]) === TRUE ? $links[$key] : '');
                    foreach ($cell as $textline){
                        $this->cell($widths[$key],$height,$textline,0,0,$align,$fill,$link);
                        $height += 2 * $lineheight * $maxlines / count($cell);
                        $this->SetX($x_axis);
                    }
                    if($key == $len - 1){
                        $lbreak = 1;
                    }
                    else{
                        $lbreak = 0;
                    }
                    $this->cell($widths[$key],$lineheight * $maxlines, '',$border,$lbreak);
                }
            }
        }
    }

    $pdf=new PDF('P','mm','A4');
    $file_name = md5(rand()) . '.pdf';

    $pdf->AddPage();
    $pdf->SetFont("Arial","",14);

    $pdf->Image('img/logo.png', 160, 20, 25, 15); // Adjust x, y, width, and height as needed
    $pdf->Ln(28);
    $pdf->SetFont("Arial","",10);
    $pdf->SetDrawColor(0,0,0,1);
    $pdf->SetFillColor(232,232,232);
    $pdf->Cell(0,1,"",0,1,'C',true);
    $pdf->Ln(3);
    $pdf->Cell(140,6,"KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets)",0,0,'L');
    $pdf->Cell(0,6,"ESTIMATE",0,1,'L');
    $pdf->Cell(140,6,"120 Newport Center Dr, Newport Beach, CA 92660",0,0,'L');
    $pdf->Cell(0,6,"ESTIMATE #: $invoice_code",0,1,'L');
    $pdf->Cell(140,6,"Phone: 91 7550705070",0,0,'L');
    $pdf->Cell(0,6,"Date : $purchaseDate",0,1,'L');
    $pdf->Cell(140,6,"Email: sales.usa@iiiqbets.com",0,0,'L');
    $pdf->Cell(0,6,"Validity Date: $dueDate",0,1,'L');
    $pdf->Cell(140,6,"GST : 29AAICK7493G1ZX",0,0,'L');
    $pdf->Cell(0,6,"Created By : $created_by",0,1,'L');

    $pdf->Ln(3);
    $pdf->SetFont("Arial","",10);
    $pdf->SetDrawColor(0,0,0,1);
    $pdf->SetFillColor(232,232,232);
    $pdf->Cell(0,1,"",0,1,'C',true);
    $pdf->Ln(3);

    $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id WHERE customer_master.id = '$cst_mstr_id'");
    if ($row1 = mysqli_fetch_array($result1)) {
        $pdf->SetFont("Arial","B",8);
        $pdf->SetTextColor(0,0,0,1);
        $pdf->SetDrawColor(221,221,221,1);
        $pdf->SetLineWidth(0);
        $pdf->Cell(65,10,"Customer",0,0,'L');
        $pdf->Cell(65,10,"Billing Address",0,0,'L');
        $pdf->Cell(65,10,"Shipping Address",0,1,'L');

        $table = array(
            array($row1['business_name'], $row1['b_address_line1'], $row1['s_address_line1']),
            array($row1['mobile'], $row1['b_address_line2'], $row1['s_address_line2']),
            array($row1['email'], $row1['b_city']."-".$row1['b_Pincode'], $row1['s_city']."-".$row1['s_Pincode']),
            array("Place of Supply: ".$row1['b_state'], $row1['b_state'], $row1['s_state'])
        );
        $lineheight = 5;
        $fontsize = 10;
        $widths = array(65,65,65);
        $aligns = array('L','L','L');
        $border = 0;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);
    }

    $pdf->Ln(20);
    $pdf->SetFont("Arial","B",8);
    $pdf->SetTextColor(0,0,0,0);
    $pdf->SetDrawColor(0,0,0,0);
    $pdf->SetLineWidth(0);

    $pdf->Cell(6,10,"#",1,0,'C');
    $pdf->Cell(78,10,"Product Description",1,0,'C');
    $pdf->Cell(12,10,"GST",1,0,'C');
    $pdf->Cell(15,10,"RATE",1,0,'C');
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
    $nontax_tot_amt = 0;

    foreach($_POST['products'] as $product) {
        $tot = 0;
        $cgsttotal = 0;
        $sgsttotal = 0;
        $gsttot = 0;
        $nontax_amt = 0;

        $attr_id = mysqli_real_escape_string($conn,$product['attr_id']);
        $itemnum = mysqli_real_escape_string($conn, $product['pitemno']);
        $productsname = mysqli_real_escape_string($conn, $product['pname']);
        $proddesc = mysqli_real_escape_string($conn, $product['pdesc']);
        $qtyvalue = mysqli_real_escape_string($conn, $product['pqty']);
        $priceval = mysqli_real_escape_string($conn, $product['pprice']);
        $gstval = mysqli_real_escape_string($conn, $product['pgst']);
        $cgstval = mysqli_real_escape_string($conn, $product['pcgst']);
        $sgstval = mysqli_real_escape_string($conn, $product['psgst']);
        $netprice = mysqli_real_escape_string($conn, $product['pnetprice']);
        $total = mysqli_real_escape_string($conn, $product['ptotal']);
        $productid = mysqli_real_escape_string($conn, $product['pproductid']);
        $in_ex_gst = mysqli_real_escape_string($conn, $product['pin_ex_gst']);

        $cgsttotal += floatval($cgstval); 
        $sgsttotal += floatval($sgstval); 
        $pricevaltot += floatval($priceval); 
        $tot_total += floatval($total);
        $tot = floatval($total) + floatval($cgstval) + floatval($sgstval);
        $tot_formatted = number_format($tot, 0, '.', ''); 

        $gsttot = floatval($cgsttotal) + floatval($sgsttotal);
        $tot_qty += intval($qtyvalue);
        if($in_ex_gst === "inclusive of GST") {
            $nontax_amt = floatval($priceval) / (1 + (floatval($gstval) / 100));
        } else {
            $nontax_amt = floatval($priceval);
        }
        $nontax_tot_amt += floatval($nontax_amt); 

        $table = array(array($itemnum,$productsname."\n". $proddesc,$gstval,$priceval, $qtyvalue, $priceval,$cgstval,$sgstval,$tot));
        $lineheight = 7;
        $fontsize = 10;
        $widths = array(7,92,14,20,12,20,24);
        $aligns = array('C','L','C','C','C','C','C');
        $border = 1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);

        if($attr_id == 0) {
            $result2 = mysqli_query($conn,"SELECT id FROM quotation_items WHERE id=(SELECT max(id) FROM quotation_items)");
            if($row2 = mysqli_fetch_array($result2)) {
                $id1 = $row2['id'] + 1;
            }

            $sql4 = "INSERT INTO quotation_items (id, itemno, quotation_id, product_id, product, prod_desc, qty, price, line_total, gst, gst_amt, total, created_by) VALUES ('$id1', '$itemnum', '$quot_id', '$productid', '$productsname', '$proddesc', '$qtyvalue', '$priceval', '$line_tot', '$gstval', '$gstamt', '$tot_formatted', '$created_by')";
            if ($conn->query($sql4) === TRUE) {
                echo "Inserted";
            } else {
                echo "Error: " . $sql4 . "<br>" . $conn->error;
            }
        } elseif($attr_id > 0) {
            mysqli_query($conn,"UPDATE quotation_items SET itemno='$itemnum', quotation_id='$quot_id', product_id='$productid', product='$productsname', prod_desc='$proddesc', qty='$qtyvalue', price='$priceval', line_total='$line_tot', gst='$gstval', gst_amt='$gstamt', total='$tot_formatted' WHERE id='$attr_id'");
        }
    }

    $gsttot = $cgsttotal + $sgsttotal;
    $tot_amt = $gsttot + $tot_total;
    $pdf->Cell(111,10,"Grand Total",1,0,'C');
    $pdf->Cell(10,10,"$tot_qty",1,0,'C');
    $pdf->Cell(19,10,"$pricevaltot",1,0,'C');
    $pdf->Cell(15,10,"$cgsttotal",1,0,'C');
    $pdf->Cell(15,10,"$sgsttotal",1,0,'C');
    $pdf->Cell(21,10,"$tot_amt",1,1,'C');

    $pdf->SetFont("Arial","",8);
    $pdf->Cell(34,10,"Bank Name",1,0,'L');
    $pdf->Cell(77,10,"IDFC BANK LIMITED ",1,0,'L');
    $pdf->Cell(59,10,"Sub total",1,0,'C');
    $pdf->Cell(21,10,"$pricevaltot",1,1,'C');

    $pdf->Cell(34,10,"Account Name",1,0,'L');
    $pdf->Cell(77,10,"KRIKA MKB CORPORATION PRIVATE LIMITED",1,0,'L');
    $pdf->Cell(59,10,"GST total",1,0,'C');
    $pdf->Cell(21,10,"$gsttot",1,1,'C');

    $pdf->Cell(34,10,"Account No",1,0,'L');
    $pdf->Cell(77,10,"10069839667",1,0,'L');
    $pdf->Cell(59,10,"Grand total",1,0,'C');
    $pdf->Cell(21,10,"$tot_amt",1,1,'C');

    $pdf->Cell(34, 10, "IFSC Code", 1, 0, 'L');
    $pdf->Cell(77, 10, "IDFB0080177", 1, 0, 'L');
    $pdf->SetFont("Arial","B",);

    $pdf->MultiCell(80, 10, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 1, 'L');
    $pdf->SetY($pdf->GetY() - 20); 

    if (empty($terms)) {
        $terms = " ";
    }
    $pdf->MultiCell(111, 10, "Terms and Condition:\n$terms", 1, 'L');

    $pdf->Cell(0,10,"Thank you for your Business!",0,1,'C');

    ob_end_clean();
    $separator = md5(time());
    $eol = PHP_EOL;
    $filename = "pdf/".$file_name;
    $pdfdoc = $pdf->Output('S');

    $sql2 = "UPDATE `quotation` SET `quotation_file`='$filename', `quotation_date`='$purchaseDate', `due_date`='$dueDate', `total_amount`='$pricevaltot',`total_tax`='$gsttot', `grand_total`='$tot_amt',  `terms_condition`='$terms', `note`='$note',`status`='Not Converted' WHERE invoice_code='$invoice_code'";
    if ($conn->query($sql2) === TRUE) {
        ?>
        <script>
            alert("Successfully Created Quotation");
        </script> 
        <?php
    } else {
        ?>
        <script>
            alert("Unable to create Quotation, try again");
            <?php echo "Error: " . $sql2 . "<br>" . $conn->error; ?>
        </script> 
        <?php
    }
}
?>
