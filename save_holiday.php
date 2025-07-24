<?php
// Include your database connection logic here
include("config.php");

// Get form data
$holidayName = $_POST['holidayName'];
$holidayDate = $_POST['holidayDate'];
$workingHours = $_POST['workingHours'];
$payInDays = $_POST['payInDays'];

// Prepare the SQL statement
$stmtInsert = $conn->prepare("INSERT INTO holidays (holidayName, holidayDate, workingHours, payInDays)
                              VALUES (?, ?, ?, ?)");

// Bind parameters to the prepared statement
$stmtInsert->bind_param("ssss", $holidayName, $holidayDate, $workingHours, $payInDays);

// Execute the statement
$insertResult = $stmtInsert->execute();

// Close the statement and connection
$stmtInsert->close();
$conn->close();

// Check the result and return a response
if ($insertResult) {
    echo "Holiday inserted successfully.";
} else {
    echo "Error inserting holiday: " . $stmtInsert->error;
}
?>
