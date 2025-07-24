<?php
include("config.php");

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

$sql = "SELECT 
    dc.id AS dc_id, 
    dc.dc_code, 
    dc.customer_name, 
    dc.dc_date, 
    dc.total_amount, 
    dc.total_gst, 
    dc.total_cess, 
    dc.grand_total, 
    cm.gstin AS customer_gstin,

    -- Merge multiple product names
    GROUP_CONCAT(DISTINCT dci.product ORDER BY dci.id SEPARATOR ', ') AS product_names,

    -- Merge multiple HSN codes
    GROUP_CONCAT(DISTINCT dci.product_id ORDER BY dci.id SEPARATOR ', ') AS product_ids,

    -- Summing up quantities, tax components
    SUM(dci.qty) AS total_qty,
    SUM(dci.line_total) AS total_sale_amount,
    SUM(dci.cgst) AS total_cgst,
    SUM(dci.sgst) AS total_sgst,
    SUM(dci.igst) AS total_igst,
    SUM(dci.cess_amount) AS total_cess

FROM delivery_challan dc
LEFT JOIN delivery_challan_items dci ON dc.id = dci.dc_id
LEFT JOIN customer_master cm ON cm.id = dc.customer_id
WHERE 1=1";

// Apply date filter only if both dates are provided
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND dc.dc_date BETWEEN '$from_date' AND '$to_date'";
}

$sql .= " GROUP BY dc.id ORDER BY dc.dc_date DESC"; // Ensures latest records are at the top

$result = mysqli_query($conn, $sql);
$output = '';

$serial_no = 1; // Initialize Serial Number

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Calculate Taxable Amount
        $taxable_amount = $row['total_sale_amount'] - ($row['total_cgst'] + $row['total_sgst'] + $row['total_igst'] + $row['total_cess']);

        $output .= "<tr>
            <td>{$serial_no}</td> <!-- Serial Number -->
            <td>" . date("d-m-Y", strtotime($row['dc_date'])) . "</td>
            <td>{$row['dc_code']}</td>
            <td>{$row['customer_name']}</td>
            <td>{$row['customer_gstin']}</td>
            <td>₹" . number_format($taxable_amount, 2) . "</td>
            <td>₹" . number_format($row['total_cgst'], 2) . "</td>
            <td>₹" . number_format($row['total_sgst'], 2) . "</td>
            <td>₹" . number_format($row['total_igst'], 2) . "</td>
            <td>₹" . number_format($row['total_cess'], 2) . "</td>
            <td>₹" . number_format($row['grand_total'], 2) . "</td>
        </tr>";

        $serial_no++; // Increment Serial Number
    }
} else {
    $output .= "<tr><td colspan='11' class='text-center'>No data found</td></tr>";
}

echo $output;
?>
