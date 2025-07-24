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

function getDateCondition($table_date_field, $from_date = null, $to_date = null, $month = null, $year = null) {
    if ($from_date && $to_date) {
        return " AND DATE($table_date_field) BETWEEN '$from_date' AND '$to_date'";
    } elseif ($month && $year) {
        return " AND MONTH($table_date_field) = $month AND YEAR($table_date_field) = $year";
    }
    return "";
}
?>
