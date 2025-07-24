<?php

session_start();
if (!isset($_SESSION['LOG_IN'])) {
    header("Location:login.php");
    exit();
}

if (!isset($_SESSION['business_id'])) {
    header("Location:dashboard.php");
    exit();
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    $branch_id = $_SESSION['branch_id'] ?? null;
}

include("config.php");
include("fpdf/fpdf.php");

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Capture POST data and validate
$customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice'] ?? '');
$customer_email = mysqli_real_escape_string($conn, $_POST['customer_email'] ?? '');
$cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id'] ?? '');
$total_amount = mysqli_real_escape_string($conn, $_POST['total_amount'] ?? '0');
$note = mysqli_real_escape_string($conn, $_POST['note'] ?? '');
$dnote_code = mysqli_real_escape_string($conn, $_POST['debitNoteNo'] ?? '');
$dnoteDate = mysqli_real_escape_string($conn, $_POST['debitNoteDate'] ?? date('Y-m-d'));
$terms = mysqli_real_escape_string($conn, $_POST['terms_condition'] ?? '');
$created_by = $_SESSION['name'] ?? '';
$purchase_invoice_id = mysqli_real_escape_string($conn, $_POST['purchaseInvoiceDropdown'] ?? '');
$adjusted_amount = mysqli_real_escape_string($conn, $_POST['adjusted_amount'] ?? '0');
$additional_charges = $_POST['additional_charges'] ?? [];
$filename = '';

// Debugging
echo "Customer Name: $customer_name<br>";
echo "Debit Note Code: $dnote_code<br>";
echo "Customer ID: " . $cst_mstr_id;

if ($customer_name != "") {
    $result1 = mysqli_query($conn, "SELECT MAX(id) as max_id FROM debit_note");
    if (!$result1) {
        die("Error Fetching Max ID: " . $conn->error);
    }
    $id = ($row1 = mysqli_fetch_assoc($result1)) ? $row1['max_id'] + 1 : 1;

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont("Arial", "", 10);
    $file_name = "DN-" . $dnote_code . '.pdf';
    // $filename = "debit_note/" . $file_name;
// Add Company Header
// Add Page Border
$pdf->SetDrawColor(200, 200, 200); // Light gray color
$pdf->Rect(5, 5, 200, 287); // Draw border: (x, y, width, height)

// Add Debit Note Header
$pdf->Ln(5);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(0, 10, "Debit Note", 0, 1, 'C');
// Add Company Details (Left-Aligned)
   // Add Company Header (Left-Aligned)
$result1 = mysqli_query($conn, "SELECT * FROM add_branch WHERE branch_id='$branch_id'");
if ($row1 = mysqli_fetch_array($result1)) {
    $pdf->SetFont("Arial", "B", 12);
    $pdf->SetTextColor(50, 50, 50); // Dark gray text
    $pdf->Cell(0, 6, $row1['branch_name'], 0, 1, 'L');
    $pdf->SetFont("Arial", "", 10);
    $pdf->MultiCell(0, 6, "{$row1['address_line1']}, {$row1['address_line2']}, {$row1['city']} - {$row1['pincode']}, {$row1['state']}\nEmail: {$row1['office_email']}\nPhone: {$row1['phone_number']}\nGSTIN: {$row1['GST']}", 0, 'L');
    $pdf->Ln(15);
}

// Add Debit Note Header (Right-Aligned)
$pdf->SetXY(140, 20); // Adjust X and Y coordinates for placement
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(50, 7, "Debit Note", 0, 1, 'R'); // Title
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(180, 6, "Note #: $dnote_code", 0, 1, 'R'); // Debit Note Number
$pdf->Cell(180, 6, "Note Date: $dnoteDate", 0, 1, 'R'); // Date
$sql = "SELECT  invoice_code FROM pi_invoice WHERE id= '$purchase_invoice_id'";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
  
  $ic = $row['invoice_code'];
$pdf->Cell(180, 6, "Reference: $ic", 0, 1, 'R'); // Date
     
    }
$pdf->Cell(180, 6, "Created By: $created_by", 0, 1, 'R'); // Created By
$pdf->Ln(10);
// Add Customer and Address Details
// Draw the common border for Supplier and Address
$pdf->SetFont("Arial", "B", 10);
$pdf->SetFillColor(245, 245, 245); // Light gray fill color

// Header row
// $pdf->Cell(190, 6, "", 1, 1, 'L', false); // Create the top border of the common table
$pdf->Cell(95, 6, "Supplier", 1, 0, 'L', true);
$pdf->Cell(95, 6, "Address", 1, 1, 'L', true);

