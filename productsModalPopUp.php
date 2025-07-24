  <?php
// session_start(); 
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

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $catlog_type = mysqli_real_escape_string($conn,$_POST['catlog_type']);
    $inventory_type = mysqli_real_escape_string($conn,$_POST['inventory_type']);
    $name= mysqli_real_escape_string($conn, $_POST['goods_name']);
    $price= mysqli_real_escape_string($conn, $_POST['price']);
    $inclusive_gst= mysqli_real_escape_string($conn, $_POST['inclusive_gst']);
    $gst_rate= mysqli_real_escape_string($conn, $_POST['gst_rate']);
    $non_taxable = mysqli_real_escape_string($conn, $_POST['non_taxable']);
    $net_price = mysqli_real_escape_string($conn, $_POST['net_price']);
    $hsn_code = mysqli_real_escape_string($conn, $_POST['hsn_code']);
    $units = mysqli_real_escape_string($conn, $_POST['units']);
    $cess_amount = mysqli_real_escape_string($conn, $_POST['cess_amount']);
    $sku = mysqli_real_escape_string($conn, $_POST['sku']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $sac_code = mysqli_real_escape_string($conn, $_POST['sac_code']);
$created_by = $_SESSION['name'];
    

    $sql = "INSERT INTO inventory_master (inventory_type, catlog_type,name,price,in_ex_gst,gst_rate,non_taxable,net_price,hsn_code,SAC_Code,units,cess_amt,sku,description,created_by)
                VALUES (?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);

// Specify the correct data types for each parameter in the bind_param call
$stmt->bind_param("sssssssssssssss", $inventory_type, $catlog_type, $name,$price,$inclusive_gst,$gst_rate,$non_taxable,$net_price,$hsn_code,$sac_code,$units,$cess_amount,$sku,$description,$created_by);

if ($stmt->execute() === TRUE) {

    ?>
<script>
    alert("Data inserted Successfully");
    // window.location.href="manage-products.php?type=<?php echo $inventory_type?>";
</script>
    <?php

} else {
    echo "Error inserting inventory: " . $stmt->error;
}




}

