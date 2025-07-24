
<!DOCTYPE html>
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
                                     <!-- <form> -->
                                <select class="btn btn-info btn-sm" id="inventoryType">
                                    <option value="Sales Catalog" class="option">Sales Catalog</option>
                                     <option value="Purchased Items" class="option">Purchased Items</option>
                                </select>
                            <!-- </form> -->
                                </div>
                                <div class="col-md-2">
                                    <!-- <form> -->
                              <!--   <select class="btn btn-success btn-sm" id="addType">
                                    <option class="option">Add</option>
                                    <option class="option" value="products">Products</option>
                                    <option  class="option" value="services">Survices</option>
                                </select> -->
                            <!-- </form> -->
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


<!-- Adding Services Module-->
                  
           <?php include("servicesModal.php");?>
<!-- End Services Modal-->

<!-- Products Modal -->

<?php include("productsModal.php");?>
<!-- End of Products Modal-->
    <!-- Required Js -->

 <!-- <script src="assets/js/jquery.min.js"></script> -->

        <!-- Bootstrap Core JavaScript -->
        <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>

 <script type="text/javascript">
    $(document).ready(function () {
    $('#dataTables-example').DataTable();
    $('.dataTables_length').addClass('bs-select');

    });
    $('#dataTables-example').dataTable( {
    "orderFixed": [ 3, 'asc' ]
    } );
</script>

<!-- Include jQuery and Bootstrap JavaScript -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

<script>
    // Handle dropdown item clicks
    $('.dropdown-item').click(function() {
        var selectedValue = $(this).data('value');
        $('#selectedOption').val(selectedValue);

         if(selectedValue === "products")
        {
            $("#addProductsModal").modal("show");
        } else if(selectedValue === "services"){
            $("#addServicesModal").modal("show");
            // $("#Div1").modal("show");
        }
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
    // Function to update the data based on the selected option
    function updateData(inventoryType) {
        var dataString = 'inventoryType=' + inventoryType;

         if (inventoryType === "Sales Catalog") {
             $('#inventory_type').val(inventoryType);
             $('#inventory_type1').val(inventoryType);

        } else if (inventoryType === "Purchased Items") {
             $('#inventory_type').val(inventoryType);
              $('#inventory_type1').val(inventoryType);
        }


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
        // Set the dropdown selection
        $('#inventoryType').val(inventoryType);
         // $('#inventory_type1').val(inventoryType);
        // Update the data
        updateData(inventoryType);
    }
    $('#inventoryType').on('change', function() {
        updateData($(this).val()); // Call the function when the dropdown selection changes
    });
});
</script>

<script>
$(function () {
    $("#addType").on("change", function () {
  var type = $('#addType').find("option:selected").val();
     if(type === "products")
        {
            $("#addProductsModal").modal("show");
        } else if(type === "services"){
            $("#addServicesModal").modal("show");
            // $("#Div1").modal("show");
        }
    });
});
</script>



        <script>
    // Function to calculate Net Price and GST and display them in a single input field
 //    function calculatePrices() {
 //        var price = parseFloat($("#price1").val()) || 0;
 //        var gstRate = parseFloat($("#gst_rate1").val()) || 0;
 //        var inclusiveGst = $("#inclusive_gst1").val();
 //        var nonTaxable = parseFloat($("#non_taxable1").val()) || 0;
        
 //        if (inclusiveGst === "inclusive of GST" && price > 0) {
 //            var gstAmount = (price * gstRate) / (100 + gstRate);
 //            var netPrice = price - gstAmount - nonTaxable;
 //            $("#net_price1").val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
 //            console.log(netPrice);
 //        } else if (inclusiveGst === "exclusive of GST" && price > 0) {
 //            var gstAmount = (price * gstRate) / 100;
 //            var netPrice = price + gstAmount - nonTaxable;
 //            $("#net_price1").val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
 //            console.log(netPrice);
 //        } else {
 //            $("#net_price1").val("");
 //        }
 //    }

 // $("#price1, #inclusive_gst1, #gst_rate1, #non_taxable1").on("input", calculatePrices);

   </script>

<script type="text/javascript">
    // Function to calculate Net Price and GST and display them in a single input field
function calculatePrices(modalId) {
    var price = parseFloat($(".modal-input.price-input[data-modal='" + modalId + "']").val()) || 0;
    var gstRate = parseFloat($(".modal-select.gst-rate-input[data-modal='" + modalId + "']").val()) || 0;
    var inclusiveGst = $(".modal-select.inclusive-gst-select[data-modal='" + modalId + "']").val();
    var nonTaxable = parseFloat($(".modal-input.non-taxable-input[data-modal='" + modalId + "']").val()) || 0;

    var netPriceField = $(".modal-input.net-price-input[data-modal='" + modalId + "']");

    if (inclusiveGst === "inclusive of GST" && price > 0) {
        var gstAmount = (price * gstRate) / (100 + gstRate);
        var netPrice = price - gstAmount - nonTaxable;
        netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
        console.log(netPrice);
    } else if (inclusiveGst === "exclusive of GST" && price > 0) {
        var gstAmount = (price * gstRate) / 100;
        var netPrice = price-nonTaxable;
        netPriceField.val(netPrice.toFixed(2) + " | " + gstAmount.toFixed(2));
        console.log(netPrice);
    } else {
        netPriceField.val("");
    }
}

// Attach event listeners to elements in both modals based on their classes and data attributes
$(".modal-input, .modal-select").on("input", function () {
    var modalId = $(this).data("modal");
    calculatePrices(modalId);
});

</script>

</body>
</html>
