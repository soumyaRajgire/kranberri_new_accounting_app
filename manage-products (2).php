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
            opening_stock, opening_stockdate, min_stockalert, max_stockalert, 
            description, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");



        foreach ($rows as $index => $row) {
            // Skip the header row
            if ($index === 0) continue;

            // Bind parameters and execute insert
            $stmt->bind_param(
                'sssssdidsdssdsssissss',
                $row[1], $row[2], $row[3], $row[4], $row[5], $row[6],
                $row[7], $row[8], $row[9], $row[10], $row[11], $row[12],
                $row[13], $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21]
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
            <form id="addStockForm" action="add_Stock.php" method="POST">
            <input type="hidden" name="item_id" id="item_id" value="">
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

 

    <?php include("servicesModal.php");?>
    <?php include("productsModal.php");?>
   


    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
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
        var inventoryType = $('#inventoryType').val();
        $('#selectedOption').val(selectedValue);

        if (selectedValue === "products") {
            $("#inventory_type_products").val(inventoryType);
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
    $('#inventoryType').on('change', function() {
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


        function calculatePrices(modalId) {
    var price = parseFloat($(".modal-input.price-input[data-modal='" + modalId + "']").val()) || 0;
    var gstRate = parseFloat($(".modal-select.gst-rate-input[data-modal='" + modalId + "']").val()) || 0;
    var inclusiveGst = $(".modal-select.inclusive-gst-select[data-modal='" + modalId + "']").val();
    var nonTaxable = parseFloat($(".modal-input.non-taxable-input[data-modal='" + modalId + "']").val()) || 0;
    var cessRate = parseFloat($(".modal-input.cess-amt-input[data-modal='" + modalId + "']").val()) || 0;
    var cessAmountField = $(".modal-input.cess_amount-input[data-modal='" + modalId + "']");
    var netPriceField = $(".modal-input.net-price-input[data-modal='" + modalId + "']");



if (inclusiveGst === "inclusive of GST" && price > 0) {
    let taxablePrice;
    let gstAmount;
    let cessAmount;

    // if (nonTaxable > 0) {
    //     // Subtract non-taxable amount first
    //     taxablePrice = (price - nonTaxable) / (1 + gstRate / 100);
    //     gstAmount = (taxablePrice) * (gstRate / 100);
    //     cessAmount = taxablePrice * (cessRate / 100);
    // } else {
    //     // Direct calculation for inclusive GST
    //     taxablePrice = price / (1 + gstRate / 100);
    //     gstAmount = taxablePrice * (gstRate / 100);
    //     cessAmount = taxablePrice * (cessRate / 100);
    // }

    if (nonTaxable > 0) {
   
    taxablePrice = parseFloat(((price - nonTaxable) / (1 + gstRate / 100)).toFixed(2));
     gstAmount = parseFloat((taxablePrice * (gstRate / 100)).toFixed(2));
    cessAmount = parseFloat((taxablePrice * (cessRate / 100)).toFixed(2));
} else {
   
    taxablePrice = parseFloat((price / (1 + gstRate / 100)).toFixed(2));
     gstAmount = parseFloat((taxablePrice * (gstRate / 100)).toFixed(2));
    cessAmount = parseFloat((taxablePrice * (cessRate / 100)).toFixed(2));
}

    // // Round values to 2 decimal places
    // taxablePrice = taxablePrice.toFixed(2);
    // gstAmount = gstAmount.toFixed(2);
    // cessAmount = cessAmount.toFixed(2);

    // Update the fields
    netPriceField.val(`${taxablePrice} | ${gstAmount}`);
    cessAmountField.val(cessAmount);
} else if (inclusiveGst === "exclusive of GST" && price > 0) {
    let taxablePrice;
    let gstAmount;
    let cessAmount;

    if (nonTaxable > 0) {
        // Subtract non-taxable amount
        taxablePrice = parseFloat(price - nonTaxable).toFixed(2);
        gstAmount = parseFloat((taxablePrice * gstRate) / 100).toFixed(2);
        cessAmount = parseFloat((taxablePrice * cessRate) / 100).toFixed(2);
    } else {
        // Direct calculation for exclusive GST
        taxablePrice = parseFloat(price).toFixed(2);
        gstAmount = parseFloat((taxablePrice * gstRate) / 100).toFixed(2);
        cessAmount = parseFloat((taxablePrice * cessRate) / 100).toFixed(2);
    }

    // Round values to 2 decimal places
    // taxablePrice = taxablePrice.toFixed(2);
    // gstAmount = gstAmount.toFixed(2);
    // cessAmount = cessAmount.toFixed(2);

    // Update the fields
    netPriceField.val(`${taxablePrice} | ${gstAmount}`);
    cessAmountField.val(cessAmount);
} else {
    // Clear fields if price is invalid
    netPriceField.val("");
    cessAmountField.val("");
}

    // if (inclusiveGst === "inclusive of GST" && price > 0) {
    // Extract GST and Net Price for inclusive case
    // var netPrice = price / (1 + gstRate / 100); // Net price excluding GST
    // var gstAmount = price - netPrice;           // GST Amount
    // var cessAmount = (netPrice * cessRate) / 100; // Cess calculated on Net Price
    
    // // Format values to two decimal places
    // gstAmount = gstAmount.toFixed(2);
    // cessAmount = cessAmount.toFixed(2);
    // netPrice = netPrice.toFixed(2);
    
    // // Update fields
    // netPriceField.val(`${netPrice} | ${gstAmount}`);
    // cessAmountField.val(cessAmount);
// } 
// else if (inclusiveGst === "exclusive of GST" && price > 0) {
//     // Calculate GST and Net Price for exclusive case
//     var gstAmount = (price * gstRate) / 100;   // GST Amount
//     var cessAmount = (price * cessRate) / 100; // Cess calculated on price (exclusive of GST)
//     var netPrice = price;                     // Net Price remains same as entered price

//     // Format values to two decimal places
//     gstAmount = gstAmount.toFixed(2);
//     cessAmount = cessAmount.toFixed(2);
//     netPrice = netPrice.toFixed(2);

//     // Update fields
//     netPriceField.val(`${netPrice} | ${gstAmount}`);
//     cessAmountField.val(cessAmount);
// } 
// else {
//     // Clear fields if no valid condition
//     netPriceField.val("");
//     cessAmountField.val("");
// }

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

                fetch('', { // Same page script
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(text => {
                    console.log('Response:', text);
                    try {
                        const data = JSON.parse(text);
                        if (data.status === 'success') {
                            alert('File uploaded successfully!');
                        } else {
                            alert('Error: ' + data.message);
                        }
                    } catch (e) {
                        alert('Error: The response is not valid JSON.');
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
   
   
</body>
</html>