// Data row
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(95, 6, $customer_name, 1, 0, 'L');
$result2 = mysqli_query($conn, "SELECT * FROM address_master WHERE customer_master_id='$cst_mstr_id'");
if ($row2 = mysqli_fetch_array($result2)) {
    $bs = $row2['s_state'];
$pdf->Cell(95, 6, $bs, 1, 1, 'L');
}

// Add bottom border to finish
$pdf->Cell(190, 0, '', 1, 1, 'L', false);

$pdf->Ln(10);

// Add Product Details
$pdf->SetFont("Arial", "B", 10);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(120, 6, "Description", 1, 0, 'L');
$pdf->Cell(70, 6, "Debit Amount", 1, 1, 'R');


 $result1=mysqli_query($conn,"select id from debit_note where id=(select max(id) from debit_note)");
  if($row1=mysqli_fetch_array($result1))
  {
    $id=$row1['id']+1;
  }else
  {
    $id=1;
  }
  // Add Product Details
// $pdf->Ln(5);
// $pdf->SetFont("Arial", "B", 10);
// $pdf->Cell(120, 6, "Description", 1, 0, 'L');
// $pdf->Cell(70, 6, "Debit Amount", 1, 1, 'R');
    $conn->begin_transaction();
    try {
       
// $dnote_id = $conn->insert_id;
// foreach ($_POST['products'] as $key => $productData) {
//     $product = mysqli_real_escape_string($conn, $productData['product'] ?? '');
//     $description = mysqli_real_escape_string($conn, $productData['prod_desc'] ?? '');
//     $quantity = mysqli_real_escape_string($conn, $productData['qty'] ?? '0');
//     $price = mysqli_real_escape_string($conn, $productData['price'] ?? '0');
//     $gst = mysqli_real_escape_string($conn, $productData['gst'] ?? '0');
//     $gst_amt = mysqli_real_escape_string($conn, $productData['gst_amt'] ?? '0');
//     $total = mysqli_real_escape_string($conn, $productData['total'] ?? '0');

//     $sql_item = ("INSERT INTO debit_note_items (dnote_id, product, prod_desc, qty, price, line_total, gst, gst_amt, total, created_by) 
//                   VALUES ('$id', '$product', '$description', '$quantity', '$price','($quantity * $price)', '$gst', '$gst_amt', '$total', '$created_by')");

//             if (!$conn->query($sql_item)) {
//                 throw new Exception("Error Inserting Debit Note Item: " . $conn->error);
//             }
//              $description = $productData['prod_desc'] ?? '';
//     $total = number_format((float)$productData['total'], 2, '.', '');
//     $pdf->Cell(60, 6, $description, 1, 0, 'L');
//     $pdf->Cell(30, 6, $total, 1, 1, 'R');
//         }

$total_amount = 0;
$total_gst_amount = 0;
$total_cess_amount = 0;
function convertNumberToWords($number) {
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'forty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convertNumberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convertNumberToWords(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convertNumberToWords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convertNumberToWords($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

foreach ($_POST['products'] as $productData) {
    $productid = $productData['productid'] ?? ''; // Get the product name

    $productName = $productData['product'] ?? ''; // Get the product name
    $productDescription = $productData['prod_desc'] ?? ''; // Get the product description
    $quantity = floatval($productData['qty'] ?? 0);
    $price = floatval($productData['price'] ?? 0);
    $gst = floatval($productData['gst'] ?? 0);
     $discount = floatval($productData['discount']);
 $gst_amount = floatval($productData['gst_amt']);
    // $gst_amount = $quantity * $price * ($gst / 100);

    $cess_rate = floatval($productData['cess_rate'] ?? 0);
    $cess_amount = floatval($productData['cess_rate'] ?? 0);
    $line_total = ($quantity * $price) + $gst_amount + $cess_amount;
   
    $total_amount += $line_total;
    $total_gst_amount += $gst_amount;
    $total_cess_amount += $cess_amount;

    // Insert into database (assuming you have valid values in $id and $created_by)
    $sql_item = "INSERT INTO debit_note_items (dnote_id, productid, product, prod_desc, qty, price, line_total, gst, gst_amt, cgst, sgst, igst, cess_rate, cess_amt,discount, total, created_by) 
                 VALUES ('$id', '$productid', '$productName', '$productDescription', '$quantity', '$price', '$line_total', '$gst', '$gst_amount', '{$productData['cgst']}', '{$productData['sgst']}', '{$productData['igst']}', '$cess_rate', '$cess_amount','$discount', '$line_total', '$created_by')";
    $conn->query($sql_item);

// Assuming you have the product's `sku` and the quantity being adjusted
$productid = $productData['productid']; // Product SKU from the debit note
$adjustedQuantity = floatval($productData['qty']); // Quantity being reduced

// Fetch the current stock from `inventory_master`
$sqlFetch = "SELECT sold_stock, opening_stock FROM inventory_master WHERE id = '$productid'";
$resultFetch = $conn->query($sqlFetch);

if ($resultFetch && $row = $resultFetch->fetch_assoc()) {
    $currentSoldStock = floatval($row['sold_stock']);
    $currentOpeningStock = floatval($row['opening_stock']);
    
    // Update sold_stock: Reduce the quantity
    $newSoldStock = max(0, $currentSoldStock - $adjustedQuantity);

    // Update opening_stock: Increase the quantity (if applicable)
    $newOpeningStock = $currentOpeningStock + $adjustedQuantity;

    // Update the inventory in `inventory_master`
    $sqlUpdate = "
        UPDATE inventory_master 
        SET 
            sold_stock = '$newSoldStock', 
            opening_stock = '$newOpeningStock', 
            last_updated_by = '$created_by', 
            last_updated_at = NOW() 
        WHERE id = '$productid'
    ";
    if ($conn->query($sqlUpdate)) {
        echo "Inventory updated successfully for SKU: $productSKU";
    } else {
        echo "Error updating inventory: " . $conn->error;
    }
} else {
    echo "Error fetching inventory data for SKU: $productSKU";
}

    // Format description and amount for the PDF
    $amount_in_words = ucwords(convertNumberToWords($line_total)); // Convert amount to words
$pdf->SetFont("Arial", "", 9);
    // Add the row to the PDF
   $description = "Debit note on Purchase Invoice for an amount of Rs." . number_format($line_total, 2) . " ($amount_in_words)";
    $description .= "\n» " . $productName; // Add product name on a new line

   $multiCellWidth = 120; // Width of the MultiCell
    $lineHeight = 6; // Height of each line in MultiCell
    $descriptionLines = $pdf->GetStringWidth($description) / $multiCellWidth;
    $cellHeight = ceil($descriptionLines) * $lineHeight;

    // Add Description and Debit Amount
    $x = $pdf->GetX(); // Current X position
    $y = $pdf->GetY(); // Current Y position

    $pdf->MultiCell($multiCellWidth, $lineHeight, $description, 1, 'L'); // Add description
    $pdf->SetXY($x + $multiCellWidth, $y); // Move to the right for the Debit Amount column
    $pdf->Cell(70, $cellHeight, number_format($line_total, 2), 1, 1, 'R'); // Align debit amount to match description height

}


        if (!empty($additional_charges)) {
            foreach ($additional_charges as $charge_type => $charge_price) {
                $charge_type = mysqli_real_escape_string($conn, $charge_type);
                $charge_price = mysqli_real_escape_string($conn, $charge_price);

                $sql_charge = "INSERT INTO note_additional_charges (note_id, charge_type, charge_price) 
                               VALUES ('$id', '$charge_type', '$charge_price')";
                if (!$conn->query($sql_charge)) {
                    throw new Exception("Error Inserting Additional Charges: " . $conn->error);
                }
            }
        }




        // $pdf->Cell(0, 10, "Debit Note: $dnote_code", 0, 1, 'C');
        // $pdf->Cell(0, 10, "Customer: $customer_name", 0, 1);
        // if (!$pdf->Output('F', $filename)) {
        //     throw new Exception("Failed to generate PDF");
        // }


// $pdf = new FPDF('P', 'mm', 'A4');
// $pdf->AddPage();
// $pdf->SetFont("Arial", "", 10);


// Add Product Details
// $pdf->Ln(5);
// $pdf->SetFont("Arial", "B", 10);
// $pdf->Cell(60, 6, "Description", 1, 0, 'L');
// $pdf->Cell(30, 6, "Debit Amount", 1, 1, 'R');

// $pdf->SetFont("Arial", "", 10);
// foreach ($_POST['products'] as $productData) {
//     $description = $productData['prod_desc'] ?? '';
//     $total = number_format((float)$productData['total'], 2, '.', '');
//     $pdf->Cell(60, 6, $description, 1, 0, 'L');
//     $pdf->Cell(30, 6, $total, 1, 1, 'R');
// }

// Add Notes and Footer
    // Add Totals
// $pdf->Ln(5);
$pdf->SetFont("Arial", "B", 10);
// $pdf->SetFillColor(245, 245, 245); // Light gray fill color
$pdf->Cell(120, 6, "Total GST Amount", 1, 0, 'L', true);
$pdf->Cell(70, 6, number_format($total_gst_amount, 2), 1, 1, 'R', true);
$pdf->Cell(120, 6, "Grand Total", 1, 0, 'L', true);
$pdf->Cell(70, 6, number_format($total_amount, 2), 1, 1, 'R', true);
// $pdf->Ln(5);

// Add the row with borders

// Notes and Authorized Signatory Row
$pdf->SetFont("Arial", "B", 10);
// Create a single-row layout

if (empty($note)) {
    $note = " "; // Set a space to ensure the cell has some content
}
$startY = $pdf->GetY();
$currentX = $pdf->GetX();

// First MultiCell
$pdf->MultiCell(100, 6, "Note:\n$note", 0, 'L');
$endYFirst = $pdf->GetY();

// Reset position for second MultiCell
$pdf->SetXY($currentX + 100, $startY);
$pdf->MultiCell(89, 6, "For \n\n Authorised Signatory", 0, 'L');
$endYSecond = $pdf->GetY();

// Determine the maximum Y position reached
$maxY = max($endYFirst, $endYSecond);

// Draw rectangles for borders
$pdf->Rect($currentX, $startY, 100, $maxY - $startY, 'L'); // Left border for first cell
$pdf->Rect($currentX + 100, $startY, 89, $maxY - $startY, 'R'); // Right border for second cell

// Add Thank You Note
// $pdf->Ln(10);
$pdf->SetFont("Arial", "I", 10);
$pdf->Cell(0, 10, "This is a computer-generated Debit Note. Thank you!", 0, 1, 'C');


// $pdf->Cell(0,10,"Thank you for your Business!",1,1,'C');


ob_end_clean();

// a random hash will be necessary to send mixed content
$separator = md5(time());

// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;

// attachment name
$filename = "debit_note/".$file_name;


// encode data (puts attachment in proper format)
 $pdfdoc = $pdf->Output('S');
 // $pdfdoc = $pdf->Output('I');


file_put_contents($filename, $pdfdoc);
//$pf = $pdf->Output();
// Save PDF
// if (!$pdf->Output('F', $filename)) {
//     throw new Exception("Failed to generate PDF");
// }
$sql = "INSERT INTO debit_note (dnote_code, dnote_file, purchase_invoice_id, customer_id, branch_id, customer_name, email, dnote_date, total_amount, total_gst_amount, total_cess_amount, adjusted_amount, terms_condition, note, status, created_by) 
        VALUES ('$dnote_code', '$filename', '$purchase_invoice_id', '$cst_mstr_id', '$branch_id', '$customer_name', '$customer_email', '$dnoteDate', '$total_amount', '$total_gst_amount', '$total_cess_amount', '$adjusted_amount', '$terms', '$note', 'pending', '$created_by')";
if (!$conn->query($sql)) {
    throw new Exception("Error Inserting Debit Note: " . $conn->error);
}
 $did = mysqli_insert_id($conn);


$sql_ledger = "INSERT INTO `ledger` (`voucher_id`, `transaction_date`, `transaction_type`, `account_id`, `account_name`, `amount`, `debit_credit`, `receipt_or_voucher_no`,`branch_id`)value($did,'$dnote_date','Debit Note','$cst_mstr_id', '$customer_name','$total_amount','C','$dnote_code','$branch_id')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save other details: " . $conn->error);
        }

        //  $sql = "INSERT INTO debit_note (dnote_code, dnote_file, purchase_invoice_id, customer_id, branch_id, customer_name, email, dnote_date, total_amount, adjusted_amount, terms_condition, note, status, created_by) 
        //         VALUES ('$dnote_code', '$filename', '$purchase_invoice_id', '$cst_mstr_id', '$branch_id', '$customer_name', '$customer_email', '$dnoteDate', '$total_amount', '$adjusted_amount', '$terms', '$note', 'pending', '$created_by')";
        // if (!$conn->query($sql)) {
        //     throw new Exception("Error Inserting Debit Note: " . $conn->error);
        // }
        $conn->commit();
        echo "<script>alert('Debit Note created successfully!'); window.location.href = 'manage-debitnote.php';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    die("Customer Name is required.");
}
?>
