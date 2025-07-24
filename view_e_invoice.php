<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';

require 'vendor/autoload.php'; // Include Composer's autoloader
require('fpdf/fpdf.php');


 
class PDF extends FPDF {
    // Header of the PDF
    function Header() {
       require 'config.php';
$inv_id = isset($_GET['inv_id']) ? $_GET['inv_id'] : null;
$GLOBALS['inv_id']=$inv_id;
if ($inv_id) {
    // Assuming you have a valid database connection stored in $conn
    $query = "SELECT * FROM `invoice` WHERE id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameter to the prepared statement
        $stmt->bind_param("i", $inv_id); // "i" denotes an integer

        // Execute the query
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Fetch the invoice_code from the result
            $row = $result->fetch_assoc();
            $invoice_code = $row['invoice_code'];
            $GLOBALS['invoice_code']= $row['invoice_code'];
            // You can now use the $invoice_code as needed
            echo "Invoice Code: " . $invoice_code;
        } else {
            echo "No invoice found with the provided ID.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }
} else {
    echo "No invoice ID provided.";
}

$customer_id = $row['customer_id'];
$query = "SELECT * FROM `customer_master` WHERE id=$customer_id";
$result = mysqli_query($conn, $query); // Assuming $conn is your DB connection
$customer_details = mysqli_fetch_assoc($result); // Fetching the customer details

// Define variables for easy reference
$gstin = $customer_details['gstin'];
$gst_reg_name = $customer_details['gst_reg_name'];
$mobile = isset($customer_details['mobile']) ? $customer_details['mobile'] : 'Not Available';
$email = isset($customer_details['email']) ? $customer_details['email'] : 'Not Available';
$business_name = isset($customer_details['business_name']) ? $customer_details['business_name'] : 'Not Available';

// Add a border around the page
$this->SetLineWidth(0.3); // Set border thickness
$this->Rect(10, 8, 190, 257); // Draw rectangle (x, y, width, height)

// GST and Invoice Type (Top Row)
$this->SetFont('Times', 'B', 8);
$this->Cell(40, 4, "GST NO. $gstin", 0, 0, 'C', false); // GST Label (reduced cell height)
$this->Cell(130, 4, 'TAX INVOICE', 0, 0, 'C', false); // Invoice Type (reduced cell height)
$this->Cell(30, 4, 'KB', 0, 1, 'C', false); // Additional Field (reduced cell height)

// Add a horizontal line after the GST and Invoice type
$this->Line(10, $this->GetY(), 200, $this->GetY()); // (x1, y1, x2, y2)
$this->Ln(4); // Add some space after the line

// Add title and other details
$this->SetFont('Times', 'B', 18);
$this->Cell(0, 8, $gst_reg_name, 0, 1, 'C'); // Business name (dynamic)

$this->SetFont('Times', '', 8);
$this->Cell(0, 6, 'Beet to Mancherial Chowrastha Road, Karimnagar, KARIMNAGAR, Telangana', 0, 1, 'C'); // Address line
$this->Cell(0, 6, 'Mob: ' . $mobile . ', Email: ' . $email, 0, 1, 'C'); // Contact details (dynamic)





$this->Ln(4);  // This adds a vertical space after the line

// Draw the horizontal line
$this->Line(10, $this->GetY(), 200, $this->GetY()); // Adjust coordinates as needed for your page size

// Example of table content after horizontal line
$this->Cell(40, 10, 'IRN No:', 0, 0, 'L');
$this->Cell(60, 10, htmlspecialchars($row['irn_no']), 0, 1, 'L');

$this->Cell(40, 10, 'Acknowledgment No:', 0, 0, 'L');
$this->Cell(60, 10, htmlspecialchars($row['ack_no']), 0, 1, 'L');

$this->Cell(40, 10, 'Acknowledgment Date:', 0, 0, 'L');
$this->Cell(60, 10, htmlspecialchars($row['ack_date']), 0, 1, 'L');

$this->Cell(40, 10, 'E-Way Bill No:', 0, 0, 'L');
$this->Cell(60, 10, htmlspecialchars($row['e_way_bill_no']), 0, 1, 'L');

$this->Cell(40, 10, 'E-Way Bill Date:', 0, 0, 'L');
$this->Cell(60, 10, htmlspecialchars($row['e_way_bill_date']), 0, 1, 'L');

$this->Cell(40, 10, 'E-Way Bill Valid Till:', 0, 0, 'L');
$this->Cell(60, 10, htmlspecialchars($row['e_way_bill_valid_till']), 0, 1, 'L');

//$this->Image('invoice/' . $invoice_code . '.jpg', $x = 10, $y = 10, $w = 15, $h = 15);



// Set image position to the right side
$right_x = 120; // X-coordinate for the right side of the page (adjust this based on page width and desired margin)
$y_position = $this->GetY(); // Get the current Y position after the text content

// Place the image at the right side with size 15x15
$this->Image('invoice/' . $invoice_code . '.jpg', $right_x, $y_position, $w = 15, $h = 15);


// Add a horizontal line after the email
$this->Line(10, $this->GetY(), 200, $this->GetY()); // (x1, y1, x2, y2)

$this->Ln(1); // Add some space after the line

    }
    
    
    

