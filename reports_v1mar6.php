<!DOCTYPE html>
<?php
session_start(); 
if(!isset($_SESSION['LOG_IN'])){
   header("Location:login.php");
}
else
{
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
}
include("config.php");
?>  

<html lang="en">
<head>
    <title>iiiQbets</title>
    <meta charset="utf-8">
    <?php include("header_link.php"); ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
    input.form-control, select.form-select {
        background-color: white;
        padding-left: 10px;
    }
    .form-control::placeholder {
        color: #6c757d;
    }
    /* Styles for report cards */
    .report-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px; /* Reduced padding */
        text-align: center;
        height: 150px; /* Reduced height */
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .report-card:hover {
        transform: scale(1.05);
    }
    .report-icon img {
        width: 50px; /* Reduced icon size */
        height: 50px; /* Reduced icon size */
        margin-bottom: 10px; /* Reduced margin */
    }
    .report-title {
        font-size: 16px; /* Reduced font size */
        font-weight: bold;
    }
    #report-images {
        height: 150px;
        width: 150px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>

</head>
<body>
    <?php include("menu.php"); ?>

    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">ALL TYPE OF REPORTS</h2>
                    <div class="row">
                        <!-- Cards Start Here -->
                        <div class="col-md-4 mb-4">
                            <a href="product_wise_sales_report.php">
                            <div class="report-card shadow">
                                <!-- <div class="report-icon">
                                    <img src="images/sales-report.jpg" alt="Product Sales" id="report-images">
                                </div> -->
                                <div class="report-title">Product Wise Sales Report</div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-4">
                            <a href="product_wise_purchase_report.php">
                            <div class="report-card shadow">
                                <!-- <div class="report-icon">
                                    <img src="images/purchase-report.jpg" alt="Product Purchase" id="report-images">
                                </div> -->
                                <div class="report-title">Product Wise Purchase Report</div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-4">
                            <a href="party_wise_sales_report.php">
                            <div class="report-card shadow">
                                <!-- <div class="report-icon">
                                    <img src="images/party-report.jpg" alt="Party Sales" id="report-images">
                                </div> -->
                                <div class="report-title">Party Wise Sales Report</div>
                            </div>
                            </a>
                        </div>
                        <!-- <div class="col-md-4 mb-4">
                            <a href="gst-sales-report.php">
                            <div class="report-card shadow">
                                <div class="report-icon">
                                    <img src="images/party-purchase.jpg" alt="Party Purchase" id="report-images">
                                </div>
                                <div class="report-title">Party Wise Purchase Report</div>
                            </div>
                            </a>
                        </div> -->
                        <div class="col-md-4 mb-4">
                <a href="gst-sales-report.php">
    <div class="report-card shadow">
        <!-- <div class="report-icon">
            <img src="images/gst1.jpg" alt="GST Sales" id="report-images">
        </div> -->
        <div class="report-title">GST Sales Report</div>
    </div>
</a>

            </div>
            <div class="col-md-4 mb-4">
                <a href="gst-purchase-report.php">
                <div class="report-card shadow">
                    <!-- <div class="report-icon">
                        <img src="images/gst2.jpg" alt="GST Purchase" id="report-images">
                    </div> -->
                    <div class="report-title">GST Purchase Report</div>
                </div>
                </a>
            </div>
            <!-- <div class="col-md-4 mb-4">
                <a href="gst-purchase-report.php">
                <div class="report-card shadow">
                    <div class="report-icon">
                        <img src="images/gst1.jpg" alt="Product Sales" id="report-images">
                    </div>
                    <div class="report-title">GSTR1</div>
                </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="gst-purchase-report.php">
                <div class="report-card shadow">
                    <div class="report-icon">
                        <img src="images/gst2.jpg" alt="Product Purchase" id="report-images">
                    </div>
                    <div class="report-title">GSTR2</div>
                </div>
                </a>
            </div> -->
            <div class="col-md-4 mb-4">
                <a href="hsn_sales_report.php">
                <div class="report-card shadow">
                    <!-- <div class="report-icon">
                        <img src="images/party-report.jpg" alt="Party Sales" id="report-images">
                    </div> -->
                    <div class="report-title">HSN Sales Report</div>
                </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="delivery_challan_report.php">
                <div class="report-card shadow">
                    <!-- <div class="report-icon">
                        <img src="images/party-report.jpg" alt="Party Sales" id="report-images">
                    </div> -->
                    <div class="report-title">Delivery Challan Report</div>
                </div>
                </a>
            </div>
            <!-- <div class="col-md-4 mb-4">
                <a href="gst-purchase-report.php">
                <div class="report-card shadow">
                    <div class="report-icon">
                        <img src="images/gst2.jpg" alt="Party Purchase" id="report-images">
                    </div>
                    <div class="report-title">Bulk Export</div>
                </div>
                </a>
            </div> -->
            <!-- <div class="col-md-4 mb-4">
                <a href="gst-purchase-report.php">
                <div class="report-card shadow">
                    <div class="report-icon">
                        <img src="images/gst1.jpg" alt="GST Sales" id="report-images">
                    </div>
                    <div class="report-title">Invoice Details Report</div>
                </div>
                </a>
            </div> -->
            <div class="col-md-4 mb-4">
                <a href="purchase_details_report.php">
                <div class="report-card shadow">
                    <!-- <div class="report-icon">
                        <img src="images/party-report.jpg" alt="GST Purchase" id="report-images">
                    </div> -->
                    <div class="report-title">Purchase Details Report</div>
                </div>
                </a>
            </div>
            <!-- <div class="col-md-4 mb-4">
                <a href="gst-purchase-report.php">
                <div class="report-card shadow">
                    <div class="report-icon">
                        <img src="images/party-report.jpg" alt="GST Purchase" id="report-images">
                    </div>
                    <div class="report-title">Invoice Details Report</div>
                </div>
                </a>
            </div> -->
            <div class="col-md-4 mb-4">
                <a href="tds_payable_report.php">
                <div class="report-card shadow">
                    <!-- <div class="report-icon">
                        <img src="images/purchase-report.jpg" alt="GST Purchase" id="report-images">
                    </div> -->
                    <div class="report-title">TDS Summary Payable</div>
                </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="tds_receivable_report.php">
                <div class="report-card shadow">
                    <!-- <div class="report-icon">
                        <img src="images/tds.jpg" alt="GST Purchase" id="report-images">
                    </div> -->
                    <div class="report-title">TDS Summary Receivable</div>
                </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="current_stock_report.php">
                <div class="report-card shadow">
                    <!-- <div class="report-icon">
                        <img src="images/party-purchase.jpg" alt="GST Purchase" id="report-images">
                    </div> -->
                    <div class="report-title">Current Stock Report</div>
                </div>
                </a>
            </div>
            <!-- <div class="col-md-4 mb-4">
                <a href="gst-purchase-report.php">
                <div class="report-card shadow">
                    <div class="report-icon">
                        <img src="images/party-report.jpg" alt="GST Purchase" id="report-images">
                    </div>
                    <div class="report-title">Delivery Challan Details Report</div>
                </div>
                </a>
            </div> -->
            <!-- <div class="col-md-4 mb-4">
                <a href="gst-purchase-report.php">
                <div class="report-card shadow">
                    <div class="report-icon">
                        <img src="images/audit.jpg" alt="GST Purchase" id="report-images">
                    </div>
                    <div class="report-title">Audit Trail</div>
                </div>
                </a>
            </div> -->
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
