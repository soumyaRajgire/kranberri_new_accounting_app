<?php
require('fpdf/fpdf.php');

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
    $this->Cell(0, 10, 'M/S. SRINIVAS CONSTRUCTION & CO', 0, 1, 'C');

    // Company Address and GSTIN
    $this->SetFont('Arial', '', 11);
    $this->SetTextColor(0, 0, 0); // Black text
    $this->Cell(0, 6, 'Gate No 9, Mormugao Port Authority, Post Box No 154, Vaddem, Near IOC Junction,', 0, 1, 'C');
    $this->Cell(0, 6, 'Vasco Da Gama, South Goa, Vasco Da Gama, Karnataka, 403802', 0, 1, 'C');
    $this->Cell(0, 6, 'GSTIN: 30HKYPS3027Q1Z8', 0, 1, 'C');
    $this->Ln(5);

    // TAX INVOICE Section
    $this->SetFont('Arial', 'B', 16);
    $this->SetFillColor(200, 220, 255); // Light blue background color
    $this->SetTextColor(0, 0, 0); // Black text color

    // Set position to touch page border
    $this->SetX(5); // Align to left page border
// Define the desired height
$desiredHeight = 10; // Adjust this value as per your requirement

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
    function AddDetails()
{
    $this->SetFont('Arial', '', 10);

    // Section 1: Invoice Details
    $this->SetFont('Arial', 'B', 12);
    $this->SetFillColor(255, 255, 255); // White background


    $this->SetFont('Arial', '', 10);

    $details = [
        ['Reverse Charge:', 'No', 'Challan No.:', ''],
        ['Invoice No.:', 'SRIN/SEP9', 'Transportation Mode:', 'Road'],
        ['Invoice Date:', '08-11-2024', 'Vehicle No.:', 'KA52M9876'],
        ['State:', 'Karnataka', 'Date of Supply:', '08-11-2024'],
        ['State Code:', '29', 'Place of Supply:', 'Bangalore']
    ];

    // Get starting Y position for border
    $startY = $this->GetY();

    // Add the details
    foreach ($details as $row) {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 6, $row[0], 0, 0, 'L', true); // Label
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 6, $row[1], 0, 0); // Value

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 6, $row[2], 0, 0, 'L', true); // Label
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 6, $row[3], 0, 1); // Value
    }

    // Draw border around Invoice Details
    // Draw border around Invoice Details
$endY = $this->GetY();
$this->SetDrawColor(0, 0, 0);
// Define the margin you want to leave on both sides
$margin = 5;

// Calculate the reduced width
$reducedWidth = $this->GetPageWidth() - ($margin * 2);

// Draw the rectangle with the reduced width, centered
$this->Rect($margin, $startY, $reducedWidth, $endY - $startY);

   

    // Section 2: Receiver and Shipped Details
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(200, 220, 255); // Light gray background


    $receiverDetails = [
        'Name' => 'ABC Corporation',
        'Address' => '123 Industrial Park, Bangalore, Karnataka, 560001',
        'GSTIN' => '29ABCDE1234F1Z5',
        'State' => 'Karnataka',
        'State Code' => '29',
        'Contact' => '9876543210'
    ];

    $shippedDetails = [
        'Name' => 'XYZ Pvt Ltd',
        'Address' => '456 Tech Park, Hyderabad, Telangana, 500032',
        'GSTIN' => '36XYZPQ4567L1Z8',
        'State' => 'Telangana',
        'State Code' => '36',
        'Contact' => '9876543211'
    ];

    // Get starting Y position for the Receiver and Shipped Details section
    $startY = $this->GetY();

    // Draw headers for the two sections
    $this->SetFont('Arial', 'B', 10);
    // Draw the headers for the two sections
