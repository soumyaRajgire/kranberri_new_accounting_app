<?php
// Include your database connection logic here
include("config.php");

// Get form data
$shiftType = $_POST['shiftType'];
$shiftName = $_POST['shiftName'];
$applicableFrom = $_POST['applicableFrom'];
$startTime = $_POST['startTime'];
$endTime = $_POST['endTime'];
$permissionTime = $_POST['permissionTime'];
$noOfLimit = $_POST['noOfLimit'];
$lateGrace = $_POST['lateGrace'];
$hours = $_POST['hours'];
$salaryDeduction = $_POST['salaryDeduction'];
$forceCheckoutLimit = $_POST['forceCheckoutLimit'];
$casualLeave = $_POST['casualLeave'];
$sickLeave = $_POST['sickLeave'];


// Prepare the SQL statement
$stmtInsert = $conn->prepare("INSERT INTO shifts (shiftType, shiftName, applicableFrom, startTime, endTime, permissionTime, noOfLimit, lateGrace, hours, salaryDeduction, forceCheckoutLimit, casualLeave, sickLeave)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Check if the statement preparation succeeded
if ($stmtInsert === false) {
    $response = array('message' => 'Error preparing statement: ' . $conn->error);
    echo json_encode($response);
    exit;
}

// Bind parameters to the prepared statement
$stmtInsert->bind_param("sssssssssssss", $shiftType, $shiftName, $applicableFrom, $startTime, $endTime, $permissionTime, $noOfLimit, $lateGrace, $hours, $salaryDeduction, $forceCheckoutLimit, $casualLeave, $sickLeave);

// Execute the statement
$insertResult = $stmtInsert->execute();

// Check if the execution succeeded
if ($insertResult === false) {
    $response = array('message' => 'Error inserting shift: ' . $stmtInsert->error);
    echo json_encode($response);
} else {
    $response = array('message' => 'Shift inserted successfully.');
    echo json_encode($response);
}

// Close the statement and connection
$stmtInsert->close();
$conn->close();
?>
