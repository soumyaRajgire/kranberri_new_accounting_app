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
    $catlog_type = isset($_POST['catlog_type']) ? mysqli_real_escape_string($conn, $_POST['catlog_type']) : null;
    $inventory_type = isset($_POST['inventory_type']) ? mysqli_real_escape_string($conn, $_POST['inventory_type']) : null;
    $name = isset($_POST['goods_name']) ? mysqli_real_escape_string($conn, $_POST['goods_name']) : null;
    $category = isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : null;
    $company_name = isset($_POST['company_name']) ? mysqli_real_escape_string($conn, $_POST['company_name']) : null;
    $created_by = isset($_SESSION['name']) ? mysqli_real_escape_string($conn, $_SESSION['name']) : null; 
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    $sac_code = isset($_POST['sac_code']) ? mysqli_real_escape_string($conn, $_POST['sac_code']) : null;

// $can_be_sold = isset($_POST['can_be_sold']) ? mysqli_real_escape_string($conn, $_POST['can_be_sold']) : null;

  $can_be_sold = isset($_POST['can_be_sold']) ? 1 : 0; // Defaults to 0 if not checked

    // Determine opening_stock logic
    //$opening_stock = $totalStock;

   // SQL Query: Insert or Update opening_stock
    $sql = "INSERT INTO inventory_master 
        (inventory_type, can_be_sold, catlog_type, name, category, company_name, description, created_by)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("SQL Error: " . $conn->error);
        echo '<script>alert("Database error. Check logs for details.");</script>';
        exit;
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssssss",  
        $inventory_type, $can_be_sold, $catlog_type, $name, $category, $company_name, $description, $created_by
    );

    // Execute the statement and check for errors
    if ($stmt->execute()) {
        // Get the inserted product's ID
        $product_id = $stmt->insert_id; 

        // Insert batch data into product_batches table only if the product insertion is successful
    if (isset($_POST['batch_no'])) {
    $batch_nos = $_POST['batch_no'];
    $manufacturers = $_POST['manufacturer'];
    $mfg_dates = $_POST['mfg_date'];
    $exp_dates = $_POST['exp_date'];
    $prices = $_POST['price'];
    $inclusive_gsts = $_POST['inclusive_gst'];
    $gst_rates = $_POST['gst_rate'];
    $non_taxables = $_POST['non_taxable'];
    $net_prices = $_POST['net_price'];
    $hsn_codes = $_POST['hsn_code'];
    $units = $_POST['units'];
    $cess_rates = $_POST['cess_rate'];
  $cess_amounts = $_POST['cess_amount'];
    $skus = $_POST['sku'];
    $opening_stocks = $_POST['opening_stock'];
    $opening_stockdates = $_POST['opening_stockdate'];
    $min_stockalerts = $_POST['min_stockalert'];
    $max_stockalerts = $_POST['max_stockalert'];
    $remarks = isset($_POST['remark']) ? $_POST['remark'] : [];
        

            // Prepare the batch insert statement
    $sql_batch = "INSERT INTO product_batches (
    product_id, batch_no, manufacturer, mfg_date, exp_date, price,
    in_ex_gst, gst_rate, non_taxable, net_price, hsn_code, units,
    cess_rate, cess_amt, sku, opening_stock, opening_stockdate,
    min_stockalert, max_stockalert, remark, created_by
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE 
opening_stock = VALUES(opening_stock)";


            $stmt_batch = $conn->prepare($sql_batch);
            if ($stmt_batch === false) {
                error_log("SQL Error: " . $conn->error);
                echo '<script>alert("Database error while inserting batch data. Check logs for details.");</script>';
                exit;
            }

            // Bind parameters for the batch insert
             foreach ($batch_nos as $index => $batch_no) {
        $manufacturer = $manufacturers[$index];
        $mfg_date = $mfg_dates[$index];
        $exp_date = $exp_dates[$index];
        $price = $prices[$index];
        $inclusive_gst = $inclusive_gsts[$index];
        $gst_rate = $gst_rates[$index];
        $non_taxable = $non_taxables[$index];
        $net_price = $net_prices[$index];
        $hsn_code = $hsn_codes[$index];
        $unit = $units[$index];
        $cess_rate = $cess_rates[$index];
        $cess_amount = $cess_amounts[$index];
        $sku = $skus[$index];
        $opening_stock = $opening_stocks[$index];
        $opening_stockdate = $opening_stockdates[$index];
        $min_stockalert = $min_stockalerts[$index];
        $max_stockalert = $max_stockalerts[$index];
        $remark = isset($remarks[$index]) ? $remarks[$index] : null;
 $stock_out = 0;
$Stock_in =0;
$balance_stock = $opening_stock;
    $created_by = $_SESSION['name'];
       $stmt_batch->bind_param(
    "isssssssssssssdssssss",
    $product_id, $batch_no, $manufacturer, $mfg_date, $exp_date, $price,
    $inclusive_gst, $gst_rate, $non_taxable, $net_price, $hsn_code, $unit,
    $cess_rate, $cess_amount, $sku, $opening_stock, $opening_stockdate,
    $min_stockalert, $max_stockalert, $remark, $created_by
);
        if (!$stmt_batch->execute()) {
            error_log("SQL Error: " . $stmt_batch->error);
            echo '<script>alert("Error inserting batch data. Check logs for details.");</script>';
            exit;
        }
            }
        }

        echo '<script>alert("Product and batch data inserted successfully."); window.location.href="manage-products.php?type=' . $inventory_type . '";</script>';
    } else {
        error_log("SQL Error: " . $stmt->error);
        echo '<script>alert("Error inserting product data. Check logs for details.");</script>';
    }

    // Close statements
    $stmt->close();
    if (isset($stmt_batch)) $stmt_batch->close();
}

$conn->close();
?>