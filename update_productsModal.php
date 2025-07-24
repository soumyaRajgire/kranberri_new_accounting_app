<?php
session_start();  

// Check if the user is logged in
if (!isset($_SESSION['LOG_IN'])) {
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if (!isset($_SESSION['business_id'])) {
    header("Location:dashboard.php");
    exit();
} else {
    // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];

    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
    }
}
include("config.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Fetch product details from the inventory_master table
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
    
    // Fetch batch details for the given product_id from the product_batches table
    $batch_sql = "SELECT * FROM product_batches WHERE product_id = ?";
    $batch_stmt = $conn->prepare($batch_sql);
    $batch_stmt->bind_param("i", $id);
    $batch_stmt->execute();
    $batch_result = $batch_stmt->get_result();
    $batches = [];

    // Fetch all batches into an array
    while ($batch_row = $batch_result->fetch_assoc()) {
        $batches[] = $batch_row;
    }
    $batch_stmt->close();
}
?>


<div class="modal-header">
    <h5 class="modal-title" id="updateProductsModalLabel">Update Products</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    
          <form id="updateProductsForm" method="POST" action="update_productsdb.php">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="catlog_type" value="products">
        <input type="hidden" name="inventory_type" value="<?php echo $row['inventory_type']; ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="goods_name" name="goods_name" class="did-floating-input modal-input name-input" placeholder="" value="<?php echo $row['name']?>" required>
                                <label for="goods_name" class="did-floating-label">Goods Name</label>
                            </div>
                        </div>
                        <!-- Category Field with + Icon -->
                        <div class="mb-1 col-lg-6">
    <div class="did-floating-label-content">
        <div class="d-flex align-items-center">
            <select id="categoryDropdown" name="category" class="did-floating-select modal-select category-select" required>
                <option value="<?php echo $row['category']?>"><?php echo $row['category']?></option>
                <?php
                $categoryQuery = "SELECT category_name FROM categories";
$result1 = $conn->query($categoryQuery);
while ($row1 = $result1->fetch_assoc()) {
    ?>
   <option value="<?php echo $row1['category_name']?>"><?php echo $row1['category_name']?></option>
   <?php
}
                ?>
            </select>
            <label for="categoryDropdown" class="did-floating-label">Select Category</label>
            <!-- Category Icon Button -->
            <button type="button" class="btn btn-link p-0 ms-2" onclick="openAddCategoryModal()">
    <i class="bi bi-plus-circle" style="font-size: 1.5rem;"></i>
</button>

        </div>
    </div>
</div>
<!-- Company Name Field with + Icon -->
<div class="mb-1 col-lg-6">
    <div class="did-floating-label-content">
        <div class="d-flex align-items-center">
            <select id="companyDropdown" name="company_name" class="did-floating-select modal-select company-select" required>
                <option value="<?php echo $row['company_name']?>"><?php echo $row['company_name']?></option>
<?php
              $companyQuery = "SELECT company_name FROM companies";
$result2 = $conn->query($companyQuery);
while ($row2 = $result2->fetch_assoc()) {
    ?>
   <option value="<?php echo $row2['company_name']?>"><?php echo $row2['company_name']?></option>
   <?php
}
                ?>
            </select>
            <label for="companyDropdown" class="did-floating-label">Company Name</label>
            <button type="button" class="btn btn-link p-0 ms-2" onclick="openAddCompanyModal()">
    <i class="bi bi-plus-circle" style="font-size: 1.5rem;"></i>
</button>

        </div>
    </div>
</div>

                       
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="number" id="price" name="price" class="did-floating-input modal-input price-input" data-modal="products" placeholder="" value="<?php echo $row['price']?>" required>
                                <label for="price" class="did-floating-label">Price</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <select id="inclusive_gst" name="inclusive_gst" class="did-floating-select modal-select inclusive-gst-select" data-modal="products">
        <option value="inclusive of GST" <?php echo ($row['in_ex_gst'] == 'inclusive of GST') ? 'selected' : ''; ?>>Inclusive of GST</option>
