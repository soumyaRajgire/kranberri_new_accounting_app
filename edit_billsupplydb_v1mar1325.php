<?php
session_start();
include('config.php');



error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);




// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

// Check if a business is selected
if (!isset($_SESSION['business_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    $branch_id = $_SESSION['branch_id'] ?? null;
    $GLOBALS['branch_id'] = $branch_id;
}



if (isset($_POST['update'])) {
    
    // Retrieve form data
    $bill_id = $_POST['bill_id'];
    $purchaseNo = $_POST['purchaseNo'];
    $purchaseDate = $_POST['purchaseDate'];
    $dueDate = $_POST['dueDate'];
    $terms_condition = $_POST['terms_condition'];
    $note = $_POST['note'];
    // $grand_total = $_POST['grand_total'];
    $pack_price = isset($_POST['pack_price']) ? $_POST['pack_price'] : 0;
    $bill_code = mysqli_real_escape_string($conn, $_POST['purchaseNo']);
    $created_by = $_SESSION['name'];
    

    $sub_total = 0;
    $total_tax = 0;

    // Delete existing items for the invoice if any
    if (!empty($_POST['delete_item_ids'])) {
        $delete_item_ids = explode(',', $_POST['delete_item_ids']);
        foreach ($delete_item_ids as $item_id) {
            $delete_query = "DELETE FROM billsupply_items WHERE id = ?";
            $stmt_delete = $conn->prepare($delete_query);
            if (!$stmt_delete) {
                die("Error preparing delete statement: " . $conn->error);
            }
            $stmt_delete->bind_param("i", $item_id);
            if (!$stmt_delete->execute()) {
                die("Error deleting bill item: " . $stmt_delete->error);
            }
            $stmt_delete->close();
        }
    }

    // Insert or update items
    foreach ($_POST['products'] as $product) {
        $attr_id = isset($product['attr_id']) ? $product['attr_id'] : null;
        $itemnum = isset($product['pitemno']) ? $product['pitemno'] : '';
        $product_id = isset($product['pproductid']) ? $product['pproductid'] : null;
        $product_name = isset($product['pname']) ? $product['pname'] : '';
        $product_desc = isset($product['pdesc']) ? $product['pdesc'] : '';
        $quantity = isset($product['pqty']) ? $product['pqty'] : '';
        $price = isset($product['pprice']) ? $product['pprice'] : '';
        $line_total = isset($product['ptotal']) ? $product['ptotal'] : '';
      
        // Skip empty product entries
        if (empty($product_id) || empty($product_name)) {
            continue;
        }

      

        if ($attr_id) {
            // Update existing items
            $update_items_query = "UPDATE billsupply_items SET itemno=?, product_id=?, product=?, prod_desc=?, qty=?, price=?, line_total=?, gst=?, total=?, in_ex_gst=? WHERE id=?";
            $stmt_items = $conn->prepare($update_items_query);
            if (!$stmt_items) {
                die("Error preparing update items statement: " . $conn->error);
            }
            $stmt_items->bind_param("isssssssssi", $itemnum, $product_id, $product_name, $product_desc, $quantity, $price, $line_total, $gst, $line_total, $in_ex_gst, $attr_id);
            if (!$stmt_items->execute()) {
                die("Error updating bill items: " . $stmt_items->error);
            }
            $stmt_items->close();
        } else {
            // Insert new items
            $insert_items_query = "INSERT INTO billsupply_items (bill_id, itemno, product_id, product, prod_desc, qty, price, line_total, gst, total, in_ex_gst) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_items = $conn->prepare($insert_items_query);
            if (!$stmt_items) {
                die("Error preparing insert items statement: " . $conn->error);
            }
            $stmt_items->bind_param("iiissssssss", $bill_id, $itemnum, $product_id, $product_name, $product_desc, $quantity, $price, $line_total, $gst, $line_total, $in_ex_gst);
            if (!$stmt_items->execute()) {
                die("Error inserting bill items: " . $stmt_items->error);
            }
            $stmt_items->close();
        }

        // Add line total to sub_total
        $sub_total += $line_total;
    }

    // Calculate the grand total
    $gtotal = $sub_total + $pack_price + $total_tax;

    $update_invoice_query = "UPDATE bill_of_supply SET bill_code=?, bill_date=?, due_date=?, total_amount=?, grand_total=?, terms_condition=?, note=? WHERE id=?";
$stmt_invoice = $conn->prepare($update_invoice_query);
if (!$stmt_invoice) {
    die("Error preparing bill update statement: " . $conn->error);
}
$stmt_invoice->bind_param("ssssssii", $purchaseNo, $purchaseDate, $dueDate, $sub_total, $gtotal, $terms_condition, $note, $bill_id);
if (!$stmt_invoice->execute()) {
    die("Error updating invoice: " . $stmt_invoice->error);
}
$stmt_invoice->close();
$qtyvalue=$quantity;

$productid=$product_id;
   $sql_st_master = "INSERT INTO stock_master (product_id, reference_no,quantity, add_and_deduct, remark, date, created_by, created_on)   VALUES (?,?, ?, ?, ?, NOW(), ?, NOW())";

// Prepare statement
        $stmt_st_master = $conn->prepare($sql_st_master);
        $transaction_type = "Sale"; // This can be changed based on the transaction type
        $remark = "Bill of Supply"; // Can be customized

        $stmt_st_master->bind_param("isisss", $productid,$bill_code, $qtyvalue, $transaction_type, $remark, $created_by);

    // Execute query
        if ($stmt_st_master->execute()) {
            echo "Stock transaction recorded successfully!";
        } else {
            echo "Error updating stock transaction: " . $conn->error;
        }   

        $stmt_st_master->close();

 // Add these variables at the beginning of the script
$gst = 0; // Or set appropriate value
$in_ex_gst = 0; // Or set appropriate value

// Initialize to store previous quantities
$previous_quantities = array();

// Query to get previous quantities before updating
$prev_qty_query = "SELECT product_id, qty FROM billsupply_items WHERE bill_id = ?";
$stmt_prev = $conn->prepare($prev_qty_query);
$stmt_prev->bind_param("i", $bill_id);
$stmt_prev->execute();
$result_prev = $stmt_prev->get_result();
while ($row = $result_prev->fetch_assoc()) {
    $previous_quantities[$row['product_id']] = $row['qty'];
}
$stmt_prev->close();

// Rest of your code...

// Inside the foreach loop for products
foreach ($_POST['products'] as $product) {
    // Existing code...
    
    // Move this code inside the loop to update stock for each product
    $productid = $product_id;
    $qtyvalue = $quantity;
    
    // Get previous quantity for this product
    $prev_qty = isset($previous_quantities[$productid]) ? $previous_quantities[$productid] : 0;
    
    // Update stock_master and inventory_master for this product
    $sql_st_master = "INSERT INTO stock_master (product_id, reference_no, quantity, add_and_deduct, remark, date, created_by, created_on) VALUES (?, ?, ?, ?, ?, NOW(), ?, NOW())";
    $stmt_st_master = $conn->prepare($sql_st_master);
    $transaction_type = "Sale";
    $remark = "Bill of Supply";
    $stmt_st_master->bind_param("isisss", $productid, $bill_code, $qtyvalue, $transaction_type, $remark, $created_by);
    $stmt_st_master->execute();
    $stmt_st_master->close();
    
    // Update inventory if quantity changed
    if ($qtyvalue != $prev_qty) {
        if ($qtyvalue > $prev_qty) {
            $diff_qty = $qtyvalue - $prev_qty;
            $sql_update_stock = "UPDATE inventory_master 
                SET stock_out = stock_out + ?, 
                    balance_stock = (opening_stock + stock_in) - stock_out,  
                    last_updated_by = ?,  
                    last_updated_at = NOW()  
                WHERE id = ?";
        } else {
            $diff_qty = $prev_qty - $qtyvalue;
            $sql_update_stock = "UPDATE inventory_master 
                SET stock_out = stock_out - ?, 
                    balance_stock = (opening_stock + stock_in) - stock_out,  
                    last_updated_by = ?,  
                    last_updated_at = NOW()  
                WHERE id = ?";
        }
        
        $stmt_update_stock = $conn->prepare($sql_update_stock);
        $stmt_update_stock->bind_param("isi", $diff_qty, $created_by, $productid);
        $stmt_update_stock->execute();
        $stmt_update_stock->close();
    }
}

// After the loop, update the ledger with the grand total
$sql_update_ledger = "UPDATE ledger SET amount=?, transaction_date=? WHERE voucher_id=?";
$stmt_update_ledger = $conn->prepare($sql_update_ledger);
$stmt_update_ledger->bind_param("dsi", $gtotal, $purchaseDate, $bill_id);
$stmt_update_ledger->execute();
$stmt_update_ledger->close();

    // Generate the updated PDF
    include("fpdf/fpdf.php");

    // Fetch updated invoice details
    $invoice_query = "SELECT * FROM bill_of_supply WHERE id=?";
    $stmt_invoice = $conn->prepare($invoice_query);
    if (!$stmt_invoice) {
        die("Error preparing bill fetch statement: " . $conn->error);
    }
    $stmt_invoice->bind_param("i", $bill_id);
    if (!$stmt_invoice->execute()) {
        die("Error fetching invoice: " . $stmt_invoice->error);
    }
    $result_invoice = $stmt_invoice->get_result();
    $invoice_data = $result_invoice->fetch_assoc();
    $stmt_invoice->close();

    // Fetch invoice items
    $items_query = "SELECT * FROM billsupply_items WHERE bill_id=?";
    $stmt_items = $conn->prepare($items_query);
    if (!$stmt_items) {
        die("Error preparing items fetch statement: " . $conn->error);
    }
    $stmt_items->bind_param("i", $bill_id);
    if (!$stmt_items->execute()) {
        die("Error fetching bill items: " . $stmt_items->error);
    }
    $result_items = $stmt_items->get_result();
    $invoice_items = $result_items->fetch_all(MYSQLI_ASSOC);
    $stmt_items->close();

    // Generate PDF
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
                        // Check if the textline is an image path
                        if (is_string($textline) && file_exists($textline)) {
                            $imageWidth = 30;
                            $imageHeight = 15;
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
    
        $scales = [
            '', 'thousand', 'million', 'billion', 'trillion'
        ];
    
        if ($number < 20) {
            return $words[$number];
        } elseif ($number < 100) {
            return $tens[(int)($number / 10)] . (($number % 10 != 0) ? ' ' . $words[$number % 10] : '');
        } elseif ($number < 1000) {
            return $words[(int)($number / 100)] . ' hundred' . (($number % 100 != 0) ? ' and ' . numberToWords($number % 100) : '');
        } else {
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $scale = $scales[(int)log($baseUnit, 1000)];
    
            return numberToWords($numBaseUnits) . ' ' . $scale . (($remainder > 0) ? ' ' . numberToWords($remainder) : '');
        }
    }
    
    $pdf = new PDF('P', 'mm', 'A4');
    $file_name = md5(rand()) . '.pdf';
    
    $pdf->AddPage();
    $pdf->SetFont("Arial", "", 10);
    
    $pdf->SetFillColor(232,232,232);
    
    $pdf->SetFont('Arial', '', 9);
    $table = array(array("img/logo.png","\n KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets) \n Skyline Beverly Park, # D 402, Amruthahalli Main Road, Amruthahalli,Amruthal,Bangalore - 560092, \n KARNATAKA \nEmail: abhijith.mavatoor@gmail.com, Phone: 9481024700 \n GSTIN: 29AAICK7493G1ZX \n"));
    $lineheight = 4;
    $fontsize = 10;
    $aligns = array('C','C');
    $widths = array(35,154);
    $border=1;
    $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);
    
    $pdf->SetFont('Arial', '', 9);
    $table = array(array("BILL OF SUPPLY"));
    $lineheight = 8;
    $fontsize = 10;
    $aligns = array('C');
    $widths = array(189);
    $border=1;
    $backgroundColors = array(array(255, 200, 200));
    
    $pdf->plot_table($widths, $lineheight, $table, $border, $aligns, $backgroundColors);
    
    $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");
    
    if ($row1 = mysqli_fetch_array($result1)) {
    
        $pdf->SetFont("Arial","B",8);
    
        $table = array(array(
            "\n Billing Address \n\n {$row1['b_address_line1']} \n {$row1['b_address_line2']} \n {$row1['b_city']} - {$row1['b_Pincode']} \n {$row1['b_state']} \n",
            "\n Shipping Address \n\n {$row1['s_address_line1']} \n {$row1['s_address_line2']} \n {$row1['s_city']} - {$row1['s_Pincode']} \n {$row1['s_state']} \n"
        ));
    
        $lineheight = 5;
        $fontsize = 10;
        $widths = array(94.5,94.5);
        $aligns = array('L','L');
        $border=1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);
    
        $pdf->SetFont("Arial","",9);
        $pdf->SetTextColor(0,0,0,0);
    
        $table = array(array("BILL Number","$bill_code","Place of Supply","{$row1['s_state']}"));
        $lineheight = 9;
        $fontsize = 10;
        $widths = array(47.25,47.25,47.25,47.25);
        $aligns = array('L','L','L','L');
        $border=1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);
    
        $table = array(array("Bill Date","$purchaseDate","Created By","$created_by"));
        $lineheight = 9;
        $fontsize = 10;
        $widths = array(47.25,47.25,47.25,47.25);
        $aligns = array('L','L','L','L');
        $border=1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);
    
        $table = array(array("Due Date","$dueDate","Bill Type","Original"));
        $lineheight = 9;
        $fontsize = 10;
        $widths = array(47.25,47.25,47.25,47.25);
        $aligns = array('L','L','L','L');
        $border=1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);
    }
    
    $pdf->SetFont("Arial","B",8);
    $pdf->SetTextColor(0,0,0,0);
    $pdf->SetDrawColor(0,0,0,0);
    $pdf->SetLineWidth(0);
    $pdf->SetFillColor(232,232,232);
    $pdf->Cell(9,10,"SLNO",1,0,'C',1);
    $pdf->Cell(106,10,"Product Description",1,0,'C',1);
    $pdf->Cell(20,10,"RATE",1,0,'C',1);
    $pdf->Cell(12,10,"QTY",1,0,'C',1);
    $pdf->Cell(41.8,10,"TOTAL",1,1,'C',1);
    
    $pricevaltot = 0;
    $tot_total = 0;
    $tot_qty = 0;
    
    $pdf->SetFillColor(255,255,255);
    
    $slno = 1; // Initialize the counter variable outside the loop
    
    foreach ($invoice_items as $item) {
        $itemnum = $slno; // Use the counter variable for the item number
        $products = isset($item['product']) ? mysqli_real_escape_string($conn, $item['product']) : '';
        $proddesc = isset($item['prod_desc']) ? mysqli_real_escape_string($conn, $item['prod_desc']) : '';
        $qtyvalue = isset($item['qty']) ? floatval($item['qty']) : 0;
        $qtyvalue = isset($item['qty']) ? floatval($item['qty']) : floatval($item['pqty']);
        echo "<script>alert('vefore additional charges  update created successfully".$qtyvalue."');</script>";
        $priceval = isset($item['price']) ? floatval($item['price']) : 0;
    
        // Calculate line total (Qty × Rate)
        $line_total = $priceval * $qtyvalue;
    
        // Update totals
        $tot_qty += $qtyvalue;
        $pricevaltot += $priceval;
        $tot_total += $line_total;
    
        $table = array(array($itemnum, $products . "\n" . $proddesc, $priceval, $qtyvalue, $line_total));
    
        $lineheight = 7;
        $fontsize = 10;
        $widths = array(9, 106, 20, 12, 41.8);
        $aligns = array('C', 'L', 'R', 'C', 'R');
        $border = 1;
        $pdf->plot_table($widths, $lineheight, $table, $border, $aligns);
    
        $slno++; 
    }
    
    // Total calculation
    $totWords = numberToWords($tot_total);
    
    // Display total at the bottom
    $pdf->Cell(120, 6, "Amount in words: $totWords", 'BL', 0, 'L');
    $pdf->Cell(30, 6, "Bill Total", 'B', 0, 'R');
    $pdf->Cell(39, 6, "INR " . number_format($tot_total, 2), 'BR', 1, 'R');
    
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
    
    $pdf->SetFont("Arial", "B", 8);
    
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
    $filename = "invoice/" . $file_name;
    file_put_contents($filename, $pdfdoc);
    
    $update_file_query = "UPDATE bill_of_supply SET bill_file=? WHERE id=?";
    $stmt_file = $conn->prepare($update_file_query);
    if (!$stmt_file) {
        die("Error preparing file update statement: " . $conn->error);
    }
    $stmt_file->bind_param("si", $filename, $bill_id);
    if (!$stmt_file->execute()) {
        die("Error updating bill file: " . $stmt_file->error);
    }
    $stmt_file->close();
}
    echo '<script>alert("Successfully Updated bill");';
    echo 'window.location.href = "view-billsupply-action.php?bill_id=' . $bill_id . '";</script>';
?>