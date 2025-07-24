<?php
session_start();

if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['business_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
    }
}

include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $catlog_type = mysqli_real_escape_string($conn, $_POST['catlog_type']);
    $inventory_type = mysqli_real_escape_string($conn, $_POST['inventory_type']);
    $name = mysqli_real_escape_string($conn, $_POST['goods_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $inclusive_gst = mysqli_real_escape_string($conn, $_POST['inclusive_gst']);
    $gst_rate = mysqli_real_escape_string($conn, $_POST['gst_rate']);
    $non_taxable = mysqli_real_escape_string($conn, $_POST['non_taxable']);
    $net_price = mysqli_real_escape_string($conn, $_POST['net_price']);
    $hsn_code = mysqli_real_escape_string($conn, $_POST['hsn_code']);
    $sac_code = mysqli_real_escape_string($conn, $_POST['sac_code']);
    $units = mysqli_real_escape_string($conn, $_POST['units']);
    $cess_rate = mysqli_real_escape_string($conn, $_POST['cess_rate']);
    $cess_amount = mysqli_real_escape_string($conn, $_POST['cess_amount']);
    $sku = mysqli_real_escape_string($conn, $_POST['sku']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $opening_stock = mysqli_real_escape_string($conn, $_POST['opening_stock']);
    $opening_stockdate = mysqli_real_escape_string($conn, $_POST['opening_stockdate']);
    $min_stockalert = mysqli_real_escape_string($conn, $_POST['min_stockalert']);
    $max_stockalert = mysqli_real_escape_string($conn, $_POST['max_stockalert']);
    $can_be_sold = isset($_POST['can_be_sold']) ? 1 : 0;
    $created_by = $_SESSION['name'];

    // Step 1: Get Existing Stock_in and Stock_out
    $sql_existing_stock = "SELECT stock_in, stock_out FROM inventory_master WHERE id = ?";
    $stmt_existing_stock = $conn->prepare($sql_existing_stock);
    $stmt_existing_stock->bind_param("i", $id);
    $stmt_existing_stock->execute();
    $result_existing_stock = $stmt_existing_stock->get_result();

    if ($row = $result_existing_stock->fetch_assoc()) {
        $stock_in = $row['stock_in'];
        $stock_out = $row['stock_out'];
    } else {
        $stock_in = 0;
        $stock_out = 0;
    }
    $stmt_existing_stock->close();

    // Step 2: Calculate Balance Stock
    $balance_stock = ($opening_stock + $stock_in) - $stock_out;

    // Step 3: Update Inventory Data
    $update_sql = "UPDATE inventory_master SET
        inventory_type = ?, catlog_type = ?, name = ?, category = ?, company_name = ?, 
        price = ?, in_ex_gst = ?, gst_rate = ?, non_taxable = ?, net_price = ?, 
        hsn_code = ?, SAC_Code = ?, units = ?, cess_rate = ?, cess_amt = ?, 
        sku = ?, description = ?, opening_stock = ?, opening_stockdate = ?, 
        min_stockalert = ?, max_stockalert = ?, balance_stock = ?, can_be_sold = ?
        WHERE id = ?";

    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
        die("SQL Prepare Error: " . $conn->error);
    }

    // Bind parameters
    $update_stmt->bind_param(
        "sssssdsdsdsssddssssssssi",
        $inventory_type, $catlog_type, $name, $category, $company_name,
        $price, $inclusive_gst, $gst_rate, $non_taxable, $net_price,
        $hsn_code, $sac_code, $units, $cess_rate, $cess_amount,
        $sku, $description, $opening_stock, $opening_stockdate,
        $min_stockalert, $max_stockalert, $balance_stock, $can_be_sold, $id
    );

    // Step 4: Handle Batch Updates and Insertions
    if (isset($_POST['maintain_batch']) && $_POST['maintain_batch'] == 1) {
        $batch_nos = $_POST['batch_no'];
        $manufacturers = $_POST['manufacturer'];
        $mfg_dates = $_POST['mfg_date'];
        $exp_dates = $_POST['exp_date'];
        $batch_prices = $_POST['batch_price'];
        $batch_non_taxables = $_POST['batch_non_taxable_price'];
        $batch_net_prices = $_POST['batch_net_price'];
        $batch_cess_amounts = $_POST['batch_cess_amount'];
        $batch_opening_stocks = $_POST['batch_opening_stock'];
        $batch_opening_stockdates = $_POST['batch_opening_stockdate'];
        $batch_barcodes = $_POST['batch_barcode'];

        // Step 4.1: Update Existing Batches
        foreach ($batch_nos as $index => $batch_no) {
            $batch_no = mysqli_real_escape_string($conn, $batch_nos[$index]);
            $manufacturer = mysqli_real_escape_string($conn, $manufacturers[$index]);
            $mfg_date = mysqli_real_escape_string($conn, $mfg_dates[$index]);
            $exp_date = mysqli_real_escape_string($conn, $exp_dates[$index]);
            $batch_price = mysqli_real_escape_string($conn, $batch_prices[$index]);
            $batch_non_taxable = mysqli_real_escape_string($conn, $batch_non_taxables[$index]);
            $batch_net_price = mysqli_real_escape_string($conn, $batch_net_prices[$index]);
            $batch_cess_amount = mysqli_real_escape_string($conn, $batch_cess_amounts[$index]);
            $batch_opening_stock = mysqli_real_escape_string($conn, $batch_opening_stocks[$index]);
            $batch_opening_stockdate = mysqli_real_escape_string($conn, $batch_opening_stockdates[$index]);
            $batch_barcode_no = mysqli_real_escape_string($conn, $batch_barcodes[$index]);

            // Handle file upload for barcode image
            $bfileTmpPath = $_FILES['batch_barcodeimage']['tmp_name'][$index];
            $bfileName = $_FILES['batch_barcodeimage']['name'][$index];
            $bfileType = $_FILES['batch_barcodeimage']['type'][$index];
            $bextension = substr($bfileType, 6);
            $bnewFileName = $batch_barcode_no . '.' . $bextension;
            $btargetFilePath = "barcodes/" . $bnewFileName;
            move_uploaded_file($bfileTmpPath, $btargetFilePath);
            $bbarcodeImagePath = $btargetFilePath;

            // Update the existing batch in the product_batches table
            $update_batch_sql = "UPDATE product_batches SET
                manufacturer = ?, mfg_date = ?, exp_date = ?, batch_price = ?, 
                batch_non_taxable = ?, batch_net_price = ?, batch_cess_amt = ?, 
                opening_stock = ?, opening_stockdate = ?, barcode_no = ?, 
                barcode_image = ?
                WHERE product_id = ? AND batch_no = ?";

            $stmt_batch = $conn->prepare($update_batch_sql);
            if ($stmt_batch === false) {
                die("SQL Prepare Error: " . $conn->error);
            }

            // Bind parameters for batch update
            $stmt_batch->bind_param(
                "ssssssssssssss",
                $manufacturer, $mfg_date, $exp_date, $batch_price,
                $batch_non_taxable, $batch_net_price, $batch_cess_amount,
                $batch_opening_stock, $batch_opening_stockdate, $batch_barcode_no,
                $bbarcodeImagePath, $id, $batch_no
            );

            if (!$stmt_batch->execute()) {
                die("Error updating batch: " . $stmt_batch->error);
            }
        }

        // Step 4.2: Insert New Batches
        $sql_batch = "INSERT INTO product_batches (
            product_id, batch_no, manufacturer, mfg_date, exp_date, batch_price, 
            batch_non_taxable, batch_net_price, batch_cess_amt, 
            opening_stock, opening_stockdate, Stock_in, stock_out, balance_stock, 
            barcode_no, barcode_image, created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_batch = $conn->prepare($sql_batch);
        if ($stmt_batch === false) {
            die("SQL Prepare Error: " . $conn->error);
        }

        // Loop through new batches and insert data
        foreach ($batch_nos as $index => $batch_no) {
            if (empty($batch_no)) {
                continue; // Skip empty batch fields
            }

            $batch_no = mysqli_real_escape_string($conn, $batch_nos[$index]);
            $manufacturer = mysqli_real_escape_string($conn, $manufacturers[$index]);
            $mfg_date = mysqli_real_escape_string($conn, $mfg_dates[$index]);
            $exp_date = mysqli_real_escape_string($conn, $exp_dates[$index]);
            $batch_price = mysqli_real_escape_string($conn, $batch_prices[$index]);
            $batch_non_taxable = mysqli_real_escape_string($conn, $batch_non_taxables[$index]);
            $batch_net_price = mysqli_real_escape_string($conn, $batch_net_prices[$index]);
            $batch_cess_amount = mysqli_real_escape_string($conn, $batch_cess_amounts[$index]);
            $batch_opening_stock = mysqli_real_escape_string($conn, $batch_opening_stocks[$index]);
            $batch_opening_stockdate = mysqli_real_escape_string($conn, $batch_opening_stockdates[$index]);
            $batch_barcode_no = mysqli_real_escape_string($conn, $batch_barcodes[$index]);

            // Handle file upload for barcode image
            $bfileTmpPath = $_FILES['batch_barcodeimage']['tmp_name'][$index];
            $bfileName = $_FILES['batch_barcodeimage']['name'][$index];
            $bfileType = $_FILES['batch_barcodeimage']['type'][$index];
            $bextension = substr($bfileType, 6);
            $bnewFileName = $batch_barcode_no . '.' . $bextension;
            $btargetFilePath = "barcodes/" . $bnewFileName;
            move_uploaded_file($bfileTmpPath, $btargetFilePath);
            $bbarcodeImagePath = $btargetFilePath;

            // Bind parameters for batch insertion
            $stmt_batch->bind_param(
                "issssssssssssssss",
                $id, $batch_no, $manufacturer, $mfg_date, $exp_date,
                $batch_price, $batch_non_taxable, $batch_net_price, $batch_cess_amount,
                $batch_opening_stock, $batch_opening_stockdate, 0, 0, $batch_opening_stock,
                $batch_barcode_no, $bbarcodeImagePath, $_SESSION['name']
            );

            if (!$stmt_batch->execute()) {
                die("Error inserting batch: " . $stmt_batch->error);
            }
        }
    }

    // Final Step: Execute Inventory Update
    if ($update_stmt->execute()) {
        echo '<script>alert("Data updated Successfully"); window.location.href="manage-products.php?type='.$inventory_type.'";</script>';
    } else {
        echo "Error updating inventory: " . $update_stmt->error;
    }

    $update_stmt->close();
    $conn->close();
}
?>
