<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
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
        $bill_code = mysqli_real_escape_string($conn, $_POST['bill_code']);
        $dueDate = mysqli_real_escape_string($conn, $_POST['dueDate']);
        $purchaseDate = mysqli_real_escape_string($conn, $_POST['bill_date']);
        $terms = mysqli_real_escape_string($conn, $_POST['terms_condition']);
        $created_by = $_SESSION['name'];

        // $final_cess_amount = floatval($_POST['final_cess_amount'] ?? 0);
        // $final_gst_amount = floatval($_POST['final_gst_amount'] ?? 0);
        // $final_taxable_amt = floatval($_POST['final_taxable_amt'] ?? 0);

        // Generate new invoice ID
        $result1 = mysqli_query($conn, "SELECT id FROM bill_of_supply ORDER BY id DESC LIMIT 1");
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
                $sql = "INSERT INTO billsupply_transport_details (bill_id, mode, freight_charges, vehicle_number, driver_name, license_number, insurance_details, permit_number, driver_contact, distance) 
                        VALUES ('$id', 'Road', '" . ($_POST['roadFreightCharges'] ?? '') . "', '" . ($_POST['roadVehicleNumber'] ?? '') . "', '" . ($_POST['driverName'] ?? '') . "', '" . ($_POST['licenseNumber'] ?? '') . "', '" . ($_POST['roadInsurance'] ?? '') . "', '" . ($_POST['roadPermit'] ?? '') . "', '" . ($_POST['roadContact'] ?? '') . "', '" . ($_POST['roadDistance'] ?? '') . "')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save transportation details: " . $conn->error);
        }
                break;
            case 'Rail':
             $vehicleNo=$_POST['trainNumber'];
                $sql = "INSERT INTO billsupply_transport_details (bill_id, mode, freight_charges, train_number, departure_station, arrival_station, booking_reference, coach_number, seat_number, departure_time) 
                        VALUES ('$id', 'Rail', '" . ($_POST['railFreightCharges'] ?? '') . "', '" . ($_POST['trainNumber'] ?? '') . "', '" . ($_POST['railwayStation'] ?? '') . "', '" . ($_POST['arrivalStation'] ?? '') . "', '" . ($_POST['railwayBooking'] ?? '') . "', '" . ($_POST['railwayCoach'] ?? '') . "', '" . ($_POST['railwaySeat'] ?? '') . "', '" . ($_POST['railDepartureTime'] ?? '') . "')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save transportation details: " . $conn->error);
        }
                break;
            case 'Air':
             $vehicleNo = $_POST['flightNumber'];
                $sql = "INSERT INTO billsupply_transport_details (bill_id, mode, freight_charges, flight_number, departure_airport, arrival_airport, airway_bill, cargo_type, airline_name, estimated_arrival) 
                        VALUES ('$id', 'Air', '" . ($_POST['airFreightCharges'] ?? '') . "', '" . ($_POST['flightNumber'] ?? '') . "', '" . ($_POST['departureAirport'] ?? '') . "', '" . ($_POST['arrivalAirport'] ?? '') . "', '" . ($_POST['airwayBill'] ?? '') . "', '" . ($_POST['airCargoType'] ?? '') . "', '" . ($_POST['airlineName'] ?? '') . "', '" . ($_POST['airETA'] ?? '') . "')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save transportation details: " . $conn->error);
        }
                break;
            case 'Ship':
             $vehicleNo = $_POST['shipVoyageNumber'];
                $sql = "INSERT INTO billsupply_transport_details (bill_id, mode, freight_charges, vessel_name, voyage_number, container_number, bill_of_lading, port_of_loading, port_of_discharge, estimated_arrival) 
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
$file_name = "Bill Of Supply-".$bill_code.'.pdf';

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
  $table = array(array("BILL OF SUPPLY"));
 $lineheight = 8;
 $fontsize = 10;
 $aligns = array('C');
 $widths = array(189);
 $border=1;
 $backgroundColors = array(array(255, 200, 200)); // RGB color for the background (light red in this example)

 $pdf->plot_table($widths, $lineheight, $table,$border,$aligns,$backgroundColors);

 
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
  

  $table = array(array("Bill Of Supply Number","$bill_code","Transportation Mode","{$transportMode}"));
$lineheight = 7;
$fontsize = 9;
$widths = array(47.25,47.25,47.25,47.25);
$aligns = array('L','L','L','L');
$border=1;
$pdf->plot_table($widths, $lineheight, $table,$border,$aligns);

$table = array(array("Bill Of Supply Date","$purchaseDate","Vehicle No.","$vehicleNo"));
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

