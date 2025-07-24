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

// Get the product ID from the query parameter
if (isset($_GET['productid'])) {
    $productid = $_GET['productid']; // Retrieve the product ID
} else {
    // Redirect back if no product ID is provided
    header("Location: product_wise_sales_report.php");
    exit();
}

// Fetch the product name
$product_name_sql = "SELECT `product` FROM `invoice_items` WHERE `productid` = '$productid' LIMIT 1";
$product_name_result = $conn->query($product_name_sql);

if ($product_name_result && $product_name_result->num_rows > 0) {
    $product_name_row = $product_name_result->fetch_assoc();
    $product_name = $product_name_row['product']; // Get the product name
} else {
    $product_name = "Unknown Product"; // Default value if product name is not found
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
.recent-reports-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
}

.report-item {
    display: flex;
    align-items: center;
    background: #f9f9f9;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
}

.report-item i {
    margin-right: 10px;
}

.report-details {
    flex-grow: 1;
}

.report-title {
    font-weight: bold;
    margin: 0;
}

.report-date {
    font-size: 12px;
    color: #666;
    margin: 0;
}

.download-btn {
    background: #007bff;
    color: white;
    border-radius: 5px;
    padding: 5px 10px;
}

.download-btn:hover {
    background: #0056b3;
}
</style>
<style>
        /* Style the input field */
        .date-input {
            width: 220px; /* Adjust width as needed */
            padding: 10px 10px 10px 35px; /* Padding to make space for the icon inside */
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: url('https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/Font_Awesome_5_regular_calendar-alt.svg/1024px-Font_Awesome_5_regular_calendar-alt.svg.png') no-repeat 10px center;
            background-size: 20px; /* Adjust icon size */
            box-sizing: border-box;
        }

        /* Optional: Highlight the input field on focus */
        .date-input:focus {
            border-color: #007bff;
            outline: none;
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
                                <h4 class="m-b-10">Individual Product Wise Sales Report</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Individual Product Wise Sales Report</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
            
                <div class="col-sm-12">
                <h4 class="text-center"><?php echo htmlspecialchars($product_name); ?></h4>
                    <div class="card">
                  
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>View Individual Product Wise Sales Reports</h5>
                            <div class="d-flex align-items-center">
                                <div class="d-flex">
                                <div class="did-floating-label-content me-2 position-relative mt-4">
                                        <input type="text" id="from_date" name="from_date" class="did-floating-input modal-input" placeholder="From Date" required>
                                        <label for="from_date" class="did-floating-label">From Date</label>
                                    </div>
                                    <div class="did-floating-label-content me-2 position-relative mt-4 mx-3">
                                        <input type="text" id="to_date" name="to_date" class="did-floating-input modal-input" placeholder="To Date" required>
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
                                            <th>Invoice No.</th>
                                            <th>Buyer Name</th>
                                            <th>Invoice Date</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
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
    <h6>Individual Product Wise Sales Report</h6>
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
<div id="recentReports">
        <!-- Recent reports will be dynamically loaded here -->
    </div>
    <!-- <div>
        <p><b>Product Sales Report</b> (01-12-2024 - 05-03-2025) <a href="#" class="btn btn-info btn-sm">Download</a></p>
        <p><b>Product Sales Report</b> (01-12-2024 - 05-03-2025) <a href="#" class="btn btn-danger btn-sm">Download</a></p>
    </div> -->
</div>


    <!-- JavaScript and jQuery dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
    // ✅ Function to format date as DD/MM/YYYY (for UI display)
    function formatToDDMMYYYY(date) {
        if (!(date instanceof Date)) return '';
        const day = ("0" + date.getDate()).slice(-2);
        const month = ("0" + (date.getMonth() + 1)).slice(-2);
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // ✅ Function to format date as YYYY-MM-DD (for MySQL)
    function formatToYYYYMMDD(date) {
        if (!(date instanceof Date)) return '';
        return date.toISOString().split('T')[0]; // Converts to YYYY-MM-DD
    }

    // ✅ Function to convert DD/MM/YYYY string to a Date object
    function convertToDateObject(dateStr) {
        if (!dateStr) return null;
        const parts = dateStr.split('/');
        return new Date(parts[2], parts[1] - 1, parts[0]); // yyyy, mm (0-based), dd
    }

    // ✅ Get today's date
    const today = new Date();
    const lastMonth = new Date(today);
    lastMonth.setMonth(lastMonth.getMonth() - 1);

    if (lastMonth.getDate() !== today.getDate()) {
        lastMonth.setDate(0); // Adjust to last valid date of the previous month
    }

    // ✅ Format dates for display and MySQL queries
    const formattedTodayDisplay = formatToDDMMYYYY(today);
    const formattedLastMonthDisplay = formatToDDMMYYYY(lastMonth);
    const formattedTodaySQL = formatToYYYYMMDD(today);
    const formattedLastMonthSQL = formatToYYYYMMDD(lastMonth);

    // ✅ Select main date inputs (affects the table data)
    const fromDateMain = document.getElementById('from_date');
    const toDateMain = document.getElementById('to_date');

    if (fromDateMain) fromDateMain.value = formattedLastMonthDisplay;
    if (toDateMain) toDateMain.value = formattedTodayDisplay;

    // ✅ Select sidebar date inputs (used for report generation)
    const fromDateSidebar = document.querySelector('#reportsSidebar #from_date');
    const toDateSidebar = document.querySelector('#reportsSidebar #to_date');

    if (fromDateSidebar) fromDateSidebar.value = formattedLastMonthDisplay;
    if (toDateSidebar) toDateSidebar.value = formattedTodayDisplay;

    // ✅ Initialize Flatpickr for main date inputs (affects table data)
    flatpickr("#from_date", {
        dateFormat: "d/m/Y",
        defaultDate: formattedLastMonthDisplay,
        onChange: function () {
            fetchSalesData();
        }
    });

    flatpickr("#to_date", {
        dateFormat: "d/m/Y",
        defaultDate: formattedTodayDisplay,
        onChange: function () {
            fetchSalesData();
        }
    });

    // ✅ Initialize Flatpickr for sidebar date inputs (used for generating reports)
    flatpickr("#reportsSidebar #from_date", {
        dateFormat: "d/m/Y",
        defaultDate: formattedLastMonthDisplay
    });

    flatpickr("#reportsSidebar #to_date", {
        dateFormat: "d/m/Y",
        defaultDate: formattedTodayDisplay
    });

    // ✅ Function to fetch sales data based on selected dates
    function fetchSalesData() {
        const fromDateForSQL = formatToYYYYMMDD(convertToDateObject(fromDateMain.value));
        const toDateForSQL = formatToYYYYMMDD(convertToDateObject(toDateMain.value));

        // ✅ Get the product ID from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const productid = urlParams.get('productid');

        if (!productid) {
            console.error("Product ID is missing in the URL.");
            return;
        }

        $.ajax({
            url: "fetch_individual_product_wise_sales_report.php",
            method: "POST",
            data: {
                productid: productid,
                from_date: fromDateForSQL,
                to_date: toDateForSQL
            },
            success: function (data) {
                $('#dataTables-example tbody').html(data);
            },
            error: function () {
                console.error("Error fetching data");
            }
        });
    }

    // ✅ Auto-fetch sales data on page load
    fetchSalesData();
});

