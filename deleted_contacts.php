<?php
session_start();
include("config.php");

// Check if the user is logged in
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Deleted Contacts</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    
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
                                <h4 class="m-b-10">Deleted Contacts</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="dataTables-example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL No.</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Contact Type</th>
                                        <th>Deleted On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $serial_no = 1;
                                    // Fetch deleted contacts with specific column names
                                    $query = "SELECT id, customerName, mobile, email, contact_type, deleted_on FROM deleted_contacts ORDER BY deleted_on DESC";
                                    $result = mysqli_query($conn, $query);

                                    if (!$result) {
                                        die("Query Failed: " . mysqli_error($conn));
                                    }

                                    // Check if any rows were fetched
                                    if (mysqli_num_rows($result) == 0) {
                                        echo "<p>No deleted contacts found.</p>";
                                    }

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                            <td>{$serial_no}</td>
                                            <td>{$row['customerName']}</td>
                                            <td>{$row['mobile']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['contact_type']}</td>
                                            <td>{$row['deleted_on']}</td>
                                            <td><button onclick=\"restoreContact({$row['id']})\" class=\"btn btn-primary btn-sm\" data-toggle=\"tooltip\" title=\"Restore\"><i class=\"fas fa-undo\"></i></button>
                                             <button onclick=\"deletePermanently({$row['id']})\" class=\"btn btn-danger btn-sm\" data-toggle=\"tooltip\" title=\"Delete Permanently\"><i class=\"fas fa-trash-alt\"></i></button></td>
                                        </tr>";
                                        $serial_no++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                "order": [[5, "desc"]]
            });
        });

        function restoreContact(id) {
            if (confirm("Are you sure you want to restore this contact?")) {
                window.location.href = 'restore_contact.php?id=' + id;
            }
        }

        function deletePermanently(id) {
            if (confirm("Are you sure you want to delete this contact permanently? This action cannot be undone.")) {
                window.location.href = 'delete_contacts_permanently.php?id=' + id;
            }
        }
    </script>
</body>
</html>
