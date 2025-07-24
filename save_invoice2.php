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
 class PDF extends FPDF
{
    // Header of the PDF
   function Header()
{
    // Draw page border
    $this->SetDrawColor(0, 0, 0); // Black border
    $this->Rect(5, 5, $this->GetPageWidth() - 10, $this->GetPageHeight() - 10);

    // Company Name
    $this->SetFont('Arial', 'B', 14);
    $this->SetTextColor(0, 0, 128); // Dark blue text
    include("config.php");
    $bid = $_SESSION['branch_id'];
     $result1 = mysqli_query($conn, "SELECT *  FROM add_branch where branch_id='$bid'");

if ($row1 = mysqli_fetch_array($result1)) {
    $this->Cell(0, 10, $row1['branch_name'], 0, 1, 'C');

    // Company Address and GSTIN
    $this->SetFont('Arial', '', 11);
    $this->SetTextColor(0, 0, 0); // Black text
    $this->Cell(0, 6, $row1['address_line1'], 0, 1, 'C');
    $this->Cell(0, 6, $row1['address_line2'], 0, 1, 'C');
$gtsno = 'GSTIN:'.$row1['GST'];
  $this->Cell(0, 6, $gtsno, 0, 1, 'C');

    $this->Ln(5);
}
    // TAX INVOICE Section
    $this->SetFont('Arial', 'B', 16);
    $this->SetFillColor(200, 220, 255); // Light blue background color
    $this->SetTextColor(0, 0, 0); // Black text color

    // Set position to touch page border
    $this->SetX(5); // Align to left page border
// Define the desired height
$desiredHeight = 7; // Adjust this value as per your requirement

// Create the cell with reduced height
$this->Cell($this->GetPageWidth() - 10, $desiredHeight, 'TAX INVOICE', 1, 1, 'C', true);
}


    // Footer of the PDF
    function Footer()
    {
        $this->SetY(-20); // Reduce footer height
        $this->SetFont('Arial', 'I', 8); // Smaller footer font
        $this->MultiCell(0, 4, "Terms and Conditions:\n1. This is an electronically generated document.\n2. All disputes are subject to Bangalore jurisdiction.", 0, 'L');
    }


    // Add Details Section
function AddDetails($invoice_code, $transportMode, $purchaseDate, $vehicleNo)
{

    include "config.php";
    // Fetch data from the database
   $result1 = mysqli_query($conn, "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");

if ($row1 = mysqli_fetch_array($result1)) {
        $this->SetFont('Arial', '', 10);

        $row1 = [
            ['Invoice No.:', $invoice_code, 'Transportation Mode:', $transportMode],
            ['Invoice Date:', $purchaseDate, 'Vehicle No.:', $vehicleNo],
            ['State:', $row1['s_state'], 'Place of Supply:', $row1['s_state']]
          
        ];

    // Set the background color for label cells
    $this->SetFillColor(200, 220, 255); // Light gray background (adjust RGB values as needed)
    
    
    // Add the details
    foreach ($row1 as $row) {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 6, $row[0], 0, 0, 'L', true); // Label with background color
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 6, $row[1], 0, 0); // Value without background color

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 6, $row[2], 0, 0, 'L', true); // Label with background color
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 6, $row[3], 0, 1); // Value without background color
    }



    // Draw border around Invoice Details
    // Draw border around Invoice Details
$endY = $this->GetY();
$this->SetDrawColor(0, 0, 0);
// Define the margin you want to leave on both sides
$margin = 5;
$startY = 50;  // Example value, set this according to your needs
$endY = 100;   // Example value, set this according to your needs
// Calculate the reduced width
$reducedWidth = $this->GetPageWidth() - ($margin * 2);

// Draw the rectangle with the reduced width, centered
$this->Rect($margin, $startY, $reducedWidth, $endY - $startY);

   

    // Section 2: Receiver and Shipped Details
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(200, 220, 255); // Light gray background


 // Run the query to fetch the data from both tables
    $result1 = mysqli_query($conn, "SELECT * FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");


