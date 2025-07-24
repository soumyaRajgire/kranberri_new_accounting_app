<?php
include('config.php');
if (isset($_GET['inventoryType'])) {
    $inventoryType = $_GET['inventoryType'];
?>


    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="card-body table-border-style">
        <div class="table-responsive">
            <table class="table table-striped" id="dataTables-example">
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
                    $sql = "SELECT * FROM inventory_master WHERE inventory_type ='$inventoryType' ORDER BY id DESC";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                    <td>
    <!-- Display icon based on catlog_type -->
    <?php if ($row['catlog_type'] == 'products') { ?>
        <i class="fas fa-box text-primary"></i> <!-- Product Icon -->
    <?php } elseif ($row['catlog_type'] == 'services') { ?>
        <i class="fas fa-concierge-bell text-success"></i> <!-- Service Icon -->
    <?php } ?>

    <!-- Make the name clickable -->
    <a href="product_details.php?id=<?php echo $row['id']; ?>" class="text-decoration-none">
        <?php echo $row["name"]; ?>
    </a>
    <br/>
    &nbsp; &nbsp; &nbsp;<?php echo "RS. " . $row["price"]; ?>
</td>

                        <td><?php echo $row["description"]; ?></td>
                        <td>GST Rate: <?php echo $row["gst_rate"]; ?></td>
                        <td><?php echo $row["created_by"]; ?><br/><?php echo $row["created_on"]; ?></td>
                        <td>
                            <!-- Always display Edit and Delete icons -->
                            <a href="#" class="text-primary mr-2 edit-btn" data-id="<?php echo $row['id']; ?>" data-type="<?php echo $row['catlog_type']; ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete-sales.php?id=<?php echo $row['id']; ?>&inventoryType=<?php echo urlencode($inventoryType); ?>" class="text-danger delete-btn mr-2">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            
                            <!-- Conditionally display additional icons for products -->
                            <?php if ($row['catlog_type'] == 'products') { ?>
                                <a href="#" class="text-warning mr-2 stock-btn" data-toggle="modal" data-target="#goodsAdditionModal" 
                                   data-id="<?php echo $row['id']; ?>" data-opening-stock="<?php echo $row['opening_stock']; ?>">
                                    <i class="fas fa-box"></i>
                                </a>
                                <a href="manage-stocks.php?id=<?php echo $row['id']; ?>" class="text-info stock-details-btn">
                                    <i class="fas fa-clipboard-check"></i>
                                </a>
                            <?php } ?>
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

<?php
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            "ordering": false // Disable sorting completely
        });
    });
</script>
