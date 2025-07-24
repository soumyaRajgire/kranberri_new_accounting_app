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
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" 
          rel="stylesheet" 
          integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" 
          crossorigin="anonymous" /> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" 
          rel="stylesheet"> -->
    <style>
    .custom-table th, td, tr {
            border: 2px solid grey; /* Define your desired border style and color here */
    }
    .custom-table th {
        width: 400px;
        font-weight: bold;
        font-size: 14px;
    }
    .custom-table td {
        font-size: 14px;
    }
    #profile-datatable th {
        text-transform: capitalize;
        font-size: 14px;
    }
    .btn-custom {
        background-color: white;
        color: #00acc1; /* Text color when not hovering */
        border-color: #00acc1; /* Border color when not hovering */
    }

    .btn-custom:hover {
        background-color: #00acc1; /* Background color on hover */
        color: #fff; /* Text color on hover */
        border-color: #00acc1; /* Border color on hover */
    }
    </style>
    

</head>
<body class="">
    <!-- [ Pre-loader ] start -->
     
     <?php include("menu.php");?>
    
    
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
                                <h4 class="m-b-10">Customer profile</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Customer profile</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
           
            <div class="col-lg-12">
    <div class="card" style="height: 58px;">
        <div class="row">
            <div class="col-lg-12" style="font-size: 15px;">
                <ul class="nav nav-tabs mt-3" id="myTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#information">Information</a>
                    </li>
                    <li class="nav-item">        
        <a class="nav-link" data-bs-toggle="tab" href="#statement" onclick="fetchTabData('statement')">Accounts Statement</a>
    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#gst_reconcilation">GST Reconcilation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#bank_reconcilation">Bank Reconcilation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#payables">Payables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#receivables">Receivables</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#access">Access</a>
                    </li> -->
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#notes">Notes</a>
                    </li> -->
                </ul>
            </div>
            <!-- <div class="col-lg-6 text-right mt-2">
                <div class="btn-group" style="margin-right: 10px;">
                <button type="button" class="btn btn-success btn-bold" data-toggle="modal" data-target="#employeeStatusModal">
                     Active Employee
                </button>
                </div>
            </div> -->
        </div>
    </div>  
</div>
<?php



// Extract the customer ID from the URL
$customer_id = isset($_GET['id']) ? $_GET['id'] : null;

