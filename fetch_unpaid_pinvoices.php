<?php
include 'config.php'; // Include database connection

$customer_id = $_GET['customer_id'];

// Fetch unpaid invoices for the customer and calculate the due amount dynamically
$query = "
    SELECT 
        i.id AS invoice_id,
        i.invoice_date,
        i.invoice_code,
        i.grand_total AS total_amount,
        COALESCE(SUM(r.paid_amount), 0) AS total_paid,
        (i.grand_total - COALESCE(SUM(r.paid_amount), 0)) AS due_amount
    FROM 
        pi_invoice i
    LEFT JOIN 
        voucher r ON i.id = r.invoice_id
    WHERE 
        i.customer_id = $customer_id
    GROUP BY 
        i.id
    HAVING 
        due_amount > 0
";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['invoice_date']}</td>
                <td>{$row['invoice_code']}</td>
                <td>INR " . number_format($row['total_amount'], 2) . "</td>
                <td>INR " . number_format($row['due_amount'], 2) . "</td>
                <td><input type='number' class='form-control' name='reconcile_amount[{$row['invoice_id']}]' min='0' max='{$row['due_amount']}' step='0.01'></td>
                <td>Unpaid</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No unpaid invoices found for this customer.</td></tr>";
}

$conn->close();
?>
