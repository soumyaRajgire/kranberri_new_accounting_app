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

            // Step 2: Check if the "id" is provided via GET or POST request
            if (isset($_REQUEST['id'])) {
                $id = $_REQUEST['id'];

                // Step 3: Perform the deletion using an SQL DELETE query
                $sql = "DELETE FROM products WHERE id = '$id'";

                if (mysqli_query($conn, $sql)) {
                    $file_path = isset($file_path) ? $file_path : '';
                    require_once 'includes/insert_audit_log.php';
                        insertAuditLog($conn, "Deleted Product", $file_path);
                    // Step 4: Redirect the user back to the view page after deletion
                    // header("Location: view_products.php"); // Replace with the desired page name
                    echo '<script>alert("Record Deleted Successfully!")</script>';
                    echo '<script>window.location="view-products.php"</script>';
                    exit(); // Make sure to exit after redirection
                } else {
                    echo "Error deleting product: " . mysqli_error($conn);
                }
            }




            // Step 5: Close the database connection
            mysqli_close($conn);
            ?>

</body>

</html>