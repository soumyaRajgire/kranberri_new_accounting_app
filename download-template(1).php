<?php
require 'vendor/autoload.php';  // Include the Composer autoloader

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Create a new spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Define fields as per the SQL query
    $fields = [
        'id', 'inventory_type', 'catlog_type', 'name', 'category', 'company_name', 'price', 'in_ex_gst', 'gst_rate',
        'non_taxable', 'net_price', 'hsn_code', 'SAC_Code', 'units', 'cess_rate', 'cess_amt',
        'sku', 'opening_stock', 'opening_stockdate',
        'min_stockalert', 'max_stockalert', 'sold_stock', 'description', 'remark',
        'created_by', 'created_on', 'last_updated_by', 'last_updated_at'
    ];

    // Add fields to the first row of the spreadsheet
    $col = 'A';
    foreach ($fields as $field) {
        $sheet->setCellValue($col . '1', $field);
        $col++;
    }

    // Set headers to prompt a download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="inventory_template.xlsx"');
    header('Cache-Control: max-age=0');

    // Create and send the file to the browser
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} catch (Exception $e) {
    echo 'Error generating Excel file: ' . $e->getMessage();
}
