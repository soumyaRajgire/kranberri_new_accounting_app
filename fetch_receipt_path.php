<?php
include("config.php");

$receipt_id = isset($_GET['receipt_id']) ? intval($_GET['receipt_id']) : 0;

if ($receipt_id > 0) {
    $query = "SELECT r.pdf_file_path, c.email AS customer_email FROM receipts r JOIN customer_master c ON r.customer_id = c.id WHERE r.id  = $receipt_id";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'status' => 'success',
            'receipt_file' => $row['pdf_file_path'],
            'customer_email' => $row['customer_email'],
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Receipt not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid receipt ID.']);
}

mysqli_close($conn);
?>
