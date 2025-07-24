<?php
include("config.php");

$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;

if ($invoice_id > 0) {
    $query = "SELECT quotation_file FROM quotation WHERE id = $invoice_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'status' => 'success',
            'file_url' => htmlspecialchars($row['quotation_file'], ENT_QUOTES, 'UTF-8'),
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
