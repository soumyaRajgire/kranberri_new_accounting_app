<?php
session_start();

// Check if business_id is set
if (isset($_POST['business_id'])) {
    $_SESSION['business_id'] = $_POST['business_id'];
    $_SESSION['branch_id'] = $_POST['branch_id'];
    
     $_SESSION['sel_gstin'] = $_POST['sel_gstin'];
     
}

// Redirect to the page where you want to display business or branch data
header("Location: index.php");
exit();
?>
