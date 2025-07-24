<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download_month'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    $type = $_POST['type'];
    $business_id = $_SESSION['business_id'];

    // Query based on product type
    $sql = "SELECT p.*, c.category_name 
            FROM product_master p
            LEFT JOIN category_master c ON p.category_id = c.id
            WHERE p.business_id = '$business_id' 
            AND p.product_type = '$type'
            AND MONTH(p.created_on) = '$month' 
            AND YEAR(p.created_on) = '$year'
            ORDER BY p.created_on DESC";

    $result = $conn->query($sql);

    // Set headers for Excel download
    $filename = str_replace(' ', '_', strtolower($type));
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '_' . $year . '_' . $month . '.xls"');

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