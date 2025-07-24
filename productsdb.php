<?php
// Start of form handling
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit;
}
 
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and validate
    $maintain_batch = isset($_POST['maintain_batch']) ? 1 : 0;
    $catlog_type = isset($_POST['catlog_type']) ? mysqli_real_escape_string($conn, $_POST['catlog_type']) : null;
    $inventory_type = isset($_POST['inventory_type']) ? mysqli_real_escape_string($conn, $_POST['inventory_type']) : null;
    $name = isset($_POST['goods_name']) ? mysqli_real_escape_string($conn, $_POST['goods_name']) : null;
    $category = isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : null;
    $company_name = isset($_POST['company_name']) ? mysqli_real_escape_string($conn, $_POST['company_name']) : null;
    $inclusive_gst = isset($_POST['inclusive_gst1']) ? mysqli_real_escape_string($conn, $_POST['inclusive_gst1']) : null;
    $gst_rate = isset($_POST['gst_rate1']) ? mysqli_real_escape_string($conn, $_POST['gst_rate1']) : null;
    $hsn_code = isset($_POST['hsn_code']) ? mysqli_real_escape_string($conn, $_POST['hsn_code']) : null;
    $units = isset($_POST['units']) ? mysqli_real_escape_string($conn, $_POST['units']) : null;
    $cess_rate = isset($_POST['cess_rate']) ? mysqli_real_escape_string($conn, $_POST['cess_rate']) : null;
    $sku = isset($_POST['sku']) ? mysqli_real_escape_string($conn, $_POST['sku']) : null;
    $totalStock = isset($_POST['totalStock']) ? mysqli_real_escape_string($conn, $_POST['totalStock']) : 0; // Default to 0
    $min_stockalert = isset($_POST['min_stockalert']) ? mysqli_real_escape_string($conn, $_POST['min_stockalert']) : '';
    $max_stockalert = isset($_POST['max_stockalert']) ? mysqli_real_escape_string($conn, $_POST['max_stockalert']) : '';
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    $sac_code = isset($_POST['sac_code']) ? mysqli_real_escape_string($conn, $_POST['sac_code']) : null;
    $can_be_sold = isset($_POST['can_be_sold']) ? 1 : 0; // Defaults to 0 if not checked
    $created_by = $_SESSION['name'];

$rawmaterial = isset($_POST['rawmaterial']) ? mysqli_real_escape_string($conn,$_POST['rawmaterial']): null;
     $color = isset($_POST['color']) ? mysqli_real_escape_string($conn, $_POST['color']) : null;
    $size = isset($_POST['size']) ? mysqli_real_escape_string($conn, $_POST['size']) : null;
    $design_no = isset($_POST['design_no']) ? mysqli_real_escape_string($conn, $_POST['design_no']) : null;


 
    $price = isset($_POST['price1']) ? mysqli_real_escape_string($conn, $_POST['price1']) : null;
    $non_taxable = isset($_POST['non_taxable1']) ? mysqli_real_escape_string($conn, $_POST['non_taxable1']) : null;
    $net_price = isset($_POST['net_price']) ? mysqli_real_escape_string($conn, $_POST['net_price']) : null;
    $cess_amount = isset($_POST['cess_amount']) ? mysqli_real_escape_string($conn, $_POST['cess_amount']) : null;
    $opening_stock = isset($_POST['opening_stock']) ? mysqli_real_escape_string($conn, $_POST['opening_stock']) : 0; 
    $opening_stockdate = isset($_POST['opening_stockdate']) ? mysqli_real_escape_string($conn, $_POST['opening_stockdate']) : null;
    $barcode_no = isset($_POST['barcode']) ? mysqli_real_escape_string($conn, $_POST['barcode'])  : null;
    $stock_out = 0;
    $Stock_in =0;
    $balance_stock = $opening_stock;
    $targetDir = "barcodes/";
    $fileTmpPath = $_FILES['barcodeimage']['tmp_name'];
    $fileName = $_FILES['barcodeimage']['name'];
    $fileSize = $_FILES['barcodeimage']['size'];
    $fileType = $_FILES['barcodeimage']['type'];
    $extension = null;
        if (strpos($fileType, 'image/') === 0) {
            // Extract extension from MIME type
            $extension = substr($fileType, 6);  // Get the part after "image/"
        }
         $barcode_no_safe = str_replace(['/', '\\'], '_', $barcode_no);
