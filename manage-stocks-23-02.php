<?php
// Start session and include config file
session_start(); 
if (!isset($_SESSION['LOG_IN'])) {
   header("Location:login.php");
} else {
   $_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
include("config.php");

// Update sold_stock in inventory_master table based on quantities in invoice_items
$updateQuery = "
    UPDATE inventory_master im
    LEFT JOIN (
        SELECT 
            productid, 
            COALESCE(SUM(qty), 0) AS total_sold
        FROM 
            invoice_items
        GROUP BY 
            productid
    ) ii ON im.id = ii.productid
    SET im.sold_stock = COALESCE(ii.total_sold, 0)
    WHERE 
        im.inventory_type = 'sales catalog' 
        AND im.catlog_type = 'products'
";

// Execute the update query
if (!mysqli_query($conn, $updateQuery)) {
    echo "Error updating sold_stock: " . mysqli_error($conn);
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php");?>
</head>
<body class="">
    <?php include("menu.php");?>
    
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">View Stock</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">View Stock</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Stock in Hand</th>
                                            <th>Sold Out</th>
                                            <th>Total Products</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
// Fetch data from the inventory_master table and calculate sold quantity
$query = "
    SELECT 
        im.name AS product_name,
        im.opening_stock AS stock_in_hand,
        COALESCE(SUM(ii.qty), 0) AS sold_out
    FROM 
        inventory_master im
    LEFT JOIN 
        invoice_items ii ON im.id = ii.productid
    
    GROUP BY 
        im.id
";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $productName = $row['product_name'];
        $stockInHand = (int) $row['stock_in_hand'];
        $soldOut = (int) $row['sold_out'];
        $totalProducts = $stockInHand - $soldOut;

        echo "<tr>";
        echo "<td>" . htmlspecialchars($productName) . "</td>";
        echo "<td>" . htmlspecialchars($stockInHand) . "</td>";
        echo "<td>" . htmlspecialchars($soldOut) . "</td>";
        echo "<td>" . htmlspecialchars($totalProducts) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No data found.</td></tr>";
}
?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#dataTables-example').DataTable();
            $('.dataTables_length').addClass('bs-select');
        });
        $('#dataTables-example').dataTable({
            "orderFixed": [3, 'asc']
        });
    </script>
</body>
</html>
