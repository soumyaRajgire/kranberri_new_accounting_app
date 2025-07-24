<?php
include("config.php");

$from_date = isset($_POST['from_date']) ? mysqli_real_escape_string($conn, $_POST['from_date']) : '';
$to_date = isset($_POST['to_date']) ? mysqli_real_escape_string($conn, $_POST['to_date']) : '';

// Start SQL Query
$sql = "SELECT `id`, `inventory_type`, `can_be_sold`, `catlog_type`, `name`, `category`, 
        `company_name`, `price`, `in_ex_gst`, `gst_rate`, `non_taxable`, `net_price`, 
        `hsn_code`, `SAC_Code`, `units`, `cess_rate`, `cess_amt`, `sku`, `opening_stock`, 
        `opening_stockdate`, `min_stockalert`, `max_stockalert`, `Stock_in`, `stock_out`, 
        `balance_stock`, `description`, `remark`, `created_by`, `created_on`, 
        `last_updated_by`, `last_updated_at` 
        FROM `inventory_master`";

// Apply date filter only if both dates are provided
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " WHERE DATE(created_on) BETWEEN '$from_date' AND '$to_date'";
}

// Group by `id` to avoid duplicate records
$sql .= " GROUP BY id ORDER BY created_on DESC";

$result = mysqli_query($conn, $sql);
$output = '';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $stock_value = $row['opening_stock'] * $row['price'];

        $output .= "<tr>
            <td>" . htmlspecialchars($row['hsn_code']) . "</td>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($stock_value) . "</td>
            <td>" . htmlspecialchars($row['price']) . "</td>
            <td>" . htmlspecialchars($row['net_price']) . "</td>
           <td>" . htmlspecialchars($row['opening_stock']) . " " . htmlspecialchars($row['units']) . "</td>
        </tr>";
    }
} else {
    $output .= "<tr><td colspan='6' class='text-center'>No data found</td></tr>";
}

echo $output;
?>
