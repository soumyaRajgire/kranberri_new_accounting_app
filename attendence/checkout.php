<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('config.php');

// Function to calculate distance between two points using the Haversine formula
function haversineDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371000; // meters
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;
    return $distance;
}

if (!isset($_SESSION['username'])) {
    echo '<script>alert("Please log in again.");</script>';
    ?>
    <script>window.location.href="login.php"</script>
    <?php
    exit();
}

$username = $_SESSION['username'];

$tdate = date('Y-m-d');

// Subquery to find the most recent checkin_time for the given employee and current date
$subquery = "SELECT MAX(checkin_time) AS max_checkin_time FROM attendance_table WHERE username = '$username' AND DATE(checkin_time) = '$tdate'";
$subresult = $conn->query($subquery);

if ($subresult) {
    $row = $subresult->fetch_assoc();
    $max_checkin_time = $row['max_checkin_time'];

    $sql1 = "SELECT * FROM attendance_table WHERE username='$username' AND checkin_time = '$max_checkin_time' AND checkout_time IS NULL AND DATE(checkin_time) = '$tdate'";
    $result1 = $conn->query($sql1);

    if ($result1->num_rows > 0) {
        if ($row = $result1->fetch_assoc()) {
            $checkInTime = $row['checkin_time'];
            $lunch_break_starts = $row['lunch_break_starts'];
            $lunch_break_ends = $row['lunch_break_ends']; 
            $lunch_break_status = $row['lunch_break_status'];
            $lunchBreakTime_taken = $row['lunchBreakTime_taken'];

            date_default_timezone_set('Asia/Kolkata');
            $checkOutTime = date('Y-m-d H:i:s');

            $checkIn = new DateTime($checkInTime);
            $checkOut = new DateTime($checkOutTime);

            $interval = $checkIn->diff($checkOut);
            $hours = $interval->h;
            $minutes = $interval->i;

            $totalLoggedHours = "";

            if ($lunch_break_status != '') {
                $total_day_time = ($hours * 60) + $minutes; // Total time in minutes
                $lunch_break_start = new DateTime($lunch_break_starts);
                $lunch_break_end = new DateTime($lunch_break_ends);
                $lunch_break_interval = $lunch_break_start->diff($lunch_break_end);

                $lunch_break_hours = $lunch_break_interval->h;
                $lunch_break_minutes = $lunch_break_interval->i;
                $lunch_break_total = ($lunch_break_hours * 60) + $lunch_break_minutes;

                $total_logged_time = $total_day_time - $lunch_break_total;
                $logged_hours = floor($total_logged_time / 60);
                $logged_minutes = $total_logged_time % 60;

                if ($logged_hours > 0) {
                    $totalLoggedHours .= $logged_hours . " hr ";
                }
                if ($logged_minutes > 0) {
                    $totalLoggedHours .= $logged_minutes . " mins";
                }
            } else {
                if ($hours > 0) {
                    $totalLoggedHours .= $hours . " hr ";
                }
                if ($minutes > 0) {
                    $totalLoggedHours .= $minutes . " mins";
                }
            }

            $att_status = "present";
            $sql = "UPDATE attendance_table SET checkin_status='OUT', checkout_time = '$checkOutTime', att_status= '$att_status', loggedhours = '$totalLoggedHours' WHERE username = '$username' AND checkout_time IS NULL AND DATE(checkin_time) = '$tdate'";

            if (mysqli_query($conn, $sql)) {
                echo '<script>alert("You are successfully checked out.")</script>';
                unset($_SESSION['username']);
                session_destroy();
                ?>
                <script>window.location.href="attendance.php"</script>
                <?php
            } else {
                echo '<script>alert("Error in attendance update.")</script>';
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo '<script>alert("You are not checked in today.")</script>';
        ?>
        <script>window.location.href="attendance.php"</script>
        <?php
    }
} else {
    echo '<script>alert("Error fetching data.")</script>';
    echo "Error: " . $subquery . "<br>" . $conn->error;
}
?>