// ✅ Report Generation (PDF or Excel)
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".btn-dark").addEventListener("click", function () {
        let fromDate = document.querySelector("#reportsSidebar #from_date").value;
        let toDate = document.querySelector("#reportsSidebar #to_date").value;
        let reportType = document.querySelector("#pdfOption").checked ? "pdf" : "excel";

        if (!fromDate || !toDate) {
            alert("Please select both From Date and To Date.");
            return;
        }

        // ✅ Convert date format for backend (from DD/MM/YYYY to YYYY-MM-DD)
        function formatToYYYYMMDD(dateStr) {
            if (!dateStr) return '';
            let parts = dateStr.split('/');
            return `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert DD/MM/YYYY → YYYY-MM-DD
        }

        let fromDateForSQL = formatToYYYYMMDD(fromDate);
        let toDateForSQL = formatToYYYYMMDD(toDate);

        // ✅ Get the product ID from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const productid = urlParams.get('productid');

        if (!productid) {
            alert("Product ID is missing. Please select a valid product.");
            return;
        }

        // ✅ Send AJAX request to generate report
        $.ajax({
            url: "generate_individual_product_wise_sales_report.php",
            method: "POST",
            data: { 
                productid: productid, 
                from_date: fromDateForSQL, 
                to_date: toDateForSQL, 
                report_type: reportType 
            },
            success: function (response) {
                try {
                    let jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === "success") {
                        alert("Report generated successfully.");
                        updateRecentReports();
                    } else {
                        alert("Error generating report: " + jsonResponse.message);
                    }
                } catch (error) {
                    alert("Unexpected response from server.");
                    console.error(response);
                }
            },
            error: function () {
                alert("Failed to generate report.");
            }
        });
    });

    // ✅ Function to update recent reports list
    function updateRecentReports() {
        $.ajax({
            url: "fetch_recent_individual_product_wise_sales_reports.php",
            method: "GET",
            success: function (data) {
                document.querySelector("#recentReports").innerHTML = data;
            },
            error: function () {
                console.error("Error fetching recent reports.");
            }
        });
    }

    updateRecentReports(); // ✅ Load recent reports on page load
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