$newFileName = $barcode_no_safe . '.' . $extension;

    // $newFileName =  $barcode_no . '.' . $extension;
    $targetFilePath = $targetDir . $newFileName;
    move_uploaded_file($fileTmpPath, $targetFilePath);
    $barcodeImagePath = $targetFilePath;  // This is the file path to store

    $sql = "INSERT INTO inventory_master (inventory_type, can_be_sold, raw_mat_id,catlog_type, name, category, company_name, price, in_ex_gst, gst_rate, non_taxable, net_price, hsn_code, units, cess_rate, cess_amt, sku, opening_stock, opening_stockdate,design_no,color,size, min_stockalert, max_stockalert, Stock_in, stock_out, balance_stock,maintain_batch,barcode_no, barcode_image, description, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?) ON DUPLICATE KEY UPDATE opening_stock=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("SQL Error: " . $conn->error);
        echo '<script>alert("Database error. Check logs for details.");</script>';
        exit;
        }
    $stmt->bind_param("ssssssssssssssissssssssssssssssss", $inventory_type, $can_be_sold, $rawmaterial,$catlog_type, $name, $category, $company_name, $price, $inclusive_gst, $gst_rate, $non_taxable, $net_price, $hsn_code, $units, $cess_rate, $cess_amount, $sku, $opening_stock, $opening_stockdate,$design_no,$color,$size, $min_stockalert, $max_stockalert, $Stock_in, $stock_out, $balance_stock, $maintain_batch,$barcode_no,$barcodeImagePath, $description, $created_by, $opening_stock );
    $stmt->execute();

     $product_id = $stmt->insert_id; 

