<?php
include('config.php');
if (isset($_GET['inventoryType'])) {
    $inventoryType = $_GET['inventoryType'];

   
        // Fetch and display sales catalog table data
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
                    </tr>
                </thead>
                <tbody>
            <?php  
            $sql="select * from inventory_master where inventory_type ='$inventoryType'";
                $result=$conn->query($sql);
                if($result->num_rows>0)
                {
                while($row = mysqli_fetch_assoc($result)) 
                {
            ?>
            <tr>
            <td><?php echo $row["name"] ?><br/><?php echo "RS. ".  $row["price"];?>
            </td>
            <td><?php echo $row["description"] === "" ? 
    ($row["catlog_type"] === "products" ? '<a href="update_productsModal.php?id=' . $row["id"] . '">Update</a>' : '<a href="update_servicesModal.php?id=' . $row["id"] . '">Update</a>') : 
    $row["description"]; 
?>

            </td>
            <td>GST Rate : <?php echo $row["gst_rate"];?><br/>
                <?php 
                if($row["catlog_type"] === "products")
                { 
                   echo "HSN:"; echo  $row["hsn_code"] === "" ? '<a href="update_productsModal.php?id='.$row["id"].'">Update </a>' : $row["hsn_code"];
                } elseif($row["catlog_type"] == "services") 
                { 
                    echo "SAC : ";echo ($row["SAC_Code"] === "") ? '<a href="update_servicesModal.php?id='.$row["id"].'">Update</a>' : $row["SAC_Code"]; } ?>
            </td>
            <td><?php echo $row["created_by"] ?><br/><?php echo $row["created_on"] ?>
            </td>
            </tr>
             
            <?php
            }
            }
            else{

            ?>
            <tr>
            <td colspan="4"><?php echo "No Records found";?></td>
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
