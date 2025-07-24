<?php
include("config.php");

$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;

if ($invoice_id > 0) {
    $query = "SELECT invoice_file, customer_name FROM pi_invoice WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        echo json_encode([
            'status' => 'success',
            'file_url' => htmlspecialchars($row['invoice_file'], ENT_QUOTES, 'UTF-8'),
            'customer_name' => htmlspecialchars($row['customer_name'], ENT_QUOTES, 'UTF-8'),
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invoice not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid invoice ID.']);
}

$conn->close();
?>
