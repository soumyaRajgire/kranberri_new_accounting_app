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
 
 <?php
if(isset($_POST['submit']))
{
    include("config.php");

    $name=mysqli_escape_string($conn,$_POST["name"]);
    $business_name= mysqli_escape_string($conn,$_POST["business_name"]);
    $panno= mysqli_escape_string($conn,$_POST["panno"]);
   $gst_reg_type=mysqli_escape_string($conn,$_POST["gst_reg_type"]);
   $gst_no = mysqli_escape_string($conn,$_POST["gst_no"]);
   $address1 = mysqli_escape_string($conn,$_POST["address1"]);
   $address2 = mysqli_escape_string($conn,$_POST["address2"]);
   $city=mysqli_escape_string($conn,$_POST["city"]);
   $state=mysqli_escape_string($conn,$_POST["state"]);
   $pincode=mysqli_escape_string($conn,$_POST["pincode"]);
   $country=mysqli_escape_string($conn,$_POST["country"]);
   $phno=mysqli_escape_string($conn,$_POST["phno"]);
   $mobno=mysqli_escape_string($conn,$_POST["mobno"]);
   $email=mysqli_escape_string($conn,$_POST["email"]);
 $address = $address1." ".$address2;
 $createdBy =  $_SESSION['name'];
       $sql = "insert into customers(name,business_Name,PanNo,gst_reg_type,gst_no,address,city,state,pincode,country,phone,mobile,email,createdBy) values('$name','$business_name','$panno','$gst_reg_type','$gst_no','$address','$city','$state','$pincode','$country','$phno','$mobno','$email','$createdBy')";

            if ($conn->query($sql) === TRUE) 
          {
              ?>
              <script>
            
                alert("Customer details Added Successfully");
                window.location="view-customers.php";
        </script>
              <?php
            } 
          else 
          {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
 

}
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
                            <h4 class="m-b-10">Add Customers</h4>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Add Customers</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
          <!-- [ stiped-table ] start -->
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <!-- <h5>Register Student Details for Admission Enquiry</h5> -->
                        <!-- <span class="d-block m-t-5">use class <code>table-striped</code> inside table element</span> -->
                        <a  href="view-customers.php" class="btn btn-info" style="color: #fff !important;float:right;" /> View Customers</a>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                          
  <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    
                    <div class="card-body">
                         <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
                      <h5><b>Basic Info</b></h5><hr/>
                    <div class="row col-lg-12">
                        
                        <div class="col-md-4 form-group">
                            <label>Name</label>
                            <input class="form-control" name="name" type="text" Placeholder="Name" required>
                        </div>
                         <div class="col-md-4 form-group">
                            <label>Business Name</label>
                           <input class="form-control" type="text" name="business_name"  placeholder="Business Name">
                        </div>
                         <div class="col-md-4 form-group">
                         <label>PAN No</label>
                        <input class="form-control" type="text" name="panno"  placeholder="PAN NO">
                        </div>
                        </div> 
                       
                    
                      <div class="row col-lg-12">
                          <div class="col-md-4 form-group">
                            <label>GST Reg. Type</label>
                             <select class="form-control" name="gst_reg_type"  placeholder="GST Reg. Type">
                            <option value="Unregistered">Unregistered/Consumer</option>
                            <option value="regular">Regular</option>
                            <option value="composition">Composition</option>
                          </select>
                        </div>
                        <div class="col-md-4 form-group">
                         <label>GST NO</label>
                        <input class="form-control" type="text" name="gst_no"  placeholder="GST NO">
                        </div>
                      </div>
                      <hr/>
                      <h5><b>Contact Info</b></h5><hr/>
                       <div class="row col-lg-12">      
                        <div class="col-md-4 form-group">
                            <label>Address1 </label>
                           <input class="form-control" type="text" name="address1"  placeholder="Address1"/>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Address2 </label>
                           <input class="form-control" type="text" name="address2"  placeholder="Address2"/>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>City</label>
                           <input class="form-control" type="text" name="city"  placeholder="City">
                        </div> 
                        
                      </div>
                     
                      <div class="row col-lg-12">
                          <div class="col-md-4 form-group">
                            <label>State</label>
                          <select class="form-control" name="state">
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
<option value="Arunachal Pradesh">Arunachal Pradesh</option>
<option value="Assam">Assam</option>
<option value="Bihar">Bihar</option>
<option value="Chandigarh">Chandigarh</option>
<option value="Chhattisgarh">Chhattisgarh</option>
<option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
<option value="Daman and Diu">Daman and Diu</option>
<option value="Delhi">Delhi</option>
<option value="Lakshadweep">Lakshadweep</option>
<option value="Puducherry">Puducherry</option>
<option value="Goa">Goa</option>
<option value="Gujarat">Gujarat</option>
<option value="Haryana">Haryana</option>
<option value="Himachal Pradesh">Himachal Pradesh</option>
<option value="Jammu and Kashmir">Jammu and Kashmir</option>
<option value="Jharkhand">Jharkhand</option>
<option value="Karnataka">Karnataka</option>
<option value="Kerala">Kerala</option>
<option value="Madhya Pradesh">Madhya Pradesh</option>
<option value="Maharashtra">Maharashtra</option>
<option value="Manipur">Manipur</option>
<option value="Meghalaya">Meghalaya</option>
<option value="Mizoram">Mizoram</option>
<option value="Nagaland">Nagaland</option>
<option value="Odisha">Odisha</option>
<option value="Punjab">Punjab</option>
<option value="Rajasthan">Rajasthan</option>
<option value="Sikkim">Sikkim</option>
<option value="Tamil Nadu">Tamil Nadu</option>
<option value="Telangana">Telangana</option>
<option value="Tripura">Tripura</option>
<option value="Uttar Pradesh">Uttar Pradesh</option>
<option value="Uttarakhand">Uttarakhand</option>
<option value="West Bengal">West Bengal</option>
</select>
                          </select>
                        </div>
                        <div class="col-md-4 form-group">
                         <label>Pincode</label>
                        <input class="form-control" type="number" name="pincode"  placeholder="Pincode">
                        </div>
                        <div class="col-md-4 form-group">
                         <label>Country</label>
                        <input class="form-control" type="text" name="country"  placeholder="Country">
                        </div>
                      </div>

                      <div class="row col-lg-12">
                          <div class="col-md-4 form-group">
                            <label>Phone No</label>
                           <input class="form-control" type="tel" name="phno"  placeholder="Phone No">
                        </div>
                        <div class="col-md-4 form-group">
                         <label>Mobile No</label>
                        <input class="form-control" type="tel" name="mobno"  placeholder="Mobile No">
                        </div>
                        <div class="col-md-4 form-group">
                         <label>Email</label>
                        <input class="form-control" type="email" name="email"  placeholder="Email">
                        </div>
                      </div>

                      
                        <!-- <div class="form-group">
                            <label>Profile Image</label>
                            <input type="file" name="image" value="" accept="Images/*" onchange="preview_image(event)" />
                        </div> -->
                        
                           <div class="col-lg-10">                     
                        <input type="submit" class="btn btn-primary" name="submit"  value="Submit"/>
                        <input type="reset" class="btn btn-danger" name="cancel" value="Cancel"/>
                   </div>
                   
                    </form>
                    </div>
                </div>
            </div>
            <!-- [ form-element ] start -->
          
            <!-- [ form-element ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
</section>
<!-- [ Main Content ] end -->
    <!-- Warning Section start -->
    <!-- Older IE warning message -->
    <!--[if lt IE 11]>
        <div class="ie-warning">
            <h1>Warning!!</h1>
            <p>You are using an outdated version of Internet Explorer, please upgrade
               <br/>to any of the following web browsers to access this website.
            </p>
            <div class="iew-container">
                <ul class="iew-download">
                    <li>
                        <a href="http://www.google.com/chrome/">
                            <img src="assets/images/browser/chrome.png" alt="Chrome">
                            <div>Chrome</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.mozilla.org/en-US/firefox/new/">
                            <img src="assets/images/browser/firefox.png" alt="Firefox">
                            <div>Firefox</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.opera.com">
                            <img src="assets/images/browser/opera.png" alt="Opera">
                            <div>Opera</div>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.apple.com/safari/">
                            <img src="assets/images/browser/safari.png" alt="Safari">
                            <div>Safari</div>
                        </a>
                    </li>
                    <li>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="assets/images/browser/ie.png" alt="">
                            <div>IE (11 & above)</div>
                        </a>
                    </li>
                </ul>
            </div>
            <p>Sorry for the inconvenience!</p>
        </div>
    <![endif]-->
    <!-- Warning Section Ends -->

    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>



</body>
</html>
