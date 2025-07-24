<?php
include("config.php");

if (isset($_POST['start_date']) && isset($_POST['end_date'])) {

     if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    // Custom date range selected
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
} else {
    
$currentYear = date("Y");

// Check if the current month is before April
if (date("m") < 4) {
    // If the current month is before April, the financial year is last year
    $start_date = ($currentYear - 1) . "-04-01";  // Financial year start (April 1 of last year)
    $end_date = $currentYear . "-03-31";           // Financial year end (March 31 of this year)
} else {
    // Otherwise, the current financial year is this year
    $start_date = $currentYear . "-04-01";         // Financial year start (April 1 of this year)
    $end_date = ($currentYear + 1) . "-03-31";     // Financial year end (March 31 of next year)
}

}
    // Query for Receivables based on selected date range
    $sql_receivables = "SELECT SUM(paid_amount) AS total_receivables
                        FROM receipts
                        WHERE receipt_date BETWEEN '$start_date' AND '$end_date'";

    $result_receivables = $conn->query($sql_receivables);
    $receivables = 0;  // Default value for Receivables
    if ($result_receivables->num_rows > 0) {
        $row = $result_receivables->fetch_assoc();
        $receivables = $row['total_receivables'];
        // Check if the value is NULL and assign 0 if it is
        if ($receivables === NULL) {
            $receivables = 0;
        }
    }

    // Query for Payables based on selected date range
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
                         WHERE voucher_date BETWEEN '$start_date' AND '$end_date'
                         GROUP BY invoice_id
                     ) v ON pi.id = v.invoice_id
                     WHERE pi.status IN ('pending', 'partial')";

    $result_payables = $conn->query($sql_payables);
    $payables = 0;  // Default value for Payables
    if ($result_payables->num_rows > 0) {
        $row = $result_payables->fetch_assoc();
        $payables = $row['total_payables'];
        // Check if the value is NULL and assign 0 if it is
        if ($payables === NULL) {
            $payables = 0;
        }
    }

    // Return the results as JSON
    echo json_encode(['payables' => $payables, 'receivables' => $receivables]);
}
?>
