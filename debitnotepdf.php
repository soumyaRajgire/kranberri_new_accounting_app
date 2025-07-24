<?php

// Initialize PDF instance
$pdf = new FPDF('P', 'mm', 'A4');
$file_name = md5(rand()) . '.pdf';
$filename = "debit_note/" . $file_name;

$pdf->AddPage();
$pdf->SetFont("Arial", "", 10);
$pdf->SetFillColor(232, 232, 232);

// Company and Debit Note Details Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, "Krika Mkb Corporation Private Limited", 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(100, 5, "Skyline Beverly Park, # D 402, Amruthahalli Main Road, Amruthahalli,\nBangalore - 560092, Karnataka\nEmail: abhijith.mavatoor@gmail.com\nPhone: 9481024700\nGSTIN: 29AAICK7493G1ZX", 0, 'L');

// Debit Note Details
$pdf->SetXY(120, 10); // Position to the right
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, "Debit Note", 0, 1, 'R');
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(120, 20);
$pdf->MultiCell(80, 5, "Note #: $dnote_code\nNote Date: $dnoteDate\nCreated By: $created_by", 0, 'R');

// Add a line break
$pdf->Ln(10);

// Supplier and Address Details
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(95, 10, "Supplier", 1, 0, 'C');
$pdf->Cell(95, 10, "Address", 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(95, 10, $customer_name, 1, 0, 'L');
$pdf->Cell(95, 10, $row1['b_state'], 1, 1, 'L');

// Description and Debit Amount
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(150, 10, "Description", 1, 0, 'C');
$pdf->Cell(40, 10, "Debit Amount", 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(150, 10, "Debit note on Purchase Invoice #$invoice_code for an amount of Rs.$total_amount", 1, 0, 'L');
$pdf->Cell(40, 10, number_format($total_amount, 2), 1, 1, 'R');

// Notes Section
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, "Notes:", 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 10, $note ?: "This is a computer-generated Debit Note. Thank you!", 0, 'L');

// Footer Section
$pdf->Ln(20);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 10, "For Krika Mkb Corporation Private Limited", 0, 1, 'R');
$pdf->Ln(5);
$pdf->Cell(0, 10, "Authorized Signatory", 0, 1, 'R');

// Save and Output PDF
$pdfdoc = $pdf->Output('S');
file_put_contents($filename, $pdfdoc);

echo "<script>window.location='purchase_invoices.php?DebitNoteCard'; alert('Successfully Created Debit Note');</script>";
?>
