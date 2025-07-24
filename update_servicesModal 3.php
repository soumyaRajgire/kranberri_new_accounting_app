<!DOCTYPE html>
<?php
session_start();  // You should start the session at the beginning of the page
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit; // Ensure that the script exits after the redirection
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
include("config.php");
?>

<html lang="en">
<head>
    <title>Update Services</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
</head>

<body class="">
    <!-- [ Pre-loader ] start -->
    <?php include("menu.php"); ?>
    <!-- [ Header ] end -->


    <?php
// Check if ID is provided via GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure that $id is an integer to prevent SQL injection

    // Fetch inventory details from the database
    $select_sql = "SELECT * FROM inventory_master WHERE id = ?";

    $select_stmt = $conn->prepare($select_sql);
    $select_stmt->bind_param("i", $id);
    $select_stmt->execute();
    $result = $select_stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Extract inventory details
        $inventory_type = $row['inventory_type'];
        $catlog_type = $row['catlog_type'];
        $name = $row['name'];
        $price = $row['price'];
        $inclusive_gst = $row['in_ex_gst'];
        $gst_rate = $row['gst_rate'];
        $non_taxable = $row['non_taxable'];
        $net_price = $row['net_price'];
        $hsn_code = $row['hsn_code'];
        $sac_code = $row['SAC_Code'];
        $units = $row['units'];
        $cess_amount = $row['cess_amt'];
        $sku = $row['sku'];
        $description = $row['description'];
    } else {
        // Handle the case where the inventory is not found
        echo "Inventory item not found.";
        exit;
    }
    $select_stmt->close();
}

