<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

session_start();
// if (!isset($_SESSION['name']) && $_SESSION['ROLE'] != '1') {
//     header("Location:login.php");
//     exit();
// } else {
//     $_SESSION['url'] = $_SERVER['REQUEST_URI'];
// }
if (!isset($_SESSION['name']) && $_SESSION['ROLE'] != '1') {
    header("Location:login.php");
    exit();
}

// Check if a business is selected
// if (!isset($_SESSION['business_id'])) {
//     header("Location:dashboard.php");
//     exit();
// } else {
//     $_SESSION['url'] = $_SERVER['REQUEST_URI'];
//     $business_id = $_SESSION['business_id'];
//     if (isset($_SESSION['branch_id'])) {
//         $branch_id = $_SESSION['branch_id'];
//     }
// }

include("config.php");
include("fpdf/fpdf.php");

// Display all PHP errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Capture POST data and validate
$customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice'] ?? '');
$customer_email = mysqli_real_escape_string($conn, $_POST['customer_email'] ?? '');
$cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id'] ?? '');
$pack_price = mysqli_real_escape_string($conn, $_POST['pack_price'] ?? '0');
$total_amount = mysqli_real_escape_string($conn, $_POST['total_amount'] ?? '0');
$note = mysqli_real_escape_string($conn, $_POST['note'] ?? '');
$bill_code = mysqli_real_escape_string($conn, $_POST['billNo'] ?? '');
$billDate = mysqli_real_escape_string($conn, $_POST['billDate'] ?? date('Y-m-d'));
$dueDate = mysqli_real_escape_string($conn, $_POST['dueDate'] ?? date('Y-m-d'));
$terms = mysqli_real_escape_string($conn, $_POST['terms_condition'] ?? '');
$created_by = $_SESSION['name'] ?? '';
$branch_id = mysqli_real_escape_string($conn, $_POST['branch_id'] ?? '');
$id = "";

