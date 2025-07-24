<?php
include("config.php");

header('Content-Type: application/json');

$customer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$response = ["payables" => [], "receivables" => [], "debug" => []];

if ($customer_id > 0) {
    // Debug info
    $response["debug"][] = "Processing customer ID: " . $customer_id;

    // Fetch Receivables (Payments received from the customer)
    $receivables_query = "
        SELECT 
            r.id as recpt_id,
            r.receipt_date,
            r.invoice_id,
            r.paid_amount,
            r.payment_mode,
            r.transactionid
        FROM 
            receipts r
        INNER JOIN invoice i ON r.invoice_id = i.id
        WHERE i.customer_id = $customer_id
        ORDER BY r.receipt_date DESC
    ";
    
    $response["debug"][] = "Receivables Query: " . $receivables_query;
    
    $receivables_result = mysqli_query($conn, $receivables_query);

    if ($receivables_result) {
        while ($row = mysqli_fetch_assoc($receivables_result)) {
            $response["receivables"][] = $row;
        }
        $response["debug"][] = "Receivables found: " . count($response["receivables"]);
    } else {
        $response["debug"][] = "Receivables query failed: " . mysqli_error($conn);
    }

    // Fetch Payables (Pending Invoices)
    $payables_query = "
        SELECT 
            id, invoice_code, invoice_date, grand_total, due_amount, status 
        FROM invoice
        WHERE customer_id = $customer_id 
        AND (status = 'pending' OR status = 'partial')
        ORDER BY invoice_date DESC
    ";
    
                        
                        $payables_query="SELECT 
    subquery.customer_id,
    subquery.customer_name,
    subquery.pi_invoice_id,
    subquery.due_date,
    
    SUM(subquery.remaining_due) AS `Total_Payable`
FROM (
    SELECT 
        pi.id AS pi_invoice_id,
        pi.customer_id,
        pi.customer_name,
        pi.due_date,
        pi.grand_total - (
            COALESCE(v.total_paid, 0) + COALESCE(vr.total_reconciled, 0)
        ) AS remaining_due
    FROM 
        pi_invoice pi
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(paid_amount) AS total_paid
        FROM 
            voucher
        GROUP BY 
            invoice_id
    ) v ON pi.id = v.invoice_id
    LEFT JOIN (
        SELECT 
            pi_invoice_id, 
            SUM(reconciled_amount) AS total_reconciled
        FROM 
            voucher_reconciliation
        GROUP BY 
            pi_invoice_id
    ) vr ON pi.id = vr.pi_invoice_id
    WHERE 
        ( pi.status = 'pending' OR pi.status = 'partial')  and branch_id='" . $_SESSION['branch_id'] . "' and customer_id = $customer_id 
    GROUP BY 
        pi.id, pi.customer_id, pi.customer_name, pi.due_date, pi.grand_total
) AS subquery
GROUP BY 
    subquery.customer_id, subquery.customer_name
ORDER BY 
    `Total_Payable` DESC;
";
    
    $response["debug"][] = "Payables Query: " . $payables_query;
    
    $payables_result = mysqli_query($conn, $payables_query);

    if ($payables_result) {
        while ($row = mysqli_fetch_assoc($payables_result)) {
            $response["payables"][] = $row;
        }
        $response["debug"][] = "Payables found: " . count($response["payables"]);
    } else {
        $response["debug"][] = "Payables query failed: " . mysqli_error($conn);
    }
} else {
    $response["debug"][] = "Invalid Customer ID: " . $customer_id;
}

// Add counts to response
$response["counts"] = [
    "receivables" => isset($response["receivables"]) ? count($response["receivables"]) : 0,
    "payables" => isset($response["payables"]) ? count($response["payables"]) : 0
];

// Output JSON response with debugging enabled
echo json_encode($response, JSON_PRETTY_PRINT);
?>