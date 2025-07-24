<?php
// Include your database connection logic here
include("config.php");

// Get form data
$departmentName = $_POST['departmentName'];

// Prepare the SQL statement
$stmtInsert = $conn->prepare("INSERT INTO departments (departmentName)
                              VALUES (?)");

// Bind parameters to the prepared statement
$stmtInsert->bind_param("s", $departmentName);

// Initialize a variable to store the result of the execution
$insertResult = $stmtInsert->execute();

// Close the statement and connection
$stmtInsert->close();
$conn->close();

// Check the result and return a response
if ($insertResult) {
    echo "Department inserted successfully.";
} else {
    echo "Error inserting department: " . $stmtInsert->error;
}
?>
