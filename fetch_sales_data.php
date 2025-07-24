<?php
include("config.php");


    if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
} else {
    // Calculate the start and end dates for the current financial year
    $currentYear = date("Y");
    if (date("m") < 4) {
        $startDate = ($currentYear - 1) . "-04-01";
        $endDate = $currentYear . "-03-31";
    } else {
        $startDate = $currentYear . "-04-01";
        $endDate = ($currentYear + 1) . "-03-31";
    }
}

$branch_id = $_POST['branch_id'];
// SQL to get Total Sales, Paid Amount, Receivables, and Overdue
$sql_sales = "
SELECT 
    SUM(i.grand_total) AS total_sales,  -- Total sales (sum of all grand totals from invoices)
    COALESCE(SUM(r.paid_amount), 0) AS total_paid,  -- Total paid (sum of all payments from receipts)
    COALESCE(SUM(cn.total_amount), 0) AS total_credit_notes, -- Total credit notes (adjustments)
    SUM(i.grand_total) - COALESCE(SUM(r.paid_amount), 0) - COALESCE(SUM(cn.total_amount), 0) AS receivables,  -- Receivables (remaining amount after payments and credit notes)
    SUM(CASE WHEN i.due_date < CURDATE() AND i.grand_total - COALESCE(r.paid_amount, 0) > 0 THEN 1 ELSE 0 END) AS overdue_count  -- Count of overdue invoices
FROM 
    invoice i
LEFT JOIN 
    receipts r ON i.id = r.invoice_id
LEFT JOIN 
    credit_note cn ON i.id = cn.invoice_id
WHERE 
    i.status IN ('pending', 'partial')
    AND i.branch_id = '$branch_id'
    AND i.invoice_date BETWEEN '$startDate' AND '$endDate'
GROUP BY 
    i.customer_id";

$result_sales = $conn->query($sql_sales);
$sales_data = $result_sales->fetch_assoc();

$total_sales = $sales_data['total_sales'];
$total_paid = $sales_data['total_paid'];
$receivables = $sales_data['receivables'];
$overdue_count = $sales_data['overdue_count'];

// Output the sales data
echo json_encode([
    'total_sales' => $total_sales,
    'total_paid' => $total_paid,
    'receivables' => $receivables,
    'overdue_count' => $overdue_count
]);
?>
