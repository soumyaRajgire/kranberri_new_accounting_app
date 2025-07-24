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
    } else {
        $branch_id = null; // Default value or handle the error appropriately
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
<style>
  .dropdown-card {
    display: none;
    margin-top: 10px;
  }

  .active-card {
    display: block;
  }

          #purchase-invoices-datatable th,
          #purchase-order-datatable th,
          #voucher-datatable th,
          #payroll-datatable th,
          #debit-note-datatable th,
          #party-wise-datatable th,
          #accounts-datatable th {
        text-transform: capitalize;
        font-size: 14px;
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
                                <h4 class="m-b-10">Purchase Invoices</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top: -40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#">Purchase Invoices</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <hr>


            <div class="card" style="border-radius: 5px;">
    <div class="row">
        <div class="col-lg-9">
        <ul class="nav nav-tabs" id="myTabs">
        <li class="nav-item">
            <a class="nav-link active" onclick="showCard('invoicesCard')">Purchase Invoices</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('orderCard')">Purchase Order</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('voucherCard')">Voucher</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('PayrollCard')">Payroll</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('DebitNoteCard')">Debit Note</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('PartyWiseCard')">Party Wise Payable</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="showCard('AccountsCard')">Accounts Payable</a>
        </li>
    </ul>
        </div>

        <div class="col-lg-2 mt-2 mx-2">
            <div class="dropdown">
                <a class="btn btn-success btn-sm dropdown-toggle" href="#" id="createDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Create</a>
                <div class="dropdown-menu" aria-labelledby="createDropdown">
                    <a class="dropdown-item" target="_blank" href='create-purchase-invoice.php'>Purchase Invoice</a>
                    <a class="dropdown-item" target="_blank" href='create-purchase-order.php'>Purchase Order</a>
                    <a class="dropdown-item" target="_blank" href='create_voucher.php'>Voucher Payment</a>
                    <a class="dropdown-item" target="_blank" href='salary_payment.php'>Salary Payment</a>
                    <a class="dropdown-item" target="_blank" href='create-debit-note.php'>Debit Note</a>
                </div>
            </div>
        </div>
        </div>
        <div class="row" style="justify-content:end;">
        <div class="col-lg-4 mb-3">
            <div class="input-group mt-3">
                <input type="text" class="form-control" placeholder="Search..." id="generalSearch1">
            </div>
        </div>

        <div class="col-lg-2 mt-3">
            <div class="input-group date-range-picker">
                <input type="text" class="form-control date-filter bg-white" readonly placeholder="Date range" />
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-calendar-check"></i></span>
                </div>
            </div>
        </div>

        <div class="col-lg-1 mt-3">
            <div class="dropdown" data-toggle="tooltip" title="Filter">
                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-filter"></i> &nbsp; <span class="filter-text"></span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item quick-filter" data-filter="All" href="#">All</a>
                    <a class="dropdown-item quick-filter" data-filter="Paid" href="#">Paid</a>
                    <a class="dropdown-item quick-filter" data-filter="Unpaid" href="#">Unpaid</a>
                    <a class="dropdown-item quick-filter" data-filter="Part Paid" href="#">Part Paid</a>
                    <a class="dropdown-item quick-filter" data-filter="Deleted" href="#">Deleted</a>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Cards for each option -->
<?php
if ($branch_id) {
    // Query for a specific branch
    $sql = "SELECT cm.customerName, pi.pinvoice_code, pi.id as pi_id, pi.pinvoice_date, pi.grand_total, pi.created_on, pi.created_by, pi.customer_id
            FROM customer_master AS cm
            JOIN purchase_invoice AS pi ON cm.id = pi.customer_id
            WHERE cm.contact_type = 'supplier' AND pi.branch_id = '$branch_id'";
} else {
    // Query for all branches
    $sql = "SELECT cm.customerName, pi.pinvoice_code, pi.id as pi_id, pi.pinvoice_date, pi.grand_total, pi.created_on, pi.created_by, pi.customer_id, pi.branch_id
            FROM customer_master AS cm
            JOIN purchase_invoice AS pi ON cm.id = pi.customer_id
            WHERE cm.contact_type = 'supplier'";
}

$result = $conn->query($sql);
?>
<div class="row">
   <div class="col-md-12" style="margin-top: -30px;">
   <div class="tab-content mt-5">
    <div id="invoicesCard" class="tab-pane active">
        <!-- Purchase Invoices Card Content -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Purchase Invoices</h5>
                <table class="table" id="purchase-invoices-datatable">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Purchase Invoice</th>
                            <th>Total Amount</th>
                            <th>Payment</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                            <td>" . $row["customerName"] . "
                            <br><a href=''>Update GSTIN</a></td>
                            <td>" . $row["pinvoice_code"] . "<br>
                            " . $row["pinvoice_date"] . "</td>
                            <td>INR
                                " . $row["grand_total"] . "  
                                <br><a href='view-purchase-invoice-action.php?pinv_id=". $row['pi_id']."'>View Purchase Invoice</a>
                            </td>
                            <td></td>
                            <td>" . $row["created_on"] . " <br>
                            " . $row["created_by"] . "</td>
                        </tr>";
                        }
                    } else {
                        // If no records found
                        echo "<tr>
                                <td colspan='4'>
                                    <span>No records found</span>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>


    <?php
if ($branch_id) {
    $sql = "SELECT cm.customerName, po.order_code, po.id as pord_id, po.order_date, po.grand_total, po.created_on, po.created_by, po.customer_id
            FROM customer_master AS cm
            JOIN purchase_order AS po ON cm.id = po.customer_id
            WHERE cm.contact_type = 'supplier' AND po.branch_id = '$branch_id'";
} else {
    $sql = "SELECT cm.customerName, po.order_code, po.id as pord_id, po.order_date, po.grand_total, po.created_on, po.created_by, po.customer_id, po.branch_id
            FROM customer_master AS cm
            JOIN purchase_order AS po ON cm.id = po.customer_id
            WHERE cm.contact_type = 'supplier'";
}

$result = $conn->query($sql);
?>




<div id="orderCard" class="tab-pane" style="display: none;">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Purchase Order</h5>
            <table class="table" id="purchase-order-datatable">
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Number</th>
                        <th>Amount</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["customerName"] . "
                                    <br><a href=''>Update GSTIN</a></td>
                                    <td>" . $row["order_code"] . "<br>
                                    " . $row["order_date"] . "
                                </td>
                                    <td>INR " . $row["grand_total"] . "
                                    <br><a href='view-purchase-order-action.php?porder_id=". $row['pord_id']."'>View Purchase Order</a>
                                    </td>
                                    <td>" . $row["created_on"] . "
                                   <br> " . $row["created_by"] . "</td>
                                  </tr>";
                        }
                    } else {
                        // If no records found
                        echo "<tr>
                                <td colspan='4'>
                                    <span>No records found</span>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<?php
if ($branch_id) {
    // Query for a specific branch
    $sql = "SELECT v.id AS v_id, v.customer_name, v.voucherNumber, v.voucherDate, v.amount, v.paymentMode, pi.pinvoice_code, pi.id AS p_id
            FROM vouchers v
            JOIN purchase_invoice pi ON v.pinvoice_code = pi.pinvoice_code
            WHERE v.branch_id = '$branch_id'";
} else {
    // Query for all branches
    $sql = "SELECT v.id AS v_id, v.customer_name, v.voucherNumber, v.voucherDate, v.amount, v.paymentMode, pi.pinvoice_code, pi.id AS p_id, v.branch_id
            FROM vouchers v
            JOIN purchase_invoice pi ON v.pinvoice_code = pi.pinvoice_code";
}

$result = $conn->query($sql);
?>

<div id="voucherCard" class="tab-pane" style="display: none;">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Voucher</h5>
      <table class="table" id="voucher-datatable">
        <thead>
          <tr>
            <th>Payee</th>
            <th>Number</th>
            <th>Amount</th>
            <th>Accounting</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>" . $row["customer_name"] . "</td>
                      <td><a href='view-voucher-action.php?voucherId=" . $row['v_id'] . "'>" . $row["voucherNumber"] . "</a><br>" . $row["voucherDate"] . "</td>
                      <td>INR " . number_format($row["amount"], 2) . "<br>" . $row["paymentMode"] . "</td>
                      <td><a href='view-purchase-invoice-action.php?pinv_id=" . $row['p_id'] . "'>" . $row["pinvoice_code"] . "</a></td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='4'><span>No records found</span></td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>





<?php
if ($branch_id) {
    // Query for a specific branch
    $sql = "SELECT em.id AS employee_id, em.name, em.officemail, sp.voucherNumber, sp.payment_date, sp.amount, sp.payment_mode, sp.created_on, sp.created_by, sp.id AS voucher_id
            FROM employees_data AS em
            JOIN salary_payments AS sp ON em.id = sp.employee
            WHERE sp.branch_id = '$branch_id'
            GROUP BY sp.employee, sp.voucherNumber";
} else {
    // Query for all branches
    $sql = "SELECT em.id AS employee_id, em.name, em.officemail, sp.voucherNumber, sp.payment_date, sp.amount, sp.payment_mode, sp.created_on, sp.created_by, sp.id AS voucher_id, sp.branch_id
            FROM employees_data AS em
            JOIN salary_payments AS sp ON em.id = sp.employee
            GROUP BY sp.employee, sp.voucherNumber";
}

$result = $conn->query($sql);
?>

<div id="PayrollCard" class="tab-pane" style="display: none;">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Payroll</h5>
      <!-- Add your table content here -->
      <table class="table" id="payroll-datatable">
        <thead>
          <tr>
            <th>Payee</th>
            <th>Number</th>
            <th>Amount</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result && $result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td><a href='employee_profile.php?id=" . $row['employee_id'] . "'>" . $row["name"] . "</a><br>
                          " . $row["officemail"] . "
                      </td>
                      <td><a href='view-salarypayment-action.php?voucherId=" . $row['voucher_id'] . "'>" . $row["voucherNumber"] . "</a><br>" . $row["payment_date"] . "</td>
                      <td>INR " . $row["amount"] . "<br>" . $row["payment_mode"] . "</td>
                      <td>" . $row["created_on"] . "<br>" . $row["created_by"] . "</td>
                    </tr>";
            }
          } else {
            // If no records found
            echo "<tr>
                    <td colspan='4'>
                        <span>No records found</span>
                    </td>
                  </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>




<div id="DebitNoteCard" class="tab-pane" style="display: none;">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Debit Note</h5>
      
      <table class="table" id="debit-note-datatable">
        <thead>
          <tr>
            <th>Debit Amount</th>
            <th>Customer Name</th>
            <th>Note Number</th>
            <th>Document</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if ($branch_id) {
                // Query for a specific branch
                $sql = "SELECT id, dnote_code, dnote_file, customer_id, customer_name, email, dnote_date, total_amount, terms_condition, note, status, created_by, created_at
                        FROM debit_note
                        WHERE branch_id = '$branch_id'";
            } else {
                // Query for all branches
                $sql = "SELECT id, dnote_code, dnote_file, customer_id, customer_name, email, dnote_date, total_amount, terms_condition, note, status, created_by, created_at, branch_id
                        FROM debit_note";
            }
            
            $result = $conn->query($sql);

            // Check if any records are returned
            if ($result && mysqli_num_rows($result) > 0) {
              // Loop through each record and output as a table row
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['total_amount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dnote_code']) . "</td>";
                echo "<td><a href='" . htmlspecialchars($row['dnote_file']) . "' target='_blank'>View Document</a></td>";
                echo "<td>" . htmlspecialchars($row['dnote_date']) . "</td>";
                echo "</tr>";
              }
            } else {
              // If no records found, display a single row with "No records found"
              echo "<tr><td colspan='5'><span>No records found</span></td></tr>";
            }

            // Close the database connection
