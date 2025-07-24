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
if($_GET['productname']) {
	$pcat=$_GET['productname'];
	$pid = $_GET['productid'];		 	
		$sql="select * from  inventory_master where id='$pid' AND name = '$pcat' ";
							$result=$conn->query($sql);
							if($result->num_rows>0)
							{
								
								 if($row = mysqli_fetch_assoc($result)) 
								 {
								 	
								 	$p= $row["net_price"];
								 	$t= explode('/', $p);
								 	$price = $row['price'];
									$p1 = $t['0'];
									$t1= floatval(preg_replace('/[^\d. ]/', '', $p1));
									$gst = $row['gst_rate'];
						   		$in_ex_gst = $row['in_ex_gst'];
						   		$cess_rate = $row['cess_rate'];
						   		$cess_amt = $row['cess_amt'];
						   				$hsn_code = $row['hsn_code'];
						   				$units = $row['units'];
						   		
					$response = array(
    'gst' => $gst, // Assign the GST value to $gstValue
    'netprice' => $t1,// Assign the price value to $priceValue
    'price' => $price,
    'in_ex_gst' => $in_ex_gst,
    'cess_rate' => $cess_rate,
    'cess_amt' => $cess_amt,
    'hsn_code' => $hsn_code,
    	'units' => $units
   
);

// Encode the response array to JSON and echo it
echo json_encode($response);
					}
				}
				
					}
							
						
?>