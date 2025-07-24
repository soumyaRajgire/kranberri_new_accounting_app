<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['LOG_IN'])) {
    die("Unauthorized access.");
}

include("config.php");

// Get the customer ID, from_date, and to_date from the AJAX request
$customerid = isset($_POST['customerid']) ? $_POST['customerid'] : '';
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';

if (empty($customerid)) {
    die("Customer ID is required.");
}

// Validate the dates
if (!empty($from_date) && !empty($to_date) && $from_date > $to_date) {
    die("Invalid date range: From Date cannot be greater than To Date.");
}

// Fetch individual party-wise sales data with date filtering
$sql = "SELECT 
            i.id AS invoice_id,
            i.invoice_code,
            i.invoice_date,
            i.customer_name AS buyer_name,
            SUM(ii.line_total) AS taxable_amount,
            SUM(ii.cgst) AS cgst,
            SUM(ii.sgst) AS sgst,
            SUM(ii.igst) AS igst,
            SUM(ii.cess_amount) AS cess,
            SUM(ii.total) AS total_amount
        FROM 
            pi_invoice i
        LEFT JOIN 
            pi_invoice_items ii ON i.id = ii.invoice_id
        WHERE 
            i.customer_id = ? and branch_id='" . $_SESSION['branch_id'] . "'";  
            
            //and branch_id='" . $_SESSION['branch_id'] . "'

// Add date filtering if from_date and to_date are provided
if (!empty($from_date)) {
    $sql .= " AND i.invoice_date >= ?";
}
if (!empty($to_date)) {
    $sql .= " AND i.invoice_date <= ?";
}

$sql .= " GROUP BY i.invoice_code
          ORDER BY i.invoice_date DESC";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Database query preparation failed: " . $conn->error);
}

// Bind parameters
$params = [$customerid];
if (!empty($from_date)) {
    $params[] = $from_date;
}
if (!empty($to_date)) {
    $params[] = $to_date;
}
$stmt->bind_param(str_repeat('s', count($params)), ...$params);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$output = '';

if ($result->num_rows > 0) {
    $serial_no = 1;
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>
            <td>{$serial_no}</td>
            <td>{$row['invoice_date']}</td>
            <td>{$row['invoice_code']}</td>
            <td>" . number_format($row['taxable_amount'], 2) . "</td>
            <td>" . number_format($row['cgst'], 2) . "</td>
            <td>" . number_format($row['sgst'], 2) . "</td>
            <td>" . number_format($row['igst'], 2) . "</td>
            <td>" . number_format($row['cess'], 2) . "</td>
            <td>" . number_format($row['total_amount'], 2) . "</td>
        </tr>";
        $serial_no++;
    }
} else {
    $output .= "<tr><td colspan='9' class='text-center'>No data found</td></tr>";
}

echo $output;

// Close the statement and connection
$stmt->close();
$conn->close();
?>