$conn->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<div id="PartyWiseCard" class="tab-pane" style="display: none;">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Party Wise Payable</h5>
      <table class="table" id="party-wise-datatable">
        <thead>
          <tr>
            <th>Balance Amount</th>
            <th>Supplier Name</th>
            <th>Paid Amount</th>
            <th>Last Payment</th>
            <th>GST ITC</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="5">
              <span>No records found</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div id="AccountsCard" class="tab-pane" style="display: none;">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Accounts Payable</h5>
      <table class="table" id="accounts-datatable">
        <thead>
          <tr>
            <th>Supplier</th>
            <th>Purchase Invoice</th>
            <th>Total Amount</th>
            <th>Payment</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="5">
              <span>No records found</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>


</div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
     </div>
</section>   
<script>
    function openDebitNoteModal() {
        // Show the hidden modal
        $('#debitNoteModal').modal('show');
    }
    document.addEventListener("DOMContentLoaded", function () {
    // Function to fetch and populate Party Wise Payable data
    function fetchPartyWisePayable() {
        $.ajax({
            url: "get_party_wise_payable.php",
            method: "GET",
            dataType: "json",
            success: function (data) {
                let rows = "";
                if (data.length > 0) {
                    data.forEach(row => {
                        rows += `
                            <tr>
                                <td>${row.balance_amount}</td>
                                <td>${row.supplier_name}</td>
                                <td>${row.paid_amount}</td>
                                <td>${row.last_payment_date ? row.last_payment_date : 'N/A'}</td>
                                <td>${row.gst_itc}</td>
                            </tr>
                        `;
                    });
                } else {
                    rows = "<tr><td colspan='5'><span>No records found</span></td></tr>";
                }
                $("#party-wise-datatable tbody").html(rows);
            },
            error: function (error) {
                console.error("Error fetching Party Wise Payable data:", error);
            }
        });
    }

    // Function to fetch and populate Accounts Payable data
    function fetchAccountsPayable() {
        $.ajax({
            url: "get_accounts_payable.php",
            method: "GET",
            dataType: "json",
            success: function (data) {
                let rows = "";
                if (data.length > 0) {
                    data.forEach(row => {
                        rows += `
                            <tr>
                                <td>${row.supplier_name}</td>
                                <td>${row.purchase_invoice}</td>
                                <td>${row.total_amount}</td>
                                <td>${row.payment_due}</td>
                                <td>${row.created_date}</td>
                            </tr>
                        `;
                    });
                } else {
                    rows = "<tr><td colspan='5'><span>No records found</span></td></tr>";
                }
                $("#accounts-datatable tbody").html(rows);
            },
            error: function (error) {
                console.error("Error fetching Accounts Payable data:", error);
            }
        });
    }

    // Tab switch event listener to load data dynamically
    document.querySelectorAll('.nav-link').forEach(tab => {
        tab.addEventListener('click', function () {
            const tabId = this.getAttribute("onclick").split("'")[1];
            if (tabId === "PartyWiseCard") {
                fetchPartyWisePayable();
            } else if (tabId === "AccountsCard") {
                fetchAccountsPayable();
            }
        });
    });

    // Fetch initial data for the default active tab
    fetchPartyWisePayable(); // Or fetchAccountsPayable() depending on default active tab
});

