<?php
include("config.php");

// Get the invoice_id from the request
$bill_id = isset($_GET['bill_id']) ? intval($_GET['bill_id']) : 0;

if ($bill_id > 0) {
    // Query to fetch the file path from the database
    $query = "SELECT bill_file FROM bill_of_supply WHERE id = '$bill_id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'status' => 'success',
            'file_url' => $row['bill_file'], // Replace this with the correct column name
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
        'message' => 'Invalid Bill ID.',
    ]);
}

mysqli_close($conn);
?>
