
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>

<?php

// {
 // Set up variables for selected business and branch
    // $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    // Check if a specific branch is selected
    // if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
        // Branch-specific code or logic here
    // } 
?>
<div id="addProductsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Products</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>

            </div>
            <form id="productsForm" action="productsdb.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="catlog_type" value="products">
                <input type="hidden" name="inventory_type" id="inventory_type_products" value="">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-1 col-lg-6">
                            <div class="did-floating-label-content">
                                <input type="text" id="goods_name1" name="goods_name" class="did-floating-input modal-input name-input" placeholder="" required>
                                <label for="goods_name" class="did-floating-label">Goods Name</label>
                            </div>
                        </div>
                        <!--raw material -->

                         <div class="mb-1 col-lg-6" id="rawmaterialContainer" style="display: none;">
    <div class="did-floating-label-content">
        <div class="d-flex align-items-center">
            <select id="rawMaterialDropdown" name="rawmaterial" class="did-floating-select modal-select" data-modal="products">
                <option value="">Select Raw Material</option>
                   <?php
    // Example query to fetch raw materials
    $sql = "SELECT id, name FROM inventory_master WHERE inventory_type = 'Raw Material'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
        }
    }
    ?>
            </select>
            <label for="rawMaterialDropdown" class="did-floating-label">Select Raw Material</label>
            <!-- Category Icon Button -->
            <!-- <button type="button" class="btn btn-link p-0 ms-2" onclick="openAddCategoryModal()">
    <i class="bi bi-plus-circle" style="font-size: 1.5rem;"></i>
</button> -->

        </div>
    </div>
</div>
                        <!-- Category Field with + Icon -->
                        <div class="mb-1 col-lg-6">
    <div class="did-floating-label-content">
        <div class="d-flex align-items-center">
            <select id="categoryDropdown" name="category" class="did-floating-select modal-select category-select" data-modal="products"required>
                <option value="">Category</option>
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
                <option value="">Select Company</option>
            </select>
            <label for="companyDropdown" class="did-floating-label">Company Name</label>
            <button type="button" class="btn btn-link p-0 ms-2" onclick="openAddCompanyModal()">
    <i class="bi bi-plus-circle" style="font-size: 1.5rem;"></i>
</button>

        </div>
    </div>
</div>

                        <div class="mb-1 col-lg-3" id="priceFieldBlock">
                            <div class="did-floating-label-content">
                                <input type="number" id="price1" name="price1" class="did-floating-input modal-input price-input" data-modal="products" oninput="handleCalculation()" placeholder="" required>
                                <label for="price" class="did-floating-label">Price</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
    <div class="did-floating-label-content">
        <select id="inclusive_gst1" name="inclusive_gst1" data-modal="products" class="did-floating-select modal-select inclusive-gst-select" data-modal="products" onchange="handleCalculation()" required>
            <option value="inclusive of GST">Inclusive of GST</option>
            <option value="exclusive of GST">Exclusive of GST</option>
        </select>
    </div>
</div>

                        <div class="mb-1 col-lg-3">
                           <div class="did-floating-label-content">
                                <select id="gst_rate1" name="gst_rate1" class="did-floating-select modal-select gst-rate-input" data-modal="products" onchange="handleCalculation()" required>
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
                                 <label for="gst_rate" class="did-floating-label">GST Rate</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3" id="nontaxableFieldBlock">
                            <div class="did-floating-label-content">
                                <input type="number" id="non_taxable1" name="non_taxable1" step="0.01" class="did-floating-input non-taxable-input modal-input" data-modal="products" placeholder="" onchange="handleCalculation()">
                                <label for="non_taxable" class="did-floating-label">Non Taxable</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3" id="netpriceFieldBlock">
                            <div class="did-floating-label-content">
                                <input type="text" id="net_price1" name="net_price" class="did-floating-input net-price-input modal-input" data-modal="products" readonly>
                                <label for="net_price" class="did-floating-label">Net Price|GST</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="hsn_code1" name="hsn_code" class="did-floating-input modal-input hsn-code-input" data-modal="products" placeholder="" required>
                                <label for="hsn_code1" class="did-floating-label">HSN Code</label>
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
    <input type="number"  id="cess_rate1"   name="cess_rate"   class="did-floating-input modal-input cess-amt-input"  placeholder="" data-modal="products" oninput="handleCalculation()">
    <label for="cess_rate" class="did-floating-label">CESS Rate%</label>
    </div>
</div>

