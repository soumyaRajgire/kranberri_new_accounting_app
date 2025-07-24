<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

error_reporting(E_ALL);
session_start();

// if (!isset($_SESSION['name']) && $_SESSION['ROLE'] != '1') {
//     header("Location:login.php");
//     exit();
// } else {
//     $_SESSION['url'] = $_SERVER['REQUEST_URI'];
// }
// Check if the user is logged in
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

if (isset($_POST['submit'])) {

    include("fpdf/fpdf.php");

    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice']);
    $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id']);
    $sub_total = mysqli_real_escape_string($conn, $_POST['sub_total']);
    $pack_price = mysqli_real_escape_string($conn, $_POST['pack_price']);
    $total_amount = mysqli_real_escape_string($conn, $_POST['total_amount']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    $invoice_code = mysqli_real_escape_string($conn, $_POST['purchaseNo']);
    $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
    $purchaseDate = mysqli_real_escape_string($conn, $_POST['purchaseDate']);
    $terms = mysqli_real_escape_string($conn, $_POST['terms_condition']);
    $quot_id = mysqli_real_escape_string($conn, $_POST['quot_id']);
    $created_by = $_SESSION['name'];

    if (!empty($customer_name)) {
        $result1 = mysqli_query($conn, "SELECT id FROM invoice WHERE id = (SELECT MAX(id) FROM invoice)");
        $id = ($row1 = mysqli_fetch_array($result1)) ? $row1['id'] + 1 : 1;

        class PDF extends FPDF {
            function plot_table($widths, $lineheight, $table, $border, $aligns = array(), $fills = array(), $backgroundColors = array(), $links = array()) {
                $func = function($text, $c_width) {
                    $len = strlen($text);
                    $twidth = $this->GetStringWidth($text);
                    $split = ($twidth != 0) ? floor($c_width * $len / $twidth) : 0;
                    return explode("\n", wordwrap($text, $split, "\n", true));
                };

                foreach ($table as $line) {
                    $line = array_map($func, $line, $widths);
                    $maxlines = max(array_map("count", $line));
                    foreach ($line as $key => $cell) {
                        $x_axis = $this->GetX();
                        $height = (count($cell) != 0) ? $lineheight * $maxlines / count($cell) : 0;
                        $width = $widths[$key] ?? $widths / count($line);
                        $align = $aligns[$key] ?? '';
                        $fill = $fills[$key] ?? false;
                        $link = $links[$key] ?? '';
                        $backgroundColor = $backgroundColors[$key] ?? '';

                        if (!empty($backgroundColor)) {
                            $this->SetFillColor($backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
                            $this->Rect($this->GetX(), $this->GetY(), array_sum($widths), $height, 'F');
                        }

                        foreach ($cell as $textline) {
                            if (is_string($textline) && file_exists($textline)) {
                                $imageWidth = 30;
                                $imageHeight = 15;
                                $imageX = $this->GetX() + ($width - $imageWidth) / 2;
                                $imageY = $this->GetY() + ($height - $imageHeight) / 2;
                                $this->Image($textline, $imageX, $imageY, $imageWidth, $imageHeight);
                            } else {
                                $this->Cell($width, $height, $textline, 0, 0, $align, $fill, $link);
                            }
                            $height += 2 * $lineheight * $maxlines / count($cell);
                            $this->SetX($x_axis);
                        }

                        $lbreak = ($key == count($line) - 1) ? 1 : 0;
                        $this->Cell($width, $lineheight * $maxlines, '', $border, $lbreak);
                    }
                }
            }
        }

        ob_start();
        $pdf = new PDF('P', 'mm', 'A4');
        $file_name = md5(rand()) . '.pdf';
        $pdf->AddPage();
        $pdf->SetFont("Arial", "", 10);
        $pdf->SetFillColor(232, 232, 232);

        $table = [["img/logo.png", "\n KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets) \n Skyline Beverly Park, # D 402, Amruthahalli Main Road, Amruthahalli, Bangalore - 560092, \n KARNATAKA \nEmail: abhijith.mavatoor@gmail.com, Phone: 9481024700 \n GSTIN: 29AAICK7493G1ZX \n"]];
        $pdf->plot_table([35, 154], 4, $table, 1, ['C', 'C']);

        $table = [["GST INVOICE"]];
        $pdf->plot_table([189], 8, $table, 1, ['C'], [], [['255, 200, 200']]);

        $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id WHERE customer_master.id = '$cst_mstr_id'");

        if ($row1 = mysqli_fetch_array($result1)) {
            $table = [["\n Billing Address \n\n {$row1['b_address_line1']} \n {$row1['b_address_line2']} \n {$row1['b_city']} - {$row1['b_Pincode']} \n {$row1['b_state']} \n", "\n Shipping Address \n\n {$row1['s_address_line1']} \n {$row1['s_address_line2']} \n {$row1['s_city']} - {$row1['s_Pincode']} \n {$row1['s_state']} \n"]];
            $pdf->plot_table([94.5, 94.5], 5, $table, 1, ['L', 'L']);

            $table = [["Invoice Number", "$invoice_code", "Place of Supply", "{$row1['s_state']}"]];
            $pdf->plot_table([47.25, 47.25, 47.25, 47.25], 9, $table, 1, ['L', 'L', 'L', 'L']);

            $table = [["Invoice Date", "$purchaseDate", "Created By", "$created_by"]];
            $pdf->plot_table([47.25, 47.25, 47.25, 47.25], 9, $table, 1, ['L', 'L', 'L', 'L']);

            $table = [["Due Date", "$dueDate", "Invoice Type", "Original"]];
            $pdf->plot_table([47.25, 47.25, 47.25, 47.25], 9, $table, 1, ['L', 'L', 'L', 'L']);
        }

        $pdf->SetFont("Arial", "B", 8);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Cell(7, 10, "#", 1, 0, 'C', 1);
        $pdf->Cell(92.3, 10, "Product Description", 1, 0, 'C', 1);
        $pdf->Cell(14, 10, "GST", 1, 0, 'C', 1);
        $pdf->Cell(20, 10, "RATE", 1, 0, 'C', 1);
        $pdf->Cell(12, 10, "QTY", 1, 0, 'C', 1);
        $pdf->Cell(20, 10, "GST", 1, 0, 'C', 1);
        $pdf->Cell(24, 10, "TOTAL", 1, 1, 'C', 1);

        $cgsttotal = 0;
        $sgsttotal = 0;
        $pricevaltot = 0;
        $tot_total = 0;
        $tot_qty = 0;
        $nontax_tot_amt = 0;

        foreach ($_POST['products'] as $product) {
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
            $gsttot = floatval($cgsttotal) + floatval($sgsttotal);
            $tot_qty += intval($qtyvalue);

            $nontax_amt = ($in_ex_gst === "inclusive of GST") ? floatval($priceval) / (1 + (floatval($gstval) / 100)) : floatval($priceval);
            $nontax_tot_amt += floatval($nontax_amt);

            $table = [[$itemnum, $productsname . "\n" . $proddesc, $gstval, $priceval, $qtyvalue, $gsttot, $total]];
            $pdf->plot_table([7, 92, 14, 20, 12, 20, 24], 7, $table, 1, ['C', 'L', 'C', 'C', 'C', 'C', 'C']);

            $result2 = mysqli_query($conn, "SELECT id FROM invoice_items WHERE id = (SELECT MAX(id) FROM invoice_items)");
            $id1 = ($row2 = mysqli_fetch_array($result2)) ? $row2['id'] + 1 : 1;
            $line_tot = intval($qtyvalue) * floatval($priceval);
            $gstamt = number_format((floatval($gstval) / 2) * floatval($total) / 100, 2, '.', '');

            mysqli_query($conn, "INSERT INTO invoice_items (id, itemno, invoice_id, product_id, product, prod_desc, qty, price, line_total, gst, gst_amt, total, created_by) VALUES ('$id1', '$itemnum', '$id', '$productid', '$productsname', '$proddesc', '$qtyvalue', '$priceval', '$line_tot', '$gstval', '$gstamt', '$total', '$created_by')");
        }

        $gsttot = ($cgsttotal + $sgsttotal);
        $tot_amt = $gsttot + $tot_total;

        $pdf->Cell(150, 6, "Nontaxable Amount", 'L', 0, 'R');
        $pdf->Cell(39, 6, "$nontax_tot_amt", 'R', 1, 'R');

        $pdf->Cell(150, 6, "Taxable Amount", 'L', 0, 'R');
        $pdf->Cell(39, 6, "$pricevaltot", 'R', 1, 'R');

        $pdf->Cell(150, 6, "GST Total", 'L', 0, 'R');
        $pdf->Cell(39, 6, "$gsttot", 'R', 1, 'R');

        $pdf->Cell(150, 6, "Adjusment", 'L', 0, 'R');
        $pdf->Cell(39, 6, "0", 'R', 1, 'R');

        $pdf->Cell(120, 6, "Amount in words", 'BL', 0, 'L');
        $pdf->Cell(30, 6, "Invoice Total", 'B', 0, 'R');
        $pdf->Cell(39, 6, "INR $tot_amt", 'BR', 1, 'R');

        $pdf->SetFont("Arial", "B", 8);

        $pdf->Cell(27, 6, "Bank Name", 'L', 0, 'L');
        $pdf->Cell(66, 6, "IDFC BANK LIMITED", 'R', 0, 'L');
        $pdf->MultiCell(96, 4, "Note : $note", 'TR', 1, 'L');

        $pdf->Cell(27, 6, "Account Name", 'L', 0, 'L');
        $pdf->Cell(66, 6, "KRIKA MKB CORPORATION PRIVATE LIMITED", 'R', 0, 'L');
        $pdf->Cell(96, 6, "", 'R', 1, 'L');

        $pdf->Cell(27, 6, "Account No", 'L', 0, 'L');
        $pdf->Cell(66, 6, "10069839667", 'R', 0, 'L');
        $pdf->Cell(96, 6, "", 'R', 1, 'L');

        $pdf->Cell(27, 6, "IFSC Code", 'BL', 0, 'L');
        $pdf->Cell(66, 6, "IDFB0080177", 'BR', 0, 'L');
        $pdf->Cell(96, 6, "", 'BR', 1, 'L');

        if (empty($terms)) {
            $terms = " ";
        }
        $startY = $pdf->GetY();
        $currentX = $pdf->GetX();
        $pdf->MultiCell(100, 6, "Terms and Condition:\n$terms", 0, 'L');
        $endYFirst = $pdf->GetY();
        $pdf->SetXY($currentX + 100, $startY);
        $pdf->MultiCell(89, 6, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 0, 'L');
        $endYSecond = $pdf->GetY();
        $maxY = max($endYFirst, $endYSecond);
        $pdf->Rect($currentX, $startY, 100, $maxY - $startY, 'L');
        $pdf->Rect($currentX + 100, $startY, 89, $maxY - $startY, 'R');
        $pdf->SetY($maxY);
        $pdf->Cell(0, 10, "Thank you for your Business!", 1, 1, 'C');

        ob_end_clean();

        $separator = md5(time());
        $eol = PHP_EOL;
        $filename = "invoice/" . $file_name;
        $pdfdoc = $pdf->Output('S');
        file_put_contents($filename, $pdfdoc);

        $sql = "INSERT INTO invoice (id, invoice_code, invoice_file, customer_id, email, invoice_date, due_date, total_amount, total_tax, grand_total, terms_condition, note, status, created_by) VALUES ('$id', '$invoice_code', '$filename', '$cst_mstr_id', '$customer_email', '$purchaseDate', '$dueDate', '$pricevaltot', '$gsttot', '$tot_amt', '$terms', '$note', 'pending', '$created_by')";

        if ($conn->query($sql) === TRUE) {
            $update_q_sql = "UPDATE quotation SET status = ? WHERE id = ?";
            $update_q_stmt = $conn->prepare($update_q_sql);
            $status = "Converted";
            $update_q_stmt->bind_param("ss", $status, $quot_id);
            $update_q_result = $update_q_stmt->execute();
            if (!$update_q_result) {
                die("Update address failed: " . $update_q_stmt->error);
            }
            ?>
            <script>
                alert("Successfully Created Invoice");
                window.location="view-invoices.php";
            </script>
            <?php
        } else {
            ?>
            <script>
                alert("Unable to create Invoice, try again");
                window.location="create-invoice.php";
            </script>
            <?php
        }
    }
}
?>
