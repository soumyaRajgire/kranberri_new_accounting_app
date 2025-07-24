<?php
// include_once("login.php");
// $statusOffline = "Offline";
// $logoutQuery = "UPDATE `admin_login` SET status = '{$statusOffline}' WHERE id = '{$_SESSION["id"]}'";
// $runLogoutQuery = mysqli_query($conn, $logoutQuery);

// if($runLogoutQuery){
//     session_start();
//     session_unset($_SESSION["id"]);
//     session_destroy();
//     header("location: login.php");
// }


session_start();
unset($_SESSION['email']);
session_destroy();

header("Location: login.php");
exit;
?>