    // Footer of the PDF
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Times', 'I', 8);
        $this->Cell(0, 10, 'This is a Computer Generated Invoice', 0, 0, 'C');
    }

    function InvoiceBody() {
        // Set font for the content
       $this->SetFont('Times', '', 10);

// Left side fields
$this->SetX(10); // Left margin
$this->Cell(120, 5, "Purchaser's Name: MRM CONSTRUCTIONS", 0, 0, 'L'); // Left field
$this->Cell(0, 5, 'Invoice Date: 17-10-2024', 0, 1, 'R'); // Right field

$this->SetX(10); // Reset position
$this->Cell(120, 5, 'Address: KARIMNAGAR OLD RAINBOW SCHOOL KARIMNAGAR', 0, 0, 'L'); // Left field
$this->Cell(0, 5, 'Invoice No: 651', 0, 1, 'R'); // Right field

$this->SetX(10); // Reset position
$this->Cell(120, 5, 'Shipping Address: MRM CONSTRUCTIONS, MOBILE NO: 7893121024, KARIMNAGAR', 0, 1, 'L'); // Add Shipping Address

$this->SetX(10); // Reset position
$this->Cell(120, 5, 'Contact:', 0, 1, 'L'); // Single-line left field

$this->SetX(10); // Reset position
$this->Cell(120, 5, 'GST NO:', 0, 1, 'L'); // Single-line left field

$this->SetX(10); // Reset position
$this->Cell(120, 5, 'PAN NO:', 0, 1, 'L'); // Single-line left field


    
    
    

        $this->SetFont('Times', '', 8);

        // Define headers and column widths
        $headers = ['S.N.', 'PRODUCT NAME', 'HSN CODE', 'UOM', 'QTY', 'RATE', 'DIS%', 'GST%', 'TOTAL'];
        $widths = [15, 50, 20, 15, 15, 20, 15, 15, 25]; // Ensure these widths add up to match your table width
        
        // Draw the header row with proper borders
        $headerHeight = 6;
        foreach ($headers as $i => $header) {
            $this->Cell($widths[$i], $headerHeight, $header, 1, 0, 'C'); // Full borders for headers
        }
        $this->Ln(); // Move to the next line after the header row
        
        // Adjust cursor position to ensure data rows align perfectly with the table
        $startY = $this->GetY(); // Get the current Y position after the header
        $dataRowHeight = 10; // Define consistent data row height (same as header for uniformity)
        $this->SetY($startY); // Set the starting Y position for the first data row
        
        // Data rows
        $data = [
            ['1', 'STEEL', '123456', 'Box', '0.5', '550.00', 'N.A.', '5', '3300']
        ];
        
        foreach ($data as $row) {
            foreach ($row as $i => $value) {
                $this->Cell($widths[$i], $dataRowHeight, $value, 1, 0, 'C'); // Full borders for data rows
            }
            $this->Ln(); // Move to the next line after each data row
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
$this->Cell($valueWidth, 5, '3,300.00', '1', 1, 'R'); // Value with full border

$this->Cell($bankDetailsWidth, 5, 'Account Name: Khushbu Mobile', 'L', 0, 'L'); // Left border only
$this->Cell($fieldWidth, 5, 'Add: SGST@%', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, '0.00', '1', 1, 'R'); // Value with full border

$this->Cell($bankDetailsWidth, 5, 'Account No.: 917020023031453', 'L', 0, 'L'); // Left border only
$this->Cell($fieldWidth, 5, 'Add: CGST@%', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, '0.00', '1', 1, 'R'); // Value with full border

$this->Cell($bankDetailsWidth, 5, 'IFSC Code: UTIB0001591', 'L', 0, 'L'); // Left border only
$this->Cell($fieldWidth, 5, 'Total Tax Amount:', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, '165.00', '1', 1, 'R'); // Value with full border

$this->Cell($bankDetailsWidth, 5, 'Branch: Kodinar', 'L', 0, 'L'); // Left border only
$this->Cell($fieldWidth, 5, 'Total After Tax:', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, '3,465.00', '1', 1, 'R'); // Value with full border

// Total Invoice Value Footer with Borders
$this->SetFont('Times', 'B', 8); // Bold font for Total Invoice Value
$this->Cell($bankDetailsWidth, 5, '', '1', 0, 'L'); // Empty cell under bank details with full border
$this->Cell($fieldWidth, 5, 'TOTAL INVOICE VALUE:', '1', 0, 'L'); // Field label with full border
$this->Cell($valueWidth, 5, '3,465.00', '1', 1, 'R'); // Value with full border
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

$this->Cell($detailsWidth, $lineHeight, 'Bill Amount In Words: Three Thousand Four Hundred Sixty Five Only', 'L', 1, 'L');
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

// Create and output the PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->InvoiceBody();
//$pdf->Output('I', 'Invoice.pdf');

// $invoice_folder = "invoice/";
// $t=$GLOBALS['invoice_code'];

// //$file_name = $invoice_folder . $invoice_code . "_e_invoice.pdf";
// $file_name = $invoice_folder . $t . "_e_invoice.pdf";

// $pdf->Output('F', $file_name);  // Save the PDF to a file instead of outputting to the browser

// echo "Invoice saved to: " . $file_name;

$invoice_folder = "invoice/";
$t = $GLOBALS['invoice_code'];


// Construct file name
$file_name = $invoice_folder . $t . "_e_invoice.pdf";

// Save the PDF to a file on the server
$pdf->Output('F', $file_name);

// Trigger the download
if (file_exists($file_name)) {
    // Set headers to force the browser to download the file
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
    header('Content-Length: ' . filesize($file_name));
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    // Clean the output buffer to prevent any other output
    ob_clean();
    flush();

    // Read the file and output it
    readfile($file_name);

    // Optionally delete the file after download (if desired)
    // unlink($file_name);
     echo "<script type='text/javascript'>
            alert('E-Invoice PDF file successfully downloaded!');
            window.location.href = 'view-invoice-action.php?inv_id=" . $GLOBALS['inv_id'] . "';
          </script>";

   // exit; // Stop the script after the download
} else {
    echo "File not found!";
}



?>
