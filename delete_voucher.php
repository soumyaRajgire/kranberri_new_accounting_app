<?php
session_start();
include("config.php");

if (!isset($_SESSION['LOG_IN'])) {
    header("Location: logininv_id.php");
    exit();
}

if (!isset($_GET['inv_id']) || empty($_GET['inv_id'])) {
    echo "<script>alert('Invalid voucher ID'); window.history.back();</script>";
    exit();
}

$voucherId = intval($_GET['inv_id']);

$conn->begin_transaction();

try {
    // 1. Fetch details including file path and invoice ID
    $stmt = $conn->prepare("SELECT pdf_file_path, invoice_id FROM voucher WHERE id = ?");
    $stmt->bind_param("i", $voucherId);
    $stmt->execute();
    $stmt->bind_result($pdfPath, $invoiceId);
    $stmt->fetch();
    $stmt->close();

    // 2. Delete from voucher_reconciliation
    $conn->query("DELETE FROM voucher_reconciliation WHERE voucher_id = $voucherId");

    // 3. Delete from ledger
    $conn->query("DELETE FROM ledger WHERE voucher_id = $voucherId");

    // 4. Delete from voucher
    $conn->query("DELETE FROM voucher WHERE id = $voucherId");

    // 5. Delete the file if it exists
    if (!empty($pdfPath) && file_exists($pdfPath)) {
        unlink($pdfPath);
    }

    // 6. (Optional) Recalculate and update invoice status
    $recalculate = $conn->query("SELECT SUM(paid_amount) AS total_paid FROM voucher WHERE invoice_id = '$invoiceId'");
    $paid = 0;
    if ($row = $recalculate->fetch_assoc()) {
        $paid = $row['total_paid'];
    }

    $invoice = $conn->query("SELECT grand_total FROM pi_invoice WHERE id = '$invoiceId'");
    $grandTotal = 0;
    if ($row = $invoice->fetch_assoc()) {
        $grandTotal = $row['grand_total'];
    }

    $due = $grandTotal - $paid;
    $status = 'pending';
    if ($due <= 0) {
        $status = 'paid';
    } elseif ($due < $grandTotal) {
        $status = 'partial';
    }

    $conn->query("UPDATE pi_invoice SET due_amount = '$due', status = '$status' WHERE id = '$invoiceId'");

    $conn->commit();
    echo "<script>alert('Voucher deleted successfully.'); window.location.href='manage-voucher.php';</script>";
} catch (Exception $e) {
    $conn->rollback();
    echo "<script>alert('Error deleting voucher: " . $e->getMessage() . "'); window.history.back();</script>";
}
?>