<option value="exclusive of GST" <?php echo ($row['in_ex_gst'] == 'exclusive of GST') ? 'selected' : ''; ?>>Exclusive of GST</option>

                    </select>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                           <div class="did-floating-label-content">
                                <select id="gst_rate" name="gst_rate" class="did-floating-select modal-select gst-rate-input" data-modal="products">
                        <option value=""> - Please Select - </option>
                        <option value="nil rated" <?php if ($row['gst_rate'] === 'nil rated') echo 'selected'; ?>>Nil-Rated</option>
                        <option value="zero rated" <?php if ($row['gst_rate'] === 'zero rated') echo 'selected'; ?>>Zero-Rated</option>
                        <option value="exempted supply" <?php if ($row['gst_rate'] === 'exempted supply') echo 'selected'; ?>>Exempted Supply</option>
                        <option value="non gst supply" <?php if ($row['gst_rate'] === 'non gst supply') echo 'selected'; ?>>Non-GST Supply</option>
                        <option value="0" <?php if ($row['gst_rate'] === '0') echo 'selected'; ?>>0 %</option>
                        <option value="0.1" <?php if ($row['gst_rate'] === '0.1') echo 'selected'; ?>>0.1 %</option>
                        <option value="0.25" <?php if ($row['gst_rate'] === '0.25') echo 'selected'; ?>>0.25 %</option>
                        <option value="1" <?php if ($row['gst_rate'] === '1') echo 'selected'; ?>>1 %</option>
                        <option value="1.5" <?php if ($row['gst_rate'] === '1.5') echo 'selected'; ?>>1.5 %</option>
                        <option value="3" <?php if ($row['gst_rate'] === '3') echo 'selected'; ?>>3 %</option>
                        <option value="5" <?php if ($row['gst_rate'] === '5') echo 'selected'; ?>>5 %</option>
                        <option value="7.5" <?php if ($row['gst_rate'] === '7.5') echo 'selected'; ?>>7.5 %</option>
                        <option value="12" <?php if ($row['gst_rate'] === '12') echo 'selected'; ?>>12 %</option>
                        <option value="18" <?php if ($row['gst_rate'] === '18') echo 'selected'; ?>>18 %</option>
                        <option value="28" <?php if ($row['gst_rate'] === '28') echo 'selected'; ?>>28 %</option>
                    </select>
                      <label for="gst_rate" class="did-floating-label">GST Rate</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="number" id="non_taxable1" name="non_taxable" step="0.01" class="did-floating-input non-taxable-input modal-input" data-modal="products" value="<?php echo $row['non_taxable']?>" placeholder="" oninput="calculateCess()">
                                <label for="non_taxable" class="did-floating-label">Non Taxable</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="net_price1" name="net_price" class="did-floating-input net-price-input modal-input" data-modal="products" value="<?php echo $row['net_price'];?>" readonly>
                                <label for="net_price" class="did-floating-label">Net Price|GST</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="hsn_code1" name="hsn_code" class="did-floating-input modal-input hsn-code-input" data-modal="products" value="<?php echo $row['hsn_code']?>" placeholder="">
                                <label for="hsn_code" class="did-floating-label">HSN Code</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select class="did-floating-select modal-select units-select" data-modal="products" name="units" id="units1" >
                                   <?php
    // Array of unit options
    $units = [
        "BAG-BAGS", "BAL-BALE", "BDL-BUNDLES", "BKL-BUCKLES",
        "BOU-BILLIONS OF UNITS", "BOX-BOX", "BTL-BOTTLES", "BUN-BUNCHES",
        "CAN-CANS", "CBM-CUBIC METERS", "CCM-CUBIC CENTIMETERS", "CMC-CENTIMETERS",
        "CTN-CARTONS", "DOZ-DOZENS", "DRM-DRUMS", "GGK-GREAT GROSS",
        "GMS-GRAMMES", "GRS-GROSS", "GYD-GROSS YARDS", "KGS-KILOGRAMS",
        "KLR-KILOLITRE", "KME-KILOMETRE", "MLT-MILILITRE", "MTR-METERS",
        "MTS-METRIC TON", "NOS-NUMBERS", "OTH-OTHERS", "PAC-PACKS",
        "PCS-PIECES", "PRS-PAIRS", "QTL-QUINTAL", "ROL-ROLLS",
        "SET-SETS", "SQF-SQUARE FEET", "SQM-SQUARE METERS", "SQY-SQUARE YARDS",
        "TBS-TABLETS", "TGM-TEN GROSS", "THD-THOUSANDS", "TON-TONNES",
        "TUB-TUBES", "UGS-US GALLONS", "UNT-UNITS", "YDS-YARDS"
    ];

    // Generate options dynamically
    foreach ($units as $unit) {
        // Check if this unit matches the selected unit from the database
        $selected = ($unit === $selected_unit) ? 'selected' : '';
        echo "<option value=\"$unit\" $selected>$unit</option>";
    }
    ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
    <div class="did-floating-label-content">
    <input type="number"  id="cess_rate1"   name="cess_rate"   class="did-floating-input modal-input cess-amt-input"  placeholder="" data-modal="products" value="<?php echo $row['cess_rate']?>">
    <label for="cess_rate" class="did-floating-label">CESS Rate%</label>
    </div>
