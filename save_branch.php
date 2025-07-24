<?php
// Include your database connection logic here
include("config.php");

// Get form data
$officeName = $_POST['officeName'];
$addressLine1 = $_POST['addressLine1'];
$addressLine2 = $_POST['addressLine2'];
$city = $_POST['city'];
$state = $_POST['state'];
$pincode = $_POST['pincode'];
$country = $_POST['country'];

// Prepare the SQL statement
$stmtInsert = $conn->prepare("INSERT INTO branches (officeName, addressLine1, addressLine2, city, state, pincode, country)
                              VALUES (?, ?, ?, ?, ?, ?, ?)");

// Bind parameters to the prepared statement
$stmtInsert->bind_param("sssssss", $officeName, $addressLine1, $addressLine2, $city, $state, $pincode, $country);

// Initialize a variable to store the result of the execution
$insertResult = $stmtInsert->execute();

// Close the statement and connection
$stmtInsert->close();
$conn->close();

// Check the result and return a response
if ($insertResult) {
    echo "Data inserted successfully.";
} else {
    echo "Error inserting data: " . $stmtInsert->error;
}
?>