// Initialize PDF only if customer_name is provided
if ($customer_name != "") {

    // Get max ID for new credit note entry
    $result1 = mysqli_query($conn, "SELECT MAX(id) as max_id FROM bill_of_supply");
    if ($row1 = mysqli_fetch_assoc($result1)) {
        $id = $row1['max_id'] + 1;
    } else {
        $id = 1;
    }

    // PDF Generation Class
    class PDF extends FPDF {
        function plot_table($widths, $lineheight, $table, $border, $aligns = array(), $fills = array(), $backgroundColors = array(), $links = array()) {
            $func = function($text, $c_width) {
                $len = strlen($text ?? '');
                $twidth = $this->GetStringWidth($text ?? '');
                $split = ($twidth != 0) ? floor($c_width * $len / $twidth) : 1;
                return explode("\n", wordwrap($text ?? '', $split, "\n", true));
            };

            foreach ($table as $line) {
                $line = array_map($func, $line, $widths);
                $maxlines = max(array_map("count", $line));

                foreach ($line as $key => $cell) {
                    $x_axis = $this->GetX();
                    $height = (count($cell) != 0) ? $lineheight * $maxlines / count($cell) : 0;
                    $width = isset($widths[$key]) ? $widths[$key] : $widths / count($line);
                    $align = isset($aligns[$key]) ? $aligns[$key] : '';
                    $fill = isset($fills[$key]) ? $fills[$key] : false;
                    $link = isset($links[$key]) ? $links[$key] : '';
                    $backgroundColor = isset($backgroundColors[$key]) ? $backgroundColors[$key] : '';

                    if (!empty($backgroundColor)) {
                        $this->SetFillColor($backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
                        $this->Rect($this->GetX(), $this->GetY(), $width, $height, 'F');
                    }

                    foreach ($cell as $textline) {
                        $this->Cell($width, $height, $textline, 0, 0, $align, $fill, $link);
                        $height += 2 * $lineheight * $maxlines / count($cell);
                        $this->SetX($x_axis);
                    }

                    $lbreak = ($key == count($line) - 1) ? 1 : 0;
                    $this->Cell($width, $lineheight * $maxlines, '', $border, $lbreak);
                }
            }
        }
    }

    $pdf = new PDF('P', 'mm', 'A4');
    $file_name = md5(rand()) . '.pdf';
    $filename = "bill_of_supply/" . $file_name;

    $pdf->AddPage();
    $pdf->SetFont("Arial", "", 10);
    $pdf->SetFillColor(232, 232, 232);

    // Header
    $pdf->SetFont('Arial', '', 9);
    $table = array(array("img/avatar.png","\n KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets) \n Skyline Beverly Park, # D 402, Amruthahalli Main Road, Amruthahalli,Amruthal,Bangalore - 560092, \n KARNATAKA \nEmail: abhijith.mavatoor@gmail.com, Phone: 9481024700 \n GSTIN: 29AAICK7493G1ZX \n"));
   $lineheight = 4;
   $fontsize = 10;
   $aligns = array('C','C');
   $widths = array(35,154);
   $border=1;
   $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

    // Title
    $table = array(array("GST Bill of Supply"));
    $pdf->plot_table(array(189), 8, $table, 1, array('C'), array(array(255, 200, 200)));

    // Address Details
    $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");
    if ($row1 = mysqli_fetch_array($result1)) {
        $table = array(array(
            "\n Billing Address \n\n {$row1['b_address_line1']} \n {$row1['b_address_line2']} \n {$row1['b_city']} - {$row1['b_Pincode']} \n {$row1['b_state']} \n",
            "\n Shipping Address \n\n {$row1['s_address_line1']} \n {$row1['s_address_line2']} \n {$row1['s_city']} - {$row1['s_Pincode']} \n {$row1['s_state']} \n"
        ));
        $pdf->plot_table(array(94.5, 94.5), 5, $table, 1, array('L', 'L'));

        $pdf->plot_table(array(47.25, 47.25, 47.25, 47.25), 9, array(
            array("Bill of Supply Number", "$bill_code", "Place of Supply", "{$row1['s_state']}"),
            array("Bill of Supply Date", "$billDate", "Created By", "$created_by"),
            array("Bill of Supply Type", "Original")
        ), 1, array('L', 'L', 'L', 'L'));
    }

    // Product Details Header
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Cell(7, 10, "Slno", 1, 0, 'C', 1);
    $pdf->Cell(92.3, 10, "Product Description", 1, 0, 'C', 1);
    $pdf->Cell(14, 10, "GST", 1, 0, 'C', 1);
    $pdf->Cell(20, 10, "RATE", 1, 0, 'C', 1);
    $pdf->Cell(12, 10, "QTY", 1, 0, 'C', 1);
    $pdf->Cell(20, 10, "GST", 1, 0, 'C', 1);
    $pdf->Cell(24, 10, "TOTAL", 1, 1, 'C', 1);

    $conn->begin_transaction();
    try {
        // Insert `credit_note`
        $sql = "INSERT INTO bill_of_supply (id, billNo, bill_file, customer_name, email, billDate, dueDate, total_amount, terms_condition, note, status, created_by, branch_id) 
        VALUES ('$id', '$bill_code', '$filename', '$customer_name', '$customer_email', '$billDate', '$billDate', '$total_amount', '$terms', '$note', 'pending', '$created_by', '$branch_id')";

        $conn->query($sql);

        // Product Details Rows
        $slno = 1;
        foreach ($_POST["products"] as $key => $val) {
            $products = mysqli_real_escape_string($conn, $_POST['products'][$key] ?? '');
            $proddesc = mysqli_real_escape_string($conn, $_POST['proddesc'][$key] ?? '');
            $qtyvalue = mysqli_real_escape_string($conn, $_POST['qtyvalue'][$key] ?? '0');
            $priceval = mysqli_real_escape_string($conn, $_POST['priceval'][$key] ?? '0');
            $gstval = mysqli_real_escape_string($conn, $_POST['gstval'][$key] ?? '0');
            $cgstval = mysqli_real_escape_string($conn, $_POST['cgstval'][$key] ?? '0');
            $sgstval = mysqli_real_escape_string($conn, $_POST['sgstval'][$key] ?? '0');
            $total = mysqli_real_escape_string($conn, $_POST['total'][$key] ?? '0');

            $table = array(array($slno++, $products . "\n" . $proddesc, $gstval, $priceval, $qtyvalue, (float)$cgstval + (float)$sgstval, $total));
            $pdf->plot_table(array(7, 92, 14, 20, 12, 20, 24), 7, $table, 1, array('C', 'L', 'C', 'C', 'C', 'C', 'C'));

            $conn->query("INSERT INTO billsupply_items (`bill_id`, `product_name`, `prod_desc`, `qty`, `price`, `line_total`, `gst`, `gst_amt`, `total`, `created_by`) 
                          VALUES ('$id', '$products','$proddesc','$qtyvalue','$priceval', '$total', '$gstval', '" . (float)$cgstval + (float)$sgstval . "', '$total', '$created_by')");
        }
        $gsttot=($cgsttotal + $sgsttotal);
        $tot_amt = $gsttot + $tot_total;
        
        $pdf->Cell(150, 6, "Nontaxable Amount", 'L', 0, 'R');
        $pdf->Cell(39, 6, "$nontax_tot_amt",'R', 1, 'R');
        
        $pdf->Cell(150, 6, "Taxable Amount", 'L', 0, 'R');
        $pdf->Cell(39, 6, "$pricevaltot",'R', 1, 'R');
        
        $pdf->Cell(150, 6, "GST Total", 'L', 0, 'R');
        $pdf->Cell(39, 6, "$gsttot",'R', 1, 'R');
        
        $pdf->Cell(150, 6, "Adjusment", 'L', 0, 'R');
        $pdf->Cell(39, 6, "0",'R', 1, 'R');
        
        $pdf->Cell(120,6,"Amount in words",'BL',0,'L');
        $pdf->Cell(30, 6, "Bill of Supply Total", 'B', 0, 'R');
        $pdf->Cell(39, 6, "INR $tot_amt",'BR', 1, 'R');

        $pdf->SetFont("Arial","B",8);
        
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        
        $pdf->Cell(27, 6, "Bank Name", 'L', 0, 'L');
        $pdf->Cell(66, 6, "IDFC BANK LIMITED",'R', 0, 'L');
        $pdf->MultiCell(96,4,"Note : $note",'TR',1,'L');
         
         $pdf->SetXY($x , $y + 6);
        
        $pdf->Cell(27, 6, "Account Name", 'L', 0, 'L');
        $pdf->Cell(66, 6, "KRIKA MKB CORPORATION PRIVATE LIMITED",'R', 0, 'L');
        $pdf->Cell(96,6,"",'R',1,'L');
        
        $pdf->Cell(27, 6, "Account No", 'L', 0, 'L');
        $pdf->Cell(66, 6, "10069839667",'R', 0, 'L');
        $pdf->Cell(96,6,"",'R',1,'L');
        
        $pdf->Cell(27, 6, "IFSC Code", 'BL', 0, 'L');
        $pdf->Cell(66, 6, "IDFB0080177",'BR', 0, 'L');
        $pdf->Cell(96,6,"",'BR',1,'L');
        
        $pdf->SetFont("Arial","B",);
        
        
        if (empty($terms)) {
            $terms = " "; // Set a space to ensure the cell has some content
        }
        $startY = $pdf->GetY();
        $currentX = $pdf->GetX();
        
        // First MultiCell
        $pdf->MultiCell(100, 6, "Terms and Condition:\n$terms", 0, 'L');
        $endYFirst = $pdf->GetY();
        
        $pdf->SetXY($currentX + 100, $startY);
        $pdf->MultiCell(89, 6, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 0, 'L');
        $endYSecond = $pdf->GetY();
        
        $maxY = max($endYFirst, $endYSecond);
        
        $pdf->Rect($currentX, $startY, 100, $maxY - $startY, 'L');
        $pdf->Rect($currentX + 100, $startY, 89, $maxY - $startY, 'R');
        $pdf->SetY($maxY);

        $pdf->SetFont("Arial", "B", 8);
        $pdf->Cell(0, 10, "Thank you for your Business!", 1, 1, 'C');

        $pdfdoc = $pdf->Output('S');
        file_put_contents($filename, $pdfdoc);

        $conn->commit();
       
        echo "<script>
        window.location.href = 'manage-billsupply.php';
        alert('Successfully Created Bill Of Supply');
    </script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error creating Bill of Supply: " . addslashes($e->getMessage()) . "');</script>";
    }
    
}
?>
