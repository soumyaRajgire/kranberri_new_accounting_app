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
        // Generate PDF using FPDF
        class PDF extends FPDF {
            

            // Add method to handle header
            function Header() {
        // Add a border around the page
        $this->SetLineWidth(0.3); // Set border thickness
        $this->Rect(10, 8, 190, 257); // Draw rectangle (x, y, width, height)
        $this->SetDrawColor(100, 149, 237); // Darker blue color in RGB (100, 149, 237)
        $this->SetLineWidth(0.4); // Thicker lines

        include("config.php");
    $bid = $_SESSION['branch_id'];
     $result1 = mysqli_query($conn, "SELECT *  FROM add_branch where branch_id='$bid'");

if ($row1 = mysqli_fetch_array($result1)) {
     // GST and Invoice Type (Top Row)
     $this->SetFont('Times', 'B', 8);
     $this->Cell(40, 4, 'GST NO.'  . $row1['GST'], 0, 0, 'C', false); // GST Label (reduced cell height)
     $this->Cell(130, 4, 'TAX INVOICE', 0, 0, 'C', false); // Invoice Type (reduced cell height)
     $this->Cell(30, 4, 'KB', 0, 1, 'C', false); // Additional Field (reduced cell height)
     
     
      // Add a horizontal line after the email
      $this->Line(10, $this->GetY(), 200, $this->GetY()); // (x1, y1, x2, y2)
    
      $this->Ln(4); // Add some space after the line
        // Add title and other details
        $this->SetFont('Times', 'B', 18);
        $this->Cell(0, 8, $row1['branch_name'], 0, 1, 'C'); // Business name
        $this->SetFont('Times', '', 8);
$this->Cell(0, 6, $row1['address_line1'] . ', ' . $row1['address_line2'] . ', ' . $row1['city'] . ', ' . $row1['state'], 0, 1, 'C');
$this->Cell(0, 6, 'Mob: ' . $row1['phone_number'], 0, 1, 'C'); // Contact details
        $this->Cell(0, 6, 'Email: ' . $row1['email'], 0, 1, 'C'); // Email
    
        // Add a horizontal line after the email
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // (x1, y1, x2, y2)
        
    
        $this->Ln(1); // Add some space after the line
    }
    
    
}   

    // Footer of the PDF
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Times', 'I', 8);
        $this->Cell(0, 10, 'This is a Computer Generated Invoice', 0, 0, 'C');
    }

    
    function InvoiceBody($invoice_code, $transportMode, $purchaseDate, $vehicleNo, $customer_name )
{
    include "config.php";
    // Fetch data from the database
    $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");

    if ($row1 = mysqli_fetch_array($result1)) {
        // Set font for the content
        $this->SetFont('Times', '', 10);
        $this->SetDrawColor(100, 149, 237); // Darker blue color in RGB (100, 149, 237)
        $this->SetLineWidth(0.4); // Thicker lines

        // Invoice Details Section
        $this->SetX(10);
        $this->Cell(95, 5, "Invoice No: $invoice_code", 0, 0, 'L');
        $this->Cell(95, 5, "Transportation Mode: $transportMode", 0, 1, 'L');

        $this->SetX(10);
        $this->Cell(95, 5, "Invoice Date: $purchaseDate", 0, 0, 'L');
        $this->Cell(95, 5, "Vehicle Number: $vehicleNo", 0, 1, 'L');

$this->SetX(10);
$this->Cell(95, 5, "State: " . $row1['s_state'], 0, 0, 'L'); 

$this->Cell(95, 5, "Place of Supply: " . $row1['s_state'], 0, 1, 'L');

// Details of Receiver/Billed to Section
$this->SetX(10);
$this->Cell(95, 5, "Details of Receiver | Billed to:", 'T', 0, 'C'); // Top border only
$this->Cell(95, 5, "Details of Consignee | Shipped to:", 'T', 1, 'C'); // Top border only

// Draw the vertical line between left and right sections
$this->Line(105, $this->GetY() - 26, 105, $this->GetY() + 26); // Vertical line from top to bottom

// Bottom borders for the first row
$this->SetX(10);
$this->Cell(95, 1, "", 'B', 0, 'L'); // Bottom border only
$this->Cell(95, 1, "", 'B', 1, 'L'); // Bottom border only



// Purchaser's Name and other details
$this->SetX(10);
$this->Cell(95, 5, "Purchaser's Name: $customer_name", 0, 0, 'L');
$this->Cell(95, 5, "$customer_name", 0, 1, 'L');

$this->SetX(10);
$this->Cell(95, 5, "Address: " . $row1['b_city'], 0, 0, 'L');
$this->Cell(95, 5, $row1['s_city'], 0, 1, 'L');
$this->SetX(10);
$this->Cell(95, 5, "GST NO:" . $row1['gstin'], 0, 0, 'L');
$this->Cell(95, 5, $row1['gstin'], 0, 1, 'L');

$this->SetX(10);
$this->Cell(95, 5, "STATE:" . $row1['b_state'], 0, 0, 'L');
$this->Cell(95, 5, $row1['s_state'], 0, 1, 'L');

        $this->SetFont('Times', '', 8);

// Define headers and column widths
$headers = ['S.N.', 'PRODUCT NAME', 'HSN CODE', 'UOM', 'QTY', 'RATE', 'DIS%', 'GST%', 'TOTAL'];
$widths = [15, 50, 20, 15, 15, 20, 15, 15, 25]; // Ensure these widths add up to match your table width

// Set font for header
$this->SetFont('Times', 'B', 10);
$this->SetDrawColor(100, 149, 237); // Darker blue for borders
$this->SetLineWidth(0.4); // Thicker line width
$headerHeight = 6;

// Set header row
foreach ($headers as $i => $header) {
    $this->SetFillColor(173, 216, 230); // Light blue for header row
    $this->Cell($widths[$i], $headerHeight, $header, 1, 0, 'C', true); // 1 = Full border, true = fill
}
$this->Ln(); // Move to the next line after header

// Set font for rows
$this->SetFont('Times', '', 10);
$rowHeight = 6;

// Fetch product details dynamically
$rows = []; // Define the rows array outside the loop

// Loop through products to collect rows
// Initialize the total taxable value to 0 before the loop
$totalTaxableValue = 0;

 $final_cess_amount = floatval($_POST['final_cess_amount'] ?? 0);
        $final_gst_amount = floatval($_POST['final_gst_amount'] ?? 0);
        $final_taxable_amt = floatval($_POST['final_taxable_amt'] ?? 0);

// Loop through products to collect rows and calculate taxable value sum
foreach ($_POST["products"] as $key => $product) {
    $qtyvalue = floatval($_POST['qtyvalue'][$key]);
    $priceval = floatval($_POST['priceval'][$key]);
    $gstval = floatval($_POST['gstval'][$key]);
    $discountval = floatval($_POST['discountval'][$key]);
    $cgstval = $_POST['cgstval'][$key] ?? 0; // Default to 0 if not set
    $sgstval = $_POST['sgstval'][$key] ?? 0; // Default to 0 if not set
    $igstval = $_POST['igstval'][$key] ?? 0; // Default to 0 if not set
    $cessamountval = $_POST['cessamountval'][$key] ?? 0;
    $cessrateval = $_POST['cessrateval'][$key] ?? 0;
    $proddesc = $_POST['proddesc'][$key] ?? '';
    $productids = $_POST['productids'][$key] ?? '';
    $name = $_POST['name'][$key] ?? ''; // Default to empty string
    $hsn_code = $_POST['hsn_code_val'][$key] ?? ''; // Default to empty string
    $units = $_POST['units_val'][$key] ?? ''; // Default to empty string
    $totalval = $_POST['totalval'][$key] ?? 0;
    $in_ex_gst_val = $_POST['in_ex_gst_val'][$key] ?? '';
    $product_choice = $_POST['product_choice'][$key] ?? '';

    // Calculate taxable value (subtract discount if applicable)
    $taxableValue = $qtyvalue * $priceval - $discountval;

    // Add taxable value to total
    $totalTaxableValue += $taxableValue;  // Sum all taxable values
    

    // Calculate total with GST
    $gstAmount = ($taxableValue * $gstval) / 100;
    $totalWithGST = $taxableValue + $gstAmount;
    $totalSum = round(floatval($final_taxable_amt) + floatval($final_gst_amount));


    // Add row to the array
    $rows[] = [
        strval($key + 1),                  // Sr. No
        $product,                          // Name of Product
        $hsn_code,                         // HSN CODE
        $units,                            // Unit (pcs, kgs, etc.)
        strval($qtyvalue),                 // Quantity
        number_format($priceval, 2),       // Rate
        number_format($discountval, 2),    // Discount
        "{$gstval}%",                      // GST Rate
        number_format($totalWithGST, 2),   // Total
    ];
}

// Loop through rows and set background colors column-wise
foreach ($rows as $row) {
    foreach ($row as $i => $cell) {
        // Set a different background color for each column
        switch ($i) {
            case 0: $this->SetFillColor(255, 228, 196); break; // Light orange for S.N.
            case 1: $this->SetFillColor(240, 230, 140); break; // Light yellow for PRODUCT NAME
            case 2: $this->SetFillColor(144, 238, 144); break; // Light green for HSN CODE
            case 3: $this->SetFillColor(176, 224, 230); break; // Light blue for UOM
            case 4: $this->SetFillColor(221, 160, 221); break; // Light purple for QTY
            case 5: $this->SetFillColor(255, 182, 193); break; // Light pink for RATE
            case 6: $this->SetFillColor(240, 128, 128); break; // Light coral for DIS%
            case 7: $this->SetFillColor(255, 222, 173); break; // Light goldenrod for GST%
            case 8: $this->SetFillColor(135, 206, 235); break; // Sky blue for TOTAL
        }

        // Draw cell with background color
        $this->Cell($widths[$i], $rowHeight, $cell, 1, 0, 'C', true);
    }
    $this->Ln(); // Move to the next row
}

// Totals section
$this->Ln(5); // Add some space before this section

// Define column widths
$bankDetailsWidth = 110; // Width for the Bank Details section
$fieldWidth = 40; // Width for the Field column on the right side
$valueWidth = 40; // Width for the Value column on the right side

// Bank Details Header (No Horizontal Line for Left Section, Full Border for Right Section)
$this->SetFont('Times', 'B', 8); // Bold font for the header
$this->Cell($bankDetailsWidth, 8, 'Bank Detail', '1', 0, 'L'); // Full border for left section
$this->Cell($fieldWidth, 8, 'Field', '1', 0, 'C'); // Field label header with full border
$this->Cell($valueWidth, 8, 'Value', '1', 1, 'C'); // Value header with full border

// Bank Details and Totals Content with Vertical and Horizontal Lines
$this->SetFont('Times', '', 8); // Regular font for content
$this->Cell($bankDetailsWidth, 5, 'Bank Name: Axis Bank', 'L', 0, 'L'); // Left border only
$this->Cell($fieldWidth, 5, 'Total Taxable Value:', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, number_format($final_taxable_amt, 2), '1', 1, 'R'); // Value with full border


$this->Cell($bankDetailsWidth, 5, 'Account Name: Khushbu Mobile', 'L', 0, 'L'); // Left border only
$this->Cell($fieldWidth, 5, 'Add: SGST@%', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, number_format($sgstval, 2), '1', 1, 'R'); // Value with full border

$this->Cell($bankDetailsWidth, 5, 'Account No.: 917020023031453', 'L', 0, 'L'); // Left border only
$this->Cell($fieldWidth, 5, 'Add: CGST@%', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, number_format($cgstval, 2), '1', 1, 'R'); // Value with full border


$this->Cell($bankDetailsWidth, 5, 'IFSC Code: UTIB0001591', 'L', 0, 'L'); // Left border only
$this->Cell($fieldWidth, 5, 'Total Tax Amount:', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, number_format($final_gst_amount, 2), '1', 1, 'R'); // Value with full border

$this->Cell($bankDetailsWidth, 5, 'Branch: Bangalore', 'L', 0, 'L'); // Left border only
$this->Cell($fieldWidth, 5, 'Total After Tax:', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, number_format($totalSum, 1), '1', 1, 'R'); // Value with full border

// Total Invoice Value Footer with Borders
$this->SetFont('Times', 'B', 8); // Bold font for Total Invoice Value
$this->Cell($bankDetailsWidth, 5, '', '1', 0, 'L'); // Empty cell under bank details with full border
$this->Cell($fieldWidth, 5, 'TOTAL INVOICE VALUE:', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, number_format($totalSum, 1), '1', 1, 'R'); // Value with full border

// Define column widths
$bankDetailsWidth = 110; // Width for the Bank Details section
$fieldWidth = 40; // Width for the Field column on the right side
$valueWidth = 40; // Width for the Value column on the right side

// Bank Details Header (No Horizontal Line for Left Section, Full Border for Right Section)


// Set font for content
// Set font for content
$this->SetFont('Times', '', 8);

// Left Section (Bill Amount in Words and Terms & Conditions)
$detailsWidth = 110; // Width for the left column
$lineHeight = 4; // Height of each row

function numberToWords($number) {
    $words = [
        'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
        'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    ];

    $tens = [
        '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
    ];

    // Handle rounding for decimals, if any
    $number = round($number); // Round the number to avoid precision loss in large float numbers

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


$amountInWords = numberToWords($totalSum);
$this->Cell($valueWidth, 5, 'Amount in Words: ' . ucfirst($amountInWords) . ' Only', 'L', 1, 'L');

// $this->Cell($detailsWidth, $lineHeight, 'Bill Amount In Words: Three Thousand Four Hundred Sixty Five Only', 'L', 1, 'L');
$this->Cell($detailsWidth, $lineHeight, '', 'L', 1, 'L'); // Empty line for spacing
$this->Cell($detailsWidth, $lineHeight, 'Terms & Conditions:', 'L', 1, 'L');
$this->Cell($detailsWidth, $lineHeight, '* Payment Terms 21 days.', 'L', 1, 'L');
$this->Cell($detailsWidth, $lineHeight, '* Interest @24% p.a. will be charged if the invoice is not paid by the due date.', 'L', 1, 'L');
$this->Cell($detailsWidth, $lineHeight, '* Once goods sold will not be taken back.', 'L', 1, 'L');
$this->Cell($detailsWidth, $lineHeight, '* Seller is not responsible for any loss or any damage in transit.', 'L', 1, 'L');
$this->Cell($detailsWidth, $lineHeight, '* Buyer undertakes to submit prescribed sales tax declaration to the seller on demand.', 'L', 1, 'L');
$this->Cell($detailsWidth, $lineHeight, '* All disputes are subject to VADODARA Jurisdiction.', 'LB', 1, 'L'); // Add bottom border on the last line


// Move to the right section position
$signatureBoxWidth = 80; // Width for the signature box
$signatureBoxHeight = 36; // Height for the signature box
$this->SetXY($this->GetPageWidth() - $signatureBoxWidth - 10, $this->GetY() - $lineHeight * 9); // Align to the top of the section

// Right Section (Signature Box)
$this->Cell($signatureBoxWidth, $signatureBoxHeight, '', '1', 0, 'C'); // Create the outer border for the signature box

// Add text inside the Signature Box
$this->SetFont('Times', '', 10); // Font for the title
$signatureBoxStartY = $this->GetY(); // Capture the current Y position for further adjustments

// Center the title text vertically
$this->SetXY($this->GetX() - $signatureBoxWidth, $signatureBoxStartY + 5); // Adjust Y position for vertical centering
$this->Cell($signatureBoxWidth, 5, '', 0, 1, 'C'); // Title text centered

// Add "Auth. Signatory" at the bottom
$this->SetFont('Times', '', 8); // Smaller font for Auth. Signatory
$this->SetXY($this->GetX() - $signatureBoxWidth, $signatureBoxStartY + $signatureBoxHeight - 10); // Adjust Y position for bottom alignment
$this->Cell($signatureBoxWidth, 5, 'Auth. Signatory', 0, 1, 'C'); // Bottom text centered

    }
    }

        }
// Generate PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->InvoiceBody($invoice_code, $transportMode, $purchaseDate, $vehicleNo, $customer_name);
// $pdf->numberToWords();


// $pdf->Output();

        

        // File name and output PDF
        $file_name = "INVOICE-" . $invoice_code . '.pdf';
        $filename = "pdf/" . $file_name;
        $pdfdoc = $pdf->Output('S'); // 'S' returns the document as a string
        file_put_contents($filename, $pdfdoc);

        // Insert the invoice into the database
        $sql = "INSERT INTO `invoice` (`id`, `invoice_code`, `customer_id`, `customer_name`, `customer_email`, `invoice_date`, `due_date`, `total_amount`, `total_gst`, `total_cess`, `grand_total`, `due_amount`, `terms_condition`, `note`, `invoice_file`, `status`, `branch_id`, `created_by`) 
                VALUES ('$id', '$invoice_code', '$cst_mstr_id', '$customer_name', '$customer_email', '$purchaseDate', '$dueDate', '$final_taxable_amt', '$final_gst_amount', '$final_cess_amount', '$total_amount', '$total_amount', '$terms', '$note', '$filename', 'pending', '$branch_id', '$created_by')";

        if (!$conn->query($sql)) {
            throw new Exception("Failed to insert invoice: " . $conn->error);
        }

        // Commit the transaction
        $conn->commit();

        echo "<script>alert('Invoice created successfully'); window.location = 'view-invoices.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "<script>alert('Failed to create invoice: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
