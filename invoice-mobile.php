<?php
require('fpdf/fpdf.php');

class InvoicePDF extends FPDF {

    function Header() {
        // Samsung logo (left)
        // $this->Image('https://via.placeholder.com/100x50?text=Samsung', 10, 8, 40);

        // // Oppo logo (right)
        // $this->Image('https://via.placeholder.com/100x50?text=Oppo', 160, 8, 40);

          // Outline the whole page
          $this->Rect(10, 8, 190, 160); // Adjust dimensions for A4 page with a margin

        // Title
        $this->SetFont('Times', 'B', 14);
        $this->Cell(0, 15, 'SELF PC AMAR COMPUTERS', 0, 1, 'C');

        // Subtitle
        $this->SetFont('Times', '', 10);
        $this->Cell(0, 8, 'KARIMNAGA - 152026 (03)', 0, 0, 'C');
        $this->Cell(0, 2, 'Ph No: 9059670107', 0, 1, 'R');

        // Spacing
        $this->Ln(5);
    }

    function BasicInfo() {
        $this->SetFont('Times', '', 8);
    
        // Bill information with adjusted alignment for the second and third cells
        $this->Cell(50, 5, 'BILL NO.: 3', 'TLB', 0, 'L'); // Left align
        $this->Cell(70, 5, 'Terms: Credit', 'TB', 0, 'C'); // Right align
        $this->Cell(70, 5, 'DATE: 14-11-2024', 'TRB', 1, 'R'); // Right align
    
        // Party information without inside vertical and horizontal lines
    // Row 1
    $this->Cell(95, 8, 'Party Name: liiqbets', 'L', 0); // Left border only
    $this->Cell(95, 8, 'Mobile No.: ', 'R', 1); // Right border only

    // Row 2
    $this->Cell(95, 8, 'Address: , Bangalore', 'L', 0); // Left border only
    $this->Cell(95, 8, 'GST No.: ', 'R', 1); // Right border only
        $this->Ln(0); // Line break
    }
    
    
    

    function InvoiceTable() {
      // Set the fill color to grey
$this->SetFillColor(200, 200, 200); // RGB values for light grey

// Table header with grey background
$this->SetFont('Times', 'B', 8);
$this->Cell(10, 5, 'S.N', 1, 0, 'C', true);
$this->Cell(60, 5, 'Item Name', 1, 0, 'C', true);
$this->Cell(20, 5, 'HSN CODE', 1, 0, 'C', true);
$this->Cell(15, 5, 'Qty', 1, 0, 'C', true);
$this->Cell(15, 5, 'Unit', 1, 0, 'C', true);
$this->Cell(20, 5, 'Price', 1, 0, 'C', true);
$this->Cell(15, 5, 'SGST %', 1, 0, 'C', true);
$this->Cell(15, 5, 'CGST %', 1, 0, 'C', true);
$this->Cell(20, 5, 'Amount', 1, 1, 'C', true);


        // Table data (example row)
        $this->SetFont('Times', '', 8);
        $this->Cell(10, 30, '1', 1, 0, 'C');
        $this->Cell(60, 30, 'KEYBOARD', 1, 0, 'L');
        $this->Cell(20, 30, '', 1, 0, 'C');
        $this->Cell(15, 30, '0.5', 1, 0, 'C');
        $this->Cell(15, 30, 'Pcs', 1, 0, 'C');
        $this->Cell(20, 30, '550.00', 1, 0, 'C');
        $this->Cell(15, 30, '0.00', 1, 0, 'C');
        $this->Cell(15, 30, '0.00', 1, 0, 'C');
        $this->Cell(20, 30, '3465.00', 1, 1, 'C');
    }

    function FooterTable()
{
    $this->SetFont('Times', '', 8);

    // Draw an outline box for the entire footer
    $startY = $this->GetY() + 5;
    $this->Rect(10, $startY, 190, 75); // Outer box for the whole footer

     // Draw an outline box for the entire footer
    $startY = $this->GetY() + 5;
    $this->Rect(10, $startY, 190, 75); // Outer box for the whole footer

    // LEFT SIDE CONTENT
    $this->SetXY(12, $startY + 2); // Start position for left content
    $this->MultiCell(90, 4, 
        "IGST Amt: 165.00\n\n" . 
        "Amount In Words:\nThree Thousand Four Hundred Sixty Five Only\n\n" . 
        "Terms & Conditions:\n" . 
        "1. Product Sales Service will be provided at the authorized service center only.\n" . 
        "2. All Subject to Bhuj Jurisdiction only.", 
        0, 'L'
    );

    // Now move to the correct position to add "Warranty Parts" after the "Amount In Words" field
    $this->SetXY(12, $this->GetY() + 2); // Set Y to next available position after the "Amount In Words" field

    // Add the "Warranty Parts" inside a bordered cell
    $this->Cell(120, 6, 
        "Warranty Parts: HEADPHONE (3 MONTHS), CHARGER (6 MONTHS), PHONE (1 YEAR)", 
        1, 1, 'L' // Border on all sides
    );
     
 

    // RIGHT SIDE TOTALS
    $this->SetXY(125, $startY + 2); // Start position for right content
    $this->SetFont('Times', '', 8);

    // Outer box for the right section (narrower width)
    $this->Rect(125, $startY, 75, 30); // Outline for the right section with reduced width

    // Each row with text and price
    $rows = [
        ['Total Amount Before Tax', '3300.00'],
        ['Discount Amt', '0.00'],
        ['Add: SGST', '0.00'],
        ['Add: CGST', '0.00'],
        ['Total Tax Amount: GST', '165.00'],
        ['BILL AMOUNT', '3465.00']
    ];

    $rowHeight = 4; // Adjust row height for tighter spacing
    $outlineWidth = 70; // Total width of the right section's outline
    $columnWidth = $outlineWidth / 2; // Divide the outline width equally between text and price columns

    $currentY = $startY + 2;

    foreach ($rows as $index => $row) {
        $this->SetXY(127, $currentY); // Adjusted X position with padding
        // For the last two rows, increase font size and add full borders
        if ($index == 4 || $index == 5) {
            // Increase font size for these rows
            $this->SetFont('Times', 'B', 8); // Bold, increased size
            // Add borders for both text and price columns
            $this->Cell($columnWidth, $rowHeight, $row[0], 1, 0, 'L'); // Text column with border
            $this->Cell($columnWidth, $rowHeight, $row[1], 1, 1, 'R'); // Price column with border
        } else {
            $this->SetFont('Times', '', 8); // Reset font size for other rows
            // No border for text and price columns (no horizontal lines)
            $this->Cell($columnWidth, $rowHeight, $row[0], 0, 0, 'L'); // Text column without border
            $this->Cell($columnWidth, $rowHeight, $row[1], 0, 1, 'R'); // Price column without border
        }
        $currentY += $rowHeight; // Move to the next row
    }

    // Add Signature Boxes
    $this->SetXY(75, $startY + 60);
    $this->Cell(50, 10, 'Customer Signature', 1, 0, 'C'); // Left signature box with border
 
    $this->Cell(70, 20, 'Auth. Signatory', 0, 0, 'R'); // Right signature box with border
}
}


$pdf = new InvoicePDF();
$pdf->AddPage();
$pdf->BasicInfo();
$pdf->InvoiceTable();
$pdf->FooterTable();

$pdf->Output();