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
?>

<html lang="en">
<head>
    <title>iiiQbets - Suppliers</title>
    <!-- Rest of your HTML and CSS links -->
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>


<body class="">
    <!-- Rest of your HTML content for customers -->
   <!-- [ Pre-loader ] start -->

   <?php include("menu.php"); ?>


<!-- [ Header ] end -->


<!-- [ Main Content ] start -->
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h4 class="m-b-10">View Suppliers</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                              <div class="col-md-12">
                                <h4 class="m-b-10">View Suppliers</h4>
                                  <div class="col-md-12" style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                                        <!-- Month and Year Selection -->
                                        <div id="reportrange" class="col-md-4" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
    <i class="fa fa-calendar"></i>&nbsp;
    <span></span> <i class="fa fa-caret-down"></i>

</div><button id="download-btn" class="btn btn-sm btn-info">Download Report</button>
                                    <!-- Month and Year Selection -->
                                    <!--<form class="form-inline" method="POST" action="download_suppliers_monthly.php" style="display: flex; align-items: center; margin-right: 10px;">
                                        <label style="margin-right: 10px;">Monthly Data:</label>
                                        <select class="form-control" id="month" name="month" style="margin-right: 10px; width: auto;">
                                            <?php
                                            $current_month = date("m");
                                            //for ($month = 1; $month <= 12; $month++) {
                                                $selected = ($current_month == $month) ? 'selected' : '';
                                              //  echo "<option value=\"$month\" $selected>" . date('F', mktime(0, 0, 0, $month, 1)) . "</option>";
                                           // }
                                            ?>
                                        </select>
                                        <select class="form-control" id="year" name="year" style="margin-right: 10px; width: auto;" required>
                                            <?php
                                            $current_year = date("Y");
                                            //for ($i = $current_year; $i >= 2017; $i--) {
                                               //// echo "<option value=\"$i\">$i</option>";
                                            //}
                                            ?>
                                        </select>
                                        <button type="submit" class="btn btn-success btn-sm" name="download_month">
                                            <i class="fas fa-download"></i> Download
                                        </button>
                                    </form>-->
                                    
                                    <!-- Date Range Selection -->
                                   <!--  <form class="form-inline" method="POST" action="download_suppliers_range.php" style="display: flex; align-items: center; margin-right: 10px;">
                                        <label style="margin-right: 10px;">Date Range:</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date" required 
                                               style="margin-right: 10px; width: auto;"
                                               value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
                                        <input type="date" class="form-control" id="to_date" name="to_date" required 
                                               style="margin-right: 10px; width: auto;"
                                               value="<?php echo date('Y-m-d'); ?>">
                                        <button type="submit" class="btn btn-success btn-sm" name="download_range">
                                            <i class="fas fa-download"></i> Download Range
                                        </button>
                                    </form> -->
                                    
                                    <!-- Add Supplier Button -->
                                    <form action="supplier_form.php" method="POST" style="margin-left: auto;">
                                        <button class="btn btn-success btn-sm" name="addCustBtn" id="addCustBtn" type="submit">Add Supplier</button>
                                    </form>
                                </div>
                            </div>
                           
                            </div>
                        </div>
                           <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Contact Info</th>
                                        <th>Tax Information</th>
                                        <th>Created BY</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                       if (!isset($_SESSION['branch_id'])) {
    // Query to get customers, ordered by the 'created_on' field in descending order
    $sql = "SELECT * FROM customer_master WHERE contact_type = 'Supplier' AND business_id = '$business_id' ORDER BY created_on DESC";
} else {
    // Query to get customers for a specific branch, ordered by 'created_on' in descending order
    $sql = "SELECT * FROM customer_master WHERE contact_type = 'Supplier' AND business_id = '$business_id' AND branch_id='$branch_id' ORDER BY created_on DESC";
}
                        //    echo $sql;            
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <tr>
                                            <td>
                                                    <a href="supplier-details.php?id=<?php echo $row['id']; ?>">
                                                        <?php echo $row['customerName']; ?>
                                                    </a><br/>
                                                        <?php echo $row["business_name"] === "" ? '<a href="update-supplier.php?id=' . $row["id"] . '">Update</a>' : $row["business_name"]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row["mobile"] === "" ? '<a href="update-supplier.php?id=' . $row["id"] . '">Update Mobile</a>' : $row["mobile"]; ?><br/>
                                                        <?php echo $row["email"] === "" ? '<a href="update-supplier.php?id=' . $row["id"] . '">Update Email</a>' : $row["email"]; ?>
                                                    </td>
                                                    <td>
                                                        PAN : <?php echo $row["pan"] === "" ? '<a href="update-supplier.php?id=' . $row["id"] . '&tab=tab2"">Update PAN</a>' : $row["pan"]; ?><br/>
                                                        GSTIN : <?php echo $row["gstin"] === "" ? '<a href="update-supplier.php?id=' . $row["id"] . '&tab=tab2"">Update GSTIN</a>' : $row["gstin"]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row["created_by"] ?><br/>
                                                        <?php echo $row["created_on"] ?>
                                                    </td>
                                                <td>
                                                        <a href="update-supplier.php?id=<?php echo $row["id"]; ?>" class="text-primary mr-2">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                     <a href="delete-suppliers.php?id=<?php echo $row["id"]; ?>" class="text-danger" id="deleteButton<?php echo $row['id']; ?>">
    <i class="fas fa-trash-alt"></i>
