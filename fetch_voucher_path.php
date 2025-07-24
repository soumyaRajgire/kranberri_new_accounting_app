<?php
include("config.php");

// Get the voucher ID from the request
$voucher_id = isset($_GET['voucher_id']) ? intval($_GET['voucher_id']) : 0;

if ($voucher_id > 0) {
    // Query to fetch the voucher file and customer email
    $query = "SELECT v.voucher_file, c.email AS customer_email
              FROM voucher v
              JOIN customers c ON v.customer_id = c.id
              WHERE v.id = $voucher_id";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Respond with success and the voucher details
        echo json_encode([
            'status' => 'success',
            'voucher_file' => $row['voucher_file'],
            'customer_email' => $row['customer_email'],
        ]);
    } else {
        // Respond with an error if the voucher is not found
        echo json_encode(['status' => 'error', 'message' => 'Voucher not found.']);
    }
} else {
    // Respond with an error for invalid voucher ID
    echo json_encode(['status' => 'error', 'message' => 'Invalid voucher ID.']);
}

// Close the database connection
mysqli_close($conn);
?>
