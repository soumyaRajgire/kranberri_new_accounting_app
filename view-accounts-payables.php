
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
    <title>iiiQbets</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <?php include("header_link.php");?>
     
    
    

</head>
<body class="">
    <!-- [ Pre-loader ] start -->
     
     <?php include("menu.php");?>
    
     <?php //include("createReceiptModal.php") ?>
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
                            <h4 class="m-b-10">View Invoice</h4>
                        </div>
                        <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">View Purchase Invoice</a></li>
                            <!-- <li class="breadcrumb-item"><a href="#!">Basic Tables</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->

<?php include("purchases_menu.php");?>

    <!--     <div class="row align-items-center">
                    <div class="col-md-12">
                        <!--  <div class="page-header-title">
                            <h4 class="m-b-10">View Quotation</h4>
                        </div> -->
                     <!--   <ul class="ul_filter pl-0 mb-0 nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold mt-0 dash_nav" role="tablist">
                    <li class="nav-item searchfilter_li">
                        <div class="dropdown">
                            <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" style="height: 2.4rem !important;width:100%;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                New
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item create" data-doc="estimate" href="javascript:;"> Quotes</a>
                                <a class="dropdown-item create" data-doc="domestic-invoice" href="create-invoice.php"> Domestic Invoice</a>
                                <a class="dropdown-item create" data-doc="international-invoice" href="javascript:;">
                                    International Invoice</a>
                                <a class="dropdown-item create" data-doc="bill" href="bill-of-supply.php"> Bill of Supply</a>
                                <a class="dropdown-item create" data-doc="credit" href="create-credit-note.php"> Credit Note</a>
                                <a class="dropdown-item create" data-doc="receipt" href="javascript:;"> Receipts</a>
                                <a class="dropdown-item create" data-doc="dc" href="delivery_challan.php"> Delivery Challan</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li quotes active" data-item="quotes" href="/m/app/invoice/manage-estimate">Quotations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li invoice" data-item="invoice" href="/m/app/invoice/manage-invoice">Invoices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li bos" data-item="bos" href="manage-billsupply.php">Bill Of Supply</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li cn" data-item="cn" href="manage-creditnote.php">Credit Note</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li receipts" data-item="receipts" href="/m/app/invoice/manage-receipt">Receipts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li receivables" data-item="receivables" href="/m/app/invoice/manage-receivable">Receivables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link exp_li delivery_challan" data-item="delivery_challan" href="manage_delivery_challan.php">Delivery Challan</a>
                    </li>
                </ul>
                    </div>
                </div> -->
                
  <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <!-- <h5>View  Details</h5> -->
                   
                        <!-- <span class="d-block m-t-5">use class <code>table-striped</code> inside table element</span> -->
                        <a  href="create-purchase-invoice.php" class="btn btn-info" style="color: #fff !important;float:right;">Create</a>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <!-- <table class="table table-striped table-bordered" id="dataTables-example"> -->
                              
                        <!-- Your HTML table structure -->
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th>Supplier </th>
            <th>Purchase Invoice</th>
            <th>Total Amount</th>
            <th>Payment</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Assuming you have a database connection, you can retrieve data from the database here
        // and loop through the results to generate table rows.
        
        // Replace the following code with your actual database query and result retrieval logic.
         $result = mysqli_query($conn, "SELECT
    cm.customerName,
    cm.id AS cm_id,
    cm.email AS customerEmail,
    q.invoice_code AS quotationNumber,
    q.created_by AS quotationCreatedBy,
    q.created_on AS quotationCreatedOn,
    q.grand_total,
    q.invoice_date,
    q.invoice_file,
    q.due_date,
    q.status,
    q.total_amount,
    q.id AS iid
FROM
    pi_invoice q
JOIN
    customer_master cm ON q.customer_id = cm.id
    where q.branch_id='$branch_id' ORDER BY
        q.id DESC;
");

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td><a href="customer-details-info.php?ctmr_id='.$row['cm_id'].'">' . $row['customerName'] . '</a><br/>'.$row['customerEmail'].'</td>';
            echo '<td><a href="view-pinvoice-action.php?inv_id='.$row['iid'].'">' .$row['quotationNumber'] . '</a><br/>'.$row['invoice_date'].'</td>';
            echo '<td>' . $row['grand_total'] . '<br/>'. $row['status'].'</td>';
            echo '<td>' . $row['quotationCreatedOn'].'<br/>'.$row['quotationCreatedBy']. '</td>';
           echo '<td> 
    <a href="'.$row['invoice_file'].'" class="btn-sm btn btn-primary" download>
        <i class="fa fa-download"></i>
    </a>
    <a href="mail-quotation.php?id='.$row['iid'].'&qcode='.$row['quotationNumber'].'" class="btn btn-primary btn-sm">
        <i class="fa fa-envelope"></i>
    </a>';

if (!empty($row['purchase_i_file'])) {
    echo '<a href="' . htmlspecialchars($row['purchase_i_file']) . '" target="_blank" class="btn btn-primary btn-sm">
        <i class="fa fa-upload"></i>
    </a>';
}

echo '</td>';
            // echo '<td> <a href="'.$row['quotation_file'].'" target="_blank" class="btn-sm btn btn-primary" download><i class="fa fa-download"></i></a></td>';

            echo '</tr>';
        }
        ?>
    </tbody>
</table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- [ stiped-table ] end -->
           
        </div>
        <!-- [ Main Content ] end -->
    </div>
</section>




    <!-- Required Js -->

 <!-- <script src="assets/js/jquery.min.js"></script> -->

        <!-- Bootstrap Core JavaScript -->
        <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
       <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
 <script type="text/javascript">
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            "ordering": false // Disable sorting completely
        });
    });
</script>
</body>
</html>
