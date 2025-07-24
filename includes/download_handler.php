<?php
function generateCSV($headers, $data, $filename) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for proper Excel encoding
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Write headers
    fputcsv($output, $headers);
    
    // Write data rows
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit();
}

function downloadPDF($file_path, $download_name = null, $headers = null, $data = null) {
    if ($headers && $data) {
        // Add error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Check if FPDF is available
        if (!file_exists('fpdf/fpdf.php')) {
            die('FPDF library not found');
        }
        
        require_once('fpdf/fpdf.php');
        $pdf = new FPDF();
        $pdf->AddPage('L'); // Use landscape orientation for better table fit
        
        // Calculate column width
        $pageWidth = $pdf->GetPageWidth() - 20; // 20mm margins
        $colWidth = $pageWidth / count($headers);
        
        // Add headers
        $pdf->SetFont('Arial', 'B', 10);
        foreach ($headers as $header) {
            $pdf->Cell($colWidth, 10, $header, 1);
        }
        $pdf->Ln();
        
        // Add data
        $pdf->SetFont('Arial', '', 9);
        foreach ($data as $row) {
            foreach ($row as $cell) {
                $pdf->Cell($colWidth, 10, $cell, 1);
            }
            $pdf->Ln();
        }
        
        // Output the PDF
        $pdf->Output('D', $download_name);
        exit();
    }
}

function getDateCondition($table_date_field, $from_date = null, $to_date = null, $month = null, $year = null) {
    if ($from_date && $to_date) {
        return " AND DATE($table_date_field) BETWEEN '$from_date' AND '$to_date'";
    } elseif ($month && $year) {
        return " AND MONTH($table_date_field) = $month AND YEAR($table_date_field) = $year";
    }
    return "";
}
?>
