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
    <title>iiiQbets - Customers</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body class="">
    <!-- Rest of your HTML content for customers -->
    <!-- [ Pre-loader ] start -->
    <?php include("menu.php"); ?>

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
                            <div class="row">
                                <div class="col-md-12" style="text-align: end;">
                                    <form action="customer_form.php" method="POST">
                                        <button class="btn btn-success btn-sm float-end" name="addCustBtn" id="addCustBtn" type="submit">Add Customer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact Info</th>
                                            <th>Tax Information</th>
                                            <th>Created BY</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM customer_master WHERE contact_type = 'Customer'";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $row["customerName"] ?><br/>
                                                        <?php echo $row["business_name"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '">Update</a>' : $row["business_name"]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row["mobile"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '">Update Mobile</a>' : $row["mobile"]; ?><br/>
                                                        <?php echo $row["email"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '">Update Email</a>' : $row["email"]; ?>
                                                    </td>
                                                    <td>
                                                        PAN : <?php echo $row["pan"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '">Update PAN</a>' : $row["pan"]; ?><br/>
                                                        GSTIN : <?php echo $row["gstin"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '">Update GSTIN</a>' : $row["gstin"]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row["created_by"] ?><br/>
                                                        <?php echo $row["created_on"] ?>
                                                    </td>
                                                    <td>
                                                        <a href="update-customer.php?id=<?php echo $row["id"]; ?>" class="text-primary mr-2">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="delete-customer.php?id=<?php echo $row["id"]; ?>" class="text-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
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

    <!-- Required Js -->
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

</body>
</html>
