<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$attendanceQuery = "SELECT checkin_time, checkout_time FROM attendance_table WHERE username = ?";
$attendanceStmt = $conn->prepare($attendanceQuery);
if ($attendanceStmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$username = $_SESSION['username'];
$attendanceStmt->bind_param("s", $username);
$attendanceStmt->execute();
$attendanceResult = $attendanceStmt->get_result();
if ($attendanceResult === false) {
    die('Execute failed: ' . htmlspecialchars($attendanceStmt->error));
}

$attendanceEvents = [];
$currentDate = date('Y-m-d');
$datesRecorded = [];

while ($row = $attendanceResult->fetch_assoc()) {
    $checkinDate = date('Y-m-d', strtotime($row['checkin_time']));
    if (!empty($row['checkin_time'])) {
        $datesRecorded[] = $checkinDate;
        $event = [
            'title' => 'Present',
            'start' => $checkinDate,
            'color' => 'blue'
        ];
        $attendanceEvents[] = $event;
    }
}

$start = new DateTime('first day of this month');
$end = new DateTime('first day of next month');
$interval = new DateInterval('P1D');
$daterange = new DatePeriod($start, $interval, $end);

foreach ($daterange as $date) {
    $formattedDate = $date->format('Y-m-d');
    if ($formattedDate <= $currentDate && !in_array($formattedDate, $datesRecorded)) {
        $attendanceEvents[] = [
            'title' => 'Absent',
            'start' => $formattedDate,
            'color' => 'red'
        ];
    }
}

$attendanceStmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include("header_link.php");?>
    <title>iiiQbets - Monthly Calendar</title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css' rel='stylesheet' />
    <style>
        .card {
            max-width: 1000px;
            margin: auto;
            margin-top: 20px;
        }
        #calendar {
            max-width: 100%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include("menu.php");?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center">Calendar (2024-2025)</h2>
            </div>
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>
    </div>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js'></script>
    <script>
        $(document).ready(function() {
            var events = <?php echo json_encode($attendanceEvents); ?>;

            $('#calendar').fullCalendar({
                editable: false,
                selectable: false,
                events: events,
                displayEventTime: false
            });
        });
    </script>
</body>
</html>
