<?php
session_start();
include('config.php');



error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//=================

// Only these 6 tables will be exported
$tables = [
    'billsupply_additional_charges',
    'billsupply_items',
    'billsupply_other_details',
    'billsupply_transport_details',
    'bill_of_supply',
    'bill_receipts'
];

// File where you want to store the exported tables
$outputFile = 'bill_supply_tables.sql';

// Open file for writing
$handle = fopen($outputFile, 'w');

if (!$handle) {
    die("Could not open file for writing");
}

// Add header to SQL file
fwrite($handle, "-- SQL Dump of Bill Supply tables\n");
fwrite($handle, "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n");


foreach ($tables as $table) {
    // Add table header
    fwrite($handle, "-- Table structure for table `$table`\n");
    
    // Get table creation SQL
    $result = mysqli_query($conn, "SHOW CREATE TABLE `$table`");
    if (!$result) {
        die("Error getting table structure for $table: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_row($result);
    fwrite($handle, $row[1] . ";\n\n");
    
    // Get table data
    fwrite($handle, "-- Dumping data for table `$table`\n");
    $result = mysqli_query($conn, "SELECT * FROM `$table`");
    if (!$result) {
        die("Error getting data from $table: " . mysqli_error($conn));
    }

    $num_fields = mysqli_num_fields($result);
    
    while ($row = mysqli_fetch_row($result)) {
        fwrite($handle, "INSERT INTO `$table` VALUES (");
        for ($i = 0; $i < $num_fields; $i++) {
            if (isset($row[$i])) {
                // Escape string values
                if (is_string($row[$i])) {
                    fwrite($handle, "'" . mysqli_real_escape_string($conn, $row[$i]) . "'");
                } else {
                    fwrite($handle, $row[$i]);
                }
            } else {
                fwrite($handle, "NULL");
            }
            
            if ($i < ($num_fields - 1)) {
                fwrite($handle, ",");
            }
        }
        fwrite($handle, ");\n");
    }
    fwrite($handle, "\n\n");
}

// Close file
fclose($handle);

// Offer file for download
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($outputFile) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($outputFile));
readfile($outputFile);

// Delete the file after download
unlink($outputFile);
exit;
?>
