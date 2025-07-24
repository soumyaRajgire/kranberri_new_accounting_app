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
 


$old_pwd=$_POST["old_pwd"];
$new_pwd=$_POST["new_pwd"];
$con_pwd=$_POST["con_pwd"];

if($conn->connect_error)
		{
			die("connection failed:" . $conn->connect_error);
		}

	$sql="select * from admin_login";
		$result=$conn->query($sql);
			 if($row = mysqli_fetch_assoc($result)) 
			 {
				 $pass=$row["password"];
				
				 
			 }
			 
			 if($pass==$old_pwd)
			 {
				 $sql = "update admin_login set password='$new_pwd' where id=1";
					if ($conn->query($sql) === TRUE) 
					{
						?>
							<script>
							window.location="index.php";
								alert("Successfully Updated password");
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
							window.location="profile.php";
							alert("Old Password doesn't match");
							</script>
				 
				 <?php
				 
			 }
			 


?>