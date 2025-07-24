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
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">



</head>

<body class="">
    <!-- [ Pre-loader ] start -->

    <?php include("menu.php"); ?>


    <!-- [ Header ] end -->




    <!-- [ Main Content ] start -->
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">View Customers</h4>
                            </div>
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- <h5>View Customers Details</h5> -->
                            <div class="row">

                                <div class="col-md-2">
                                    <!-- <form> -->
                                    <select class="btn btn-info btn-sm" id="ctype">
                                        <option class="option" value="Customer">Customers</option>
                                        <option class="option" value="suppliers">Suppliers</option>
                                    </select>
                                    <!-- </form> -->
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-success btn-sm" name="addCustBtn" id="addCustBtn" style="display:block">Add Customer</button>
                                    <button class="btn btn-success btn-sm" name="addsuplBtn" id="addsuplBtn" style="display:none">Add Supplier</button>
                                </div>
                                <!--  <div class="col-md-4">
                                    <div class="search-box">
  <input class="search-input" type="text" placeholder="Search something..">
  <button class="search-btn"><i class="fas fa-search" aria-hidden="true"></i></button>
</div>
                                    
                                </div> -->

                            </div>
                        </div>
<div id="tablecont"></div>
</div>
</div>
                <!-- [ stiped-table ] end -->

            </div>
            <!-- [ Main Content ] end -->
        </div>
    </section>


    <!-- Adding Services Module-->

    <?php include("suppliersModal.php"); ?>
    <!-- End Services Modal-->

    <!-- Products Modal -->

    <?php include("customersModal.php"); ?>
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
        $(document).ready(function() {
            $('#dataTables-example').DataTable();
            $('.dataTables_length').addClass('bs-select');

        });
        $('#dataTables-example').dataTable({
            "orderFixed": [3, 'asc']
        });
    </script>


    <script>
        // $(function() {
        //     $("#addType").on("change", function() {

        //         var type = $('#addType').find("option:selected").val();

        //         // if (type.toUpperCase() == 'SELECT_MULTIPLE' || type.toUpperCase() == 'SELECT_ONE') {
        //         if (type == "customers") {
        //             $("#addCustomersModal").modal("show");
        //         } else if (type == "suppliers") {
        //             $("#addSuppliersModal").modal("show");
        //             // $("#Div1").modal("show");
        //         }
        //     });
            
        // });
$(document).ready(function() {
    // Function to update the data based on the selected option
    function updateData(contact_type) {
        var dataString = 'contact_type=' + contact_type;

         if (contact_type === "Customer") {
            // Show the "Add Customer" button and hide the "Add Supplier" button
            $("#addCustBtn").show();
            $("#addsuplBtn").hide();
        } else if (contact_type == "suppliers") {
            // Show the "Add Supplier" button and hide the "Add Customer" button
            $("#addsuplBtn").show();
            $("#addCustBtn").hide();
        }


        $.ajax({
            url: 'get_contact.php',
            type: "GET",
            data: dataString,
            success: function(data) {
                $("#tablecont").html(data);
            }
        });
    }

    // Check for the 'type' query parameter in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const contactType = urlParams.get('type');

    if (contactType) {
        // Set the dropdown selection
        $('#ctype').val(contactType);
        // Update the data
        updateData(contactType);
    }

    // Attach an event listener to the dropdown change event
    $('#ctype').on('change', function() {
        updateData($(this).val()); // Call the function when the dropdown selection changes
    });
});
        // $(document).ready(function() {

        //     var contact_type = $('#ctype').find("option:selected").val();
        //      var dataString = 'contact_type='+ contact_type;   
        //     $.ajax({
        //     url: 'get_contact.php',
        //     type: "GET",
        //     data: dataString,
        //     success: function(data) {
        //        // displayTable(data);
        //          $("#tablecont").html(data);
        //     }
        // });

        // });
        //  $(function() {
        //     $("#ctype").on("change", function() {

        //         var type = $('#ctype').find("option:selected").val();
        //         var dataString = 'contact_type='+ type; 

        //         // if (type.toUpperCase() == 'SELECT_MULTIPLE' || type.toUpperCase() == 'SELECT_ONE') {
        //         if (type == "Customer") {
        //             if(document.getElementById("addCustBtn").style.display == "none")
        //             {
        //                 document.getElementById("addCustBtn").style.display = "block";
        //                 document.getElementById("addsuplBtn").style.display = "none";
        //             }
        //             // $("#addCustomersModal").modal("show");
        //         } else if (type == "suppliers") {
        //              if(document.getElementById("addsuplBtn").style.display == "none")
        //             {
        //                 document.getElementById("addsuplBtn").style.display = "block";
        //                 document.getElementById("addCustBtn").style.display = "none";
        //             }
        //             // $("#addSuppliersModal").modal("show");
                    
        //         }

        //     $.ajax({
        //     url: 'get_contact.php',
        //     type: "GET",
        //     data: dataString,
        //     success: function(data) {
        //        // displayTable(data);
        //          $("#tablecont").html(data);
        //     }
        // });
     
        //     });
            
        // });


       $(function() {
            $("#addCustBtn").on("click", function() {
                // $("#addCustomersModal").modal("show");
                 openAddModal("Customer");
            });
                   

        $("#addsuplBtn").on("click", function() {
                // $("#addSuppliersModal").modal("show");
                 openAddModal("Suppliers");
            });
            
            function openAddModal(contactType) {
    $("#contactType").val(contactType); // Set the contact type value in a hidden input field
    if (contactType == "Customer") {
        $("#addCustomersModal").modal("show"); // Show the modal
    } else {
        $("#addSuppliersModal").modal("show"); // Show the modal
    }
}

        });
    </script>


</body>

</html>