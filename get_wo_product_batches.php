<?php
include('config.php');

if (isset($_GET['productId'])) {
    $productId = $_GET['productId'];


    $sql = "
        SELECT pb.id,pb.batch_no, pb.exp_date,pb.barcode_no,pb.barcode_image
        FROM product_batches pb
        WHERE pb.product_id = ? 
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        $batches = [];
        while ($row = $result->fetch_assoc()) {
            $batches[] = $row;
        }

        echo json_encode($batches);
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare SQL statement.']);
    }
} else {
    echo json_encode([]);
}

$conn->close();
?>
