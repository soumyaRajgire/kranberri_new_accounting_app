<div id="addProductsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Products</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>             
            </div>
            <form id="productsForm" action="productsdb.php" method="POST">
                <input type="hidden" name="catlog_type" value="products">
                <input type="hidden" name="inventory_type" id="inventory_type_products" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="goods_name" name="goods_name" class="did-floating-input modal-input name-input" placeholder="" required>
                                <label for="goods_name" class="did-floating-label">Goods Name</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="number" id="price" name="price" class="did-floating-input modal-input price-input" data-modal="products" placeholder="" required>
                                <label for="price" class="did-floating-label">Price</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <select id="inclusive_gst" name="inclusive_gst" class="did-floating-select modal-select inclusive-gst-select" data-modal="products" required>
                                    <option value="inclusive of GST" selected>Inclusive of GST</option>
                                    <option value="exclusive of GST">Exclusive of GST</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <select id="gst_rate" name="gst_rate" class="did-floating-select modal-select gst-rate-input" data-modal="products" required>
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
                                <input type="number" id="non_taxable1" name="non_taxable" step="0.01" class="did-floating-input non-taxable-input modal-input" data-modal="products" placeholder="">
                                <label for="non_taxable" class="did-floating-label">Non Taxable</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="net_price1" name="net_price" class="did-floating-input net-price-input modal-input" data-modal="products" readonly>
                                <label for="net_price" class="did-floating-label">Net Price|GST</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="hsn_code1" name="hsn_code" class="did-floating-input modal-input hsn-code-input" data-modal="products" placeholder="">
                                <label for="hsn_code" class="did-floating-label">HSN Code</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select class="did-floating-select modal-select units-select" data-modal="products" name="units" id="units1" required>
                                    <option value="BAG-BAGS">BAG-BAGS</option>
                                    <option value="BAL-BALE">BAL-BALE</option>
                                    <option value="BDL-BUNDLES">BDL-BUNDLES</option>
                                    <option value="BKL-BUCKLES">BKL-BUCKLES</option>
                                    <option value="BOU-BILLIONS OF UNITS">BOU-BILLIONS OF UNITS</option>
                                    <option value="BOX-BOX">BOX-BOX</option>
                                    <option value="BTL-BOTTLES">BTL-BOTTLES</option>
                                    <option value="BUN-BUNCHES">BUN-BUNCHES</option>
                                    <option value="CAN-CANS">CAN-CANS</option>
                                    <option value="CBM-CUBIC METERS">CBM-CUBIC METERS</option>
                                    <option value="CCM-CUBIC CENTIMETERS">CCM-CUBIC CENTIMETERS</option>
                                    <option value="CMC-CENTIMETERS">CMC-CENTIMETERS</option>
                                    <option value="CTN-CARTONS">CTN-CARTONS</option>
                                    <option value="DOZ-DOZENS">DOZ-DOZENS</option>
                                    <option value="DRM-DRUMS">DRM-DRUMS</option>
                                    <option value="GGK-GREAT GROSS">GGK-GREAT GROSS</option>
                                    <option value="GMS-GRAMMES">GMS-GRAMMES</option>
                                    <option value="GRS-GROSS">GRS-GROSS</option>
                                    <option value="GYD-GROSS YARDS">GYD-GROSS YARDS</option>
                                    <option value="KGS-KILOGRAMS">KGS-KILOGRAMS</option>
                                    <option value="KLR-KILOLITRE">KLR-KILOLITRE</option>
                                    <option value="KME-KILOMETRE">KME-KILOMETRE</option>
                                    <option value="MLT-MILILITRE">MLT-MILILITRE</option>
                                    <option value="MTR-METERS">MTR-METERS</option>
                                    <option value="MTS-METRIC TON">MTS-METRIC TON</option>
                                    <option value="NOS-NUMBERS">NOS-NUMBERS</option>
                                    <option value="OTH-OTHERS">OTH-OTHERS</option>
                                    <option value="PAC-PACKS">PAC-PACKS</option>
                                    <option value="PCS-PIECES">PCS-PIECES</option>
                                    <option value="PRS-PAIRS">PRS-PAIRS</option>
                                    <option value="QTL-QUINTAL">QTL-QUINTAL</option>
                                    <option value="ROL-ROLLS">ROL-ROLLS</option>
                                    <option value="SET-SETS">SET-SETS</option>
                                    <option value="SQF-SQUARE FEET">SQF-SQUARE FEET</option>
                                    <option value="SQM-SQUARE METERS">SQM-SQUARE METERS</option>
                                    <option value="SQY-SQUARE YARDS">SQY-SQUARE YARDS</option>
                                    <option value="TBS-TABLETS">TBS-TABLETS</option>
                                    <option value="TGM-TEN GROSS">TGM-TEN GROSS</option>
                                    <option value="THD-THOUSANDS">THD-THOUSANDS</option>
                                    <option value="TON-TONNES">TON-TONNES</option>
                                    <option value="TUB-TUBES">TUB-TUBES</option>
                                    <option value="UGS-US GALLONS">UGS-US GALLONS</option>
                                    <option value="UNT-UNITS" selected="">UNT-UNITS</option>
                                    <option value="YDS-YARDS">YDS-YARDS</option>
                                </select>
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
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="number" id="opening_stock" name="opening_stock" class="did-floating-input modal-input" placeholder="" required>
                                <label for="opening_stock" class="did-floating-label">Opening Stock</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="date" id="opening_stockdate" name="opening_stockdate" class="did-floating-input modal-input" required>
                                <label for="opening_stockdate" class="did-floating-label">Opening Stock Date</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="min_stockalert" name="min_stockalert" class="did-floating-input modal-input" placeholder="" required>
                                <label for="min_stockalert" class="did-floating-label">Min Stock Alert</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="max_stockalert" name="max_stockalert" class="did-floating-input modal-input" placeholder="" required>
                                <label for="max_stockalert" class="did-floating-label">Max Stock Alert</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-12">
                            <div class="did-floating-label-content">
                                <textarea id="description1" name="description" class="did-floating-input modal-input description-input" data-modal="products" placeholder="" style="height:100px;padding:11px;"></textarea>
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

<script>
    document.getElementById('productsForm').addEventListener('submit', function (e) {
        var requiredFields = ['goods_name', 'price', 'inclusive_gst', 'gst_rate', 'units'];
        var isValid = true;
        
        requiredFields.forEach(function(field) {
            var input = document.getElementById(field);
            if (!input || input.value.trim() === '') {
                input.style.border = '1px solid red';
                isValid = false;
            } else {
                input.style.border = '';
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill out all mandatory fields.');
        }
    });
    
</script>
