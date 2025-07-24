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
$cnote_code = mysqli_real_escape_string($conn, $_POST['cnote_code'] ?? '');
$cnoteDate = mysqli_real_escape_string($conn, $_POST['cnote_date'] ?? date('Y-m-d'));
$terms = mysqli_real_escape_string($conn, $_POST['terms_condition'] ?? '');
$created_by = $_SESSION['name'] ?? '';

// Initialize PDF only if customer_name is provided
if ($customer_name != "") {

    // Get max ID for new debit note entry
    $result1 = mysqli_query($conn, "SELECT MAX(id) as max_id FROM debit_note");
    $id = ($row1 = mysqli_fetch_assoc($result1)) ? $row1['max_id'] + 1 : 1;

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
    $filename = "credit_note/" . $file_name;

    $pdf->AddPage();
    $pdf->SetFont("Arial", "", 10);
    $pdf->SetFillColor(232, 232, 232);

    // Header
    $pdf->SetFont('Arial', '', 9);
    $table = array(array("img/logo.png","\n KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets) \n Skyline Beverly Park, # D 402, Amruthahalli Main Road, Amruthahalli,Amruthal,Bangalore - 560092, \n KARNATAKA \nEmail: abhijith.mavatoor@gmail.com, Phone: 9481024700 \n GSTIN: 29AAICK7493G1ZX \n"));
    $lineheight = 4;
    $aligns = array('C','C');
    $widths = array(35,154);
    $border=1;
    $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

    // Title
    $table = array(array("GST Debit Note"));
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
            array("Debit Note Number", "$cnote_code", "Place of Supply", "{$row1['s_state']}"),
            array("Debit Note Date", "$cnoteDate", "Created By", "$created_by"),
            array("Debit Note Type", "Original")
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
        // Insert `debit_note`
        $sql = "INSERT INTO credit_note (id, cnote_code, cnote_file, customer_id, customer_name, email, cnote_date, total_amount, terms_condition, note, status, created_by) 
                VALUES ('$id', '$cnote_code', '$filename', '$cst_mstr_id', '$customer_name', '$customer_email', '$cnoteDate', '$total_amount', '$terms', '$note', 'pending', '$created_by')";
        $conn->query($sql);

        // Product Details Rows
        $slno = 1;
        foreach ($_POST["products"] as $key => $val) {
            $products = mysqli_real_escape_string($conn, $_POST['products'][$key] ?? '');
            $proddesc = mysqli_real_escape_string($conn, $_POST['prod_desc'][$key] ?? '');
            $gsttax = mysqli_real_escape_string($conn, $_POST['gst'][$key] ?? '0');
            $total = mysqli_real_escape_string($conn, $_POST['total'][$key] ?? '0');
            $qty = mysqli_real_escape_string($conn, $_POST['qty'][$key] ?? '0');
            $tax = mysqli_real_escape_string($conn, $_POST['gst_amt'][$key] ?? '0');

            // Insert each product into credit_note_items
            $sql = "INSERT INTO credit_note_items (cnote_id, prod_desc, gst, price, qty, gst_amt, total) 
                    VALUES ('$id', '$proddesc', '$gsttax', '$total', '$qty', '$tax', '$total')";
            $conn->query($sql);

            $pdf->Cell(7, 10, $slno++, 1, 0, 'C');
            $pdf->Cell(92.3, 10, $proddesc, 1, 0, 'C');
            $pdf->Cell(14, 10, $gsttax, 1, 0, 'C');
            $pdf->Cell(20, 10, $total, 1, 0, 'C');
            $pdf->Cell(12, 10, $qty, 1, 0, 'C');
            $pdf->Cell(20, 10, $tax, 1, 0, 'C');
            $pdf->Cell(24, 10, $total, 1, 1, 'C');
        }

        $conn->commit();
        $pdf->Output("F", $filename);
    } catch (Exception $e) {
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }
}
?>
