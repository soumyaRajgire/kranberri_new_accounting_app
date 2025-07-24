<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit;
}

include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and validate
    $catlog_type = isset($_POST['catlog_type']) ? mysqli_real_escape_string($conn, $_POST['catlog_type']) : null;
    $inventory_type = isset($_POST['inventory_type']) ? mysqli_real_escape_string($conn, $_POST['inventory_type']) : null;
    $name = isset($_POST['goods_name']) ? mysqli_real_escape_string($conn, $_POST['goods_name']) : null;
    $category = isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : null;
    $company_name = isset($_POST['company_name']) ? mysqli_real_escape_string($conn, $_POST['company_name']) : null;

    $price = isset($_POST['price']) ? mysqli_real_escape_string($conn, $_POST['price']) : null;
    $inclusive_gst = isset($_POST['inclusive_gst']) ? mysqli_real_escape_string($conn, $_POST['inclusive_gst']) : null;
    $gst_rate = isset($_POST['gst_rate']) ? mysqli_real_escape_string($conn, $_POST['gst_rate']) : null;
    $non_taxable = isset($_POST['non_taxable']) ? mysqli_real_escape_string($conn, $_POST['non_taxable']) : null;
    $net_price = isset($_POST['net_price']) ? mysqli_real_escape_string($conn, $_POST['net_price']) : null;
    $hsn_code = isset($_POST['hsn_code']) ? mysqli_real_escape_string($conn, $_POST['hsn_code']) : null;
    $units = isset($_POST['units']) ? mysqli_real_escape_string($conn, $_POST['units']) : null;

    $cess_rate = isset($_POST['cess_rate']) ? mysqli_real_escape_string($conn, $_POST['cess_rate']) : null;
    $cess_amount = isset($_POST['cess_amount']) ? mysqli_real_escape_string($conn, $_POST['cess_amount']) : null;
    $sku = isset($_POST['sku']) ? mysqli_real_escape_string($conn, $_POST['sku']) : null;

    $totalStock = isset($_POST['totalStock']) ? mysqli_real_escape_string($conn, $_POST['totalStock']) : 0; // Default to 0

    $opening_stock = isset($_POST['opening_stock']) ? mysqli_real_escape_string($conn, $_POST['opening_stock']) : 0; 
    $opening_stockdate = isset($_POST['opening_stockdate']) ? mysqli_real_escape_string($conn, $_POST['opening_stockdate']) : null;
    $min_stockalert = isset($_POST['min_stockalert']) ? mysqli_real_escape_string($conn, $_POST['min_stockalert']) : '';
    $max_stockalert = isset($_POST['max_stockalert']) ? mysqli_real_escape_string($conn, $_POST['max_stockalert']) : '';
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    $sac_code = isset($_POST['sac_code']) ? mysqli_real_escape_string($conn, $_POST['sac_code']) : null;

// $can_be_sold = isset($_POST['can_be_sold']) ? mysqli_real_escape_string($conn, $_POST['can_be_sold']) : null;

  $can_be_sold = isset($_POST['can_be_sold']) ? 1 : 0; // Defaults to 0 if not checked
$stock_out = 0;
$Stock_in =0;
$balance_stock = $opening_stock;
    $created_by = $_SESSION['name'];

    // Determine opening_stock logic
    //$opening_stock = $totalStock;

    // SQL Query: Insert or Update opening_stock
    $sql = "INSERT INTO inventory_master 
        (inventory_type, can_be_sold, catlog_type, name, category, company_name, price, in_ex_gst, gst_rate, non_taxable, net_price, hsn_code, units, cess_rate, cess_amt, sku, opening_stock, opening_stockdate, min_stockalert, max_stockalert, Stock_in, stock_out, balance_stock, description, created_by)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE 
opening_stock=?";

// Prepare the statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    error_log("SQL Error: " . $conn->error);
    echo '<script>alert("Database error. Check logs for details.");</script>';
    exit;
}

// Bind parameters (now with 26 values)
$stmt->bind_param(
    "sssssssssssssissssssssssss",  
    $inventory_type, $can_be_sold, $catlog_type, $name, $category, $company_name, $price, 
    $inclusive_gst, $gst_rate, $non_taxable, $net_price, $hsn_code, $units, $cess_rate, 
    $cess_amount, $sku, $opening_stock, $opening_stockdate, $min_stockalert, $max_stockalert, 
    $Stock_in, $stock_out, $balance_stock, $description, $created_by, 
    $opening_stock 
);

// Execute statement and handle response
if ($stmt->execute()) {
    echo '<script>alert("Data inserted successfully."); window.location.href="manage-products.php?type=' . $inventory_type . '";</script>';
} else {
    error_log("SQL Error: " . $stmt->error);
    echo '<script>alert("Error inserting inventory. Check logs for details.");</script>';
}


    // Close statement
    $stmt->close();
}

$conn->close();
?>
