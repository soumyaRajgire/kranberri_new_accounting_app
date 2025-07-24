<?php
include('config.php');

if (isset($_GET['batchNo']) && isset($_GET['productId'])) {
    $batchNo = $_GET['batchNo'];
    $productId = $_GET['productId'];

    $sql = "
        SELECT pb.id,pb.batch_size, pb.batch_designno, pb.batch_color, pb.batch_no,pb.barcode_no,pb.barcode_image FROM product_batches pb
        WHERE pb.id = ? AND pb.product_id = ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('si', $batchNo, $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
           
            $response = array(
                'dno'        => $row['batch_designno'],
                'size'      => $row['batch_size'],
                'color'   => $row['batch_color'],
                'batch_no' => $row['batch_no'],
                'batch_id' => $row['id'],
                 'barcode_no' => $row['barcode_no'],
                  'barcode_image' => $row['barcode_image']
            );

            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Batch not found.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare SQL statement.']);
    }
} else {
    echo json_encode(['error' => 'batchNo and productId required.']);
}

$conn->close();
?>