</script>
<!-- Debit Note  popup modal -->
<!-- JavaScript to handle card visibility -->


        
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Function to handle search logic
    function handleSearch(searchInput, tableId) {
        var searchTerm = searchInput.value.toLowerCase();
        var table = document.getElementById(tableId);
        var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        // Loop through each row in the table body
        for (var i = 0; i < rows.length; i++) {
            var rowData = rows[i].innerText.toLowerCase();

            // Check if the search term matches any part of the row data
            if (rowData.includes(searchTerm)) {
                // Display the row
                rows[i].style.display = '';
            } else {
                // Hide the row
                rows[i].style.display = 'none';
            }
        }
    }

    // Attach event listeners to search inputs
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'purchase-invoices-datatable');
    });
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'purchase-order-datatable');
    });
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'voucher-datatable');
    });
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'payroll-datatable');
    });
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'party-wise-datatable');
    });
    document.getElementById('generalSearch1').addEventListener('input', function () {
        handleSearch(this, 'accounts-datatable');
    });
    // Add similar event listeners for other search inputs and their respective tables
    // Example:
    // document.getElementById('yourSearchInputId').addEventListener('input', function () {
    //     handleSearch(this, 'yourTableId');
    // });

    // Repeat for other search inputs and tables as needed
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    const expenseVouchersLink = document.querySelector('a[href="purchase_invoices.php?voucherCard"]');
    expenseVouchersLink.addEventListener('click', function (event) {
        event.preventDefault();
        showDropdown('voucherCard');
    });

    const accountsPayablesLink = document.querySelector('a[href="purchase_invoices.php?AccountsCard"]');
    accountsPayablesLink.addEventListener('click', function (event) {
        event.preventDefault();
        showDropdown('AccountsCard');
    });
});

