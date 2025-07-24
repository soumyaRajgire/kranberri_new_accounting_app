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
                                <h4 class="m-b-10">View Products</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">View Products</a></li>
                                <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                            </ul>
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
                            <h5>View Products Details</h5>

                            <!-- <span class="d-block m-t-5">use class <code>table-striped</code> inside table element</span> -->
                            <a href="add-products.php" class="btn btn-info" style="color: #fff !important;float:right;" />Add Products</a>
                        </div>
                        <?php

                        // Step 2: Fetch data from the "products" table
                        $sql = "SELECT id, name, price, sku_item_no, stock, entry_time FROM products";
                        $result = mysqli_query($conn, $sql);

                        // Step 3: Display the data on the front end
                        echo '<div class="card-body table-border-style">';
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped table-bordered" id="dataTables-example">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Id</th>';
                        echo '<th>Name</th>';
                        echo '<th>Price</th>';
                        echo '<th>SKU/Item No</th>';
                        echo '<th>Stock</th>';
                        echo '<th>Entry Time</th>';
                        echo '<th>Action</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["name"] . "</td>";
                                echo "<td>" . $row["price"] . "</td>";
                                echo "<td>" . $row["sku_item_no"] . "</td>";
                                echo "<td>" . $row["stock"] . "</td>";
                                echo "<td>" . $row["entry_time"] . "</td>";
                                echo '<td>';
                                // Add action buttons with Font Awesome icons for each row
                                echo '<a type="button" data-toggle="modal" data-target="#viewDetails' . $row['id'] . '"><i class="fa fa-eye btn-outline-success"></i></a>';
                                echo '<a href="update-products.php?id=' . $row["id"] . '" class="btn  btn-sm"><i class="fa fa-edit btn-outline-primary "></i></a>';
                                echo '<a href="delete-products.php?id=' . $row["id"] . '" class="btn  btn-sm"><i class="fa fa-trash btn-outline-danger"></i> </a>';
                                echo '</td>';
                                echo "</tr>";

                        ?>

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
                                                        <h6>Price : <?php echo $row['price']; ?></h6>
                                                        <h6>SKU/Item no : <?php echo $row['sku_item_no']; ?></h6>
                                                        <h6>Stock : <?php echo $row['stock']; ?></h6>

                                                        <!-- <h6>PAN : <?php echo $row["PanNo"] ?></h6>
                         <h6>GST Type : <?php echo $row['gst_reg_type']; ?></h6>
                         <h6>GSTNo : <?php echo $row['gst_no']; ?></h6>
                         <h6>Address : <?php echo $row['address'] . " " . $row['city'] . " " . $row['state'] . " " . $row['country'] . "" . $row['pincode'] ?></h6>
                         <h6>Conatct No : <?php echo $row['phone'] . " , " . $row['mobile']   ?></h6>
                         <h6>Email : <?php echo $row['email']; ?></h6>
                         
                         <h6>Created : <?php echo $row['createdBy'] . " , " . $row['createdON']  ?></h6>
                         </div>  -->

                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                            }
                        } else {
                            echo '<tr><td colspan="6">No data found in the "products" table.</td></tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                        echo '</div>';

                        // Step 4: Close the database connection
                        mysqli_close($conn);
                            ?>

                            <script src="assets/js/vendor-all.min.js"></script>
                            <script src="assets/js/plugins/bootstrap.min.js"></script>
                            <script src="assets/js/pcoded.min.js"></script>
                            <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>

</body>

</html>