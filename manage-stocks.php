<?php
// Start session and include config file
session_start();
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit();
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
}

include("config.php");

// Get product ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ensure a valid ID is passed
if ($id == 0) {
    echo "Invalid product ID.";
    exit();
}

// Update sold_stock and stock quantity in inventory_master
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
    SET 
        im.sold_stock = COALESCE(ii.total_sold, 0)
    WHERE 
        im.inventory_type = 'sales catalog' 
        AND im.catlog_type = 'products'
        AND im.id = $id";

// Execute update query
if (!mysqli_query($conn, $updateQuery)) {
    echo "Error updating stock: " . mysqli_error($conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>iiiQbets - View Stock</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
</head>
<body>
    <?php include("menu.php"); ?>

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
        <th>Stock Action</th>
        <th>Quantity</th>
        <th>Available Stock</th> <!-- New Column -->
        <th>Remark</th>
        <th>Date</th>
    </tr>
</thead>
<tbody>
<?php
// Fetch all stock transaction details for the selected product with available stock calculation
$query = "
SELECT 
    im.name AS product_name,
    sm.add_and_deduct,
    sm.quantity,
    sm.remark,
    sm.created_on,
    (
        SELECT 
            COALESCE(SUM(CASE WHEN sm2.add_and_deduct = 'add' THEN sm2.quantity ELSE 0 END), 0) 
            - COALESCE(SUM(CASE WHEN sm2.add_and_deduct = 'deduct' THEN sm2.quantity ELSE 0 END), 0)
        FROM stock_master sm2
        WHERE sm2.product_id = sm.product_id
        AND sm2.created_on <= sm.created_on
    ) AS available_stock
FROM 
    inventory_master im
LEFT JOIN stock_master sm ON im.id = sm.product_id
WHERE 
    im.inventory_type = 'sales catalog' 
    AND im.catlog_type = 'products'
    AND im.id = $id
ORDER BY sm.created_on DESC"; // Show latest transactions first

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
        echo "<td>" . htmlspecialchars(ucfirst($row['add_and_deduct'])) . "</td>";
        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
        echo "<td>" . htmlspecialchars($row['available_stock']) . "</td>"; // New column
        echo "<td>" . htmlspecialchars($row['remark']) . "</td>";
        echo "<td>" . htmlspecialchars($row['created_on']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No stock data found for this product.</td></tr>";
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
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable();
        });
    </script>
</body>
</html>
