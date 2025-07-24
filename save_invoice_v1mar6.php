
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once "phpqrcode/qrlib.php"; // Include QR code library

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
}

include("config.php");


if (isset($_POST['submit'])) {
    include("fpdf/fpdf.php");

 
    // Start transaction
    $conn->begin_transaction();

    try {
        // Escaping and sanitizing inputs
        $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice']);
        $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
        $cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id']);
        $total_amount = floatval(mysqli_real_escape_string($conn, $_POST['total_amount']));
        $note = mysqli_real_escape_string($conn, $_POST['note']);
        $invoice_code = mysqli_real_escape_string($conn, $_POST['invoice_code']);
        $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
        $purchaseDate = mysqli_real_escape_string($conn, $_POST['invoice_date']);
        $terms = mysqli_real_escape_string($conn, $_POST['terms_condition']);
        $created_by = $_SESSION['name'];

        $final_cess_amount = floatval($_POST['final_cess_amount'] ?? 0);
        $final_gst_amount = floatval($_POST['final_gst_amount'] ?? 0);
        $final_taxable_amt = floatval($_POST['final_taxable_amt'] ?? 0);

        // Generate new invoice ID
        $result1 = mysqli_query($conn, "SELECT id FROM invoice ORDER BY id DESC LIMIT 1");
        $id = ($row1 = mysqli_fetch_array($result1)) ? $row1['id'] + 1 : 1;



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
            // default:
            //     $sql = "INSERT INTO transportation_details (invoice_id, mode) VALUES ('$id', 'None')";
            //     break;
        }



$pdf=new PDF('P','mm','A4');
// $file_name = md5(rand()) . '.pdf';
$file_name = "INVOICE-".$invoice_code.'.pdf';

$pdf->AddPage();
$pdf->SetFont("Arial","",10);

  $pdf->SetFillColor(232,232,232);
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

 
 $result1 = mysqli_query($conn, "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");

 $v = "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id";
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

// $pdf->CellFitScale(70,10,"proddesc",1,0,'',1);
// $pdf->Cell(7,10,"#",1,0,'C',1);
//  $pdf->Cell(92.3,10,"Product Description",1,0,'C',1);
// $pdf->Cell(14,10,"GST",1,0,'C',1);
// $pdf->Cell(20,10,"RATE",1,0,'C',1);
// $pdf->Cell(12, 10, "QTY", 1, 0, 'C',1);
// // $pdf->Cell(20, 10, "Taxable Amt", 1, 0, 'C',1);
// $pdf->Cell(20, 10, "GST", 1, 0, 'C',1);
// $pdf->Cell(24, 10, "TOTAL", 1, 1, 'C',1); 

//     $pdf->Ln(10);
//     $pdf->Cell(5, 10, "#", 1, 0, 'C');
//     $pdf->Cell(75, 10, "Product Description", 1, 0, 'C');
//     $pdf->Cell(10, 10, "GST(%)", 1, 0, 'C');
//     $pdf->Cell(12, 10, "RATE", 1, 0, 'C');
//     $pdf->Cell(10, 10, "QTY", 1, 0, 'C');
//      $pdf->Cell(10, 10, "DIS(%)", 1, 0, 'C');
//     $pdf->Cell(19, 10, "Taxable Amt", 1, 0, 'C');
//     $pdf->Cell(12, 10, "CGST", 1, 0, 'C');
//     $pdf->Cell(12, 10, "SGST", 1, 0, 'C');
//       $pdf->Cell(12, 10, "IGST", 1, 0, 'C');
//       $pdf->Cell(13, 10, "CESS", 1, 0, 'C');
//     $pdf->Cell(18, 10, "TOTAL", 1, 1, 'C'); 
// $pdf->SetFillColor(255,255,255);

// // $filename="";

//         // Insert invoice items
//         if (!empty($_POST['products'])) {
//             foreach ($_POST["products"] as $key => $product) {
//                 $qtyvalue = floatval(mysqli_real_escape_string($conn, $_POST['qtyvalue'][$key]));
//                 $priceval = floatval(mysqli_real_escape_string($conn, $_POST['priceval'][$key]));
//                 $gstval = floatval(mysqli_real_escape_string($conn, $_POST['gstval'][$key]));
//                 $discountval = floatval(mysqli_real_escape_string($conn, $_POST['discountval'][$key]));
//                 $cessrateval = mysqli_real_escape_string($conn, $_POST['cessrateval'][$key]);
//                 $cessamountval = mysqli_real_escape_string($conn, $_POST['cessamountval'][$key]);
//                 $in_ex_gst_val = mysqli_real_escape_string($conn, $_POST['in_ex_gst_val'][$key]);
//                 $cgstval = mysqli_real_escape_string($conn, $_POST['cgstval'][$key]);
//                 $sgstval = mysqli_real_escape_string($conn, $_POST['sgstval'][$key]);
//                 $igstval = mysqli_real_escape_string($conn, $_POST['igstval'][$key]);
//                 $proddesc = mysqli_real_escape_string($conn, $_POST['proddesc'][$key]);

//                 $line_tot = $qtyvalue * $priceval;

//                   $table = array(array($key + 1 ,$products."\n". $proddesc, $gstval, $priceval, $qtyvalue,$discountval, $line_tot, $cgstval, $sgstval,$igstval, $cessamountval));

// $fontsize = 10;
//         $widths = array(5, 75, 10, 12, 10,10, 19, 12, 12,12,13, 18);
//         $pdf->plot_table($widths, 12, $table, 1, array('C','C','C','C','C','C','C','C','C','C','C','C'));


//                 // $sql = "INSERT INTO `invoice_items` (`invoice_id`, `itemno`, `product`, `prod_desc`, `qty`, `price`, `discount`, `gst`, `cgst`, `sgst`, `igst`, `cess_rate`, `cess_amount`, `total`, `in_ex_gst`) 
//                         // VALUES ('$id', '$key', '$product', '$proddesc', '$qtyvalue', '$priceval', '$discountval', '$gstval', '$cgstval', '$sgstval', '$igstval', '$cessrateval', '$cessamountval', '$line_tot', '$in_ex_gst_val')";

//                 // if (!$conn->query($sql)) {
//                 //     throw new Exception("Failed to insert invoice items: " . $conn->error);
//                 // }
//             }
//         }


$pdf->Ln(1);

// Header of the table
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(0, 0, 0);    
$pdf->SetFillColor(220, 220, 220); // Light gray background for headings

$header = ['#', 'Product Description', 'RATE', 'QTY', 'DIS(%)', 'Taxable Amt','GST(%)','CGST', 'SGST', 'IGST', 'CESS', 'TOTAL'];

// Adjusted column widths to exactly fit 190 mm
$widths = [8, 55, 10, 15, 10, 10, 20, 12, 12, 12, 12, 14]; // SUM = 190 mm

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
        $cgstval = $_POST['cgstval'][$key];
        $sgstval = $_POST['sgstval'][$key];
        $igstval = $_POST['igstval'][$key];
        $cessamountval = $_POST['cessamountval'][$key];
        $cessrateval = $_POST['cessrateval'][$key];
        $proddesc = $_POST['proddesc'][$key];
       $productids = $_POST['productids'][$key];
$totalval = $_POST['totalval'][$key];
$in_ex_gst_val = $_POST['in_ex_gst_val'][$key];
 $hsn_code = $_POST['hsn_code_val'][$key];
  $units_val = $_POST['units_val'][$key];

        // Start new row

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
        $pdf->Cell($widths[10], 8, $cessamountval. " (" . $cessrateval . "%)", 1, 0, 'C');
        $pdf->Cell($widths[11], 8, number_format($l1, 2), 1, 1, 'C'); // Total column

         $sql = "INSERT INTO `invoice_items` (`invoice_id`, `itemno`,`productid`, `product`, `prod_desc`, `qty`, `price`, `discount`,`line_total`, `gst`, `cgst`, `sgst`, `igst`, `cess_rate`, `cess_amount`, `total`, `in_ex_gst`,`hsn_code`,`units`)  VALUES ('$id', '$itemno', '$productids','$product', '$proddesc', '$qtyvalue', '$priceval', '$discountval','$l1', '$gstval', '$cgstval', '$sgstval', '$igstval', '$cessrateval', '$cessamountval', '$totalval', '$in_ex_gst_val','$hsn_code','$units_val')";

                if (!$conn->query($sql)) {
                    throw new Exception("Failed to insert invoice items: " . $conn->error);
                }


// The SQL query with placeholders

                $sql_st_master = "INSERT INTO stock_master (product_id,reference_no, quantity, add_and_deduct, remark, date, created_by, created_on) 
        VALUES (?, ?,?, ?, ?, NOW(), ?, NOW())";

// Prepare statement
$stmt_st_master = $conn->prepare($sql_st_master);
$transaction_type = "Sale"; // This can be changed based on the transaction type
$remark = "Invoice Sale"; // Can be customized

$stmt_st_master->bind_param("isisss", $productids,$invoice_code, $qtyvalue, $transaction_type, $remark, $created_by);

// Execute query
if ($stmt_st_master->execute()) {
    echo "Stock transaction recorded successfully!";
} else {
    echo "Error updating stock transaction: " . $conn->error;
}

$stmt_st_master->close();


$sql1 = "UPDATE inventory_master 
         SET stock_out = stock_out + ?, 
             balance_stock = (opening_stock + stock_in) - (stock_out), 
             last_updated_by = ?, 
             last_updated_at = NOW() 
         WHERE id = ?";


// Prepare the statement
$stmt1 = $conn->prepare($sql1);

// Check if preparation was successful
if (!$stmt1) {
    die("Prepare failed: " . $mysqli->error);
}

// Bind the parameters to the statement
$stmt1->bind_param("ssi", $qtyvalue, $created_by, $productids);

// Execute the statement
if (!$stmt1->execute()) {
    die("Execute failed: " . $stmt->error);
} else {
    echo "Record updated successfully.";
}
$stmt1->close();

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

        $pdf->Image($qrCodePath, 20, 126, 28, 28); // Adjust the position and size as needed
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(10, 10, "Scan this QR Code to Pay", 0, 1, 'L');
}


