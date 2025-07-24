<?php
include('config.php');
if (isset($_GET['inventoryType'])) {
    $inventoryType = $_GET['inventoryType'];
?>


    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <div class="card-body table-border-style">

          <!-- <div class="input-group"> -->
            <!-- <div class="input-group-append"> -->
                       <div class="col-md-12" style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                                        <!-- Month and Year Selection -->
                                        <div id="reportrange" class="col-md-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
    <i class="fa fa-calendar"></i>&nbsp;
    <span></span> <i class="fa fa-caret-down"></i>

</div><button id="download-btn" class="btn btn-sm btn-info">Download Report</button>
                    </div>
                    <hr/>
                <!-- </div> -->

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
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                                <!-- Deduct Stock -->
                                <a href="#" class="text-danger mr-2 deduct-stock-btn" data-toggle="modal" data-target="#deductStockModal" 
           data-id="<?php echo $row['id']; ?>" data-current-stock="<?php echo $row['opening_stock']; ?>">
            <i class="fas fa-minus-circle"></i> <!-- New Icon for Deduct Stock -->
        </a>
                               <!--  <a href="" class="text-info stock-details-btn">
                                    <i class="fas fa-eye"></i>
                                </a> -->
                                <!-- View Stock Details -->
<a href="#" class="text-info stock-details-btn" data-toggle="modal" data-target="#viewStockModal" 
   data-id="<?php echo $row['id']; ?>">
    <i class="fas fa-eye"></i>
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


<!-- View Stock Modal -->
<div class="modal fade" id="viewStockModal" tabindex="-1" role="dialog" aria-labelledby="viewStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewStockModalLabel">Stock Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="stock-details-content">
                    <!-- Stock details will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            "ordering": false // Disable sorting completely
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
    // When clicking the eye icon
    $('.stock-details-btn').on('click', function() {
        var productId = $(this).data('id');  // Get Product ID

        // AJAX request to fetch stock details
        $.ajax({ 
            url: 'fetch_stock_details.php', // PHP file to fetch data
            type: 'GET',
            data: { id: productId },
            beforeSend: function() {
                $('#stock-details-content').html('<p>Loading stock details...</p>'); // Show loading text
            },
            success: function(response) {
                $('#stock-details-content').html(response); // Load data into modal
            },
            error: function() {
                $('#stock-details-content').html('<p>Error loading stock details.</p>'); // Show error message
            }
        });
    });
});

</script>
