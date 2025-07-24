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
if (isset($_GET['customerid'])) {
    $customerid = $_GET['customerid']; // Retrieve the customer ID
} else {
    // Redirect back if no customer ID is provided
    header("Location: party_wise_sales_report.php");
    exit();
}

// Fetch customer details
$customer_sql = "SELECT `customer_name` FROM `pi_invoice` WHERE `customer_id` = '$customerid' LIMIT 1";
$customer_result = $conn->query($customer_sql);

if ($customer_result && $customer_result->num_rows > 0) {
    $customer_row = $customer_result->fetch_assoc();
    $customer_name = $customer_row['customer_name']; // Get the customer name
} else {
    $customer_name = "Unknown Customer"; // Default value if customer name is not found
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>

/* Sidebar styles */
#reportsSidebar {
    position: fixed;
    top: 0;
    right: -400px; /* Initially hidden */
    width: 350px;
    height: 100%;
    background: white;
    box-shadow: -2px 0px 10px rgba(0,0,0,0.2);
    transition: right 0.3s ease-in-out;
    padding: 20px;
    z-index: 1050; /* Ensure it's higher than the menu */
    overflow-y: auto;
}


/* Show sidebar */
#reportsSidebar.show {
    right: 0;
}

/* Close button */
.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
    background: none;
    border: none;
    color: #333;
}

.close-btn:hover {
    color: red;
}
.form-check .form-check-input {
    margin-right: 8px; /* Space between radio button and label */
}

