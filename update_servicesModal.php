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
    <h5 class="modal-title" id="updateServicesModalLabel">Update Services</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="updateServicesForm" method="POST" action="update_productsdb.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="catlog_type" value="services">
        <input type="hidden" name="inventory_type" value="<?php echo $row['inventory_type']; ?>">
        <div class="row">
            <!-- Form fields for updating services -->
            <div class="mb-1 col-lg-6">
                <div class="did-floating-label-content">
                    <input type="text" id="goods_name" name="goods_name" class="did-floating-input modal-input name-input" placeholder="" value="<?php echo $row['name']?>" required>
                    <label for="goods_name" class="did-floating-label">Service Name</label>
                </div>
            </div>
            <!-- More form fields -->
            <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                    <input type="text" id="price" name="price" class="did-floating-input modal-input price-input" data-modal="services" placeholder="" value="<?php echo $row['price']?>">
                    <label for="price" class="did-floating-label">Price</label>
                </div>
            </div>
            <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                    <select id="inclusive_gst" name="inclusive_gst" class="did-floating-select modal-select inclusive-gst-select" data-modal="services">
                       <option value="inclusive of GST" <?php echo ($row['in_ex_gst'] == 'inclusive of GST') ? 'selected' : ''; ?>>Inclusive of GST</option>
<option value="exclusive of GST" <?php echo ($row['in_ex_gst'] == 'exclusive of GST') ? 'selected' : ''; ?>>Exclusive of GST</option>
                    </select>
                </div>
            </div>
            <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                    <select id="gst_rate" name="gst_rate" class="did-floating-select modal-select gst-rate-input" data-modal="services">
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
                      <label for="gst_rate" class="did-floating-label">GST Rate to be Applied </label>
                </div>
            </div>
            <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                    <input type="number" step="0.01" id="non_taxable" name="non_taxable" class="did-floating-input modal-input non-taxable-input" data-modal="services" placeholder="" value="<?php echo $row['non_taxable']?>">
                    <label for="non_taxable" class="did-floating-label">Non Taxable</label>
                </div>
            </div>
            <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                    <input type="text" id="net_price" name="net_price" class="did-floating-input modal-input net-price-input" data-modal="services" value="<?php echo $row['net_price'];?>" readonly>
                    <label for="net_price" class="did-floating-label">Net Price</label>
                </div>
            </div>
          
            <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                    <input type="text" id="sac_code" name="sac_code" class="did-floating-input sac-code-input modal-input" data-modal="services" placeholder="" value="<?php echo $row['SAC_Code']?>">
                    <label for="sac_code" class="did-floating-label">SAC Code</label>
                </div>
            </div>
          
           <div class="mb-1 col-lg-3">
    <div class="did-floating-label-content">
    <input type="number"  id="cess_rate"  name="cess_rate"  class="did-floating-input modal-input cess-amt-input" value="<?php echo $row["cess_rate"]?>"  placeholder=""  data-modal="services" step="any">
    <label for="cess_rate" class="did-floating-label">CESS Rate%</label>
    </div>
</div>


            <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                    <input type="number" id="cess_amount" name="cess_amount" step="0.01" class="did-floating-input modal-input cess-amount-input" data-modal="services" placeholder="" value="<?php echo $row['cess_amt']?>" readonly>
                    <label for="cess_amount" class="did-floating-label">Cess Amount</label>
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
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<script>
$(document).ready(function () {
    function calculatePrices() {
        var price = parseFloat($("#price").val()) || 0;
        var gstRate = parseFloat($("#gst_rate").val()) || 0;
        var inclusiveGst = $("#inclusive_gst").val();
        var nonTaxable = parseFloat($("#non_taxable").val()) || 0;
        var netPriceField = $("#net_price");
        var cessRate = parseFloat($("#cess_rate").val()) || 0;
    var cessAmountField = $("#cess_amount");
        var gstAmount = 0;
        var netPrice = 0;
        
         if (inclusiveGst === "inclusive of GST" && price > 0) {
        var gstAmount = (price / (1 + gstRate / 100)) * (gstRate / 100);
        var cessAmount = gstAmount * (cessRate / 100);
        var netPrice = price - gstAmount - nonTaxable;
        netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
        cessAmountField.val(cessAmount.toFixed(2));
    } else if (inclusiveGst === "exclusive of GST" && price > 0) {
        var netPrice = price - nonTaxable;
         var gstAmount = (netPrice * gstRate) / 100;
        var cessAmount = gstAmount * (cessRate / 100);
         cessAmount = cessAmount.toFixed(2);
        netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
        cessAmountField.val(cessAmount);
    } else {
        netPriceField.val("");
        cessAmountField.val("");
    }
    
    

        // if (inclusiveGst === "inclusive of GST" && price > 0) {
        //     gstAmount = (price * gstRate) / (100 + gstRate);
        //       var cessAmount = gstAmount * (cessRate / 100);
        //     netPrice = price - gstAmount - nonTaxable;
        // } else if (inclusiveGst === "exclusive of GST" && price > 0) {
        //     gstAmount = (price * gstRate) / 100;
        //     netPrice = price + gstAmount - nonTaxable;
        // }
        
        netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
    }

    $("#price, #gst_rate, #inclusive_gst, #non_taxable,#cess_rate").on("input", calculatePrices);
    calculatePrices(); // Initial call to set the values based on loaded data
});
</script>