$totWords = numberToWords($tot_amt);
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
$pdf->MultiCell(89, 6, "For  \n\n Authorised Signatory", 0, 'L');
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

// a random hash will be necessary to send mixed content
$separator = md5(time());

// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;


    $filename = "invoice/".$file_name;
    $pdfdoc = $pdf->Output('S');
 

file_put_contents($filename, $pdfdoc);

 // Insert main invoice
       $sql = "INSERT INTO `invoice` (`id`, `invoice_code`, `customer_id`, `customer_name`, `customer_email`, `invoice_date`, `due_date`, `total_amount`, `total_gst`, `total_cess`, `grand_total`,`due_amount`, `terms_condition`, `note`, `invoice_file`, `status`, `branch_id`, `created_by`) 
                VALUES ('$id', '$invoice_code', '$cst_mstr_id', '$customer_name', '$customer_email', '$purchaseDate', '$dueDate', '$final_taxable_amt', '$final_gst_amount', '$final_cess_amount', '$total_amount', '$total_amount','$terms', '$note', '$filename', 'pending', '$branch_id', '$created_by')";

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
     
        // Insert other details
   $sql = "INSERT INTO invoice_other_details (invoice_id, po_number, po_date, challan_number, due_date, ewaybill_number, sales_person, reverse_charge, tcs_value, tcs_type, created_on) 
                VALUES ('$id', '" . ($_POST['other_poNumber'] ?? '') . "', '" . ($_POST['other_poDate'] ?? '') . "', '" . ($_POST['challanNumber'] ?? '') . "', '" . ($_POST['other_dueDate'] ?? '') . "', '" . ($_POST['ewayBill'] ?? '') . "', '" . ($_POST['salesPerson'] ?? '') . "', '" . ($_POST['reverseCharge'] ?? '0') . "', '" . ($_POST['tcsValue'] ?? '0') . "', '" . ($_POST['tcsTax'] ?? '') . "', NOW())";


