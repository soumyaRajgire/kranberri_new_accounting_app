
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once "phpqrcode/qrlib.php"; // Include QR code library

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

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

include("config.php");


if (isset($_POST['submit'])) {
    include("fpdf/fpdf.php");

 
    // Start transaction
    $conn->begin_transaction();

    try {
        // Escaping and sanitizing inputs
       echo $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice']);
        $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
        $cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id']);
        $total_amount = floatval(mysqli_real_escape_string($conn, $_POST['total_amount']));
        $note = mysqli_real_escape_string($conn, $_POST['note']);
       echo $invoice_code = mysqli_real_escape_string($conn, $_POST['invoice_code']);
        echo $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
        $purchaseDate = mysqli_real_escape_string($conn, $_POST['invoice_date']);
        $terms = mysqli_real_escape_string($conn, $_POST['terms_condition']);
        $created_by = $_SESSION['name'];

       echo  $final_cess_amount = floatval($_POST['final_cess_amount'] ?? 0);
        $final_gst_amount = floatval($_POST['final_gst_amount'] ?? 0);
        $final_taxable_amt = floatval($_POST['final_taxable_amt'] ?? 0);
        $final_tcs_amount  = floatval($_POST['final_tcs_amount']) ?? 0;
        // Generate new invoice ID
        $result1 = mysqli_query($conn, "SELECT id FROM invoice ORDER BY id DESC LIMIT 1");
        $id = ($row1 = mysqli_fetch_array($result1)) ? $row1['id'] + 1 : 1;

       
 $GLOBALS['invoice_code'] = $invoice_code;
    
    $GLOBALS['conn'] = $conn;

    
     $GLOBALS['invoice_code']; 
     $GLOBALS['branch_id'];     



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

function convertAmountToWords($amount) {
    $rupees = floor($amount);
    $paise = round(($amount - $rupees) * 100);

    $rupee_words = numberToWords($rupees) . ' rupees';

    if ($paise > 0) {
        $paise_words = numberToWords($paise) . ' paise';
        return $rupee_words . ' and ' . $paise_words;
    }

    return $rupee_words;
}

function numberToWords($number) {
    $number = intval(round($number));  // Ensure it's an integer

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
        return $tens[intval($number / 10)] . (($number % 10 != 0) ? ' ' . $words[$number % 10] : '');
    } elseif ($number < 1000) {
        return $words[intval($number / 100)] . ' hundred' . (($number % 100 != 0) ? ' and ' . numberToWords($number % 100) : '');
    } elseif ($number < 1000000) {
        return numberToWords(intval($number / 1000)) . ' thousand' . (($number % 1000 != 0) ? ' and ' . numberToWords($number % 1000) : '');
    } elseif ($number < 1000000000) {
        return numberToWords(intval($number / 1000000)) . ' million' . (($number % 1000000 != 0) ? ' and ' . numberToWords($number % 1000000) : '');
    } elseif ($number < 1000000000000) {
        return numberToWords(intval($number / 1000000000)) . ' billion' . (($number % 1000000000 != 0) ? ' and ' . numberToWords($number % 1000000000) : '');
    } elseif ($number < 1000000000000000) {
        return numberToWords(intval($number / 1000000000000)) . ' trillion' . (($number % 1000000000000 != 0) ? ' and ' . numberToWords($number % 1000000000000) : '');
    } elseif ($number < 1000000000000000000) {
        return numberToWords(intval($number / 1000000000000000)) . ' quadrillion' . (($number % 1000000000000 != 0) ? ' and ' . numberToWords($number % 1000000000000000) : '');
    } else {
        return 'Number is out of range for this example.';
    }
}

  $transportMode = $_POST['transportMode'] ?? 'None';
        //$sql = ""; // Default transportation query
        $vehicleNo ="";
        switch ($transportMode) {
            case 'Road':
             $vehicleNo =$_POST['roadVehicleNumber'];
                $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, vehicle_number, driver_name, license_number, insurance_details, permit_number, driver_contact, distance) 
                        VALUES ('$id', 'Road', '" . ($_POST['roadFreightCharges'] ?? '') . "', '" . ($_POST['roadVehicleNumber'] ?? '') . "', '" . ($_POST['driverName'] ?? '') . "', '" . ($_POST['licenseNumber'] ?? '') . "', '" . ($_POST['roadInsurance'] ?? '') . "', '" . ($_POST['roadPermit'] ?? '') . "', '" . ($_POST['roadContact'] ?? '') . "', '" . ($_POST['roadDistance'] ?? '') . "')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save transportation details: " . $conn->error);
        }
                break;
            case 'Rail':
             $vehicleNo=$_POST['trainNumber'];
                $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, train_number, departure_station, arrival_station, booking_reference, coach_number, seat_number, departure_time) 
                        VALUES ('$id', 'Rail', '" . ($_POST['railFreightCharges'] ?? '') . "', '" . ($_POST['trainNumber'] ?? '') . "', '" . ($_POST['railwayStation'] ?? '') . "', '" . ($_POST['arrivalStation'] ?? '') . "', '" . ($_POST['railwayBooking'] ?? '') . "', '" . ($_POST['railwayCoach'] ?? '') . "', '" . ($_POST['railwaySeat'] ?? '') . "', '" . ($_POST['railDepartureTime'] ?? '') . "')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save transportation details: " . $conn->error);
        }
                break;
            case 'Air':
             $vehicleNo = $_POST['flightNumber'];
                $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, flight_number, departure_airport, arrival_airport, airway_bill, cargo_type, airline_name, estimated_arrival) 
                        VALUES ('$id', 'Air', '" . ($_POST['airFreightCharges'] ?? '') . "', '" . ($_POST['flightNumber'] ?? '') . "', '" . ($_POST['departureAirport'] ?? '') . "', '" . ($_POST['arrivalAirport'] ?? '') . "', '" . ($_POST['airwayBill'] ?? '') . "', '" . ($_POST['airCargoType'] ?? '') . "', '" . ($_POST['airlineName'] ?? '') . "', '" . ($_POST['airETA'] ?? '') . "')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save transportation details: " . $conn->error);
        }
                break;
            case 'Ship':
             $vehicleNo = $_POST['shipVoyageNumber'];
                $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, vessel_name, voyage_number, container_number, bill_of_lading, port_of_loading, port_of_discharge, estimated_arrival) 
                        VALUES ('$id', 'Ship', '" . ($_POST['shipFreightCharges'] ?? '') . "', '" . ($_POST['shipVesselName'] ?? '') . "', '" . ($_POST['shipVoyageNumber'] ?? '') . "', '" . ($_POST['shipContainerNumber'] ?? '') . "', '" . ($_POST['shipBillOfLading'] ?? '') . "', '" . ($_POST['shipPortOfLoading'] ?? '') . "', '" . ($_POST['shipPortOfDischarge'] ?? '') . "', '" . ($_POST['shipEstimatedArrival'] ?? '') . "')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save transportation details: " . $conn->error);
        }
                break;
            default:
                $sql = "INSERT INTO transportation_details (invoice_id, mode) VALUES ('$id', 'None')";
                break;
        }
        
        
        $pdf=new PDF('P','mm','A4');
       
      $file_name = "INVOICE-template1".$invoice_code.'.pdf';
        
        
         
    
        
        $pdf->AddPage();
        $pdf->SetFont("Arial","",10);
        
          $pdf->SetFillColor(232,232,232);
          $pdf->SetFont('Arial', '', 9);
