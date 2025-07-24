<?php
session_start();
include("config.php");

if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['inv_id']) || empty($_GET['inv_id'])) {
    echo "<script>alert('Invalid receipt ID'); window.history.back();</script>";
    exit();
}

$receiptId = intval($_GET['inv_id']);

$conn->begin_transaction();

try {
    // 1. Fetch PDF path, invoice_id, amount
    $stmt = $conn->prepare("SELECT pdf_file_path, invoice_id, paid_amount FROM receipts WHERE id = ?");
    $stmt->bind_param("i", $receiptId);
    $stmt->execute();
    $stmt->bind_result($pdfPath, $invoiceId, $paidAmount);
    $stmt->fetch();
    $stmt->close();

    // 2. Delete related reconciliation
    $conn->query("DELETE FROM reconciliation WHERE receipt_id = $receiptId");

    // 3. Delete from ledger
    $conn->query("DELETE FROM ledger WHERE voucher_id = $receiptId AND transaction_type = 'Receipt'");

    // 4. Delete the receipt
    $conn->query("DELETE FROM receipts WHERE id = $receiptId");

    // 5. Delete the PDF if it exists
    if (!empty($pdfPath) && file_exists($pdfPath)) {
        unlink($pdfPath);
    }

    // 6. Recalculate the invoice due amount and status
    $paid = 0;
    $res1 = $conn->query("SELECT SUM(paid_amount) AS total_paid FROM receipts WHERE invoice_id = '$invoiceId'");
    if ($row = $res1->fetch_assoc()) {
        $paid = $row['total_paid'];
    }

    $invoiceRes = $conn->query("SELECT grand_total FROM invoice WHERE id = '$invoiceId'");
    $grandTotal = 0;
    if ($row = $invoiceRes->fetch_assoc()) {
        $grandTotal = $row['grand_total'];
    }

    $due = $grandTotal - $paid;
    $status = 'pending';
    if ($due <= 0) {
        $status = 'paid';
    } elseif ($due < $grandTotal) {
        $status = 'partial';
    }

    $conn->query("UPDATE invoice SET due_amount = '$due', status = '$status' WHERE id = '$invoiceId'");

    $conn->commit();

    echo "<script>alert('Receipt deleted successfully.'); window.location.href = 'manage-receipt.php';</script>";
} catch (Exception $e) {
    $conn->rollback();
    echo "<script>alert('Error deleting receipt: " . $e->getMessage() . "'); window.history.back();</script>";
}
?>