if ($row1 = mysqli_fetch_array($result1)) {
    // Now you can safely access the values
    $receiverDetails = [
    'Name' => isset($row1['business_name']) ? $row1['business_name'] : '',
    'Address' => isset($row1['b_address_line1']) && isset($row1['b_address_line2']) && isset($row1['b_city']) ? 
            $row1['b_address_line1'] . ', ' . $row1['b_address_line2'] . "\n" . $row1['b_city'] : '',

    'GSTIN' => isset($row1['b_gstin']) ? $row1['b_gstin'] : '',
    'State' => isset($row1['b_state']) ? $row1['b_state'] : '',
    'Contact' => isset($row1['mobile']) ? $row1['mobile'] : ''
];


    $shippedDetails = [
    'Name' => isset($row1['business_name']) ? $row1['business_name'] : '',
    'Address' => isset($row1['s_address_line1']) && isset($row1['s_address_line2']) && isset($row1['s_city']) ? 
            $row1['s_address_line1'] . ', ' . $row1['s_address_line2'] . "\n" . $row1['s_city'] : '',

    'GSTIN' => isset($row1['s_gstin']) ? $row1['s_gstin'] : '',
    'State' => isset($row1['s_state']) ? $row1['s_state'] : '',
    'Contact' => isset($row1['mobile']) ? $row1['mobile'] : ''
];

} else {
    // Handle case where no results are returned
    echo "No data found!";
}


    // Get starting Y position for the Receiver and Shipped Details section
    $startY = $this->GetY();

    // Draw headers for the two sections
    $this->SetFont('Arial', 'B', 10);
    // Draw the headers for the two sections
$this->SetFont('Arial', 'B', 10);
$this->Cell(90, 7, 'Receiver Details', 0, 0, 'C', true);
$this->Cell(90, 7, 'Shipped To Details', 0, 1, 'C', true);
// Move the word "Address" to the right
$this->Cell(95, 5, 'Address', 0, 0, 'R');

// Add a line under the headings
$lineStartX = 5; // Start position of the line (left margin)
$lineEndX = $this->GetPageWidth() - 5; // End position of the line (right margin)
$currentY = $this->GetY(); // Current Y position after the headings
$this->Line($lineStartX, $currentY, $lineEndX, $currentY);

// Add some space after the line if needed
$this->Ln(2);


    $this->SetFont('Arial', '', 9);

    $detailsKeys = array_keys($receiverDetails);
    foreach ($detailsKeys as $key) {
        if ($key == 'Address') {
            // Receiver Address
            $this->Cell(50, 6, $key . ':', 0, 0, 'L');
            $xReceiver = $this->GetX();
            $yReceiver = $this->GetY();

            $this->MultiCell(70, 6, $receiverDetails[$key], 0, 'L');
            $receiverAddressHeight = $this->GetY() - $yReceiver;

            // Align Shipped To Address
            $this->SetXY(120, $yReceiver);
            $this->Cell(50, 6, $key . ':', 0, 0, 'L');
            $this->MultiCell(70, 6, $shippedDetails[$key], 0, 'L');
            $shippedAddressHeight = $this->GetY() - $yReceiver;

            // Adjust Y position for the next field
            $this->SetY($yReceiver + max($receiverAddressHeight, $shippedAddressHeight));
        } else {
            // Other fields
            $this->Cell(50, 6, $key . ':', 0, 0, 'L');
            $this->Cell(70, 6, $receiverDetails[$key], 0, 0, 'L');
            $this->Cell(50, 6, $key . ':', 0, 0, 'L');
            $this->Cell(70, 6, $shippedDetails[$key], 0, 1, 'L');
        }
    }

}

    // Draw border and finish Details Section
    // Draw border around Invoice Details
$endY = $this->GetY();
$this->SetDrawColor(0, 0, 0);
// Define the margin you want to leave on both sides
$margin = 5;

// Calculate the reduced width
$reducedWidth = $this->GetPageWidth() - ($margin * 2);

// Draw the rectangle with the reduced width, centered
$this->Rect($margin, $startY, $reducedWidth, $endY - $startY);

    
    // Add separation line
    // $this->Line(10, $endY + 5, $this->GetPageWidth() - 10, $endY + 5);

}


    // Table Header
    function AddTableHeader()
{

    
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(200, 220, 255); // Light blue background

    // Row 1: Main headers
    $this->Cell(10, 15, 'Sr.', 1, 0, 'C', true); // Sr. No
    $this->Cell(40, 15, 'Name of Product', 1, 0, 'C', true); // Name of Product
    $this->Cell(20, 15, 'HSN/SAC', 1, 0, 'C', true); // HSN/SAC
    $this->Cell(10, 15, 'QTY', 1, 0, 'C', true); // QTY
    $this->Cell(10, 15, 'Unit', 1, 0, 'C', true); // Unit
    $this->Cell(20, 15, 'Rate', 1, 0, 'C', true); // Rate
    $this->Cell(25, 15, 'Taxable Value', 1, 0, 'C', true); // Taxable Value

    // IGST Header (parent with sub-columns)
    $this->Cell(35, 7.5, 'GST%', 1, 0, 'C', true); // Parent column for IGST%
    $this->Cell(20, 15, 'Total', 1, 1, 'C', true); // Total column (spanning full height)

    // Row 2: Sub-columns for IGST
    $this->SetXY($this->GetX() - 75, $this->GetY() - 7.5); // Adjust alignment for IGST sub-columns
    $this->Cell(15, 7.5, 'Rate', 1, 0, 'C', true); // IGST Rate
    $this->Cell(20, 7.5, 'Amount', 1, 0, 'C', true); // IGST Amount

    $this->Ln(); // Move to the next line after header
}



