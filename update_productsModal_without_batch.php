<?php
session_start();  
// Check if the user is logged in
if(!isset($_SESSION['LOG_IN'])){
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if(!isset($_SESSION['business_id'])){
    header("Location:dashboard.php");
    exit();
} else {
 // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
        // Branch-specific code or logic here
    } 
}
include("config.php");

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

   </script>