$sql_ledger = "INSERT INTO `ledger` (`voucher_id`, `transaction_date`, `transaction_type`, `account_id`, `account_name`, `amount`, `debit_credit`, `receipt_or_voucher_no`,`branch_id`)value($id,'$purchaseDate','Sales','$cst_mstr_id', '$customer_name','$total_amount','D','$invoice_code','branch_id')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save other details: " . $conn->error);
        }
          if (!$conn->query($sql_ledger)) {
            throw new Exception("Failed to save other details: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();

       echo "<script>alert('Invoice created successfully'); window.location = 'view-invoices.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "<script>alert('Failed to create invoice: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>






<?php
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;

// session_start(); 
// if(!isset($_SESSION['name'])) {
  
//     header("Location:login.php");
//     exit();
// }

// // Check if a business is selected
// if(!isset($_SESSION['business_id'])){
//     header("Location:dashboard.php");
//     exit();
// } else {
//  // Set up variables for selected business and branch
//     $_SESSION['url'] = $_SERVER['REQUEST_URI'];
//     $business_id = $_SESSION['business_id'];
//     // Check if a specific branch is selected
//     if (isset($_SESSION['branch_id'])) {
//         $branch_id = $_SESSION['branch_id'];
//         // Branch-specific code or logic here
//     } 
// }

// include("config.php");

// if(isset($_POST['submit'])) {
//     include("fpdf/fpdf.php");

//     $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name_choice']);
//     $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
//     $cst_mstr_id = mysqli_real_escape_string($conn, $_POST['cst_mstr_id']);
//     // $sub_total = floatval(mysqli_real_escape_string($conn, $_POST['sub_total']));
//     // $pack_price = floatval(mysqli_real_escape_string($conn, $_POST['pack_price']));
//     $total_amount = floatval(mysqli_real_escape_string($conn, $_POST['total_amount']));
//     // $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
//     $note = mysqli_real_escape_string($conn, $_POST['note']);
//     $invoice_code = mysqli_real_escape_string($conn, $_POST['invoice_code']);
//     $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
//     $purchaseDate = mysqli_real_escape_string($conn, $_POST['invoice_date']);
//     $terms = mysqli_real_escape_string($conn, $_POST['terms_condition']);
//     $created_by = $_SESSION['name'];
// $final_cess_amount = $_POST['final_cess_amount'];
// $final_gst_amount = $_POST['final_gst_amount'];
// $final_taxable_amt = $_POST['final_taxable_amt'];

//     date_default_timezone_set('Asia/Kolkata');
//     $date1 = date("d-m-Y");
//     $time1 = date("h:i:sa");


// $other_poNumber = $_POST['other_poNumber'] ?? '';
// $other_poDate = $_POST['other_poDate'] ?? '';
// $challanNumber = $_POST['challanNumber'] ?? '';
// $other_dueDate = $_POST['other_dueDate'] ?? '';
// $ewayBill = $_POST['ewayBill'] ?? '';
// $salesPerson = $_POST['salesPerson'] ?? '';
// $tcsTax=$_POST['tcsTax'] ?? '';
// $tcsValue =$_POST['tcsValue'] ?? '';
// $reverseCharge =$_POST['reverseCharge'] ?? 0;

//  ;   // Generate ID for the new quotation
//     $result1 = mysqli_query($conn, "SELECT id FROM invoice ORDER BY id DESC LIMIT 1");
//     $id = ($row1 = mysqli_fetch_array($result1)) ? $row1['id'] + 1 : 1;

//    foreach ($_POST["products"] as $key => $product) {
//     $qtyvalue = floatval(mysqli_real_escape_string($conn, $_POST['qtyvalue'][$key]));
//     $priceval = floatval(mysqli_real_escape_string($conn, $_POST['priceval'][$key]));
//     $gstval = floatval(mysqli_real_escape_string($conn, $_POST['gstval'][$key]));
//     $discountval = floatval(mysqli_real_escape_string($conn, $_POST['discountval'][$key]));
//     $cessrateval = mysqli_real_escape_string($conn, $_POST['cessrateval'][$key]);
//     $cessamountval = mysqli_real_escape_string($conn, $_POST['cessamountval'][$key]);
//     $in_ex_gst_val = mysqli_real_escape_string($conn, $_POST['in_ex_gst_val'][$key]);
//     $cgstval = mysqli_real_escape_string($conn, $_POST['cgstval'][$key]);
//     $sgstval = mysqli_real_escape_string($conn, $_POST['sgstval'][$key]);
//     $igstval = mysqli_real_escape_string($conn, $_POST['igstval'][$key]);
//     $gstamountval = mysqli_real_escape_string($conn, $_POST['gstamountval'][$key]);
//     $productid = mysqli_real_escape_string($conn, $_POST['productids'][$key]);
//     $proddesc = mysqli_real_escape_string($conn, $_POST['proddesc'][$key]);

//     $line_tot = $qtyvalue * $priceval;

//     $sql = "INSERT INTO `invoice_items` (`invoice_id`, `itemno`, `product`, `prod_desc`, `qty`, `price`, `discount`, `gst`, `cgst`, `sgst`, `igst`, `cess_rate`, `cess_amount`, `total`, `in_ex_gst`) 
//             VALUES ('$id', '$key', '$product', '$proddesc', '$qtyvalue', '$priceval', '$discountval', '$gstval', '$cgstval', '$sgstval', '$igstval', '$cessrateval', '$cessamountval', '$line_tot', '$in_ex_gst_val')";

//     if (!$conn->query($sql)) {
//         throw new Exception("Failed to insert invoice items: " . $conn->error);
//     }
// }

   
// if (isset($_POST['additionalCharges']['charge_type']) && isset($_POST['additionalCharges']['charge_price'])) {
//     foreach ($_POST['additionalCharges']['charge_type'] as $key => $chargeType) {
//         $chargePrice = floatval($_POST['additionalCharges']['charge_price'][$key]);
//         $chargeType = mysqli_real_escape_string($conn, $chargeType);

//         $sql = "INSERT INTO invoice_additional_charges (invoice_id, charge_type, charge_price, created_on)
//                 VALUES ('$id', '$chargeType', '$chargePrice', NOW())";

//         if (!$conn->query($sql)) {
//             throw new Exception("Failed to save additional charges: " . $conn->error);
//         }
//     }
// }


// $transportMode = $_POST['transportMode'] ?? 'None';

// switch ($transportMode) {
//     case 'Road':
//         $vehicleNumber = $_POST['roadVehicleNumber'] ?? '';
//         $driverName = $_POST['driverName'] ?? '';
//         $licenseNumber = $_POST['licenseNumber'] ?? '';
//         $roadFreightCharges = $_POST['roadFreightCharges'] ?? '';
//         $insuranceDetails = $_POST['roadInsurance'] ?? '';
//         $permitNumber = $_POST['roadPermit'] ?? '';
//         $driverContact = $_POST['roadContact'] ?? '';
//         $roadDistance = $_POST['roadDistance'] ?? '';

//         // Insert query for Road
//         $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, vehicle_number, driver_name, license_number, insurance_details, permit_number, driver_contact, distance) 
//                 VALUES ('$id', 'Road', '$roadFreightCharges', '$vehicleNumber', '$driverName', '$licenseNumber', '$insuranceDetails', '$permitNumber', '$driverContact', '$roadDistance')";
//         break;

//     case 'Rail':
//         $trainNumber = $_POST['trainNumber'] ?? '';
//         $departureStation = $_POST['railwayStation'] ?? '';
//         $arrivalStation = $_POST['arrivalStation'] ?? '';
//         $railwayBooking = $_POST['railwayBooking'] ?? '';
//         $railFreightCharges = $_POST['railFreightCharges'] ?? '';
//         $railwayCoach = $_POST['railwayCoach'] ?? '';
//         $railwaySeat = $_POST['railwaySeat'] ?? '';
//         $railDepartureTime = $_POST['railDepartureTime'] ?? '';

//         // Insert query for Rail
//         $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, train_number, departure_station, arrival_station, booking_reference, coach_number, seat_number, departure_time) 
//                 VALUES ('$id', 'Rail', '$railFreightCharges', '$trainNumber', '$departureStation', '$arrivalStation', '$railwayBooking', '$railwayCoach', '$railwaySeat', '$railDepartureTime')";
//         break;

//     case 'Air':
//         $flightNumber = $_POST['flightNumber'] ?? '';
//         $departureAirport = $_POST['departureAirport'] ?? '';
//         $arrivalAirport = $_POST['arrivalAirport'] ?? '';
//         $airwayBill = $_POST['airwayBill'] ?? '';
//         $airFreightCharges = $_POST['airFreightCharges'] ?? '';
//         $cargoType = $_POST['airCargoType'] ?? '';
//         $airlineName = $_POST['airlineName'] ?? '';
//         $estimatedArrival = $_POST['airETA'] ?? '';

//         // Insert query for Air
//         $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, flight_number, departure_airport, arrival_airport, airway_bill, cargo_type, airline_name, estimated_arrival) 
//                 VALUES ('$id', 'Air', '$airFreightCharges', '$flightNumber', '$departureAirport', '$arrivalAirport', '$airwayBill', '$cargoType', '$airlineName', '$estimatedArrival')";
//         break;

//     case 'Ship':
    
//         $vesselName = $_POST['shipVesselName'] ?? '';
//         $voyageNumber = $_POST['shipVoyageNumber'] ?? '';
//         $containerNumber = $_POST['shipContainerNumber'] ?? '';
//         $billOfLading = $_POST['shipBillOfLading'] ?? '';
//         $portOfLoading = $_POST['shipPortOfLoading'] ?? '';
//         $portOfDischarge = $_POST['shipPortOfDischarge'] ?? '';
//         $shipFreightCharges = $_POST['shipFreightCharges'] ?? '';
//         $estimatedArrival = $_POST['shipEstimatedArrival'] ?? '';

//         // Insert query for Ship
//         $sql = "INSERT INTO transportation_details (invoice_id, mode, freight_charges, vessel_name, voyage_number, container_number, bill_of_lading, port_of_loading, port_of_discharge, estimated_arrival) 
//                 VALUES ('$id', 'Ship', '$shipFreightCharges', '$vesselName', '$voyageNumber', '$containerNumber', '$billOfLading', '$portOfLoading', '$portOfDischarge', '$estimatedArrival')";
//         break;

// }

// // Execute the query
// if (!$conn->query($sql)) {
//     throw new Exception("Failed to save transportation details: " . $conn->error);
// }
// $filename="";
// $sql = "INSERT INTO `invoice` (`id`, `invoice_code`, `customer_id`, `customer_name`, `customer_email`, `invoice_date`, `due_date`, `total_amount`, `total_gst`, `total_cess`, `grand_total`, `terms_condition`, `note`, `invoice_file`,`status`,`branch_id`,`created_by`) VALUES ('$id', '$invoice_code','$cst_mstr_id','$customer_name', '$customer_email', '$purchaseDate', '$dueDate', '$final_taxable_amt', '$final_gst_amount','$final_cess_amount','$total_amount', '$terms', '$note','$filename','Not Converted','$branch_id', '$created_by')";

//     if ($conn->query($sql) === TRUE) {
//         ?>
        <script>
//             window.location = "view-invoices.php";
//             alert("Successfully Created Quotation");
//         </script>
       <?php
//     } else {
//         ?>
         <script>
//             window.location = "create-invoice.php";
//             alert("Unable to create Quotation, try again");
//         </script>
         <?php
//     }
// }
?>
