<!DOCTYPE html>
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

// Fetch documents from the database
$query = "SELECT `id`, `document_name`, `document_path`, `uploaded_at` FROM `documents`";
$result = $conn->query($query);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<html lang="en">
<head>
    <title>iiiQbets</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
                                <h4 class="m-b-10">Documents</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Documents</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="card">
                <div class="row">
                    <!-- Sidebar Section -->
                    <div class="col-md-3">
                        <div class="card bg-light shadow-sm p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Accounting</h5>
                                <span class="badge bg-danger">0</span>
                            </div>
                        </div>
                        <div class="card p-3">
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#uploadModal">
                                    Upload New Document
                                </button>

                                <!-- Modal for Uploading Document -->
                                <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="uploadModalLabel">Upload New Document</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="upload_document.php" method="post" enctype="multipart/form-data">
                                                    <div class="mb-3">
                                                        <label for="documentName" class="form-label">Document Name</label>
                                                        <input type="text" class="form-control" id="documentName" name="documentName" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="documentFile" class="form-label">Choose File</label>
                                                        <input type="file" class="form-control" id="documentFile" name="documentFile" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Upload</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content Section -->
                    <div class="col-md-9">
    <div class="card shadow-sm mt-3 mt-md-0">
        <div class="card-body">
            <h5 class="card-title">Documents &gt; Accounting</h5>
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>Document Name</th>
                        <th>File Path</th>
                        <th>Uploaded At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Fetch documents from the database
                    $query = "SELECT `document_name`, `document_path`, `uploaded_at` FROM `documents`";
                    $result = $conn->query($query);

                    // Check if the query was successful
                    if ($result) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['document_name']) . "</td>";
                                echo "<td><a href='" . htmlspecialchars($row['document_path']) . "' target='_blank'>View Document</a></td>";
                                echo "<td>" . htmlspecialchars($row['uploaded_at']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center'>No Record Found</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>Query failed: " . htmlspecialchars($conn->error) . "</td></tr>";
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

    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>
</body>
</html>