<div class="mb-1 col-lg-3" id="cessamtFieldBlock">
    <div class="did-floating-label-content">
    <input type="number" id="cess_amount1" name="cess_amount" class="did-floating-input modal-input cess_amount-input" placeholder="" step="0.01" data-modal="products" readonly>
    <label for="cess_amount" class="did-floating-label">CESS Amount</label>
    </div>
</div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="number" id="sku1" name="sku" class="did-floating-input modal-input sku-input" placeholder="" data-modal="products">
                                <label for="sku1" class="did-floating-label">SKU</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3" id="openingStockFieldBlock">
                            <div class="did-floating-label-content">
                                <input type="number" id="opening_stock" name="opening_stock" class="did-floating-input modal-input" placeholder="" >
                                <label for="opening_stock" class="did-floating-label">Opening Stock</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3" id="openingStockdateFieldBlock">
                            <div class="did-floating-label-content">
                                <input type="date" id="opening_stockdate" name="opening_stockdate" class="did-floating-input modal-input" >
                                <label for="opening_stockdate" class="did-floating-label">Opening Stock Date</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="min_stockalert" name="min_stockalert" class="did-floating-input modal-input" placeholder="" >
                                <label for="min_stockalert" class="did-floating-label">Min Stock Alert</label>
                            </div>
                        </div>
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text" id="max_stockalert" name="max_stockalert" class="did-floating-input modal-input" placeholder="" >
                                <label for="max_stockalert" class="did-floating-label">Max Stock Alert</label>
                            </div>
                        </div>

                        <div class="mb-1 col-lg-3" id="designNoFieldBlock">
                            <div class="did-floating-label-content">
                                <input type="text" id="design_no" name="design_no" class="did-floating-input modal-input design_no-input" data-modal="products" placeholder="" >
                                <label for="design_no" class="did-floating-label">Design No</label>
                            </div>
                        </div>
                            <div class="mb-1 col-lg-3" id="colorFieldBlock">
                            <div class="did-floating-label-content">
                                <input type="text" id="color" name="color" class="did-floating-input modal-input color-input" data-modal="products" placeholder="" >
                                <label for="color" class="did-floating-label">Color</label>
                            </div>
                        </div>

                        <div class="mb-1 col-lg-3" id="sizeFieldBlock">
                            <div class="did-floating-label-content">
                                <input type="text" id="size" name="size" class="did-floating-input modal-input size-input" data-modal="products" placeholder="" >
                                <label for="size" class="did-floating-label">Size</label>
                            </div>
                        </div>

                        <div class="mb-1 col-lg-3" id="canBeSoldContainer" style="display: none;">
                            <div class="text-center">
                                <label class="form-check-label">
                                    <input type="checkbox" id="can_be_sold" name="can_be_sold" class="form-check-input">
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

<div class="mb-1 col-lg-2" id="maintainBatchContainer">
    <div class="text-center">
        <label class="form-check-label">
            <input type="checkbox" id="maintain_batch" name="maintain_batch" class="form-check-input" onchange="toggleBatchFields()">
            Maintain Batch
        </label>
    </div>
</div>

<!-- Batch Fields Container - Initially hidden -->
<div class="mb-1 col-lg-12" id="batchFieldsContainer" style="display: none;">
    <label><strong>Batch Details</strong></label>
    <div id="batchFieldWrapper"></div>
    <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addBatchRow()">+ Add Batch</button>
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

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>             
            </div>
            <div class="modal-body">
            <form id="addCategoryForm" data-modal-type="category" novalidate>                    
                <div class="mb-3">
                        <label for="newCategory" class="form-label">Category Name</label>
                        <input type="text" id="newCategory" name="name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Company Modal -->
<div class="modal fade" id="addCompanyModal" tabindex="-1" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCompanyModalLabel">Add Company Name</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>             
            </div>
            <div class="modal-body">
            <form id="addCompanyForm" data-modal-type="company" novalidate>
                    <div class="mb-3">
                        <label for="newCompanyName" class="form-label">Company Name</label>
                        <input type="text" id="newCompanyName" name="name" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function openAddCategoryModal() {
        const addCategoryModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
        addCategoryModal.show();
    }
</script>
<script>
    function openAddCompanyModal() {
        const addCompanyModal = new bootstrap.Modal(document.getElementById('addCompanyModal'));
        addCompanyModal.show();
    }
</script>

