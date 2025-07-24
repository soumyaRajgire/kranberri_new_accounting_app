<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customer_id'])) {
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    
    $sql = "SELECT id FROM invoice WHERE customer_id = '$customer_id' AND status != 'paid' ORDER BY created_on DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode(['invoice_id' => $row['id']]);
    } else {
        echo json_encode(['invoice_id' => '']);
    }
}