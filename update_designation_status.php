<?php
// Include your database connection logic here
include("config.php");

// Get form data
$designationId = $_POST['designationId'];
$newStatus = $_POST['newStatus'];

// Prepare the SQL statement
$stmtUpdate = $conn->prepare("UPDATE designations SET status = ? WHERE id = ?");

// Bind parameters to the prepared statement
$stmtUpdate->bind_param("si", $newStatus, $designationId);

// Execute the update
$updateResult = $stmtUpdate->execute();

// Close the statement and connection
$stmtUpdate->close();
$conn->close();

// Check the result and return a response
if ($updateResult) {
    echo "Status updated successfully.";
} else {
    echo "Error updating status: " . $stmtUpdate->error;
}
?>
