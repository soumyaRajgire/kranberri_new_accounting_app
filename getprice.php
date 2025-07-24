<?php
include_once("config.php");

if ($_GET['productname']) {
    $pcat = $_GET['productname'];
    $pid = $_GET['productid'];


    $sql = "SELECT * FROM inventory_master WHERE id='$pid' AND name = '$pcat'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        if ($row = mysqli_fetch_assoc($result)) {


            if($row["maintain_batch"] === 1)
            {
                $batch_no = $_GET['batch_no'];
                $sql1 = "SELECT * FROM product_batches WHERE id='$batch_no' AND product_id ='$pid' ";
                $result1 = $conn->query($sql1);
                if ($result1->num_rows > 0) {

                if ($row1 = mysqli_fetch_assoc($result1)) {
                        $p = $row1["batch_net_price"];
                    $t = explode('/', $p);
                    $price = $row1['batch_price'];
                    $p1 = $t['0'];
                    $t1 = floatval(preg_replace('/[^\d. ]/', '', $p1));
                    $gst = $row['gst_rate'];
                    $in_ex_gst = $row['in_ex_gst'];
                    $cess_rate = $row['cess_rate'];
                    $cess_amt = $row['cess_amt'];
                    $hsn_code = $row['hsn_code'];
                    $units = $row['units'];
                    $color = $row1['batch_color'];
                    $size = $row1['batch_size'];
                    $dno = $row1['batch_designno'];
                     $response = array(
                'gst' => $gst,
                'netprice' => $t1,
                'price' => $price,
                'in_ex_gst' => $in_ex_gst,
                'cess_rate' => $cess_rate,
                'cess_amt' => $cess_amt,
                'hsn_code' => $hsn_code,
                'units' => $units,
                'color' => $color,
                'size' => $size,
                'dno' => $dno
            );
                }
            }
         }
            else{


            $p = $row["net_price"];
            $t = explode('/', $p);
            $price = $row['price'];
            $p1 = $t['0'];
            $t1 = floatval(preg_replace('/[^\d. ]/', '', $p1));
            $gst = $row['gst_rate'];
            $in_ex_gst = $row['in_ex_gst'];
            $cess_rate = $row['cess_rate'];
            $cess_amt = $row['cess_amt'];
            $hsn_code = $row['hsn_code'];
            $units = $row['units'];
            $color = $row1['color'];
            $size = $row1['size'];
            $dno = $row1['dno'];
             $response = array(
                'gst' => $gst,
                'netprice' => $t1,
                'price' => $price,
                'in_ex_gst' => $in_ex_gst,
                'cess_rate' => $cess_rate,
                'cess_amt' => $cess_amt,
                'hsn_code' => $hsn_code,
                'units' => $units,
                'color' => $color,
                'size' => $size,
                'dno' => $dno
            );
            }
            // Include the price and related information in the response
           
        }

            echo json_encode($response);
        }
    }
?>
