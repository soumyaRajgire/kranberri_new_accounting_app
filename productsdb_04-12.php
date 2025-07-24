<?php
session_start();
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit;
}
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize it
    $catlog_type = mysqli_real_escape_string($conn, $_POST['catlog_type']);
    $inventory_type = mysqli_real_escape_string($conn, $_POST['inventory_type']);
    $name = mysqli_real_escape_string($conn, $_POST['goods_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);

    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $inclusive_gst = mysqli_real_escape_string($conn, $_POST['inclusive_gst']);
    $gst_rate = mysqli_real_escape_string($conn, $_POST['gst_rate']);
    $non_taxable = mysqli_real_escape_string($conn, $_POST['non_taxable']);
    $net_price = mysqli_real_escape_string($conn, $_POST['net_price']);
    $hsn_code = mysqli_real_escape_string($conn, $_POST['hsn_code']);
    $units = mysqli_real_escape_string($conn, $_POST['units']);
    $cess_amount = mysqli_real_escape_string($conn, $_POST['cess_amount']);
    $sku = mysqli_real_escape_string($conn, $_POST['sku']);
    $totalStock = mysqli_real_escape_string($conn, $_POST['totalStock']); // Use totalStock as the new opening_stock
    $created_by = $_SESSION['name'];

    // Additional optional fields
    $opening_stockdate = isset($_POST['opening_stockdate']) ? mysqli_real_escape_string($conn, $_POST['opening_stockdate']) : null;
    $min_stockalert = isset($_POST['min_stockalert']) ? mysqli_real_escape_string($conn, $_POST['min_stockalert']) : '';
    $max_stockalert = isset($_POST['max_stockalert']) ? mysqli_real_escape_string($conn, $_POST['max_stockalert']) : '';
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $sac_code = mysqli_real_escape_string($conn, $_POST['sac_code']);

    // Assign totalStock as the new opening_stock if conditions are met
    if ($inventory_type === 'sales catalog' && $catlog_type === 'products') {
        $opening_stock = $totalStock;
    } else {
        $opening_stock = $totalStock; // Default to using totalStock as opening_stock
    }

    // SQL statement to insert or update opening_stock
    $sql = "INSERT INTO inventory_master (inventory_type, catlog_type, name, category, company_name, price, in_ex_gst, gst_rate, non_taxable, net_price, hsn_code, SAC_Code, units, cess_amt, sku, opening_stock, opening_stockdate, min_stockalert, max_stockalert, description, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE opening_stock=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssssssssss",
        $inventory_type, $catlog_type, $name, $category, $company_name, $price, $inclusive_gst, $gst_rate, $non_taxable, $net_price,
        $hsn_code, $sac_code, $units, $cess_amount, $sku, $opening_stock,
        $opening_stockdate, $min_stockalert, $max_stockalert, $description, $created_by, $opening_stock // Last for update
    );

    if ($stmt->execute()) {
        echo '<script>alert("Data inserted successfully"); window.location.href="manage-products.php?type='.$inventory_type.'";</script>';
    } else {
        echo '<script>alert("Error inserting inventory: ' . addslashes($stmt->error) . '");</script>';
    }

    $stmt->close();
}
?>
