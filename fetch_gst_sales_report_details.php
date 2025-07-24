<?php
include("config.php");

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

// Start SQL Query
$sql = "SELECT 
    i.id AS invoice_id, 
    i.invoice_code, 
    i.invoice_date, 
    i.customer_name, 
    cm.gstin AS customer_gstin,
    i.grand_total, 
    SUM(ii.line_total) AS taxable_amount,
    SUM(ii.cgst) AS total_cgst,
    SUM(ii.sgst) AS total_sgst,
    SUM(ii.igst) AS total_igst,
    SUM(ii.cess_amount) AS total_cess
FROM invoice i
LEFT JOIN invoice_items ii ON i.id = ii.invoice_id
LEFT JOIN customer_master cm ON cm.id = i.customer_id
WHERE i.is_deleted = 0";  // Ensure only active invoices are retrieved

// Apply date filter only if both dates are provided
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND DATE(i.invoice_date) BETWEEN '$from_date' AND '$to_date'";
}

// Group by invoice to avoid duplicate records
$sql .= " GROUP BY i.id ORDER BY i.invoice_date DESC";

$result = mysqli_query($conn, $sql);
$output = '';

if (mysqli_num_rows($result) > 0) {
    $serial_no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>
            <td>{$serial_no}</td>
            <td>" . date("d-m-Y", strtotime($row['invoice_date'])) . "</td>
            <td>" . htmlspecialchars($row['invoice_code']) . "</td>
            <td>" . htmlspecialchars($row['customer_name']) . "</td>
            <td>" . htmlspecialchars($row['customer_gstin']) . "</td>
            <td>" . number_format($row['taxable_amount'], 2) . "</td>
            <td>" . number_format($row['total_cgst'], 2) . "</td>
            <td>" . number_format($row['total_sgst'], 2) . "</td>
            <td>" . number_format($row['total_cess'], 2) . "</td>
            <td>" . number_format($row['total_igst'], 2) . "</td>
            <td>" . number_format($row['grand_total'], 2) . "</td>
        </tr>";
        $serial_no++;
    }
} else {
    $output .= "<tr><td colspan='11' class='text-center'>No data found</td></tr>";
}

echo $output;
?>
