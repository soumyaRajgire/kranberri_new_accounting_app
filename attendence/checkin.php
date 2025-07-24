<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION['username'];

    if (empty($username)) {
        echo "<script>alert('Please log in again.');</script>";
        ?>
        <script>window.location.href="login.php"</script>
        <?php
        exit();
    }

    $tdate = date('Y-m-d');

    // Query to check if user has already checked in
    // $checkQuery = "SELECT * FROM attendance_table WHERE username = '$username' AND DATE(checkin_time) = '$tdate' AND checkin_status='IN'";
    // $checkResult = $conn->query($checkQuery);

    // if (!$checkResult) {
    //     die("Query Failed: " . mysqli_error($conn));
    // }
// Query to check if user has already checked in
$checkQuery = "SELECT * FROM attendance_table WHERE username = '$username' AND DATE(checkin_time) = '$tdate' AND checkin_status='IN'";

$checkResult = $conn->query($checkQuery);

if ($checkResult->num_rows > 0) {
    // User is already checked in, show a message or take appropriate action
   // echo "You are already checked in for today.";
     echo '<script>alert("You are already checked in for today")</script>';
     ?>
                    <script>window.location.href="attendance.php"</script>
                    <?php
} 
   else {
        date_default_timezone_set('Asia/Kolkata');

        $checkInTime = date('Y-m-d H:i:s');
        $att_status = "present";

        $sql = "INSERT INTO attendance_table (username, checkin_time, checkin_status, att_status) VALUES ('$username', '$checkInTime', 'IN', '$att_status')";

        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("You are successfully checked in")</script>';
            ?>
            <script>window.location.href="attendance.php"</script>
            <?php
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
