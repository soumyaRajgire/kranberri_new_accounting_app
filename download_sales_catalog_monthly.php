<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download_month'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    $business_id = $_SESSION['business_id'];

    // Query for Sales Catalog items
    $sql = "SELECT i.*, c.category_name 
            FROM inventory_master i
            LEFT JOIN category_master c ON i.category = c.id
            WHERE i.business_id = '$business_id' 
            AND i.inventory_type = 'Sales Catalog'
            AND MONTH(i.created_on) = '$month' 
            AND YEAR(i.created_on) = '$year'
            ORDER BY i.created_on DESC";

    $result = $conn->query($sql);

    // Set headers for Excel download
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="sales_catalog_' . $year . '_' . $month . '.csv"');

    // Create Excel content
    echo "Name\tCategory\tType\tHSN Code\tSAC Code\tGST Rate\tUnit\tPrice\tNet Price\tOpening Stock\tSold Stock\tCreated On\n";

    while ($row = $result->fetch_assoc()) {
        echo $row['name'] . "\t";
        echo $row['category_name'] . "\t";
        echo $row['catlog_type'] . "\t";
        echo $row['hsn_code'] . "\t";
        echo $row['SAC_Code'] . "\t";
        echo $row['gst_rate'] . "\t";
        echo $row['units'] . "\t";
        echo $row['price'] . "\t";
        echo $row['net_price'] . "\t";
        echo $row['opening_stock'] . "\t";
        echo $row['sold_stock'] . "\t";
        echo $row['created_on'] . "\n";
    }
    exit();
}
?>