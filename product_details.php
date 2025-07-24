<?php
// Include the database configuration file
include('config.php');
?>

<?php
// Assuming $id is the ID you want to fetch the description for (e.g., from URL or form input)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Get ID from URL parameter (e.g., ?id=1)

// Ensure $id is valid before proceeding
if ($id > 0) {
    $query = "SELECT `description`, `last_updated_at` FROM `inventory_master` WHERE `id` = $id";
    $auditLogResult = mysqli_query($conn, $query);

    if (!$auditLogResult) {
        die("Query Failed: " . mysqli_error($conn)); // Check if the query failed
    }

    if (mysqli_num_rows($auditLogResult) > 0) {
        $log = mysqli_fetch_assoc($auditLogResult);
        $description = $log['description'];
        $last_updated_at = $log['last_updated_at'];
    } else {
        $description = "No description found.";
        $last_updated_at = "N/A";
    }
} else {
    $description = "Invalid ID.";
    $last_updated_at = "N/A";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>iiiQbets - Customers</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: grid;
            grid-template-columns: 1fr 2fr; /* Left: 1/3, Right: 2/3 */
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .left-section, .right-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card, .box {
            background-color: white;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-left: 70px;
        }

        .card h2, .box h3 {
            margin: 0 0 15px;
            font-size: 18px;
        }

        .info-item, .status, a {
            display: block;
            margin-bottom: 10px;
        }
        .table-sm th, .table-sm td {
    padding: 8px !important;
    font-size: 14px !important;
}
.table-responsive {
    max-width: 100%;
    overflow-x: auto;
}
.modal-lg {
    max-width: 70%;
}


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 20px;
            text-align: left;
        }

        table th {
            background-color: #f0f0f0;
        }

        .no-data {
            text-align: center;
            color: gray;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tabs button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #ddd;
            cursor: pointer;
        }

        .tabs button.active {
            background-color: #007bff;
            color: white;
        }

        .left-section{
            width: 100%;
        }

        .right-section{
            width:100%;
        }
    </style>
</head>

<body>
    <?php include("menu.php"); ?>

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
  
    <div class="row">
        
        <!-- Left Section -->
        <div class="col-sm-4">
            
            <!-- Card 1: Item Details -->
            <?php
            // Get the ID from the query parameter
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

            // Fetch item by ID
            $sql = "SELECT name, category FROM inventory_master WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($item = $result->fetch_assoc()) {
                    // Display the item details
                    echo '<div class="card" style="margin-left:0px;">';
                    echo '<h2>' . htmlspecialchars($item['name']) . '</h2>';
                    echo '<p>' . htmlspecialchars($item['category']) . '</p>';
                    //echo '<a href="#">Edit</a>';
                   // echo '<a href="#">Clone to Purchase</a>';
                    //echo '<a href="#">Add Variant</a>';
                    echo '</div>';
                }
            } else {
                echo "Item not found.";
            }

            $stmt->close();
            ?>

            <!-- Card 2: Item Information -->
            <?php
            // Fetch item details
            $sql = "SELECT  units,   hsn_code, gst_rate,cess_amt,  created_by,  created_on, last_updated_by 
                FROM inventory_master  WHERE id = ? ";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $item = $result->fetch_assoc();
            } else {
                $item = null;
            }

            $stmt->close();
            ?>

            <!-- Display the data -->
            <div class="card" style="margin-left:0px;">
                <?php if ($item): ?>
                    <div class="info-item">Units: <?= htmlspecialchars($item['units']) ?></div>
                    <div class="info-item">HSN: <?= htmlspecialchars($item['hsn_code']) ?></div>
                    <div class="info-item">GST: <?= htmlspecialchars($item['gst_rate']) ?>%</div>
                    <div class="info-item">Cess: <?= htmlspecialchars($item['cess_amt']) ?></div>
                    <div class="info-item">Created By: <?= htmlspecialchars($item['created_by']) ?></div>
                    <div class="info-item">Created On: <?= htmlspecialchars($item['created_on']) ?></div>
                    <div class="info-item">Last Updated By: <?= htmlspecialchars($item['last_updated_by']) ?></div>
                <?php else: ?>
                    <p>No data found for the given ID.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Section -->
        <div class="col-sm-8">

            <!-- Table 1: Variants -->

<?php
$pid = $_GET['id'];
$sqlBatchManagement = "SELECT maintain_batch FROM  inventory_master WHERE id = ?";
$stmtBatchManagement = $conn->prepare($sqlBatchManagement);
$stmtBatchManagement->bind_param("i", $pid);
$stmtBatchManagement->execute();
$stmtBatchManagement->bind_result($batchManagementEnabled);
$stmtBatchManagement->fetch();
$stmtBatchManagement->close();

