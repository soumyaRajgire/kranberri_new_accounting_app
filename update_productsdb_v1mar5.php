<?php
session_start();  
// Check if the user is logged in
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit();
}

// Check if a business is selected
if (!isset($_SESSION['business_id'])) {
    header("Location:dashboard.php");
    exit();
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
    }
}

include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $id = mysqli_real_escape_string($conn, $_POST['id']);
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
    $sac_code = mysqli_real_escape_string($conn, $_POST['sac_code']);
    $units = mysqli_real_escape_string($conn, $_POST['units']);
    $cess_rate = mysqli_real_escape_string($conn, $_POST['cess_rate']); // Added missing cess_rate
    $cess_amount = mysqli_real_escape_string($conn, $_POST['cess_amount']);
    $sku = mysqli_real_escape_string($conn, $_POST['sku']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $opening_stock = mysqli_real_escape_string($conn, $_POST['opening_stock']);
    $opening_stockdate = mysqli_real_escape_string($conn, $_POST['opening_stockdate']);
    $min_stockalert = mysqli_real_escape_string($conn, $_POST['min_stockalert']);
    $max_stockalert = mysqli_real_escape_string($conn, $_POST['max_stockalert']);
    $can_be_sold = isset($_POST['can_be_sold']) ? 1 : 0; // Checkbox handling
    $created_by = $_SESSION['name'];
// Calculate Stock Values
    $stock_out = 0;
    $Stock_in = 0;
    $balance_stock = $opening_stock; // Keeping balance stock equal to opening stock
    
    // Prepare the UPDATE statement
    $update_sql = "UPDATE inventory_master SET
        inventory_type = ?, catlog_type = ?, name = ?, category = ?, company_name = ?, 
        price = ?, in_ex_gst = ?, gst_rate = ?, non_taxable = ?, net_price = ?, 
        hsn_code = ?, SAC_Code = ?, units = ?, cess_rate = ?, cess_amt = ?, 
        sku = ?, description = ?, opening_stock = ?, opening_stockdate = ?, 
        min_stockalert = ?, max_stockalert = ?, Stock_in = ?, stock_out = ?, 
        balance_stock = ?, can_be_sold = ?
        WHERE id = ?";

    // Prepare the statement
    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
        die("SQL Prepare Error: " . $conn->error);
    }

    // Bind parameters (matching placeholders)
    $update_stmt->bind_param(
        "sssssdidsdsssddssssssssssi",  
        $inventory_type, $catlog_type, $name, $category, $company_name, 
        $price, $inclusive_gst, $gst_rate, $non_taxable, $net_price, 
        $hsn_code, $sac_code, $units, $cess_rate, $cess_amount, 
        $sku, $description, $opening_stock, $opening_stockdate, 
        $min_stockalert, $max_stockalert, $Stock_in, $stock_out, 
        $balance_stock, $can_be_sold, $id
    );

    // Execute the query
    if ($update_stmt->execute()) {
        echo '<script>alert("Data updated Successfully"); window.location.href="manage-products.php?type='.$inventory_type.'";</script>';
    } else {
        echo "Error updating inventory: " . $update_stmt->error;
    }

    // Close statement and connection
    $update_stmt->close();
    $conn->close();
}
?>
