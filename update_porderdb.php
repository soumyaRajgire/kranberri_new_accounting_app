<?php
session_start();
include('config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debug log (optional)
error_log(print_r($_POST['products'], true), 3, "debug_products.log");

if (isset($_POST['update'])) {
    try {
        $conn->begin_transaction();
        // echo "<script>alert('13 line');</script>";
        // Retrieve form data
        $invoice_id         = intval($_POST['invoice_id'] ?? 0);
        $invoice_code         = $_POST['invoice_code']         ?? '';
       $invoice_date = $_POST['invoice_date'] ?? '';
        $due_date     = $_POST['due_date']     ?? '';
        $terms_condition    = $_POST['terms_condition']    ?? '';
        $note               = $_POST['note']               ?? '';
        $pack_price         = floatval($_POST['pack_price'] ?? 0);
        $invoice_code       = mysqli_real_escape_string($conn, $_POST['invoice_code'] ?? '');
        $created_by         = $_SESSION['name']            ?? '';
        $final_cess_amount  = floatval($_POST['final_cess_amount']  ?? 0);
        $final_gst_amount   = floatval($_POST['final_gst_amount']   ?? 0);
        $final_taxable_amt  = floatval($_POST['final_taxable_amt']  ?? 0);
        $total_amount       = floatval($_POST['total_amount']       ?? 0);
        $sub_total          = 0;
        $total_tax          = 0;
        $branch_id          = $_POST['branch_id']          ?? 0;
        $transportMode      = $_POST['transportMode']      ?? '';
        $vehicleNo          = $_POST['vehicleNumber']      ?? '';
 echo "<script>alert('".$total_amount."  ');</script>";
        // Generate PDF using FPDF
        include("fpdf/fpdf.php");

        class PDF extends FPDF {
            function plot_table($widths, $lineheight, $table, $border, $aligns = array(), $fills = array(), $backgroundColors = array(), $links = array()) {
                // Splitting logic
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
                        $height = (count($cell) != 0) ? ($lineheight * $maxlines / count($cell)) : 0;
                        $width  = $widths[$key] ?? $widths / count($line);
                        $align  = $aligns[$key] ?? '';
                        $fill   = $fills[$key]  ?? false;
                        $link   = $links[$key]  ?? '';
                        $bg     = $backgroundColors[$key] ?? '';

                        if (!empty($bg)) {
                            $this->SetFillColor($bg[0], $bg[1], $bg[2]);
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
                        $this->Cell($width, $lineheight * $maxlines, '', $border, ($key == count($line) - 1) ? 1 : 0);
                    }
                }
            }

            function numberToWords($number) {
                $words = [
                    'zero', 'one', 'two', 'three', 'four', 'five',
                    'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
                    'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen',
                    'seventeen', 'eighteen', 'nineteen'
                ];
                $tens = [
                    '', '', 'twenty', 'thirty', 'forty', 'fifty',
                    'sixty', 'seventy', 'eighty', 'ninety'
                ];

                if ($number < 20) {
                    return $words[$number];
                } elseif ($number < 100) {
                    return $tens[(int)($number / 10)]
                           . (($number % 10 != 0) ? ' ' . $words[$number % 10] : '');
                } elseif ($number < 1000) {
                    return $words[(int)($number / 100)] . ' hundred'
                           . (($number % 100 != 0) ? ' and ' . $this->numberToWords($number % 100) : '');
                } elseif ($number < 1000000) {
                    return $this->numberToWords((int)($number / 1000)) . ' thousand'
                           . (($number % 1000 != 0) ? ' and ' . $this->numberToWords($number % 1000) : '');
                } elseif ($number < 1000000000) {
                    return $this->numberToWords((int)($number / 1000000)) . ' million'
                           . (($number % 1000000 != 0) ? ' and ' . $this->numberToWords($number % 1000000) : '');
                }
                // Extend if needed
                return 'Number is out of range.';
            }
        }

        $pdf = new PDF('P','mm','A4');
        $file_name = md5(rand()) . '.pdf';
        $pdf->AddPage();
        $pdf->SetFont("Arial","",10);
        $pdf->SetFillColor(232,232,232);

        // Fetch branch details
        $result_branch = mysqli_query($conn, "SELECT * FROM add_branch WHERE branch_id='$branch_id'");
        if ($rowB = mysqli_fetch_array($result_branch)) {
            $tableBranch = [[
                "img/logo.png",
                "\n {$rowB['branch_name']} \n {$rowB['address_line1']}, {$rowB['address_line2']}, 
                {$rowB['city']} - {$rowB['pincode']}, \n {$rowB['state']}
                \nEmail: {$rowB['office_email']}, Phone: {$rowB['phone_number']} 
                \n GSTIN: {$rowB['GST']}"
            ]];
            $pdf->plot_table([35,154], 4, $tableBranch, 1, ['C','C']);
        }

        // "GST INVOICE" heading
        $pdf->SetFont("Arial","",9);
        $tableTitle = [["GST INVOICE"]];
        $pdf->plot_table([189], 8, $tableTitle, 1, ['C'], [], [[255, 200, 200]]);

        // Fetch customer details
        $result_cust = mysqli_query($conn, "SELECT * FROM customer_master 
                                           JOIN address_master ON customer_master.id = address_master.customer_master_id WHERE contact_type = 'Supplier'");
        if ($rowC = mysqli_fetch_array($result_cust)) {
            $pdf->SetFont("Arial","B",8);
            $tableAddress = [[
                "\n Billing Address \n\n {$rowC['b_address_line1']} \n {$rowC['b_address_line2']} 
                 \n {$rowC['b_city']} - {$rowC['b_Pincode']} \n {$rowC['b_state']}",
                "\n Shipping Address \n\n {$rowC['s_address_line1']} \n {$rowC['s_address_line2']} 
                 \n {$rowC['s_city']} - {$rowC['s_Pincode']} \n {$rowC['s_state']}"
            ]];
            $pdf->plot_table([94.5,94.5], 5, $tableAddress, 1, ['L','L']);
        }

        // Basic Invoice Info
        $pdf->SetFont("Arial","",9);
        $pdf->SetTextColor(0,0,0,0);
        $infoTable1 = [["Purchase Order Number", $invoice_code]];
        $pdf->plot_table([47.25,47.25,47.25,47.25], 7, $infoTable1, 1, ['L','L','L','L']);

        $infoTable2 = [["Order Date", $invoice_date]];
        $pdf->plot_table([47.25,47.25,47.25,47.25], 7, $infoTable2, 1, ['L','L','L','L']);

        // If $rowC is set, we can use shipping state:
        $placeSupply = $rowC['s_state'] ?? '';
        $infoTable3 = [["Due Date", $due_date, "Place of Supply", $placeSupply]];
        $pdf->plot_table([47.25,47.25,47.25,47.25], 7, $infoTable3, 1, ['L','L','L','L']);

        $pdf->Ln(1);
        $pdf->SetFont("Arial","B",8);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFillColor(220,220,220);

        // Table Header
        $header = ['#','Product Description','GST(%)','RATE','QTY','DIS(%)','Taxable Amt','CGST','SGST','IGST','CESS','TOTAL'];
        $widths = [8, 55, 10, 15, 10, 10, 20, 12, 12, 12, 12, 14]; // sum = 190
        foreach ($header as $i => $col) {
            $pdf->Cell($widths[$i], 8, $col, 1, 0, 'C', true);
        }
        $pdf->Ln();
        $pdf->SetFillColor(255,255,255);

        
       $previous_quantities = [];
$stmt_prev = $conn->prepare("SELECT productid, qty FROM po_items WHERE invoice_id=?");
$stmt_prev->bind_param("i", $invoice_id);
$stmt_prev->execute();
$result_prev = $stmt_prev->get_result();

while ($row = $result_prev->fetch_assoc()) {
    $previous_quantities[$row['productid']] = $row['qty'];
}
        $stmt_prev->close();

        $stmt_del = $conn->prepare("DELETE FROM po_items WHERE invoice_id=?");
        $stmt_del->bind_param("i", $invoice_id);
        $stmt_del->execute();
        $stmt_del->close();
       

        if (!empty($_POST['products'])) {
            // echo "<script>alert('if condition');</script>";
            foreach ($_POST["products"] as $key => $product) {
                // echo "<script>alert('for Loop');</script>";
            $itemId = isset($product['attr_id']) ? intval($product['attr_id']) : null;
            $productid = isset($product['pproductid']) ? mysqli_real_escape_string($conn, $product['pproductid']) : '';
            $products = isset($product['pname']) ? mysqli_real_escape_string($conn, $product['pname']) : '';
            $proddesc = isset($product['pdesc']) ? mysqli_real_escape_string($conn, $product['pdesc']) : '';
            $qtyvalue = isset($product['pqty']) ? floatval($product['pqty']) : 0;
            $priceval = isset($product['pprice']) ? floatval($product['pprice']) : 0;
            $discountval = isset($product['discount']) ? floatval($product['discount']) : 0;
            $gstval = isset($product['gst']) ? floatval($product['gst']) : 0;
            $cgstval = isset($product['cgst']) ? floatval($product['cgst']) : 0;
            $sgstval = isset($product['sgst']) ? floatval($product['sgst']) : 0;
            $igstval = isset($product['igst']) ? floatval($product['igst']) : 0;
            $cessrateval = isset($product['cess_rate']) ? floatval($product['cess_rate']) : 0;
            $cessamountval = isset($product['cess_amount']) ? floatval($product['cess_amount']) : 0;
            $totalval = isset($product['ptotal']) ? floatval($product['ptotal']) : 0;
            $in_ex_gst = isset($product['pin_ex_gst']) ? mysqli_real_escape_string($conn, $product['pin_ex_gst']) : '';
        

                // Calculate line total if discount present
                if ($discountval > 0) {
                    $discountAmt    = ($priceval * $discountval) / 100;
                    $discountedRate = $priceval - $discountAmt;
                    $line_tot       = $discountedRate * $qtyvalue;
                } else {
                    $line_tot = $qtyvalue * $priceval;
                }

                // Print product row in PDF
                $pdf->Cell($widths[0], 8, $key+1, 1, 0, 'C');
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell($widths[1], 8, $products . "\n" . $proddesc, 1, 'L');
                $pdf->SetXY($x + $widths[1], $y);
                $pdf->Cell($widths[2], 8, $gstval, 1, 0, 'C');
                $pdf->Cell($widths[3], 8, number_format($priceval, 2), 1, 0, 'C');
                $pdf->Cell($widths[4], 8, $qtyvalue, 1, 0, 'C');
                $pdf->Cell($widths[5], 8, $discountval, 1, 0, 'C');
                $pdf->Cell($widths[6], 8, number_format($line_tot,2), 1, 0, 'C');
                $pdf->Cell($widths[7], 8, $cgstval, 1, 0, 'C');
                $pdf->Cell($widths[8], 8, $sgstval, 1, 0, 'C');
                $pdf->Cell($widths[9], 8, $igstval, 1, 0, 'C');
                $pdf->Cell($widths[10], 8, $cessamountval . " ({$cessrateval}%)", 1, 0, 'C');
                $pdf->Cell($widths[11], 8, number_format($line_tot,2), 1, 1, 'C');
                
        //       $stmt_item = $conn->prepare("INSERT INTO pi_invoice_items 
        //     (invoice_id, productid, product, qty, price, discount, line_total, total) 
        //     VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        // $stmt_item->bind_param("issddddd", 
        //     $invoice_id, $productid, $products, $qtyvalue, $priceval, $discountval, $line_tot, $totalval);
            
            // Getting current timestamp for created_on
$created_on = date("Y-m-d H:i:s");


    $sql = "INSERT INTO po_items 
        (invoice_id, itemno, productid, product, prod_desc, qty, price, discount, line_total, 
         gst, cgst, sgst, igst, cess_rate, cess_amount, total, in_ex_gst, created_on) 
        VALUES 
        ('$invoice_id', '$itemno', '$productid', '$products', '$proddesc', '$qtyvalue', '$priceval', 
         '$discountval', '$line_tot', '$gstval', '$cgstval', '$sgstval', '$igstval', '$cessrateval', 
         '$cessamountval', '$totalval', '$in_ex_gst', '$created_on')";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Purchase order item created successfully!');</script>";
} else {
    echo "<script>alert('Error inserting updated items: " . $conn->error . "');</script>";
}



//        $stmt_item->execute();
//        $stmt_item->close();

        // Get previous quantity (if exists)
        
    }
}

        // Totals Section
        $pdf->SetFont("Arial","",9);
        $pdf->Cell(150, 6, "Nontaxable Amount", 'L', 0, 'R');
        $pdf->Cell(40, 6, "", 'R', 1, 'R');

        $pdf->Cell(150, 6, "Taxable Amount", 'L', 0, 'R');
        $pdf->Cell(40, 6, $final_taxable_amt, 'R', 1, 'R');

        $pdf->Cell(150, 6, "GST Total", 'L', 0, 'R');
        $pdf->Cell(40, 6, $final_gst_amount, 'R', 1, 'R');

        $pdf->Cell(150, 6, "CESS Total", 'L', 0, 'R');
        $pdf->Cell(40, 6, $final_cess_amount, 'R', 1, 'R');

        // Additional Charges
        $additionalCharges = [];
        if (isset($_POST['additionalCharges']['charge_type']) && isset($_POST['additionalCharges']['charge_price'])) {
            foreach ($_POST['additionalCharges']['charge_type'] as $key => $chargeType) {
                $chargePrice = floatval($_POST['additionalCharges']['charge_price'][$key]);
                $chargeType  = mysqli_real_escape_string($conn, $chargeType);
                if ($chargePrice > 0) {
                    $additionalCharges[$chargeType] = $chargePrice;
                }
            }
        }
        if (!empty($additionalCharges)) {
            foreach ($additionalCharges as $chargeType => $chargePrice) {
                $pdf->Cell(150, 6, $chargeType, 'L', 0, 'R');
                $pdf->Cell(40, 6, number_format($chargePrice, 2), 'R', 1, 'R');
            }
        }

        // Final Invoice Totals
        $pdf->SetFont("Arial","",9);

        // Convert total amount to words
        $totWords = $pdf->numberToWords($total_amount);

        $pdf->Cell(120, 6, "Amount in words : $totWords", 'BL', 0, 'L');
        $pdf->Cell(30, 6, "Invoice Total", 'B', 0, 'R');
        $pdf->Cell(40, 6, "INR $total_amount", 'BR', 1, 'R');

        // Note / Bank info
        $pdf->SetFont("Arial","B",8);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell(27, 6, "Bank Name", 'L', 0, 'L');
        $pdf->Cell(66, 6, "IDFC BANK LIMITED", 'R', 0, 'L');
        $pdf->MultiCell(96, 4, "Note : $note", 'TR', 1, 'L');
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

        // Terms & Condition
        $pdf->SetFont("Arial","B",8);
        if (empty($terms_condition)) {
            $terms_condition = " ";
        }
        $startY   = $pdf->GetY();
        $currentX = $pdf->GetX();
        $pdf->MultiCell(100, 6, "Terms and Condition:\n$terms_condition", 0, 'L');
        $endYFirst = $pdf->GetY();

        // Authorised Sign
        $pdf->SetXY($currentX + 100, $startY);
        $pdf->MultiCell(89, 6, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 0, 'L');
        $endYSecond = $pdf->GetY();
        $maxY = max($endYFirst, $endYSecond);
        $pdf->Rect($currentX, $startY, 100, $maxY - $startY, 'L');
        $pdf->Rect($currentX + 100, $startY, 89, $maxY - $startY, 'R');
        $pdf->SetY($maxY);
        $pdf->Cell(0, 10, "Thank you for your Business!", 1, 1, 'C');

        ob_end_clean();
        $pdfdoc   = $pdf->Output('S');
        $filename = "purchase_invoice/" . $file_name;
        file_put_contents($filename, $pdfdoc);

        
        $stmt_file = $conn->prepare("UPDATE purchase_orders
                                     SET quotation_file=?, invoice_date=?, due_date=?, 
                                         total_amount=?, total_gst=?, total_cess=?, grand_total=?, 
                                         terms_condition=?, note=?
                                     WHERE id=?");
        $stmt_file->bind_param(
            "sssssssssi",
            $filename,
             $invoice_date,
            $due_date,
            $final_taxable_amt,
            $final_gst_amount,
            $final_cess_amount,
            $total_amount,
          
            $terms_condition,
            $note,
            $invoice_id
        );
        $stmt_file->execute();
        $stmt_file->close();

// Check if the voucher record exists before updating






        
        

        $conn->commit();
        echo '<script>
            alert("Successfully Updated Purchase Order");
            window.location.href = "view-purchase-order-action.php?inv_id=' . addslashes($invoice_id) . '";
        </script>';
    } catch (Exception $e) {
        $conn->rollback();
        echo '<script>
            alert("Error updating invoice: ' . addslashes($e->getMessage()) . '");
            window.history.back();
        </script>';
    }
}
?>
