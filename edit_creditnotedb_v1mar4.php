<<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<?php
session_start();
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['business_id'])) {
    header("Location: dashboard.php");
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

// Capture and sanitize POST data
$cnote_id = mysqli_real_escape_string($conn, $_POST['inv_id']);
$customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice'] ?? '');
$customer_email = mysqli_real_escape_string($conn, $_POST['customer_email'] ?? '');
$cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id'] ?? '');
$total_amount1 = floatval($_POST['total_amount'] ?? '0');
$note = mysqli_real_escape_string($conn, $_POST['note'] ?? '');
$cnote_code = mysqli_real_escape_string($conn, $_POST['purchaseNo'] ?? '');
$cnote_date = mysqli_real_escape_string($conn, $_POST['purchaseDate'] ?? date('Y-m-d'));
$terms = mysqli_real_escape_string($conn, $_POST['terms_condition'] ?? '');
$created_by = $_SESSION['name'] ?? '';
$invoice_id = mysqli_real_escape_string($conn, $_POST['purchaseInvoiceDropdown'] ?? '');
$adjusted_amount = floatval($_POST['adjusted_amount'] ?? '0');
$additional_charges = $_POST['additional_charges'] ?? [];
$delete_item_ids = $_POST['delete_item_ids'] ?? '';
$final_gst_amount = mysqli_real_escape_string($conn, $_POST['final_gst_amount']);
$final_cess_amount = mysqli_real_escape_string($conn, $_POST['final_cess_amount']);
$final_taxable_amt = mysqli_real_escape_string($conn, $_POST['final_taxable_amt']);




// Start transaction
$conn->begin_transaction();
try {
    // DELETE Removed Items
    if (!empty($delete_item_ids)) {
        $delete_item_ids = implode(",", array_map('intval', explode(',', $delete_item_ids)));
        $conn->query("DELETE FROM credit_note_items WHERE id IN ($delete_item_ids)");
    }

    // UPDATE Credit Note Detail

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont("Arial", "", 10);
    $file_name = "CN-" . $dnote_code . '.pdf';
    // $filename = "debit_note/" . $file_name;
// Add Company Header
// Add Page Border
$pdf->SetDrawColor(200, 200, 200); // Light gray color
$pdf->Rect(5, 5, 200, 287); // Draw border: (x, y, width, height)

// Add Debit Note Header
$pdf->Ln(5);
$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(0, 10, "Credit Note", 0, 1, 'C');
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
$pdf->Cell(50, 7, "Credit Note", 0, 1, 'R'); // Title
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(180, 6, "Note #: $dnote_code", 0, 1, 'R'); // Debit Note Number
$pdf->Cell(180, 6, "Note Date: $dnoteDate", 0, 1, 'R'); // Date
$sql = "SELECT  invoice_code FROM invoice WHERE id= '$purchase_invoice_id'";
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
$pdf->Cell(95, 6, "Customer", 1, 0, 'L', true);
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
$pdf->Cell(70, 6, "Credit Amount", 1, 1, 'R');


 $result1=mysqli_query($conn,"select id from credit_note where id=(select max(id) from credit_note)");
  if($row1=mysqli_fetch_array($result1))
  {
    $cid=$row1['id']+1;
  }else
  {
    $cid=1;
  }
  // Add Product Details
// $pdf->Ln(5);
// $pdf->SetFont("Arial", "B", 10);
// $pdf->Cell(120, 6, "Description", 1, 0, 'L');
// $pdf->Cell(70, 6, "Debit Amount", 1, 1, 'R');
    // $conn->begin_transaction();
    // try {
       


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


    // LOOP Through Updated Products
    foreach ($_POST['products'] as $productData) {
        $productid = mysqli_real_escape_string($conn, $productData['pproductid'] ?? '');
    $productName = mysqli_real_escape_string($conn, $productData['pname'] ?? '');
    $productDescription = mysqli_real_escape_string($conn, $productData['pdesc'] ?? '');
    $quantity = floatval($productData['pqty'] ?? 0);
    $price = floatval($productData['pprice'] ?? 0);
    $gst = floatval($productData['gst'] ?? 0);
    $cess_rate = floatval($productData['cess_rate'] ?? 0);
    $discount = floatval($productData['discount'] ?? 0);
    $gst_amount = floatval($productData['gst_amount'] ?? 0);
    $cess_amount = floatval($productData['cess_amount'] ?? 0);
    $line_total = floatval($productData['ptotal'] ?? 0);
    $cgst = floatval($productData['cgst'] ?? 0);
    $sgst = floatval($productData['sgst'] ?? 0);
    $igst = floatval($productData['igst'] ?? 0);

    // Debugging: Check if the backend is getting updated values
    error_log("Updating product: $productName - Qty: $quantity - Price: $price - Total: $line_total");

    $sql_update_item = "UPDATE credit_note_items 
        SET productid = '$productid',
            product = '$productName',
            prod_desc = '$productDescription',
            qty = '$quantity',
            price = '$price',
            line_total = '$line_total',
            gst = '$gst',
            gst_amt = '$gst_amount',
            cess_rate = '$cess_rate',
            cess_amt = '$cess_amount',
            discount = '$discount',
            total = '$line_total',
            cgst='$cgst',
            sgst='$sgst',
            igst='$igst'
        WHERE cnote_id = '$cnote_id' AND productid = '$productid'";

    if (!$conn->query($sql_update_item)) {
        throw new Exception("Error updating credit note item: " . $conn->error);
    }

        $sql_old_quantity = "SELECT qty FROM credit_note_items WHERE cnote_id = ? AND productid = ?";
$stmt_old = $conn->prepare($sql_old_quantity);
$stmt_old->bind_param("ii", $cnote_id, $productid);
$stmt_old->execute();
$result_old = $stmt_old->get_result();
$row_old = $result_old->fetch_assoc();
$old_quantity = $row_old['qty'] ?? 0; // Default to 0 if not found
$stmt_old->close();


        // Update stock
        $sql_update_stock = "UPDATE inventory_master 
    SET stock_in = stock_in - ? + ?, 
        balance_stock = (opening_stock + stock_in) - stock_out
    WHERE id = ?";
        
        $stmt_update = $conn->prepare($sql_update_stock);
$stmt_update->bind_param("iii", $old_quantity, $quantity, $productid);

if (!$stmt_update->execute()) {
    throw new Exception("Error updating stock: " . $conn->error);
}
$stmt_update->close();



            // Add PDF description
    $amount_in_words = ucwords(convertNumberToWords($line_total)); // Convert to words
    $description = "Credit note on Invoice for an amount of Rs." . number_format($line_total, 2) . " ($amount_in_words)";
    $description .= "\n» " . $productName; // Add product name

    $multiCellWidth = 120;
    $lineHeight = 6;
    $descriptionLines = $pdf->GetStringWidth($description) / $multiCellWidth;
    $cellHeight = ceil($descriptionLines) * $lineHeight;

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->MultiCell($multiCellWidth, $lineHeight, $description, 1, 'L');
    $pdf->SetXY($x + $multiCellWidth, $y);
    $pdf->Cell(70, $cellHeight, number_format($line_total, 2), 1, 1, 'R');


    }


$pdf->SetFont("Arial", "B", 10);
// $pdf->SetFillColor(245, 245, 245); // Light gray fill color
$pdf->Cell(120, 6, "Total GST Amount", 1, 0, 'L', true);
$pdf->Cell(70, 6, number_format($total_gst_amount, 2), 1, 1, 'R', true);
$pdf->Cell(120, 6, "Grand Total", 1, 0, 'L', true);
$pdf->Cell(70, 6, number_format($total_amount1, 2), 1, 1, 'R', true);
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
$pdf->Cell(0, 10, "This is a computer-generated Credit Note. Thank you!", 0, 1, 'C');


// $pdf->Cell(0,10,"Thank you for your Business!",1,1,'C');


ob_end_clean();

// a random hash will be necessary to send mixed content
$separator = md5(time());

// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;

// attachment name
$filename = "credit_note/".$file_name;


// encode data (puts attachment in proper format)
 $pdfdoc = $pdf->Output('S');
 // $pdfdoc = $pdf->Output('I');


file_put_contents($filename, $pdfdoc);


   $sql_update_credit_note = "UPDATE credit_note  SET cnote_date = '$cnote_date',total_amount = '$total_amount1',adjusted_amount = '$adjusted_amount',terms_condition = '$terms',note = '$note', total_gst_amount='$final_gst_amount',total_cess_amount='$final_cess_amount',
            created_by = '$created_by',created_at = NOW() WHERE id = '$cnote_id'";

    if (!$conn->query($sql_update_credit_note)) {
        throw new Exception("Error Updating Credit Note: " . $conn->error);
    }


    // Update Ledger
    $sql_update_ledger = "UPDATE ledger 
        SET transaction_date = '$cnote_date',
            amount = '$total_amount1'
        WHERE voucher_id = '$cnote_id' AND transaction_type = 'Credit Note'";

    if (!$conn->query($sql_update_ledger)) {
        throw new Exception("Error updating ledger: " . $conn->error);
    }

    // Generate Updated PDF
    

    // Commit Transaction
    $conn->commit();
    echo "<script>alert('Credit Note updated successfully!'); window.location.href = 'manage-creditnote.php';</script>";
//    echo "<script>
//     Swal.fire({
//         icon: 'success',
//         title: 'Success',
//         text: 'Credit Note created successfully!',
//         confirmButtonColor: '#3085d6',
//         confirmButtonText: 'OK'
//     }).then(() => {
//        header("Location: manage-creditnote.php?success=1");
//   });
// </script>";
} catch (Exception $e) {
    $conn->rollback();
    die("Error: " . $e->getMessage());
//       echo "<script>
//     Swal.fire({
//         icon: 'error',
//         title: 'Error',
//         text: '". $e->getMessage() ."',
//         confirmButtonColor: '#d33',
//         confirmButtonText: 'OK'
//     });
// </script>";

}
?>

</body>
</html>