.form-check div {
    margin-right: 40px; /* Adjust spacing between PDF and Excel */
}
/* Overlay effect */
#overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1040; /* Just below the sidebar */
    display: none;
}
/* Show overlay when sidebar is active */
#reportsSidebar.show ~ #overlay {
    display: block;
}
body {
    position: relative;
    overflow-x: hidden;
}
.table-responsive {
    overflow-x: auto !important;
    white-space: nowrap;
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
                                <h4 class="m-b-10">Individual Party Wise Purchase Report</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Individual Party Wise Purchase Report</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                <h4 class="text-center"><?php echo htmlspecialchars($customer_name); ?></h4>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>View Individual Party Wise Purchase Reports</h5>
                            <div class="d-flex align-items-center">
                                <div class="d-flex">
                                    <div class="did-floating-label-content me-2 position-relative mt-4">
                                        <input type="date" id="from_date" name="from_date" class="did-floating-input modal-input datepicker" placeholder="" required>
                                        <label for="from_date" class="did-floating-label">From Date</label>
                                    </div>
                                    <div class="did-floating-label-content me-2 position-relative mt-4 mx-3">
                                        <input type="date" id="to_date" name="to_date" class="did-floating-input modal-input datepicker" placeholder="" required>
                                        <label for="to_date" class="did-floating-label">To Date</label>                                   
                                    </div>
                                </div>
                                <a href="" class="btn btn-info mx-1" style="color: #fff !important;">Reports</a>
                                <a href="" class="btn btn-info" style="color: #fff !important;">+ Generate New Report</a>
                            </div>
                        </div>



                        <div class="card-body table-border-style">
                        <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:100%">
    <thead>
        <tr>
            <th>Serial No.</th>
            <th>Date</th> <!-- Changed from Invoice No. -->
            <th>Purchase No.</th> <!-- Changed from Buyer Name -->
            <th>Taxable Amount</th>
            <th>CGST</th>
            <th>SGST</th>
            <th>IGST</th>
            <th>CESS</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
   
    </tbody>
</table>

</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Right Sidebar for Reports -->
<div id="reportsSidebar">
    <button class="close-btn" onclick="closeSidebar()">×</button>
    <h4>Reports</h4>
    <h6>Individual Party Wise Purchase Report</h6>
<hr>
    <div class="text-center">
    <h5 class="text-center">Generate Report</h5>
</div>
<div class="d-flex justify-content-center">
    <div class="did-floating-label-content me-2 position-relative mt-4">
        <input type="date" id="from_date" name="from_date" class="did-floating-input modal-input datepicker" placeholder="" required>
        <label for="from_date" class="did-floating-label">From Date</label>
    </div>
    <div class="did-floating-label-content me-2 position-relative mt-4 mx-3">
        <input type="date" id="to_date" name="to_date" class="did-floating-input modal-input datepicker" placeholder="" required>
        <label for="to_date" class="did-floating-label">To Date</label>
    </div>
</div>


<div class="form-check mt-3 d-flex align-items-center">
    <div class="me-4">
        <input type="radio" class="form-check-input" name="reportType" id="pdfOption" checked>
        <label class="form-check-label" for="pdfOption">PDF</label>
    </div>

    <div class="ms-4">
        <input type="radio" class="form-check-input" name="reportType" id="excelOption">
        <label class="form-check-label" for="excelOption">Excel</label>
    </div>
</div>


    <div class="form-check mt-2">
        <input type="checkbox" class="form-check-input" id="whatsappOption">
        <label class="form-check-label" for="whatsappOption">Also Send me in WhatsApp</label>
    </div>

    <button class="btn btn-dark mt-3 w-100">Generate New Report</button>
<hr>
    
    <div class="text-center">
    <h4 class="text-center">Recent Reports</h4>
</div>
    <!-- <div>
        <p><b>Product Sales Report</b> (01-12-2024 - 05-03-2025) <a href="#" class="btn btn-info btn-sm">Download</a></p>
        <p><b>Product Sales Report</b> (01-12-2024 - 05-03-2025) <a href="#" class="btn btn-danger btn-sm">Download</a></p>
    </div> -->
</div>


    <!-- JavaScript and jQuery dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
$(document).ready(function () {
    $('#dataTables-example').DataTable({
        "scrollX": true, // Allows horizontal scrolling for extra columns
        "pageLength": 10,
        "ordering": true,
        "searching": true,
        "info": true,
        "lengthChange": true
    });

    // Force DataTables to adjust layout
    setTimeout(function () {
        $('#dataTables-example').DataTable().columns.adjust();
    }, 500);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function formatDate(date) {
        return date.toISOString().split('T')[0]; // Format as YYYY-MM-DD
    }

    // Get today's date and last month's date
    const today = new Date();
    const lastMonth = new Date();
    lastMonth.setMonth(lastMonth.getMonth() - 1);

    // Ensure the date consistency
    if (lastMonth.getDate() !== today.getDate()) {
        lastMonth.setDate(0);
    }

    const formattedToday = formatDate(today);
    const formattedLastMonth = formatDate(lastMonth);

    // Select Date Inputs
    const fromDateMain = document.getElementById('from_date');
    const toDateMain = document.getElementById('to_date');
    const fromDateSidebar = document.querySelector('#reportsSidebar #from_date');
    const toDateSidebar = document.querySelector('#reportsSidebar #to_date');

    // Set default values
    if (fromDateMain) fromDateMain.value = formattedLastMonth;
    if (toDateMain) toDateMain.value = formattedToday;
    if (fromDateSidebar) fromDateSidebar.value = formattedLastMonth;
    if (toDateSidebar) toDateSidebar.value = formattedToday;

    // Fetch Data Function
    function fetchInvoiceData() {
        const customerid = new URLSearchParams(window.location.search).get('customerid');
        const fromDate = fromDateMain.value;
        const toDate = toDateMain.value;

        $.ajax({
            url: "fetch_individual_party_wise_purchase_report.php",
            method: "POST",
            data: {
                customerid: customerid,
                from_date: fromDate,
                to_date: toDate
            },
            success: function (data) {
                $('#dataTables-example tbody').html(data);
            },
            error: function () {
                console.error("Error fetching data");
            }
        });
    }

    // Update Sidebar Dates when Main Dates Change
    fromDateMain.addEventListener('change', function () {
        if (fromDateSidebar) fromDateSidebar.value = fromDateMain.value;
        fetchInvoiceData();
    });

    toDateMain.addEventListener('change', function () {
        if (toDateSidebar) toDateSidebar.value = toDateMain.value;
        fetchInvoiceData();
    });

    // Fetch data when Sidebar dates change
    fromDateSidebar.addEventListener('change', function () {
        fromDateMain.value = fromDateSidebar.value;
        fetchInvoiceData();
    });

    toDateSidebar.addEventListener('change', function () {
        toDateMain.value = toDateSidebar.value;
        fetchInvoiceData();
    });

    // Auto-fetch data on page load
    fetchInvoiceData();
});
</script>
    <script>
        // Function to open sidebar
// Function to open sidebar and show overlay
function openSidebar() {
    document.getElementById("reportsSidebar").classList.add("show");
    document.getElementById("overlay").style.display = "block"; // Show overlay
}

// Function to close sidebar and hide overlay
function closeSidebar() {
    document.getElementById("reportsSidebar").classList.remove("show");
    document.getElementById("overlay").style.display = "none"; // Hide overlay
}

// Open sidebar when clicking Reports or Generate New Report
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-info").forEach(function (button) {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default link behavior
            openSidebar();
        });
    });

    // Close sidebar when clicking outside of it
    document.getElementById("overlay").addEventListener("click", function () {
        closeSidebar();
    });

    // Prevent closing when clicking inside sidebar
    document.getElementById("reportsSidebar").addEventListener("click", function (event) {
        event.stopPropagation();
    });
});


    </script>
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>

<div id="overlay"></div>
</body>
</html>
