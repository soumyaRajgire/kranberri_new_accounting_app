<?php
header('Content-Type: application/json');
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$conn || $conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch customer details
$customerQuery = "SELECT `business_name`, `entityType`, `mobile` FROM `customer_master` WHERE `id` = ?";
$stmt = $conn->prepare($customerQuery);
$stmt->bind_param('i', $id);
$stmt->execute();
$customerResult = $stmt->get_result();
$customer = $customerResult->fetch_assoc();

// Fetch ledger details
$ledgerQuery = "SELECT `transaction_date`, `receipt_or_voucher_no`, `amount`, `debit_credit` 
                FROM `ledger` WHERE `account_id` = ?";
$stmt = $conn->prepare($ledgerQuery);
$stmt->bind_param('i', $id);
$stmt->execute();
$ledgerResult = $stmt->get_result();

$ledgerData = [];
while ($row = $ledgerResult->fetch_assoc()) {
    $ledgerData[] = [
        'transaction_date' => $row['transaction_date'],
        'receipt_or_voucher_no' => $row['receipt_or_voucher_no'],
        'debit' => $row['debit_credit'] === 'D' ? $row['amount'] : null,
        'credit' => $row['debit_credit'] === 'C' ? $row['amount'] : null,
    ];
}

echo json_encode([
    "customer" => $customer,
    "ledger" => $ledgerData,
]);
exit;
?>
