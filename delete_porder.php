<?php
include('config.php');
session_start();

if (isset($_GET['inv_id'])) {
    $inv_id = $_GET['inv_id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete items associated with the invoice
        $delete_items_query = "DELETE FROM po_items WHERE invoice_id=?";
        $stmt_delete_items = $conn->prepare($delete_items_query);
        if (!$stmt_delete_items) {
            throw new Exception("Error preparing delete items statement: " . $conn->error);
        }
        $stmt_delete_items->bind_param("i", $inv_id);
        if (!$stmt_delete_items->execute()) {
            throw new Exception("Error deleting invoice items: " . $stmt_delete_items->error);
        }
        $stmt_delete_items->close();

        // Delete the invoice
        $delete_invoice_query = "DELETE FROM purchase_orders WHERE id=?";
        $stmt_delete_invoice = $conn->prepare($delete_invoice_query);
        if (!$stmt_delete_invoice) {
            throw new Exception("Error preparing delete invoice statement: " . $conn->error);
        }
        $stmt_delete_invoice->bind_param("i", $inv_id);
        if (!$stmt_delete_invoice->execute()) {
            throw new Exception("Error deleting invoice: " . $stmt_delete_invoice->error);
        }
        $stmt_delete_invoice->close();
$file_path = isset($file_path) ? $file_path : '';
        require_once 'includes/insert_audit_log.php';
        insertAuditLog($conn, "Deleted Purchase Order", $file_path);

        // Commit the transaction
        $conn->commit();

        // Redirect after successful deletion
        echo '<script>alert("Successfully deleted purchase order");';
        echo 'window.location.href = "view-purchase-order.php";</script>';
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
} else {
    // Redirect if inv_id is not set
    header("Location: view-purchase-order.php");
    exit;
}
?>
