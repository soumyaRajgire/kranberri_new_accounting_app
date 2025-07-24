<?php
include('config.php');
session_start();

if (isset($_GET['qid'])) {
    $qid = mysqli_real_escape_string($conn, $_GET['qid']);

    // Delete the estimate from the database
    $delete_estimate_query = "DELETE FROM quotation WHERE id = ?";
    $stmt = $conn->prepare($delete_estimate_query);
    $stmt->bind_param("i", $qid);

    if ($stmt->execute()) {
        // Delete related items from quotation_items table
        $delete_items_query = "DELETE FROM quotation_items WHERE quotation_id = ?";
        $stmt_items = $conn->prepare($delete_items_query);
        $stmt_items->bind_param("i", $qid);
        $stmt_items->execute();
        $file_path = isset($file_path) ? $file_path : '';
        require_once 'includes/insert_audit_log.php';
        insertAuditLog($conn, "Deleted Estimate", $file_path);
        echo '<script>alert("Estimate deleted successfully");</script>';
        echo '<script>window.location.href = "view-quotation.php";</script>';
        
    } else {
        echo '<script>alert("Error deleting estimate: ' . $conn->error . '");</script>';
    }
    
    $stmt->close();
    $stmt_items->close();
    $conn->close();
} else {
    echo '<script>alert("No estimate ID provided");</script>';
    echo '<script>window.location.href = "view-quotation.php";</script>';
}
?>
