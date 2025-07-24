<?php
session_start();
include("config.php");

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

$voucherId = isset($_GET['voucherId']) ? intval($_GET['voucherId']) : 0;

if ($voucherId == 0) {
    echo "Invalid voucher ID.";
    exit();
}

$query = "DELETE FROM salary_payments WHERE id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("i", $voucherId);

if ($stmt->execute()) {
            $file_path = isset($file_path) ? $file_path : '';
    require_once 'includes/insert_audit_log.php';
        insertAuditLog($conn, "Deleted Salary Payment", $file_path);
    echo "<script>alert('Salary payment deleted successfully!'); window.location.href='purchase_invoices.php';</script>";
} else {
    echo "Error deleting record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
