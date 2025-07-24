<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download_range'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $type = $_POST['type'];
    $business_id = $_SESSION['business_id'];

    // Query based on product type and date range
    $sql = "SELECT p.*, c.category_name 
            FROM product_master p
            LEFT JOIN category_master c ON p.category_id = c.id
            WHERE p.business_id = '$business_id' 
            AND p.product_type = '$type'
            AND DATE(p.created_on) BETWEEN '$from_date' AND '$to_date'
            ORDER BY p.created_on DESC";

    $result = $conn->query($sql);

    // Set headers for Excel download
    $filename = str_replace(' ', '_', strtolower($type));
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '_' . $from_date . '_to_' . $to_date . '.xls"');

    // Create Excel content
    echo "Product Name\tCategory\tHSN Code\tGST Rate\tUnit\tSelling Price\tPurchase Price\tCreated On\n";

    while ($row = $result->fetch_assoc()) {
        echo $row['product_name'] . "\t";
        echo $row['category_name'] . "\t";
        echo $row['hsn_code'] . "\t";
        echo $row['gst_rate'] . "\t";
        echo $row['unit'] . "\t";
        echo $row['selling_price'] . "\t";
        echo $row['purchase_price'] . "\t";
        echo $row['created_on'] . "\n";
    }
    exit();
}
?>