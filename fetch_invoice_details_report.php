<?php
include("config.php");

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

$sql = "SELECT 
    i.id AS invoice_id, 
    i.invoice_code, 
    i.customer_name, 
    i.customer_gstin, 
    i.invoice_date, 
    i.grand_total, 
    ii.product, 
    ii.hsn_code, 
    ii.qty, 
    ii.price, 
    ii.line_total, 
    ii.gst, 
    ii.cgst, 
    ii.sgst, 
    ii.igst, 
    ii.cess_rate, 
    ii.cess_amount,
    im.units,
    cm.gstin AS customer_gstin 
FROM invoice i

LEFT JOIN invoice_items ii ON i.id = ii.invoice_id
LEFT JOIN inventory_master im ON ii.productid = im.id
LEFT JOIN customer_master cm ON cm.id = i.customer_id
WHERE i.is_deleted = 0";

// Apply date filter only if both dates are provided
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND i.invoice_date BETWEEN '$from_date' AND '$to_date'";
}

$sql .= " ORDER BY i.invoice_date DESC";

$result = mysqli_query($conn, $sql);
$output = '';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        $taxable_amount = $row['line_total'] - ($row['cgst'] + $row['sgst'] + $row['igst'] + $row['cess_amount']);

        $output .= "<tr>
            <td>{$row['invoice_code']}</td>
            <td>" . date("d-m-Y", strtotime($row['invoice_date'])) . "</td>
            <td>{$row['customer_name']}</td>
            <td>{$row['customer_gstin']}</td>
            <td>{$row['product']}</td>
            <td>{$row['hsn_code']}</td>
            <td>{$row['qty']} {$row['units']}</td>
            <td>" . number_format($row['price'], 2) . "</td>
            <td>" . number_format($row['line_total'], 2) . "</td>
            <td>{$row['gst']}%</td>
            <td>" . number_format($row['cgst'], 2) . "</td>
            <td>" . number_format($row['sgst'], 2) . "</td>
            <td>" . number_format($row['cess_amount'], 2) . "</td>
            <td>" . number_format($row['igst'], 2) . "</td>
            <td>" . number_format($taxable_amount, 2) . "</td>
            <td>" . number_format($row['grand_total'], 2) . "</td>
        </tr>";
    }
} else {
    $output .= "<tr><td colspan='15' class='text-center'>No data found</td></tr>";
}

echo $output;
?>
