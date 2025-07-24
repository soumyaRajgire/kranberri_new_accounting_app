<?php
include_once("config.php");

if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];
    $response = array();

    $sql_batch = "SELECT pb.*,pb.id as pbid, im.name as product_name, im.id as product_id, im.maintain_batch,im.in_ex_gst,im.cess_rate
                  FROM product_batches pb
                  JOIN inventory_master im ON pb.product_id = im.id
                  WHERE pb.barcode_no = '$barcode'";

    $result_batch = $conn->query($sql_batch);

    if ($result_batch->num_rows > 0) {
        $row = $result_batch->fetch_assoc();
        $p = $row["batch_net_price"];
        $t = explode('/', $p);
        $p1 = $t[0];
        $t1 = floatval(preg_replace('/[^\d.]/', '', $p1));

        $response = array(
            'status'          => "success",
            'product_id'      => $row['product_id'],
            'product_name'    => $row['product_name'],
            'maintain_batch'  => $row['maintain_batch'],
            'is_batch_barcode'=> true,
            'batch_no'        => $row['batch_no'],
            'pbid'            => $row['pbid'],
            'gst'             => $row['batch_gst_rate'],
            'price'           => $row['batch_price'],
            'netprice'        => $t1,
            'in_ex_gst'       => $row['in_ex_gst'],
            'cess_rate'       => $row['cess_rate'],
            'cess_amt'        => $row['batch_cess_amt'],
            'hsn_code'        => $row['hsn_code'],
            'units'           => $row['units'],
            'exp_date'        => $row['exp_date'],
             'size'           => $row['batch_size'],
            'dno'             => $row['batch_designno'],
            'color'           => $row['batch_color'],
        );
    } else {
        $sql_prod = "SELECT * FROM inventory_master WHERE barcode_no = '$barcode'";
        $result_prod = $conn->query($sql_prod);

        if ($result_prod->num_rows > 0) {
            $row1 = $result_prod->fetch_assoc();
            $p = $row1["net_price"];
            $t = explode('/', $p); 
            $p1 = $t[0];
            $t1 = floatval(preg_replace('/[^\d.]/', '', $p1));

            $response = array(
                'status'          => "success",
                'product_id'      => $row1['id'],
                'product_name'    => $row1['name'],
                'maintain_batch'  => $row1['maintain_batch'],
                'is_batch_barcode'=> false,
                'gst'             => $row1['gst_rate'],
                'price'           => $row1['price'],
                'netprice'        => $t1,
                'in_ex_gst'       => $row1['in_ex_gst'],
                'cess_rate'       => $row1['cess_rate'],
                'cess_amt'        => $row1['cess_amt'],
                'hsn_code'        => $row1['hsn_code'],
                'units'           => $row1['units'],
                'size'           => $row1['size'],
                'dno'            => $row1['dno'],
                'color'          => $row1['color'],
            );
        } else {
            $response = array("status" => "error", "message" => "Barcode not found.");
        }
    }

    echo json_encode($response);
}
?>
