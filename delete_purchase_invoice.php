<?php
include("config.php");
session_start();
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $invoice_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Delete invoice items first
    $delete_items_query = "DELETE FROM purchase_invoice_items WHERE pinvoice_id='$invoice_id'";
    if (mysqli_query($conn, $delete_items_query)) {
        // Then delete the invoice
        $delete_invoice_query = "DELETE FROM purchase_invoice WHERE id='$invoice_id'";
        if (mysqli_query($conn, $delete_invoice_query)) {
            $file_path = isset($file_path) ? $file_path : '';
            require_once 'includes/insert_audit_log.php';
            insertAuditLog($conn, "Deleted Purchase Invoice", $file_path);
            echo "<script>alert('Purchase invoice deleted successfully!'); window.location.href='purchase_invoices.php';</script>";
            
        } else {
            echo "<script>alert('Error deleting purchase invoice!'); window.location.href='purchase_invoices.php';</script>";
        }
    } else {
        echo "<script>alert('Error deleting purchase invoice items!'); window.location.href='purchase_invoices.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='purchase_invoices.php';</script>";
}
?>
