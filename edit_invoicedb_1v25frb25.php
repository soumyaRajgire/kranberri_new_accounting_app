 <?php
 use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once "phpqrcode/qrlib.php"; // Include QR code library
session_start();
include('config.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);
error_log(print_r($_POST['products'], true), 3, "debug_products.log");
print_r($_POST['products']);

if (isset($_POST['update'])) {
    // Retrieve form data
    $inv_id = $_POST['inv_id'];
    $purchaseNo = $_POST['purchaseNo'];
    $purchaseDate = $_POST['purchaseDate'];
    $dueDate = $_POST['dueDate'];
    $terms_condition = $_POST['terms_condition'];
    $note = $_POST['note'];
    $pack_price = isset($_POST['pack_price']) ? $_POST['pack_price'] : 0;
    $invoice_code = mysqli_real_escape_string($conn, $_POST['purchaseNo']);
    $created_by = $_SESSION['name'];
     $final_cess_amount = floatval($_POST['final_cess_amount'] ?? 0);
        $final_gst_amount = floatval($_POST['final_gst_amount'] ?? 0);
        $final_taxable_amt = floatval($_POST['final_taxable_amt'] ?? 0);
 $total_amount = floatval(mysqli_real_escape_string($conn, $_POST['total_amount']));
    $sub_total = 0;
    $total_tax = 0;



    // Calculate the grand total
   

    // Generate the updated PDF
    include("fpdf/fpdf.php");

    // Fetch updated invoice details
    // Generate PDF
    class PDF extends FPDF{
      
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
        //   $func = function ($text, $c_width) {
        //     // Avoid division by zero
        //     if ($c_width <= 0) {
        //         $c_width = 1; // Set a minimum width
        //     }
        
        //     $len = mb_strlen($text, 'UTF-8');
        //     $twidth = $this->GetStringWidth($text);
            
        //     $split = ($twidth != 0) ? max(1, floor($c_width * $len / $twidth)) : 1;
        
        //     $w_text = explode("\n", wordwrap($text, $split, "\n", true));
        //     return $w_text;
        // };
        
        
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

        $pdf=new PDF('P','mm','A4');
        $file_name = md5(rand()) . '.pdf';
        
        $pdf->AddPage();
        $pdf->SetFont("Arial","",10);
        
        // $pdf->SetDrawColor(221,221,221,1);
        // // $pdf->SetFillColor(51, 184, 255);
        // $pdf->SetFillColor(113, 163, 244 );
        // $pdf->Cell(0,10,"Quotation",0,1,'C',true);
        
        
        // $pdf->Ln(3);
        // $pdf->SetFont("Arial","",10);
        // $pdf->SetDrawColor(221,221,221,1);
        // $pdf->SetDrawColor(0,0,0,1);
        $pdf->SetFillColor(232,232,232);
        // $pdf->Cell(0,1,"",0,1,'C',true);
        // $pdf->SetLineWidth(2);
        // $pdf->Ln(3);
        // $pdf->Cell(15,10,"Buyer :",0,0);
        //$w= $pdf->GetStringWidth($customer_name)+6;
        // $pdf->SetX((210-$w)/2);
        // $pdf->Cell(120,10,$customer_name,0,0);
        
        $pdf->SetFont('Arial', '', 9);
           $result1 = mysqli_query($conn, "SELECT *  FROM add_branch where branch_id='$branch_id'");

if ($row1 = mysqli_fetch_array($result1)) {

  $table = array(array("img/logo.png","\n {$row1['branch_name']} \n {$row1['address_line1']}, {$row1['address_line2']}, {$row1['city']} - {$row1['pincode']}, \n {$row1['state']} \nEmail: {$row1['office_email']}, Phone: {$row1['phone_number']} \n GSTIN: {$row1['GST']} \n"));

}
         $lineheight = 4;
         $fontsize = 10;
         $aligns = array('C','C');
         $widths = array(35,154);
         $border=1;
         $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);
        
        $pdf->SetFont('Arial', '', 9);
          $table = array(array("GST INVOICE"));
         $lineheight = 8;
         $fontsize = 10;
         $aligns = array('C');
         $widths = array(189);
         $border=1;
         $backgroundColors = array(array(255, 200, 200)); // RGB color for the background (light red in this example)
        
         $pdf->plot_table($widths, $lineheight, $table,$border,$aligns,$backgroundColors);
        
        
        // Fetch data from the database
        $result1 = mysqli_query($conn, "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");
        
        if ($row1 = mysqli_fetch_array($result1)) {
        
        $pdf->SetFont("Arial","B",8);
        
        $table = array(array(
                "\n Billing Address \n\n {$row1['b_address_line1']} \n {$row1['b_address_line2']} \n {$row1['b_city']} - {$row1['b_Pincode']} \n {$row1['b_state']} \n",
                "\n Shipping Address \n\n {$row1['s_address_line1']} \n {$row1['s_address_line2']} \n {$row1['s_city']} - {$row1['s_Pincode']} \n {$row1['s_state']} \n"
            )
        );
        
        $lineheight = 5;
        $fontsize = 10;
        $widths = array(94.5,94.5);
        $aligns = array('L','L');
        $border=1;
        $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);
        
        // }
        $pdf->SetFont("Arial","",9);
        $pdf->SetTextColor(0,0,0,0);
        
      
  $table = array(array("Invoice Number","$invoice_code","Transportation Mode","{$transportMode}"));
$lineheight = 7;
$fontsize = 9;
$widths = array(47.25,47.25,47.25,47.25);
$aligns = array('L','L','L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$table = array(array("Invoice Date","$purchaseDate","Vehicle No.","$vehicleNo"));
$lineheight = 7;
$fontsize = 9;
$widths = array(47.25,47.25,47.25,47.25);
$aligns = array('L','L','L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$table = array(array("Due Date","$dueDate","Place of Supply","{$row1['s_state']}"));
$lineheight = 7;
$fontsize = 10;
$widths = array(47.25,47.25,47.25,47.25);
$aligns = array('L','L','L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);
}
$pdf->SetFont("Arial","B",8);
$pdf->SetTextColor(0,0,0,0);
$pdf->SetDrawColor(0,0,0,0);
$pdf->SetLineWidth(0);
$pdf->SetFillColor(232,232,232);
        // $pdf->Cell(10,10,"Sl.No.",1,0);
        // $pdf->CellFitScale(70,10,"products",1,0,'',1);
     $pdf->Ln(1);

// Header of the table
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(0, 0, 0);    
$pdf->SetFillColor(220, 220, 220); // Light gray background for headings
$header = ['#', 'Product Description', 'RATE', 'QTY', 'DIS(%)', 'Taxable Amt','GST(%)','CGST', 'SGST', 'IGST', 'CESS', 'TOTAL'];

// Adjusted column widths to exactly fit 190 mm
$widths = [7, 55, 20, 10, 10, 15, 10, 12, 12, 12, 12, 15]; // SUM = 190 mm

// Display header
foreach ($header as $key => $col) {
   $pdf->Cell($widths[$key], 8, $col, 1, 0, 'C', true);
}
$pdf->Ln(); // Move to the next row after headers

$pdf->SetFillColor(255, 255, 255); // Reset to white background for table content

        
        $cgsttotal =0;
        $sgsttotal = 0;
        $pricevaltot =0;
        $tot_total =0;
        $tot_qty=0;
        $nontax_tot_amt=0;
        $gsttot =0;
        $gsttotamt=0;
        $pdf->SetFillColor(255,255,255);

     // Delete old invoice_items entries
        $delete_items_sql = "DELETE FROM invoice_items WHERE invoice_id=?";
        $stmt_delete = $conn->prepare($delete_items_sql);
        $stmt_delete->bind_param("i", $inv_id);
        $stmt_delete->execute();

        // Loop through products
foreach ($_POST["products"] as $key => $product) {

    $itemnum = isset($product['itemnum']) ? $product['itemnum'] : '';
    $products_name = isset($product['pname']) ? mysqli_real_escape_string($conn, $product['pname']) : '';
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
   echo $totalval = isset($product['ptotal']) ? floatval($product['ptotal']) : 0;
    $productid = isset($product['pproductid']) ? mysqli_real_escape_string($conn, $product['pproductid']) : '';
    $in_ex_gst = isset($product['pin_ex_gst']) ? mysqli_real_escape_string($conn, $product['pin_ex_gst']) : '';
    $hsn_code = isset($product['hsn_code']) ? mysqli_real_escape_string($conn, $product['hsn_code']) : '';
    $units_val = isset($product['units_val']) ? mysqli_real_escape_string($conn, $product['units_val']) : '';

    // Ensure mandatory fields are not empty
    // if (empty($products) || empty($productid)) {
    //     continue;
    // }

    // Calculate line total (if not already calculated)
    // if ($discountval > 0) {
    //     $discount_amount = ($priceval * $discountval) / 100;
    //     $discounted_price = $priceval - $discount_amount;
    //     $line_tot = $discounted_price * $qtyvalue;
    // } else {
    //     $line_tot = $qtyvalue * $priceval;
    // }

if($discountval > 0)
{
    $discount_amount = ($priceval * $discountval) / 100;

// Step 2: Subtract the discount from the original price
$discounted_price = $priceval - $discount_amount;

// Step 3: Calculate the line total with quantity
$line_tot = $discounted_price * $qtyvalue;
}
else
{
     $line_tot = $qtyvalue * $priceval;
}

  $pdf->Cell($widths[0], 8, $key + 1, 1, 0, 'C');

        // MultiCell for Product Description
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell($widths[1], 8, $products_name . "\n" . $proddesc, 1, 'L');
        $pdf->SetXY($x + $widths[1], $y);

        // Other Columns
        $pdf->Cell($widths[2], 8, number_format($priceval, 2), 1, 0, 'C');
        $pdf->Cell($widths[3], 8, $qtyvalue, 1, 0, 'C');
        $pdf->Cell($widths[4], 8, $discountval, 1, 0, 'C');
        $pdf->Cell($widths[5], 8, number_format($line_tot, 2), 1, 0, 'C');
        $pdf->Cell($widths[6], 8, $gstval , 1, 0, 'C');
         
        $pdf->Cell($widths[7], 8, $cgstval, 1, 0, 'C');
        $pdf->Cell($widths[8], 8, $sgstval, 1, 0, 'C');
        $pdf->Cell($widths[9], 8, $igstval, 1, 0, 'C');
        $pdf->Cell($widths[10], 8, $cessamountval. " (" . $cessrateval . "%)", 1, 0, 'C');
        $pdf->Cell($widths[11], 8, number_format($line_tot, 2), 1, 1, 'C'); // Total column

    // Insert query
   $sql = "INSERT INTO `invoice_items` (`invoice_id`, `itemno`,`productid`, `product`, `prod_desc`, `qty`, `price`, `discount`,`line_total`, `gst`, `cgst`, `sgst`, `igst`, `cess_rate`, `cess_amount`, `total`, `in_ex_gst`,`hsn_code`,`units`) 
                        VALUES ('$inv_id', '$key', '$productid','$products_name', '$proddesc', '$qtyvalue', '$priceval', '$discountval','$line_tot', '$gstval', '$cgstval', '$sgstval', '$igstval', '$cessrateval', '$cessamountval', '$totalval', '$in_ex_gst_val','$hsn_code','$units_val')";

                if (!$conn->query($sql)) {
                    throw new Exception("Failed to insert invoice items: " . $conn->error);
                }

    // // Execute the statement
    // if (!$stmt->execute()) {
    //     die("Insert failed: " . $stmt->error);
    // }
     // $pdf->Cell($widths[0], 8, $key + 1, 1, 0, 'C');

     //    // MultiCell for Product Description
     //    $x = $pdf->GetX();
     //    $y = $pdf->GetY();
     //    $pdf->MultiCell($widths[1], 8, $product . "\n" . $proddesc, 1, 'L');
     //    $pdf->SetXY($x + $widths[1], $y);

     //    // Other Columns
        
     //    $pdf->Cell($widths[3], 8, number_format($priceval, 2), 1, 0, 'C');
     //    $pdf->Cell($widths[4], 8, $qtyvalue, 1, 0, 'C');
     //    $pdf->Cell($widths[5], 8, $discountval, 1, 0, 'C');
     //    $pdf->Cell($widths[6], 8, number_format($line_tot, 2), 1, 0, 'C');
     //    $pdf->Cell($widths[2], 8, $gstval, 1, 0, 'C');
     //    $pdf->Cell($widths[7], 8, $cgstval, 1, 0, 'C');
     //    $pdf->Cell($widths[8], 8, $sgstval, 1, 0, 'C');
     //    $pdf->Cell($widths[9], 8, $igstval, 1, 0, 'C');
     //    $pdf->Cell($widths[10], 8, $cessamountval. " (" . $cessrateval . "%)", 1, 0, 'C');
     //    $pdf->Cell($widths[11], 8, number_format($line_tot, 2), 1, 1, 'C'); // Total column


$sql_update_stock = "UPDATE inventory_master 
        SET opening_stock = opening_stock, 
            sold_stock = sold_stock + ?, 
            last_updated_by = ?, 
            last_updated_at = NOW() 
        WHERE id = ?";

    $stmt_update_stock = $conn->prepare($sql_update_stock);
    $stmt_update_stock->bind_param("ssi", $qtyvalue, $created_by, $productid);
    $stmt_update_stock->execute();
    $stmt_update_stock->close();

//           $sql = "UPDATE inventory_master 
//         SET opening_stock = opening_stock, 
//             sold_stock = sold_stock + ?, 
//             last_updated_by = ?, 
//             last_updated_at = NOW() 
//         WHERE id = ?";

// // Prepare the statement
// $stmt = $conn->prepare($sql);

// // Check if preparation was successful
// if (!$stmt) {
//     die("Prepare failed: " . $mysqli->error);
// }

// // Bind the parameters to the statement
// $stmt->bind_param("sssi", $qtyvalue, $qtyvalue, $created_by, $productids);

// // Execute the statement
// if (!$stmt->execute()) {
//     die("Execute failed: " . $stmt->error);
// } else {
//     echo "Record updated successfully.";

//     $stmt->close();
// }

       
          
          }
        
         // $nonTaxableAmount = $totalAmount / (1 + ($taxRate / 100));
        
        // $table = array(
        //     array(
        //         "\n Billing Address \n\n {$row1['b_address_line1']} \n {$row1['b_address_line2']} \n {$row1['b_city']} - {$row1['b_Pincode']} \n {$row1['b_state']} \n",
        //         "\n Shipping Address \n\n {$row1['s_address_line1']} \n {$row1['s_address_line2']} \n {$row1['s_city']} - {$row1['s_Pincode']} \n {$row1['s_state']} \n"
        //     )
        // );
        
        // $lineheight = 5;
        // $fontsize = 10;
        // $widths = array(94.5,94.5);
        // $aligns = array('L','L');
        // $border=1;
        // $pdf->plot_table($widths, $lineheight, $table,$border,$aligns);
        
        // $gsttot=($cgsttotal + $sgsttotal);
  $totWords = numberToWords($tot_amt);

   // $upiId = 'savithagundla@ybl'; // Your UPI ID
   //      $payeeName = 'savitha gundla';
   //      $transactionNote = 'Payment for services';
   //      $currencyCode = 'INR';

   //      // Create UPI URL
   //      $upiUrl = "upi://pay?pa=" . urlencode($upiId) .
   //                "&pn=" . urlencode($payeeName) .
   //                "&am=" . urlencode($total_amount) .
   //                "&cu=" . urlencode($currencyCode) .
   //                "&tn=" . urlencode($transactionNote);

   //      // Save the QR code image to a file
   //      $qrCodePath = "qrcodes/{$invoice_code}.png";
   //      QRcode::png($upiUrl, $qrCodePath);

$branch_id = $_SESSION['branch_id'];
  $sql = "SELECT bank_upi, payee_name FROM add_branch WHERE branch_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $branch_id);
$stmt->execute();
$result = $stmt->get_result();
$branch_data = $result->fetch_assoc();

$upiId = $branch_data['bank_upi'] ?? "";
$payeeName = $branch_data['payee_name'] ?? "";

// Proceed only if UPI ID is available
if (!empty($upiId)) {
    // Payment Details
    $transactionNote = "Payment for services";
    $currencyCode = "INR";
    // $total_amount = "100"; // Replace with actual amount dynamically

    // Generate UPI Payment URL
    $upiUrl = "upi://pay?pa=" . urlencode($upiId) .
              "&pn=" . urlencode($payeeName) .
              "&am=" . urlencode($total_amount) .
              "&cu=" . urlencode($currencyCode) .
              "&tn=" . urlencode($transactionNote);

    // Define path for QR code storage
    $invoice_qr_code = uniqid(); // Unique filename for QR code
   $qrCodePath = "qrcodes/{$invoice_qr_code}.png";
        QRcode::png($upiUrl, $qrCodePath);

        $pdf->Image($qrCodePath, 20, 126, 28, 28); // Adjust the position and size as needed
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(10, 10, "Scan this QR Code to Pay", 0, 1, 'L');
}


     
$pdf->Cell(150, 6, "Nontaxable Amount", 'L', 0, 'R');
$pdf->Cell(40, 6, "",'R', 1, 'R');

$pdf->Cell(150, 6, "Taxable Amount", 'L', 0, 'R');
$pdf->Cell(40, 6, "$final_taxable_amt",'R', 1, 'R');

$pdf->Cell(150, 6, "GST Total", 'L', 0, 'R');
$pdf->Cell(40, 6, "$final_gst_amount",'R', 1, 'R');

$pdf->Cell(150, 6, "CESS Total", 'L', 0, 'R');
$pdf->Cell(40, 6, "$final_cess_amount",'R', 1, 'R');

// Fetch Additional Charges
$additionalCharges = [];
if (isset($_POST['additionalCharges']['charge_type']) && isset($_POST['additionalCharges']['charge_price'])) {
    foreach ($_POST['additionalCharges']['charge_type'] as $key => $chargeType) {
        $chargePrice = floatval($_POST['additionalCharges']['charge_price'][$key]);
        $chargeType = mysqli_real_escape_string($conn, $chargeType);
        if ($chargePrice > 0) {
            $additionalCharges[$chargeType] = $chargePrice;
        }
    }
}

// Display Additional Charges
if (!empty($additionalCharges)) {
    foreach ($additionalCharges as $chargeType => $chargePrice) {
        $pdf->Cell(150, 6, $chargeType, 'L', 0, 'R'); // Charge Name
        $pdf->Cell(40, 6, number_format($chargePrice, 2), 'R', 1, 'R'); // Charge Price
    }
}

// $pdf->Cell(150, 6, "Adjusment", 'L', 0, 'R');
// $pdf->Cell(40, 6, "0",'R', 1, 'R');

$pdf->Cell(120,6,"Amount in words : $totWords",'BL',0,'L');
$pdf->Cell(30, 6, "Invoice Total", 'B', 0, 'R');
$pdf->Cell(40, 6, "INR $total_amount",'BR', 1, 'R');


// $pdf->Cell(21,10,$tot_amt,1,1,'C');


$pdf->SetFont("Arial","B",8);
// $pdf->MultiCell(80, 10, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 1, 'L');

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
        
        // Reset position for second MultiCell
        $pdf->SetXY($currentX + 100, $startY);
        $pdf->MultiCell(89, 6, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 0, 'L');
        $endYSecond = $pdf->GetY();
        
        // Determine the maximum Y position reached
        $maxY = max($endYFirst, $endYSecond);
        
        // Draw rectangles for borders
        $pdf->Rect($currentX, $startY, 100, $maxY - $startY, 'L'); // Left border for first cell
        $pdf->Rect($currentX + 100, $startY, 89, $maxY - $startY, 'R'); // Right border for second cell
        
        // Reset Y position to the maximum Y
        $pdf->SetY($maxY);
        
        
        $pdf->Cell(0,10,"Thank you for your Business!",1,1,'C');
    
    ob_end_clean();
    
    $pdfdoc = $pdf->Output('S');
    $filename = "pdf/" . $file_name;
    file_put_contents($filename, $pdfdoc);
    
 $gtotal = $sub_total + $pack_price + $total_tax;

  
    $update_file_query = "UPDATE invoice SET invoice_file=?,invoice_date=?, due_date=?, total_amount=?, total_gst=?, total_cess=?,grand_total=?, due_amount =?,terms_condition=?, note=?  WHERE id=?";
    $stmt_file = $conn->prepare($update_file_query);
    if (!$stmt_file) {
        die("Error preparing file update statement: " . $conn->error);
    }
    $stmt_file->bind_param("ssssssssssi", $filename, $purchaseDate, $dueDate,$final_taxable_amt, $final_gst_amount, $final_cess_amount, $total_amount, $total_amount, $terms_condition, $note, $inv_id);
    if (!$stmt_file->execute()) {
        die("Error updating invoice file: " . $stmt_file->error);
    }
    $stmt_file->close();
    
     
$stmt = $conn->prepare("UPDATE receipts SET total_amount = ? WHERE invoice_id = ?");

// Bind parameters to the prepared statement
$stmt->bind_param("di", $total_amount, $inv_id); 

// Execute the query
if ($stmt->execute()) {
    
    echo "<script>alert('Receipt updated successfully!');</script>";
} else {
    
    echo "<script>alert('Failed to update receipt!');</script>";
}

// Close the prepared statement
$stmt->close();


    // Step 1: Delete Old Additional Charges
$sql_delete_charges = "DELETE FROM invoice_additional_charges WHERE invoice_id = ?";
$stmt_delete_charges = $conn->prepare($sql_delete_charges);
$stmt_delete_charges->bind_param("i", $inv_id);
$stmt_delete_charges->execute();
$stmt_delete_charges->close();

// Step 2: Insert Updated Additional Charges
if (isset($_POST['additionalCharges']['charge_type']) && isset($_POST['additionalCharges']['charge_price'])) {
    foreach ($_POST['additionalCharges']['charge_type'] as $key => $chargeType) {
        $chargePrice = isset($_POST['additionalCharges']['charge_price'][$key]) 
            ? floatval($_POST['additionalCharges']['charge_price'][$key]) 
            : 0; // Default to 0 if not set

        $chargeType = mysqli_real_escape_string($conn, $chargeType);

        $sql_insert_charge = "INSERT INTO invoice_additional_charges (invoice_id, charge_type, charge_price, created_on)
                              VALUES (?, ?, ?, NOW())";
        $stmt_insert_charge = $conn->prepare($sql_insert_charge);
        $stmt_insert_charge->bind_param("isd", $inv_id, $chargeType, $chargePrice);

        if (!$stmt_insert_charge->execute()) {
            throw new Exception("Failed to save additional charges: " . $stmt_insert_charge->error);
        }
        $stmt_insert_charge->close();
    }
}


    $sql_update_ledger = "UPDATE ledger SET amount=?, transaction_date=? 
WHERE voucher_id=?";
$stmt_update_ledger = $conn->prepare($sql_update_ledger);
$stmt_update_ledger->bind_param("dsi", $total_amount, $purchaseDate, $inv_id);
$stmt_update_ledger->execute();
$stmt_update_ledger->close();



}
    echo '<script>alert("Successfully Updated invoice");';
    echo 'window.location.href = "view-invoice-action.php?inv_id=' . $inv_id . '";</script>';
?>