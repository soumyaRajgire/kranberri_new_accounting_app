<?php
include("config.php");

header('Content-Type: application/json');

$customer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$response = ["payables" => [], "receivables" => [], "debug" => []];

if ($customer_id > 0) {
    // Fetch Payables (Invoices with Due Amounts)
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
    $payables_result = mysqli_query($conn, $payables_query);

    if ($payables_result) {
        while ($row = mysqli_fetch_assoc($payables_result)) {
            $response["payables"][] = $row;
        }
    } else {
        $response["debug"][] = "Payables query failed: " . mysqli_error($conn);
    }

    
    $receivables_query="SELECT 
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
    $receivables_result = mysqli_query($conn, $receivables_query);

    if ($receivables_result) {
        while ($row = mysqli_fetch_assoc($receivables_result)) {
            $response["receivables"][] = $row;
        }
    } else {
        $response["debug"][] = "Receivables query failed: " . mysqli_error($conn);
    }
} else {
    $response["debug"][] = "Invalid Customer ID: " . $customer_id;
}

// Output JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
?>