</a>
                                                    </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="3"><?php echo "No Records found";?></td>
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
            <!-- [ stiped-table ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
</section>


        <!-- Adding Services Module-->

        
    <!-- End Services Modal-->

    <!-- Products Modal -->

    <!-- End of Products Modal-->
    <!-- Required Js -->

    <!-- <script src="assets/js/jquery.min.js"></script> -->

    <!-- Bootstrap Core JavaScript -->
    <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTables-example').DataTable();
            $('.dataTables_length').addClass('bs-select');

        });
        $('#dataTables-example').dataTable({
            "orderFixed": [3, 'asc']
        });
    </script>
<script type="text/javascript">
  $(function() {
    var start = moment().startOf('month');
    var end = moment().endOf('month');

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    // When the user selects a date range manually
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        
        // Store the selected date range for use
        $('#reportrange').data('start', startDate);
        $('#reportrange').data('end', endDate);
    });

    // Handle the download button click for monthly or custom date range download
  $('#download-btn').on('click', function() {
    var startDate = $('#reportrange').data('start');
    var endDate = $('#reportrange').data('end');

    if (!startDate || !endDate) {
        alert('Please select a date range first.');
        return;
    }

    // Send the selected date range to the backend for processing
    $.ajax({
        url: 'download-supplier.php', // PHP script to handle the download
        type: 'GET',
        data: {
            from_date: startDate,
            to_date: endDate,
            download_range: true // Indicates custom date range download
        },
        success: function(response) {
            // If no data is found, the server returns JSON with 'error'
            try {
                var data = JSON.parse(response);
                if (data.status === 'error') {
                    // No data found, show SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'No Data Found',
                        text: data.message, // This will show the "No data found" message
                    });
                    return;  // Prevent file download
                }
            } catch (e) {
                // If JSON parsing fails, assume the response is the Excel file
                // Proceed with the file download
                window.location.href = 'download-supplier.php?from_date=' + startDate + '&to_date=' + endDate + '&download_range=true';
            }
        },
        error: function(xhr, status, error) {
            // Handle error if the request fails
            alert('Error: ' + error);
        }
    });
});

});
</script>
<script>
    // Wait for the document to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener for each delete link
        const deleteButtons = document.querySelectorAll('a[id^="deleteButton"]');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();  // Prevent the default action of the link (which is redirecting to delete page)
                
                const deleteUrl = this.href;  // Get the delete URL from the href attribute
                
                // Show the SweetAlert confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this contact!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, navigate to the delete URL
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>
</body>
</html>
