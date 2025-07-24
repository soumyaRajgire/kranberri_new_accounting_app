<?php
session_start();  // ✅ Ensure session is started

include("config.php");

if (!isset($_SESSION['branch_id'])) {
    die("Branch ID not set. Please log in again.");
}

// Fetch request parameters and sanitize inputs
$from_date = isset($_POST['from_date']) ? trim($_POST['from_date']) : '';
$to_date = isset($_POST['to_date']) ? trim($_POST['to_date']) : '';
$branch_id = $_SESSION['branch_id']; // ✅ Fetch branch_id from session

// Base SQL query
$sql = "SELECT productid, product, SUM(qty) AS total_qty, SUM(qty * price) AS total_amount
       FROM `pi_invoice_items` ii
        JOIN `pi_invoice` i ON i.id = ii.invoice_id
        WHERE i.branch_id = '$branch_id'";


// Apply date filter only if both dates are provided
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND DATE(i.created_on) BETWEEN '$from_date' AND '$to_date'";
}

$sql .= " GROUP BY ii.productid, ii.product ORDER BY i.created_on DESC";

// Prepare and execute the statement
$result = mysqli_query($conn, $sql);
$output = '';

if (!$result) {
    die("Query Error: " . mysqli_error($conn));  // ✅ Catch SQL errors
}

if (mysqli_num_rows($result) > 0) {
    $serial_no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>
            <td>" . $serial_no . "</td>
            <td><a href='individual_product_wise_purchase_report.php?productid=" . htmlspecialchars($row['productid']) . "'>" . htmlspecialchars($row['product']) . "</a></td>
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
