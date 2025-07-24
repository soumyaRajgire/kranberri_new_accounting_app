<?php
// Include your database connection logic here
include("config.php");

// Get data from the AJAX request
$departmentId = $_POST['departmentId'];
$newStatus = $_POST['newStatus'];

// Prepare the SQL statement
$stmtUpdate = $conn->prepare("UPDATE departments SET status = ? WHERE id = ?");

// Bind parameters to the prepared statement
$stmtUpdate->bind_param("si", $newStatus, $departmentId);

// Initialize a variable to store the result of the execution
$updateResult = $stmtUpdate->execute();

// Close the statement and connection
$stmtUpdate->close();
$conn->close();

// Check the result and return a response
if ($updateResult) {
    echo "Department status updated successfully.";
} else {
    echo "Error updating department status: " . $stmtUpdate->error;
}
?>
