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
$productid = isset($_POST['productid']) ? trim($_POST['productid']) : ''; 
$report_type = isset($_POST['report_type']) ? trim($_POST['report_type']) : 'pdf';

// Validate input
if (empty($from_date) || empty($to_date) || empty($productid)) {
    echo json_encode(["status" => "error", "message" => "Invalid Date Range or Product ID"]);
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
            i.customer_name AS buyer_name,
            DATE_FORMAT(i.invoice_date, '%d/%m/%Y') AS invoice_date,
            ii.qty AS quantity,
            ii.price AS rate,
            ii.line_total,
            ii.cgst,
            ii.sgst,
            ii.igst,
            ii.cess_amount AS cess,
            ii.total AS total_amount
        FROM 
            invoice_items ii
        JOIN 
            invoice i ON ii.invoice_id = i.id
        WHERE 
            ii.productid = ? AND i.branch_id = ? 
            AND DATE(i.invoice_date) BETWEEN ? AND ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $productid, $branch_id, $from_date_sql, $to_date_sql);
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
$filename = "Individual_Product_Wise_Sales_Report_Branch_{$branch_id}_{$from_date_formatted}_to_{$to_date_formatted}_{$timestamp}." . ($report_type == 'pdf' ? 'pdf' : 'xlsx');
$file_path = $directory . $filename;

// ✅ Generate PDF using FPDF
if ($report_type == 'pdf') {
    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(275, 7, 'Individual Product Wise Sales Report', 1, 1, 'C');
            $this->Ln(5);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage('L'); // Landscape Mode
    $pdf->SetFont('Arial', 'B', 9);

    // Table Header
    $header = ['S.No', 'Invoice No.', 'Buyer Name', 'Invoice Date', 'Qty', 'Rate', 'Taxable Amt', 'CGST', 'SGST', 'IGST', 'CESS', 'Total Amount'];
    $widths = [12, 25, 45, 22, 12, 20, 25, 20, 20, 20, 20, 34];

    foreach ($header as $index => $col) {
        $pdf->Cell($widths[$index], 7, $col, 1, 0, 'C');
    }
    $pdf->Ln();

    // Table Data
    $pdf->SetFont('Arial', '', 8);
    $serial = 1;
    if (empty($reportData)) {
        $pdf->Cell(array_sum($widths), 7, 'No data found for the selected date range.', 1, 1, 'C');
    } else {
        foreach ($reportData as $row) {
            $taxable_amount = $row['line_total'] - ($row['cgst'] + $row['sgst'] + $row['igst'] + $row['cess']);

            $pdf->Cell($widths[0], 7, $serial, 1, 0, 'C');
            $pdf->Cell($widths[1], 7, $row['invoice_code'], 1, 0, 'C');
            $pdf->Cell($widths[2], 7, $row['buyer_name'], 1, 0, 'L');
            $pdf->Cell($widths[3], 7, $row['invoice_date'], 1, 0, 'C');
            $pdf->Cell($widths[4], 7, $row['quantity'], 1, 0, 'C');
            $pdf->Cell($widths[5], 7, number_format($row['rate'], 2), 1, 0, 'R');
            $pdf->Cell($widths[6], 7, number_format($taxable_amount, 2), 1, 0, 'R');
            $pdf->Cell($widths[7], 7, number_format($row['cgst'], 2), 1, 0, 'R');
            $pdf->Cell($widths[8], 7, number_format($row['sgst'], 2), 1, 0, 'R');
            $pdf->Cell($widths[9], 7, number_format($row['igst'], 2), 1, 0, 'R');
            $pdf->Cell($widths[10], 7, number_format($row['cess'], 2), 1, 0, 'R');
            $pdf->Cell($widths[11], 7, number_format($row['total_amount'], 2), 1, 1, 'R');
            $serial++;
        }
    }

    $pdf->Output('F', $file_path);
}

// ✅ Generate Excel using PhpSpreadsheet
elseif ($report_type == 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers
    $sheet->fromArray([
        ['S.No', 'Invoice No.', 'Buyer Name', 'Invoice Date', 'Quantity', 'Rate', 'Taxable Amount', 'CGST', 'SGST', 'IGST', 'CESS', 'Total Amount']
    ], null, 'A1');

    // Data
    $rowIndex = 2;
    $serial = 1;
    if (!empty($reportData)) {
        foreach ($reportData as $row) {
            $taxable_amount = $row['line_total'] - ($row['cgst'] + $row['sgst'] + $row['igst'] + $row['cess']);
            
            $sheet->fromArray([
                $serial, 
                $row['invoice_code'], 
                $row['buyer_name'], 
                $row['invoice_date'], 
                $row['quantity'], 
                number_format($row['rate'], 2), 
                number_format($taxable_amount, 2), 
                number_format($row['cgst'], 2), 
                number_format($row['sgst'], 2), 
                number_format($row['igst'], 2), 
                number_format($row['cess'], 2), 
                number_format($row['total_amount'], 2)
            ], null, "A$rowIndex");
            $rowIndex++;
            $serial++;
        }
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save($file_path);
}

echo json_encode(["status" => "success", "message" => "Report generated successfully.", "file_path" => $file_path]);
?>
