<?php
//echo '<script>alert("helooo grom paroduct cat")</script>';
session_start();
// if(isset($_SESSION['ROLE']) && $_SESSION['ROLE']!='1'){
//     header('location:news.php');
//     die();
// }

if(!isset($_SESSION['LOG_IN'])){
   header("Location:login.php");
}
else
{
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
include_once("config.php");
if($_GET['customerID']) {
    
    $pcat=$_GET['customername'];
$c_m_id = $_GET['customerID'];

            // $pmname = explode("|", $pcat);
            $pname = $_GET['customername'];
            $mname = $_GET['mobileNumber'];
        $sql="select * from  customer_master where id ='$c_m_id'";

                $result=$conn->query($sql);
                if($result->num_rows>0)
                {   
                     if($row = mysqli_fetch_assoc($result)) 
                     {
                        // $c_m_id = $row['id'];
                        
                            ?>
                            
<input type="text" name="cst_mstr_id" id="cst_mstr_id" value="<?php echo $c_m_id; ?>" hidden>
<input type="text" name="customer_email" id="customer_email" value="<?php echo $row['email']; ?>" hidden>
                            <?php   
                $sql1="select * from  address_master where customer_master_id = '$c_m_id'";
                
                $result1=$conn->query($sql1);
                if($result1->num_rows>0)    
                    {
                        if($row1 = mysqli_fetch_assoc($result1))
                        {
                            ?>
                            
                <div class="col-md-4 border-left border-bottom border-dark p-3">
                <div>
               <div class="d-flex align-items-center">
    <h6 class="mr-2">Supplier/Customer Info</h6>
    <button type="button" id="edit_button" class="btn btn-primary" onclick="clearInput()">
        <i class="fas fa-edit"></i> 
    </button>
</div>


                <span><?php echo $row['customerName'];?></span><br/>
                                <span>Business Name :<?php echo $row['business_name'] === "" ? "business name": $row['business_name'];?></span><br/>
                                <span><?php echo $row1['s_state']?></span><br/>
                            <input type="text" name="customer_s_state" id="customer_s_state" value="<?php echo $row1['s_state']; ?>" hidden>
<input type="text" name="customer_b_state" id="customer_b_state" value="<?php echo $row1['b_state']; ?>" hidden>
<input type="text" name="tcsTax" id="tcsTax" value="<?php echo $row['tds_slab_rate']; ?>" hidden>
                              <span>GSTIN :<?php echo $row['gstin'] ?></span>
                </div>
                </div>

                 <div class="col-md-4 border-left border-bottom border-dark p-3">
                <div>
                <h6>Billing Address</h6>
                <span><?php echo $row1['b_address_line1'] === "" ? '<span style="color:red;">Adress Line1</span>' : $row1['b_address_line1'];?></span><br/>
                <span><?php echo $row1['b_address_line2'] === "" ? '<span style="color:red;">Adress Line2</span>' : $row1['b_address_line2'];?></span><br/>
                <span><?php echo ($row1['b_city'] === "" ? '<span style="color:red;">City</span>' : $row1['b_city']) . "-". ($row1['b_Pincode'] === "" ? '<span style="color:red;">Pincode</span>': $row1['b_Pincode']) ;?></span><br/>
                </div>
                </div>

                <div class="col-md-4 border-left border-bottom border-right border-dark p-3">
                <h6>Shipping Address</h6>
                <span><?php echo $row1['s_address_line1'] === "" ? '<span style="color:red;">Adress Line1</span>' : $row1['s_address_line1'];?></span><br/>
                <span><?php echo $row1['s_address_line2'] === "" ? '<span style="color:red;">Adress Line2</span>' : $row1['s_address_line2'];?></span><br/>
                <span><?php echo ($row1['s_city'] === "" ? '<span style="color:red;">City</span>' : $row1['s_city']) . "-". ($row1['s_Pincode'] === "" ? '<span style="color:red;">Pincode</span>': $row1['s_Pincode']) ;?></span><br/>
                </div>
                </div>
                                

                            <?php
                        }
                    }
                    }
                }
                
                    }
                            
                        
?>