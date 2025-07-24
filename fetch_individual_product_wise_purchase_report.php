<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['LOG_IN'])) {
    die("Unauthorized access.");
}

// Ensure branch_id exists in session
if (!isset($_SESSION['branch_id'])) {
    die("Branch ID not set. Please log in again.");
}

include("config.php");

// Get the product ID, from_date, and to_date from the AJAX request
$productid = isset($_POST['productid']) ? $_POST['productid'] : '';
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
$branch_id = $_SESSION['branch_id']; // Fetch branch_id from session

if (empty($productid)) {
    die("Product ID is required.");
}

// Validate the dates
if (!empty($from_date) && !empty($to_date) && $from_date > $to_date) {
    die("Invalid date range: From Date cannot be greater than To Date.");
}

// Fetch individual product-wise sales data with date filtering
$sql = "SELECT 
            i.id AS invoice_id,
            i.invoice_code,
            i.customer_name AS buyer_name,
            i.invoice_date,
            ii.qty AS quantity,
            ii.price AS rate,
            ii.line_total,
            ii.cgst,
            ii.sgst,
            ii.igst,
            ii.cess_amount AS cess,
            ii.total AS total_amount
        FROM 
            pi_invoice_items ii
        JOIN 
            pi_invoice i ON ii.invoice_id = i.id
        WHERE 
            ii.productid = ? AND i.branch_id = ?";
            // GROUP BY invoice_id";

// Add date filtering if from_date and to_date are provided
if (!empty($from_date)) {
    $sql .= " AND i.invoice_date >= ?";
}
if (!empty($to_date)) {
    $sql .= " AND i.invoice_date <= ?";
}

$sql .= " ORDER BY i.invoice_date DESC";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Database query preparation failed: " . $conn->error);
}

// Bind parameters
$params = [$productid, $branch_id];
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

if (!$result) {
    die("Query Error: " . mysqli_error($conn));  // Catch SQL errors
}

if ($result->num_rows > 0) {
    
    $serial_no = 1;
    while ($row = $result->fetch_assoc()) {
        // Calculate taxable amount
        $taxable_amount = $row['line_total'] - ($row['cgst'] + $row['sgst'] + $row['igst'] + $row['cess']);

        $output .= "<tr>
            <td>{$serial_no}</td>
            <td>{$row['invoice_code']}</td>
            <td>{$row['buyer_name']}</td>
            <td>{$row['invoice_date']}</td>
            <td>{$row['quantity']}</td>
            <td>" . number_format($row['rate'], 2) . "</td>
            <td>" . number_format($taxable_amount, 2) . "</td>
            <td>" . number_format($row['cgst'], 2) . "</td>
            <td>" . number_format($row['sgst'], 2) . "</td>
            <td>" . number_format($row['igst'], 2) . "</td>
            <td>" . number_format($row['cess'], 2) . "</td>
            <td>" . number_format($row['total_amount'], 2) . "</td>
        </tr>";
        $serial_no++;
    }
} else {
    $output .= "<tr><td colspan='12' class='text-center'>No data found</td></tr>";
}

echo $output;

// Close the statement and connection
$stmt->close();
$conn->close();
?>