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

    // Start transaction
     include("fpdf/fpdf.php");
    $conn->begin_transaction();
    
    try {
        
    error_log("POST Data: " . print_r($_POST, true));
    
    // Retrieve form data
    $bill_id = $_POST['bill_id'];
      $cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id']);
    error_log("Bill ID: " . $bill_id);
    $purchaseNo = $_POST['purchaseNo'];
    $purchaseDate = $_POST['purchaseDate'];
    $dueDate = $_POST['dueDate'];
    $terms_condition = $_POST['terms_condition'];
    $note = $_POST['note'];
    // $grand_total = $_POST['grand_total'];
    $pack_price = isset($_POST['pack_price']) ? $_POST['pack_price'] : 0;
    $bill_code = mysqli_real_escape_string($conn, $_POST['purchaseNo']);
    $created_by = $_SESSION['name'];
    

    $gtotal = 0;
    $total_tax = 0;
    
        
          // Generate PDF
 // Generate PDF using FPDF
    class PDF extends FPDF {
       function plot_table($widths, $lineheight, $table, $border, $aligns = array(), $fills = array(), $backgroundColors = array(),$links = array()) {
      $func = function($text, $c_width){
            $len=strlen($text);
            $twidth = $this->GetStringWidth($text);
            $split = 0;
if ($twidth != 0) {
     $split = floor($c_width * $len / $twidth);
}
            // $split = floor($c_width * $len / $twidth);
            $w_text = explode( "\n", wordwrap( $text, $split, "\n", true));
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
                    $imageWidth = 30;  // Replace with your desired width
                    $imageHeight = 15;  // Keep the same height as the text
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

    
    $pdf = new PDF('P', 'mm', 'A4');
    $file_name = md5(rand()) . '.pdf';
    
    $pdf->AddPage();
    $pdf->SetFont("Arial", "", 10);
    
    $pdf->SetFillColor(232,232,232);
    
    $pdf->SetFont('Arial', '', 9);
    // $table = array(array("img/logo.png","\n KRIKA MKB CORPORATION PRIVATE LIMITED(iiiQbets) \n Skyline Beverly Park, # D 402, Amruthahalli Main Road, Amruthahalli,Amruthal,Bangalore - 560092, \n KARNATAKA \nEmail: abhijith.mavatoor@gmail.com, Phone: 9481024700 \n GSTIN: 29AAICK7493G1ZX \n"));

       $result_br = mysqli_query($conn, "SELECT *  FROM add_branch where branch_id='$branch_id'");

if ($row_br = mysqli_fetch_array($result_br)) {

  $table = array(array("img/logo.png","\n {$row_br['branch_name']} \n {$row_br['address_line1']}, {$row_br['address_line2']}, {$row_br['city']} - {$row_br['pincode']}, \n {$row_br['state']} \nEmail: {$row_br['office_email']}, Phone: {$row_br['phone_number']} \n GSTIN: {$row_br['GST']} \n"));

}

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
    
    $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id WHERE customer_master.id= '$cst_mstr_id'");
    
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



// Header of the table
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(0, 0, 0);    
$pdf->SetFillColor(220, 220, 220); // Light gray background for headings

$header = ['S.No', 'Product Description', 'Rate', 'Qty', 'Discount','Total'];

// Adjusted column widths to exactly fit 190 mm
$widths = [10, 97, 20, 15, 20,27]; // Adjusted for proper alignment

// Display header
foreach ($header as $key => $col) {
   $pdf->Cell($widths[$key], 8, $col, 1, 0, 'C', true);
}
$pdf->Ln(); // Move to the next row after headers

$pdf->SetFillColor(255, 255, 255); // Reset to white background for table content


    $pricevaltot = 0;
    $tot_total = 0;
    $tot_qty = 0;
    
    $pdf->SetFillColor(255,255,255);
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




     $transportMode = $_POST['transportMode'] ?? 'None';
        $vehicleNo ="";
        switch ($transportMode) {
            case 'Road':
             $vehicleNo =$_POST['roadVehicleNumber'];
                break;
            case 'Rail':
             $vehicleNo=$_POST['trainNumber'];
                 break;
            case 'Air':
             $vehicleNo = $_POST['flightNumber'];
                break;
            case 'Ship':
             $vehicleNo = $_POST['shipVoyageNumber'];
                break;
            
        }

if (!empty($_POST['products'])) {
    include("config.php");
  $cgsttotal =0;
        $sgsttotal = 0;
        $pricevaltot =0;
        $tot_total =0;
        $tot_qty=0;
        $nontax_tot_amt=0;
        $gsttot =0;
        $gsttotamt=0;

 // ✅ Step 1: Store previous quantities before deleting
    $previous_quantities = []; 

$sql_get_prev_qty = "SELECT product_id, qty FROM billsupply_items WHERE bill_id = ?";
$stmt_get_prev_qty = $conn->prepare($sql_get_prev_qty);
$stmt_get_prev_qty->bind_param("i", $bill_id);
$stmt_get_prev_qty->execute();
$result_prev_qty = $stmt_get_prev_qty->get_result();

while ($row = $result_prev_qty->fetch_assoc()) {
    $previous_quantities[$row['product_id']] = $row['qty'];
}


$stmt_get_prev_qty->close();


$deleted_item_ids = explode(',', $_POST['delete_item_ids']);
    foreach ($deleted_item_ids as $item_id) {
        // Get the product ID and quantity for the deleted item
        $stmt_get_qty = $conn->prepare("SELECT product_id, qty FROM billsupply_items WHERE id = ?");
        $stmt_get_qty->bind_param("i", $item_id);
        $stmt_get_qty->execute();
        $result111 = $stmt_get_qty->get_result();

        if ($row111 = $result111->fetch_assoc()) {
            $productid = $row111['product_id'];
            $qty = $row111['qty'];
        }

        // Now update the inventory to restore the stock that was deducted
        if (isset($qty) && isset($productid) && $qty > 0) {
          $stmt_inventory = $conn->prepare("UPDATE inventory_master 
                                  SET stock_out = stock_out - ?, 
                                      balance_stock = (opening_stock + stock_in) - stock_out 
                                  WHERE id = ?");


            $stmt_inventory->bind_param("di", $qty, $productid);
            if ($stmt_inventory->execute()) {
                // Successfully updated the inventory
                error_log("Inventory updated successfully for product ID: $productid and quantity: $qty (Deleted)", 3, "inventory_update.log");
            } else {
                error_log("Failed to update inventory for product ID: $productid (Deleted)", 3, "inventory_update_error.log");
            }
            $stmt_inventory->close();
        }

         $sql_st_master = "INSERT INTO stock_master (product_id, reference_no,quantity, add_and_deduct, remark, date, created_by, created_on)   VALUES (?,?, ?, ?, ?, ?, ?, ?)";

        // Prepare statement
        $stmt_st_master = $conn->prepare($sql_st_master);
        $transaction_type = "Bill-of-supply-Deleted"; // This can be changed based on the transaction type
         $remark = "Bill of supply Deleted"; // Can be customized
        $current_timestamp = date('Y-m-d H:i:s'); // Get current timestamp
// echo $productid;
        $stmt_st_master->bind_param("isisssss", $productid,$bill_code, $qty, $transaction_type, $remark,
        $current_timestamp, $created_by,$current_timestamp);

    // Execute query
        if ($stmt_st_master->execute()) {
             // echo "frm update";

            echo "Stock transaction recorded successfully!";
        } else {
            echo "failed to execute";
            echo "Error updating stock transaction: " . $conn->error;
        }   

        $stmt_st_master->close();


        // Delete the product item from the invoice
        
    }
// }

$delete_items_sql = "DELETE FROM billsupply_items WHERE bill_id=?";
   
        $stmt_delete = $conn->prepare($delete_items_sql);
        $stmt_delete->bind_param("i", $bill_id);
        $stmt_delete->execute();

$itemno=0;
    foreach ($_POST['products'] as $key => $product) {
        $itemno++;
                    error_log("Processing product: " . print_r($product, true));
                $pprice = isset($product['pprice']) ? $product['pprice'] : 0;
                $product_name = $product['pname'];
                $pqty = isset($product['pqty']) ? $product['pqty'] : 0;
                $ptotal = isset($product['ptotal']) ? $product['ptotal'] : 0;
                $pitemno = isset($product['pitemno']) ? $product['pitemno'] : '';
                $pproductid = isset($product['pproductid']) ? $product['pproductid'] : '';
                $attr_id = isset($product['attr_id']) ? $product['attr_id'] : null;
                $discountval = $product['discountval'];
                $product_id=$product['pproductid'];
                  // Calculate line total (Qty × Rate)
        // $line_total = floatval($pprice) * floatval($pqty);
            
                 if($discountval > 0)
        {
            $discount_amount = ($pprice * $discountval) / 100;
        
        // Step 2: Subtract the discount from the original price
        $discounted_price = $pprice - $discount_amount;
        
        // Step 3: Calculate the line total with quantity
        $line_tot = $discounted_price * $pqty;
        }
        else
        {
             $line_tot = floatval($pqty) * floatval($pprice);
        }


        
        // $l1 = $line_tot + $cgstval + $sgstval + $igstval;
                  
                    // Skip empty product entries
                    // if (empty($product_id) || empty($product_name)) {
                    //     continue;
                    // }
            
                  $quantity =$pqty ;
              $pdf->Cell($widths[0], 8, $itemno, 1, 0, 'C'); // S.No
        $pdf->Cell($widths[1], 8, $product_name, 1, 0, 'L'); 
        $pdf->Cell($widths[2], 8, number_format($pprice, 2), 1, 0, 'C'); // Rate
        $pdf->Cell($widths[3], 8, $quantity, 1, 0, 'C'); // Qty
        $pdf->Cell($widths[4], 8, $discounted_price, 1, 0, 'C'); 
        $pdf->Cell($widths[5], 8, number_format($line_tot, 2), 1, 1, 'C'); // Total
                   
                        // Insert new items


                        $insert_items_query = "INSERT INTO billsupply_items (bill_id, itemno, product_id, product, prod_desc, qty, price, line_total, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_items = $conn->prepare($insert_items_query);
                        if (!$stmt_items) {
                            die("Error preparing insert items statement: " . $conn->error);
                        }
                        $stmt_items->bind_param("iiissssss", $bill_id, $itemno, $product_id, $product_name, $product_desc, $quantity, $pprice, $line_tot, $line_tot);
                        if (!$stmt_items->execute()) {
                            die("Error inserting bill items: " . $stmt_items->error);
                        }
                        $stmt_items->close();
                   
            
                    // Add line total to sub_total
                    $gtotal += $line_tot;

// Get previous quantity for this product
   
            $sql_st_master1 = "INSERT INTO stock_master (product_id, reference_no,quantity, add_and_deduct, remark, date, created_by, created_on)   VALUES (?,?, ?, ?, ?, NOW(), ?, NOW())";

// Prepare statement
        $stmt_st_master1 = $conn->prepare($sql_st_master1);
        $transaction_type = "Bill of Supply"; // This can be changed based on the transaction type
        $remark = "Bill of supply"; // Can be customized

        $stmt_st_master1->bind_param("isisss", $productid,$bill_code, $quantity, $transaction_type, $remark, $created_by);

    // Execute query
        if ($stmt_st_master1->execute()) {
            echo "Stock transaction recorded successfully!";
        } else {
            echo "Error updating stock transaction: " . $conn->error;
        }   

        $stmt_st_master1->close();

 $prev_qty = isset($previous_quantities[$productid]) ? $previous_quantities[$productid] : 0;


if ($quantity != $prev_qty) {
      
    if ($quantity > $prev_qty) {
        
        // ✅ Case:xxxxxxxIncreased Quantity (Stock-Out Increases)
        $diff_qty = $quantity - $prev_qty;
       // echo "<script>alert('Difference between existing quantity $quantity and previous quantity $prev_qty is: $diff_qty');</script>";
        // echo "Difference between existing quantity $quantity and previous quantity $prev_qty is: $diff_qty";

        $sql_update_stock = "UPDATE inventory_master 
            SET stock_out = stock_out + ?, 
                balance_stock = (opening_stock + stock_in) - stock_out,  
                last_updated_by = ?,  
                last_updated_at = NOW()  
            WHERE id = ?";
    } else {
        
        // ✅ Case: Reduced Quantity (Stock-Out Decreases)
        $diff_qty = $prev_qty - $quantity;
    // echo "<script>alert('Difference between  previous quantity $prev_qty and existing quantity $quantity is: $diff_qty');</script>";
    // echo "Difference between  previous quantity $prev_qty and existing quantity $quantity is: $diff_qty";

        $sql_update_stock = "UPDATE inventory_master 
            SET stock_out = stock_out - ?, 
                balance_stock = (opening_stock + stock_in) - stock_out,  
                last_updated_by = ?,  
                last_updated_at = NOW()  
            WHERE id = ?";
    }

    // Execute update
    $stmt_update_stock = $conn->prepare($sql_update_stock);
    $stmt_update_stock->bind_param("isi", $diff_qty, $created_by, $productid);
    $stmt_update_stock->execute();
    $stmt_update_stock->close();
}      
              
            
            
}
    }

// Rest of your code...


        



    
    // Total calculation
    $totWords = numberToWords($gtotal);
    
    // Display total at the bottom
    $pdf->Cell(120, 6, "Amount in words: $totWords", 'BL', 0, 'L');
    $pdf->Cell(30, 6, "Bill Total", 'B', 0, 'R');
    $pdf->Cell(39, 6, "INR " . number_format($gtotal, 2), 'BR', 1, 'R');
    
$pdf->SetFont("Arial", "B", 8);

    $x = $pdf->GetX();
    $y = $pdf->GetY();
    
    $pdf->Cell(27, 6, "Bank Name", 'L', 0, 'L');
    $pdf->Cell(66, 6, "IDFC BANK LIMITED", 'R', 0, 'L');
    $pdf->MultiCell(96, 6, "Note: $note", 'LTR', 1, 'L');
    
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
    $filename = "bill_of_supply/" . $file_name;
    file_put_contents($filename, $pdfdoc);
    
    // After the loop, update the ledger with the grand total
$sql_update_ledger = "UPDATE ledger SET amount=?, transaction_date=? WHERE voucher_id=?";
$stmt_update_ledger = $conn->prepare($sql_update_ledger);
$stmt_update_ledger->bind_param("dsi", $gtotal, $purchaseDate, $bill_id);
$stmt_update_ledger->execute();
$stmt_update_ledger->close();


  $update_invoice_query = "UPDATE bill_of_supply SET bill_code=?, bill_date=?, due_date=?, total_amount=?, grand_total=?, due_amount=?,terms_condition=?, note=?, bill_file=? WHERE id=?";
            $stmt_invoice = $conn->prepare($update_invoice_query);
            if (!$stmt_invoice) {
                die("Error preparing bill update statement: " . $conn->error);
            }
            $stmt_invoice->bind_param("sssssssssi", $purchaseNo, $purchaseDate, $dueDate,$gtotal, $gtotal, $gtotal, $terms_condition, $note, $filename,$bill_id);
            if (!$stmt_invoice->execute()) {
                die("Error updating invoice: " . $stmt_invoice->error);
            }
            $stmt_invoice->close();


// Step 2: Insert Updated Additional Charges
if (isset($_POST['additionalCharges']['charge_type']) && isset($_POST['additionalCharges']['charge_price'])) {
       // Step 1: Delete Old Additional Charges
        $sql_delete_charges = "DELETE FROM billsupply_additional_charges WHERE bill_id = ?";
        $stmt_delete_charges = $conn->prepare($sql_delete_charges);
        $stmt_delete_charges->bind_param("i", $inv_id);
        $stmt_delete_charges->execute();
        $stmt_delete_charges->close();
    foreach ($_POST['additionalCharges']['charge_type'] as $key => $chargeType) {
        $chargePrice = isset($_POST['additionalCharges']['charge_price'][$key]) 
            ? floatval($_POST['additionalCharges']['charge_price'][$key]) 
            : 0; // Default to 0 if not set

        $chargeType = mysqli_real_escape_string($conn, $chargeType);

        $sql_insert_charge = "INSERT INTO   billsupply_additional_charges (bill_id, charge_type, charge_price, created_on)
                              VALUES (?, ?, ?, NOW())";
        $stmt_insert_charge = $conn->prepare($sql_insert_charge);
        $stmt_insert_charge->bind_param("isd", $bill_id, $chargeType, $chargePrice);

        if (!$stmt_insert_charge->execute()) {
            throw new Exception("Failed to save additional charges: " . $stmt_insert_charge->error);
        }
        $stmt_insert_charge->close();
    }
}

        // Insert transportation details
     
        // Insert other details
   // $sql = "UPDATE  invoice_other_details SET invoice_id='$id', po_number=($_POST['other_poNumber'] ?? ''), po_date=($_POST['other_poDate'] ?? ''), challan_number=($_POST['challanNumber'] ?? ''), due_date=($_POST['other_dueDate'] ?? ''), ewaybill_number=($_POST['ewayBill'] ?? ''), sales_person=($_POST['salesPerson'] ?? ''), reverse_charge=($_POST['reverseCharge'] ?? '0'), tcs_value=($_POST['tcsValue'] ?? '0'), tcs_type=($_POST['tcsTax'] ?? ''), NOW()) ";
  // echo "<script>alert('vefore invoice other detials update created successfully');</script>";

$sql_other_details = "UPDATE billsupply_other_details 
        SET po_number = '" . ($_POST['other_poNumber'] ?? '') . "', 
            po_date = '" . ($_POST['other_poDate'] ?? '') . "', 
            challan_number = '" . ($_POST['challanNumber'] ?? '') . "', 
            due_date = '" . ($_POST['other_dueDate'] ?? '') . "', 
            ewaybill_number = '" . ($_POST['ewayBill'] ?? '') . "', 
            sales_person = '" . ($_POST['salesPerson'] ?? '') . "', 
            reverse_charge = '" . ($_POST['reverseCharge'] ?? '0') . "', 
            tcs_value = '" . ($_POST['tcsValue'] ?? '0') . "', 
            tcs_type = '" . ($_POST['tcsTax'] ?? '') . "', 
            updated_on = NOW() 
        WHERE  bill_id = '$bill_id'"; // Replace 'your_condition' with the actual condition
if (!$conn->query($sql_other_details)) {
            throw new Exception("Failed to save other details: " . $conn->error);
        }

    // $update_file_query = "UPDATE bill_of_supply SET bill_file=? WHERE id=?";
    // $stmt_file = $conn->prepare($update_file_query);
    // if (!$stmt_file) {
    //     die("Error preparing file update statement: " . $conn->error);
    // }
    // $stmt_file->bind_param("si", $filename, $bill_id);
    // if (!$stmt_file->execute()) {
    //     die("Error updating bill file: " . $stmt_file->error);
    // }
    // $stmt_file->close();
      // If everything is successful
        $conn->commit();
       echo '<script>alert("Successfully Updated bill");';
       echo 'window.location.href = "view-billsupply-action.php?bill_id=' . $bill_id . '";</script>';
} catch (Exception $e) {
        // If there's an error, rollback the transaction
        $conn->rollback();
        error_log("Error updating bill: " . $e->getMessage());
        echo '<script>alert("Error updating bill: ' . $e->getMessage() . '");';
       // echo 'history.back();</script>';
    }
}
?>