</div>

<div class="mb-1 col-lg-3">
    <div class="did-floating-label-content">
    <input type="number" id="cess_amount1" name="cess_amount" class="did-floating-input modal-input cess_amount-input" placeholder="" step="0.01" data-modal="products" value="<?php echo $row['cess_amt']?>" readonly>
    <label for="cess_amount" class="did-floating-label">CESS Amount</label>
    </div>
</div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="number" id="sku1" name="sku" class="did-floating-input modal-input sku-input" placeholder="" value="<?php echo $row['sku']?>" data-modal="products">
                                <label for="sku" class="did-floating-label">SKU</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="number" id="opening_stock" name="opening_stock" class="did-floating-input modal-input" value="<?php echo $row['opening_stock']?>" placeholder="" >
                                <label for="opening_stock" class="did-floating-label">Opening Stock</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="date" id="opening_stockdate" name="opening_stockdate" class="did-floating-input modal-input" value="<?php echo $row['opening_stockdate']?>">
                                <label for="opening_stockdate" class="did-floating-label">Opening Stock Date</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="min_stockalert" name="min_stockalert" class="did-floating-input modal-input" placeholder="" value="<?php echo $row['min_stockalert']?>" >
                                <label for="min_stockalert" class="did-floating-label">Min Stock Alert</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="max_stockalert" name="max_stockalert" class="did-floating-input modal-input" placeholder="" value="<?php echo $row['max_stockalert']?>">
                                <label for="max_stockalert" class="did-floating-label">Max Stock Alert</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3" id="canBeSoldContainer">
    <div class="text-center">
        <label class="form-check-label">
            <input type="checkbox" id="can_be_sold" name="can_be_sold" class="form-check-input" 
                value="1" <?php echo ($row['can_be_sold'] == 1) ? 'checked' : ''; ?>>
            Can be Sold
        </label>
    </div>
</div>

                      <!-- Barcode Field (Manual Entry or Auto-Generate) -->
<!-- Barcode input and generation button -->
<div class="mb-1 col-lg-7" id="barcodeFieldBlock">
    <div class="mb-1 did-floating-label-content  d-flex">
        <input type="text" id="barcode" name="barcode" class="did-floating-input modal-input barcode-input" placeholder="">
        <label for="barcode" class="did-floating-label">Barcode</label>
        <!-- Barcode Image Display -->
     <img id="barcodeImage" style="width:25%"></img>  <!-- Barcode image will be drawn here -->
     <input type="file" id="barcodeimage" name="barcodeimage" hidden />

    </div>
    <button type="button" id="generateBarcodeButton" class="btn btn-info btn-sm" onclick="generateBarcode()">Generate Barcode</button> 
</div>
<!-- Maintain Batch Checkbox -->
                <div class="mb-1 col-lg-3" id="maintainBatchContainer">
    <div class="text-center">
        <label class="form-check-label">
            <input type="checkbox" id="maintain_batch" name="maintain_batch" class="form-check-input" onchange="toggleBatchFields()">
            Maintain Batch
        </label>
    </div>
