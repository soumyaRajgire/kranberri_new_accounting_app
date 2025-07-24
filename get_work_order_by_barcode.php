<?php
include('config.php');

if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];

    // Fetch product details along with maintain_batch flag (to check if batch is maintained)
    $sql = "SELECT im.size, im.dno, im.color, im.maintain_batch, im.barcode_image,im.barcode_no, pb.id as pbid,pb.batch_no, pb.exp_date
            FROM inventory_master im
            LEFT JOIN product_batches pb ON im.id = pb.product_id
            WHERE im.barcode_no = '$barcode' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = array(
            'status' => 'success',
            'size' => $row['size'],
            'dno' => $row['dno'],
            'color' => $row['color'],
            'maintain_batch' => $row['maintain_batch'],
            'batch_no' => $row['batch_no'],
            'pbid' => $row['pbid'],
             'barcode_no' => $row['barcode_no'],
            'barcode_image' => $row['barcode_image'],
            'exp_date' => $row['exp_date'],
        );
        echo json_encode($response); // Send response as JSON
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    }
}
?>
