<?php
include("config.php");

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

$sql = "SELECT `customer_id`, `customer_name`, SUM(`grand_total`) AS total_amount 
                                                  FROM `pi_invoice`";

// Apply date filter only if both dates are provided
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " WHERE DATE(invoice_date) BETWEEN '$from_date' AND '$to_date'";
}

$sql .= " GROUP BY `customer_id` ORDER BY invoice_date DESC";

$result = mysqli_query($conn, $sql);
$output = '';

if (mysqli_num_rows($result) > 0) {
    $serial_no = 1;  // Initialize Serial No.
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>
            <td>" . $serial_no . "</td>
            <td><a href='individual_party_wise_purchase_report.php?customerid=" . $row['customer_id'] . "'>" . htmlspecialchars($row['customer_name']) . "</a></td>
            <td>{$row['total_amount']}</td>
           
        </tr>";
        $serial_no++;  // Increment Serial No.
    }
} else {
    $output .= "<tr><td colspan='4' class='text-center'>No data found</td></tr>";
}

echo $output;
?>
