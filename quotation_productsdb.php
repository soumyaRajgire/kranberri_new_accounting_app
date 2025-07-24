<?php
session_start(); 
// Check if the user is logged in
if(!isset($_SESSION['LOG_IN'])){
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if(!isset($_SESSION['business_id'])){
    header("Location:dashboard.php");
    exit();
} else {
 // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
        // Branch-specific code or logic here
    } 
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
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $sac_code = mysqli_real_escape_string($conn, $_POST['sac_code']);
    $created_by = $_SESSION['name'];

    $sql = "INSERT INTO inventory_master (inventory_type, catlog_type, name, price, in_ex_gst, gst_rate, non_taxable, net_price, hsn_code, SAC_Code, units, cess_amt, sku, description, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssss", $inventory_type, $catlog_type, $name, $price, $inclusive_gst, $gst_rate, $non_taxable, $net_price, $hsn_code, $sac_code, $units, $cess_amount, $sku, $description, $created_by);

    if ($stmt->execute()) {
        echo '<script>alert("Data inserted Successfully"); window.location.href="create-estimate.php";</script>';
    } else {
        echo "Error inserting inventory: " . $stmt->error;
    }
    $stmt->close();
}
?>
