<?php
include("config.php");

$response = ['status' => 'error', 'message' => 'Invalid request'];

if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    // Query to fetch the total quantity credited for the product from all previous credit notes
    $query = "SELECT SUM(qi.qty) AS total_credited_qty 
              FROM credit_note_items qi 
              JOIN credit_note q ON qi.cnote_id = q.id
              WHERE qi.productid = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Return the total credited quantity
        $response = [
            'status' => 'success',
            'total_credited_qty' => $row['total_credited_qty']
        ];
    } else {
        $response = ['status' => 'error', 'message' => 'Failed to fetch credited quantity.'];
    }

    $stmt->close();
}

echo json_encode($response);
exit();
?>
