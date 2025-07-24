<?php
include('config.php');
session_start();

if (!isset($_SESSION['name']) || !isset($_SESSION['branch_id'])) {
    die("<script>alert('Session expired. Please log in again.'); window.location.href = 'login.php';</script>");
}

if (isset($_GET['inv_id'])) {
    $inv_id = $_GET['inv_id'];
    $branch_id = $_SESSION['branch_id'];
    $username = $_SESSION['name'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Fetch invoice details before deletion
        $query = "SELECT status, invoice_file FROM pi_invoice WHERE id = ? AND branch_id = ?";
        $stmt_status = $conn->prepare($query);
        if (!$stmt_status) {
            throw new Exception("Error preparing invoice status statement: " . $conn->error);
        }
        $stmt_status->bind_param("ii", $inv_id, $branch_id);
        $stmt_status->execute();
        $result = $stmt_status->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("Invoice not found or belongs to a different branch.");
        }
        $row = $result->fetch_assoc();
        $invoice_status = $row['status'];
        $file_path = $row['invoice_file'];
        $stmt_status->close();

        // Prevent deletion for Partial or Fully Paid invoices
        if ($invoice_status == "partial" || $invoice_status == "paid") {
            throw new Exception("Cannot delete an invoice that is partially or fully paid!");
        }

        // Fetch items from the invoice before deleting
        $fetch_items_query = "SELECT productid, qty, batch_no FROM pi_invoice_items WHERE invoice_id = ?";
        $stmt_fetch_items = $conn->prepare($fetch_items_query);
        $stmt_fetch_items->bind_param("i", $inv_id);
        $stmt_fetch_items->execute();
        $result_items = $stmt_fetch_items->get_result();


  $products_to_restore = [];
        while ($row = $result_items->fetch_assoc()) {
            $products_to_restore[] = $row; // Store productid, qty, and batch_no
        }
      //  $stmt_fetch_items->close();

        // Step 3: Restore inventory and product batches BEFORE deleting invoice items
        foreach ($products_to_restore as $item) {
          echo  $productid = $item['productid'];
            $quantity = $item['qty'];
          echo  "batchno".$batch_no = $item['batch_no'];  // Get batch number

            // Check if the product is in a batch (from product_batches table)
            if (!empty($batch_no)) {
                // Restore stock for product batches
                $update_batch_query = "UPDATE product_batches SET stock_in = stock_in - ?, 
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
                $update_inventory_query = "UPDATE inventory_master SET stock_in = stock_in - ?, 
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
        // Update inventory before deleting invoice items
//         while ($row = $result_items->fetch_assoc()) {
//             $productid = $row['productid'];
//             $quantity = $row['qty'];
//             $batch_no = $row['batch_no'];

//             // Update the inventory for the product
//             $update_inventory_query = "UPDATE inventory_master 
//                                        SET stock_in = stock_in - ?, 
//                                            balance_stock = (opening_stock + stock_in) - stock_out, 
//                                            last_updated_by = ?, 
//                                            last_updated_at = NOW() 
//                                        WHERE id = ?";
//             $stmt_update_inventory = $conn->prepare($update_inventory_query);
//             $stmt_update_inventory->bind_param("isi", $quantity, $username, $productid);
//             if (!$stmt_update_inventory->execute()) {
//                 throw new Exception("Error updating inventory: " . $stmt_update_inventory->error);
//             }
//             $stmt_update_inventory->close();

//             // If batch management is enabled, update the batch details
//             $sqlBatchManagement = "SELECT maintain_batch FROM inventory_master WHERE id = ?";
//             $stmtBatchManagement = $conn->prepare($sqlBatchManagement);
//             $stmtBatchManagement->bind_param("i", $productid);
//             $stmtBatchManagement->execute();
//             $stmtBatchManagement->bind_result($batchManagementEnabled);
//             $stmtBatchManagement->fetch();
//             $stmtBatchManagement->close();

//             if ($batchManagementEnabled) {
//                 // Update the batch data
//               $updateBatchSql = "UPDATE product_batches 
//                    SET stock_in = stock_in - ?, 
//                        balance_stock = (opening_stock + stock_in) - stock_out 
//                    WHERE product_id = ?";
// $stmtBatch = $conn->prepare($updateBatchSql);

// // Bind both parameters (quantity and product_id)
// $stmtBatch->bind_param("ii", $quantity, $productid);

// if (!$stmtBatch->execute()) {
//     throw new Exception("Error updating batch stock: " . $stmtBatch->error);
// }
// $stmtBatch->close();
//             }
//         }
        $stmt_fetch_items->close();

        // Delete stock transaction records from stock_master
        $delete_stock_query = "DELETE FROM stock_master WHERE reference_no = ?";
        $stmt_delete_stock = $conn->prepare($delete_stock_query);
        $stmt_delete_stock->bind_param("s", $inv_id);
        if (!$stmt_delete_stock->execute()) {
            throw new Exception("Error deleting stock transactions: " . $stmt_delete_stock->error);
        }
        $stmt_delete_stock->close();

        // Delete items associated with the invoice
        $delete_items_query = "DELETE FROM pi_invoice_items WHERE invoice_id=?";
        $stmt_delete_items = $conn->prepare($delete_items_query);
        $stmt_delete_items->bind_param("i", $inv_id);
        if (!$stmt_delete_items->execute()) {
            throw new Exception("Error deleting invoice items: " . $stmt_delete_items->error);
        }
        $stmt_delete_items->close();

        // Delete other related records (transportation, additional charges, etc.)
        $delete_transport_query = "DELETE FROM pi_transportation_details WHERE p_invoice_id=?";
        $stmt_delete_transport = $conn->prepare($delete_transport_query);
        $stmt_delete_transport->bind_param("i", $inv_id);
        if (!$stmt_delete_transport->execute()) {
            throw new Exception("Error deleting transportation details: " . $stmt_delete_transport->error);
        }
        $stmt_delete_transport->close();

        $delete_additional_charges_query = "DELETE FROM pi_invoice_additional_charges WHERE invoice_id=?";
        $stmt_delete_additional_charges = $conn->prepare($delete_additional_charges_query);
        $stmt_delete_additional_charges->bind_param("i", $inv_id);
        if (!$stmt_delete_additional_charges->execute()) {
            throw new Exception("Error deleting additional charges: " . $stmt_delete_additional_charges->error);
        }
        $stmt_delete_additional_charges->close();

        $delete_other_details_query = "DELETE FROM pi_invoice_other_details WHERE invoice_id=?";
        $stmt_delete_other_details = $conn->prepare($delete_other_details_query);
        $stmt_delete_other_details->bind_param("i", $inv_id);
        if (!$stmt_delete_other_details->execute()) {
            throw new Exception("Error deleting other details: " . $stmt_delete_other_details->error);
        }
        $stmt_delete_other_details->close();

        // Delete ledger entries related to this invoice
        $delete_ledger_query = "DELETE FROM ledger WHERE voucher_id = ? AND transaction_type = 'Purchase'";
        $stmt_delete_ledger = $conn->prepare($delete_ledger_query);
        $stmt_delete_ledger->bind_param("i", $inv_id);
        if (!$stmt_delete_ledger->execute()) {
            throw new Exception("Error deleting ledger entry: " . $stmt_delete_ledger->error);
        }
        $stmt_delete_ledger->close();

        // Delete the purchase invoice
        $delete_invoice_query = "DELETE FROM pi_invoice WHERE id=? AND branch_id=?";
        $stmt_delete_invoice = $conn->prepare($delete_invoice_query);
        $stmt_delete_invoice->bind_param("ii", $inv_id, $branch_id);
        if (!$stmt_delete_invoice->execute()) {
            throw new Exception("Error deleting invoice: " . $stmt_delete_invoice->error);
        }
        $stmt_delete_invoice->close();

        // Insert audit log after deletion
        require_once 'includes/insert_audit_log.php';
        insertAuditLog($conn, "Deleted Purchase Invoice", $file_path);

        // Commit the transaction
        $conn->commit();

        echo '<script>
                alert("Successfully deleted purchase invoice");
                window.location.href = "view-purchase-invoices.php";
              </script>';
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); history.back();</script>";
    }
} else {
    header("Location: view-purchase-invoices.php");
    exit;
}
?>
