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
    /*.custom-table th, td, tr {
            border: 2px solid grey; 
    }
    .custom-table th {
        width: 400px;
        font-weight: bold;
        font-size: 14px;
    }
    .custom-table td {
        font-size: 14px;
    }*/
    /*#profile-datatable th {
        text-transform: capitalize;
        font-size: 14px;
    }*/
    .btn-custom {
        background-color: white;
        color: #00acc1; 
        border-color: #00acc1; 
    }

    .btn-custom:hover {
        background-color: #00acc1; /* Background color on hover */
        color: #fff; /* Text color on hover */
        border-color: #00acc1; /* Border color on hover */
    }
    </style>
    
<style type="text/css">
/* Global Styling */
body {
  font-family: Arial, sans-serif;
  background-color: #f4f6f9;
}

.card {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

/* Customer Info Section */
.customer-info p {
  margin-bottom: 10px;
  font-size: 14px;
  color: #333;
}

.customer-info strong {
  font-weight: bold;
}

/* Financial Summary Section */
.financial-summary {
  background-color: #f9f9f9;
  border-radius: 10px;
}

.financial-summary h5 {
  font-weight: 600;
}

.financial-summary .row {
  padding: 10px 0;
}

.financial-summary .col-6 {
  padding: 10px;
  background-color: #e9f7ff;
  border-radius: 5px;
}

.financial-summary .col-6 strong {
  font-weight: bold;
}

/* Button Styling */
.btn-block {
  width: 100%;
  font-weight: bold;
}

/* Customer Profile Table */
.customer-profile table {
  margin-top: 20px;
  border-collapse: collapse;
}

.customer-profile th, .customer-profile td {
  padding: 15px;
  text-align: left;
  border: 1px solid #ddd;
}

.customer-profile th {
  background-color: #f1f1f1;
  font-weight: bold;
}

/* Recent Invoices Table */
.recent-invoices table {
  margin-top: 20px;
  border-collapse: collapse;
}

.recent-invoices th, .recent-invoices td {
  padding: 12px;
  text-align: left;
  border: 1px solid #ddd;
}

.recent-invoices th {
  background-color: #f1f1f1;
  font-weight: bold;

}

.recent-invoices .text-danger {
  color: #ff5c5c;
}

@media (max-width: 768px) {
  .financial-summary .row {
    flex-direction: column;
  }

  .recent-invoices table th, .recent-invoices table td {
    font-size: 12px;
  }
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
        <a class="nav-link" data-bs-toggle="tab" href="#statement" >Accounts Statement</a>
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
    $customer_query = "SELECT 
            cm.id, cm.business_id, cm.branch_id, cm.title, cm.customerName, cm.entityType, 
            cm.mobile, cm.email, cm.gstin, cm.gst_reg_name, cm.business_name, 
            cm.display_name, cm.phone_no, cm.fax, cm.account_no, cm.account_name, 
            cm.bank_name, cm.account_type, cm.ifsc_code, cm.branch_name, cm.pan, cm.tan, 
            cm.tds_slab_rate, cm.currency, cm.terms_of_payment, cm.reverse_charge, 
            cm.export_or_sez, cm.contact_type, cm.created_by, cm.created_on
        FROM customer_master cm
        WHERE cm.id = $customer_id";
    $customer_result = mysqli_query($conn, $customer_query);

    // Query to fetch address details from `address_master`
    $address_query = "SELECT  am.s_address_line1, am.s_address_line2, am.s_city, am.s_Pincode, am.s_state, 
            am.s_country, am.b_address_line1, am.b_address_line2, am.b_city, am.b_Pincode, am.b_state, am.b_country, am.b_gstin
        FROM address_master am WHERE am.customer_master_id = $customer_id";
    $address_result = mysqli_query($conn, $address_query);

    // Fetch the results
    if ($customer_row = mysqli_fetch_assoc($customer_result)) {
        $address_row = mysqli_fetch_assoc($address_result);
           // If business name is empty, show the customer name instead
        $business_name = !empty($customer_row['business_name']) ? $customer_row['business_name'] : $customer_row['customerName'];

        // Display if any data is missing
        $missing_data = [];
        if (empty($customer_row['mobile'])) $missing_data[] = "Mobile";
        if (empty($customer_row['email'])) $missing_data[] = "Email";
        if (empty($customer_row['gstin'])) $missing_data[] = "GSTIN";
        if (empty($customer_row['pan'])) $missing_data[] = "PAN";
        ?>

<div class="tab-content">
        <!-- Profile Tab Content -->
        <div id="information" class="tab-pane fade show active">
     
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
    $invoice_query = "SELECT SUM(grand_total) AS total_invoices FROM invoice WHERE customer_id = $customer_id";
    $invoice_result = mysqli_query($conn, $invoice_query);
    $invoice_row = mysqli_fetch_assoc($invoice_result);
    $total_invoices = $invoice_row['total_invoices'] ?? 0;

    // Fetch Receivables (Due Amount)
    $receivables_query = "SELECT SUM(due_amount) AS total_receivables FROM  invoice WHERE customer_id = $customer_id";
    $receivables_result = mysqli_query($conn, $receivables_query);
    $receivables_row = mysqli_fetch_assoc($receivables_result);
    $total_receivables = $receivables_row['total_receivables'] ?? 0;
}
?>


<div class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar with buttons and customer details -->
    <div class="col-md-4">
      <div class="card p-4">
        <!-- Customer Info Section -->
        <h5 class="card-title">Customer Info</h5>
        <div class="customer-info">
            <p><strong>Business Name:</strong> <?php echo $business_name; ?></p>
            <p><strong>Customer Name:</strong> <?php echo $customer_row['customerName']; ?></p>
            <p><strong>Mobile:</strong> <?php echo !empty($customer_row['mobile']) ? $customer_row['mobile'] : '<span class="text-warning">Missing</span>'; ?></p>
            <p><strong>Email:</strong> <?php echo !empty($customer_row['email']) ? $customer_row['email'] : '<span class="text-warning">Missing</span>'; ?></p>
            <p><strong>GSTIN:</strong> <?php echo !empty($customer_row['gstin']) ? $customer_row['gstin'] : '<span class="text-warning">Missing</span>'; ?></p>
            <p><strong>PAN:</strong> <?php echo !empty($customer_row['pan']) ? $customer_row['pan'] : '<span class="text-warning">Missing</span>'; ?></p>
            <p><strong>Entity:</strong> <?php echo $customer_row['entityType']; ?></p>
        </div>

        <div class="row">
          <div class="">
            <strong>Billing Address</strong>
            <address>
               <?php echo $address_row['b_address_line1'] . ', ' . $address_row['b_address_line2'] . ', ' . $address_row['b_city'] . ', ' . $address_row['b_state'] . ', ' . $address_row['b_country'] . ' - ' . $address_row['b_Pincode'];  ?>
            </address>
          </div>
          <div class="">
            <strong>Shipping Address</strong>
            <address>
             <?php echo $address_row['s_address_line1'] . ', ' . $address_row['s_address_line2'] . ', ' . $address_row['s_city'] . ', ' . $address_row['s_state'] . ', ' . $address_row['s_country'] . ' - ' .  $address_row['s_Pincode']; ?>
            </address>
          </div>
        </div>
        <div class="">
            <p>Bank: </p>
            <span><?php echo $customer_row['bank_name']; ?></span>
            <p>Created BY: </p>
            <span><?php echo $customer_row['created_by']; ?></span>
            <p>Created BY: </p>
            <span><?php echo $customer_row['created_by']; ?></span>
            <p>Created BY: </p>
            <span><?php echo $customer_row['created_by']; ?></span>
             <!-- Show Update if any data is missing -->
                            <?php if (!empty($missing_data)): ?>
                                <div class="mt-4">
                                    <a href="update-customer.php?id=<?php echo $customer_id; ?>" class="btn btn-warning">Update Missing Info</a>
                                </div>
                            <?php endif; ?>
        </div>
      </div>

      <!-- Financial Summary Section -->
      <div class=" card p-4 mt-4">
           <div class="item">
    <div class="info">
        <a href="#" class="sername mx-2" style="font-weight: bold; display: block;">New Quote</a>
        <a href="#" class="sername mx-2 mt-3" style="font-weight: bold; display: block;">New Invoice</a>
        <a href="#" class="sername mx-2 mt-3" style="font-weight: bold; display: block;">New Receipt</a>
        <a href="#" class="sername mx-2 mt-3" style="font-weight: bold; display: block;">New Credit Note</a>
        <!-- <a href="#" class="kt-font"  style="margin-left : 220px; color: blue;" ><span id="total_inv">0%</span></a> -->
    </div>
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
       
      </div>
  
    </div>

    <!-- Customer Profile Information -->
    <div class="col-md-8">
      <div class="financial-summary customer-profile card p-4">
        <!-- <h4>Account Information</h4> -->
        <!-- <div class=" card p-4 mt-4"> -->
        <h5>Financial Summary</h5>
        <div class="row">
          <div class="col-6">
            <strong>Customer Receivables</strong><br>
            <span class="text-primary">INR  Rs <?php echo number_format($total_credit_notes, 2); ?></span>
          </div>
          <div class="col-6">
            <strong>Supplier Payable</strong><br>
            <span class="text-danger">INR 0</span>
          </div>
        </div>
      <!-- </div> -->
      </div>

      <!-- Recent Invoices Section -->
      <?php
// Fetching recent invoices
$sql_invoices = "SELECT * FROM invoice WHERE customer_id='$customer_id' AND is_deleted=0 ORDER BY invoice_date DESC LIMIT 5";
$result_invoices = $conn->query($sql_invoices);

// Fetching recent purchase invoices
$sql_purchase_invoices = "SELECT * FROM pi_invoice WHERE customer_id='$customer_id' ORDER BY invoice_date DESC LIMIT 5";
$result_purchase_invoices = $conn->query($sql_purchase_invoices);

// Fetching recent credit notes
$sql_credit_notes = "SELECT * FROM credit_note WHERE customer_id='$customer_id' AND is_deleted = 0 ORDER BY cnote_date DESC LIMIT 5";
$result_credit_notes = $conn->query($sql_credit_notes);
?>

      <div class="recent-invoices card p-4 mt-4">
    <h5>Recent Invoices</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice Date</th>
                <th>Invoice</th>
                <th>Payment Status</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Combine all results into one array
            $transactions = [];
            if ($result_invoices->num_rows > 0) {
                while($row1 = $result_invoices->fetch_assoc()) {
                    // Standardizing the data
                    $row1['transaction_date'] = $row1['invoice_date'];  // Standardize the date column
                    $row1['due_date'] = $row1['due_date'];  // Standardize the due date column
                    $row1['invoice_code'] = $row1['invoice_code'];
                    $row1['status'] = $row1['status'];
                    $transactions[] = $row1;
                }
            }

            // Collect purchase invoices
            if ($result_purchase_invoices->num_rows > 0) {
                while($row2 = $result_purchase_invoices->fetch_assoc()) {
                    // Standardizing the data
                    $row2['transaction_date'] = $row2['invoice_date'];  // Standardize the date column
                    $row2['due_date'] = $row2['due_date'];  // Standardize the due date column
                    $row2['invoice_code'] = $row2['invoice_code'];
                    $row2['status'] = $row2['status'];
                    $transactions[] = $row2;
                }
            }

            // Collect credit notes
            if ($result_credit_notes->num_rows > 0) {
                while($row3 = $result_credit_notes->fetch_assoc()) {
                    // Standardizing the data
                    $row3['transaction_date'] = $row3['cnote_date'];  // Standardize the date column
                    $row3['due_date'] = '';  // No due date for credit notes
                    $row3['cnote_code'] = $row3['cnote_code'];
                    $row3['status'] = $row3['status'];
                    $transactions[] = $row3;
                }
            }

            // Loop through all transactions and display them
            foreach ($transactions as $transaction) {
                echo "<tr>";
                echo "<td>" . date("d-m-Y", strtotime($transaction['transaction_date'])) . "</td>";
                echo "<td>";
                if (isset($transaction['invoice_code'])) {
                    echo "#" . $transaction['invoice_code'];
                } elseif (isset($transaction['pi_invoice_code'])) {
                    echo "#" . $transaction['pi_invoice_code']; // purchase invoice code
                } elseif (isset($transaction['cnote_code'])) {
                    echo "#" . $transaction['cnote_code']; // credit note code
                }
                echo "</td>";
                echo "<td class='text-danger'>";
                if ($transaction['status'] == 'Not Paid') {
                    echo "Not Paid";
                } else {
                    echo "Paid";
                }
                echo "</td>";
                echo "<td>";
                if ($transaction['due_date'] != '') {
                    echo date("d-m-Y", strtotime($transaction['due_date']));
                } else {
                    echo "N/A";  // No due date for credit notes
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    </div>
  </div>
</div>


 <?php
    } else {
        echo "Customer not found.";
    }

    // Close the database connection
    // mysqli_close($conn);
} else {
    echo "Invalid customer ID.";
}
?>
  </div>

        <!-- Statement Tab Content -->
       
        <div id="statement" class="tab-pane fade">
    <div class="container my-4">
        <div class="row">
            <!-- Sidebar Section -->
            <div class="col-md-4">
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
<div class="col-md-8 card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Accounts Statement</h5>
        <div>
            <button class="btn btn-sm btn-secondary me-2" onclick="window.print()">Print</button>
            <button class="btn btn-sm btn-primary me-2" id="download-pdf">PDF</button>
            <button class="btn btn-sm btn-success" id="download-excel">Excel</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Voucher No</th>
                    <th>Debit (INR)</th>
                    <th>Credit (INR)</th>
                </tr>
            </thead>
            <tbody id="statementContent">
                <?php
                        // Fetching account statement data from ledger table
$sql = "SELECT * FROM ledger WHERE account_id = '$customer_id' ORDER BY transaction_date DESC";
$result = $conn->query($sql);
                // Displaying fetched data in table rows
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        // Initialize debit and credit columns
                        $debit = $row['debit_credit'] == 'Debit' ? $row['amount'] : '-';
                        $credit = $row['debit_credit'] == 'Credit' ? $row['amount'] : '-';

                        echo "<tr>";
                        echo "<td>" . date("m/d/Y", strtotime($row['transaction_date'])) . "</td>";
                        echo "<td>" . $row['voucher_id'] . "</td>";
                        echo "<td>" . $debit . "</td>";
                        echo "<td>" . $credit . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found.</td></tr>";
                }
                ?>
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
        <div class="col-md-4">
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
        <div class="col-md-8">
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
   <!--  <div class="kt-portlet__head">
        <div class="kt-portlet__head-toolbar">
            <div class="">
                <div class="btn-group mt-2">
                    <a href="#" class="btn btn-md btn-custom" id="attendanceChanges">Changes</a>&nbsp;&nbsp;
                    <a href="#" class="btn btn-md btn-custom" id="leaveApplication">Application</a>&nbsp;&nbsp;
                    <a href="#" class="btn btn-md btn-custom" id="addCheckin">Add</a>&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div> -->
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

<!-- <div id="payables" class="tab-pane fade">
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
                <!--</tbody>
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
                    <!--</tbody>
                </table>
            </div>
        </div>                                          
    </div> -->

    <?php

// Fetching payables (Purchase Invoices and Debit Notes)
$sql_payables = "
SELECT 
    pi.id,
    pi.customer_id,
    pi.customer_name,
    pi.invoice_code,
    pi.invoice_date,  -- Adding invoice date
    pi.grand_total,   -- Purchase invoice grand total
    COALESCE(dn_subquery.total_adjusted, 0) AS total_adjusted,  -- Debit note adjustments
    COALESCE(v.total_paid, 0) AS total_paid,  -- Payments made
    -- Correct calculation of remaining due (after adjustments and payments)
    ROUND((pi.grand_total - COALESCE(dn_subquery.total_adjusted, 0) - COALESCE(v.total_paid, 0)), 2) AS `remaining_due`,
    
    -- Correct calculation of Total Payables (ensuring no negative value)
    ROUND(
        CASE 
            WHEN (pi.grand_total - COALESCE(dn_subquery.total_adjusted, 0) - COALESCE(v.total_paid, 0)) < 0 THEN 0
            ELSE (pi.grand_total - COALESCE(dn_subquery.total_adjusted, 0) - COALESCE(v.total_paid, 0))
        END, 
        2
    ) AS `Total_Payables`,

    -- Status based on total paid and total due
    CASE
        WHEN pi.grand_total = COALESCE(v.total_paid, 0) THEN 'Paid'
        WHEN pi.grand_total > COALESCE(v.total_paid, 0) AND pi.grand_total - COALESCE(v.total_paid, 0) < COALESCE(dn_subquery.total_adjusted, 0) THEN 'Partial'
        ELSE 'Pending'
    END AS status

FROM 
    pi_invoice pi
LEFT JOIN (
    -- Debit note adjustments
    SELECT 
        purchase_invoice_id, 
        SUM(total_amount) AS total_adjusted
    FROM 
        debit_note
    GROUP BY 
        purchase_invoice_id
) dn_subquery 
ON pi.id = dn_subquery.purchase_invoice_id
LEFT JOIN (
    -- Payments made via vouchers
    SELECT 
        invoice_id, 
        SUM(paid_amount) AS total_paid
    FROM 
        voucher 
    GROUP BY 
        invoice_id
) v ON pi.id = v.invoice_id
WHERE 
    (pi.status = 'pending' OR pi.status = 'partial')  -- Only unpaid or partially paid invoices
    AND pi.branch_id = '$branch_id'
    AND pi.customer_id = '$customer_id'  -- Customer filter
ORDER BY 
    `Total_Payables` DESC;
";

$result_payables = $conn->query($sql_payables);

// Fetching receivables (Receipts)
// Modified query for fetching Receivables
$sql_receivables = "
SELECT 
    subquery.customer_id,
    subquery.customer_name,
    subquery.invoice_code,
    subquery.invoice_date,  -- Adding invoice date
    subquery.grand_total,   -- Include grand total in the subquery for correct referencing
    subquery.remaining_due,
    COALESCE(cn_subquery.credit_note_total_amount, 0) AS credit_note_total_amount,
    COALESCE(r.total_paid, 0) AS total_paid,
    (subquery.grand_total - COALESCE(cn_subquery.credit_note_total_amount, 0) - COALESCE(r.total_paid, 0)) AS `Total_Receivables`, -- Correct calculation of remaining due
    -- Credit note fields
    cn.id AS credit_note_id,
    cn.cnote_code,
    cn.cnote_file,
    cn.invoice_id AS credit_note_invoice_id,
    cn.customer_id AS credit_note_customer_id,
    cn.branch_id AS credit_note_branch_id,
    cn.customer_name AS credit_note_customer_name,
    cn.email AS credit_note_email,
    cn.cnote_date,
    cn.total_amount AS credit_note_total_amount,
    cn.adjusted_amount AS credit_note_adjusted_amount,
    cn.terms_condition,
    cn.note,
    cn.status AS credit_note_status,
    cn.created_by AS credit_note_created_by,
    cn.created_at AS credit_note_created_at,
    cn.total_gst_amount,
    cn.total_cess_amount,
    cn.is_deleted AS credit_note_is_deleted
FROM (
    SELECT 
        i.customer_id,
        i.customer_name,
        i.invoice_code,
        i.invoice_date,  -- Adding invoice date
        i.grand_total,   -- Including grand total in subquery
        i.id,
        i.grand_total - COALESCE(r.total_paid, 0) AS remaining_due
    FROM 
        invoice i
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(paid_amount) AS total_paid
        FROM 
            receipts
        GROUP BY 
            invoice_id
    ) r ON i.id = r.invoice_id
    WHERE 
        (i.status = 'pending' OR i.status = 'partial')  -- Only unpaid or partially paid invoices
        AND i.branch_id = '$branch_id'
        AND i.customer_id = '$customer_id'  -- Customer filter
) AS subquery
LEFT JOIN (
    SELECT 
        cn.invoice_id,
        SUM(cn.total_amount) AS credit_note_total_amount
    FROM 
        credit_note cn
    WHERE 
        cn.branch_id = '$branch_id'
    GROUP BY 
        cn.invoice_id
) AS cn_subquery 
ON subquery.id = cn_subquery.invoice_id
LEFT JOIN credit_note cn 
ON subquery.id = cn.invoice_id 
AND cn.branch_id = '$branch_id'
LEFT JOIN (
    SELECT 
        invoice_id,
        SUM(paid_amount) AS total_paid
    FROM 
        receipts
    GROUP BY 
        invoice_id
) r ON subquery.id = r.invoice_id
WHERE 
    subquery.customer_id = '$customer_id'  -- Ensure we filter by customer_id for the final result
