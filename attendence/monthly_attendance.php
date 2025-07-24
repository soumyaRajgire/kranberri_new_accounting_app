<?php
session_start();
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Get the logged-in user's username
$username = $_SESSION['username'];

// Fetch the monthly attendance data for the logged-in user based on username
$query = "SELECT id, username, checkin_time, checkin_status, att_status, checkout_time, loggedhours 
          FROM attendance_table
          WHERE username = ? AND MONTH(checkin_time) = MONTH(CURRENT_DATE()) AND YEAR(checkin_time) = YEAR(CURRENT_DATE())";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$attendance_data = [];
while ($row = $result->fetch_assoc()) {
    $attendance_data[] = $row;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>iiiQbets-Calendar</title>
    <?php include("header_link.php");?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }
        .attendance-table th, .attendance-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .attendance-table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #f9f9f9;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
        }
        .sortable::after {
            content: " \25B2\25BC";
            font-size: 0.8em;
            color: #ccc;
            padding-left: 5px;
        }
        .attendance-table th.sortable {
            background: #f8f8f8;
            position: relative;
        }
        .attendance-table th.sortable::after {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>
    <?php include("menu.php");?>
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h4 class="m-b-10">Monthly Attendance Report</h4>
                </div>
            </div>
            <hr>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Reports</h5>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        
                                        <th class="sortable">Username</th>
                                        <th class="sortable">Check-In Time</th>
                                        <th class="sortable">Check-In Status</th>
                                        <th class="sortable">Attendance Status</th>
                                        <th class="sortable">Check-Out Time</th>
                                        <th class="sortable">Logged Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attendance_data as $attendance) : ?>
                                        <tr>
                                            
                                            <td><?php echo htmlspecialchars($attendance['username']); ?></td>
                                            <td><?php echo htmlspecialchars($attendance['checkin_time']); ?></td>
                                            <td><?php echo htmlspecialchars($attendance['checkin_status']); ?></td>
                                            <td><?php echo htmlspecialchars($attendance['att_status']); ?></td>
                                            <td><?php echo htmlspecialchars($attendance['checkout_time']); ?></td>
                                            <td><?php echo htmlspecialchars($attendance['loggedhours']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').DataTable({
                "pageLength": 10
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
</body>
</html>
