<?php
session_start();
include("config.php");
include("includes/download_handler.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download_month'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    
    $query = "SELECT * FROM inventory_master WHERE inventory_type = 'Purchased Items' 
              AND MONTH(created_on) = ? AND YEAR(created_on) = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Name', 'Category', 'Purchase Price', 'GST Rate', 'Net Price', 'HSN Code', 'Units', 'Can Be Sold'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['name'],
            $row['category'],
            $row['price'],
            $row['gst_rate'],
            $row['net_price'],
            $row['hsn_code'],
            $row['units'],
            $row['can_be_sold']
        ];
    }

    $filename = "purchase_items_{$year}_{$month}.csv";
    generateCSV($headers, $data, $filename);
}