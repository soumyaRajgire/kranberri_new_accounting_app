<?php
session_start();
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit;
}
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catlog_type = mysqli_real_escape_string($conn, $_POST['catlog_type']);
    $inventory_type = mysqli_real_escape_string($conn, $_POST['inventory_type']);
    $name = mysqli_real_escape_string($conn, $_POST['goods_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $inclusive_gst = mysqli_real_escape_string($conn, $_POST['inclusive_gst']);
    $gst_rate = mysqli_real_escape_string($conn, $_POST['gst_rate']);
    $non_taxable = mysqli_real_escape_string($conn, $_POST['non_taxable']);
    $net_price = mysqli_real_escape_string($conn, $_POST['net_price']);
    $hsn_code = mysqli_real_escape_string($conn, $_POST['hsn_code']);
    $units = mysqli_real_escape_string($conn, $_POST['units']);
    $cess_amount = mysqli_real_escape_string($conn, $_POST['cess_amount']);
    $sku = mysqli_real_escape_string($conn, $_POST['sku']);
    $opening_stock = mysqli_real_escape_string($conn, $_POST['opening_stock']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $updatedOpeningStock = mysqli_real_escape_string($conn, $_POST['updatedOpeningStock']);
    $created_by = $_SESSION['name'];

    // Additional variables
    $opening_stockdate = isset($_POST['opening_stockdate']) ? mysqli_real_escape_string($conn, $_POST['opening_stockdate']) : null;
    $min_stockalert = isset($_POST['min_stockalert']) ? mysqli_real_escape_string($conn, $_POST['min_stockalert']) : '';
    $max_stockalert = isset($_POST['max_stockalert']) ? mysqli_real_escape_string($conn, $_POST['max_stockalert']) : '';
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $sac_code = mysqli_real_escape_string($conn, $_POST['sac_code']);

    // Check conditions for sales catalog and products type
    if ($inventory_type === 'sales catalog' && $catlog_type === 'products') {
        $opening_stock = $updatedOpeningStock; // Set opening stock to the updated value
    }

    // Prepare SQL and bind parameters
    $sql = "INSERT INTO inventory_master (inventory_type, catlog_type, name, price, in_ex_gst, gst_rate, non_taxable, net_price, hsn_code, SAC_Code, units, cess_amt, sku, opening_stock, opening_stockdate, min_stockalert, max_stockalert, description, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE opening_stock=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssssssss",
        $inventory_type, $catlog_type, $name, $price, $inclusive_gst, $gst_rate, $non_taxable, $net_price,
        $hsn_code, $sac_code, $units, $cess_amount, $sku, $opening_stock,
        $opening_stockdate, $min_stockalert, $max_stockalert, $description, $created_by, $opening_stock // Last is for update
    );

    if ($stmt->execute()) {
        echo '<script>alert("Data inserted Successfully"); window.location.href="manage-products.php?type='.$inventory_type.'";</script>';
    } else {
        echo '<script>alert("Error inserting inventory: ' . addslashes($stmt->error) . '");</script>';
    }

    $stmt->close();
}
?>