</div>

                <!-- Batch Fields -->
                <div class="mb-1 col-lg-12" id="batchFieldsContainer" style="display: <?php echo ($batches) ? 'block' : 'none'; ?>">
                    <label><strong>Batch Details</strong></label>
                    <div id="batchFieldWrapper">
                        <?php
                        // Generate batch rows for editing if there are existing batches
                        foreach ($batches as $batch) {
                            echo "
                            <div class='row batch-row mb-2 border p-2 rounded'>
                                <div class='mb-1 col-lg-3'>
                                    <div class='did-floating-label-content'>
                                        <input type='text' name='batch_no[]' class='did-floating-input modal-input' placeholder='Batch No' value='{$batch['batch_no']}' required>
                                        <label for='batch_no' class='did-floating-label'>Batch Number</label>
                                    </div>
                                </div>
                                <div class='mb-1 col-lg-3'>
                                    <div class='did-floating-label-content'>
                                        <select name='manufacturer[]' class='did-floating-select modal-select units-select' required>
                                            <option value='{$batch['manufacturer']}'>{$batch['manufacturer']}</option>
                                            <!-- Add other manufacturers dynamically as needed -->
                                        </select>
                                        <label for='manufacturer' class='did-floating-label'>Select Manufacturer</label>
                                    </div>
                                </div>
                                <div class='mb-1 col-lg-3'>
                                    <div class='did-floating-label-content'>
                                        <input type='date' name='mfg_date[]' class='did-floating-input modal-input' value='{$batch['mfg_date']}' required>
                                        <label for='mfg_date' class='did-floating-label'>Mfg Date</label>
                                    </div>
                                </div>
                                <div class='mb-1 col-lg-3'>
                                    <div class='did-floating-label-content'>
                                        <input type='date' name='exp_date[]' class='did-floating-input modal-input' value='{$batch['exp_date']}' required>
                                        <label for='exp_date' class='did-floating-label'>Exp Date</label>
                                    </div>
                                </div>
                                <div class='mb-1 col-lg-3'>
                                    <div class='did-floating-label-content'>
                                        <input type='number' name='batch_price[]' class='did-floating-input modal-input batch-price-input' value='{$batch['batch_price']}' onchange='handleCalculation()' placeholder='' required>
                                        <label for='batch_price' class='did-floating-label'>Price</label>
                                    </div>
                                </div>
                                <div class='mb-1 col-lg-3'>
                                    <div class='did-floating-label-content'>
                                        <input type='number' name='batch_non_taxable_price[]' class='did-floating-input modal-input batch-price-input' value='{$batch['batch_non_taxable']}' onchange='handleCalculation()' placeholder=''>
                                        <label for='batch_non_taxable_price' class='did-floating-label'>Non Taxable</label>
                                    </div>
                                </div>
                                <div class='mb-1 col-lg-3'>
                                    <div class='did-floating-label-content'>
                                        <input type='text' name='batch_net_price[]' class='did-floating-input batch-net-price-input modal-input' value='{$batch['batch_net_price']}' readonly>
                                        <label for='net_price' class='did-floating-label'>Net Price|GST</label>
                                    </div>
                                </div>
                                <div class='mb-1 col-lg-3'>
                                    <div class='did-floating-label-content'>
                                        <input type='number' name='batch_cess_amount[]' class='did-floating-input modal-input batch-cess-amount-input' value='{$batch['batch_cess_amt']}' step='0.01' readonly>
                                        <label for='cess_amount' class='did-floating-label'>CESS Amount</label>
                                    </div>
                                </div>
                                <div class='mb-1 col-lg-3'>
                                    <div class='did-floating-label-content'>
                                        <input type='number' name='batch_opening_stock[]' class='did-floating-input modal-input' value='{$batch['opening_stock']}' placeholder=''>
                                        <label for='batch_opening_stock' class='did-floating-label'>Opening Stock</label>
                                    </div>
                                </div>
                                <div class='mb-1 col-lg-3'>
                                    <div class='did-floating-label-content'>
                                        <input type='date' name='batch_opening_stockdate[]' class='did-floating-input modal-input' value='{$batch['opening_stockdate']}'>
                                        <label for='batch_opening_stockdate' class='did-floating-label'>Opening Stock Date</label>
                                    </div>
                                </div>

                                                    <!-- Barcode Input and Image -->
                            <div class='mb-1 col-lg-3'>
                                <div class='did-floating-label-content d-flex'>
                                    <input type='text' name='batch_barcode[]' class='did-floating-input modal-input barcode-input' placeholder='Batch Barcode' value='{$batch['barcode_no']}' >
                                    <label for='batch_barcode' class='did-floating-label'>Barcode</label>
                                    <img class='batch-barcode-img' style='width: 30%;' value='{$batch['barcode_image']}' />
                                    <input type='file' name='batch_barcodeimage[]' id='batch_barcodeimage' hidden />

                                </div>
                                
                            </div>
                            </div>";
                        }
                        ?>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addBatchRow()">+ Add Batch</button>
                </div>
                        <div class="mb-1 col-lg-12">
                            <div class="did-floating-label-content">
                                <textarea id="description1" name="description" class="did-floating-input modal-input description-input" data-modal="products" placeholder="" style="height:100px;padding:11px;" value="<?php echo $row['description']?>"><?php echo $row['description']?></textarea>
                                <label for="description" class="did-floating-label">Description</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
    
    
    
    
