<?php
session_start(); // Ensure session is started
include("config.php");
require 'vendor/autoload.php'; // Load required libraries

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
include("fpdf/fpdf.php");

// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit();
}

// Ensure branch_id exists in session
if (!isset($_SESSION['branch_id'])) {
    echo json_encode(["status" => "error", "message" => "Branch ID not set. Please log in again."]);
    exit();
}

// Fetch and sanitize request parameters
$branch_id = $_SESSION['branch_id']; // Fetch branch ID from session
$from_date = isset($_POST['from_date']) ? trim($_POST['from_date']) : '';
$to_date = isset($_POST['to_date']) ? trim($_POST['to_date']) : '';
$customerid = isset($_POST['customerid']) ? trim($_POST['customerid']) : ''; 
$report_type = isset($_POST['report_type']) ? trim($_POST['report_type']) : 'pdf';

// Validate input
if (empty($from_date) || empty($to_date) || empty($customerid)) {
    echo json_encode(["status" => "error", "message" => "Invalid Date Range or Customer ID"]);
    exit();
}

// Convert dates to MySQL format (YYYY-MM-DD)
$from_date_sql = date("Y-m-d", strtotime(str_replace('/', '-', $from_date)));
$to_date_sql = date("Y-m-d", strtotime(str_replace('/', '-', $to_date)));

// Convert to Indian format (DD-MM-YYYY)
$from_date_formatted = date("d-m-Y", strtotime($from_date_sql));
$to_date_formatted = date("d-m-Y", strtotime($to_date_sql));

// Fetch data from database using prepared statements
$sql = "SELECT 
            i.invoice_code,
            i.invoice_date,
            i.customer_name AS buyer_name,
            SUM(ii.line_total) AS line_total, 
            SUM(ii.cgst) AS cgst,
            SUM(ii.sgst) AS sgst,
            SUM(ii.igst) AS igst,
            SUM(ii.cess_amount) AS cess,
            SUM(ii.total) AS total_amount
        FROM 
            invoice i
        LEFT JOIN 
            invoice_items ii ON i.id = ii.invoice_id
        WHERE 
            i.customer_id = ? AND i.branch_id = ? 
            AND DATE(i.invoice_date) BETWEEN ? AND ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $customerid, $branch_id, $from_date_sql, $to_date_sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$reportData = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Ensure reports directory exists
$directory = "generated_reports/";
if (!file_exists($directory)) {
    mkdir($directory, 0777, true);
}

// Define a unique file name
$timestamp = date("YmdHis");
$filename = "Individual_Party_Wise_Sales_Report_Branch_{$branch_id}_{$from_date_formatted}_to_{$to_date_formatted}_{$timestamp}." . ($report_type == 'pdf' ? 'pdf' : 'xlsx');
$file_path = $directory . $filename;