if($batchManagementEnabled)
{
    ?>
    <div class="box" style="margin-left: -10px;">
    <h3>Product Stock Details</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-sm text-center">
            <thead class="table-dark">
                <tr>
                    <th style="width: 20%;">Product Name</th>
                     <th style="width: 20%;">Batch No</th>
                    <th style="width: 12%;">Price Per Unit</th>
                    <th style="width: 12%;">Opening Stock</th>
                    <th style="width: 12%;">Stock In</th>
                    <th style="width: 12%;">Stock Out</th>
                    <th style="width: 12%;">Balance Stock</th>
                    <th>Barcode</th>
                </tr>
            </thead>
            <tbody>
                <?php
     $sql = "SELECT inventory_master.name, product_batches.batch_no, product_batches.batch_price, product_batches.opening_stock, product_batches.stock_in, product_batches.stock_out, product_batches.barcode_image, product_batches.balance_stock, (SELECT IFNULL(SUM(pb1.stock_in), 0) FROM product_batches pb1 WHERE pb1.product_id = inventory_master.id) AS total_batch_stock_in, (SELECT IFNULL(SUM(pb2.stock_out), 0) FROM product_batches pb2 WHERE pb2.product_id = inventory_master.id) AS total_batch_stock_out FROM inventory_master JOIN product_batches ON inventory_master.id = product_batches.product_id WHERE inventory_master.id = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                         echo '<td>' . htmlspecialchars($row['batch_no']) . '</td>';
                        echo '<td>' . htmlspecialchars(number_format($row['batch_price'], 2)) . '</td>';
                        echo '<td>' . htmlspecialchars($row['opening_stock'] ?? 0) . '</td>';  
                        echo '<td><a href="#" class="stock-in-link text-primary" data-id="' . $id . '" data-toggle="modal" data-target="#stockInModal">' . htmlspecialchars($row['stock_in'] ?? 0) . '</a></td>';
                        echo '<td><a href="#" class="stock-out-link text-danger" data-id="' . $id . '" data-toggle="modal" data-target="#stockOutModal">' . htmlspecialchars($row['stock_out'] ?? 0) . '</a></td>';
                        echo '<td>' . htmlspecialchars($row['balance_stock'] ?? 0) . '</td>';
                        echo '<td><img src="'.htmlspecialchars($row['barcode_image']).'" width="50%"/><td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6">No data found for the specified ID.</td></tr>';
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
    <?php

}
else{
?>
<div class="box" style="margin-left: -10px;">
    <h3>Product Stock Details</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-sm text-center">
            <thead class="table-dark">
                <tr>
                    <th style="width: 20%;">Product Name</th>
                    <th style="width: 12%;">Price Per Unit</th>
                    <th style="width: 12%;">Opening Stock</th>
                    <th style="width: 12%;">Stock In</th>
                    <th style="width: 12%;">Stock Out</th>
                    <th style="width: 12%;">Balance Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php
     $sql = "SELECT  inventory_master.name,inventory_master.price,inventory_master.opening_stock,inventory_master.Stock_in, inventory_master.stock_out,  inventory_master.balance_stock  FROM inventory_master WHERE inventory_master.id = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                        echo '<td>' . htmlspecialchars(number_format($row['price'], 2)) . '</td>';
                        echo '<td>' . htmlspecialchars($row['opening_stock'] ?? 0) . '</td>';  
                        echo '<td><a href="#" class="stock-in-link text-primary" data-id="' . $id . '" data-toggle="modal" data-target="#stockInModal">' . htmlspecialchars($row['Stock_in'] ?? 0) . '</a></td>';
                        echo '<td><a href="#" class="stock-out-link text-danger" data-id="' . $id . '" data-toggle="modal" data-target="#stockOutModal">' . htmlspecialchars($row['stock_out'] ?? 0) . '</a></td>';
                        echo '<td>' . htmlspecialchars($row['balance_stock'] ?? 0) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6">No data found for the specified ID.</td></tr>';
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php

}
?>







            <!-- Table 2: Recent Purchases -->
            <div class="box" style="margin-left:0px;">
                <h3>Recent Sales</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Invoice Date</th>
                            <th>Customer Name</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
// Get the selected invoice ID from the request
$invoice_id = $_GET['id'] ?? null;

