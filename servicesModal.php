<div id="addServicesModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Services</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="servicesForm" action="productsdb.php" method="POST">
                <input type="hidden" name="catlog_type" value="services">
                <input type="hidden" name="inventory_type" id="inventory_type_services" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="goods_name" name="goods_name" class="did-floating-input modal-input name-input" placeholder="" required>
                                <label for="goods_name" class="did-floating-label">Service Name</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="number" id="price" name="price" class="did-floating-input modal-input price-input" data-modal="services" placeholder="" required>
                                <label for="price" class="did-floating-label">Price</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <select id="inclusive_gst" name="inclusive_gst" class="did-floating-select modal-select inclusive-gst-select" data-modal="services" required>
                                    <option value="inclusive of GST">Inclusive of GST</option>
                                    <option value="exclusive of GST">Exclusive of GST</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <select id="gst_rate" name="gst_rate" class="did-floating-select modal-select gst-rate-input" data-modal="services" required>
                                    <!-- <option value=""> - Please Select - </option> -->
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
                                    <option value="18" selected>18 %</option>
                                    <option value="28">28 %</option>
                                </select>
                                   <label for="gst_rate" class="did-floating-label">GST Rate to be Applied </label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="net_price" name="net_price" class="did-floating-input net-price-input modal-input" data-modal="services" readonly>
                                <label for="net_price" class="did-floating-label">Net Price|GST</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="sac_code" name="sac_code" class="did-floating-input modal-input sac-code-input" placeholder="" data-modal="services">
                                <label for="sac_code" class="did-floating-label">SAC Code</label>
                            </div>
                        </div>
                          <!--</div>-->
                         <div class="mb-1 col-lg-3">
    <div class="did-floating-label-content">
    <input type="number"  id="cess_rate"   name="cess_rate"   class="did-floating-input modal-input cess-amt-input"  placeholder="" data-modal="services">
    <label for="cess_rate" class="did-floating-label">CESS Rate%</label>
    </div>
</div>

<div class="mb-1 col-lg-3">
    <div class="did-floating-label-content">
    <input type="number" id="cess_amount" name="cess_amount" class="did-floating-input modal-input cess_amount-input" placeholder="" step="0.01" data-modal="services" readonly>
    <label for="cess_amount" class="did-floating-label">CESS Amount</label>
    </div>
</div>
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="number" id="non_taxable" name="non_taxable" step="0.01" class="did-floating-input non-taxable-input modal-input" data-modal="services" placeholder="">
                                <label for="non_taxable" class="did-floating-label">Non Taxable</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-12">
                            <div class="did-floating-label-content">
                                <textarea id="description" name="description" class="did-floating-input modal-input description-input" placeholder="" style="height:100px;padding:11px;"></textarea>
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
    document.getElementById('servicesForm').addEventListener('submit', function (e) {
        var requiredFields = ['goods_name', 'price', 'inclusive_gst', 'gst_rate'];
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