if (isset($_POST['submit'])) {
    // Process form submission

    // Retrieve form data from $_POST
    $inventory_type = $_POST['inventory_type'];
    $catlog_type = $_POST['catlog_type'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $inclusive_gst = $_POST['inclusive_gst'];
    $gst_rate = $_POST['gst_rate'];
    $non_taxable = $_POST['non_taxable'];
    $net_price = $_POST['net_price'];
    $hsn_code = $_POST['hsn_code'];
    $sac_code = $_POST['sac_code'];
    $units = $_POST['units'];
    $cess_amount = $_POST['cess_amount'];
    $sku = $_POST['sku'];
    $description = $_POST['description'];

    // Update the Inventory item in the database
    $update_inventory_sql = "UPDATE inventory_master SET
        inventory_type = ?,
        catlog_type = ?,
        name = ?,
        price = ?,
        in_ex_gst = ?,
        gst_rate = ?,
        non_taxable = ?,
        net_price = ?,
        hsn_code = ?,
        SAC_Code = ?,
        units = ?,
        cess_amt = ?,
        sku = ?,
        description = ?
        WHERE id = ?";

    $update_inventory_stmt = $conn->prepare($update_inventory_sql);

    if (!$update_inventory_stmt) {
        die("Prepare failed: " . $conn->error); // Add error handling
    }

    $update_inventory_stmt->bind_param("ssssssssssssssi",
        $inventory_type, $catlog_type, $name, $price, $inclusive_gst, $gst_rate, $non_taxable, $net_price, $hsn_code, $sac_code, $units, $cess_amount, $sku, $description, $id);

    $update_inventory_result = $update_inventory_stmt->execute();

    if (!$update_inventory_result) {
        die("Update inventory item failed: " . $update_inventory_stmt->error); // Print the error message
    }

    $update_inventory_stmt->close();

    if ($update_inventory_result) {
        echo '<script>alert("Inventory data updated successfully!");</script>';
        echo '<script>window.location="manage-products.php?type=' . urlencode($inventory_type) . '";</script>';
        exit();
    } else {
        die("Update inventory item failed: " . $update_inventory_stmt->error);
    }
}
?>




<style>
    /* Custom CSS styles for the card */
    .custom-card  {
        width: 1452px; /* Adjust the width as per your preference */
        height: 710px;
        margin-left: 235px /* Center the card horizontally */
    }
</style>



<div class="custom-card">
    <div class="card-header">
        <h4 class="card-title">Update Services</h4>
    </div>
    <form action="update_servicesModal.php" method="POST">
        <div class="card-body">
            <input type="hidden" name="catlog_type" id="catlog_type" value="services" class="modal-input catlog-type-input" data-modal="services">
            <input type="hidden" name="inventory_type" id="inventory_type2" value="" class="modal-input inventory-type-input">
            <div class="row">
                <div class="mb-1 col-lg-6">
                    <div class="did-floating-label-content">
                        <input type="text" id="goods_name" name="goods_name" class="did-floating-input modal-input name-input" data-modal="services" placeholder="" value="<?php echo $row['name'];?>">
                        <label for="goods_name" class="did-floating-label">Service Name</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                        <input type="text" id="price" name="price" class="did-floating-input modal-input price-input" data-modal="services" placeholder="" value="<?php echo ($row['price'])?>" required>
                        <label for="price" class="did-floating-label">Price</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                        <select id="inclusive_gst" name="inclusive_gst" class="did-floating-select modal-select inclusive-gst-select" data-modal="services">
                        <option value="<?php echo $row['in_ex_gst']; ?>"><?php echo $row['in_ex_gst']; ?></option> 
                            <option value="inclusive of GST">Inclusive of GST</option>
                            <option value="exclusive of GST">Exclusive of GST</option>
                        </select>
                    </div>
                </div>
                <div class="mb-1 col-lg-6">
                    <div class="did-floating-label-content">
                        <select id="gst_rate" name="gst_rate" class="did-floating-select modal-select gst-rate-input" data-modal="services">
                        <option value="<?php echo $row['gst_rate']; ?>"><?php echo $row['gst_rate']; ?></option> 
                        <option value=""> - Please Select - </option>
                            <option value="nil rated">Nil-Rated</option>
                            <option value="zero rated">Zero-Rated</option>
                            <option value="exempted supply">Exempted Supply</option>
                            <option value="non gst supply">Non-GST Supply</option>
                            <option value="0">0 %</option>
                            <option value="0.1">0.1 %</option>
                            <option value="0.25">0.25 %</option>
                            <option value="1">1 %</option>
                            <option value="1.5">1.5 %</option>
                            <option value="3">3 %</option>
                            <option value="5">5 %</option>
                            <option value="7.5">7.5 %</option>
                            <option value="12">12 %</option>
                            <option value="18">18 %</option>
                            <option value="28">28 %</option>
                        </select>
                    </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                <input type="text" id="net_price1" name="net_price" class="did-floating-input net-price-input modal-input" data-modal="services" value="<?php echo $row['net_price'];?>" readonly>
                        <label for="net_price" class="did-floating-label">Net Price|GST</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                <input type="text" id="sac_code" name="sac_code" class="did-floating-input modal-input sac-code-input" data-modal="services" placeholder="" value="<?php echo $row['SAC_Code'];?>" >
                        <label for="sac_code" class="did-floating-label">SAC Code</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-6">
                    <div class="did-floating-label-content">
                        <input type="number" id="cess_amount1" name="cess_amount" class="did-floating-input modal-input cess-amt-input" data-modal="services" placeholder="" value="<?php echo ($row['cess_amt'])?>" >
                        <label for="cess_amount" class="did-floating-label">CESS Amount</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-6">
                    <div class="did-floating-label-content">
                        <input type="number" id="non_taxable" name="non_taxable" step="0.01" class="did-floating-input non-taxable-input modal-input" data-modal="services" placeholder="" value="<?php echo ($row['non_taxable'])?>">
                        <label for="non_taxable" class="did-floating-label">Non Taxable</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-12">
                    <div class="did-floating-label-content">
                        <textarea id="description" name="description" class="did-floating-input modal-input decrsiption-input" placeholder="" style="height:100px;padding:11px;"><?php echo $row['description']?></textarea>
                        <label for="description" class="did-floating-label">Description</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>


    <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
   



<script src="assets/js/myscript.js"></script>


</body>

</html>