$this->SetFont('Arial', 'B', 10);
$this->Cell(90, 7, 'Receiver Details', 0, 0, 'C', true);
$this->Cell(90, 7, 'Shipped To Details', 0, 1, 'C', true);

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
    $this->Cell(35, 7.5, 'IGST%', 1, 0, 'C', true); // Parent column for IGST%
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
    $this->SetFont('Arial', '', 10);
    $this->SetFillColor(245, 245, 245); // Light gray for alternating row colors

    // Sample data rows
    $rows = [
        ['1', 'Diesel Generator', '85021320', '1', 'NOS', '350000.00', '350000.00', '18%', '413000.00'],
        ['2', 'Grove Cutting Machine', '846693', '4', 'NOS', '26500.00', '106000.00', '18%', '125080.00'],
        ['3', 'Air Blower', '84145920', '2', 'NOS', '30000.00', '60000.00', '18%', '70800.00'],
        
    ];

    // Loop through rows and add them
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
        $this->Cell(15, 6, $row[7], 1, 0, 'C', $fill); // IGST Rate
        $this->Cell(20, 6, $row[8], 1, 0, 'R', $fill); // IGST Amount
        $this->Cell(20, 6, $row[8], 1, 1, 'R', $fill); // Total
    }
    
}



    // Totals Section
    function AddTotals()
{
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(200, 220, 255); // Light blue background for totals

    // Adjusted widths for the cells
    $this->Cell(70, 6, 'Total:', 1, 0, 'R', true); // Total label
    $this->Cell(10, 6, '9', 1, 0, 'R', true); // Total Quantity
    $this->Cell(30, 6, '970830.00', 1, 0, 'R', true); // Total Taxable Value
    $this->Cell(25, 6, '970830.00', 1, 0, 'R', true); // Total IGST Rate
    $this->Cell(55, 6, '970830.00', 1, 1, 'R', true); // Total IGST Amount
    // $this->Cell(30, 6, '970830.00', 1, 1, 'R', true); // Total IGST Amount


// Store the starting Y position of this section
$startY = $this->GetY();

// Define dimensions and settings
$leftWidth = 125; // Width for the left section
$rightWidth = 35; // Width for each right-side cell
$rowHeight = 7; // Height of each row on the right side
$lineSpacing = 4; // Spacing between sections
$sectionHeight = 28; // Total height of both sections (to make them equal)

// **Left Section: Invoice Amount in Words**
$this->SetFont('Arial', 'B', 10);
$this->MultiCell($leftWidth, $rowHeight, "Total Invoice Amount in Words", 1, 'C'); // Title
$this->SetFont('Arial', '', 10);
$leftContentHeight = $sectionHeight - $rowHeight; // Adjust remaining height
$this->MultiCell($leftWidth, $leftContentHeight, "Nine Lakh Seventy Thousand Eight Hundred Thirty Rupees Only", 1, 'C');

// **Right Section: Summary**
$this->SetXY($leftWidth + 10, $this->GetY() - $sectionHeight); // Align to the top of the right section
$this->SetFont('Arial', 'B', 8);

// Row 1: Total Amount Before Tax
$this->Cell($rightWidth, $rowHeight, "Total Amount Before Tax:", 1, 0, 'L');
$this->SetFont('Arial', '', 8);
$this->Cell($rightWidth, $rowHeight, "₹8,31,000.00", 1, 1, 'R');

// Row 2: Add IGST
$this->SetX($leftWidth + 10); // Maintain alignment
$this->SetFont('Arial', 'B', 8);
$this->Cell($rightWidth, $rowHeight, "Add: IGST:", 1, 0, 'L');
$this->SetFont('Arial', '', 8);
$this->Cell($rightWidth, $rowHeight, "₹1,39,830.00", 1, 1, 'R');

// Row 3: Tax Amount: GST
$this->SetX($leftWidth + 10);
$this->SetFont('Arial', 'B', 8);
$this->Cell($rightWidth, $rowHeight, "Tax Amount: GST:", 1, 0, 'L');
$this->SetFont('Arial', '', 8);
$this->Cell($rightWidth, $rowHeight, "₹1,39,830.00", 1, 1, 'R');

// Row 4: Amount With Tax
$this->SetX($leftWidth + 10);
$this->SetFont('Arial', 'B', 8);
$this->Cell($rightWidth, $rowHeight, "Amount With Tax:", 1, 0, 'L');
$this->SetFont('Arial', '', 8);
$this->Cell($rightWidth, $rowHeight, "₹9,70,830.00", 1, 1, 'R');

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
$pdf->AddDetails();
$pdf->AddTableHeader();
$pdf->AddTableRows();
$pdf->AddTotals();
// $pdf->AddBottomSection();
$pdf->Output();
?>
