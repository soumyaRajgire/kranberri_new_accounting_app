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
        $this->Cell(0, 6, $row1['address_line1'] . ', ' . $row1['address_line2'] . ', ' . $row1['city'] . ', ' . $row1['state'], 0, 1, 'C'); // Address line
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

    function InvoiceBody($invoice_code, $transportMode, $purchaseDate, $vehicleNo, $customer_name)
{
    include "config.php";

    $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");

    if ($row1 = mysqli_fetch_array($result1)) {
        $this->SetFont('Times', '', 10);

        // Customer and Invoice Details
        $this->SetX(10);
        $this->Cell(120, 5, "Purchaser's Name: $customer_name", 0, 0, 'L');
        $this->Cell(0, 5, "Invoice Date: $purchaseDate", 0, 1, 'R');

        $this->SetX(10);
        $this->Cell(120, 5, 'Address: ' . $row1['b_city'], 0, 0, 'L');
        $this->Cell(0, 5, "Invoice No: $invoice_code", 0, 1, 'R');

        $this->SetX(10);
        $this->Cell(120, 5, 'Contact: ' . $row1['mobile'], 0, 1, 'L');

        $this->SetX(10);
        $this->Cell(120, 5, 'GST NO: ' . $row1['gstin'], 0, 1, 'L');

        $this->SetX(10);
        $this->Cell(120, 5, 'PAN NO: ' . $row1['pan'], 0, 1, 'L');

        // Table Headers
        $this->Ln(5);
        $this->SetFont('Times', 'B', 8);
        $headers = ['S.N.', 'PRODUCT NAME', 'HSN CODE', 'UOM', 'QTY', 'RATE', 'TAXABLE VALUE', 'GST%', 'TOTAL'];
        $widths = [15, 50, 20, 15, 15, 20, 25, 15, 15];
        foreach ($headers as $i => $header) {
            $this->Cell($widths[$i], 6, $header, 1, 0, 'C');
        }
        $this->Ln();

        // Table Rows
        $this->SetFont('Times', '', 8);

        $final_cess_amount = floatval($_POST['final_cess_amount'] ?? 0);
        $final_gst_amount = floatval($_POST['final_gst_amount'] ?? 0);
        $final_taxable_amt = floatval($_POST['final_taxable_amt'] ?? 0);
        $totalTaxableValue = 0;
        $totalGST = 0;
        $grandTotal = 0;
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
            $taxableValue = $qtyvalue * $priceval - $discountval;
            $gstAmount = ($taxableValue * $gstval) / 100;
            $totalWithGST = $taxableValue + $gstAmount;

            $totalTaxableValue += $taxableValue;
            $totalGST += $gstAmount;
            $grandTotal += $totalWithGST;
            $totalSum = round(floatval($final_taxable_amt) + floatval($final_gst_amount));


            $row = [
                strval($key + 1),
                $product,
                $_POST['hsn_code_val'][$key] ?? '',
                $_POST['units_val'][$key] ?? '',
                number_format($qtyvalue, 2),
                number_format($priceval, 2),
                number_format($taxableValue, 2),
                "{$gstval}%",
                number_format($totalWithGST, 1)
            ];

            foreach ($row as $i => $value) {
                $this->Cell($widths[$i], 6, $value, 1, 0, 'C');
            }
            $this->Ln();
        }

        // Totals section
       // Totals and Bank Details Section
// Totals and Bank Details Section
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
$this->Cell($valueWidth, 5, number_format($totalSum, 2), '1', 1, 'R'); // Value with full border

// Total Invoice Value Footer with Borders
$this->SetFont('Times', 'B', 8); // Bold font for Total Invoice Value
$this->Cell($bankDetailsWidth, 5, '', '1', 0, 'L'); // Empty cell under bank details with full border
$this->Cell($fieldWidth, 5, 'TOTAL INVOICE VALUE:', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, number_format($totalSum, 2), '1', 1, 'R'); // Value with full border
// Additional Section: Bill Amount in Words and Authorization


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

$this->Cell($detailsWidth, $lineHeight, 'Bill Amount in Words: ' . ucfirst($amountInWords) . ' Only', 'L', 1, 'L');
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
$this->Cell($signatureBoxWidth, 5, 'For SELF PC AMAR COMPUTERS', 0, 1, 'C'); // Title text centered

// Add "Auth. Signatory" at the bottom
$this->SetFont('Times', '', 8); // Smaller font for Auth. Signatory
$this->SetXY($this->GetX() - $signatureBoxWidth, $signatureBoxStartY + $signatureBoxHeight - 10); // Adjust Y position for bottom alignment
$this->Cell($signatureBoxWidth, 5, 'Auth. Signatory', 0, 1, 'C'); // Bottom text centered

    }
}

        }

        $pdf = new PDF();
        $pdf->AddPage();
$pdf->InvoiceBody($invoice_code, $transportMode, $purchaseDate, $vehicleNo, $customer_name);

      
        // Add totals to the invoice
       
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
