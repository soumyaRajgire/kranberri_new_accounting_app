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
    $GLOBALS['branch_id'] = $branch_id;
}

include("config.php");


// if (isset($_POST['submit'])) {
    include("fpdf/fpdf.php");

 
    try {
         
     if (isset($_GET['inv_id'])) {
    // If the 'inv_id' is in the URL (GET method)
    $inv_id = mysqli_real_escape_string($conn, $_GET['inv_id']);
    $invoiceId= mysqli_real_escape_string($conn, $_GET['inv_id']);
} elseif (isset($_POST['inv_id'])) {
    // If the 'inv_id' is in the form (POST method)
    $inv_id = mysqli_real_escape_string($conn, $_POST['inv_id']);
    $invoiceId= mysqli_real_escape_string($conn, $_POST['inv_id']);
} else {
    // If 'inv_id' is not set in either GET or POST, you can handle the error or redirect
    echo "<script>alert('Error: inv_id is missing.');</script>";
    exit;  // Stop the script from running further
}

// Now you can use $inv_id in your queries or logic

 



  $query = "SELECT q.*, c.*, a.*, qi.*, im.in_ex_gst, im.net_price, im.gst_rate, im.price as imprice
                      FROM invoice q
                      JOIN customer_master c ON q.customer_id = c.id
                      JOIN address_master a ON c.id = a.customer_master_id
                      JOIN invoice_items qi ON q.id = qi.invoice_id
                      JOIN inventory_master im ON qi.productid = im.id
                      WHERE q.id = '$invoiceId'";
            
$result = mysqli_query($conn, $query);

// Check if the query was successful and fetch the data
if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the data
    $row = mysqli_fetch_assoc($result);
    echo '<pre>';
print_r($row);
echo '</pre>';


    
 $invoice_id = $row['id']; // Assuming this is fetched from the database

    
// Check if 'customer_name_choice' exists in $row, otherwise fall back to 'customer_name'
$customer_name = isset($row['customer_name_choice']) ? mysqli_real_escape_string($conn, $row['customer_name_choice']) : 'customer_name';

// Check if 'cst_mstr_id' exists in $row, otherwise fall back to 'customer_id'
$cst_mstr_id = isset($row['cst_mstr_id']) ? mysqli_real_escape_string($conn, $row['cst_mstr_id']) : 'customer_id';

// Check if 'dueDate' exists in $row, otherwise fall back to 'due_date'
$dueDate = isset($row['dueDate']) ? mysqli_real_escape_string($conn, $row['dueDate']) : 'due_date';


$customer_email = mysqli_real_escape_string($conn, $row['customer_email']);
$total_amount = floatval($row['total_amount']);
$note = mysqli_real_escape_string($conn, $row['note']);
$invoice_code = mysqli_real_escape_string($conn, $row['invoice_code']);

$purchaseDate = mysqli_real_escape_string($conn, $row['invoice_date']);
$terms = mysqli_real_escape_string($conn, $row['terms_condition']);
$created_by = $_SESSION['name']; // This is still based on session data

// Optional final values
$final_cess_amount = floatval($row['final_cess_amount'] ?? 0);
$final_gst_amount = floatval($row['final_gst_amount'] ?? 0);
$final_taxable_amt = floatval($row['final_taxable_amt'] ?? 0);

    

    // Now you can use the $invoice_id and other data for further operations, like inserting or updating data in the database
} else {
    echo "Invoice not found!";
}

        // Generate new invoice ID
        $result1 = mysqli_query($conn, "SELECT id FROM invoice ORDER BY id DESC LIMIT 1");
        $id = ($row1 = mysqli_fetch_array($result1)) ? $row1['id'] + 1 : 1;

       
 $GLOBALS['invoice_code'] = $invoice_code;
    
    $GLOBALS['conn'] = $conn;

    
    echo $GLOBALS['invoice_code']; 
    echo $GLOBALS['branch_id'];     



 // Generate PDF using FPDF
    class PDF extends FPDF {
        
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
    
    $rows = []; 

$_POST["products"]=$row;
 echo '<pre>';
print_r($_POST["products"]);
echo '</pre>';
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
      
        
        
        $pdf=new PDF('P','mm','A4');
       
      $file_name = "INVOICE-template1".$invoice_code.'.pdf';
        
        
         
    
        
        $pdf->AddPage();
        $pdf->SetFont("Arial","",10);
        
          $pdf->SetFillColor(232,232,232);
          $pdf->SetFont('Arial', '', 9);

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
        
         
        
         $result1 = mysqli_query($GLOBALS['conn'], "SELECT *  FROM customer_master JOIN address_master ON customer_master.id = address_master.customer_master_id");
         
         
        
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
        
        
        $totWords = numberToWords($total_amount);
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
         $pdf->MultiCell(80, 10, "For KRIKA MKB CORPORATION PRIVATE LIMITED \n\n Authorised Signatory", 1, 'L');
        
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

// Terms and Conditions (using a single MultiCell to keep the cursor consistent)
$pdf->MultiCell(100, 6, "Terms2 and Condition2:\n$terms", 0, 'L');


// Calculate the height of the "Terms and Conditions" block
$termsHeight = $pdf->GetY() - $startY;


$pdf->SetXY($currentX + 100, $startY); // Start the "For" section at the same Y as Terms

// "For" text
$pdf->MultiCell(89, 6, "For", 0, 'L');


// Image (adjust Y position based on the height of the "For" text)
$imageHeight = 15; // Height of your image
$imagePath = 'pdf/1741349953_13522.jpg'; 
$pdf->Image($imagePath, 120, $pdf->GetY(), 20, $imageHeight);

$pdf->MultiCell(89, 6, "Authorised Signatory", 0, 'L');



$endYSecond = $pdf->GetY();


        
        
        
        $endYFirst = $pdf->GetY();
        
      
        

        
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
         
        
        // file_put_contents($filename, $pdfdoc);
        
    
    
    if (empty($pdfdoc)) {
    echo "<script>alert('Error generating Invoice TEMPLATE 1.');</script>";
} else {
    file_put_contents($filename, $pdfdoc);
   echo "<script>alert('Invoice TEMPLATE - 1 created successfully ".$filename."'); </script>";
}

 

        
        
        
        



 


// Generate PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->AddDetails($invoice_code, $transportMode, $purchaseDate, $vehicleNo);
$pdf->AddTableHeader();
$pdf->AddTableRows();
// $pdf->numberToWords($totalAmount);
$pdf->AddTotals();



        $file_name2 = "INVOICE-template2".$invoice_code.'.pdf';
      
    $filename2 = "invoice/".$file_name2;


    $pdf2doc2 = $pdf->Output('S');
    
 

    
  


     
    
    
$write_result = file_put_contents($filename2, $pdf2doc2);
if ($write_result === false) {
    echo "<script>alert('Error writing file for Invoice TEMPLATE 2.');</script>";
} else {
    echo "<script>alert('Invoice TEMPLATE 2 created successfully at ".$filename2."');</script>";
}


 // $pdf->Output('D', 'invoice_template2' . date('Y-m-d') . '.pdf');



 class CustomPDF extends FPDF {

            

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


// Initialize the total taxable value to 0 before the loop
$totalTaxableValue = 0;

 $final_cess_amount = floatval($_POST['final_cess_amount'] ?? 0);
        $final_gst_amount = floatval($_POST['final_gst_amount'] ?? 0);
        $final_taxable_amt = floatval($_POST['final_taxable_amt'] ?? 0);


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
//$pdf = new PDF();
$pdf = new CustomPDF();
$pdf->AddPage();
$pdf->InvoiceBody($invoice_code, $transportMode, $purchaseDate, $vehicleNo, $customer_name);



        $file_name2 = "INVOICE-template3".$invoice_code.'.pdf';
      
    $filename2 = "invoice/".$file_name2;


    $pdf2doc2 = $pdf->Output('S');
    
 

    
  


     
    
    
$write_result = file_put_contents($filename2, $pdf2doc2);
if ($write_result === false) {
    echo "<script>alert('Error writing file for Invoice TEMPLATE 3.');</script>";
} else {
    echo "<script>alert('Invoice TEMPLATE 3 created successfully at ".$filename2."');</script>";
}

 // $pdf->Output('D', 'invoice_template2' . date('Y-m-d') . '.pdf');



  //class PDF extends FPDF {
      class CustomPDF1 extends PDF {

           
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

        $pdf = new CustomPDF1();
        $pdf->AddPage();
$pdf->InvoiceBody($invoice_code, $transportMode, $purchaseDate, $vehicleNo, $customer_name);



        $file_name2 = "INVOICE-template4".$invoice_code.'.pdf';
      
    $filename2 = "invoice/".$file_name2;


    $pdf2doc2 = $pdf->Output('S');
    
 

    
  


     
    
    
$write_result = file_put_contents($filename2, $pdf2doc2);
if ($write_result === false) {
    echo "<script>alert('Error writing file for Invoice TEMPLATE 4.');</script>";
} else {
    echo "<script>alert('Invoice TEMPLATE 4 created successfully at ".$filename2."');</script>";
}


 
        // Insert transportation details
     
      
       

      
    } catch (Exception $e) {
     
        echo "<script>alert('Failed to save sign on  invoice: " . $e->getMessage() . "'); 
        
        </script>";
        //window.history.back();
    }
//}
?>