$pdf->Ln(1);

// Header of the table
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(0, 0, 0);    
$pdf->SetFillColor(220, 220, 220); // Light gray background for headings

$header = ['S.No', 'Product Description', 'Rate', 'Qty', 'Total'];

// Adjusted column widths to exactly fit 190 mm
$widths = [10, 120, 20, 20, 20]; // Adjusted for proper alignment

// Display header
foreach ($header as $key => $col) {
   $pdf->Cell($widths[$key], 8, $col, 1, 0, 'C', true);
}
$pdf->Ln(); // Move to the next row after headers

$pdf->SetFillColor(255, 255, 255); // Reset to white background for table content

// Table content
$pdf->SetFont('Arial', '', 8);
if (!empty($_POST['products'])) {
    foreach ($_POST["products"] as $key => $product) {
        $qtyvalue = floatval($_POST['qtyvalue'][$key]);
        $priceval = floatval($_POST['priceval'][$key]);
        $totalval = $qtyvalue * $priceval; // Direct calculation for total
        
        // Start new row
        $pdf->Cell($widths[0], 8, $key + 1, 1, 0, 'C'); // S.No
        $pdf->Cell($widths[1], 8, $product, 1, 0, 'L'); // Product Description
        $pdf->Cell($widths[2], 8, number_format($priceval, 2), 1, 0, 'C'); // Rate
        $pdf->Cell($widths[3], 8, $qtyvalue, 1, 0, 'C'); // Qty
        $pdf->Cell($widths[4], 8, number_format($totalval, 2), 1, 1, 'C'); // Total

        // Insert into database (if needed)
        $sql = "INSERT INTO `billsupply_items` (`bill_id`, `itemno`, `product`, `qty`, `price`, `total`) 
                VALUES ('$id', '$key', '$product', '$qtyvalue', '$priceval', '$totalval')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to insert bill supply items: " . $conn->error);
        }
    }
}

// Calculate total amount in words
$totWords = numberToWords($tot_amt);

// Footer with total amount
$pdf->Cell(120, 6, "Amount in words: $totWords", 'BL', 0, 'L');
$pdf->Cell(30, 6, "Bill Total", 'B', 0, 'R');
$pdf->Cell(40, 6, "INR " . number_format($tot_amt, 2), 'BR', 1, 'R');



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

// a random hash will be necessary to send mixed content
$separator = md5(time());

// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;


    $filename = "pdf/".$file_name;
    $pdfdoc = $pdf->Output('S');
   
file_put_contents($filename, $pdfdoc);

 // Insert main invoice
       $sql = "INSERT INTO `bill_of_supply` (`id`, `bill_code`, `customer_id`, `customer_name`, `customer_email`, `bill_date`, `due_date`, `total_amount`, `total_gst`, `total_cess`, `grand_total`,`due_amount`, `terms_condition`, `note`, `bill_file`, `status`, `branch_id`, `created_by`) 
                VALUES ('$id', '$bill_code', '$cst_mstr_id', '$customer_name', '$customer_email', '$purchaseDate', '$dueDate', '$final_taxable_amt', '$final_gst_amount', '$final_cess_amount', '$total_amount', '$total_amount','$terms', '$note', '$filename', 'pending', '$branch_id', '$created_by')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to insert Bill: " . $conn->error);
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

        $sql = "INSERT INTO billsupply_additional_charges (bill_id, charge_type, charge_price, created_on)
                VALUES ('$id', '$chargeType', '$chargePrice', NOW())";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save additional charges: " . $conn->error);
        }
    }
}


        // Insert transportation details
     
        // Insert other details
        $sql = "INSERT INTO billsupply_other_details (bill_id, po_number, po_date, challan_number, due_date, ewaybill_number, sales_person, reverse_charge, tcs_value, tcs_type, created_on) 
                VALUES ('$id', '" . ($_POST['other_poNumber'] ?? '') . "', '" . ($_POST['other_poDate'] ?? '') . "', '" . ($_POST['challanNumber'] ?? '') . "', '" . ($_POST['other_dueDate'] ?? '') . "', '" . ($_POST['ewayBill'] ?? '') . "', '" . ($_POST['salesPerson'] ?? '') . "', '" . ($_POST['reverseCharge'] ?? '0') . "', '" . ($_POST['tcsValue'] ?? '0') . "', '" . ($_POST['tcsTax'] ?? '') . "', NOW())";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to save other details: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Bill created successfully'); window.location = 'manage-billsupply.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "<script>alert('Failed to create Bill: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>






<?php

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