</div>
<script>
    // $(document).ready(function () {

    //     function calculatePrices() {
    //         var price = parseFloat($("#price").val()) || 0;
    //         var gstRate = parseFloat($("#gst_rate").val()) || 0;
    //         var inclusiveGst = $("#inclusive_gst").val();
    //         var nonTaxable = parseFloat($("#non_taxable").val()) || 0;
    //          var cessRate = parseFloat($("#cess_rate").val()) || 0;
    // var cessAmountField = parseFloat($("#cess_amount").val()) || 0;
   
    //         var netPriceField = $("#net_price");
    //         var gstAmount = 0;
    //         var netPrice = 0;

            
    //            if (inclusiveGst === "inclusive of GST" && price > 0) {
    //     var gstAmount = (price / (1 + gstRate / 100)) * (gstRate / 100);
    //     var cessAmount = gstAmount * (cessRate / 100);
    //     var netPrice = price - gstAmount - nonTaxable;
    //     netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
    //     cessAmountField.val(cessAmount.toFixed(2));
    // } else if (inclusiveGst === "exclusive of GST" && price > 0) {
    //     var netPrice = price - nonTaxable;
    //      var gstAmount = (netPrice * gstRate) / 100;
    //     var cessAmount = gstAmount * (cessRate / 100);
    //      cessAmount = cessAmount.toFixed(2);
    //     netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
    //     cessAmountField.val(cessAmount);
    // } else {
    //     netPriceField.val("");
    //     cessAmountField.val("");
    // }
    
    //         netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
    //     }

    //     $("#price, #gst_rate, #inclusive_gst, #non_taxable,#cess_rate").on("input", calculatePrices);
    //     calculatePrices(); // Initial call to set the values based on loaded data
    // });
    $(document).ready(function () {
    function calculatePrices() {
        var price = parseFloat($("#price").val()) || 0;
        var gstRate = parseFloat($("#gst_rate").val()) || 0;
        var inclusiveGst = $("#inclusive_gst").val();
        var nonTaxable = parseFloat($("#non_taxable").val()) || 0;
        var cessRate = parseFloat($("#cess_rate").val()) || 0;

        var cessAmountField = $("#cess_amount");
        var netPriceField = $("#net_price");

        var gstAmount = 0;
        var cessAmount = 0;
        var netPrice = 0;

if (inclusiveGst === "inclusive of GST" && price > 0) {
    let taxablePrice;
    let gstAmount;
    let cessAmount;

    if (nonTaxable > 0) {
        // Subtract non-taxable amount first
        taxablePrice = (price - nonTaxable) / (1 + gstRate / 100);
        gstAmount = (taxablePrice) * (gstRate / 100);
        cessAmount = taxablePrice * (cessRate / 100);
    } else {
        // Direct calculation for inclusive GST
        taxablePrice = price / (1 + gstRate / 100);
        gstAmount = taxablePrice * (gstRate / 100);
        cessAmount = taxablePrice * (cessRate / 100);
    }

    // Round values to 2 decimal places
    taxablePrice = taxablePrice.toFixed(2);
    gstAmount = gstAmount.toFixed(2);
    cessAmount = cessAmount.toFixed(2);

    // Update the fields
    netPriceField.val(`${taxablePrice} | ${gstAmount}`);
    cessAmountField.val(cessAmount);
} else if (inclusiveGst === "exclusive of GST" && price > 0) {
    let taxablePrice;
    let gstAmount;
    let cessAmount;

    if (nonTaxable > 0) {
        // Subtract non-taxable amount
        taxablePrice = price - nonTaxable;
        gstAmount = (taxablePrice * gstRate) / 100;
        cessAmount = (taxablePrice * cessRate) / 100;
    } else {
        // Direct calculation for exclusive GST
        taxablePrice = price;
        gstAmount = (taxablePrice * gstRate) / 100;
        cessAmount = (taxablePrice * cessRate) / 100;
    }

    // Round values to 2 decimal places
    taxablePrice = taxablePrice.toFixed(2);
    gstAmount = gstAmount.toFixed(2);
    cessAmount = cessAmount.toFixed(2);

    // Update the fields
    netPriceField.val(`${taxablePrice} | ${gstAmount}`);
    cessAmountField.val(cessAmount);
} else {
    // Clear fields if price is invalid
    netPriceField.val("");
    cessAmountField.val("");
}
        // if (inclusiveGst === "inclusive of GST" && price > 0) {
        //     // Calculate GST and Net Price for inclusive GST
        //     netPrice = price / (1 + gstRate / 100);
        //     gstAmount = price - netPrice;
        //     cessAmount = netPrice * (cessRate / 100);
        // } 
        // else if (inclusiveGst === "exclusive of GST" && price > 0) {
        //     // Calculate GST and Cess for exclusive GST
        //     netPrice = price - nonTaxable;
        //     gstAmount = (netPrice * gstRate) / 100;
        //     cessAmount = (netPrice * cessRate) / 100;
        // } 
        // else {
        //     // Clear fields if price or conditions are invalid
        //     netPriceField.val("");
        //     cessAmountField.val("");
        //     return;
        // }

        // // Update fields with formatted values
        // netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
        // cessAmountField.val(cessAmount.toFixed(2));
    }

    // Trigger calculation on input changes
    $("#price, #gst_rate, #inclusive_gst, #non_taxable, #cess_rate").on("input", calculatePrices);

    // Initial call to set values based on pre-loaded data
    calculatePrices();
});

