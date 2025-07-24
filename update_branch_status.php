<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the request
    $branchId = $_POST['branchId'];
    $newStatus = $_POST['newStatus'];

    // Update the status in the database
    $updateSql = "UPDATE branches SET status = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($updateSql);
    $stmtUpdate->bind_param("si", $newStatus, $branchId);

    if ($stmtUpdate->execute()) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . $stmtUpdate->error;
    }

    $stmtUpdate->close();
}

$conn->close();
?>