GROUP BY 
    subquery.customer_id, 
    subquery.customer_name, 
    subquery.invoice_code, 
    subquery.id, 
    cn.id
ORDER BY 
    `Total_Receivables` DESC;

";



$result_receivables = $conn->query($sql_receivables);
?>

<!-- Payables Tab -->
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
                    <?php
                    // Displaying payables (purchase invoices and debit notes)
                    if ($result_payables->num_rows > 0) {
                        while($row4 = $result_payables->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row4['invoice_code'] . "</td>";
                            echo "<td>" . date("d-m-Y", strtotime($row4['invoice_date'])) . "</td>";
                            echo "<td>" . number_format($row4['grand_total'], 2) . "</td>";
                            echo "<td>" . number_format($row4['remaining_due'], 2) . "</td>";
                            echo "<td>" . $row4['status'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No payables found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Receivables Tab -->
<div id="receivables" class="tab-pane fade">
    <div class="col-lg-12 tab-pane card mx-3" id="kt_tabs_6_4" style="width: 98%;">
        <div class="portlet-body m-2">
            <table class="table table-bordered" id="receivables-datatable">
                <thead>
                    <tr>
                        <th>Invocie Code</th>
                        <th> Date</th>
                        <th>Total amount</th>
                        <th>TOtal Receivables</th>
                        <!-- <th>Payment Mode</th> -->
                        <!-- <th>Transaction ID</th> -->
                    </tr>
                </thead>
                <tbody id="receivablesContent">
                    <?php
// Displaying receivables (receipts)
// Displaying Receivables (Receipts)
// Displaying Receivables (Receipts)
if ($result_receivables->num_rows > 0) {
    while($row = $result_receivables->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['invoice_code'] . "</td>";  // Invoice code
        echo "<td>" . date("d-m-Y", strtotime($row['invoice_date'])) . "</td>";  // Invoice date
        echo "<td>" . number_format($row['grand_total'], 2) . "</td>";  // Remaining due
        echo "<td>" . number_format($row['Total_Receivables'], 2) . "</td>";  // Total receivables
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No receivables found.</td></tr>";
}


?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Close database connection
// $conn->close();
?>

            
</div>  
            
</div>
</section>


    <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // function fetchPayablesReceivables() {
        //     const customerId = new URLSearchParams(window.location.search).get('id');
        //     console.log("Fetching data for customer ID:", customerId);
        
        //     if (!customerId) {
        //         console.error("Customer ID is missing");
        //         alert("Customer ID is missing.");
        //         return;
        //     }
        
        //     $.ajax({
        //         url: 'fetch_payables_receivables_customer.php',
        //         method: 'GET',
        //         data: { id: customerId },
        //         success: function (response) {
        //             try {
        //                 console.log("Raw response:", response);
        //                 response = typeof response === 'string' ? JSON.parse(response) : response;
        //                 console.log("Parsed response:", response);
        //                 console.log("Debug info:", response.debug);
        //                 console.log("Counts:", response.counts);
                        
        //                 // Populate Receivables Table (Payments received)
        //                 let receivablesHtml = '';
        //                 if (response.receivables && response.receivables.length > 0) {
        //                     console.log("Processing receivables:", response.receivables);
        //                     response.receivables.forEach((item, index) => {
        //                         console.log(`Processing receivable ${index}:`, item);
        //                         receivablesHtml += `
        //                             <tr>
        //                                 <td>${item.recpt_id || ''}</td>
        //                                 <td>${item.receipt_date ? new Date(item.receipt_date).toLocaleDateString() : ''}</td>
        //                                 <td>${item.invoice_id || ''}</td>
        //                                 <td>Rs ${item.paid_amount ? parseFloat(item.paid_amount).toFixed(2) : '0.00'}</td>
        //                                 <td>${item.payment_mode || ''}</td>
        //                                 <td>${item.transactionid || '-'}</td>
        //                             </tr>`;
        //                     });
        //                 } else {
        //                     console.log("No receivables found");
        //                     receivablesHtml = '<tr><td colspan="6" class="text-center">No Receivables Found</td></tr>';
        //                 }
        //                 $('#receivablesContent').html(receivablesHtml);
        //                 console.log("Receivables table updated");

        //                 // Populate Payables Table (Pending Invoices)
        //                 let payablesHtml = '';
        //                 if (response.payables && response.payables.length > 0) {
        //                     console.log("Processing payables:", response.payables);
        //                     response.payables.forEach((item, index) => {
        //                         console.log(`Processing payable ${index}:`, item);
        //                         payablesHtml += `
        //                             <tr>
        //                                 <td>${item.invoice_code - item.customer_name - item.customer_id  || ''}</td>
        //                                 <td>${item.Total_Payable ? new Date(item.invoice_date).toLocaleDateString() : ''}</td>
        //                                 <td>Rs ${item.grand_total ? parseFloat(item.grand_total).toFixed(2) : '0.00'}</td>
        //                                 <td>Rs ${item.due_amount ? parseFloat(item.due_amount).toFixed(2) : '0.00'}</td>
        //                                 <td>${item.status || ''}</td>
        //                             </tr>`;
                                    
                                   
        //                     });
        //                 } else {
        //                     console.log("No payables found");
        //                     payablesHtml = '<tr><td colspan="5" class="text-center">No Payables Found</td></tr>';
        //                 }
        //                 $('#payablesContent').html(payablesHtml);
        //                 console.log("Payables table updated");

        //             } catch (e) {
        //                 console.error("Error processing response:", e);
        //                 console.log("Raw response that caused error:", response);
        //                 alert("Failed to process data. Please check console for details.");
        //             }
        //         },
        //         error: function (xhr, status, error) {
        //             console.error("AJAX Error:", error);
        //             console.log("Status:", status);
        //             console.log("Response Text:", xhr.responseText);
        //             try {
        //                 const errorResponse = JSON.parse(xhr.responseText);
        //                 console.log("Parsed error response:", errorResponse);
        //             } catch (e) {
        //                 console.log("Could not parse error response");
        //             }
        //             alert("Failed to fetch data. Please check console for details.");
        //         }
        //     });
        // }
        
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
                        // fetchPayablesReceivables();
                    }
                });
            });
            
            // Load data initially
            console.log("Loading initial data");
            // fetchPayablesReceivables();
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