<script>
    document.getElementById('productsForm').addEventListener('submit', function (e) {
        var requiredFields = ['goods_name', 'price1', 'inclusive_gst1', 'gst_rate1', 'units'];
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
    
    
document.addEventListener('DOMContentLoaded', function () {
    // Add event listener to the Add Category Form
    document.getElementById('addCategoryForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const categoryName = document.getElementById('newCategory').value.trim();
    if (categoryName) {
        submitModalForm('category', categoryName, this);
    } else {
        alert('Category name cannot be empty.');
    }
});
    // Add event listener to the Add Company Form
    document.getElementById('addCompanyForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const companyName = document.getElementById('newCompanyName').value.trim();
    if (companyName) {
        submitModalForm('company', companyName, this);
    } else {
        alert('Company name cannot be empty.');
    }
});

    // Function to handle form submission via AJAX
    function submitModalForm(type, name, form) {
        fetch('saveCategoryCompany.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `type=${encodeURIComponent(type)}&name=${encodeURIComponent(name)}`
})
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(`${type === 'category' ? 'Category' : 'Company'} added successfully.`);
            const dropdown = document.getElementById(type === 'category' ? 'categoryDropdown' : 'companyDropdown');
            const option = new Option(data.name, data.name);
            dropdown.add(option);
            dropdown.value = data.name;
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById(type === 'category' ? 'addCategoryModal' : 'addCompanyModal')).hide();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        // alert('An error occurred. Please try again.');
    });
}

    // Fetch data and populate dropdowns on page load
    fetch('fetch_data.php')
        .then(response => response.json())
        .then(data => {
            const categoryDropdown = document.getElementById('categoryDropdown');
            const companyDropdown = document.getElementById('companyDropdown');

            // Populate Categories
            data.categories.forEach(category => {
                const option = new Option(category.name, category.name);
                categoryDropdown.add(option);
            });

            // Populate Companies
            data.companies.forEach(company => {
                const option = new Option(company.name, company.name);
                companyDropdown.add(option);
            });
        })
        .catch(error => console.error('Error fetching data:', error));
});

</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">


<script>
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

// Function to add a new batch row dynamically
/*function addBatchRow() {
    const html = `
    <div class="row batch-row mb-2 border p-2 rounded">
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="text" name="batch_no[]" class="did-floating-input modal-input" placeholder="Batch No" required>
                <label for="batch_no" class="did-floating-label">Batch Number</label>
            </div>
        </div>
        
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <select name="manufacturer[]" data-modal="products" class="did-floating-select modal-select units-select" required>
                    <option value="">Select Manufacturer</option>
                    <?php
                        // PHP code to fetch manufacturers from your database
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
                <input type="date" data-modal="products" name="mfg_date[]" class="did-floating-input modal-input" required>
                <label for="mfg_date" class="did-floating-label">Mfg Date</label>
            </div>
        </div>

        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="date" data-modal="products" name="exp_date[]" class="did-floating-input modal-input" required>
                <label for="exp_date" class="did-floating-label">Exp Date</label>
            </div>
        </div>
        

      
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="number" id="batch_price" name="batch_price[]" class="did-floating-input modal-input batch-price-input" data-modal="products" onchange="handleCalculation()" placeholder=""  required>
                        <label for="batch_price" class="did-floating-label">Price</label>
            </div>
        </div>
        
          <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="number" id="batch_non_taxable_price" name="batch_non_taxable_price[]" class="did-floating-input modal-input batch-price-input" data-modal="products" onchange="handleCalculation()" placeholder=""  required>
                        <label for="batch_non_taxable_price" class="did-floating-label">Non Taxable</label>
            </div>
        </div>
      
                        
                      
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="text"  name="batch_net_price[]" class="did-floating-input batch-net-price-input modal-input" data-modal="products" readonly>
                                <label for="net_price" class="did-floating-label">Net Price|GST</label>
                            </div>
                        </div>

                    

<div class="mb-1 col-lg-3">
    <div class="did-floating-label-content">
    <input type="number"  name="batch_cess_amount[]" class="did-floating-input modal-input batch-cess-amount-input" placeholder="" step="0.01" data-modal="products" readonly>
    <label for="cess_amount" class="did-floating-label">CESS Amount</label>
    </div>
</div>
                      
                        <div class="mb-1 col-lg-3">
                            <div class="did-floating-label-content">
                                <input type="number" data-modal="products" name="opening_stock[]" class="did-floating-input modal-input" placeholder="" > 
                                <label for="opening_stock" class="did-floating-label">Opening Stock</label>
                            </div>
                        </div>
<!-- Barcode Input and Image -->
        <div class="mb-1 col-lg-3">
            <div class="mb-1 did-floating-label-content  d-flex">
                <input type="text" name="batch_barcode[]" class="did-floating-input modal-input barcode-input" placeholder="Batch Barcode" readonly>
                <label for="batch_barcode" class="did-floating-label">Barcode</label>
                     <img id="batchbarcodeImage" class="batch-barcode-img" style="width:25%"></img>
            </div>
            <button type="button" id="generateBatchBarcodeButton" class="btn btn-info btn-sm" onclick="generateBatchBarcode(this)">Generate Barcode</button>
            <br><br>
           
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
*/
 $branch_id = $_SESSION['branch_id'];

