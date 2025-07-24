<?php
session_start();

// Check if the user is logged in
if(!isset($_SESSION['LOG_IN'])){
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if(!isset($_SESSION['business_id'])){
    header("Location:dashboard.php");
    exit();
} else {
 // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
        // Branch-specific code or logic here
    } 
}

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
    dci.product AS product_name,
    im.hsn_code AS hsn_code,
    dci.qty AS quantity,
    im.price AS rate_per_unit,
    dci.line_total AS sale_amount,
    dci.gst AS gst_percentage,
    dci.cgst AS cgst,
    dci.sgst AS sgst,
    dci.igst AS igst,
    dci.cess_amount AS cess
FROM delivery_challan dc
LEFT JOIN delivery_challan_items dci ON dc.id = dci.dc_id
LEFT JOIN customer_master cm ON cm.id = dc.customer_id
LEFT JOIN inventory_master im ON im.name = dci.product
WHERE 1=1";

// Apply date filter only if both dates are provided
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND dc.dc_date BETWEEN '$from_date' AND '$to_date'";
}

$sql .= " ORDER BY dc.dc_date DESC"; // Ensures latest records are at the top

$result = mysqli_query($conn, $sql);
$output = '';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Calculate Taxable Amount
        $taxable_amount = $row['sale_amount'] - ($row['cgst'] + $row['sgst'] + $row['igst'] + $row['cess']);

        $output .= "<tr>
            <td>{$row['dc_code']}</td>
            <td>" . date("d-m-Y", strtotime($row['dc_date'])) . "</td>
            <td>{$row['customer_name']}</td>
            <td>{$row['customer_gstin']}</td>
            <td>{$row['product_name']}</td>
            <td>{$row['hsn_code']}</td>
            <td>{$row['quantity']}</td>
            <td>₹" . number_format($row['rate_per_unit'], 2) . "</td>
            <td>₹" . number_format($row['sale_amount'], 2) . "</td>
            <td>{$row['gst_percentage']}%</td>
            <td>₹" . number_format($row['cgst'], 2) . "</td>
            <td>₹" . number_format($row['sgst'], 2) . "</td>
            <td>₹" . number_format($row['cess'], 2) . "</td>
            <td>₹" . number_format($row['igst'], 2) . "</td>
            <td>₹" . number_format($taxable_amount, 2) . "</td>
            <td>₹" . number_format($row['grand_total'], 2) . "</td>
        </tr>";
    }
} else {
    $output .= "<tr><td colspan='16' class='text-center'>No data found</td></tr>";
}

echo $output;
?>