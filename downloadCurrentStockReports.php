<?php
require 'config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $report_type = $_POST['reportType']; // either 'pdf' or 'excel'
    $whatsappOption = isset($_POST['whatsappOption']) ? true : false; // Check if WhatsApp option is selected
    
    // Query to fetch data within the selected date range
    $query = "SELECT `id`, `inventory_type`, `can_be_sold`, `catlog_type`, `name`, `category`, `company_name`, `price`, 
              `in_ex_gst`, `gst_rate`, `non_taxable`, `net_price`, `hsn_code`, `SAC_Code`, `units`, `cess_rate`, 
              `cess_amt`, `sku`, `opening_stock`, `opening_stockdate`, `min_stockalert`, `max_stockalert`, `Stock_in`, 
              `stock_out`, `balance_stock`, `description`, `remark`, `created_by`, `created_on`, `last_updated_by`, 
              `last_updated_at` 
              FROM `inventory_master` 
              WHERE `created_on` BETWEEN '$from_date' AND '$to_date'";
              
            //  echo $query;

    // Connect to your database and fetch the data (assuming you have a $conn connection variable)
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch data into an array
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Generate the report based on selected type (PDF or Excel)
        if ($report_type == 'pdf') {
            
            generatePDFReport($data);
        } else if ($report_type == 'excel') {
            
            generateExcelReport($data);
        }
        //echo $report_type;

        // Optionally send the report via WhatsApp if the checkbox is checked
        if ($whatsappOption) {
            sendReportViaWhatsApp($data);
        }
    } else {
        echo "No data found for the selected date range.";
    }
}

// Function to generate PDF Report
function generatePDFReport($data) {
    // Use a library like FPDF or TCPDF for generating PDF reports.
    //require('fpdf.php');
    include("fpdf/fpdf.php");
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    
    // Add table headers
    $pdf->Cell(30, 10, 'Item Code');
    $pdf->Cell(60, 10, 'Item Name');
    $pdf->Cell(20, 10, 'Stock Value');
    $pdf->Cell(20, 10, 'Purchase Price');
    $pdf->Cell(20, 10, 'Sales Price');
    $pdf->Cell(20, 10, 'Stock In Hand');
    $pdf->Ln();
    
    // Loop through data and add rows to the table
    foreach ($data as $row) {
        $pdf->Cell(30, 10, $row['sku']);
        $pdf->Cell(60, 10, $row['name']);
        $pdf->Cell(20, 10, $row['balance_stock']);
        $pdf->Cell(20, 10, $row['price']);
        $pdf->Cell(20, 10, $row['net_price']);
        $pdf->Cell(20, 10, $row['opening_stock'] . ' ' . $row['units']);
        $pdf->Ln();
    }

    // Output PDF
    $pdf->Output('D', 'report.pdf');
}

// Function to generate Excel Report
function generateExcelReport($data) {
    // Use a library like PhpSpreadsheet for generating Excel files
    require('vendor/autoload.php');
    
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set column headers
    $sheet->setCellValue('A1', 'Item Code');
    $sheet->setCellValue('B1', 'Item Name');
    $sheet->setCellValue('C1', 'Stock Value');
    $sheet->setCellValue('D1', 'Purchase Price');
    $sheet->setCellValue('E1', 'Sales Price');
    $sheet->setCellValue('F1', 'Stock In Hand');
    
    // Fill data in rows
    $rowNum = 2;
    foreach ($data as $row) {
        $sheet->setCellValue('A' . $rowNum, $row['sku']);
        $sheet->setCellValue('B' . $rowNum, $row['name']);
        $sheet->setCellValue('C' . $rowNum, $row['balance_stock']);
        $sheet->setCellValue('D' . $rowNum, $row['price']);
        $sheet->setCellValue('E' . $rowNum, $row['net_price']);
        $sheet->setCellValue('F' . $rowNum, $row['opening_stock'] . ' ' . $row['units']);
        $rowNum++;
    }

    // Set headers for download
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="inventory_report.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
}

// Function to send report via WhatsApp (Placeholder)
function sendReportViaWhatsApp($data) {
    // Use a third-party API to send the report via WhatsApp
    // This can be done with WhatsApp Business API or a service like Twilio
    // This is a placeholder function and needs actual implementation.
    echo "Sending report via WhatsApp... (this requires an API implementation)";
}
?>
