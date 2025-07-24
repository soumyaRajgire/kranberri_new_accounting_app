<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the necessary libraries
require 'vendor/autoload.php';  // Ensure this path is correct

// Database connection (use your own config file)
include("config.php");

// Fetch data from the POST request
$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];
$business_id = $_SESSION['business_id'];

// Create a new Spreadsheet
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers for the Excel file (column names)
$sheet->setCellValue('A1', 'Name');
$sheet->setCellValue('B1', 'Business Name');
$sheet->setCellValue('C1', 'Mobile');
$sheet->setCellValue('D1', 'Email');
$sheet->setCellValue('E1', 'PAN');
$sheet->setCellValue('F1', 'GSTIN');
$sheet->setCellValue('G1', 'Created By');
$sheet->setCellValue('H1', 'Created On');

// Query to fetch customer data based on date range
$sql = "SELECT * FROM customer_master 
        WHERE contact_type = 'Supplier' 
        AND business_id = '$business_id' 
        AND DATE(created_on) BETWEEN '$from_date' AND '$to_date'
        ORDER BY created_on DESC";

$result = $conn->query($sql);

// Check if there are any records
if ($result->num_rows > 0) {
    $rowNum = 2; // Start after headers
    while ($row = $result->fetch_assoc()) {
        // Write data rows dynamically
        $sheet->setCellValue('A' . $rowNum, $row['customerName']);
        $sheet->setCellValue('B' . $rowNum, $row['business_name']);
        $sheet->setCellValue('C' . $rowNum, $row['mobile']);
        $sheet->setCellValue('D' . $rowNum, $row['email']);
        $sheet->setCellValue('E' . $rowNum, $row['pan']);
        $sheet->setCellValue('F' . $rowNum, $row['gstin']);
        $sheet->setCellValue('G' . $rowNum, $row['created_by']);
        $sheet->setCellValue('H' . $rowNum, $row['created_on']);
        $rowNum++;
    }
} else {
    // If no data found, return a JSON response
    echo json_encode(['status' => 'error', 'message' => 'No data found for the specified date range.']);
    exit();
}

// Apply some basic styling (bold headers)
$sheet->getStyle('A1:H1')->getFont()->setBold(true);

// Set the headers for the Excel file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="suppliers_' . $from_date . '_to_' . $to_date . '.xlsx"');
header('Cache-Control: max-age=0');

// Prevent any unwanted output before writing the file
ob_clean();
flush();

// Write the Excel file to output
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save('php://output');
exit();
?>