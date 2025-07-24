<?php
session_start();
include_once("config.php");

// If "login" button clicked
if (isset($_POST['login'])) {
    // Store username
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    // Store password
    $password = md5($_POST['password']);
    
    // Check username exists
    $usernameQuery = "SELECT * FROM `employee_login` WHERE username = '$username'";
    $runUsernameQuery = mysqli_query($conn, $usernameQuery);

    if (!$runUsernameQuery) {
        die("Query Failed: " . mysqli_error($conn));
    } else {
        if (mysqli_num_rows($runUsernameQuery) > 0) {
            $passwordQuery = "SELECT * FROM `employee_login` WHERE username = '$username' AND password = '$password'";
            $runPasswordQuery = mysqli_query($conn, $passwordQuery);

            if (!$runPasswordQuery) {
                die("Query Failed: " . mysqli_error($conn));
            } else {
                if (mysqli_num_rows($runPasswordQuery) > 0) {
                    $fetchData = mysqli_fetch_assoc($runPasswordQuery);
                    $_SESSION['id'] = $fetchData['id'];
                    $_SESSION['username'] = $fetchData['username'];
                    $_SESSION['name'] = $fetchData['name'];
                    $_SESSION['role'] = $fetchData['role'];
                    $_SESSION['LOGED_IN'] = 'yes';

                    // Debug: Print session variables
                    echo "Session variables set: ";
                    print_r($_SESSION);

                    // Redirect to dashboard.php
                    header("Location: attendance.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Invalid Password";
                    header("Location: login.php");
                    exit();
                }
            }
        } else {
            $_SESSION['error'] = "Invalid username";
            header("Location: login.php");
            exit();
        }
    }
} else if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
}
?>
