<?php

session_start();  // ✅ Ensure session is started

include("config.php");

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
$branch_id = $_SESSION['branch_id']; // ✅ Fetch branch_id from session

// Base SQL query with branch_id filter
$sql = "SELECT `customer_id`, `customer_name`, SUM(`grand_total`) AS total_amount 
        FROM `invoice` i
        WHERE i.branch_id = ?";

// Apply date filter only if both dates are provided
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND DATE(i.invoice_date) BETWEEN ? AND ?";
}

// Group by customer_id and order by invoice_date
$sql .= " GROUP BY i.customer_id ORDER BY i.invoice_date DESC";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query Error: " . mysqli_error($conn));  // ✅ Catch SQL errors
}

// Bind parameters based on the presence of dates
if (!empty($from_date) && !empty($to_date)) {
    $stmt->bind_param("sss", $branch_id, $from_date, $to_date); // Binding for branch_id, from_date, and to_date
} else {
    $stmt->bind_param("s", $branch_id);  // Binding only for branch_id if no dates are provided
}

$stmt->execute();
$result = $stmt->get_result();
$output = '';

if (mysqli_num_rows($result) > 0) {
    $serial_no = 1;  // Initialize Serial No.
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>
            <td>" . $serial_no . "</td>
            <td><a href='individual_party_wise_sales_report.php?customerid=" . $row['customer_id'] . "'>" . htmlspecialchars($row['customer_name']) . "</a></td>
            <td>" . number_format($row['total_amount'], 2) . "</td>
        </tr>";
        $serial_no++;  // Increment Serial No.
    }
} else {
    $output .= "<tr><td colspan='3' class='text-center'>No data found</td></tr>";
}

echo $output;

$stmt->close();
?>