?>

  <div id="addProductsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Products</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>             
            </div>
            
    <form action="" method="POST">
        <input type="hidden" name="catlog_type" id="catlog_type" value="products" class="modal-input catlog-type-input" data-modal="products">
        <input type="hidden" name="inventory_type" id="inventory_type" value="" class="modal-input inventory-type-input">
        <div class="modal-body">
            <div class="row">
                <div class="mb-1 col-lg-6">
                <div class="did-floating-label-content">
                <input type="text" id="goods_name" name="goods_name" class="did-floating-input modal-input name-input" data-modal="products" placeholder="" autocomplete="off" >
                <label for="goods_name" class="did-floating-label">Goods Name</label>
                </div>
                </div>
                   
                <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                <input type="text" id="price2" name="price" class="did-floating-input modal-input price-input" data-modal="products" placeholder="" required>
                <label for="price" class="did-floating-label">Price</label>
                </div>
                </div>

                <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                <select id="inclusive_gst2" name="inclusive_gst" class="did-floating-select modal-select inclusive-gst-select" data-modal="products">
                <option value="inclusive of GST" selected>Inclusive of GST</option>
                <option value="exclusive of GST">Exclusive of GST</option>
                </select>
                </div>
                </div>

                <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                <select  id="gst_rate2" name="gst_rate" class="did-floating-select modal-select gst-rate-input" data-modal="products"> 
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
                    <option value="18">18 %</option> <option value="28">28 %</option> 
                </select>
                </div>
                </div>    

                <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                <input type="number" id="non_taxable2" name="non_taxable" step="0.01" class="did-floating-input non-taxable-input modal-input" data-modal="products" placeholder="" >
                <label for="non_taxable" class="did-floating-label">Non Taxable</label>
                </div>
                </div>
                    
                <div class="mb-1 col-lg-3">
                <div class="did-floating-label-content">
                <input type="text" id="net_price2" name="net_price" class="did-floating-input net-price-input modal-input" data-modal="products" readonly >
                <label for="net_price" class="did-floating-label">Net Price|GST</label>
                </div>
                </div>
                        
            <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
            <input type="text" id="hsn_code2" name="hsn_code" class="did-floating-input modal-input hsn-code-input" data-modal="products" placeholder="">
            <label for="hsn_code" class="did-floating-label">HSN Code</label>
            </div>
            </div>
               
             <div class="mb-1 col-lg-6">
            <div class="did-floating-label-content">
                <select class="did-floating-select modal-select units-select " data-modal="products" name="units" id="units1"> <option vlaue="BAG-BAGS">BAG-BAGS</option> <option vlaue="BAL-BALE">BAL-BALE</option> <option vlaue="BDL-BUNDLES">BDL-BUNDLES</option> <option vlaue="BKL-BUCKLES">BKL-BUCKLES</option> <option vlaue="BOU-BILLIONS OF UNITS">BOU-BILLIONS OF UNITS</option> <option vlaue="BOX-BOX">BOX-BOX</option> <option vlaue="BTL-BOTTLES">BTL-BOTTLES</option> <option vlaue="BUN-BUNCHES">BUN-BUNCHES</option> <option vlaue="CAN-CANS">CAN-CANS</option> <option vlaue="CBM-CUBIC METERS">CBM-CUBIC METERS</option> <option vlaue="CCM-CUBIC CENTIMETERS">CCM-CUBIC CENTIMETERS</option> <option vlaue="CMC-CENTIMETERS">CMC-CENTIMETERS</option> <option vlaue="CTN-CARTONS">CTN-CARTONS</option> <option vlaue="DOZ-DOZENS">DOZ-DOZENS</option> <option vlaue="DRM-DRUMS">DRM-DRUMS</option> <option vlaue="GGK-GREAT GROSS">GGK-GREAT GROSS</option> <option vlaue="GMS-GRAMMES">GMS-GRAMMES</option> <option vlaue="GRS-GROSS">GRS-GROSS</option> <option vlaue="GYD-GROSS YARDS">GYD-GROSS YARDS</option> <option vlaue="KGS-KILOGRAMS">KGS-KILOGRAMS</option> <option vlaue="KLR-KILOLITRE">KLR-KILOLITRE</option> <option vlaue="KME-KILOMETRE">KME-KILOMETRE</option> <option vlaue="MLT-MILILITRE">MLT-MILILITRE</option> <option vlaue="MTR-METERS">MTR-METERS</option> <option vlaue="MTS-METRIC TON">MTS-METRIC TON</option> <option vlaue="NOS-NUMBERS">NOS-NUMBERS</option> <option vlaue="OTH-OTHERS">OTH-OTHERS</option> <option vlaue="PAC-PACKS">PAC-PACKS</option> <option vlaue="PCS-PIECES">PCS-PIECES</option> <option vlaue="PRS-PAIRS">PRS-PAIRS</option> <option vlaue="QTL-QUINTAL">QTL-QUINTAL</option> <option vlaue="ROL-ROLLS">ROL-ROLLS</option> <option vlaue="SET-SETS">SET-SETS</option> <option vlaue="SQF-SQUARE FEET">SQF-SQUARE FEET</option> <option vlaue="SQM-SQUARE METERS">SQM-SQUARE METERS</option> <option vlaue="SQY-SQUARE YARDS">SQY-SQUARE YARDS</option> <option vlaue="TBS-TABLETS">TBS-TABLETS</option> <option vlaue="TGM-TEN GROSS">TGM-TEN GROSS</option> <option vlaue="THD-THOUSANDS">THD-THOUSANDS</option> <option vlaue="TON-TONNES">TON-TONNES</option> <option vlaue="TUB-TUBES">TUB-TUBES</option> <option vlaue="UGS-US GALLONS">UGS-US GALLONS</option> <option vlaue="UNT-UNITS" selected="">UNT-UNITS</option> <option vlaue="YDS-YARDS">YDS-YARDS </option> </select>
            </div>
             </div>      
            <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
            <input type="number" id="cess_amount1" name="cess_amount" class="did-floating-input modal-input cess-amt-input" placeholder="" data-modal="products">
            <label for="cess_amount" class="did-floating-label">CESS Amount</label>
            </div>
            </div>        

            <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
            <input type="number" id="sku1" name="sku" class="did-floating-input modal-input sku-input" placeholder="" data-modal="products">
            <label for="sku" class="did-floating-label">SKU</label>
            </div>
            </div>          

            <div class="mb-1 col-lg-12">
            <div class="did-floating-label-content">
            <textarea  id="description2" name="description" class="did-floating-input modal-input description-input" data-modal="products" placeholder="" style="height:100px;padding:11px;"></textarea>
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
                </div>
            </div>
        <!-- </div> -->


