<?php
include('config.php');
if (isset($_GET['contact_type'])) {
    $contact_type = $_GET['contact_type'];

   
        // Fetch and display sales catalog table data
        ?>
<div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="dataTables-example">
                                <thead>
                             <tr>
                                     <th>Name</th>
                    <th>Contact Info</th>
                    <th>Tax Information</th>
                    <th>Created BY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                              
                            $sql="select * from customer_master where contact_type ='$contact_type'";
                           
                                 $result=$conn->query($sql);
                                if($result->num_rows>0)
                                    {
                                while($row = mysqli_fetch_assoc($result)) 
                                    {
                                    ?>
                                    <tr>
                                  <!-- <td><?php echo $row["id"] ?></td>  -->
                            <td><?php echo $row["customerName"] ?><br/><?php echo $row["business_name"] === "" ? '<a href="update-customer.php?id=' . $row["id"] . '<a href="update_link">Update</a>' : $row["business_name"];?>
                             </td>
                            
                             <td><?php echo $row["mobile"] === "" ? '<a href="update_link">Update Mobile</a>' : $row["mobile"]; ?><br/><?php echo $row["email"] === "" ? '<a href="update_link">Update Email</a>' : $row["email"]; ?>
                            </td>

                            <td>PAN : <?php echo $row["pan"] === "" ? '<a href="update_link">Update PAN</a>' : $row["pan"]; ?><br/>GSTIN : <?php echo $row["gstin"] === "" ? '<a href="update_link">Update GSTIN</a>' : $row["gstin"]; ?>
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
        <td colspan="3"><?php echo "No Records found";?></td>
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
