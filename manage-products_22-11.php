<!DOCTYPE html>
<?php
session_start(); 
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
    <div class="col-md-2">
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
    <!-- <div class="col-md-7 text-right">
    <a href="manage_catalog.php">
    <button type="button" class="btn btn-primary btn-sm" style="background-color: #5867dd;">
        Inventory
    </button>
</a>

    </div> -->
</div>

                    </div>
                     
<div id="tablecont"></div>
                </div>
            </div>
            <!-- [ stiped-table ] end -->
           
           <div class="col-sm-3">
               <div class="card">
                <div class="card-body">
                    <p>Total Active Catalog  9</p>
                    <p>Total Active Goods  0</p>
                    <p>Total Active Services 14</p>
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
            <form id="addStockForm" action="addStock.php" method="POST">
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
            var url = type === 'products' ? 'update_productsModal.php' : 'update_servicesModal.php';
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

    var netPriceField = $(".modal-input.net-price-input[data-modal='" + modalId + "']");

    if (inclusiveGst === "inclusive of GST" && price > 0) {
        var gstAmount = (price / (1 + gstRate / 100)) * (gstRate / 100);
        var netPrice = price - gstAmount - nonTaxable;
        netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
    } else if (inclusiveGst === "exclusive of GST" && price > 0) {
        var gstAmount = (price * gstRate) / 100;
        var netPrice = price - nonTaxable;
        netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
    } else {
        netPriceField.val("");
    }
}

$(document).on('input', '.modal-input, .modal-select', function () {
    var modalId = $(this).data('modal');
    calculatePrices(modalId);
});

$(document).on('click', '.stock-btn', function(e) {
    e.preventDefault(); // Prevent default link behavior
    var itemId = $(this).data('id'); // Get the ID of the item

    // You could make an AJAX request here if you need to load data specific to the item
    // $.ajax({
    //     url: 'get_stock_data.php',
    //     type: 'GET',
    //     data: { id: itemId },
    //     success: function(response) {
    //         // Populate modal fields if necessary
    //         $('#goodsAdditionModal').find('#stockQuantity').val(response.quantity);
    //     }
    // });

    // Show the modal
    $('#goodsAdditionModal').modal('show');
});


    </script>
    
    
</body>
</html>
