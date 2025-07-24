<?php
include('config.php');

if (isset($_GET['batchNo']) && isset($_GET['productId'])) {
    $batchNo = $_GET['batchNo'];
    $productId = $_GET['productId'];

    $sql = "SELECT pb.batch_price, pb.batch_gst_rate, pb.batch_net_price, pb.batch_in_ex_gst,im.in_ex_gst,im.cess_rate,pb.batch_cess_rate, pb.batch_cess_amt, im.hsn_code, im.units,pb.batch_designno,pb.batch_size,pb.batch_color 
        FROM product_batches pb JOIN inventory_master im ON im.id = pb.product_id
        WHERE pb.id = ? AND pb.product_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('si', $batchNo, $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $p = $row["batch_net_price"];
            $t = explode('/', $p);
            $p1 = $t[0];
            $t1 = floatval(preg_replace('/[^\d.]/', '', $p1));

            $response = array(
                'gst'        => $row['batch_gst_rate'],
                'price'      => $row['batch_price'],
                'netprice'   => $t1,
                'in_ex_gst'  => $row['in_ex_gst'],
                'cess_rate'  => $row['cess_rate'],
                'cess_amt'   => $row['batch_cess_amt'],
                'hsn_code'   => $row['hsn_code'],
                'units'      => $row['units'],
                'color'      => $row['batch_color'],
                'size'      => $row['batch_size'],
                'dno'      => $row['batch_designno']

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
