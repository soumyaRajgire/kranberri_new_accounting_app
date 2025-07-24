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
                        
                    $dno = $row1['batch_designno'];
                    $color = $row1['batch_color'];
                    $size = $row1['batch_size'];
                      $barcode_no = $row1['barcode_no'];
                        $barcode_image = $row1[' barcode_image'];
                  
                     $response = array(
                'dno' => $dno,
                'color' => $color,
                'size' => $size,
                 'barcode_no' => $barcode_no,
                  'barcode_image' => $barcode_image
                
            );
                }
            }
         }
            else{

                    $dno = $row['design_no'];
                    $color = $row['color'];
                    $size = $row['size'];
                      $barcode_no = $row['barcode_no'];
                        $barcode_image = $row[' barcode_image'];
                  
                     $response = array(
                'dno' => $dno,
                'color' => $color,
                'size' => $size,
                 'barcode_no' => $barcode_no,
                  'barcode_image' => $barcode_image
                
            );
            }
            // Include the price and related information in the response
           
        }

            echo json_encode($response);
        }
    }
?>