function showDropdown(dropdownId) {
    // Hide all dropdown cards
    const allDropdownCards = document.querySelectorAll('.dropdown-card');
    allDropdownCards.forEach(card => card.style.display = 'none');

    // Show the selected dropdown card
    const selectedDropdownCard = document.getElementById(dropdownId);
    if (selectedDropdownCard) {
        selectedDropdownCard.style.display = 'block';
    }

    // Update the dropdown button text
    const dropdownButton = document.getElementById('purchaseDropdown');
    dropdownButton.textContent = selectedDropdownCard ? selectedDropdownCard.querySelector('.card-title').textContent : '';
}


</script>
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <!-- <script src="assets/js/myscript.js"></script> -->
    <script>
    // Function to handle showing the correct card based on the tab clicked
    function showCard(cardId) {
        // Hide all cards
        document.querySelectorAll('.tab-pane').forEach(card => {
            card.style.display = 'none';
        });

        // Show the selected card
        document.getElementById(cardId).style.display = 'block';

        // Remove 'active' class from all tabs
        document.querySelectorAll('.nav-link').forEach(tab => {
            tab.classList.remove('active');
        });

        // Add 'active' class to the clicked tab
        event.target.classList.add('active');
    }

    // Attach click event listeners to all tab links
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function (event) {
                // Prevent default link behavior
                event.preventDefault();

                // Get the card ID from the data attribute
                const cardId = event.target.getAttribute('onclick').split("'")[1];

                // Show the corresponding card
                showCard(cardId);
            });
        });
    });
</script>

</body>
</html>

    
