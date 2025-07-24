<!DOCTYPE html>

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit();
} 

// Include database configuration
include("config.php");

// Include the PhpSpreadsheet library
require 'vendor/autoload.php'; // Ensure PhpSpreadsheet is installed via Composer
use PhpOffice\PhpSpreadsheet\IOFactory;

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Default response structure
$response = ['status' => 'error', 'message' => 'Something went wrong!'];

// Check if the request method is POST and file is uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['spreadsheet'])) {
    $fileTmpPath = $_FILES['spreadsheet']['tmp_name'];

    // Check if there was an issue with the file upload
    if ($_FILES['spreadsheet']['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'Error uploading file!';
        echo json_encode($response);
        exit();
    }

    try {
        // Load the Excel file
        $spreadsheet = IOFactory::load($fileTmpPath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Prepare SQL statement for database insertion
         // Prepare SQL statement for database insertion
         $stmt = $conn->prepare("INSERT INTO inventory_master (
            inventory_type, catlog_type, name, category, company_name,  price, in_ex_gst, gst_rate, 
            non_taxable, net_price, hsn_code, SAC_Code, units, cess_amt, sku, 
            opening_stock, opening_stockdate, min_stockalert, max_stockalert, sold_stock, 
            description, remark, created_by, created_on, last_updated_by, last_updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");



        foreach ($rows as $index => $row) {
            // Skip the header row
            if ($index === 0) continue;

            // Bind parameters and execute insert
            $stmt->bind_param(
                'sssssdidsdssdsssssssssssss',
                $row[1], $row[2], $row[3], $row[4], $row[5], $row[6],
                $row[7], $row[8], $row[9], $row[10], $row[11], $row[12],
                $row[13], $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21], $row[22], $row[23], $row[24], $row[25], $row[26]
            );
            $stmt->execute();
        } 
        
        // Success response
        $response['status'] = 'success';
        $response['message'] = 'File uploaded and data inserted successfully!';
    } catch (Exception $e) {
        // Catch any exception and return the error message
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>

<html lang="en">
<head>
    <title>iiiQbets</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <?php include("header_link.php");?>
   <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    
    
<style>
    #addOption {
    cursor: pointer;
}

</style>
</head>
<body class="">
    <!-- [ Pre-loader ] start -->
     
     <?php include("menu.php");?>
    
    
    <!-- [ Header ] end -->
    

    

<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <!-- <div class="page-header-title">
                            <h4 class="m-b-10">View Customers</h4>
                        </div> -->
                       <!--  <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Customers</a></li>
                            <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> 
                        </ul> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
  <div class="row">
            <div class="col-sm-9">
                <div class="card">
                    <div class="card-header">
                        <!-- <h5>View Customers Details</h5> -->
                        <div class="row">
    <div class="col-md-3">
        <select class="btn btn-info btn-sm" id="inventoryType">
            <option value="Sales Catalog" class="option">Sales Catalog</option>
            <option value="Purchased Items" class="option">Purchased Items</option>
            <option value="Raw Material" class="option">Raw Material</option>
        </select>
    </div>
    
   
    <div class="col-md-3">
        <div class="btn-group">
            <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ADD
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" data-value="products">Products</a>
                <a class="dropdown-item" href="#" data-value="services">Services</a>
            </div>
        </div>
        <input type="hidden" id="selectedOption">
    </div>
    <div class="col-md-6 text-right">
        <!-- Bulk Upload Button -->
       <!-- Bulk Upload Button -->
       <button type="button" class="btn btn-warning btn-sm" id="bulkUpload">Bulk Upload</button>
        <input type="file" id="uploadFileInput" style="display: none;" />


        <!-- Export Button -->
        <button type="button" class="btn btn-primary btn-sm" id="exportData">Export</button>
        
        
        <div class="row">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
    <div class="col-md-12">
        <!-- Sales Catalog Download Forms -->
        <div id="salesDownloadForms">
            
            <!--<form class="form-inline mb-2" method="POST" action="download_sales_monthly.php">
                <div class="input-group">
                    <select class="form-control" name="month" required>
                        <?php
                        //$current_month = date("m");
                       // for ($month = 1; $month <= 12; $month++) {
                          //  $selected = ($current_month == $month) ? 'selected' : '';
                         //   echo "<option value=\"$month\" $selected>" . date('F', mktime(0, 0, 0, $month, 1)) . "</option>";
                        //}
                        ?>
                    </select>
                    <select class="form-control" name="year" required>
                        <?php
                        //$current_year = date("Y");
                        //for ($year = $current_year; $year >= 2020; $year--) {
                          //  echo "<option value=\"$year\">$year</option>";
                        //}
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success btn-sm" name="download_month">
                            <i class="fa fa-download"></i> Monthly
                        </button>
                    </div>
                </div>
            </form>-->

            <!-- Sales Catalog Download Forms -->
           <!-- <form class="form-inline" method="POST" action="download_sales_range.php">
                <div class="input-group">
                    <input type="date" class="form-control" name="from_date" required 
                        value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
                    <input type="date" class="form-control" name="to_date" required 
                        value="<?php echo date('Y-m-d'); ?>">
                        
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success btn-sm" name="download_range">
                            <i class="fa fa-download"></i> Range
                        </button>
                    </div>
                </div>
            </form>-->

          
        </div>

        <!-- Purchase Items Download Forms -->
        <!--<div id="purchaseDownloadForms" style="display: none;">
            <form class="form-inline" method="POST" action="download_purchase_monthly.php"  style="display: flex; align-items: center; margin-right: 10px;">
                <div class="input-group">
                    <select class="form-control" name="month" required>
                        <?php
                     //   $current_month = date("m");
                       // for ($month = 1; $month <= 12; $month++) {
                           // $selected = ($current_month == $month) ? 'selected' : '';
                           // echo "<option value=\"$month\" $selected>" . date('F', mktime(0, 0, 0, $month, 1)) . "</option>";
                     //   }
                        ?>
                    </select>
                    <select class="form-control" name="year" required>
                        <?php
                      //  $current_year = date("Y");
                       // for ($year = $current_year; $year >= 2020; $year--) {
                       //    echo "<option value=\"$year\">$year</option>";
                       // }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success btn-sm" name="download_month">
                            <i class="fa fa-download"></i> Monthly
                        </button>
                    </div>
                </div>
            </form>

            <form class="form-inline" method="POST" action="download_purchase_range.php"  style="display: flex; align-items: center; margin-right: 10px;">
                <div class="input-group">
                     <input type="date" class="form-control" name="from_date" required 
                        value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
                    <input type="date" class="form-control" name="to_date" required 
                        value="<?php echo date('Y-m-d'); ?>">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-success btn-sm" name="download_range">
                            <i class="fa fa-download"></i> Range
                        </button>
                    </div>
                </div>
            </form>
        </div>-->
    </div>
        </div>
        </div>
        <div id="tableContainer"></div>
    </div>
</div>



                    </div>
                     
<div id="tablecont"></div>
                </div>
            </div>
           
    
            <!-- [ stiped-table ] end -->
           
            <?php
include('config.php');

// Query to fetch total counts
$sql = "
    SELECT 
        COUNT(*) AS total_catalog,
        SUM(CASE WHEN catlog_type = 'products' THEN 1 ELSE 0 END) AS total_products,
        SUM(CASE WHEN catlog_type = 'services' THEN 1 ELSE 0 END) AS total_services
    FROM 
        inventory_master
   
";
$result = $conn->query($sql);

// Fetch data
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $totalCatalog = $data['total_catalog'];
    $totalProducts = $data['total_products'];
    $totalServices = $data['total_services'];
} else {
    $totalCatalog = $totalProducts = $totalServices = 0; // Default values if no data found
}
?> 

<!-- HTML Section -->
<div class="col-sm-3">
    <div class="card">
        <div class="card-body">
            <p>Total Active Catalog: <?php echo $totalCatalog; ?></p> 
            <p>Total Active Products: <?php echo $totalProducts; ?></p> 
            <p>Total Active Services: <?php echo $totalServices; ?></p> 
        </div>                  
    </div>
</div>

        </div>
        <!-- [ Main Content ] end -->
    </div>
</section>

    <!-- Modals for updating products and services -->
    <div class="modal fade" id="updateProductsModal" tabindex="-1" role="dialog" aria-labelledby="updateProductsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Modal content will be loaded here dynamically -->
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateServicesModal" tabindex="-1" role="dialog" aria-labelledby="updateServicesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Modal content will be loaded here dynamically -->
            </div>
        </div>
    </div>


<!-- Add Stock Modal -->
<div id="goodsAdditionModal" class="modal fade" tabindex="-1" aria-labelledby="goodsAdditionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="goodsAdditionModalLabel">Add Stock</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>           
                 </div>
            <form id="addStockForm" action="add_stockdb.php" method="POST">
            <input type="hidden" name="item_id" id="item_id" value="">
            <input type="hidden" name="operation" value="add">  <!-- For add stock -->


                <div class="modal-body">
                    <div class="row">
                        <!-- Display Current Stock and Date -->
                        <div class="col-lg-6 mb-3">
                            <label for="currentStock" class="form-label">Current Stock</label>
                            <div id="currentStock">0</div>
                        </div>
                        <div class="col-lg-6 mb-3 text-end">
                            <label for="date" class="form-label">Date</label>
                            <div id="date"><?= date('d-m-Y'); ?></div>
                        </div>

                        <!-- Enter Quantity to Add -->
                        <div class="col-lg-12 mb-3">
                            <label for="quantity" class="form-label">Enter Quantity to Add</label>
                            <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Enter Quantity" required>
                        </div>

                        <!-- Enter Remark (Optional) -->
                        <div class="col-lg-12 mb-3">
                            <label for="remark" class="form-label">Enter Remark (Optional)</label>
                            <input type="text" id="remark" name="remark" class="form-control" placeholder="Remark">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-dark">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="deductStockModal" class="modal fade" tabindex="-1" aria-labelledby="deductStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deductStockModalLabel">Deduct Stock</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>           
            </div>
            <form id="deductStockForm" action="add_stockdb.php" method="POST">
                <input type="hidden" name="item_id" id="deduct_item_id" value="">
                <input type="hidden" name="operation" value="deduct"> <!-- For deduct stock -->

                <div class="modal-body">
                    <div class="row">
                        <!-- Display Current Stock and Date -->
                        <div class="col-lg-6 mb-3">
                            <label for="deductCurrentStock" class="form-label">Current Stock</label>
                            <div id="deductCurrentStock">0</div>
                        </div>
                        <div class="col-lg-6 mb-3 text-end">
                            <label for="deductDate" class="form-label">Date</label>
                            <div id="deductDate"><?= date('d-m-Y'); ?></div>
                        </div>

                        <!-- Enter Quantity to Deduct -->
                        <div class="col-lg-12 mb-3">
                            <label for="deductQuantity" class="form-label">Enter Quantity to Deduct</label>
                            <input type="number" id="deductQuantity" name="quantity" class="form-control" placeholder="Enter Quantity" required>
                        </div>

                        <!-- Enter Remark (Optional) -->
                        <div class="col-lg-12 mb-3">
                            <label for="deductRemark" class="form-label">Enter Remark (Optional)</label>
                            <input type="text" id="deductRemark" name="remark" class="form-control" placeholder="Remark">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

 

    <?php include("servicesModal.php");?>
    <?php include("productsModal.php");?>
   



   <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
 <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script type="text/javascript">
       $(document).ready(function () {
    $('#dataTables-example').DataTable();
    $('.dataTables_length').addClass('bs-select');
    $('#dataTables-example').dataTable({
        "orderFixed": [3, 'asc']
    });

    // Handle the ADD button dropdown click
    $('.dropdown-item').click(function() {
        var selectedValue = $(this).data('value');
        var inventoryType = $('#inventoryType').val()
        alert(inventoryType);
        $('#selectedOption').val(selectedValue);

        if (selectedValue === "products") {
            $("#inventory_type_products").val(inventoryType);
               if (inventoryType === "Purchased Items") {
                $("#canBeSoldContainer").show();  // Show checkbox
            } else {
                $("#canBeSoldContainer").hide();  // Hide checkbox
            }
            if(inventoryType === "Purchased Items"){
                $("#rawmaterialContainer").show();
            }
            else{
                $("#rawmaterialContainer").hide();
            }
            $("#addProductsModal").modal("show");
        } else if (selectedValue === "services") {
            $("#inventory_type_services").val(inventoryType);
            $("#addServicesModal").modal("show");
        }
    });


    function updateData(inventoryType) {
        var dataString = 'inventoryType=' + inventoryType;
        $.ajax({
            url: 'get_inventory.php',
            type: "GET",
            data: dataString,
            success: function(data) {
                $("#tablecont").html(data);
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    const inventoryType = urlParams.get('type');
    if (inventoryType) {
        $('#inventoryType').val(inventoryType);
        updateData(inventoryType);
    }
    $('#inventoryType').change(function() {
        if ($(this).val() === 'Sales Catalog') {
            $('#salesDownloadForms').show();
            $('#purchaseDownloadForms').hide();
        } else {
            $('#salesDownloadForms').hide();
            $('#purchaseDownloadForms').show();
        }
        updateData($(this).val());
    });
});

        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            console.log(type);
            var url = type === 'products' ? 'update_productsModal.php' : 'update_servicesModal.php';
            console.log(url);
            $.ajax({
                url: url,
                type: "GET",
                data: {id: id},
                success: function(data) {
                    if(type === 'products') {
                        $('#updateProductsModal .modal-content').html(data);
                        $('#updateProductsModal').modal('show');
                    } else {
                        $('#updateServicesModal .modal-content').html(data);
                        $('#updateServicesModal').modal('show');
                    }
                }
            });
        });

function computePriceValues({ price, gstRate, inclusiveGst, nonTaxable, cessRate }) {
    let taxablePrice = 0, gstAmount = 0, cessAmount = 0;

    // Convert special GST values to 0
    const gst = isNaN(parseFloat(gstRate)) ? 0 : parseFloat(gstRate);
    const cess = isNaN(parseFloat(cessRate)) ? 0 : parseFloat(cessRate);
    const nonTax = nonTaxable || 0;

    if (inclusiveGst === "inclusive of GST" && price > 0) {
        const net = price - nonTax;
        taxablePrice = net / (1 + gst / 100);
    } else if (inclusiveGst === "exclusive of GST" && price > 0) {
        taxablePrice = price - nonTax;
    } else {
        return null;
    }

    gstAmount = taxablePrice * (gst / 100);
    cessAmount = taxablePrice * (cess / 100);

    return {
        taxablePrice: taxablePrice.toFixed(2),
        gstAmount: gstAmount.toFixed(2),
        cessAmount: cessAmount.toFixed(2)
    };
}

// Function to calculate batch price for each row
function calculateBatchRow(priceField, netPriceField, nonTaxableField, cessAmountField) {
    // Common fields for all batches
    const gstRateRaw = document.getElementById('gst_rate1')?.value?.trim();
    const gstType = document.getElementById('inclusive_gst1')?.value?.toLowerCase();
    const cessRate = parseFloat(document.getElementById('cess_rate1')?.value) || 0;

     // const nonTaxableField = document.getElementById('non_taxable1');  // Get the non-taxable field
    const nonTaxableAmount = parseFloat(nonTaxableField.value) || 0;  // Get the value of non-taxable field
     if (!priceField || !gstRateRaw || !gstType) {
        console.log('Missing required field');
        return;
    }

    const price = parseFloat(priceField.value) || 0;

    // Determine GST rate
    let gstRate = 0;
    const isSpecialGST = ['nil rated', 'zero rated', 'exempted supply', 'non gst supply'].includes(gstRateRaw.toLowerCase());
    if (!isSpecialGST) {
        gstRate = parseFloat(gstRateRaw) || 0;
    }

    // Calculate GST & Net Price
    let taxableAmount = price;
    let gstAmount = 0;

    if (gstType === 'inclusive of gst') {
        const taxableWithGst = price - nonTaxableAmount;
        taxableAmount = taxableWithGst / (1 + gstRate / 100);
        gstAmount = taxableWithGst - taxableAmount;
    } else if (gstType === 'exclusive of gst') {
        taxableAmount = price - nonTaxableAmount;
        gstAmount = taxableAmount * (gstRate / 100);
    }

    const cessAmount = taxableAmount * (cessRate / 100);
    // Final net price = taxable + gst (same as price if inclusive)
    const netPrice = taxableAmount + gstAmount;
    // Set calculated values for the batch row
    netPriceField.value = `${taxableAmount.toFixed(2)} | ${gstAmount.toFixed(2)}`;
    cessAmountField.value = cessAmount.toFixed(2);
}


// Handle the calculation depending on whether Maintain Batch is checked
function handleCalculation() {
    const maintainBatchChecked = document.getElementById('maintain_batch')?.checked;

    if (maintainBatchChecked) {
        // console.log("Maintain Batch is checked, calling recalculateBatchRows");
        recalculateBatchRows();  // Recalculate all batch rows if Maintain Batch is checked
    } else {
        // console.log("Maintain Batch is NOT checked, calling calculatePrices");
        calculatePrices('products');  // Recalculate the product price if Maintain Batch is not checked
    }
}

// Function to recalculate all batch rows when common fields change
function recalculateBatchRows() {
    const batchRows = document.querySelectorAll('.batch-row');
    batchRows.forEach((row, index) => { 
        const priceField = row.querySelector('input[name="batch_price[]"]');
        const netPriceField = row.querySelector('input[name="batch_net_price[]"]');
        const cessAmountField = row.querySelector('input[name="batch_cess_amount[]"]');
        const nonTaxableField = row.querySelector('input[name="batch_non_taxable_price[]"]');
        if (priceField) {
            calculateBatchRow(priceField, netPriceField, nonTaxableField, cessAmountField);  // Recalculate for this batch row
        }
    });
}

function calculatePrices(context = 'products') {
    const modal = document.querySelector(`[data-modal="${context}"]`)?.closest('form') || document;
    const priceField = modal.querySelector(`.price-input[data-modal="${context}"]`);
    const gstTypeField = modal.querySelector(`.inclusive-gst-select[data-modal="${context}"]`);
    const gstRateField = modal.querySelector(`.gst-rate-input[data-modal="${context}"]`);
    const nonTaxableField = modal.querySelector(`.non-taxable-input[data-modal="${context}"]`);
    const cessRateField = modal.querySelector(`.cess-amt-input[data-modal="${context}"]`);
    const netPriceField = modal.querySelector(`.net-price-input[data-modal="${context}"]`);
    const cessAmountField = modal.querySelector(`.cess_amount-input[data-modal="${context}"]`);

    if (!priceField || !gstTypeField || !gstRateField || !netPriceField) return;

    const price = parseFloat(priceField.value) || 0;
    const gstType = gstTypeField.value.toLowerCase();
    const gstRateRaw = gstRateField.value.trim();
    const cessRate = parseFloat(cessRateField?.value) || 0;
    const nonTaxableAmount = parseFloat(nonTaxableField.value) || 0;
    let gstRate = 0;
    const isSpecialGST = ['nil rated', 'zero rated', 'exempted supply', 'non gst supply'].includes(gstRateRaw.toLowerCase());

    if (!isSpecialGST) {
        gstRate = parseFloat(gstRateRaw) || 0;
    }
    let taxableAmount = price;
    let gstAmount = 0;
    if (gstType === 'inclusive of gst') {
        const taxableWithGst = price - nonTaxableAmount;
        taxableAmount = taxableWithGst / (1 + gstRate / 100);
        gstAmount = taxableWithGst - taxableAmount;
    } else if (gstType === 'exclusive of gst') {
        taxableAmount = price - nonTaxableAmount;
        gstAmount = taxableAmount * (gstRate / 100);
    }
    const cessAmount = taxableAmount * (cessRate / 100);

    // Show values
    netPriceField.value = `${taxableAmount.toFixed(2)} | ${gstAmount.toFixed(2)}`;
    if (cessAmountField) {
        cessAmountField.value = cessAmount.toFixed(2);
    }
}

    $(document).on('input', '.modal-input, .modal-select', function () {
        var modalId = $(this).data('modal');
        calculatePrices(modalId);
    });

$(document).on('click', '.stock-btn', function(e) {
    e.preventDefault();
    var itemId = $(this).data('id'); // Get the ID of the item
    $('#item_id').val(itemId); // Set item ID in hidden field for form submission

    // AJAX request to get current stock (opening stock)
    $.ajax({
        url: 'get_stock.php', // PHP file to handle fetching stock
        type: 'GET',
        data: { id: itemId },
        dataType: 'json',
        success: function(response) {
            $('#currentStock').text(response.opening_stock); // Set opening stock in modal
        },
        error: function() {
            alert("Error fetching stock data.");
        }
    });

    // Show the modal
    $('#goodsAdditionModal').modal('show');
    
    
});
$(document).on('click', '.deduct-stock-btn', function(e) {
    e.preventDefault();
    var itemId = $(this).data('id'); // Get the ID of the item
    $('#deduct_item_id').val(itemId); // Set item ID in hidden field for form submission

    // AJAX request to get current stock (opening stock)
    $.ajax({
        url: 'get_stock.php', // PHP file to fetch stock
        type: 'GET',
        data: { id: itemId },
        dataType: 'json',
        success: function(response) {
            $('#deductCurrentStock').text(response.opening_stock); // Set opening stock in modal
        },
        error: function() {
            alert("Error fetching stock data.");
        }
    });

    // Show the modal
    $('#deductStockModal').modal('show');
});

// function generateBarcode() {
//     const productName = document.getElementById('goods_name1').value.trim();  // Get the product name
//     const price = document.getElementById('price1').value.trim();  // Get the price
//     const randomDigits = Math.floor(Math.random() * 1000000);  // Generate a random 6-digit number

//     // Format the barcode
//     let barcode = `${productName.substring(0, 3).toUpperCase()}-${price}-${randomDigits}`;

//     // Set the generated barcode in the barcode field
//     document.getElementById('barcode').value = barcode;
    
//     // Generate barcode as SVG using JsBarcode
//     JsBarcode("#barcodeImage", barcode, {
//         format: "CODE128",  // You can change the format to another barcode type if needed
//         displayValue: true,  // Display the value below the barcode
//         width: 2,  // Width of the barcode lines
//         height: 100,  // Height of the barcode
//         margin: 10  // Margin around the barcode
//     });

//     // Now convert SVG to PNG using the canvas
//     convertSvgToPng("#barcodeImage");
// }


//working code ************************
// function generateBarcode() {
//     const barcodeInput = document.getElementById('barcode');  // Get the barcode input field
//     let barcodeValue = barcodeInput.value.trim();  // Get the entered barcode value

//     // If the barcode field is empty, generate a barcode with product details
//     if (!barcodeValue) {
//         const productName = document.getElementById('goods_name1').value.trim();  // Get the product name
//         const price = document.getElementById('price1').value.trim();  // Get the price
//         const randomDigits = Math.floor(Math.random() * 1000000);  // Generate a random 6-digit number

//         // Format the barcode value
//         barcodeValue = `${productName.substring(0, 3).toUpperCase()}-${price}-${randomDigits}`;

//         // Set the generated barcode in the barcode input field
//         barcodeInput.value = barcodeValue; // Automatically fills the barcode input field
//     }

//     // Generate the barcode and display it in the image element (using JsBarcode)
//     JsBarcode("#barcodeImage", barcodeValue, {
//         format: "CODE128",  // You can change the format to another barcode type if needed
//         displayValue: true,  // Display the value below the barcode
//         width: 2,  // Width of the barcode lines
//         height: 100,  // Height of the barcode
//         margin: 10  // Margin around the barcode
//     });
//     convertSvgToPng("#barcodeImage");
// }

function generateBarcode() {
    const barcodeInput = document.getElementById('barcode');  // Get the barcode input field
    let barcodeValue = barcodeInput.value.trim();  // Get the entered barcode value

    // If the barcode field is empty, generate a barcode with product details
    if (!barcodeValue) {
        const productName = document.getElementById('goods_name1').value.trim();  // Get the product name
        const price = document.getElementById('price1').value.trim();  // Get the price
        const randomDigits = Math.floor(Math.random() * 1000000);  // Generate a random 6-digit number
        // barcodeValue = `${productName.substring(0, 3).toUpperCase()}-${price}-${randomDigits}`;  // Format the barcode value
        // barcodeInput.value = barcodeValue;
        barcodeInput.value = randomDigits;

    }

    // Create a canvas element
    const canvas = document.createElement('canvas');
    
    // Generate the barcode and display it in the canvas element (using JsBarcode)
    JsBarcode(canvas, barcodeValue, {
        format: "CODE128",  // Barcode format
        displayValue: true,  // Display the value below the barcode
        width: 2,  // Width of the barcode lines
        height: 100,  // Height of the barcode
        margin: 10  // Margin around the barcode
    });

    // Display the canvas as an image
    const barcodeImage = document.getElementById('barcodeImage');
    barcodeImage.src = canvas.toDataURL('image/png');  // Set the src attribute to the data URL of the barcode image

    // Optionally, convert the canvas to a file (Blob) and assign it to the file input field
    canvas.toBlob(function(blob) {
        // Create a new file object
        const file = new File([blob], "barcode.png", { type: 'image/png' });

        // Create a DataTransfer object to hold the file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        // Assign the file to the hidden file input field
        const fileInput = document.getElementById('barcodeimage');
        fileInput.files = dataTransfer.files;

        // Optionally, display the file path (or name) for testing
        console.log("Barcode image file path:", fileInput.files[0].name);
    }, 'image/png');
}

// function convertBase64ToFile(base64, filename) {
//     const byteString = atob(base64.split(',')[1]);
//     const arrayBuffer = new ArrayBuffer(byteString.length);
//     const uintArray = new Uint8Array(arrayBuffer);

//     for (let i = 0; i < byteString.length; i++) {
//         uintArray[i] = byteString.charCodeAt(i);
//     }

//     const blob = new Blob([arrayBuffer], { type: 'image/png' });
//     const file = new File([blob], filename, { type: 'image/png' });

//     // Set the file into the hidden file input
//     const fileInput = document.getElementById('barcodeimage');
//     const dataTransfer = new DataTransfer();
//     dataTransfer.items.add(file);
//     fileInput.files = dataTransfer.files;
// }

// Example usage after generating the barcode




// function generateBatchBarcode(button) {
//     const row = button.closest('.batch-row');  // Ensure you're getting the correct row

//     const barcodeInput = row.querySelector('input[name="batch_barcode[]"]');  // Barcode input field
//     const barcodeValue = barcodeInput.value.trim();  // Barcode input value
//     const canvas = row.querySelector('.batch-barcode-img');  // Barcode image element

//     // Get the values of necessary fields

//     const productName = document.getElementById('goods_name1').value.trim();;  // Product Name
//     const price = row.querySelector('input[name="batch_price[]"]').value.trim();  // Price
//     const mfgDate = row.querySelector('input[name="mfg_date[]"]').value.trim();  // Manufacturing Date
//     const expDate = row.querySelector('input[name="exp_date[]"]').value.trim();  // Expiry Date
//     const batchNumber = row.querySelector('input[name="batch_no[]"]').value.trim();  // Batch Number

//     if (barcodeValue) {
//         // If the barcode value exists, generate the barcode image
//         JsBarcode(canvas, barcodeValue, {
//             format: "CODE128",
//             displayValue: true,
//             width: 2,
//             height: 100,
//             margin: 10
//         });
//     } else {
//         // If barcode value is empty, generate barcode automatically
//         const generatedBarcode = `${productName.substring(0, 3).toUpperCase()}-${price}-${mfgDate}-${expDate}-${batchNumber}`;
//         barcodeInput.value = generatedBarcode;  // Set the barcode value

//         JsBarcode(canvas, generatedBarcode, {
//             format: "CODE128",
//             displayValue: true,
//             width: 2,
//             height: 100,
//             margin: 10
//         });
//     }
// }


function generateBatchBarcode(button) {
    const row = button.closest('.batch-row');

    const barcodeInput = row.querySelector('input[name="batch_barcode[]"]');
    let barcodeValue = barcodeInput.value.trim();

    const productName = document.getElementById('goods_name1').value.trim();
    const price = row.querySelector('input[name="batch_price[]"]').value.trim();
    const mfgDate = row.querySelector('input[name="mfg_date[]"]').value.trim();
    const expDate = row.querySelector('input[name="exp_date[]"]').value.trim();
    const batchNumber = row.querySelector('input[name="batch_no[]"]').value.trim();

    if (!barcodeValue) {
        const randomDigits = Math.floor(Math.random() * 1000000);
        // barcodeValue = `${productName.substring(0, 3).toUpperCase()}-${price}-${mfgDate}-${expDate}-${batchNumber}-${randomDigits}`;
        // barcodeInput.value = barcodeValue;
        barcodeInput.value = randomDigits;
        
    }

    // ✅ Create a real canvas element
    const canvas = document.createElement('canvas');

    // Generate the barcode on the canvas
    JsBarcode(canvas, barcodeValue, {
        format: "CODE128",
        displayValue: true,
        width: 2,
        height: 100,
        margin: 10
    });

    // Display the barcode in the <img>
    const barcodeImg = row.querySelector('.batch-barcode-img');
    barcodeImg.src = canvas.toDataURL('image/png');

    // Convert the canvas to Blob and assign to file input
    canvas.toBlob(function(blob) {
        const file = new File([blob], "batch_barcode.png", { type: 'image/png' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        const fileInput = row.querySelector('input[name="batch_barcodeimage[]"]');
        fileInput.files = dataTransfer.files;

        console.log("Barcode image file path:", fileInput.files[0].name);
    }, 'image/png');
}





// Function to convert SVG to PNG and display it on the canvas
function convertSvgToPng(svgSelector) {
    const svgElement = document.querySelector(svgSelector);

    // Create an image from the SVG content
    const svgData = new XMLSerializer().serializeToString(svgElement);
    const svgBlob = new Blob([svgData], { type: 'image/svg+xml' });
    const svgUrl = URL.createObjectURL(svgBlob);

    // Create an image object to hold the PNG
    const image = new Image();
    image.onload = function() {
        // Draw the image to the canvas
        const canvas = document.getElementById('barcodeCanvas');
        const context = canvas.getContext('2d');
        
        // Set the canvas size to match the image
        canvas.width = image.width;
        canvas.height = image.height;

        // Draw the image to the canvas
        context.drawImage(image, 0, 0);

        // Convert canvas to PNG data URL
        const pngDataUrl = canvas.toDataURL("image/png");

        // Display the PNG data URL in the console (for debugging)
        console.log(pngDataUrl);

        // Optionally, store the barcode image data on the server
        storeBarcodeImage(pngDataUrl);
    };

    image.src = svgUrl;  // Set the image source to the SVG URL
}



   // Bulk Upload button click handler
   document.getElementById('bulkUpload').addEventListener('click', function () {
            document.getElementById('uploadFileInput').click();
        });

        // File selection change handler
        document.getElementById('uploadFileInput').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                alert('File selected: ' + file.name);

                const formData = new FormData();
                formData.append('spreadsheet', file);

                fetch('', { // Ensure correct endpoint hered"
    method: 'POST',
    body: formData,
})
.then(response => response.text())  // Get response as text first
.then(text => {
    console.log('Server Response:', text); // Debugging step

    try {
        const data = JSON.parse(text);
        if (data.status === 'success') {
            alert('File uploaded successfully!');
        } else {
            alert('Error: ' + data.message);
        }
    } catch (e) {
        alert('File uploaded successfully!');
        console.error('Non-JSON Response:', text);
    }
})
.catch(error => {
    alert('Error: ' + error.message);
    console.error('Fetch Error:', error);
});

            }
        });

        // Export button click handler
        document.getElementById('exportData').addEventListener('click', function () {
            alert('Export button clicked! The Excel file will now download.');
            window.location.href = 'download-template.php'; // Modify to your export script
        });

    </script>

    <script type="text/javascript">
  $(function() {
    var start = moment().startOf('month');
    var end = moment().endOf('month');

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    // When the user selects a date range manually
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        
        // Store the selected date range for use
        $('#reportrange').data('start', startDate);
        $('#reportrange').data('end', endDate);
    });

    // Handle the download button click for monthly or custom date range download
  $('#download-btn').on('click', function() {
    alert("from click");
    var startDate = $('#reportrange').data('start');
    var endDate = $('#reportrange').data('end');

    if (!startDate || !endDate) {
        alert('Please select a date range first.');
        return;
    }

    // Send the selected date range to the backend for processing
    $.ajax({
        url: 'download-inventory-data.php', // PHP script to handle the download
        type: 'GET',
        data: {
            from_date: startDate,
            to_date: endDate,
            download_range: true // Indicates custom date range download
        },
        success: function(response) {
            // If no data is found, the server returns JSON with 'error'
            try {
                var data = JSON.parse(response);
                if (data.status === 'error') {
                    // No data found, show SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'No Data Found',
                        text: data.message, // This will show the "No data found" message
                    });
                    return;  // Prevent file download
                }
            } catch (e) {
                // If JSON parsing fails, assume the response is the Excel file
                // Proceed with the file download
                window.location.href = 'download-customer-data.php?from_date=' + startDate + '&to_date=' + endDate + '&download_range=true';
            }
        },
        error: function(xhr, status, error) {
            // Handle error if the request fails
            alert('Error: ' + error);
        }
    });
});

});
</script>

</body>
</html>
