<?php
include('config.php');
if (isset($_GET['inventoryType'])) {
    $inventoryType = $_GET['inventoryType'];
?>

    <div class="card-body table-border-style">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="dataTables-example">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>GST Rate</th>
                        <th>Updated BY</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  
                    $sql = "SELECT * FROM inventory_master WHERE inventory_type ='$inventoryType'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td>
                            <a href="product_details.php?id=<?php echo $row['id']; ?>">
                                <?php echo $row["name"]; ?>
                            </a>
                            <br/>
                            <?php echo "RS. " . $row["price"]; ?>
                        </td>
                        <td><?php echo $row["description"]; ?></td>
                        <td>GST Rate: <?php echo $row["gst_rate"]; ?></td>
                        <td><?php echo $row["created_by"]; ?><br/><?php echo $row["created_on"]; ?></td>
                        <td>
                            <a href="#" class="text-primary mr-2 edit-btn" data-id="<?php echo $row['id']; ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete-sales.php?id=<?php echo $row['id']; ?>&inventoryType=<?php echo urlencode($inventoryType); ?>" class="text-danger delete-btn mr-2">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            <a href="#" class="text-warning mr-2 stock-btn" data-id="<?php echo $row['id']; ?>">
    <i class="fas fa-box"></i>
</a>

                            <!-- Stock Details Icon -->
                            <a href="manage-stocks.php?id=<?php echo $row['id']; ?>" class="text-info stock-details-btn">
                                <i class="fas fa-clipboard-check"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="6">No Records found</td>
                    </tr>
                    <?php
                    }
                    ?>   
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include the Modal File at the Bottom, Outside the Table Structure -->
    <?php include 'add-stock.php'; ?>

<?php
}
?>