if ($invoice_id) {
    // Sanitize the input to prevent SQL injection
    $invoice_id = intval($invoice_id);

    // Fetch the invoice details for the selected ID
    $sql = "
    SELECT i.invoice_code, i.invoice_date, i.customer_name, SUM(ii.qty) as total_qty
    FROM invoice i
    INNER JOIN invoice_items ii ON i.id = ii.invoice_id
    WHERE ii.productid = ?
    GROUP BY i.invoice_code, i.invoice_date, i.customer_name";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoice_id); // Use the sanitized invoice_id
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($purchase = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($purchase['invoice_code']) . '</td>';
            echo '<td>' . htmlspecialchars(date("d/m/Y", strtotime($purchase['invoice_date']))) . '</td>';
            echo '<td>' . htmlspecialchars($purchase['customer_name']) . '</td>';
            echo '<td>' . htmlspecialchars($purchase['total_qty']) . ' Units</td>'; // Display total quantity
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="4" class="no-data">No sales recorded for this product.</td></tr>';
    }
    $stmt->close();
} else {
    echo '<tr><td colspan="4" class="no-data">No invoice ID provided.</td></tr>';
}
?>

                    </tbody>
                </table>
            </div>


  <!-- Table 2: Recent Purchases -->
            <div class="box" style="margin-left:0px;">
                <h3>Recent Purchases</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Purchase Invoice</th>
                            <th>Invoice Date</th>
                            <th>Supplier Name</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
$product_id = $_GET['id'] ?? null;

if ($product_id) {
    $product_id = intval($product_id);

    $sql = "
        SELECT i.invoice_code, i.invoice_date, i.customer_name, ii.qty
        FROM pi_invoice_items ii
        JOIN pi_invoice i ON ii.invoice_id = i.id
        WHERE ii.productid = ?
        ORDER BY i.invoice_date DESC
        LIMIT 5
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($purchase = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($purchase['invoice_code']) . '</td>';
            echo '<td>' . htmlspecialchars(date("d/m/Y", strtotime($purchase['invoice_date']))) . '</td>';
            echo '<td>' . htmlspecialchars($purchase['customer_name']) . '</td>';
            echo '<td>' . htmlspecialchars($purchase['qty']) . ' Units</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="4" class="no-data">No purchase invoice records for this product.</td></tr>';
    }

    $stmt->close();
} else {
    echo '<tr><td colspan="4" class="no-data">No product ID provided.</td></tr>';
}

?>

                    </tbody>
                </table>
            </div>
            <!-- Audit Log -->
            <div class="box" style="margin-left:0px;">
                <h3>Audit Log</h3>
                <?php if ($description !== "Invalid ID" && $description !== "No description found."): ?>
                    <p><?= htmlspecialchars($description) ?> on <?= htmlspecialchars($last_updated_at) ?></p>
                <?php else: ?>
                    <p><?= htmlspecialchars($description) ?></p>
                <?php endif; ?>
            </div>
        </div>

    </div>
  </div>
</section>

<!-- Stock In Modal -->
<div id="stockInModal" class="modal fade" tabindex="-1" aria-labelledby="stockInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="stockInModalLabel">Stock In Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>           
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Source</th>
                                <th>Reference No.</th>
                                <th>Quantity</th>
                                <th>Remark</th>
                                <th>Date</th>
                               
                            </tr>
                        </thead>
                        <tbody id="stockInTableBody">
                            <tr><td colspan="4">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<!-- Stock Out Modal -->
<div id="stockOutModal" class="modal fade" tabindex="-1" aria-labelledby="stockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="stockOutModalLabel">Stock Out Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>           
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-sm text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Source</th>
                            <th>Reference No.</th>
                            <th>Quantity</th>
                            <th>Remark</th>
                            <th>Date</th>

                        </tr>
                    </thead>
                    <tbody id="stockOutDetails">
                        <tr><td colspan="4" class="text-danger">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
     
$(document).ready(function () {
    $(".stock-in-link").click(function () {
        let productId = $(this).data("id");

        // AJAX request to fetch stock-in details
        $.ajax({
            url: "fetch_stock_in.php",  // PHP script to fetch stock-in data
            type: "POST",
            data: { product_id: productId },
            success: function (response) {
                $("#stockInTableBody").html(response);
            },
            error: function () {
                $("#stockInTableBody").html("<tr><td colspan='4' class='text-danger'>Error fetching data</td></tr>");
            }
        });
    });
});


$(document).ready(function() {
    $(".stock-out-link").click(function() {
        var productId = $(this).data("id");

        $.ajax({
            url: "fetch_stock_out.php",  // Backend file to fetch stock out details
            type: "POST",
            data: { product_id: productId },
            success: function(response) {
                $("#stockOutDetails").html(response);
            },
            error: function() {
                $("#stockOutDetails").html('<tr><td colspan="4" class="text-danger">Error fetching data</td></tr>');
            }
        });
    });
});


    </script>
</body>
</html>
