
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
    <title>iiiQbets</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <?php include("header_link.php");?>
   
    
    

</head>
<body class="">
    <!-- [ Pre-loader ] start -->
     
     <?php include("menu.php");?>
    
    
    <!-- [ Header ] end -->
    

    

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
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Customers</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
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
                        <h5>View Customers Details</h5>
                   
                        <!-- <span class="d-block m-t-5">use class <code>table-striped</code> inside table element</span> -->
                        <a  href="add-customer.php" class="btn btn-info" style="color: #fff !important;float:right;" />Add Customer</a>
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
                                    $sql="select * from customers";
                                    $result=$conn->query($sql);

                                if($result->num_rows>0)
                                    {
                                while($row = mysqli_fetch_assoc($result)) 
                                    {
                                    ?>
                                    <tr>
                                  <!-- <td><?php echo $row["id"] ?></td>  -->
                                 <td><?php echo $row["name"] ?><br/><?php echo $row["business_Name"] ?></td>
                                  <td><?php echo $row["phone"] ?><br/><?php echo $row["email"] ?></td>
                                  <td>PAN : <?php echo $row["PanNo"] ?><br/>GSTIN : <?php echo $row["gst_no"] ?></td>
                                  <td><?php echo $row["createdBy"] ?><br/><?php echo $row["createdON"] ?></td>
                                 
                                 
<!-- <td><form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" name="action-change" method="POST" id="action-change" style="display: flex;" >
        <input type="hidden" value="<?php echo $row["id"] ?>" name="id1"/>
        <select name="enq_status" id="enq_status" class="form-control" style="width:100% !important;"  onchange="checkvalue(this.value,'<?php echo $row["id"]?>')">
            
            <option value="<?php echo $row["enquiry_status"] ?>" selected><?php echo $row["enquiry_status"] ?></option>
           <option value="Follow Up">Follow Up</option>
            <option value="Intrested/Registration Done"> Intrested/Registration Done</option>
            <option value="Not Intrested">Not Intrested</option>
            <option value="Not Eligible">Not Eligible</option>
             <option value="Confirmed Taken Admission">Confirmed Taken Admission</option>
            <option value="Financial Issue">Financial Issue </option>
             <option value="Other"> Other</option>
        </select>
        <br/>
        <?php 
        if($row["enquiry_status"] === "Other")
        {
        ?>
        <textarea name="enq_sts_cmnt" id="enq_sts_cmnt<?php echo $row["id"] ?>" class="form-control" rows="1" cols="30" style="display:block;" value=""><?php echo $row["enq_sts_cmnt"];?></textarea>
        <?php
        }
        else{
            
        ?>
         <textarea name="enq_sts_cmnt" id="enq_sts_cmnt<?php echo $row["id"] ?>" class="form-control" rows="1" cols="30" style="display:none;" value=""></textarea>
         <?php
        }
         ?>
         <br/>
          <?php 
        if($row["enquiry_status"] === "Follow Up")
        {
        ?>
        <input type="date" class="form-control" name="follow_up_date" id="follow_up_date<?php echo $row["id"] ?>" style="display: block;" value="<?php echo $row["follow_up_date"] ?>">
      
        <?php
        }
        else{
            
        ?>
        <input type="date" class="form-control" name="follow_up_date" id="follow_up_date<?php echo $row["id"] ?>" style="display: none;" value=""/>
        
         <?php
        }
         ?><br/>
        <button type="submit" name="action_submit" class="btn  btn-success" style="margin-left: 2%"><i class="fa fa-save btn-outline-success" style="color:white;"></i></button>
    </form></td> -->

                                 <!-- <td><?php echo $row["follow_up_date"] ?></td> -->
                                 <!-- <td><?php echo $row["remark"] ?></td> -->
                                 <td>
                                    <a href="update-customer.php?id=<?php echo $row["id"];?>"><i class="fa fa-edit btn-outline-primary"></i></a>
                                    <a href="delete-customer.php?id=<?php echo $row["id"];?>"><i class="fa fa-trash btn-outline-danger"></i></a>
                                    <a type="button" data-toggle="modal" data-target="#viewDetails<?php echo $row['id'] ?>"><i class="fa fa-eye btn-outline-success"></i></a>
                                        </td>
                                        
                                    </tr>
                             
                                    <div id="viewDetails<?php echo $row['id'] ?>" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">View Details</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                            
                    </div>
                    <div class="modal-body">
                        <div class="row col-md-12">
                            <div class="col-md-10">
                               
                                <!-- <h6> Id : <?php echo $row['id']; ?></h6> -->
                         <h6>Name : <?php echo $row['name']; ?></h6>
                         <h6>Business Name : <?php echo $row['business_Name']; ?></h6>
                         <h6>PAN : <?php echo $row["PanNo"] ?></h6>
                         <h6>GST Type : <?php echo $row['gst_reg_type']; ?></h6>
                         <h6>GSTNo : <?php echo $row['gst_no']; ?></h6>
                         <h6>Address : <?php echo $row['address'] . " " .$row['city']." ". $row['state']." ".$row['country']."".$row['pincode']?></h6>
                         <h6>Conatct No : <?php echo $row['phone'] ." , " . $row['mobile']   ?></h6>
                         <h6>Email : <?php echo $row['email']; ?></h6>
                         
                         <h6>Created : <?php echo $row['createdBy'] . " , " . $row['createdON']  ?></h6>
                         </div>
                           
                        </div>
                         
                         
                         
                    </div>
                </div>
            </div>
        </div>
                                     <?php


                       }
                   }
                   else{

                     ?>
               <tr>
        <td colspan="5"><?php echo "No Records found";?></td>
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


<?php
if(isset($_POST["action_submit"]))
{
    if(isset($_POST["enq_status"]))
    {
        $id=$_POST["id1"];
        $enq_status=$_POST["enq_status"];
        if($enq_status === "Other")
        {
            $enq_sts_cmnt=$_POST["enq_sts_cmnt"];
        }
        else{
            $enq_sts_cmnt=" ";
        }

         if($enq_status === "Follow Up")
        {
            $follow_up_date=$_POST["enq_sts_cmnt"];
        }
        else{
            $follow_up_date=" ";
        }
        if($conn->connect_error)
        {
             echo "Failed to connect";
        }
        else
        {
            $sql = "update admission_enquiry set enquiry_status='$enq_status',enq_sts_cmnt='$enq_sts_cmnt',follow_up_date='$follow_up_date' where id=$id";
        
        if ($conn->query($sql) === TRUE) 
          {?>
            <script type="text/javascript">
                alert(" Updated successfully");
                window.location="view-admission-enquiry.php";
            </script>

           <?php } 
          else 
          {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
          }
$conn->close();
    
        
    }
}

?>


<script>
    function checkvalue(val,c)
{
  
    var cc="enq_sts_cmnt"+c;
    var dd = "follow_up_date"+c;
    if(val === "Other")
    {
        if(document.getElementById(cc).style.display === "none")
        {
        document.getElementById(cc).style.display = 'block';
        }
        if(document.getElementById(dd).style.display === "block")
        {
        document.getElementById(dd).style.display = 'none';
        }
    }
    else
    {
       document.getElementById(cc).style.display="none"; 
    }

    if(val === "Follow Up")
    {
        if(document.getElementById(dd).style.display === "none")
        {
        document.getElementById(dd).style.display = 'block';
        }
    }
    else
    {
       document.getElementById(dd).style.display="none"; 
    }
}
</script>

    <!-- Required Js -->

 <!-- <script src="assets/js/jquery.min.js"></script> -->

        <!-- Bootstrap Core JavaScript -->
        <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
       <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
 <script type="text/javascript">
            $(document).ready(function () {
  $('#dataTables-example').DataTable();
  $('.dataTables_length').addClass('bs-select');

});
            $('#dataTables-example').dataTable( {
    "orderFixed": [ 3, 'asc' ]
} );
        </script>
</body>
</html>