// ✅ Generate PDF using FPDF
if ($report_type == 'pdf') {
    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 10); // Increased font size for title
            $this->Cell(280, 10, 'Individual Party Wise Sales Report', 1, 1, 'C');
            $this->Ln(5);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8); // Increased footer font size
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage('L'); // Landscape Mode
    $pdf->SetFont('Arial', 'B', 10); // Increased font size for table headings

    // ✅ Increased Column Widths for Better Spacing
    $header = ['S.No', 'Date', 'Invoice No.', 'Taxable Amt', 'CGST', 'SGST', 'IGST', 'CESS', 'Total Amount'];
    $widths = [15, 35, 50, 40, 25, 25, 25, 25, 40]; // Increased column widths

    // ✅ Apply Bold Headers with Larger Font Size
    foreach ($header as $index => $col) {
        $pdf->Cell($widths[$index], 10, $col, 1, 0, 'C');
    }
    $pdf->Ln();

    // ✅ Updated Table Data Style
    $pdf->SetFont('Arial', '', 10); // Increased font size for values
    $serial = 1;

    if (empty($reportData)) {
        $pdf->Cell(array_sum($widths), 10, 'No data found for the selected date range.', 1, 1, 'C');
    } else {
        foreach ($reportData as $row) {
            // ✅ Corrected Taxable Amount Calculation
            $taxable_amount = $row['line_total'] - ($row['cgst'] + $row['sgst'] + $row['igst'] + $row['cess']);

            $pdf->Cell($widths[0], 8, $serial, 1, 0, 'C'); // S.No
            $pdf->Cell($widths[1], 8, $row['invoice_date'], 1, 0, 'C'); // Date
            $pdf->Cell($widths[2], 8, $row['invoice_code'], 1, 0, 'L'); // Invoice No.
            $pdf->Cell($widths[3], 8, number_format($taxable_amount, 2), 1, 0, 'R'); // Taxable Amount
            $pdf->Cell($widths[4], 8, number_format($row['cgst'], 2), 1, 0, 'R'); // CGST
            $pdf->Cell($widths[5], 8, number_format($row['sgst'], 2), 1, 0, 'R'); // SGST
            $pdf->Cell($widths[6], 8, number_format($row['igst'], 2), 1, 0, 'R'); // IGST
            $pdf->Cell($widths[7], 8, number_format($row['cess'], 2), 1, 0, 'R'); // CESS
            $pdf->Cell($widths[8], 8, number_format($row['total_amount'], 2), 1, 1, 'R'); // Total Amount
            $serial++;
        }
    }

    $pdf->Output('F', $file_path);
}


// ✅ Generate Excel using PhpSpreadsheet
elseif ($report_type == 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ✅ Define Headers (Consistent with PDF Report)
    $headers = ['S.No', 'Date', 'Invoice No.', 'Taxable Amt', 'CGST', 'SGST', 'IGST', 'CESS', 'Total Amount'];
    
    // ✅ Set Headers in First Row
    $sheet->fromArray([$headers], null, 'A1');

    // ✅ Apply Header Formatting (Bold)
    $headerStyle = [
        'font' => ['bold' => true],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
    ];
    $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

    // ✅ Set Column Widths for Better Readability
    $columnWidths = [6, 15, 20, 18, 12, 12, 12, 12, 18];
    $columnLetters = range('A', 'I');
    foreach ($columnLetters as $index => $column) {
        $sheet->getColumnDimension($column)->setWidth($columnWidths[$index]);
    }

    // ✅ Data Insertion
    $rowIndex = 2;
    $serial = 1;
    
    if (!empty($reportData)) {
        foreach ($reportData as $row) {
            // ✅ Correct Calculation for Taxable Amount
            $taxable_amount = $row['line_total'] - ($row['cgst'] + $row['sgst'] + $row['igst'] + $row['cess']);

            // ✅ Insert Row Data
            $sheet->fromArray([
                $serial, 
                $row['invoice_date'], 
                $row['invoice_code'], 
                number_format($taxable_amount, 2), 
                number_format($row['cgst'], 2), 
                number_format($row['sgst'], 2), 
                number_format($row['igst'], 2), 
                number_format($row['cess'], 2), 
                number_format($row['total_amount'], 2)
            ], null, "A$rowIndex");

            // ✅ Apply Border for Each Row
            $sheet->getStyle("A$rowIndex:I$rowIndex")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            ]);

            $rowIndex++;
            $serial++;
        }
    } else {
        // ✅ If No Data Found, Insert "No Data Found" Message
        $sheet->setCellValue("A$rowIndex", "No data found for the selected date range.");
        $sheet->mergeCells("A$rowIndex:I$rowIndex");
        $sheet->getStyle("A$rowIndex")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    // ✅ Save the Excel File
    $writer = new Xlsx($spreadsheet);
    $writer->save($file_path);
}

echo json_encode(["status" => "success", "message" => "Report generated successfully.", "file_path" => $file_path]);
?>

