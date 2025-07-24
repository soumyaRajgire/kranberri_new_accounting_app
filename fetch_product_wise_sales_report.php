<?php
session_start();  // ✅ Ensure session is started

include("config.php");

// ✅ Ensure branch_id exists in session
if (!isset($_SESSION['branch_id'])) {
    die("Branch ID not set. Please log in again.");
}

$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
$branch_id = $_SESSION['branch_id']; // ✅ Fetch branch_id from session

// ✅ Updated SQL query with JOIN
$sql = "SELECT ii.productid, ii.product, 
               SUM(ii.qty) AS total_qty, 
               SUM(ii.qty * ii.price) AS total_amount
        FROM `invoice_items` ii
        JOIN `invoice` i ON i.id = ii.invoice_id
        WHERE i.branch_id = '$branch_id'";

// ✅ Apply date filter only if both dates are provided
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND DATE(i.created_on) BETWEEN '$from_date' AND '$to_date'";
}

$sql .= " GROUP BY ii.productid, ii.product ORDER BY i.created_on DESC";

// ✅ Execute query
$result = mysqli_query($conn, $sql);
$output = '';

if (!$result) {
    die("Query Error: " . mysqli_error($conn));  // ✅ Catch SQL errors
}

if (mysqli_num_rows($result) > 0) {
    $serial_no = 1;  // Initialize Serial No.
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>
            <td>" . $serial_no . "</td>
            <td><a href='individual_product_wise_sales_report.php?productid=" . htmlspecialchars($row['productid']) . "'>" . htmlspecialchars($row['product']) . "</a></td>
            <td>" . number_format($row['total_qty'], 2) . "</td>
            <td>" . number_format($row['total_amount'], 2) . "</td>
        </tr>";
        $serial_no++;  // Increment Serial No.
    }
} else {
    $output .= "<tr><td colspan='4' class='text-center'>No data found</td></tr>";
}

echo $output;
?>
