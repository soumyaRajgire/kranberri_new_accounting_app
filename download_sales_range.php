<?php
session_start();
include("config.php");
include("includes/download_handler.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download_range'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    
    $query = "SELECT * FROM inventory_master WHERE inventory_type = 'Sales Catalog' 
              AND DATE(created_on) BETWEEN ? AND ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $from_date, $to_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Name', 'Category', 'Price', 'GST Rate', 'Net Price', 'HSN Code', 'Units'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['name'],
            $row['category'],
            $row['price'],
            $row['gst_rate'],
            $row['net_price'],
            $row['hsn_code'],
            $row['units']
        ];
    }

    $filename = "sales_catalog_{$from_date}_to_{$to_date}.csv";
    generateCSV($headers, $data, $filename);
}