<?php
include("config.php"); // Include DB connection

$branch_id = $_SESSION['branch_id'] ?? null; // Get branch_id from session or set to null

$sql = "
SELECT 
    cm.customerName AS supplier_name,
    SUM(pi.grand_total - IFNULL(py.amount_paid, 0)) AS balance_amount,
    SUM(IFNULL(py.amount_paid, 0)) AS paid_amount,
    MAX(py.payment_date) AS last_payment_date,
    SUM(pi.gst_itc) AS gst_itc
FROM 
    customer_master cm
JOIN 
    purchase_invoice pi ON cm.id = pi.customer_id
LEFT JOIN 
    payments py ON pi.id = py.invoice_id
WHERE 
    cm.contact_type = 'supplier'
    " . ($branch_id ? "AND pi.branch_id = ?" : "") . "
GROUP BY 
    cm.id, cm.customerName
ORDER BY 
    balance_amount DESC";

$stmt = $conn->prepare($sql);

if ($branch_id) {
    $stmt->bind_param("i", $branch_id); // Bind branch_id if provided
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