// Table Rows
function AddTableRows()
{
    // Initialize rows array before the loop
    $rows = []; // Define the rows array outside the loop

    // Loop through products to collect rows
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

        // Calculate total with GST
        $gstAmount = ($taxableValue * $gstval) / 100;
        $totalWithGST = $taxableValue + $gstAmount;

        // Add row to the array
        $rows[] = [
            strval($key + 1),                  // Sr. No
            $product,                          // Name of Product
            $hsn_code, 
            strval($qtyvalue),
            $units,                            // Unit (pcs, kgs, etc.)
            number_format($priceval, 2),       // Rate
            number_format($taxableValue, 2),   // Taxable Value
            "{$gstval}%",                      // GST Rate
            number_format($totalWithGST, 2),   // Total
        ];
    }

    // Now loop through rows and add them to the table (only once)
    $this->SetFont('Arial', '', 10);
    $this->SetFillColor(245, 245, 245); // Light gray for alternating row colors

    foreach ($rows as $rowIndex => $row) {
        $fill = ($rowIndex % 2 === 0); // Alternate row color
        $this->SetFillColor($fill ? 245 : 255); // Alternating fill color

        $this->Cell(10, 6, $row[0], 1, 0, 'C', $fill); // Sr. No
        $this->Cell(40, 6, $row[1], 1, 0, 'L', $fill); // Name of Product
        $this->Cell(20, 6, $row[2], 1, 0, 'C', $fill); // HSN/SAC
        $this->Cell(10, 6, $row[3], 1, 0, 'C', $fill); // Quantity
        $this->Cell(10, 6, $row[4], 1, 0, 'C', $fill); // Unit
        $this->Cell(20, 6, $row[5], 1, 0, 'R', $fill); // Rate
        $this->Cell(25, 6, $row[6], 1, 0, 'R', $fill); // Taxable Value
        $this->Cell(15, 6, $row[7], 1, 0, 'C', $fill); // GST Rate
        $this->Cell(20, 6, $row[8], 1, 0, 'R', $fill); // GST Amount
        $this->Cell(20, 6, $row[8], 1, 1, 'R', $fill); // Total
    }
}

    // Totals Section
   function AddTotals()
{

    $final_cess_amount = floatval($_POST['final_cess_amount'] ?? 0);
        $final_gst_amount = floatval($_POST['final_gst_amount'] ?? 0);
        $final_taxable_amt = floatval($_POST['final_taxable_amt'] ?? 0);
    // Initialize totals
    $totalQuantity = 0;
    $totalTaxableValue = 0;
    $totalIGSTRate = 0;
    $totalIGSTAmount = 0;

    // Calculate totals from form data
    foreach ($_POST["products"] as $key => $product) {
        $qtyvalue = floatval($_POST['qtyvalue'][$key]);
        $priceval = floatval($_POST['priceval'][$key]);
        $gstval = floatval($_POST['igstval'][$key]);
        $discountval = floatval($_POST['discountval'][$key]);

        // Calculate taxable value and IGST amount
        $taxableValue = $qtyvalue * $priceval - $discountval;
        $igstAmount = $taxableValue * $gstval / 100;

        // Accumulate totals
        $totalQuantity += $qtyvalue;
        $totalTaxableValue += $taxableValue;
        $totalIGSTRate += $gstval;
        $totalIGSTAmount += $igstAmount;
    }

    // Optional: Calculate average IGST rate
    if (count($_POST['products']) > 0) {
        $totalIGSTRate = $totalIGSTRate / count($_POST['products']);
    }

    // Calculate the sum of total taxable value and total IGST rate
$totalSum = round(floatval($final_taxable_amt) + floatval($final_gst_amount));

    // Display totals in the PDF
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(200, 220, 255); // Light blue background for totals
    
    // Print the 'Total' row with the calculated totals
    $this->Cell(70, 6, 'Total:', 1, 0, 'R', true);  // Label "Total"
    $this->Cell(10, 6, $totalQuantity, 1, 0, 'R', true);  // Total quantity
    $this->Cell(55, 6, number_format($final_taxable_amt, 2), 1, 0, 'R', true);  // Total taxable value
    // $this->Cell(25, 6, number_format($final_gst_amount, 2), 1, 0, 'R', true);  // This seems redundant, but keeping for now
// $totalSumFormatted = number_format($totalSum, 2); // Format the sum for display with 2 decimal places
$this->Cell(55, 6, $totalSum, 1, 1, 'R', true);



    // Adjusted widths for the cells
    // $this->Cell(70, 6, 'Total:', 1, 0, 'R', true); // Total label
    // $this->Cell(10, 6, '9', 1, 0, 'R', true); // Total Quantity
    // $this->Cell(30, 6, '970830.00', 1, 0, 'R', true); // Total Taxable Value
    // $this->Cell(25, 6, '970830.00', 1, 0, 'R', true); // Total IGST Rate
    // $this->Cell(55, 6, '970830.00', 1, 1, 'R', true); // Total IGST Amount
    // // $this->Cell(30, 6, '970830.00', 1, 1, 'R', true); // Total IGST Amount


// Store the starting Y position of this section
$startY = $this->GetY();

// Define dimensions and settings
$leftWidth = 125; // Width for the left section
$rightWidth = 35; // Width for each right-side cell
$rowHeight = 7; // Height of each row on the right side
$lineSpacing = 4; // Spacing between sections
$sectionHeight = 28; // Total height of both sections (to make them equal)

// Assuming $totalSum contains the total amount value
$totalAmount = $totalSum; // Use the dynamic $totalSum value
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

// Convert the amount to words
$amountInWords = numberToWords($totalAmount) . " Rupees Only"; 

// **Left Section: Invoice Amount in Words**
$this->SetFont('Arial', 'B', 10);
$this->MultiCell($leftWidth, $rowHeight, "Total Invoice Amount in Words", 1, 'C'); // Title
$this->SetFont('Arial', '', 10);
$leftContentHeight = $sectionHeight - $rowHeight; // Adjust remaining height
$this->MultiCell($leftWidth, $leftContentHeight, $amountInWords, 1, 'C'); // Display the converted amount in words

// **Right Section: Summary**
$this->SetXY($leftWidth + 10, $this->GetY() - $sectionHeight); // Align to the top of the right section
$this->SetFont('Arial', 'B', 8);

// Row 1: Total Amount Before Tax
$this->Cell($rightWidth, $rowHeight, "Total Amount Before Tax:", 1, 0, 'L');
$this->SetFont('Arial', '', 8);
$this->Cell($rightWidth, $rowHeight, "$final_taxable_amt", 1, 1, 'R');

// Row 2: Add IGST
$this->SetX($leftWidth + 10); // Maintain alignment
$this->SetFont('Arial', 'B', 8);
$this->Cell($rightWidth, $rowHeight, "Add: GST:", 1, 0, 'L');
$this->SetFont('Arial', '', 8);
$this->Cell($rightWidth, $rowHeight, "", 1, 1, 'R');

// Row 3: Tax Amount: GST
$this->SetX($leftWidth + 10);
$this->SetFont('Arial', 'B', 8);
$this->Cell($rightWidth, $rowHeight, "Tax Amount: GST:", 1, 0, 'L');
$this->SetFont('Arial', '', 8);
$this->Cell($rightWidth, $rowHeight, "$final_gst_amount", 1, 1, 'R');

// Row 4: Amount With Tax
$this->SetX($leftWidth + 10);
$this->SetFont('Arial', 'B', 8);
$this->Cell($rightWidth, $rowHeight, "Amount With Tax:", 1, 0, 'L');
$this->SetFont('Arial', '', 8);
$this->Cell($rightWidth, $rowHeight, "$totalSum", 1, 1, 'R');

// Set font for content
$this->SetFont('Times', '', 8);

// Left Section (Bill Amount in Words and Terms & Conditions)
$detailsWidth = 110; // Width for the left column
$lineHeight = 4; // Height of each row

$this->Cell($detailsWidth, $lineHeight, 'Bill Amount In Words: ' . $amountInWords, 'L', 1, 'L');
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

// Generate PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->AddDetails($invoice_code, $transportMode, $purchaseDate, $vehicleNo);
$pdf->AddTableHeader();
$pdf->AddTableRows();
// $pdf->numberToWords($totalAmount);
$pdf->AddTotals();


// $pdf->AddBottomSection();
// $pdf->Output();
      

       

        // File name and output PDF
        $file_name = "INVOICE-" . $invoice_code . '.pdf';
        $filename = "pdf/" . $file_name;
        $pdfdoc = $pdf->Output('S'); // 'S' returns the document as a string
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

        if (!$conn->query($sql)) {
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
