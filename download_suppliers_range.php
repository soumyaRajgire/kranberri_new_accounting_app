<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download_range'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $business_id = $_SESSION['business_id'];

    // Query to get suppliers for the selected date range
    $sql = "SELECT * FROM customer_master 
            WHERE contact_type = 'Supplier' 
            AND business_id = '$business_id' 
            AND DATE(created_on) BETWEEN '$from_date' AND '$to_date'
            ORDER BY created_on DESC";

    $result = $conn->query($sql);

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="suppliers_' . $from_date . '_to_' . $to_date . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for proper Excel encoding
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Write headers
    $headers = ['Name', 'Business Name', 'Mobile', 'Email', 'PAN', 'GSTIN', 'Created By', 'Created On'];
    fputcsv($output, $headers);

    // Write data rows
    while ($row = $result->fetch_assoc()) {
        $data = [
            $row['customerName'],
            $row['business_name'],
            $row['mobile'],
            $row['email'],
            $row['pan'],
            $row['gstin'],
            $row['created_by'],
            $row['created_on']
        ];
        fputcsv($output, $data);
    }
    
    fclose($output);
    exit();
}
?>