//           $result1 = mysqli_query($GLOBALS['conn'], "SELECT *  FROM add_branch where branch_id='$GLOBALS['branch_id']'");
$result1 = mysqli_query($GLOBALS['conn'], "SELECT * FROM add_branch WHERE branch_id='" . $GLOBALS['branch_id'] . "'");

        
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
        
         
        // $result1 = mysqli_query($conn, "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");
         $result1 = mysqli_query($GLOBALS['conn'], "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id where customer_master.id='$cst_mstr_id'");
         
         
        
         $v = "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id where customer_master.id='$cst_mstr_id'";
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
        
        
        // $pdf->Ln(1);
        
        // Header of the table
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);    
        $pdf->SetFillColor(220, 220, 220); // Light gray background for headings
        
        $header = ['#', 'Product Description', 'RATE', 'QTY', 'DIS(%)', 'Taxable Amt','GST(%)','CGST', 'SGST', 'IGST', 'HSN', 'TOTAL'];
        
        // Adjusted column widths to exactly fit 190 mm
        $widths = [7, 55, 14, 12, 12, 15, 12, 12, 12, 12, 12, 14.2]; // SUM = 190 mm
       
        
        // Display header
        foreach ($header as $key => $col) {
           $pdf->Cell($widths[$key], 8, $col, 1, 0, 'C', true);
        }
        $pdf->Ln(); // Move to the next row after headers
        
        $pdf->SetFillColor(255, 255, 255); // Reset to white background for table content
        
        // Table content
        $pdf->SetFont('Arial', '', 8);
        if (!empty($_POST['products'])) {
            $itemno =0;
            foreach ($_POST["products"] as $key => $product) {
                $itemno++;
                $qtyvalue = floatval($_POST['qtyvalue'][$key]);
                $priceval = floatval($_POST['priceval'][$key]);
                $gstval = floatval($_POST['gstval'][$key]);
                $discountval = floatval($_POST['discountval'][$key]);
                $cgstval = floatval($_POST['cgstval'][$key]);
                $sgstval = floatval($_POST['sgstval'][$key]);
                $igstval = floatval($_POST['igstval'][$key]);
                $cessamountval = $_POST['cessamountval'][$key];
                $cessrateval = $_POST['cessrateval'][$key];
                $proddesc = $_POST['proddesc'][$key];
                $productids = $_POST['productids'][$key];
                $totalval = $_POST['totalval'][$key];
                $in_ex_gst_val = $_POST['in_ex_gst_val'][$key];
                $hsn_code = $_POST['hsn_code_val'][$key];
                $units_val = $_POST['units_val'][$key];
                $batch_no = $_POST['batchid'][$key] ?? '';
                $colorval= $_POST['color_val'][$key];
                $sizeval = $_POST['size_val'][$key];
                $dnoval = $_POST['dno_val'][$key];


        
            if($discountval > 0)
            {
                $discount_amount = ($priceval * $discountval) / 100;
                 $discounted_price = $priceval - $discount_amount;
                 $line_tot = $discounted_price * $qtyvalue;
            }
            else
            {
                 $line_tot = $qtyvalue * $priceval;
            }
        
            $l1 = $line_tot + $cgstval + $sgstval + $igstval;
        
        
                $pdf->Cell($widths[0], 8, $itemno, 1, 0, 'C');
        
                // MultiCell for Product Description
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->MultiCell($widths[1], 8, $product . "\n" . $proddesc, 1, 'L');
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
                $pdf->Cell($widths[10], 8, $hsn_code, 1, 0, 'C');
                $pdf->Cell($widths[11], 8, number_format($l1, 2), 1, 1, 'C'); // Total column
        
               echo  $sql = "INSERT INTO `invoice_items` (`invoice_id`, `itemno`,`productid`, `product`, `prod_desc`, `qty`, `price`, `discount`,`line_total`, `gst`, `cgst`, `sgst`, `igst`, `cess_rate`, `cess_amount`, `total`, `in_ex_gst`,`hsn_code`,`units`, `batch_no`, `colorval`, `sizeval`, `dnoval`)  VALUES ('$id', '$itemno', '$productids','$product', '$proddesc', '$qtyvalue', '$priceval', '$discountval','$l1', '$gstval', '$cgstval', '$sgstval', '$igstval', '$cessrateval', '$cessamountval', '$totalval', '$in_ex_gst_val','$hsn_code','$units_val', '$batch_no','$colorval','$sizeval','$dnoval')";
        
                        if (!$conn->query($sql)) {
                            throw new Exception("Failed to insert invoice items: " . $conn->error);
                        }else{
                            echo "inserted";
                        }
        
        
                $sqlBatchManagement = "SELECT maintain_batch FROM  inventory_master WHERE id = ?";
                $stmtBatchManagement = $conn->prepare($sqlBatchManagement);
                $stmtBatchManagement->bind_param("i", $productids);
                $stmtBatchManagement->execute();
                $stmtBatchManagement->bind_result($batchManagementEnabled);
                $stmtBatchManagement->fetch();
                $stmtBatchManagement->close();   


if ($batchManagementEnabled) {


// Define the query with placeholders
$updateBatchSql = "UPDATE product_batches  SET stock_out = stock_out + ?, balance_stock = (opening_stock + Stock_in) - stock_out  WHERE id = ?";

// Prepare the statement
$stmtBatch = $conn->prepare($updateBatchSql);

// Check if the statement was prepared successfully
if ($stmtBatch === false) {
    echo "Error preparing the query: " . $conn->error;
} else {
    // Bind parameters (assuming qtyvalue, created_by, and batch_no are properly defined)
    $stmtBatch->bind_param("si", $qtyvalue, $batch_no); // 'i' for integers and 's' for string

    // Execute the query
    if ($stmtBatch->execute()) {
        echo "qtyvalue: $qtyvalue, batch_no: $batch_no\n";
echo "Rows affected: " . $stmtBatch->affected_rows;
        echo "Batch stock updated successfully!";
    } else {
        echo "Error updating batch stock: " . $stmtBatch->error;
    }

    // Close the prepared statement
    $stmtBatch->close();
}



         $sql_st_master = "INSERT INTO stock_master (product_id,batch_id,reference_no, quantity, add_and_deduct, remark, date, created_by, created_on) VALUES (?, ?,?,?, ?, ?, NOW(), ?, NOW())";
        
        // Prepare statement
        $stmt_st_master = $conn->prepare($sql_st_master);
        $transaction_type = "Sale"; // This can be changed based on the transaction type
        $remark = "Invoice Sale"; // Can be customized
        print_r($sql_st_master);
        $stmt_st_master->bind_param("ississs", $productids,$batch_no,$invoice_code, $qtyvalue, $transaction_type, $remark, $created_by);
        
        // Execute query
        if ($stmt_st_master->execute()) {
            echo "Stock transaction recorded successfully!";
        } else {
            echo "Error updating stock transaction: " . $conn->error;
        }
        
        $stmt_st_master->close();
}
else{
// Update inventory master table
$sql1 = "UPDATE inventory_master SET stock_out = stock_out + ?,  balance_stock = (opening_stock + Stock_in) - stock_out, 
             last_updated_by = ?, 
             last_updated_at = NOW() 
         WHERE id = ?";

$batch_id = 0;
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ssi", $qtyvalue, $created_by, $productids); // Assuming $qtyvalue and $productids are correctly assigned
$stmt1->execute();
$stmt1->close();
     $sql_st_master = "INSERT INTO stock_master (product_id,batch_id,reference_no, quantity, add_and_deduct, remark, date, created_by, created_on) 
                VALUES (?, ?,?,?, ?, ?, NOW(), ?, NOW())";
        
        // Prepare statement
        $stmt_st_master = $conn->prepare($sql_st_master);
        $transaction_type = "Sale"; // This can be changed based on the transaction type
        $remark = "Invoice Sale"; // Can be customized
        
        $stmt_st_master->bind_param("ississs", $productids,$batch_id,$invoice_code, $qtyvalue, $transaction_type, $remark, $created_by);
        
        // Execute query
        if ($stmt_st_master->execute()) {
            echo "Stock transaction recorded successfully!";
        } else {
            echo "Error updating stock transaction: " . $conn->error;
        }
        
        $stmt_st_master->close();

        }
    }
}
        
    
        
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
        
                $pdf->Image($qrCodePath, 20, 170, 25, 25); // Adjust the position and size as needed
        $pdf->SetFont("Arial", "B", 10);
        $pdf->Cell(189, 10, "Scan this QR Code to Pay", 'LR', 1, 'L');
        }
        
        
       $totWords = convertAmountToWords($total_amount);
      // echo ucwords($totWords);
        $pdf->Cell(150, 6, "Nontaxable Amount", 'L', 0, 'R');
        $pdf->Cell(39, 6, "",'R', 1, 'R');
        
        $pdf->Cell(150, 6, "Taxable Amount", 'L', 0, 'R');
        $pdf->Cell(39, 6, "$final_taxable_amt",'R', 1, 'R');
        
        $pdf->Cell(150, 6, "GST Total", 'L', 0, 'R');
        $pdf->Cell(39, 6, "$final_gst_amount",'R', 1, 'R');
        
        // $pdf->Cell(150, 6, "CESS Total", 'L', 0, 'R');
        // $pdf->Cell(39, 6, "$final_cess_amount",'R', 1, 'R');
        
        if($final_tcs_amount > 0){
        $pdf->Cell(150, 6, "TCS Value", 'L', 0, 'R');
        $pdf->Cell(39, 6, "$final_tcs_amount",'R', 1, 'R');
        }
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
                $pdf->Cell(39, 6, number_format($chargePrice, 2), 'R', 1, 'R'); // Charge Price
            }
        }
        
        // $pdf->Cell(150, 6, "Adjusment", 'L', 0, 'R');
        // $pdf->Cell(40, 6, "0",'R', 1, 'R');
        // $pdf->Ln(2);
        $pdf->Cell(120,6,"Amount in words : $totWords",'BL',0,'L');
        $pdf->Cell(29, 6, "Invoice Total", 'B', 0, 'R');
        $pdf->Cell(40, 6, "INR $total_amount",'BR', 1, 'R');
        
        
        // $pdf->Cell(21,10,$tot_amt,1,1,'C');
        
        
        $pdf->SetFont("Arial","B",8);
        // $pdf->MultiCell(80, 10, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 1, 'L');
        
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        
        $pdf->Cell(27, 6, "Bank Name", 'L', 0, 'L');
        $pdf->Cell(66, 6, "AXIS BANK",'R', 0, 'L');
        $pdf->MultiCell(96,6,"Note : $note",'TRL',1,'L');
         
         $pdf->SetXY($x , $y + 6);
        
        $pdf->Cell(27, 6, "Account Name", 'L', 0, 'L');
        $pdf->Cell(66, 6, "KRIKA MKB CORPORATION PRIVATE LIMITED",'R', 0, 'L');
        $pdf->Cell(96,6,"",'R',1,'L');
        
        $pdf->Cell(27, 6, "Account No", 'L', 0, 'L');
        $pdf->Cell(66, 6, "921020008198970",'R', 0, 'L');
         $pdf->Cell(96,6,"",'R',1,'L');
        
        $pdf->Cell(27, 6, "IFSC Code", 'BL', 0, 'L');
        $pdf->Cell(66, 6, "UTIB0000560",'BR', 0, 'L');
        $pdf->Cell(96,6,"",'BR',1,'L');


        $pdf->Ln(0); 
        $pdf->SetFont("Arial", "", 8);
        $remarks = "\n1. Payment within 30 days." . "    " . "2. In case of unpaid bill, interest @ 24% will be charged extra from the date of bill. " . "   " . "3. Goods supplied to order will not be accepted back." . "   " .  "4. Payment of this bill will be accepted only by a/c payee cheque/NEFT/RTGS." . "  " .  "5. Once the goods are delivered we are not responsible for any claim.\n" . "* SUBJECT TO BENGALURU JURISDICTION *";


        $pdf->SetX($x);
        $pdf->MultiCell(189, 5, "Remark : $remarks", 1, 'L'); 

        $pdf->SetFont("Arial", "", 8);

        // Set positions and sizes
        $startY = $pdf->GetY();
        $currentX = $pdf->GetX();

        $leftWidth = 100;
        $rightWidth = 89;
        $cellHeight = 35; 

        // === LEFT CELL: Terms and Condition ===
        $pdf->SetXY($currentX, $startY);
        $pdf->MultiCell($leftWidth, 6, "Terms and Condition:\n$terms", 0, 'L');

        // Draw manual border for left cell with fixed height
        $pdf->Rect($currentX, $startY, $leftWidth, $cellHeight);

        // === RIGHT CELL: Signature Block ===
        $pdf->SetXY($currentX + $leftWidth, $startY);
        $pdf->Cell($rightWidth, $cellHeight, "", 1); // Border only

        $pdf->SetXY($currentX + $leftWidth + 2, $startY + 2);
        $pdf->SetFont("Arial", "B", 9);
        $pdf->Cell($rightWidth - 4, 5, "For NAVAKAR EXPORTS", 0, 2, 'L');

        $pdf->Image("img/sample-sign.png", $currentX + $leftWidth + 10, $startY + 8, 35);

        $pdf->SetXY($currentX + $leftWidth + 2, $startY + $cellHeight - 7);
        $pdf->Cell($rightWidth - 4, 5, "Authorized Signatory", 0, 0, 'L');

        // === FINAL ROW: Thank You ===
        $pdf->SetY($startY + $cellHeight);
        $pdf->SetFont("Arial", "B", 10);
        $pdf->Cell($leftWidth + $rightWidth, 10, "Thank you for your Business!", 1, 1, 'C');
               
        ob_end_clean();
        
        // a random hash will be necessary to send mixed content
        $separator = md5(time());
        
        // carriage return type (we use a PHP end of line constant)
        $eol = PHP_EOL;
        
        
            $filename1 = "invoice/".$file_name;
            // $pdfdoc1 = $pdf->Output('S');
         $pdfdoc1 = $pdf->Output('S');
        
    
        // file_put_contents($filename, $pdfdoc);
        
        if (empty($pdfdoc1)) {
            echo "<script>alert('Error generating Invoice TEMPLATE 1.');</script>";
        } else {
            file_put_contents($filename1, $pdfdoc1);
            //  echo "<script>alert('Invoice TEMPLATE - 1 created successfully ".$filename."'); </script>";
        }



 // Insert main invoice
     echo  $sql = "INSERT INTO `invoice` (`id`, `invoice_code`, `customer_id`, `customer_name`, `customer_email`, `invoice_date`, `due_date`, `total_amount`, `total_gst`, `total_cess`,`tcs_amount`, `grand_total`,`due_amount`, `terms_condition`, `note`, `status`, `branch_id`, `created_by`,`invoice_file_template1` ) 
                VALUES ('$id', '$invoice_code', '$cst_mstr_id', '$customer_name', '$customer_email', '$purchaseDate', '$dueDate', '$final_taxable_amt', '$final_gst_amount', '$final_cess_amount','$final_tcs_amount', '$total_amount', '$total_amount','$terms', '$note', 'pending', '$branch_id', '$created_by','$filename1')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to insert invoice: " . $conn->error);
        }

        // Insert additional charges
        if (isset($_POST['additionalCharges']['charge_type']) && isset($_POST['additionalCharges']['charge_price'])) {
            foreach ($_POST['additionalCharges']['charge_type'] as $key => $chargeType) {
                $chargePrice = isset($_POST['additionalCharges']['charge_price'][$key]) 
                    ? floatval($_POST['additionalCharges']['charge_price'][$key]) 
                    : 0; // Default to 0 if not set

                $chargeType = mysqli_real_escape_string($conn, $chargeType);

                // Debugging
                error_log("Charge Type: $chargeType, Charge Price: $chargePrice");

                $sql = "INSERT INTO invoice_additional_charges (invoice_id, charge_type, charge_price, created_on)
                        VALUES ('$id', '$chargeType', '$chargePrice', NOW())";

                if (!$conn->query($sql)) {
                    throw new Exception("Failed to save additional charges: " . $conn->error);
                }
            }
        }


        // Insert transportation details
     
        //Insert other details
   $sql = "INSERT INTO invoice_other_details (invoice_id, po_number, po_date, challan_number, due_date, ewaybill_number, sales_person, reverse_charge, tcs_value, tcs_type, created_on)  VALUES ('$id', '" . ($_POST['other_poNumber'] ?? '') . "', '" . ($_POST['other_poDate'] ?? '') . "', '" . ($_POST['challanNumber'] ?? '') . "', '" . ($_POST['other_dueDate'] ?? '') . "', '" . ($_POST['ewayBill'] ?? '') . "', '" . ($_POST['salesPerson'] ?? '') . "', '" . ($_POST['reverseCharge'] ?? '0') . "', '" . ($_POST['tcsValue'] ?? '0') . "', '" . ($_POST['tcsTax'] ?? '') . "', NOW())";


 $sql_ledger = "INSERT INTO `ledger` (`voucher_id`, `transaction_date`, `transaction_type`, `account_id`, `account_name`, `amount`, `debit_credit`, `receipt_or_voucher_no`,`branch_id`)value($id,'$purchaseDate','Sales','$cst_mstr_id', '$customer_name','$total_amount','D','$invoice_code','branch_id')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save other details: " . $conn->error);
        }
          if (!$conn->query($sql_ledger)) {
            throw new Exception("Failed to save other details: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();

       // "<script>alert('Invoice created successfully');</script>";
       echo "<script>alert('Invoice created successfully'); window.location = 'view-invoices.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        // $conn->rollback();
        echo "<script>alert('Failed to create invoice: " . $e->getMessage() . "');</script>";
    }
}
?>




