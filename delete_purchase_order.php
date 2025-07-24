<?php
include("config.php");
session_start();
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Delete order items first
    $delete_items_query = "DELETE FROM purchase_order_items WHERE order_id='$order_id'";
    if (mysqli_query($conn, $delete_items_query)) {
        // Then delete the order
        $delete_order_query = "DELETE FROM purchase_order WHERE id='$order_id'";
        if (mysqli_query($conn, $delete_order_query)) {
                    $file_path = isset($file_path) ? $file_path : '';
                       require_once 'includes/insert_audit_log.php';
                insertAuditLog($conn, "Deleted Purchase Order", $file_path);
            echo "<script>alert('Purchase order deleted successfully!'); window.location.href='purchase_invoices.php';</script>";
         
        } else {
            echo "<script>alert('Error deleting purchase order!'); window.location.href='purchase_invoices.php';</script>";
        }
    } else {
        echo "<script>alert('Error deleting purchase order items!'); window.location.href='purchase_invoices.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='purchase_invoices.php';</script>";
}
?>
