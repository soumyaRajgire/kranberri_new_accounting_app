<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download_month'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    $business_id = $_SESSION['business_id'];

    // Query to get customers for the selected month and year
    $sql = "SELECT * FROM customer_master 
            WHERE contact_type = 'Customer' 
            AND business_id = '$business_id' 
            AND MONTH(created_on) = '$month' 
            AND YEAR(created_on) = '$year'
            ORDER BY created_on DESC";

    $result = $conn->query($sql);

    // Set headers for CSV download instead of Excel
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="customers_' . $year . '_' . $month . '.csv"');
    
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