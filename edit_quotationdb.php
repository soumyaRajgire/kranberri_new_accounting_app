<?php
include('config.php');
require('fpdf/fpdf.php');

if (isset($_POST['update'])) {
    // Your existing update logic here

    // Generate the updated PDF
    class PDF extends FPDF {
        function plot_table($widths, $lineheight, $table, $border, $aligns = array(), $fills = array(), $backgroundColors = array(), $links = array()) {
            $func = function($text, $c_width){
                $len=strlen($text);
                $twidth = $this->GetStringWidth($text);
                $split = 0;
                if ($twidth != 0) {
                    $split = floor($c_width * $len / $twidth);
                }
                $w_text = explode("\n", wordwrap($text, $split, "\n", true));
                return $w_text;
            };
            foreach ($table as $line) {
                $line = array_map($func, $line, $widths);
                $maxlines = max(array_map("count", $line));

                foreach ($line as $key => $cell) {
                    $x_axis = $this->GetX();
                    $height = 0;

                    if (count($cell) != 0) {
                        $height = $lineheight * $maxlines / count($cell);
                    }

                    $len = count($line);
                    $width = (isset($widths[$key]) === TRUE ? $widths[$key] : $widths / count($line));
                    $align = (isset($aligns[$key]) === TRUE ? $aligns[$key] : '');
                    $fill = (isset($fills[$key]) === TRUE ? $fills[$key] : false);
                    $link = (isset($links[$key]) === TRUE ? $links[$key] : '');

                    $backgroundColor = (isset($backgroundColors[$key]) === TRUE ? $backgroundColors[$key] : '');

                    // Set background color if available
                    if (!empty($backgroundColor)) {
                        $this->SetFillColor($backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);
                        $this->Rect($this->GetX(), $this->GetY(), array_sum($widths), $height, 'F');
                    }

                    foreach ($cell as $textline) {
                        // Check if the textline is an image path by verifying the file extension
                        if (is_string($textline) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $textline)) {
                            $imageWidth = 30; // Adjust width if necessary
                            $imageHeight = 15; // Adjust height if necessary
                            $imageX = $this->GetX() + ($width - $imageWidth) / 2;
                            $imageY = $this->GetY() + ($height - $imageHeight) / 2;
                            $this->Image($textline, $imageX, $imageY, $imageWidth, $imageHeight);
                        } else {
                            $this->Cell($widths[$key], $height, $textline, 0, 0, $align, $fill, $link);
                        }

                        $height += 2 * $lineheight * $maxlines / count($cell);
                        $this->SetX($x_axis);
                    }

                    if ($key == $len - 1) {
                        $lbreak = 1;
                    } else {
                        $lbreak = 0;
                    }

                    $this->Cell($widths[$key], $lineheight * $maxlines, '', $border, $lbreak);
                }
            }
        }
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

    $pdf = new PDF('P', 'mm', 'A4');
    $file_name = md5(rand()) . '.pdf';

    $pdf->AddPage();
    $pdf->SetFont("Arial", "", 10);

    $pdf->SetFillColor(232,232,232);
    $pdf->SetFont('Arial', '', 9);
    $table = array(array("img/logo.png", "\n KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets) \n Skyline Beverly Park, # D 402, Amruthahalli Main Road, Amruthahalli,Amruthal,Bangalore - 560092, \n KARNATAKA \nEmail: abhijith.mavatoor@gmail.com, Phone: 9481024700 \n GSTIN: 29AAICK7493G1ZX \n"));
    $lineheight = 4;
    $aligns = array('C', 'C');
    $widths = array(35, 154);
    $border = 1;
    $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);

    $pdf->SetFont('Arial', '', 9);
    $table = array(array("QUOTATION"));
    $lineheight = 8;
    $aligns = array('C');
    $widths = array(189);
    $border = 1;
    $backgroundColors = array(array(255, 200, 200));
    $pdf->plot_table($widths, $lineheight, $table, $border, $aligns, $backgroundColors);

    $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id WHERE customer_master.id = (SELECT customer_id FROM quotation WHERE id = $qid)");
    if ($row1 = mysqli_fetch_array($result1)) {
        $pdf->SetFont("Arial", "B", 8);
        $table = array(array(
            "\n Billing Address \n\n {$row1['b_address_line1']} \n {$row1['b_address_line2']} \n {$row1['b_city']} - {$row1['b_Pincode']} \n {$row1['b_state']} \n",
            "\n Shipping Address \n\n {$row1['s_address_line1']} \n {$row1['s_address_line2']} \n {$row1['s_city']} - {$row1['s_Pincode']} \n {$row1['s_state']} \n"
        ));
        $lineheight = 5;
        $widths = array(94.5, 94.5);
        $aligns = array('L', 'L');
        $border = 1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);

        $pdf->SetFont("Arial", "", 9);
        $pdf->SetTextColor(0, 0, 0, 0);
        $table = array(array("Quotation Number", "$purchaseNo", "Place of Supply", "{$row1['s_state']}"));
        $lineheight = 9;
        $widths = array(47.25, 47.25, 47.25, 47.25);
        $aligns = array('L', 'L', 'L', 'L');
        $border = 1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);

        $table = array(array("Quotation Date", "$purchaseDate", "Created By", "$created_by"));
        $lineheight = 9;
        $widths = array(47.25, 47.25, 47.25, 47.25);
        $aligns = array('L', 'L', 'L', 'L');
        $border = 1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);

        $table = array(array("Due Date", "$dueDate", "Quotation Type", "Original"));
        $lineheight = 9;
        $widths = array(47.25, 47.25, 47.25, 47.25);
        $aligns = array('L', 'L', 'L', 'L');
        $border = 1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);
    }

    $pdf->SetFont("Arial", "B", 8);
    $pdf->SetTextColor(0, 0, 0, 0);
    $pdf->SetDrawColor(0, 0, 0, 0);
    $pdf->SetLineWidth(0);
    $pdf->SetFillColor(232, 232, 232);
    $pdf->Cell(7, 10, "#", 1, 0, 'C', 1);
    $pdf->Cell(92.3, 10, "Product Description", 1, 0, 'C', 1);
    $pdf->Cell(14, 10, "GST", 1, 0, 'C', 1);
    $pdf->Cell(20, 10, "RATE", 1, 0, 'C', 1);
    $pdf->Cell(12, 10, "QTY", 1, 0, 'C', 1);
    $pdf->Cell(24, 10, "TOTAL", 1, 1, 'C', 1); 

    $cgsttotal = 0;
    $sgsttotal = 0;
    $pricevaltot = 0;
    $tot_total = 0;
    $tot_qty = 0;
    $nontax_tot_amt = 0;
    $gsttot = 0;
    $gsttotamt = 0;
    $pdf->SetFillColor(255, 255, 255);
    foreach ($_POST["products"] as $key => $val) {
        $tot = 0;
        $cgsttotal = 0;
        $sgsttotal = 0;
        $nontax_amt = 0;
        $itemnum = $key + 1;
        $products = mysqli_real_escape_string($conn, $val['pname']);
        $proddesc = mysqli_real_escape_string($conn, $val['pdesc']);
        $qtyvalue = mysqli_real_escape_string($conn, $val['pqty']);
        $priceval = mysqli_real_escape_string($conn, $val['pprice']);
        $gstval = mysqli_real_escape_string($conn, $val['pgst']);
        $productid = mysqli_real_escape_string($conn, $val['pproductid']);
        $in_ex_gst = mysqli_real_escape_string($conn, $val['pin_ex_gst']);
        $line_tot = $qtyvalue * $priceval;
        $gstamt = number_format(($gstval / 2) * $line_tot / 100, 2, '.', '');
        $total = $line_tot + $gstamt;

        if ($in_ex_gst === "inclusive of GST") {
            $nontax_amt = $priceval / (1 + ($gstval / 100));
        } else {
            $nontax_amt = $priceval;
        }

        $nontax_tot_amt += $nontax_amt;
        $cgsttotal += floatval($gstamt);
        $sgsttotal += floatval($gstamt);
        $pricevaltot += floatval($priceval);
        $tot_total += floatval($total);
        $gsttot = ($cgsttotal + $sgsttotal);
        $tot_qty += $qtyvalue;
        $gsttotamt += $gsttot;
        $tot_formatted = number_format($total, 0, '.', '');

        $table = array(array($itemnum, $products . "\n" . $proddesc, $gstval, $priceval, $qtyvalue, $gstamt * 2, $tot_formatted));
        $lineheight = 7;
        $widths = array(7, 92, 14, 20, 12, 20, 24);
        $aligns = array('C', 'L', 'C', 'C', 'C', 'C', 'C');
        $border = 1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);
    }

    $tot_amt = $gsttotamt + $tot_total;
    $totWords = numberToWords($tot_amt);
    $pdf->Cell(150, 6, "Nontaxable Amount", 'L', 0, 'R');
    $pdf->Cell(39, 6, number_format($nontax_tot_amt, 2), 'R', 1, 'R');

    $pdf->Cell(150, 6, "Taxable Amount", 'L', 0, 'R');
    $pdf->Cell(39, 6, number_format($pricevaltot, 2), 'R', 1, 'R');

    $pdf->Cell(150, 6, "GST Total", 'L', 0, 'R');
    $pdf->Cell(39, 6, number_format($gsttotamt, 2), 'R', 1, 'R');

    $pdf->Cell(150, 6, "Adjustment", 'L', 0, 'R');
    $pdf->Cell(39, 6, "0", 'R', 1, 'R');

    $pdf->Cell(120, 6, "Amount in words: $totWords", 'BL', 0, 'L');
    $pdf->Cell(30, 6, "Quotation Total", 'B', 0, 'R');
    $pdf->Cell(39, 6, "INR " . number_format($tot_amt, 2), 'BR', 1, 'R');

    $pdf->SetFont("Arial", "B", 8);
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->Cell(27, 6, "Bank Name", 'L', 0, 'L');
    $pdf->Cell(66, 6, "IDFC BANK LIMITED", 'R', 0, 'L');
    $pdf->MultiCell(96, 4, "Note: $note", 'TR', 1, 'L');
    $pdf->SetXY($x, $y + 6);

    $pdf->Cell(27, 6, "Account Name", 'L', 0, 'L');
    $pdf->Cell(66, 6, "KRIKA MKB CORPORATION PRIVATE LIMITED", 'R', 0, 'L');
    $pdf->Cell(96, 6, "", 'R', 1, 'L');

    $pdf->Cell(27, 6, "Account No", 'L', 0, 'L');
    $pdf->Cell(66, 6, "10069839667", 'R', 0, 'L');
    $pdf->Cell(96, 6, "", 'R', 1, 'L');

    $pdf->Cell(27, 6, "IFSC Code", 'BL', 0, 'L');
    $pdf->Cell(66, 6, "IDFB0080177", 'BR', 0, 'L');
    $pdf->Cell(96, 6, "", 'BR', 1, 'L');

    if (empty($terms_condition)) {
        $terms_condition = " ";
    }
    $startY = $pdf->GetY();
    $currentX = $pdf->GetX();

    $pdf->MultiCell(100, 6, "Terms and Condition:\n$terms_condition", 0, 'L');
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
    $pdfdoc = $pdf->Output('S');
    $filename = "quotation/" . $file_name;
    file_put_contents($filename, $pdfdoc);

    // Update the quotation file path in the database
    $update_file_query = "UPDATE quotation SET quotation_file=? WHERE id=?";
    $stmt_file = $conn->prepare($update_file_query);
    if (!$stmt_file) {
        die("Error preparing file update statement: " . $conn->error);
    }
    $stmt_file->bind_param("si", $filename, $qid);
    if (!$stmt_file->execute()) {
        die("Error updating quotation file: " . $stmt_file->error);
    }
    $stmt_file->close();

    // Redirect after successful update
    echo '<script>alert("Successfully Updated Quotation");';
    echo 'window.location.href = "view_estimate.php?qid=' . $qid . '";</script>';
} else {
    header("Location: create-quotation.php");
    exit;
}
?>
