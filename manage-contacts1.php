<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['LOG_IN'])) {
    header("Location:login.php");
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
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
                                    <select class="select">
                                        <option class="option">Customers</option>
                                        <option class="option">Suppliers</option>
                                    </select>
                                    <!-- </form> -->
                                </div>
                                <div class="col-md-2">
                                    <!-- <form> -->
                                    <select class="btn btn-success select" id="addType">
                                        <option class="option">Add</option>
                                        <option class="option" value="customers">Add Customers</option>
                                        <option class="option" value="suppliers">Add Suppliers</option>
                                    </select>
                                    <!-- </form> -->
                                </div>
                                <!--  <div class="col-md-4">
                                    <div class="search-box">
  <input class="search-input" type="text" placeholder="Search something..">
  <button class="search-btn"><i class="fas fa-search" aria-hidden="true"></i></button>
</div>
                                    
                                </div> -->




                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Description</th>
                                            <th>GST Rate</th>
                                            <th>Updated By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "select * from customers";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <tr>
                                                    <!-- <td><?php echo $row["id"] ?></td>  -->
                                                    <td><?php echo $row["name"] ?><br /><?php echo $row["business_Name"] ?></td>
                                                    <td><?php echo $row["phone"] ?><br /><?php echo $row["email"] ?></td>
                                                    <td>PAN : <?php echo $row["PanNo"] ?><br />GSTIN : <?php echo $row["gst_no"] ?></td>
                                                    <td><?php echo $row["createdBy"] ?><br /><?php echo $row["createdON"] ?></td>

                                                    <td>
                                                        <a href="update-suppliers.php?id=<?php echo $row["id"]; ?>"><i class="fa fa-edit btn-outline-primary"></i></a>
                                                        <a href="delete-supplier.php?id=<?php echo $row["id"]; ?>"><i class="fa fa-trash btn-outline-danger"></i></a>
                                                        <a type="button" data-toggle="modal" data-target="#viewDetails<?php echo $row['id'] ?>"><i class="fa fa-eye btn-outline-success"></i></a>
                                                    </td>

                                                </tr>

                                                <div id="viewDetails<?php echo $row['id'] ?>" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">View Details</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row col-md-12">
                                                                    <div class="col-md-10">

                                                                        <!-- <h6> Id : <?php echo $row['id']; ?></h6> -->
                                                                        <h6>Name : <?php echo $row['name']; ?></h6>
                                                                        <h6>Business Name : <?php echo $row['business_Name']; ?></h6>
                                                                        <h6>PAN : <?php echo $row["PanNo"] ?></h6>
                                                                        <h6>GST Type : <?php echo $row['gst_reg_type']; ?></h6>
                                                                        <h6>GSTNo : <?php echo $row['gst_no']; ?></h6>
                                                                        <h6>Address : <?php echo $row['address'] . " " . $row['city'] . " " . $row['state'] . " " . $row['country'] . "" . $row['pincode'] ?></h6>
                                                                        <h6>Conatct No : <?php echo $row['phone'] . " , " . $row['mobile']   ?></h6>
                                                                        <h6>Email : <?php echo $row['email']; ?></h6>

                                                                        <h6>Created : <?php echo $row['createdBy'] . " , " . $row['createdON']  ?></h6>
                                                                    </div>

                                                                </div>



                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php


                                            }
                                        } else {

                                            ?>
                                            <tr>
                                                <td colspan="5"><?php echo "No Records found"; ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
        $(function() {
            $("#addType").on("change", function() {

                var type = $('#addType').find("option:selected").val();

                // if (type.toUpperCase() == 'SELECT_MULTIPLE' || type.toUpperCase() == 'SELECT_ONE') {
                if (type == "customers") {
                    $("#addCustomersModal").modal("show");
                } else if (type == "suppliers") {
                    $("#addsuppliersModal").modal("show");
                    // $("#Div1").modal("show");
                }
            });
            // $('[id*=btnClosePopup]').click('on', function () {
            //     $("#addServicesModal").modal("hide");
            // });
            // $('[id*=Button1]').click('on', function () {
            //     $("#Div1").modal("hide");
            // });
        });
    </script>


</body>

</html>