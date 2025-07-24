<?php
include('config.php');
session_start();

if (isset($_GET['bill_id'])) {
    $bill_id = intval($_GET['bill_id']); // Ensure it's an integer

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Fetch invoice status before deletion
        $query = "SELECT status FROM bill_of_supply WHERE id = ?";
        $stmt_status = $conn->prepare($query);
        if (!$stmt_status) {
            throw new Exception("Error preparing invoice status statement: " . $conn->error);
        }
        $stmt_status->bind_param("i", $bill_id);
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
        $fetch_items_query = "SELECT product_id, qty FROM billsupply_items WHERE bill_id = ?";
        $stmt_fetch_items = $conn->prepare($fetch_items_query);
        $stmt_fetch_items->bind_param("i", $bill_id);
        $stmt_fetch_items->execute();
        $result_items = $stmt_fetch_items->get_result();

        // Store all product quantities in an array before deletion
        $products_to_restore = [];
        while ($row = $result_items->fetch_assoc()) {
            $products_to_restore[] = $row; // Store productid and qty
        }
        $stmt_fetch_items->close();

        // Step 3: Restore inventory BEFORE deleting invoice items
        foreach ($products_to_restore as $item) {
            $productid = $item['productid'];
            $quantity = $item['qty'];

            // ✅ Restore Stock Out (Subtract the quantity from stock_out)
            $update_inventory_query = "UPDATE inventory_master SET stock_out = stock_out - ?, balance_stock = (opening_stock + stock_in) - stock_out, last_updated_by = ?, last_updated_at = NOW()  WHERE id = ?";

            $stmt_update_inventory = $conn->prepare($update_inventory_query);
            $stmt_update_inventory->bind_param("isi", $quantity, $_SESSION['name'], $productid);
            if (!$stmt_update_inventory->execute()) {
                throw new Exception("Error updating inventory: " . $stmt_update_inventory->error);
            }
            $stmt_update_inventory->close();
        }

        // Step 4: Delete stock transactions from stock_master
        $delete_stock_query = "DELETE FROM stock_master WHERE reference_no = ?";
        $stmt_delete_stock = $conn->prepare($delete_stock_query);
        $stmt_delete_stock->bind_param("i", $bill_id);
        if (!$stmt_delete_stock->execute()) {
            throw new Exception("Error deleting stock transactions: " . $stmt_delete_stock->error);
        }
        $stmt_delete_stock->close();

        // Step 5: Delete all related invoice data
        $delete_invoice_items = "DELETE FROM billsupply_items WHERE bill_id=?";
        $delete_invoice_other_details = "DELETE FROM  billsupply_other_details WHERE bill_id=?";
        $delete_invoice_additional_charges = "DELETE FROM billsupply_additional_charges WHERE bill_id=?";
        $delete_transportation_details = "DELETE FROM billsupply_transport_details WHERE bill_id=?";

        $stmt1 = $conn->prepare($delete_invoice_items);
        $stmt2 = $conn->prepare($delete_invoice_other_details);
        $stmt3 = $conn->prepare($delete_invoice_additional_charges);
        $stmt4 = $conn->prepare($delete_transportation_details);

        foreach ([$stmt1, $stmt2, $stmt3, $stmt4] as $stmt) {
            $stmt->bind_param("i", $bill_id);
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
        $delete_invoice_query = "DELETE FROM bill_of_supply WHERE id=?";
        $stmt_delete_invoice = $conn->prepare($delete_invoice_query);
        $stmt_delete_invoice->bind_param("i", $bill_id);
        if (!$stmt_delete_invoice->execute()) {
            throw new Exception("Error deleting invoice: " . $stmt_delete_invoice->error);
        }
        $stmt_delete_invoice->close();
$file_path = isset($file_path) ? $file_path : '';
        require_once 'includes/insert_audit_log.php';
        insertAuditLog($conn, "Deleted Sales Invoice", $file_path);

        // Commit the transaction
        $conn->commit();

        echo '<script>alert("Successfully deleted invoice and restored stock.");';
        echo 'window.location.href = "manage-billsupply.php";</script>';
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Invoice Deletion Error: " . $e->getMessage()); // Log the error for debugging
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: manage-billsupply.php");
    exit;
}
?>
