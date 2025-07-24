<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config.php'); // Ensure this file contains your database connection details

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user details from employees_data table
$sql = "SELECT * FROM employees_data WHERE officemail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$imagePath = "../" . htmlspecialchars($user['image_path']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System</title>
    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .img-radius {
            border-radius: 50%;
            max-width: 50px; /* Adjust size as needed */
        }
    </style>
</head>
<body>
<!-- [ Pre-loader ] start -->
<!-- <div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div> -->
<!-- [ Pre-loader ] End -->
<!-- [ navigation menu ] start -->
<nav class="pcoded-navbar">
    <div class="navbar-wrapper">
        <div class="navbar-content scroll-div">
            <div class="">
                <div class="main-menu-header">
                    <!-- Corrected the image path and used htmlspecialchars to avoid XSS attacks -->
                    <img class="img-radius" src="<?php echo $imagePath; ?>" alt="User-Profile-Image">
                    <div class="user-details">
                        <span><?php echo htmlspecialchars($username); ?></span>
                    </div>
                </div>
                <div class="collapse" id="nav-user-link">
                    <ul class="list-unstyled">
                        <li class="list-group-item"><a href="profile.php?username=<?php echo htmlspecialchars($username); ?>"><i class="feather icon-user m-r-5"></i>View Profile</a></li>
                        <li class="list-group-item"><a href="#!"><i class="feather icon-settings m-r-5"></i>Settings</a></li>
                        <li class="list-group-item"><a href="logout.php"><i class="feather icon-log-out m-r-5"></i>Logout</a></li>
                    </ul>
                </div>
            </div>

            <!-- Navigation Menu -->
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item">
                    <a href="attendance.php" class="nav-link "><span class="pcoded-micon"><i class="fas fa-users"></i></span><span class="pcoded-mtext">Attendance</span></a>
                </li>
                <li class="nav-item">
                    <a href="calendar.php" class="nav-link "><span class="pcoded-micon"><i class="fas fa-calendar-alt"></i></span><span class="pcoded-mtext">Calendar</span></a>
                </li>
                <li class="nav-item">
                    <a href="monthly_attendance.php" class="nav-link "><span class="pcoded-micon"><i class="fas fa-calendar-check"></i></span><span class="pcoded-mtext">Monthly Attendance</span></a>
                </li>
            </ul>
            <!-- Navigation Menu End -->
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->
<!-- [ Header ] start -->
<header class="navbar pcoded-header navbar-expand-lg navbar-light header-dark">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        <a href="#!" class="b-brand">
            <!-- Change your logo here -->
            <img src="assets/images/logo.png" alt="" class="logo" width="87px">
            <img src="assets/images/logo-icon.png" alt="" class="logo-thumb">
        </a>
        <a href="#!" class="mob-toggler">
            <i class="feather icon-more-vertical"></i>
        </a>
    </div>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <li>
                <div class="dropdown drp-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="feather icon-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-notification">
                        <div class="pro-head">
                            <img src="<?php echo $imagePath; ?>" class="img-radius" alt="User-Profile-Image">
                            <span><?php echo htmlspecialchars($username); ?></span>
                        </div>
                        <ul class="pro-body">
                            <li class="list-group-item"><a href="profile.php?username=<?php echo htmlspecialchars($username); ?>"><i class="feather icon-user m-r-5"></i>View Profile</a></li>
                            <li><a href="manage-business.php" class="dropdown-item"><i class="feather icon-settings"></i> Settings</a></li>
                            <li><a href="logout.php" class="dropdown-item"><i class="feather icon-lock"></i> Lock Screen</a></li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
<!-- [ Header ] end -->
</body>
</html>
