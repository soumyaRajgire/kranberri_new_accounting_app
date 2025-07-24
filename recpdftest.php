
<?php
require('fpdf/fpdf.php');

class PDF extends FPDF {
    // Simple header
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Receipt', 0, 1, 'C');
    }

    // Simple footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'This is a computer-generated receipt. Thank you!', 0, 0, 'C');
    }
}

// Instantiation of FPDF class
$pdf = new PDF();
$pdf->AddPage();

// Payor details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Payor', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'soumya n', 0, 1);
$pdf->Cell(0, 10, 'soumyacn16@gmail.com', 0, 1);

// Space
$pdf->Ln(10);

// Receipt details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Receipt #:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, '2023-4', 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Receipt Date:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, '14-12-2023', 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Created By:', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'VENKATESH', 0, 1);

// Space
$pdf->Ln(10);

// Amount
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Amount', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Rs.10000.00', 0, 1);

// Space
$pdf->Ln(10);

// Description
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Description', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, 'Received from soumya n an amount of Rs.10000.00 (Ten thousands) through Cheque');

// Space
$pdf->Ln(10);

// Footer Text
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, 'Authorised Signatory', 0, 0, 'R');

$pdf->Output();
?>
