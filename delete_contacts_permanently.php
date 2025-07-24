<?php
session_start();
include("config.php");

// Check if the user is logged in
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

// Check if an ID was passed
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete the contact permanently
    $query = "DELETE FROM deleted_contacts WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $file_path = isset($file_path) ? $file_path : '';
        require_once 'includes/insert_audit_log.php';
        insertAuditLog($conn, "Deleted Contact Permanently", $file_path);

        echo "<script>alert('Contact deleted permanently.'); window.location.href='deleted_contacts.php';</script>";
    } else {
        echo "<script>alert('Error deleting contact: " . mysqli_error($conn) . "'); window.location.href='deleted_contacts.php';</script>";
    }
} else {
    echo "<script>alert('Invalid contact ID.'); window.location.href='deleted_contacts.php';</script>";
}
?>
