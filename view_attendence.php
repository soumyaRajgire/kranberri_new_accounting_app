<?php
session_start();
// Check if the user is logged in
if(!isset($_SESSION['LOG_IN'])){
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if(!isset($_SESSION['business_id'])){
    header("Location:dashboard.php");
    exit();
} else {
 // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
        // Branch-specific code or logic here
    } 
}
include("config.php");

// Fetch attendance records
$sql = "SELECT * FROM attendance_table";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7ie5U5y5bmj2lg43Fw4p9qf4q9s4p5ks1o6" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-L6VRsxJ6pgEX3LZ6EMB7sfh3BC5HYN6A9bHHu6fi65VOBDGRJ1JNTj58h5ntYnsH" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIqJ2s0ihnZn9RJOVgpFvhE/jxoOJQ+lwwBapupetxc5a0IM4AH" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <style>
        .btn-info {
            color: #fff !important;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <?php include("menu.php"); ?>

    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">View Attendance</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="border-radius: 5px;">
                        <div class="card-header">
                            <h5>Reports</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="dataTables-example" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Check-in Time</th>
                                            <th>Check-in Status</th>
                                            <th>Attendance Status</th>
                                            <th>Check-out Time</th>
                                            <th>Logged Hours</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                  
                                  
                                     <?php
                 $sql="select * from attendance_table";
                  $result=$conn->query($sql);

             if($result->num_rows>0)
                {
            while($row = mysqli_fetch_assoc($result)) 
                {
                                    ?>
                                   <tr>
    <td><?php echo htmlspecialchars($row['id'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['username'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['checkin_time'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['checkin_status'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['att_status'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['checkout_time'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($row['loggedhours'] ?? ''); ?></td>
    <td><a href="#" class="view-details" data-id="<?php echo htmlspecialchars($row['id'] ?? ''); ?>" data-toggle="modal" data-target="#detailsModal"><i class="fas fa-eye"></i></a></td>
</tr>

                                    <?php
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="8">No records found</td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Employee Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Details will be loaded here via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable();
            $('.view-details').on('click', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'get_details.php',
                    method: 'POST',
                    data: { id: id },
                    success: function(response) {
                        $('#detailsModal .modal-body').html(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
