<?php
include("config.php");

if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
} else {
    // Calculate the start and end dates for the current financial year
    $currentYear = date("Y");
    if (date("m") < 4) {
        $start_date = ($currentYear - 1) . "-04-01";
        $end_date = $currentYear . "-03-31";
    } else {
        $start_date = $currentYear . "-04-01";
        $end_date = ($currentYear + 1) . "-03-31";
    }
}

// Fetch Purchase Orders
$sql_purchase_orders = "SELECT COUNT(*) AS total_purchase_orders 
                        FROM purchase_orders 
                        WHERE invoice_date BETWEEN '$start_date' AND '$end_date'";

$result_purchase_orders = $conn->query($sql_purchase_orders);
$purchase_orders = 0;
if ($result_purchase_orders->num_rows > 0) {
    $row = $result_purchase_orders->fetch_assoc();
    $purchase_orders = $row['total_purchase_orders'];
}

// Fetch Purchases
$sql_purchases = "SELECT SUM(grand_total) AS total_purchases 
                  FROM pi_invoice 
                  WHERE invoice_date BETWEEN '$start_date' AND '$end_date' 
                  AND is_deleted = 0";

$result_purchases = $conn->query($sql_purchases);
$purchases = 0;
if ($result_purchases->num_rows > 0) {
    $row = $result_purchases->fetch_assoc();
    $purchases = $row['total_purchases'];
}

// Fetch Total Payments (via vouchers)
$sql_total_payments = "SELECT SUM(paid_amount) AS total_payments 
                       FROM voucher 
                       WHERE transaction_date BETWEEN '$start_date' AND '$end_date'";

$result_total_payments = $conn->query($sql_total_payments);
$total_payments = 0;
if ($result_total_payments->num_rows > 0) {
    $row = $result_total_payments->fetch_assoc();
    $total_payments = $row['total_payments'];
}

// Fetch Payables
$sql_payables = "SELECT SUM(pi.grand_total - COALESCE(dn.total_adjusted, 0) - COALESCE(v.total_paid, 0)) AS total_payables
                 FROM pi_invoice pi
                 LEFT JOIN (
                     SELECT purchase_invoice_id, SUM(total_amount) AS total_adjusted
                     FROM debit_note
                     WHERE dnote_date BETWEEN '$start_date' AND '$end_date'
                     GROUP BY purchase_invoice_id
                 ) dn ON pi.id = dn.purchase_invoice_id
                 LEFT JOIN (
                     SELECT invoice_id, SUM(paid_amount) AS total_paid
                     FROM voucher
                     WHERE transaction_date BETWEEN '$start_date' AND '$end_date'
                     GROUP BY invoice_id
                 ) v ON pi.id = v.invoice_id
                 WHERE pi.status IN ('pending', 'partial') AND pi.is_deleted = 0";

$result_payables = $conn->query($sql_payables);
$payables = 0;
if ($result_payables->num_rows > 0) {
    $row = $result_payables->fetch_assoc();
    $payables = $row['total_payables'];
}

// Return the results as JSON
echo json_encode(['purchase_orders' => $purchase_orders, 
                  'purchases' => $purchases, 
                  'total_payments' => $total_payments, 
                  'payables' => $payables]);
}
?>
