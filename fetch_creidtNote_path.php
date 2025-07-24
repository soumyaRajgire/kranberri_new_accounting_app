<?php
include("config.php");

// Get the invoice_id from the request
$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;

if ($invoice_id > 0) {
    // Query to fetch the file path from the database
    $query = "SELECT cnote_file FROM credit_note WHERE id = $invoice_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'status' => 'success',
            'file_url' => $row['cnote_file'], // Replace this with the correct column name
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invoice not found.',
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid invoice ID.',
    ]);
}

mysqli_close($conn);
?>
