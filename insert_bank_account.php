<?php
// Database connection
include 'config.php';

// Create a MySQLi connection
// $conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $accountHolder = $_POST['accountHolder'];
    $nickname = $_POST['nickname'];
    $accountNo = $_POST['accountNo'];
    $ifsc = $_POST['ifsc'];
    $bank = $_POST['bank'];
    $branch = $_POST['branch'];
    $accountType = $_POST['accountType'];

    // Prepare the SQL query
    $sql = "INSERT INTO bank_accounts (account_holder, nickname, account_no, ifsc, bank, branch, account_type) 
            VALUES ('$accountHolder', '$nickname', '$accountNo', '$ifsc', '$bank', '$branch', '$accountType')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Redirect to index.php
        header("Location: index.php");
        exit(); // Make sure to exit after the redirect
    } else {
        echo "Error inserting record: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
