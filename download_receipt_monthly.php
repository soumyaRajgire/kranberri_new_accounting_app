<?php
session_start();
include("config.php");
include("includes/download_handler.php");

if (!isset($_SESSION['LOG_IN']) || !isset($_SESSION['business_id'])) {
    header("Location:login.php");
    exit();
}

$business_id = $_SESSION['business_id'];
$branch_id = $_SESSION['branch_id'] ?? null;

if (isset($_POST['month']) && isset($_POST['year'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    
    $query = "SELECT 
        r.recpt_id,
        r.receipt_date,
        r.customer_id,
        r.invoice_id,
        r.total_amount,
        r.paid_amount,
        r.payment_mode,
        r.bank_name,
        r.transactionid,
        r.reconciliation_status,
        r.created_at
    FROM 
        receipts r
    WHERE 
        r.branch_id = ? " . getDateCondition('r.receipt_date', null, null, $month, $year);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Receipt ID', 'Date', 'Customer ID', 'Invoice ID', 'Total Amount', 'Paid Amount', 'Payment Mode', 'Bank Name', 'Transaction ID', 'Status', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['recpt_id'],
            $row['receipt_date'],
            $row['customer_id'],
            $row['invoice_id'],
            $row['total_amount'],
            $row['paid_amount'],
            $row['payment_mode'],
            $row['bank_name'],
            $row['transactionid'],
            $row['reconciliation_status'],
            $row['created_at']
        ];
    }

    $month_name = date('F', mktime(0, 0, 0, $month, 1));
    $filename = "receipts_{$month_name}_{$year}.csv";
    generateCSV($headers, $data, $filename);
}

header("Location: manage-receipt.php");
exit();
?>
