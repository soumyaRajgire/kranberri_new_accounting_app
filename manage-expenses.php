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
                            <h4 class="m-b-10">Manage Expenses</h4>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Manage Expenses</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
           <!--add Subject form-->
          <!--  <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Add Subjects</h5>
                        
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST" enctype="multipart/form-data">
                            
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="floating-label" for="std_class">Class</label>
                                        <select class="form-control" id="std_class" name="std_class">
                                            <option value="LKG">LKG </option>
                                            <option value="UKG">UKG</option>
                                            <option value="Class 1">Class 1</option>
                                            <option value="Class 2">Class 2</option>
                                            <option value="Class 3">Class 3</option>
                                            <option value="Class 4">Class 4</option>
                                            <option value="Class 5">Class 5</option>
                                            <option value="Class 6">Class 6</option>
                                            <option value="Class 7">Class 7</option>
                                            <option value="Class 8">Class 8</option>
                                            <option value="Class 9">Class 9</option>
                                            <option value="Class 10">Class 10</option>
                                        </select>
                                        
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="floating-label" for="subject">Subject</label>
                                        <input type="text" class="form-control" name="subject" id="subject" value="">
                                    </div>
                                </div>
                            
                             <input type="submit" name="submit" id="submit" class="btn  btn-primary form-control" value="Submit"/>
                        </form>
                    </div>
                </div>
            </div> -->

           <!--end of subject form-->
          
            <!-- [ stiped-table ] start -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Expenses Report</h5>
                         <a type="button" data-toggle="modal" data-target="#addexpense" class="btn btn-info" style="color: #fff !important;float:right;margin-left:5px;" />Add Expenses</a>
                         <a type="button" data-toggle="modal" data-target="#addclass" class="btn btn-info" style="color: #fff !important;float:right;" />Export</a>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Expense Name</th>
                                        <th>Date</th>
                                        <th>Transaction Details</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                               
                                <tbody>
                                     <?php
                 $sql="select * from manage_expense";
                  $result=$conn->query($sql);

             if($result->num_rows>0)
                {
            while($row = mysqli_fetch_assoc($result)) 
                {

                                    ?>
                                    <tr>
                                        <td><?php echo $row['expense_name']?></td>
                                       <td><?php echo $row['date1']?></td>
                                       <td><b>Bill Reference No :</b><?php echo $row['bill_ref_no']?><br/>
                                        <b>Voucher No :</b><?php echo $row['voucher_no']?><br/>
                                        <b>Payment Mode :</b><?php echo $row['payment_mode']?><br/>

                                       </td>
                                       <td><?php echo $row['amount']?></td>
                                       <td><?php if($row['status'] == 'Pending')
                                       {
                                        ?>
                                        <h5 style="background-color:red;color:white;padding: 4px;width: fit-content;border-radius: 8px;"><?php echo $row["status"];?></h5>
                                       <?php
                                       }
                                       elseif($row['status'] == 'Approved')
                                       {
                                        ?>
                                        <h5 style="background-color:green;color:white;padding: 4px;width: fit-content;border-radius: 8px;"><?php echo $row["status"];?></h5>
                                       <?php
                                       }
                                       ?><br/>
                                           <b>Approved Date :</b><?php echo $row['approved_date']?>
                                       </td>
                                     <!--   <td><?php if($row["status"] == 'active')
                                        {
                                            ?>
                                            <input type="" name="sec_id1" id="sec_id1" value="<?php echo $row["id"]; ?>" hidden>
                                              <a class="" href="javascript:void(0);" id="status_change1" onclick="edit_status1('active');">  <i class="fa fa-circle " style="font-size:18px;color:green;"></i> </a>
                                    
                                            <?php
                                        }
                                        elseif ($row["status"] == 'deactive') {
                                            ?>
                                            <input type="" name="sec_id1" id="sec_id1" value="<?php echo $row["id"]; ?>" hidden>
                                              <a class="" id="status_change1" onclick="edit_status1('deactive');">  <i class="fa fa-circle" style="font-size:18px;color:red"></i> </a> 
                                    
                                        <?php
                                        }
                                        ?>
                                             
                                        </td> --> 
                                        <td><a herf=""><i class="fa fa-edit btn-outline-primary"></i></a>
                                            <a herf=""><i class="fa fa-trash btn-outline-danger"></i></a></td>
                                    </tr>
                                   <?php
                               }
                           }
                           else
                           {
                            ?>
                            <tr><td colspan="6">No Records Found</td></tr>
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


    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>



 <div id="addexpense" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Expense</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                            
                    </div>
                    <div class="modal-body">
                        <div class="">
                        
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                            <div class="row col-md-12">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="floating-label" for="expense_name">Expense Title <span style="color:red">*</span></label>
                                    <input type="text" name="expense_name" id="expense_name" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="floating-label" for="date1">Date<span style="color:red">*</span></label>
                                    <input type="date" name="date1" id="date1" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="floating-label" for="voucher_no">Voucher No</label>
                                    <input type="text" name="voucher_no" id="voucher_no" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="floating-label" for="bill_ref_no">Bill Reference No</label>
                                    <input type="text" name="bill_ref_no" id="bill_ref_no" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="floating-label" for="payment_mode">Payment Mode <span style="color:red">*</span></label>
                                    <select  class="form-control" name="payment_mode" id="payment_mode">
                                        <option>Select Payment Type</option>
                                        <option>Cash</option>
                                        <option>Cheque</option>
                                        <option>DD</option>
                                    </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="floating-label" for="amount">Amount(INR)<span style="color:red">*</span></label>
                                    <input type="text" name="amount" id="amount" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                    <label class="floating-label" for="description">Description</label>
                                    <textarea type="text" name="description" id="description" class="form-control"></textarea>
                                    </div>
                                </div>
                                 
                            </div>

                          
                            <div class="modal-footer">
                              <input type="submit" name="submit1" id="submit1" class="btn  btn-primary"value="Submit"/>
                          </div>
                        </form>
                           
                            
                        </div>
                         
                         
                         
                    </div>
                </div>
            </div>
        </div>

<?php
include("config.php");
if(isset($_POST['submit1']))
{

$expense_name=mysqli_real_escape_string($conn,$_POST['expense_name']);
$date1=mysqli_real_escape_string($conn,$_POST['date1']);
$voucher_no=mysqli_real_escape_string($conn,$_POST['voucher_no']);
$bill_ref_no=mysqli_real_escape_string($conn,$_POST['bill_ref_no']);
$payment_mode=mysqli_real_escape_string($conn,$_POST['payment_mode']);
$amount=mysqli_real_escape_string($conn,$_POST['amount']);
$description=mysqli_real_escape_string($conn,$_POST['description']);
$id="";
$status ="Pending";
$approved_date="";


    

    if(($expense_name != "") && ($payment_mode != "") && ($amount != ""))
 {

    $result=mysqli_query($conn,"select id from manage_expense where id=(select max(id) from manage_expense)");
    if($row=mysqli_fetch_array($result))
    {
        $id=$row['id']+1;
    }
$sql = "insert into manage_expense(id,expense_name,date1,voucher_no,bill_ref_no,payment_mode,amount,description,status,approved_date) values('$id','$expense_name','$date1','$voucher_no','$bill_ref_no','$payment_mode','$amount','$description','$status','$approved_date')";

     if ($conn->query($sql) === TRUE) 
          {
              ?>
              <script>
            
                alert("Successfully added Record");
                window.location="manage-expenses.php";
        </script>
              <?php
            } 
          else 
          {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }

 }
 else
 {
     ?>
       <script>
          alert("Please Enter all the fields");
                // window.location="add-route.php";
        </script>
      <?php
 }


}

?>



  <script type="text/javascript">
        $(document).ready(function(){  
    // code to get all records from table via select box
    $("#category").change(function() {   
    
        var category = $(this).find(":selected").val();
        //var academic_year = document.getElementById("academic_year").value;
        var dataString = 'category='+ category ;   
        //alert(cat_type);  
        $.ajax({
            url: 'get-class-category.php',
            Type: "GET",
            //data:{"cat_id" : cat_id, "cat_type":cat_type}
            data: dataString,  
            //cache: false,
            success: function(data) {
               
                    $("#class_category").html(data);
                //}     
            } 
        });
    }) 
});
    </script>


  <script type="text/javascript">

function edit_status(category)
{

var sec_id=document.getElementById('sec_id').value;

 var dataString = 'category='+ category+'&sec_id='+sec_id; 
 alert(sec_id);
 alert(dataString);
  $.ajax({
            url: 'edit_status.php',
            Type: "GET",
            //data:{"cat_id" : cat_id, "cat_type":cat_type}
            data: dataString,  
            //cache: false,
            success: function(data) {
               
                     $("#status_change").html(data);
                //}     
            } 
        });
}


    </script>

      <script type="text/javascript">

function edit_status1(category)
{

var sec_id=document.getElementById('sec_id1').value;

 var dataString = 'category='+ category+'&sec_id='+sec_id; 
 alert(sec_id);
 alert(dataString);
  $.ajax({
            url: 'edit_status1.php',
            Type: "GET",
            //data:{"cat_id" : cat_id, "cat_type":cat_type}
            data: dataString,  
            //cache: false,
            success: function(data) {
               
                     $("#status_change1").html(data);
                //}     
            } 
        });
}


    </script>
</body>
</html>
