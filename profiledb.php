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
$result=false;


$b_name= mysqli_real_escape_string($conn,$_POST['b_name']);
$gstin = mysqli_real_escape_string($conn,$_POST['gstin']);
$email = mysqli_real_escape_string($conn,$_POST['email']);

$phone = mysqli_real_escape_string($conn,$_POST['phone']);

$b_address = mysqli_real_escape_string($conn,$_POST['b_address']);
$pincode = mysqli_real_escape_string($conn,$_POST['pincode']);
$state = mysqli_real_escape_string($conn,$_POST['state']);
$b_desc = mysqli_real_escape_string($conn,$_POST['b_desc']);
$business_type = mysqli_real_escape_string($conn,$_POST['business_type']);
$business_cat = mysqli_real_escape_string($conn,$_POST['business_cat']);

$role='admin';
/* to add signature*/
$signature=$_FILES["signature"]["name"];

$dist="./img/".$signature;

$dist1="img/".$signature;

move_uploaded_file($_FILES["signature"]["tmp_name"],$dist);

//$imageFileType = pathinfo($dist1,PATHINFO_EXTENSION);

//to add logo

$logo=$_FILES["logo"]["name"];

$dis="./img/".$logo;

$dis1="img/".$logo;

move_uploaded_file($_FILES["logo"]["tmp_name"],$dis);

//$imageFileType = pathinfo($dis1,PATHINFO_EXTENSION);



if(($_FILES["signature"]["error"]) && ($_FILES["logo"]["error"]))
{

 $sql = "update user_login set name='$b_name',gstin='$gstin',email='$email',phone='$phone',role='$role',address='$b_address',pincode='$pincode',state='$state',Business_desc='$b_desc', business_type='$business_type',business_cat='$business_cat' where id=1";
} 
elseif($_FILES["logo"]["error"] && (!$_FILES["signature"]["error"])) {
 $sql = "update user_login set name='$b_name',gstin='$gstin',email='$email',phone='$phone', role='$role',address='$b_address', pincode='$pincode',state='$state',Business_desc='$b_desc',business_type='$business_type',business_cat='$business_cat',signature='$dist1' where id=1";
}
elseif($_FILES["signature"]["error"] && (!$_FILES["logo"]["error"])){
	 $sql = "update user_login set name='$b_name',gstin='$gstin',email='$email',phone='$phone',logoimage='$dis1', role='$role',address='$b_address', pincode='$pincode',state='$state',Business_desc='$b_desc',business_type='$business_type',business_cat='$business_cat' where id=1";

}
else
{
	 $sql = "update user_login set name='$b_name',gstin='$gstin',email='$email',phone='$phone',logoimage='$dis1', role='$role',address='$b_address', pincode='$pincode',state='$state',Business_desc='$b_desc',business_type='$business_type',business_cat='$business_cat',signature='$dist1' where id=1";
}

 
 //$sql = "update admin_login set name='$name',email='$email',phone='$phone' where id=1";

			if ($conn->query($sql) === TRUE) 
		  {
			  ?>
			  <script>
			window.location="profile.php";
				alert("Successfully Updated ");
		</script>
			  <?php
			} 
		  else 
		  {
            echo "Error: " . $sql . "<br>" . $conn->error;
          }



?>