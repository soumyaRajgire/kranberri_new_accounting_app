<?php
require_once 'config.php';
session_start();

// Initialize result message variable
$resultMessage = "";

// Check if the 'inv_id' parameter is set in the URL
if (isset($_GET['inv_id'])) {
    $inv_id = intval($_GET['inv_id']); // Ensure it's an integer
// echo $inv_id;
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Fetch invoice status before deletion
        $query = "SELECT status FROM invoice WHERE id = ?";
        $stmt_status = $conn->prepare($query);
        $stmt_status->bind_param("i", $inv_id);
        $stmt_status->execute();
        $result = $stmt_status->get_result();
        $row = $result->fetch_assoc();
        $invoice_status = $row['status'];
        $stmt_status->close();

        // Prevent deletion for Paid invoices
        if ($invoice_status == "partial" || $invoice_status == "paid") {
            die("<script>alert('Cannot delete an invoice that is partially or fully paid!'); history.back();</script>");
        }

        // Step 2: Fetch products & quantities before deletion
        $fetch_items_query = "SELECT productid, qty, batch_no FROM invoice_items WHERE invoice_id = ?";
        $stmt_fetch_items = $conn->prepare($fetch_items_query);
        $stmt_fetch_items->bind_param("i", $inv_id);
        $stmt_fetch_items->execute();
        $result_items = $stmt_fetch_items->get_result();

        // Store all product quantities in an array before deletion
        $products_to_restore = [];
        while ($row = $result_items->fetch_assoc()) {
            $products_to_restore[] = $row; // Store productid, qty, and batch_no
        }
        $stmt_fetch_items->close();

        // Step 3: Restore inventory and product batches BEFORE deleting invoice items
        foreach ($products_to_restore as $item) {
          echo  $productid = $item['productid'];
            $quantity = $item['qty'];
          echo  "batchno".$batch_no = $item['batch_no'];  // Get batch number

            // Check if the product is in a batch (from product_batches table)
            if (!empty($batch_no)) {
                // Restore stock for product batches
                $update_batch_query = "UPDATE product_batches 
                                       SET stock_out = stock_out - ?, 
                                           balance_stock = (opening_stock + stock_in) - stock_out 
                                       WHERE product_id = ? AND id = ?";
                $stmt_update_batch = $conn->prepare($update_batch_query);
                $stmt_update_batch->bind_param("iis", $quantity, $productid, $batch_no);
                if (!$stmt_update_batch->execute()) {
                    throw new Exception("Error updating product batch stock: " . $stmt_update_batch->error);
                }
                $stmt_update_batch->close();
            } else {
                // If no batch exists, update the inventory_master table (without batch)
                $update_inventory_query = "UPDATE inventory_master 
                                           SET stock_out = stock_out - ?, 
                                               balance_stock = (opening_stock + stock_in) - stock_out 
                                           WHERE id = ?";
                $stmt_update_inventory = $conn->prepare($update_inventory_query);
                $stmt_update_inventory->bind_param("ii", $quantity, $productid);
                if (!$stmt_update_inventory->execute()) {
                    throw new Exception("Error updating inventory: " . $stmt_update_inventory->error);
                }
                $stmt_update_inventory->close();
            }
        }

        // Step 4: Delete stock transactions from stock_master
        $delete_stock_query = "DELETE FROM stock_master WHERE reference_no = ?";
        $stmt_delete_stock = $conn->prepare($delete_stock_query);
        $stmt_delete_stock->bind_param("i", $inv_id);
        if (!$stmt_delete_stock->execute()) {
            throw new Exception("Error deleting stock transactions: " . $stmt_delete_stock->error);
        }
        $stmt_delete_stock->close();

        // Step 5: Delete all related invoice data
        $delete_invoice_items = "DELETE FROM invoice_items WHERE invoice_id=?";
        $delete_invoice_other_details = "DELETE FROM invoice_other_details WHERE invoice_id=?";
        $delete_invoice_additional_charges = "DELETE FROM invoice_additional_charges WHERE invoice_id=?";
        $delete_transportation_details = "DELETE FROM transportation_details WHERE invoice_id=?";

        $stmt1 = $conn->prepare($delete_invoice_items);
        $stmt2 = $conn->prepare($delete_invoice_other_details);
        $stmt3 = $conn->prepare($delete_invoice_additional_charges);
        $stmt4 = $conn->prepare($delete_transportation_details);

        foreach ([$stmt1, $stmt2, $stmt3, $stmt4] as $stmt) {
            $stmt->bind_param("i", $inv_id);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting related invoice data: " . $stmt->error);
            }
            $stmt->close();
        }

        // Step 6: Delete ledger entries related to this invoice
        $delete_ledger_query = "DELETE FROM ledger WHERE voucher_id = ? AND transaction_type = 'Sale'";
        $stmt_delete_ledger = $conn->prepare($delete_ledger_query);
        $stmt_delete_ledger->bind_param("i", $inv_id);
        if (!$stmt_delete_ledger->execute()) {
            throw new Exception("Error deleting ledger entry: " . $stmt_delete_ledger->error);
        }
        $stmt_delete_ledger->close();

        // Step 7: Delete the invoice
        $delete_invoice_query = "DELETE FROM invoice WHERE id=?";
        $stmt_delete_invoice = $conn->prepare($delete_invoice_query);
        $stmt_delete_invoice->bind_param("i", $inv_id);
        if (!$stmt_delete_invoice->execute()) {
            throw new Exception("Error deleting invoice: " . $stmt_delete_invoice->error);
        }
        $stmt_delete_invoice->close();

        // Log the deletion in audit log
        $file_path = isset($file_path) ? $file_path : '';
        require_once 'includes/insert_audit_log.php';
        insertAuditLog($conn, "Deleted Sales Invoice", $file_path);

        // Commit the transaction
        $conn->commit();

        echo '<script>alert("Successfully deleted invoice and restored stock.");';
       echo 'window.location.href = "view-invoices.php";</script>';
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Invoice Deletion Error: " . $e->getMessage()); // Log the error for debugging
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: view-invoices.php");
    exit;
}
?>
