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
                                <h4 class="m-b-10">Bulk Export</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Bulk Export</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                          
                            <div class="row">
                                <div class="col-12">
                                    <h5>Generate New Report</h5>
                                </div>
                                <div class="col-md-8 mt-4">
                                    <div class="did-floating-label-content">
                                        <select id="entity_type" name="entity_type" class="did-floating-select">
                                            <option value="">Select an Document</option>
                                            <option value="Invoice">INVOICE</option>
                                            <option value="Quotation">QUOTATION</option>
                                            <option value="Purchase">PURCHASE</option>
                                            <option value="Reverse Charge">REVERSE CHARGE</option>
                                            <option value="Delivery Challan">DELIVERY CHALLAN</option>
                                            <option value="Payment Receipt">PAYMENT RECEIPT</option>
                                            <option value="Purchase Order">PURCHASE ORDER</option>
                                            <option value="Credit Note">CREDIT NOTE</option>
                                            <option value="Debit Note">DEBIT NOTE</option>
                                            <option value="Retail Invoice">RETAIL INVOICE</option>
                                            <option value="Payments Made">PAYMENTS MADE</option>
                                            <option value="Proforma Invoice">PROFORMA INVOICE</option>
                                            <option value="E Way Bill">E WAY BILL</option>
                                            <option value="Invoice(E-Invoice)">INVOICE(E-INVOICE)</option>
                                            <option value="E-Invoice(Govt)">E-INVOICE(GOVT)</option>
                                            <option value="Sales Debit Note">SALES DEBIT NOTE</option>
                                        </select>
                                        <label for="document" class="did-floating-label">Document</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-4">
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
                                <a href="" class="btn btn-info" style="color: #fff !important;">+ Generate</a>
                            </div>
                        </div>



                        <div class="card-body table-border-style">
                        <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="width:100%">
        <thead>
            <tr>
                <th>Serial No.</th>
                <th>Date</th>
                <th>Purchase No.</th>
                <th>Seller Name</th>
                <th>GSTIN</th>
                <th>Taxable Amount</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>IGST</th>
                <th>CESS</th>         
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>05-03-2025</td>
                <td>1234</td>
                <td>Desai Associates</td>
                <td>2524435AHK</td>
                <td>1316.10</td>
                <td>191.41</td>
                <td>191.41</td>
                <td>0</td>
                <td>0</td>
                <td>1750</td>
            </tr>
        </tbody>
    </table>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

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

    // Get today's date and the date of the previous month
    const today = new Date();
    const lastMonth = new Date();
    lastMonth.setMonth(lastMonth.getMonth() - 1);

    // Ensure consistency in case last month has fewer days
    if (lastMonth.getDate() !== today.getDate()) {
        lastMonth.setDate(0); // Set to last day of the previous month if necessary
    }

    const formattedToday = formatDate(today);
    const formattedLastMonth = formatDate(lastMonth);

    // Set default dates for the main report section
    const fromDateMain = document.getElementById('from_date');
    const toDateMain = document.getElementById('to_date');

    if (fromDateMain) fromDateMain.value = formattedLastMonth;
    if (toDateMain) toDateMain.value = formattedToday;

   

    // Function to fetch stock data
    // function fetchStockData() {
    //     $.ajax({
    //         url: "fetch_stock_data.php",
    //         method: "POST",
    //         data: {
    //             from_date: fromDateMain.value,
    //             to_date: toDateMain.value
    //         },
    //         success: function (data) {
    //             $('#dataTables-example tbody').html(data);
    //         }
    //     });
    // }

    // Fetch stock data when the dates are changed
    fromDateMain.addEventListener('change', fetchStockData);
    toDateMain.addEventListener('change', fetchStockData);

    // Auto-fetch data on page load
    fetchStockData();
});

    </script>
    
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>

</body>
</html>
