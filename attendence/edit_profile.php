<?php
// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if not already started
}

include("config.php");

// Ensure the session variable is set and not empty
if (!isset($_SESSION['login_id']) || empty($_SESSION['login_id'])) {
    echo "<p>No session data found. Please log in.</p>";
    exit;
}

$login_id = $_SESSION['login_id'];

// Fetch the current data to populate the form
$sql = "SELECT * FROM employees_data WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $login_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $employee_data = mysqli_fetch_assoc($result);
} else {
    echo "No employee found for the logged-in user.";
    exit;
}

// Handle form submission for updating profile
if (isset($_POST['update_profile'])) {
    // Sanitize and validate input as needed
    $emp_id = $_POST['emp_id'];
    $name = $_POST['name'];
    $officemail = $_POST['officemail'];
    $personalmobile = $_POST['personalmobile'];
    $personalemail = $_POST['personalemail'];
    $doj = $_POST['doj'];
    $branch = $_POST['branch'];
    $accountname = $_POST['accountname'];
    $accountnumber = $_POST['accountnumber'];
    $ifsc = $_POST['ifsc'];
    $accounttype = $_POST['accounttype'];
    $bankname = $_POST['bankname'];
    $bankbranch = $_POST['bankbranch'];
    $aadhar = $_POST['aadhar'];
    $pan = $_POST['pan'];
    $uan = $_POST['uan'];
    $esi = $_POST['esi'];
    $profile_image = $_POST['profile_image'];

    // Update `employees_data` table
    $update_query = "UPDATE employees_data 
                     SET name = ?, officemail = ?, personalmobile = ?, personalemail = ?, doj = ?, branch = ?, accountname = ?, accountnumber = ?, ifsc = ?, accounttype = ?, bankname = ?, bankbranch = ?, aadhar = ?, pan = ?, uan = ?, esi = ?, profile_image = ? 
                     WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sssssssssssssssssi", $name, $officemail, $personalmobile, $personalemail, $doj, $branch, $accountname, $accountnumber, $ifsc, $accounttype, $bankname, $bankbranch, $aadhar, $pan, $uan, $esi, $profile_image, $login_id);
    $update_result = mysqli_stmt_execute($stmt);

    if (!$update_result) {
        // Handle update error
        echo "Error updating profile: " . mysqli_error($conn);
        exit();
    }

    // Redirect to profile page after update
    header("Location: profile.php");
    exit();
}

// Close connection
mysqli_close($conn);
?>
