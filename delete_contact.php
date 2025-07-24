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

if(isset($_GET['ctmr_id'])){
    $id = $_GET['ctmr_id'];

    // Prepare a statement to delete the contact from customer_master
    $stmt = $conn->prepare("DELETE FROM customer_master WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Prepare a statement to delete the associated address from address_master
    $stmt_address = $conn->prepare("DELETE FROM address_master WHERE customer_master_id = ?");
    $stmt_address->bind_param("i", $id);
    $stmt_address->execute();
$file_path = isset($file_path) ? $file_path : '';
        
    require_once 'includes/insert_audit_log.php';
        insertAuditLog($conn, "Deleted Contact", $file_path);

    if ($stmt->affected_rows > 0 && $stmt_address->affected_rows > 0) {
        $_SESSION['message'] = "Contact deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to delete contact.";
        $_SESSION['message_type'] = "error";
    }

    $stmt->close();
    $stmt_address->close();
    $conn->close();

    header("Location: customers.php"); // Redirect to the customer list page or appropriate page
    exit();
} else {
    $_SESSION['message'] = "Invalid contact ID.";
    $_SESSION['message_type'] = "error";
    header("Location: customers.php"); // Redirect to the customer list page or appropriate page
    exit();
}
?>
