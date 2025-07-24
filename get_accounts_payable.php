<?php
include("config.php"); // Include DB connection

$branch_id = $_SESSION['branch_id'] ?? null; // Get branch_id from session or set to null

$sql = "
SELECT 
    cm.customerName AS supplier_name,
    pi.pinvoice_code AS purchase_invoice,
    pi.grand_total AS total_amount,
    (pi.grand_total - IFNULL(py.amount_paid, 0)) AS payment_due,
    pi.created_on AS created_date,
    pi.created_by AS created_by
FROM 
    purchase_invoice pi
JOIN 
    customer_master cm ON pi.customer_id = cm.id
LEFT JOIN 
    (SELECT invoice_id, SUM(amount_paid) AS amount_paid 
     FROM payments GROUP BY invoice_id) py ON pi.id = py.invoice_id
WHERE 
    cm.contact_type = 'supplier'
    " . ($branch_id ? "AND pi.branch_id = ?" : "") . "
ORDER BY 
    pi.created_on DESC";

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
