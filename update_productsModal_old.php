<!DOCTYPE html>
<?php
session_start();  
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit;
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
include("config.php");
?>

<html lang="en">
<head>
    <title>Update Products</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
</head>

<body class="">
    <?php include("menu.php"); ?>

    <?php
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        $select_sql = "SELECT * FROM inventory_master WHERE id = ?";

        $select_stmt = $conn->prepare($select_sql);
        $select_stmt->bind_param("i", $id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
        } else {
            echo "Inventory item not found.";
            exit;
        }
        $select_stmt->close();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $catlog_type = mysqli_real_escape_string($conn, $_POST['catlog_type']);
        $inventory_type = mysqli_real_escape_string($conn, $_POST['inventory_type']);
        $name = mysqli_real_escape_string($conn, $_POST['goods_name']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $inclusive_gst = mysqli_real_escape_string($conn, $_POST['inclusive_gst']);
        $gst_rate = mysqli_real_escape_string($conn, $_POST['gst_rate']);
        $non_taxable = mysqli_real_escape_string($conn, $_POST['non_taxable']);
        $net_price = mysqli_real_escape_string($conn, $_POST['net_price']);
        $hsn_code = mysqli_real_escape_string($conn, $_POST['hsn_code']);
        $units = mysqli_real_escape_string($conn, $_POST['units']);
        $cess_amount = mysqli_real_escape_string($conn, $_POST['cess_amount']);
        $sku = mysqli_real_escape_string($conn, $_POST['sku']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $sac_code = mysqli_real_escape_string($conn, $_POST['sac_code']);
        $created_by = $_SESSION['name'];
    
        $update_sql = "UPDATE inventory_master SET
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
    
        $update_stmt = $conn->prepare($update_sql);
    
        if (!$update_stmt) {
            die("Prepare failed: " . $conn->error);
        }
    
        $update_stmt->bind_param("ssssssssssssssi", $inventory_type, $catlog_type, $name, $price, $inclusive_gst, $gst_rate, $non_taxable, $net_price, $hsn_code, $sac_code, $units, $cess_amount, $sku, $description, $id);
    
        $update_result = $update_stmt->execute();
    
        if (!$update_result) {
            die("Update inventory item failed: " . $update_stmt->error);
        }
    
        $update_stmt->close();
    
        if ($update_result) {
            ?>
            <script>
                alert("Data updated Successfully");
                window.location.href = "manage-products.php?type=<?php echo $inventory_type?>";
            </script>
            <?php
        } else {
            echo "Error updating inventory: " . $update_stmt->error;
        }
    }
    ?>

    <style>
        /* Custom CSS styles for the card */
        .custom-card {
            width: 1280px; /* Adjust the width as per your preference */
        height: 639px;
            margin-left: 235px;
        }
    </style>

    <div class="custom-card">
        <div class="card-header">
            <h4 class="card-title">Update Products</h4>
        </div>

        <div class="card-body table-border-style">
            <form action="update_productsModal.php" method="POST">
                <div class="card-body">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
           <input type="hidden" name="catlog_type" id="catlog_type" value="products" class="modal-input catlog-type-input" data-modal="products">

            <input type="hidden" name="inventory_type" id="inventory_type" value="<?php echo $row['inventory_type']; ?>" class="modal-input inventory-type-input">

            <div class="row">
                <div class="mb-1 col-lg-6">
                <div class="did-floating-label-content">
                  <input type="text" id="name" name="goods_name" class="did-floating-input modal-input name-input" data-modal="products" placeholder="" value="<?php echo ($row['name'])?>">
                  <label for="goods_name" class="did-floating-label">Goods Name</label>
                </div>

                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                    <input type="text" id="price" name="price" class="did-floating-input modal-input price-input" data-modal="products" placeholder="" value="<?php echo ($row['price'])?>">
                        <label for="price" class="did-floating-label">Price</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                        <select id="inclusive_gst" name="inclusive_gst" class="did-floating-select modal-select inclusive-gst-select" data-modal="products">
                        <option value="<?php echo $row['in_ex_gst']; ?>"><?php echo $row['in_ex_gst']; ?></option> 
                        <option value="inclusive of GST" selected>Inclusive of GST</option>
                            <option value="exclusive of GST">Exclusive of GST</option>
                        </select>
                    </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                        <select id="gst_rate" name="gst_rate" class="did-floating-select modal-select gst-rate-input" data-modal="products">
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
                            <option value="5" selected>5 %</option>
                            <option value="7.5">7.5 %</option>
                            <option value="12">12 %</option>
                            <option value="18">18 %</option>
                            <option value="28">28 %</option>
                        </select>
                    </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                        <input type="number" id="non_taxable1" name="non_taxable" step="0.01" class="did-floating-input non-taxable-input modal-input" data-modal="products" placeholder="" value="<?php echo ($row['non_taxable'])?>">
                        <label for="non_taxable" class="did-floating-label">Non Taxable</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                    <input type="text" id="net_price1" name="net_price" class="did-floating-input net-price-input modal-input" data-modal="products" value="<?php echo ($row['net_price'])?>" readonly>

                        <label for="net_price" class="did-floating-label">Net Price|GST</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                        <input type="text" id="hsn_code1" name="hsn_code" class="did-floating-input modal-input hsn-code-input" data-modal="products" placeholder="" value="<?php echo isset($row['hsn_code1'])?>">
                        <label for="hsn_code" class="did-floating-label">HSN Code</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-6">
                    <div class="did-floating-label-content">
                    <select class="did-floating-select modal-select units-select" data-modal="products" name="units" id="units1"> 
    <option value="<?php echo $row['units']; ?>"><?php echo $row['units']; ?></option> 
                        <option vlaue="BAG-BAGS">BAG-BAGS</option> <option vlaue="BAL-BALE">BAL-BALE</option> <option vlaue="BDL-BUNDLES">BDL-BUNDLES</option> <option vlaue="BKL-BUCKLES">BKL-BUCKLES</option> <option vlaue="BOU-BILLIONS OF UNITS">BOU-BILLIONS OF UNITS</option> <option vlaue="BOX-BOX">BOX-BOX</option> <option vlaue="BTL-BOTTLES">BTL-BOTTLES</option> <option vlaue="BUN-BUNCHES">BUN-BUNCHES</option> <option vlaue="CAN-CANS">CAN-CANS</option> <option vlaue="CBM-CUBIC METERS">CBM-CUBIC METERS</option> <option vlaue="CCM-CUBIC CENTIMETERS">CCM-CUBIC CENTIMETERS</option> <option vlaue="CMC-CENTIMETERS">CMC-CENTIMETERS</option> <option vlaue="CTN-CARTONS">CTN-CARTONS</option> <option vlaue="DOZ-DOZENS">DOZ-DOZENS</option> <option vlaue="DRM-DRUMS">DRM-DRUMS</option> <option vlaue="GGK-GREAT GROSS">GGK-GREAT GROSS</option> <option vlaue="GMS-GRAMMES">GMS-GRAMMES</option> <option vlaue="GRS-GROSS">GRS-GROSS</option> <option vlaue="GYD-GROSS YARDS">GYD-GROSS YARDS</option> <option vlaue="KGS-KILOGRAMS">KGS-KILOGRAMS</option> <option vlaue="KLR-KILOLITRE">KLR-KILOLITRE</option> <option vlaue="KME-KILOMETRE">KME-KILOMETRE</option> <option vlaue="MLT-MILILITRE">MLT-MILILITRE</option> <option vlaue="MTR-METERS">MTR-METERS</option> <option vlaue="MTS-METRIC TON">MTS-METRIC TON</option> <option vlaue="NOS-NUMBERS">NOS-NUMBERS</option> <option vlaue="OTH-OTHERS">OTH-OTHERS</option> <option vlaue="PAC-PACKS">PAC-PACKS</option> <option vlaue="PCS-PIECES">PCS-PIECES</option> <option vlaue="PRS-PAIRS">PRS-PAIRS</option> <option vlaue="QTL-QUINTAL">QTL-QUINTAL</option> <option vlaue="ROL-ROLLS">ROL-ROLLS</option> <option vlaue="SET-SETS">SET-SETS</option> <option vlaue="SQF-SQUARE FEET">SQF-SQUARE FEET</option> <option vlaue="SQM-SQUARE METERS">SQM-SQUARE METERS</option> <option vlaue="SQY-SQUARE YARDS">SQY-SQUARE YARDS</option> <option vlaue="TBS-TABLETS">TBS-TABLETS</option> <option vlaue="TGM-TEN GROSS">TGM-TEN GROSS</option> <option vlaue="THD-THOUSANDS">THD-THOUSANDS</option> <option vlaue="TON-TONNES">TON-TONNES</option> <option vlaue="TUB-TUBES">TUB-TUBES</option> <option vlaue="UGS-US GALLONS">UGS-US GALLONS</option> <option vlaue="UNT-UNITS" selected="">UNT-UNITS</option> <option vlaue="YDS-YARDS">YDS-YARDS </option> </select>
                                            </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                        <input type="number" id="cess_amount1" name= "cess_amount" class="did-floating-input modal-input cess-amt-input" placeholder="" data-modal="products" value="<?php echo isset($row['cess_amount1'])?>">
                        <label for="cess_amount" class="did-floating-label">CESS Amount</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-3">
                    <div class="did-floating-label-content">
                        <input type="number" id="sku1" name="sku" class="did-floating-input modal-input sku-input" placeholder="" data-modal="products" value="<?php echo isset($row['sku1'])?>">
                        <label for="sku" class="did-floating-label">SKU</label>
                    </div>
                </div>
                <div class="mb-1 col-lg-12">
                    <div class="did-floating-label-content">
                        <textarea id="description" name="description" class="did-floating-input modal-input description-input" data-modal="products" style="height: 100px; padding: 11px;"></textarea>
                        <label for="description" class="did-floating-label">Description</label>
                    </div>
                </div>
            </div>
            
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="submit">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
        $(document).ready(function () {
            $(".modal-input, .modal-select").on("input", function () {
                var modalId = $(this).data("modal");
                calculatePrices(modalId);
            });

            function calculatePrices(modalId) {
            var price = parseFloat($(".modal-input.price-input[data-modal='" + modalId + "']").val()) || 0;
            var gstRate = parseFloat($(".modal-select.gst-rate-input[data-modal='" + modalId + "']").val()) || 0;
            var inclusiveGst = $(".modal-select.inclusive-gst-select[data-modal='" + modalId + "']").val();
            var nonTaxable = parseFloat($(".modal-input.non-taxable-input[data-modal='" + modalId + "']").val()) || 0;

            var netPriceField = $(".modal-input.net-price-input[data-modal='" + modalId + "']");

            if (inclusiveGst === "inclusive of GST" && price > 0) {
                var gstAmount = (price * gstRate) / (100);
                var netPrice = price - gstAmount - nonTaxable;
                netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
                console.log(netPrice);
            } else if (inclusiveGst === "exclusive of GST" && price > 0) {
                var gstAmount = (price * gstRate) / 100;
                var netPrice = price - nonTaxable;
                netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
                console.log(netPrice);
            } else {
                netPriceField.val("");
            }
        }
    });
</script>

    <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
   



<script src="assets/js/myscript.js"></script>
</body>

</html>
