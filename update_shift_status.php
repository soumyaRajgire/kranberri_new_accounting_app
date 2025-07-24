<?php
// Include your database connection logic here
include("config.php");

// Check if the required POST variables are set
if (isset($_POST['shiftId']) && isset($_POST['newStatus'])) {
    // Get form data
    $shiftId = $_POST['shiftId'];
    $newStatus = $_POST['newStatus'];

    // Prepare the SQL statement
    $stmtUpdate = $conn->prepare("UPDATE shifts SET status = ? WHERE id = ?");

    // Bind parameters to the prepared statement
    $stmtUpdate->bind_param("si", $newStatus, $shiftId);

    // Execute the update
    $updateResult = $stmtUpdate->execute();

    // Close the statement
    $stmtUpdate->close();

    // Check the result and return a response
    if ($updateResult) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . $stmtUpdate->error;
    }
} else {
    echo "Invalid data sent to the server.";
}

// Close the database connection
$conn->close();
?>
