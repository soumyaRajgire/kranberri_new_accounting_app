<?php
include('config.php');

if (isset($_GET['branch_id'])) {
    $branch_id = $_GET['branch_id'];

    $sql = "DELETE FROM `add_branch` WHERE `branch_id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $branch_id); // Fixed variable name

    if ($stmt->execute()) {
        echo "<script>alert('Branch deleted successfully!'); window.location.href='manage-business.php';</script>";
    } else {
        echo "<script>alert('Error deleting branch!'); window.location.href='manage-business.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
