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

$branch_id = $_SESSION['branch_id']; // Fetch branch ID from session
$from_date = isset($_POST['from_date']) ? trim($_POST['from_date']) : '';
$to_date = isset($_POST['to_date']) ? trim($_POST['to_date']) : '';
$report_type = isset($_POST['report_type']) ? trim($_POST['report_type']) : 'pdf';

if (empty($from_date) || empty($to_date)) {
    echo json_encode(["status" => "error", "message" => "Invalid Date Range"]);
    exit();
}

// Convert date format to MySQL format (YYYY-MM-DD)
$from_date_sql = date("Y-m-d", strtotime(str_replace('/', '-', $from_date)));
$to_date_sql = date("Y-m-d", strtotime(str_replace('/', '-', $to_date)));

// Convert to Indian format (DD-MM-YYYY)
$from_date_formatted = date("d-m-Y", strtotime($from_date_sql));
$to_date_formatted = date("d-m-Y", strtotime($to_date_sql));

// Fetch data from the database (filtered by branch_id)
$sql = "SELECT ii.productid, ii.product, SUM(ii.qty) AS total_qty, SUM(ii.qty * ii.price) AS total_amount
        FROM `invoice_items` ii
        JOIN `invoice` i ON i.id = ii.invoice_id
        WHERE i.branch_id = ? 
        AND DATE(i.created_on) BETWEEN ? AND ?
        GROUP BY ii.productid, ii.product 
        ORDER BY i.created_on DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sss", $branch_id, $from_date_sql, $to_date_sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$reportData = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Ensure reports directory exists
$directory = "generated_reports/";
if (!file_exists($directory)) {
    mkdir($directory, 0777, true);
}

// Define a unique file name with branch ID and the date range
$timestamp = date("YmdHis"); // Unique timestamp
$filename = "Product_Sales_Report_Branch_{$branch_id}_{$from_date_formatted}_to_{$to_date_formatted}_{$timestamp}." . ($report_type == 'pdf' ? 'pdf' : 'xlsx');
$file_path = $directory . $filename;

// ✅ Generate PDF using FPDF
if ($report_type == 'pdf') {
    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(190, 10, 'Product Wise Sales Report', 1, 1, 'C');
            $this->Ln(5);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    // Table Header
    $pdf->Cell(15, 10, 'S.No', 1);
    $pdf->Cell(100, 10, 'Product Name', 1);
    $pdf->Cell(30, 10, 'Total Qty', 1);
    $pdf->Cell(45, 10, 'Total Amount', 1);
    $pdf->Ln();

    // Table Data
    $pdf->SetFont('Arial', '', 12);
    $serial = 1;

    if (empty($reportData)) {
        $pdf->Cell(190, 10, 'No data found for the selected date range.', 1, 1, 'C');
    } else {
        foreach ($reportData as $row) {
            $pdf->Cell(15, 10, $serial, 1);
            $pdf->Cell(100, 10, $row['product'], 1);
            $pdf->Cell(30, 10, $row['total_qty'], 1);
            $pdf->Cell(45, 10, number_format($row['total_amount'], 2), 1);
            $pdf->Ln();
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
    $sheet->setCellValue('A1', 'Serial No.')
          ->setCellValue('B1', 'Product Name')
          ->setCellValue('C1', 'Total Quantity')
          ->setCellValue('D1', 'Total Amount');

    // Data
    $rowIndex = 2;
    $serial = 1;
    
    if (empty($reportData)) {
        $sheet->setCellValue('A2', 'No data found for the selected date range.');
    } else {
        foreach ($reportData as $row) {
            $sheet->setCellValue("A$rowIndex", $serial)
                  ->setCellValue("B$rowIndex", $row['product'])
                  ->setCellValue("C$rowIndex", $row['total_qty'])
                  ->setCellValue("D$rowIndex", number_format($row['total_amount'], 2));
            $rowIndex++;
            $serial++;
        }
    }

    // Save Excel File
    $writer = new Xlsx($spreadsheet);
    $writer->save($file_path);
}

// Output success response
echo json_encode(["status" => "success", "message" => "Report generated successfully.", "file_path" => $file_path]);
?>
