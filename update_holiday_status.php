<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the request
    $holidayId = $_POST['holidayId'];
    $newStatus = $_POST['newStatus'];

    // Update the status in the database
    $updateSql = "UPDATE holidays SET status = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($updateSql);
    $stmtUpdate->bind_param("si", $newStatus, $holidayId);

    if ($stmtUpdate->execute()) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . $stmtUpdate->error;
    }

    $stmtUpdate->close();
}

$conn->close();
?>
