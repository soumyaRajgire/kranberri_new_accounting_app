<?php
// Include your database connection logic here
include("config.php");

// Get form data
$designationName = $_POST['designationName'];

// Prepare the SQL statement
$stmtInsert = $conn->prepare("INSERT INTO designations (designationName)
                              VALUES (?)");

// Bind parameters to the prepared statement
$stmtInsert->bind_param("s", $designationName);

// Initialize a variable to store the result of the execution
$insertResult = $stmtInsert->execute();

// Close the statement and connection
$stmtInsert->close();
$conn->close();

// Check the result and return a response
if ($insertResult) {
    echo "Designation inserted successfully.";
} else {
    echo "Error inserting designation: " . $stmtInsert->error;
}
?>