function addBatchRow() {

    const html = `
    <div class="row batch-row mb-2 border p-2 rounded">
        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="text" name="batch_no[]" class="did-floating-input modal-input" placeholder="Batch No" required>
                <label for="batch_no" class="did-floating-label">Batch Number</label>
            </div>
        </div>

        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <select name="manufacturer[]" data-modal="products" class="did-floating-select modal-select units-select" required>
                    <option value="">Select Manufacturer</option>
                    <?php
                        // PHP code to fetch manufacturers from your database
                        $res = $conn->query("SELECT customerName FROM customer_master WHERE contact_type='Manufacturer' AND  branch_id='$branch_id'");
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
                <input type="date" name="mfg_date[]" class="did-floating-input modal-input" >
                <label for="mfg_date" class="did-floating-label">Mfg Date</label>
            </div>
        </div>

        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="date" name="exp_date[]" class="did-floating-input modal-input" >
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

        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="text" name="batch_designno[]" class="did-floating-input modal-input" placeholder="Design No">
                <label for="batch_designno" class="did-floating-label">Design No</label>
            </div>
        </div>

        <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="text" name="batch_color[]" class="did-floating-input modal-input" >
                <label for="batch_color" class="did-floating-label">Color</label>
            </div>
        </div>

         <div class="mb-1 col-lg-3">
            <div class="did-floating-label-content">
                <input type="text" name="batch_size[]" class="did-floating-input modal-input" >
                <label for="batch_size" class="did-floating-label">Size</label>
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
function toggleBatchFields() {
    const batchContainer = document.getElementById("batchFieldsContainer");
    const maintainBatchCheckbox = document.getElementById("maintain_batch");

    // Get the blocks to hide/show
    const priceFieldBlock = document.getElementById("priceFieldBlock");
    const netPriceFieldBlock = document.getElementById("netpriceFieldBlock");
    const cessAmountFieldBlock = document.getElementById("cessamtFieldBlock");
    const nonTaxableFieldBlock = document.getElementById("nontaxableFieldBlock");
    const barcodeFieldBlock = document.getElementById("barcodeFieldBlock");
    const openingStockFieldBlock = document.getElementById("openingStockFieldBlock");
    const openingStockdateFieldBlock = document.getElementById("openingStockdateFieldBlock");
const priceInput = document.getElementById("price1");

    if (maintainBatchCheckbox.checked) {
        batchContainer.style.display = "block";
        // Automatically add the first batch row if none exists
        if (document.querySelectorAll(".batch-row").length === 0) {
            addBatchRow(); 
        }
              // Hide the price input and make it non-required
        priceFieldBlock.style.display = "none";
        priceInput.removeAttribute("required"); 

        // Hide the entire blocks when "Maintain Batch" is checked
        priceFieldBlock.style.display = "none";
        netPriceFieldBlock.style.display = "none";
        cessAmountFieldBlock.style.display = "none";
        nonTaxableFieldBlock.style.display = "none";
        barcodeFieldBlock.style.display ="none";
        openingStockFieldBlock.style.display ="none";
        openingStockdateFieldBlock.style.display ="none";
          colorFieldBlock.style.display ="none";
         sizeFieldBlock.style.display ="none";
         designNoFieldBlock.style.display="none";
    } else {
        batchContainer.style.display = "none";
        document.getElementById("batchFieldWrapper").innerHTML = ""; // Clear all batch rows

        // Show the blocks when "Maintain Batch" is unchecked
        priceFieldBlock.style.display = "block";
        netPriceFieldBlock.style.display = "block";
        cessAmountFieldBlock.style.display = "block";
        nonTaxableFieldBlock.style.display = "block";
        barcodeFieldBlock.style.display = "block";
         openingStockFieldBlock.style.display ="block";
         openingStockdateFieldBlock.style.display = "block";
           colorFieldBlock.style.display ="block";
         sizeFieldBlock.style.display ="block";
         designNoFieldBlock.style.display="block";
    }
}




</script>
