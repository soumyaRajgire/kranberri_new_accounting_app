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
            <?php
            // Step 1: Establish a connection to the database
            // $servername = "localhost";
            // $username = "root";
            // $password = "";
            // $dbname = "account_db";

            // // Create a connection
            // $conn = mysqli_connect($servername, $username, $password, $dbname);

            // // Check the connection
            // if (!$conn) {
            //     die("Connection failed: " . mysqli_connect_error());
            // }

            // Step 2: Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Retrieve form data
                $id = $_POST['id'];
                $name = $_POST['name'];
                $price = $_POST['price'];
                $sku_item_no = $_POST['sku_item_no'];
                $stock = $_POST['stock'];

                // Step 3: Update the data in the database
                $sql = "UPDATE products SET name='$name', price='$price', sku_item_no='$sku_item_no', stock='$stock' WHERE id='$id'";

                if (mysqli_query($conn, $sql)) {
                    echo "Product data updated successfully!";
                    // Redirect to another page after successful update
                    // header("Location: view-products.php"); // Replace with the desired page name
                    echo '<script>window.location="view-products.php"</script>';
                    exit(); // Make sure to exit after redirection
                } else {
                    echo "Error updating product data: " . mysqli_error($conn);
                }
            }

            // Step 4: Fetch the existing data based on the provided ID
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $sql = "SELECT * FROM products WHERE id='$id'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) === 1) {
                    $row = mysqli_fetch_assoc($result);
            ?>
                    <form action="" method="post">

                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                        <div class="col-md-6 form-group">
                            <label for="name">Name</label>
                            <input class="form-control" name="name" type="text" placeholder="Name" value="<?php echo $row['name']; ?>" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="price">Price</label>
                            <input class="form-control" name="price" type="number" step="0.01" placeholder="Price" value="<?php echo $row['price']; ?>" required>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="sku_item_no">SKU/Item No:</label>
                            <input class="form-control" name="sku_item_no" type="text" placeholder="SKU/Item No" value="<?php echo $row['sku_item_no']; ?>" required>
                        </div>



                        <div class="col-md-6 form-group">
                            <label for="stock">Stock:</label>
                            <input class="form-control" name="stock" type="number" placeholder="Stock" value="<?php echo $row['stock']; ?>" required>
                        </div>



                        <div class="col-md-6">
                            <input type="submit" class="btn btn-primary" name="submit" value="Submit" value="Update" />
                        </div>

                    </form>
            <?php
                } else {
                    echo "Product not found.";
                }
            }

            // Step 5: Close the database connection
            mysqli_close($conn);
            ?>

</body>

</html>