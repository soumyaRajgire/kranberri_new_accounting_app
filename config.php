<?php

// $host="localhost";
// $username="jyothilanduser";
// $pass="BTds{%L+H^Gd";
// $db="jyothilanddb";
		
$host="localhost";
$username="root";
$pass="";
$db="kothari";
		
		$conn=new mysqli($host,$username,$pass,$db);
		
		if($conn->connect_error)
		{
			die("connection failed:" . $conn->connect_error);
		}
		?>