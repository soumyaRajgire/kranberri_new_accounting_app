<?php
// include("sme_auth.php");
require_once 'config.php';
require_once 'includes/insert_audit_log.php';
session_start();
// Initialize result message variable
$resultMessage = "";

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Sanitize and store the id parameter
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // SQL query to delete the record with the specified id
    $sql = "DELETE FROM employees_data WHERE id = ?";

    // Prepare the delete statement
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            // Add JavaScript alert to notify the user
         
        $file_path = isset($file_path) ? $file_path : '';
        require_once 'includes/insert_audit_log.php';
        insertAuditLog($conn, "Deleted Employee", $file_path);
            echo "<script>alert('Record deleted successfully'); window.location.href = 'view_employees.php';</script>";
            exit; // Stop further execution
        } else {
            // Set the result message for error
            $resultMessage = "Error deleting record: " . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
     
    } else {
        // Set the result message for error in preparing statement
        $resultMessage = "Error preparing statement: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // If 'id' parameter is not set in the URL
    $resultMessage = "Invalid request. Please provide an 'id' parameter.";
}

// Display the result message
echo $resultMessage;
?>