</script>
   <script>
    $(document).ready(function () {
    var inventoryType = $('#inventoryType').val();
    console.log(inventoryType);
    if (inventoryType === "Purchased Items") {

        $("#canBeSoldContainer").show(); 
    } else {
        $("#canBeSoldContainer").hide();
    }

    $("#inventoryType").change(function () {

        var inventoryType = $(this).val();
        console.log(inventoryType);
        if (inventoryType === "Purchased Items") {
            $("#canBeSoldContainer").show();
        } else {
            $("#canBeSoldContainer").hide();
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
        // Check if batch data exists for the product
        const batchesExist = <?php echo !empty($batches) ? 'true' : 'false'; ?>;
        const maintainBatchCheckbox = document.getElementById("maintain_batch");

        // If batches exist, check the "Maintain Batch" checkbox
        if (batchesExist) {
            maintainBatchCheckbox.checked = true;
        }

        // Trigger the function to toggle the batch fields visibility based on the checkbox state
        toggleBatchFields();

        // Dynamically populate the batch rows with data if necessary
        <?php if (!empty($batches)): ?>
            const batchData = <?php echo json_encode($batches); ?>;
            batchData.forEach(batch => {
                addBatchRow(batch); // Dynamically populate the batch rows with data
            });
        <?php endif; ?>

        // Attach event listener to the "Maintain Batch" checkbox to toggle fields
        maintainBatchCheckbox.addEventListener("change", toggleBatchFields);
    });
function toggleBatchFields() {
    const batchContainer = document.getElementById("batchFieldsContainer");
    const maintainBatchCheckbox = document.getElementById("maintain_batch");

    // Fields to hide/show based on batch selection
    const priceFieldBlock = document.getElementById("priceFieldBlock");
    const netPriceFieldBlock = document.getElementById("netpriceFieldBlock");
    const cessAmountFieldBlock = document.getElementById("cessamtFieldBlock");
    const nonTaxableFieldBlock = document.getElementById("nontaxableFieldBlock");
    const barcodeFieldBlock = document.getElementById("barcodeFieldBlock");
    const openingStockFieldBlock = document.getElementById("openingStockFieldBlock");
    const openingStockdateFieldBlock = document.getElementById("openingStockdateFieldBlock");

    const priceInput = document.getElementById("price1");

    if (maintainBatchCheckbox.checked) {
        // Show the batch fields and hide certain main form fields
        batchContainer.style.display = "block";
        // Automatically add the first batch row if none exists
        if (document.querySelectorAll(".batch-row").length === 0) {
            addBatchRow(); 
        }

        // Hide certain fields when "Maintain Batch" is checked
        priceFieldBlock.style.display = "none";
        priceInput.removeAttribute("required"); 

        // Hide additional fields related to price, stock, etc.
        netPriceFieldBlock.style.display = "none";
        cessAmountFieldBlock.style.display = "none";
        nonTaxableFieldBlock.style.display = "none";
        barcodeFieldBlock.style.display = "none";
        openingStockFieldBlock.style.display = "none";
        openingStockdateFieldBlock.style.display = "none";
    } else {
        // Hide batch fields and show main form fields
        batchContainer.style.display = "none";
        document.getElementById("batchFieldWrapper").innerHTML = ""; // Clear all batch rows

        // Show the hidden fields when "Maintain Batch" is unchecked
        priceFieldBlock.style.display = "block";
        netPriceFieldBlock.style.display = "block";
        cessAmountFieldBlock.style.display = "block";
        nonTaxableFieldBlock.style.display = "block";
        barcodeFieldBlock.style.display = "block";
        openingStockFieldBlock.style.display = "block";
        openingStockdateFieldBlock.style.display = "block";
    }
}
document.addEventListener("DOMContentLoaded", function() {
    // Get references to the form fields
    const manufacturerField = document.getElementById("manufacturer");
    const mfgDateField = document.getElementById("mfg_date");
    const expDateField = document.getElementById("exp_date");
    const batchNoField = document.getElementById("batch_no");

    // Function to generate batch number
    function generateBatchNumber() {
        const manufacturer = manufacturerField ? manufacturerField.value : '';
        const mfgDate = mfgDateField ? mfgDateField.value : '';
        const expDate = expDateField ? expDateField.value : '';

        if (manufacturer && mfgDate && expDate) {
            // Format: Manufacturer-MfgDate-ExpDate
            const batchNumber = manufacturer.substring(0, 3).toUpperCase() + '-' + mfgDate + '-' + expDate;
            batchNoField.value = batchNumber; // Set the batch number in the input field
        } else {
            batchNoField.value = ''; // Clear the batch number if any field is empty
        }
    }

    // Validate Mfg Date and Exp Date
    function validateDates() {
        const currentDate = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
        const mfgDate = mfgDateField ? mfgDateField.value : '';
        const expDate = expDateField ? expDateField.value : '';

        // Check if Mfg Date is not in the future
        if (mfgDate && mfgDate > currentDate) {
            alert("Manufacture date cannot be in the future.");
            mfgDateField.value = ''; // Reset the field
            return false;
        }

        // Check if Exp Date is later than Mfg Date
        if (mfgDate && expDate && expDate <= mfgDate) {
            alert("Expiry date must be later than Manufacture date.");
            expDateField.value = ''; // Reset the Exp Date field
            return false;
        }

        return true;
    }

    // Event listeners for changes in the fields
    if (manufacturerField) {
        manufacturerField.addEventListener("change", function() {
            generateBatchNumber(); // Generate batch number whenever manufacturer is selected
        });
    }

    if (mfgDateField) {
        mfgDateField.addEventListener("change", function() {
            validateDates(); // Validate dates when Mfg Date is changed
            generateBatchNumber(); // Generate batch number whenever Mfg Date is selected
        });
    }

    if (expDateField) {
        expDateField.addEventListener("change", function() {
            validateDates(); // Validate dates when Exp Date is changed
            generateBatchNumber(); // Generate batch number whenever Exp Date is selected
        });
    }

    // Toggle Batch Fields visibility based on "Maintain Batch" checkbox
    document.getElementById("maintain_batch")?.addEventListener("change", function() {
        const batchContainer = document.getElementById("batchFieldsContainer");
        if (this.checked) {
            batchContainer.style.display = "block";
            if (document.querySelectorAll(".batch-row").length === 0) {
                addBatchRow(); // Add the first batch row if not already added
            }
        } else {
            batchContainer.style.display = "none";
            document.getElementById("batchFieldWrapper").innerHTML = ""; // Clear all batch rows
        }
    });
});

function addBatchRow(batch = {}) {
    const html = `
    <div class="row batch-row mb-2 border p-2 rounded">
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="text" name="batch_no[]" class="did-floating-input modal-input" placeholder="Batch No" value="${batch.batch_no || ''}" required>
                <label for="batch_no" class="did-floating-label">Batch Number</label>
            </div>
        </div>
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <select name="manufacturer[]" data-modal="products" class="did-floating-select modal-select units-select" required>
                    <option value="">Select Manufacturer</option>
                    <?php
                        $res = $conn->query("SELECT customerName FROM customer_master WHERE contact_type='Manufacturer'");
                        while ($row = $res->fetch_assoc()) {
                            echo "<option value='{$row['customerName']}'>{$row['customerName']}</option>";
                        }
                    ?>
                </select>
                <label for="manufacturer" class="did-floating-label">Select Manufacturer</label>
            </div>
        </div>
        
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="date" name="mfg_date[]" class="did-floating-input modal-input" required>
                <label for="mfg_date" class="did-floating-label">Mfg Date</label>
            </div>
        </div>

        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="date" name="exp_date[]" class="did-floating-input modal-input" required>
                <label for="exp_date" class="did-floating-label">Exp Date</label>
            </div>
        </div>

        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="number" name="batch_price[]" class="did-floating-input modal-input batch-price-input" onchange="handleCalculation()" placeholder="" required>
                <label for="batch_price" class="did-floating-label">Price</label>
            </div>
        </div>

        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="number" name="batch_non_taxable_price[]" class="did-floating-input modal-input batch-price-input" onchange="handleCalculation()" placeholder="">
                <label for="batch_non_taxable_price" class="did-floating-label">Non Taxable</label>
            </div>
        </div>
      
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="text" name="batch_net_price[]" class="did-floating-input batch-net-price-input modal-input" readonly>
                <label for="net_price" class="did-floating-label">Net Price|GST</label>
            </div>
        </div>

        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="number" name="batch_cess_amount[]" class="did-floating-input modal-input batch-cess-amount-input" placeholder="" step="0.01" readonly>
                <label for="cess_amount" class="did-floating-label">CESS Amount</label>
            </div>
        </div>
                       
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="number" name="batch_opening_stock[]" class="did-floating-input modal-input" placeholder="">
                <label for="batch_opening_stock" class="did-floating-label">Opening Stock</label>
            </div>
        </div>
        
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="date" name="batch_opening_stockdate[]" class="did-floating-input modal-input" >
                <label for="batch_opening_stockdate" class="did-floating-label">Opening Stock Date</label>
            </div>
        </div>

        <!-- Barcode Input and Image -->
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content d-flex">
                <input type="text" name="batch_barcode[]" class="did-floating-input modal-input barcode-input" placeholder="Batch Barcode" >
                <label for="batch_barcode" class="did-floating-label">Barcode</label>
                <img class="batch-barcode-img" style="width: 30%;" />
                    <input type="file" name="batch_barcodeimage[]" id="batch_barcodeimage" hidden />

            </div>
            <button type="button" class="btn btn-info btn-sm" onclick="generateBatchBarcode(this)">Generate Barcode</button>
        </div>

        <!-- Delete icon (trash) for removing the batch row -->
        <div class="col-lg-12 text-end">
            <button type="button" class="btn btn-sm btn-danger" onclick="deleteBatchRow(this)">
                <i class="bi bi-trash" style="font-size: 1.25rem;"></i> Delete
            </button>
        </div>
    </div>`;

    // Add the batch input row to the batch container
    document.getElementById("batchFieldWrapper").insertAdjacentHTML('beforeend', html);
    
    // After adding a new row, set up event listeners
    const lastRow = document.querySelectorAll('.batch-row');
    const newRow = lastRow[lastRow.length - 1];
    const priceInput = newRow.querySelector('input[name="batch_price[]"]');
    priceInput.addEventListener('input', function () {
        calculateBatchRow(this);
    });
}





// Function to delete a batch row
function deleteBatchRow(button) {
    // Find the parent batch-row of the clicked button and remove it
    const batchRow = button.closest('.batch-row');
    batchRow.remove();

    // Check if no rows are left
    const batchRows = document.querySelectorAll(".batch-row");
    if (batchRows.length === 0) {
        // Uncheck the "Maintain Batch" checkbox
        document.getElementById("maintain_batch").checked = false;

        // Hide the batch fields container
        document.getElementById("batchFieldsContainer").style.display = "none";
    }
}
// function toggleBatchFields() {
//     const batchContainer = document.getElementById("batchFieldsContainer");
//     const maintainBatchCheckbox = document.getElementById("maintain_batch");

//     if (maintainBatchCheckbox.checked) {
//         batchContainer.style.display = "block";
//         // Automatically add the first batch row if none exists
//         if (document.querySelectorAll(".batch-row").length === 0) {
//             addBatchRow(); 
//         }
//     } else {
//         batchContainer.style.display = "none";
//         document.getElementById("batchFieldWrapper").innerHTML = ""; // Clear all batch rows
//     }
// }


   </script>