if ($maintain_batch) {
        if (isset($_POST['batch_no'])) {
            $batch_nos = $_POST['batch_no'];
            $manufacturers = $_POST['manufacturer'];
            $mfg_dates = $_POST['mfg_date'];
            $exp_dates = $_POST['exp_date'];
            $batch_prices = $_POST['batch_price'];
            // $batch_gst_rates = $_POST['batch_gst_rate'];
            $batch_non_taxables = $_POST['batch_non_taxable_price'];
            $batch_net_prices = $_POST['batch_net_price'];
            $batch_cess_amounts = $_POST['batch_cess_amount'];
            $batch_opening_stocks = $_POST['batch_opening_stock'];
            $batch_opening_stockdates = $_POST['batch_opening_stockdate'];

            $batch_colors = $_POST['batch_color'];
            $batch_sizes = $_POST['batch_size'];
            $batch_designnos = $_POST['batch_designno'];
            // $remarks = isset($_POST['remark']) ? $_POST['remark'] : [];
            $batch_barcodes = $_POST['batch_barcode'];

           

            // Insert into product_batches table
            $sql_batch = "INSERT INTO product_batches (
                product_id, batch_no, manufacturer, mfg_date, exp_date, batch_price, 
                batch_gst_rate, batch_non_taxable, batch_net_price, batch_cess_amt, 
                opening_stock, opening_stockdate,batch_designno,batch_color,batch_size, Stock_in, stock_out, balance_stock, 
                barcode_no, barcode_image, created_by
            ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";



            // Prepare the statement for product_batches
            $stmt_batch = $conn->prepare($sql_batch);
            if ($stmt_batch === false) {
                error_log("SQL Error: " . $conn->error);
                echo '<script>alert("Database error while inserting batch data. Check logs for details.");</script>';
                exit;
            }

            foreach ($batch_nos as $index => $batch_no) {
                // Sanitize inputs
              
                $batch_no = mysqli_real_escape_string($conn, $batch_nos[$index]);
                $manufacturer = mysqli_real_escape_string($conn, $manufacturers[$index]);
                $mfg_date = mysqli_real_escape_string($conn, $mfg_dates[$index]);
                $exp_date = mysqli_real_escape_string($conn, $exp_dates[$index]);
                $batch_price = mysqli_real_escape_string($conn, $batch_prices[$index]);
                // $batch_gst_rate = mysqli_real_escape_string($conn, $batch_gst_rates[$index]);
                $batch_non_taxable = mysqli_real_escape_string($conn, $batch_non_taxables[$index]);
                $batch_net_price1 = mysqli_real_escape_string($conn, $batch_net_prices[$index]);
                $batch_net_price_parts = explode(' | ', $batch_net_price1);
                 $batch_net_price = $batch_net_price_parts[0]; // Net Price
    $batch_gst_rate = $batch_net_price_parts[1]; // GST Amount

    // Optionally, sanitize or format the values if needed
    $batch_net_price = floatval($batch_net_price); // Convert to float for calculations
    $batch_gst_rate = floatval($batch_gst_rate); // Convert to float for calculations
     echo $batch_color = mysqli_real_escape_string($conn, $batch_colors[$index]);
      echo $batch_size = mysqli_real_escape_string($conn, $batch_sizes[$index]);
      echo $batch_designno = mysqli_real_escape_string($conn,$batch_designnos[$index]);

                $batch_cess_amount = mysqli_real_escape_string($conn, $batch_cess_amounts[$index]);
                $batch_opening_stock = mysqli_real_escape_string($conn, $batch_opening_stocks[$index]);
                $batch_opening_stockdate = mysqli_real_escape_string($conn, $batch_opening_stockdates[$index]);
                // $remark = isset($remarks[$index]) ? mysqli_real_escape_string($conn, $remarks[$index]) : null;
                $batch_barcode_no = mysqli_real_escape_string($conn, $batch_barcodes[$index]);

                $bstock_out = 0;
                $bStock_in = 0;
                $bbalance_stock = $batch_opening_stock;
                $created_by = $_SESSION['name'];
                // Handle file upload

                $btargetDir = "barcodes/";
                 $bfileTmpPath = $_FILES['batch_barcodeimage']['tmp_name'][$index];
                $bfileName = $_FILES['batch_barcodeimage']['name'][$index];
                $bfileType = $_FILES['batch_barcodeimage']['type'][$index];
                 $bextension = null;
        if (strpos($bfileType, 'image/') === 0) {
            // Extract extension from MIME type
            echo $bextension = substr($bfileType, 6);  // Get the part after "image/"
        }
                // Generate a unique name for the barcode image
                // $bfileExt = pathinfo($bfileName, PATHINFO_EXTENSION);
        $batch_barcode_no_safe = str_replace(['/', '\\'], '-', $batch_barcode_no);
$bnewFileName = $batch_barcode_no_safe . '.' . $bextension;
                 // $bnewFileName = $batch_barcode_no . '.' . $bextension;
               $btargetFilePath = $btargetDir . $bnewFileName;
                move_uploaded_file($bfileTmpPath, $btargetFilePath);
                 $bbarcodeImagePath = $btargetFilePath;

                // Bind the parameters for batch insertion
                $stmt_batch->bind_param(
                    "issssssssssssssssssss", 
                    $product_id, $batch_no, $manufacturer, $mfg_date, $exp_date, 
                    $batch_price, $gst_rate, $batch_non_taxable, $batch_net_price1, 
                    $batch_cess_amount, $batch_opening_stock, $batch_opening_stockdate,$batch_designno, $batch_color, $batch_size,$bStock_in, $bstock_out, $bbalance_stock, $batch_barcode_no, 
                    $bbarcodeImagePath, $created_by
                );

                // Execute the batch insert query
                if (!$stmt_batch->execute()) {
                    error_log("SQL Error: " . $stmt_batch->error);
                    echo '<script>alert("Error inserting batch data. Check logs for details.");</script>';
                    exit;
                }
            }

        }

        echo '<script>alert("Product and batch data inserted successfully."); window.location.href="manage-products.php?type=' . $inventory_type . '";</script>';
    }   

    

    // Close statements
    $stmt->close();
    if (isset($stmt_batch)) $stmt_batch->close();
}

$conn->close();
?>