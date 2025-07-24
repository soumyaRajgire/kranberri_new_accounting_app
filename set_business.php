<?php
session_start();
if (isset($_POST['business_id'])) {
    $_SESSION['business_id'] = $_POST['business_id'];

    if (isset($_POST['branch_id'])) {
        $_SESSION['branch_id'] = $_POST['branch_id'];
    }

    // Redirect to index.php (dashboard)
    header("Location: index.php");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
