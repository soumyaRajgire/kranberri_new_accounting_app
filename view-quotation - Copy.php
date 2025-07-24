
<!DOCTYPE html>
<?php
session_start(); 
if(!isset($_SESSION['LOG_IN'])){
   header("Location:login.php");
}
else
{
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
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
                        <a  href="create-quotation.php" class="btn btn-info" style="color: #fff !important;float:right;" />Create</a>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <!-- <table class="table table-striped table-bordered" id="dataTables-example"> -->
                              
                          <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                               <tr>
                                <th hidden>No</th>
                                <th>Invoice Code</th>
                                <th>Customer Name</th>
                                <th>Quotation</th>
                                <th>Created By</th>
                                <th>Created On</th>
                                <td>Review</td>
                                <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                    <?php

                    include("config.php");
                    
                     if(ISSET($_POST['search']))
                     {

     $date1 = date("d-m-Y", strtotime($_POST['date1']));
     $date2 = date("d-m-Y", strtotime($_POST['date2']));
   
   if(isset($_GET["name"]))
   {
     $n=$_GET["name"];
       $query=mysqli_query($conn, "SELECT * FROM quotations WHERE name= '$n' and date1 BETWEEN '$date1' AND '$date2'") or die(mysqli_error());
   }
   else{
     $query=mysqli_query($conn, "SELECT * FROM quotations WHERE date1 BETWEEN '$date1' AND '$date2'") or die(mysqli_error());
   }
    $row1=mysqli_num_rows($query);
    if($row1>0){
      while($fetch=mysqli_fetch_array($query)){
?>
         <tr class="odd gradeX">
                                <td hidden><?php echo $fetch["id"]?></td>
                                <td><?php echo $fetch["invoice_code"]?></td>
                                <td><?php echo $fetch["customer_name"]?></td>
                                <td><a href="<?php echo $fetch["invoice"];?>" >Quotation</a></td>
                                <td><?php echo $fetch["created_by"];?></td>
                                <td><?php echo $fetch["date1"],$fetch["time1"];?></td>
                                <td> <?php if($fetch["review"] == "Converted")
                                { ?>
                                 <td><h5 style="background-color:green;color:white;padding: 4px;width: fit-content;border-radius: 8px;"><?php echo $fetch["review"];?></h5></td>
                                <?php }
                                else if($fetch["review"] == "Not Converted")
                                { ?>
                                <td><h5 style="background-color:red;color:white;padding: 4px;width: fit-content;border-radius: 8px;"><?php echo $fetch["review"];?></h5></td>
                                <?php }
                                else if($fetch["review"] == "Under Negotiation")
                                { ?>
                                 <td><h5 style="background-color:orange;color:white;padding: 4px;width: fit-content;border-radius: 8px;"><?php echo $fetch["review"];?></h5></td>
                                <?php } ?>
                                </td>
                                <td>
                                <!-- <a class="btn btn-info" href="edit_quotation.php?id=<?php echo $row["id"] ?>" name="edit"> <i class="fa fa-pencil-square-o white edit"></i>  </a> -->
                                <a class="" href="edit_quotation_review.php?id=<?php echo $fetch["id"] ?>&review=Not Converted"> <i class="fa fa-circle" style="font-size:18px;color:red"></i> </a><br/>
                                <a class="" href="edit_quotation_review.php?id=<?php echo $fetch["id"] ?>&review=Under Negotiation"> <i class="fa fa-circle" style="font-size:18px;color:orange"></i> </a><br/>
                                <a class=" " href="edit_quotation_review.php?id=<?php echo $fetch["id"] ?>&review=Converted"> <i class="fa fa-circle" style="font-size:18px;color:green"></i></a><br/>
                                <a class="btn btn-danger" style="padding:2px 4px !important;" href="del_quotation.php?id=<?php echo $fetch["id"] ?>"> <i class="fa fa-trash white trash"></i> </a>
                                 <a class="btn btn-info" style="padding:2px 4px !important;" href="edit_quotation.php?ic=<?php echo $fetch["invoice_code"]; ?>" name="edit"> <i class="fa fa-pencil-square-o white edit"></i>  </a>
                                </td>
                            </tr>   
                    <?php
                    }
                }
                else
                {
                    echo '<tr> <td colspan="6"><?php echo "No Records found";?></td></tr>';
                }
        }
    //}
//}
else
{
                    if(isset($_GET["name"]))
                    {
                        $n=$_GET["name"];
                        $sql="select * from quotations where created_by = '$n'";
                    }
                    else
                    {
                        $sql="select * from quotations";
                    }
                    
                    $result=$conn->query($sql);
                    if($result->num_rows>0)
                    {
                    while($row = mysqli_fetch_assoc($result)) 
                    {
                    ?>
                            <tr class="odd gradeX">
                                <td hidden><?php echo $row["id"]?></td>
                                <td><?php echo $row["invoice_code"]?></td>
                                <td><?php echo $row["customer_name"]?></td>
                                <td><a href="<?php echo $row["invoice"];?>" >Quotation</a></td>
                                <td><?php echo $row["created_by"];?></td>
                                <td><?php echo $row["date1"],$row["time1"];?></td>
                               <?php 
                                if($row["review"] == "Converted")
                                { ?>
                                 <td><h5 style="background-color:green;color:white;padding: 4px;width: fit-content;border-radius: 8px;"><?php echo $row["review"];?></h5></td>
                               <?php  }
                                else if($row["review"] == "Not Converted")
                                { ?>
                                <td><h5 style="background-color:red;color:white;padding: 4px;width: fit-content;border-radius: 8px;"><?php echo $row["review"];?></h5></td>
                                <?php }
                                else if($row["review"] == "Under Negotiation")
                                { ?>
                                 <td><h5 style="background-color:orange;color:white;padding: 4px;width: fit-content;border-radius: 8px;"><?php echo $row["review"];?></h5></td>
                                <?php } ?>
                                
                                 <td>
                                 <!-- <a class="btn btn-info" href="edit_quotation.php?id=<?php echo $row["id"] ?>" name="edit"> <i class="fa fa-pencil-square-o white edit"></i>  </a>  -->
                                
                                <a class="" href="edit_quotation_review.php?id=<?php echo $row["id"] ?>&review=Not Converted"> <i class="fa fa-circle" style="font-size:18px;color:red"></i> </a><br/>
                                <a class="" href="edit_quotation_review.php?id=<?php echo $row["id"] ?>&review=Under Negotiation"> <i class="fa fa-circle" style="font-size:18px;color:orange"></i> </a><br/>
                                <a class=" " href="edit_quotation_review.php?id=<?php echo $row["id"] ?>&review=Converted"> <i class="fa fa-circle" style="font-size:18px;color:green"></i></a><br/>
                                <a class="btn btn-danger" style="padding:2px 4px !important;" href="del_quotation.php?id=<?php echo $row["id"] ?>"> <i class="fa fa-trash white trash"></i> </a>

                                 <a class="btn btn-info" style="padding:2px 4px !important;" href="edit_quotation.php?ic=<?php echo $row["invoice_code"]; ?>" name="edit"> <i class="fa fa-pencil-square-o white edit"></i>  </a>
                                </td>
                            </tr>   
                    <?php
                    }
                }
                else
                {
                    echo '<tr> <td colspan="6"><?php echo "No Records found";?></td></tr>';
                }
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