// Validate and fetch customer data
if ($customer_id) {
    // Query to fetch customer details from `customer_master`
    $customer_query = "
        SELECT 
            cm.id, cm.business_id, cm.branch_id, cm.title, cm.customerName, cm.entityType, 
            cm.mobile, cm.email, cm.gstin, cm.gst_reg_name, cm.business_name, 
            cm.display_name, cm.phone_no, cm.fax, cm.account_no, cm.account_name, 
            cm.bank_name, cm.account_type, cm.ifsc_code, cm.branch_name, cm.pan, cm.tan, 
            cm.tds_slab_rate, cm.currency, cm.terms_of_payment, cm.reverse_charge, 
            cm.export_or_sez, cm.contact_type, cm.created_by, cm.created_on
        FROM customer_master cm
        WHERE cm.id = $customer_id
    ";
    $customer_result = mysqli_query($conn, $customer_query);

    // Query to fetch address details from `address_master`
    $address_query = "
        SELECT  am.s_address_line1, am.s_address_line2, am.s_city, am.s_Pincode, am.s_state, 
            am.s_country, am.b_address_line1, am.b_address_line2, am.b_city, am.b_Pincode, 
            am.b_state, am.b_country, am.b_gstin FROM address_master am
        WHERE am.customer_master_id = $customer_id
    ";
    $address_result = mysqli_query($conn, $address_query);

    // Fetch the results
    if ($customer_row = mysqli_fetch_assoc($customer_result)) {
        $address_row = mysqli_fetch_assoc($address_result);
        ?>
<div class="tab-content">
        <!-- Profile Tab Content -->
        <div id="information" class="tab-pane fade show active">
     
            <div class="row"  style="margin-left:-10px;">
            <div class="col-md-3 card" style="height:450px; max-width: 445px;">

  


<div class="item mt-3">
    <div class="info">
        <a class="username mx-2" style="color: black; font-weight: bold"><?php echo $customer_row['business_name']; ?></a><br>
        <!-- <a href="#" class="kt-badge" style="margin-left: 250px; color: #ffb822; background: rgba(255, 184, 34, 0.1);">Pending</a> -->
        <a class="username mx-2" style="color: black; font-weight: bold"><?php echo $customer_row['entityType']; ?></a><br>
        <a class="username mx-2" style="color: black; font-weight: bold"><?php echo $customer_row['mobile']; ?></a>
    </div>
    
</div>
<hr>
<div class="item">
    <div class="info">
        <a href="#" class="sername mx-2" style="font-weight: bold; display: block;">New Quote</a>
        <a href="#" class="sername mx-2 mt-3" style="font-weight: bold; display: block;">New Invoice</a>
        <a href="#" class="sername mx-2 mt-3" style="font-weight: bold; display: block;">New Receipt</a>
        <a href="#" class="sername mx-2 mt-3" style="font-weight: bold; display: block;">New Credit Note</a>
        <!-- <a href="#" class="kt-font"  style="margin-left : 220px; color: blue;" ><span id="total_inv">0%</span></a> -->
    </div>
</div>
<hr>
<?php
// Extract the customer ID from the session or URL
$customer_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($customer_id) {
    // Fetch Credit Notes Total Amount
    $credit_note_query = "SELECT SUM(total_amount) AS total_credit_notes FROM credit_note WHERE customer_id = $customer_id";
    $credit_note_result = mysqli_query($conn, $credit_note_query);
    $credit_note_row = mysqli_fetch_assoc($credit_note_result);
    $total_credit_notes = $credit_note_row['total_credit_notes'] ?? 0;

    // Fetch Invoices Grand Total
    $invoice_query = "SELECT SUM(grand_total) AS total_invoices FROM  invoice  WHERE customer_id = $customer_id";
    $invoice_result = mysqli_query($conn, $invoice_query);
    $invoice_row = mysqli_fetch_assoc($invoice_result);
    $total_invoices = $invoice_row['total_invoices'] ?? 0;

    // Fetch Receivables (Due Amount)
    $receivables_query = "SELECT SUM(due_amount) AS total_receivables FROM invoice WHERE customer_id = $customer_id";
    $receivables_result = mysqli_query($conn, $receivables_query);
    $receivables_row = mysqli_fetch_assoc($receivables_result);
    $total_receivables = $receivables_row['total_receivables'] ?? 0;
}
?>

<div class="item mt-4">
    <div class="info d-flex justify-content-between">
        <span class="username" style="color: black; font-weight: bold">Credit Notes</span>
        <span class="kt-badge" style="color:rgb(41, 66, 203); background: rgba(255, 184, 34, 0.1);">
            Rs <?php echo number_format($total_credit_notes, 2); ?>
        </span>
    </div>
</div>

<div class="item mt-4">
    <div class="info d-flex justify-content-between">
        <span class="sername" style="color: black; font-weight: bold">Invoices</span>
        <span class="kt-font" style="color:rgb(41, 66, 203); background: rgba(255, 184, 34, 0.1);">
            Rs <?php echo number_format($total_invoices, 2); ?>
        </span>
    </div>
</div>

<div class="item mt-4">
    <div class="info d-flex justify-content-between">
        <span class="sername" style="color: black; font-weight: bold">Receivables</span>
        <span class="kt-font" style="color:rgb(41, 66, 203); background: rgba(255, 184, 34, 0.1);">
            Rs <?php echo number_format($total_receivables, 2); ?>
        </span>
    </div>
</div>

                    
        </div>
        
     <div class="col-md-8 mx-4 card">                                          
        <h5 class="mt-2">Account Information</h5>
            <table class="table table-bordered custom-table mt-1">
                <tbody>
                    <tr>
                        <th class="kt-font-bold">Customer Name</th>
                        <td><?php echo $customer_row['customerName']; ?></td>
                    </tr>
                    <tr>
                        <th class="kt-font-bold">Email</th>
                        <td><?php echo $customer_row['email']; ?></td>
                    </tr>
                  
                    <tr>
                        <th class="kt-font-bold">Mobile</th>
                        <td><?php echo $customer_row['mobile']; ?></td>
                    </tr>
                    <tr>
                        <th class="kt-font-bold">Business Name</th>
                        <td><?php echo $customer_row['business_name']; ?></td>
                    </tr>
                    <tr>
                        <th class="kt-font-bold">Entity</th>
                        <td><?php echo $customer_row['entityType']; ?></td>
                    </tr>
                    <tr>
                        <th class="kt-font-bold">GSTIN</th>
                        <td><?php echo $customer_row['gstin']; ?></td>
                    </tr>
                    <tr>
                        <th class="kt-font-bold">PAN</th>
                        <td><?php echo $customer_row['pan']; ?></td>
                    </tr>
                    <tr>
                        <th class="kt-font-bold">Billing Address</th>
                        <td>
                            <?php
                            echo $address_row['b_address_line1'] . ', ' .
                                 $address_row['b_address_line2'] . ', ' .
                                 $address_row['b_city'] . ', ' .
                                 $address_row['b_state'] . ', ' .
                                 $address_row['b_country'] . ' - ' .
                                 $address_row['b_Pincode'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="kt-font-bold">Shipping Address</th>
                        <td>
                            <?php
                            echo $address_row['s_address_line1'] . ', ' .
                                 $address_row['s_address_line2'] . ', ' .
                                 $address_row['s_city'] . ', ' .
                                 $address_row['s_state'] . ', ' .
                                 $address_row['s_country'] . ' - ' .
                                 $address_row['s_Pincode'];
                            ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="kt-font-bold">Bank</th>
                        <td><?php echo $customer_row['bank_name']; ?></td>
                    </tr>
                    <tr>
                        <th class="kt-font-bold">TDS Applicable</th>
                        <!-- <td><?php echo $customer_row['account_no']; ?></td> -->
                    </tr>
                    <tr>
                        <th class="kt-font-bold">TDS Deductible</th>
                        <!-- <td><?php echo $customer_row['account_no']; ?></td> -->
                    </tr>
                    <tr>
                        <th class="kt-font-bold">TDS Deducted</th>
                        <!-- <td><?php echo $customer_row['ifsc_code']; ?></td> -->
                    </tr>
                    <tr>
                        <th class="kt-font-bold">TAN</th>
                        <!-- <td><?php echo $customer_row['bank_name']; ?></td> -->
                    </tr>
                    <tr>
                        <th class="kt-font-bold">COA</th>
                        <!-- <td><?php echo $customer_row['bank_name']; ?></td> -->
                    </tr>
                    <tr>
                        <th class="kt-font-bold">MSME Number</th>
                        <!-- <td><?php echo $customer_row['bank_name']; ?></td> -->
                    </tr>
                    <tr>
                        <th class="kt-font-bold">Terms of Payment</th>
                        <!-- <td><?php echo $customer_row['bank_name']; ?></td> -->
                    </tr>
                    <tr>
                        <th class="kt-font-bold">Status</th>
                        <!-- <td><?php echo $customer_row['bank_name']; ?></td> -->
                    </tr>
                    <tr>
                        <th class="kt-font-bold">Created By</th>
                        <td><?php echo $customer_row['created_by']; ?></td>
                    </tr>
                    <tr>
                        <th class="kt-font-bold">Created On</th>
                        <td><?php echo $customer_row['created_on']; ?></td>
                    </tr>
                    <tr>
                        <th class="kt-font-bold">Contact ID</th>
                        <td><?php echo $customer_row['id']; ?></td>
                    </tr>
                    
                </tbody>
            </table>
    
        <?php
    } else {
        echo "Customer not found.";
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "Invalid customer ID.";
}
?>
                                                </div>

          
        </div>
        </div>

        <!-- Statement Tab Content -->
       
        <div id="statement" class="tab-pane fade">
    <div class="container my-4">
        <div class="row">
            <!-- Sidebar Section -->
            <div class="col-md-3">
                <!-- Card 1: Company Details -->
                <div class="card p-3">
                    <p class="username mx-2" style="color: black; font-weight: bold" id="business_name">--</p>
                    <p class="username mx-2" style="color: black; font-weight: bold" id="entity_type">--</p>
                    <p class="username mx-2" style="color: black; font-weight: bold" id="mobile">--</p>
                </div>

                <!-- Card 2: Quick Links -->
                <div class="card p-3">
                    <h6>Quick Links</h6>
                    <div class="link-list">
                        <a href="#">New Quote</a>
                        <a href="#">New Invoice</a>
                        <a href="#">New Receipt</a>
                        <a href="#">New Credit Note</a>
                    </div>
                </div>

                <!-- Card 3: Financial Overview -->
                <div class="card p-3">
                    <h6>Financial Overview</h6>
                    <p><strong>Credit Notes:</strong> <span id="credit_notes">--</span></p>
                    <p><strong>Invoices:</strong> <span id="invoices">--</span></p>
                    <p><strong>Receivable:</strong> <span id="receivable">--</span></p>
                </div>
            </div>

            <!-- Main Content Section -->
            <div class="card w-75">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Accounts Statement</h5>
                    <div>
                        <button class="btn btn-sm btn-secondary me-2">Print</button>
                        <button class="btn btn-sm btn-primary me-2">PDF</button>
                        <button class="btn btn-sm btn-success">Excel</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Number</th>
                                <th>Debit (in INR)</th>
                                <th>Credit (in INR)</th>
                            </tr>
                        </thead>
                        <tbody id="statementContent">
                            <!-- Table rows will be inserted here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

            <!-- Add your documents-related content here -->
        <!-- </div> -->

        <!-- Documents Tab Content -->
        <div id="gst_reconcilation" class="tab-pane fade">
            <!-- Content for the Documents tab -->
            <div class="container my-4">
    <div class="row">
        <!-- Sidebar Section -->
        <div class="col-md-3">
            <!-- Card 1: Company Details -->
            <div class="card p-3">
                <h5>MJVA WELLNESS PRIVATE LIMITED</h5>
                <p>Private Limited Company</p>
                <p>Phone: 9920590475</p>
            </div>

            <!-- Card 2: Quick Links -->
            <div class="card p-3">
                <h6>Quick Links</h6>
                <div class="link-list">
                    <a href="#">New Quote</a>
                    <a href="#">New Invoice</a>
                    <a href="#">New Receipt</a>
                    <a href="#">New Credit Note</a>
                </div>
            </div>

            <!-- Card 3: Financial Overview -->
            <div class="card p-3">
                <h6>Financial Overview</h6>
                <p><strong>Credit Notes:</strong> Rs. 59,000</p>
                <p><strong>Invoices:</strong> Rs. 5,90,000</p>
                <p><strong>Receivable:</strong> Rs. -5,31,000</p>
            </div>
        </div>

        <!-- GST Reconciliation Section -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>GST Reconciliation</h5>
                    <p>GST Data Synced on: <strong>Nov-2024</strong></p>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Total Invoiced</th>
                                <th>GST Collected</th>
                                <th>GST Paid</th>
                                <th>GST Remitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Apr-2024</td>
                                <td>INR 59,000</td>
                                <td>INR 9,000</td>
                                <td>INR 9,000</td>
                                <td><span class="badge badge-success">Paid</span></td>
                            </tr>
                            <tr>
                                <td>Mar-2024</td>
                                <td>INR 59,000</td>
                                <td>INR 9,000</td>
                                <td>INR 9,000</td>
                                <td><span class="badge badge-success">Paid</span></td>
                            </tr>
                            <tr>
                                <td>Feb-2024</td>
                                <td>INR 59,000</td>
                                <td>INR 9,000</td>
                                <td>INR 9,000</td>
                                <td><span class="badge badge-success">Paid</span></td>
                            </tr>
                            <tr>
                                <td>Jan-2024</td>
                                <td>INR 59,000</td>
                                <td>INR 9,000</td>
                                <td>INR 9,000</td>
                                <td><span class="badge badge-success">Paid</span></td>
                            </tr>
                            <tr>
                                <td>Dec-2023</td>
                                <td>INR 59,000</td>
                                <td>INR 9,000</td>
                                <td>INR 9,000</td>
                                <td><span class="badge badge-success">Paid</span></td>
                            </tr>
                            <tr>
                                <td>Nov-2023</td>
                                <td>INR 59,000</td>
                                <td>INR 9,000</td>
                                <td>INR 9,000</td>
                                <td><span class="badge badge-success">Paid</span></td>
                            </tr>
                            <tr>
                                <td>Oct-2023</td>
                                <td>INR 59,000</td>
                                <td>INR 9,000</td>
                                <td>INR 9,000</td>
                                <td><span class="badge badge-success">Paid</span></td>
                            </tr>
                            <tr>
                                <td>Sep-2023</td>
                                <td>INR 59,000</td>
                                <td>INR 9,000</td>
                                <td>INR 9,000</td>
                                <td><span class="badge badge-success">Paid</span></td>
                            </tr>
                            <tr>
                                <td>Aug-2023</td>
                                <td>INR 1,18,000</td>
                                <td>INR 18,000</td>
                                <td>INR 18,000</td>
                                <td><span class="badge badge-success">Paid</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between">
                        <p>Showing 1 - 9 of 9</p>
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option selected>10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            <!-- Add your documents-related content here -->
        </div>

        <!-- Approvals Tab Content -->
        <div id="bank_reconcilation" class="tab-pane fade">
        <div class="col-lg-12 tab-pane card mx-3" id="kt_tabs_6_4" style="width: 98%;">
        <div class="kt-portlet kt-portlet--height-fluid">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-toolbar">
            <div class="">
                <div class="btn-group mt-2">
                    <a href="#" class="btn btn-md btn-custom" id="attendanceChanges">Changes</a>&nbsp;&nbsp;
                    <a href="#" class="btn btn-md btn-custom" id="leaveApplication">Application</a>&nbsp;&nbsp;
                    <a href="#" class="btn btn-md btn-custom" id="addCheckin">Add</a>&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="kt-datatable table-responsive" id="approvals"></div>
    </div>
</div>
                                    <hr>
                                    <div class="portlet-body m-2">
            <!-- Profile Table -->
            <table class="table table-bordered" id="profile-datatable">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
            <!-- End Holidays Table -->
        </div>
                                </div>
                                
            
        </div>
        <div id="payables" class="tab-pane fade">
        <div class="col-lg-12 tab-pane card mx-3" id="kt_tabs_6_4" style="width: 98%;">
                                
        <div class="portlet-body m-2">
    <table class="table table-bordered" id="payables-datatable">
        <thead>
            <tr>
                <th>Pur.Invoice Code</th>
                <th>Pur.Invoice Date</th>
                <th>Grand Total (INR)</th>
                <th>Due Amount (INR)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="payablesContent">
            <!-- Dynamic content will be loaded here -->
        </tbody>
    </table>
</div>


                                </div>
                                
            
        </div>

        <div id="receivables" class="tab-pane fade">
        <div class="col-lg-12 tab-pane card mx-3" id="kt_tabs_6_4" style="width: 98%;">
  
                                
        <div class="portlet-body m-2">
    <table class="table table-bordered" id="receivables-datatable">
        <thead>
            <tr>
                <th>Receipt ID</th>
                <th>Receipt Date</th>
                <th>Invoice ID</th>
                <th>Paid Amount (INR)</th>
                <th>Payment Mode</th>
                <th>Transaction ID</th>
            </tr>
        </thead>
        <tbody id="receivablesContent">
            <!-- Dynamic content will be loaded here -->
        </tbody>
    </table>
</div>


                                </div>
                                
            
        </div>
        <!-- Access Tab Content -->
<div id="access" class="tab-pane fade">
    <!-- Content for the Access tab -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mx-3" style="height:400px;">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mt-1" style="height:350px;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">Modules</th>
                                    <th class="text-center">User</th>
                                    <th class="text-center">Manager</th>
                                    <th class="text-center">Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left">ConqHR - Employee Module</td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input user-select" id="user-conqhr-employee">
                                            <label class="custom-control-label" for="user-conqhr-employee"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input manager-select" id="manager-conqhr-employee">
                                            <label class="custom-control-label" for="manager-conqhr-employee"></label>
                                        </div>
                                    </td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td class="text-left">ConqHR - Employer Module</td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input user-select" id="user-conqhr-employer">
                                            <label class="custom-control-label" for="user-conqhr-employer"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input manager-select" id="manager-conqhr-employer">
                                            <label class="custom-control-label" for="manager-conqhr-employer"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input admin-select" id="admin-conqhr-employer">
                                            <label class="custom-control-label" for="admin-conqhr-employer"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">Superreceptionistapp</td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input user-select" id="user-superreceptionistapp">
                                            <label class="custom-control-label" for="user-superreceptionistapp"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input manager-select" id="manager-superreceptionistapp">
                                            <label class="custom-control-label" for="manager-superreceptionistapp"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input admin-select" id="admin-superreceptionistapp">
                                            <label class="custom-control-label" for="admin-superreceptionistapp"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">Ledgers</td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input user-select" id="user-ledgers">
                                            <label class="custom-control-label" for="user-ledgers"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input manager-select" id="manager-ledgers">
                                            <label class="custom-control-label" for="manager-ledgers"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-switch">
                                            <input type="checkbox" class="custom-control-input admin-select" id="admin-ledgers">
                                            <label class="custom-control-label" for="admin-ledgers"></label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    <div class="col-md-4 card" style="height:300px; max-width: 390px;">
     <div class="kt-widget4__item pb-0" style="border-bottom: 0px !important;">
      <div class="box_img d-flex align-items-center mt-4" style="border: 2px solid #e0e0e0; padding: 10px;">
        <div class="img_div mr-3">
            <img src="https://dhr.ledgers.cloud/images/male.jpeg" class="img-thumbnail img-fluid" style="width: 80px; height: 80px;" onerror="this.src='https://dhr.ledgers.cloud/images/female.jpeg'" data-toggle="modal" data-target="#profileUploadModal">
        </div>
        <div class="img_txt_div">
            <p class="mb-1" style="font-weight: bold;"><?php echo $row['name']; ?></p>
            <p class="mb-1 "><?php echo $row['designation']; ?></p>
            <p class="mb-0 "><?php echo $row['personalmobile']; ?></p>
        </div>
      </div>
     </div>

<div class="item mb-4 mt-5">
    <div class="info">
        <a href="#" class="username mx-2" style="color: black; font-weight: bold">KYC</a>
        <a href="#" class="kt-badge" style="margin-left: 250px; color: #ffb822; background: rgba(255, 184, 34, 0.1);">Pending</a>

    </div>
</div>

<div class="item">
    <div class="info">
        <a href="#" class="sername mx-2" style="color: black; font-weight: bold">Attendance</a>
        <a href="#" class="kt-font"  style="margin-left : 220px; color: blue;" ><span id="total_inv">0%</span></a>
    </div>
</div>

                    
        </div>
    </div>
    <!-- Add your access-related content here -->
</div>


        <!-- Notes Tab Content -->
        <div id="notes" class="tab-pane fade">
    <!-- Content for the Notes tab -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mx-3">
                <div class="dash_sec tab-pane" id="kt_tabs_6_6">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src='' id="notesframe"></iframe>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="col-md-4 card" style="height:300px; max-width: 390px;">
     <div class="kt-widget4__item pb-0" style="border-bottom: 0px !important;">
      <div class="box_img d-flex align-items-center mt-4" style="border: 2px solid #e0e0e0; padding: 10px;">
        <div class="img_div mr-3">
            <img src="https://dhr.ledgers.cloud/images/male.jpeg" class="img-thumbnail img-fluid" style="width: 80px; height: 80px;" onerror="this.src='https://dhr.ledgers.cloud/images/female.jpeg'" data-toggle="modal" data-target="#profileUploadModal">
        </div>
        <div class="img_txt_div">
            <p class="mb-1" style="font-weight: bold;"><?php echo $row['name']; ?></p>
            <p class="mb-1 "><?php echo $row['designation']; ?></p>
            <p class="mb-0 "><?php echo $row['personalmobile']; ?></p>
        </div>
      </div>
     </div>

<div class="item mb-4 mt-5">
    <div class="info">
        <a href="#" class="username mx-2" style="color: black; font-weight: bold">KYC</a>
        <a href="#" class="kt-badge" style="margin-left: 250px; color: #ffb822; background: rgba(255, 184, 34, 0.1);">Pending</a>

    </div>
</div>

<div class="item">
    <div class="info">
        <a href="#" class="sername mx-2" style="color: black; font-weight: bold">Attendance</a>
        <a href="#" class="kt-font"  style="margin-left : 220px; color: blue;" ><span id="total_inv">0%</span></a>
    </div>
</div>

                    
        </div>
    
    <!-- Add your notes-related content here -->
</div>

    </div>

            </div>  
            
</div>
</section>


    <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fetchPayablesReceivables() {
            const customerId = new URLSearchParams(window.location.search).get('id');
            console.log("Fetching data for customer ID:", customerId);
        
            if (!customerId) {
                console.error("Customer ID is missing");
                alert("Customer ID is missing.");
                return;
            }
        
            $.ajax({
                url: 'fetch_payables_receivables_customer.php',
                method: 'GET',
                data: { id: customerId },
                success: function (response) {
                    try {
                        console.log("Raw response:", response);
                        response = typeof response === 'string' ? JSON.parse(response) : response;
                        console.log("Parsed response:", response);
                        console.log("Debug info:", response.debug);
                        console.log("Counts:", response.counts);
                        
                        // Populate Receivables Table (Payments received)
                        let receivablesHtml = '';
                        if (response.receivables && response.receivables.length > 0) {
                            console.log("Processing receivables:", response.receivables);
                            response.receivables.forEach((item, index) => {
                                console.log(`Processing receivable ${index}:`, item);
                                receivablesHtml += `
                                    <tr>
                                        <td>${item.recpt_id || ''}</td>
                                        <td>${item.receipt_date ? new Date(item.receipt_date).toLocaleDateString() : ''}</td>
                                        <td>${item.invoice_id || ''}</td>
                                        <td>Rs ${item.paid_amount ? parseFloat(item.paid_amount).toFixed(2) : '0.00'}</td>
                                        <td>${item.payment_mode || ''}</td>
                                        <td>${item.transactionid || '-'}</td>
                                    </tr>`;
                            });
                        } else {
                            console.log("No receivables found");
                            receivablesHtml = '<tr><td colspan="6" class="text-center">No Receivables Found</td></tr>';
                        }
                        $('#receivablesContent').html(receivablesHtml);
                        console.log("Receivables table updated");

                        // Populate Payables Table (Pending Invoices)
                        let payablesHtml = '';
                        if (response.payables && response.payables.length > 0) {
                            console.log("Processing payables:", response.payables);
                            response.payables.forEach((item, index) => {
                                console.log(`Processing payable ${index}:`, item);
                                payablesHtml += `
                                    <tr>
                                        <td>${item.invoice_code - item.customer_name - item.customer_id  || ''}</td>
                                        <td>${item.Total_Payable ? new Date(item.invoice_date).toLocaleDateString() : ''}</td>
                                        <td>Rs ${item.grand_total ? parseFloat(item.grand_total).toFixed(2) : '0.00'}</td>
                                        <td>Rs ${item.due_amount ? parseFloat(item.due_amount).toFixed(2) : '0.00'}</td>
                                        <td>${item.status || ''}</td>
                                    </tr>`;
                                    
                                   
                            });
                        } else {
                            console.log("No payables found");
                            payablesHtml = '<tr><td colspan="5" class="text-center">No Payables Found</td></tr>';
                        }
                        $('#payablesContent').html(payablesHtml);
                        console.log("Payables table updated");

                    } catch (e) {
                        console.error("Error processing response:", e);
                        console.log("Raw response that caused error:", response);
                        alert("Failed to process data. Please check console for details.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    console.log("Status:", status);
                    console.log("Response Text:", xhr.responseText);
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        console.log("Parsed error response:", errorResponse);
                    } catch (e) {
                        console.log("Could not parse error response");
                    }
                    alert("Failed to fetch data. Please check console for details.");
                }
            });
        }
        
        // Load data when Payables or Receivables tabs are clicked
        $(document).ready(function () {
            console.log("Document ready, setting up tab handlers");
            
            // Initialize Bootstrap 5 tabs
            const triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="tab"]'));
            triggerTabList.forEach(function (triggerEl) {
                triggerEl.addEventListener('shown.bs.tab', function (event) {
                    const target = event.target.getAttribute('href');
                    console.log("Tab clicked:", target);
                    if (target === '#payables' || target === '#receivables') {
                        console.log("Fetching data for tab:", target);
                        fetchPayablesReceivables();
                    }
                });
            });
            
            // Load data initially
            console.log("Loading initial data");
            fetchPayablesReceivables();
        });
    </script>
    <script>
        // Define customerId based on the URL parameter
        const customerId = new URLSearchParams(window.location.search).get('id');

        // Fetch customer and ledger data
        function fetchCustomerDetails() {
            $.ajax({
                url: 'fetch_statement.php',
                method: 'GET',
                data: { id: customerId },
                success: function (response) {
                    if (response.customer) {
                        $('#business_name').text(response.customer.business_name || 'N/A');
                        $('#entity_type').text(response.customer.entityType || 'N/A');
                        $('#mobile').text(response.customer.mobile || 'N/A');
                    } else {
                        alert('Customer details not found.');
                    }

                    if (response.ledger && response.ledger.length > 0) {
                        let tableHtml = `
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Number</th>
                                        <th>Debit (INR)</th>
                                        <th>Credit (INR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;

                        response.ledger.forEach(entry => {
                            tableHtml += `
                                <tr>
                                    <td>${new Date(entry.transaction_date).toLocaleDateString()}</td>
                                    <td>${entry.receipt_or_voucher_no || '-'}</td>
                                    <td>${entry.debit ? parseFloat(entry.debit).toFixed(2) : '-'}</td>
                                    <td>${entry.credit ? parseFloat(entry.credit).toFixed(2) : '-'}</td>
                                </tr>
                            `;
                        });

                        tableHtml += '</tbody></table>';
                        $('#statementContent').html(tableHtml);
                    } else {
                        $('#statementContent').html('<p>No ledger records found.</p>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data:', error);
                    console.log('Status:', status);
                    console.log('Response:', xhr.responseText);
                    alert('Failed to fetch data. Please check console for details.');
                }
            });
        }

        // Initialize data fetching
        $(document).ready(function () {
            if (customerId) {
                fetchCustomerDetails();
            } else {
                alert('Customer ID is missing.');
            }
        });
    </script>

</body>
</html>