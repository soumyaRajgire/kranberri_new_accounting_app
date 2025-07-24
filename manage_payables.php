<!DOCTYPE html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
    <title>Accounts App</title>
    <meta chaINRet="utf-8">
    <?php include("header_link.php");?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>
<body class="">
    <!-- Pre-loader -->
    <?php include("menu.php");?>
    <?php include("createNewVoucherModal.php"); ?>
    <!-- Header -->
    <style>
        /* Custom CSS styles for the card */
        .table{
            border: 2px solid grey; /* Define your desired border style and color here */
        
        }  
        #allreciv_sec th,
        #myreciv_sec th,
        #overdue_sec th,
        #underdue_sec th,
        #partywise_sec th {
        text-transform: capitalize;
        font-size: 14px;
    }
    </style>
 
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">Manage Payables</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <!-- <li class="breadcrumb-item"><a href="#">Manage Payables</a></li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?php include("purchases_menu.php");?>
      <div class="container-fluid">
    <div class="row">
        <!-- Center Section Start-->
        <div class="col-lg-12 card">
     <div class="row">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#partywise_sec">Party-wise Payables</a> 
        </li>
        <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#allreciv_sec">ageing</a>
        </li>
       <!--  <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#myreciv_sec">My Payables</a>
        </li> -->
        <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#overdue_sec">Overdue Payables</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#underdue_sec">Upcoming Payables</a>
        </li>
        <!-- <div class="form-group" style="margin-left: 0px;">
            <div class="input-group">
                <input type="text" class="form-control form-controt receive_search party_search mt-2" placeholder="Search...">
            </div>
        </div> -->
    </ul>
    </div>
    <!-- Tab panes -->
    <div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="partywise_sec">
        <?php

function getPartyWisePayables($conn) {
    $data = [];
$branch_id = $_SESSION['branch_id'];
   
// $query = "SELECT 
//     subquery.customer_id,
//     subquery.customer_name,
//     subquery.invoice_code,
//     subquery.remaining_due,
//     COALESCE(dn_subquery.debit_note_total_amount, 0) AS debit_note_total_amount,  -- Summing the debit note amounts
//     (subquery.remaining_due - COALESCE(dn_subquery.debit_note_total_amount, 0)) AS `Total_Payables`, -- Total Receivables
//     dn.id AS debit_note_id,
//     dn.dnote_code,
//     dn.dnote_file,
//     dn.purchase_invoice_id AS debit_note_invoice_id,
//     dn.customer_id AS debit_note_customer_id,
//     dn.branch_id AS debit_note_branch_id,
//     dn.customer_name AS debit_note_customer_name,
//     dn.email AS debit_note_email,
//     dn.dnote_date,
//     dn.total_amount AS debit_note_total_amount,
//     dn.adjusted_amount AS debit_note_adjusted_amount,
//     dn.terms_condition,
//     dn.note,
//     dn.status AS debit_note_status,
//     dn.created_by AS debit_note_created_by,
//     dn.created_at AS debit_note_created_at,
//     dn.total_gst_amount,
//     dn.total_cess_amount,
//     dn.is_deleted AS debit_note_is_deleted
// FROM (
//     SELECT 
//         i.customer_id,
//         i.customer_name,
//         i.invoice_code,
//         i.id,
//         i.grand_total - COALESCE(r.total_paid, 0) AS remaining_due
//     FROM 
//         pi_invoice i
//     LEFT JOIN (
//         SELECT 
//             invoice_id, 
//             SUM(paid_amount) AS total_paid
//         FROM 
//             voucher
//         GROUP BY 
//             invoice_id
//     ) r ON i.id = r.invoice_id
//     WHERE 
//         (i.status = 'pending' OR i.status = 'partial')  
//         AND i.branch_id = '1'
// ) AS subquery
// LEFT JOIN (
//     SELECT 
//         dn.purchase_invoice_id,
//         SUM(dn.total_amount) AS debit_note_total_amount
//     FROM 
//         debit_note dn
//     WHERE 
//         dn.branch_id = '1'
//     GROUP BY 
//         dn.purchase_invoice_id
// ) AS dn_subquery 
// ON subquery.id = dn_subquery.purchase_invoice_id  -- Join on invoice.id to ensure we're getting the right debit notes for the invoice
// LEFT JOIN debit_note dn 
// ON subquery.id = dn.purchase_invoice_id  -- Ensure we are linking the debit notes by invoice.id
// AND dn.branch_id = '1'
// GROUP BY 
//     subquery.customer_id, 
//     subquery.customer_name,
//     subquery.invoice_code, 
//     subquery.id, 
//     dn.id
// ORDER BY 
//     `Total_Payables` DESC;";


$query= "SELECT 
    subquery.customer_id,
    subquery.customer_name,
    SUM(subquery.remaining_due) AS total_remaining_due,
    COALESCE(SUM(dn_subquery.debit_note_total_amount), 0) AS debit_note_total_amount,  -- Summing the debit note amounts
    (SUM(subquery.remaining_due) - COALESCE(SUM(dn_subquery.debit_note_total_amount), 0)) AS Total_Payables,  -- Total Payables after adjusting debit notes
    GROUP_CONCAT(subquery.invoice_code) AS invoice_codes,  -- Concatenate all invoice codes for the customer
    GROUP_CONCAT(dn.dnote_code) AS debit_note_codes,  -- Concatenate all debit note codes for the customer
    GROUP_CONCAT(dn.dnote_file) AS debit_note_files,  -- Concatenate all debit note file paths
    MAX(dn.dnote_date) AS latest_debit_note_date  -- Get the latest debit note date
FROM (
    SELECT 
        i.customer_id,
        i.customer_name,
        i.invoice_code,
        i.id,
        i.grand_total - COALESCE(r.total_paid, 0) AS remaining_due
    FROM 
        pi_invoice i
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(paid_amount) AS total_paid
        FROM 
            voucher
        GROUP BY 
            invoice_id
    ) r ON i.id = r.invoice_id
    WHERE 
        (i.status = 'pending' OR i.status = 'partial')  
        AND i.branch_id = '$branch_id'
) AS subquery
LEFT JOIN (
    SELECT 
        dn.purchase_invoice_id,
        SUM(dn.total_amount) AS debit_note_total_amount
    FROM 
        debit_note dn
    WHERE 
        dn.branch_id = '$branch_id'
    GROUP BY 
        dn.purchase_invoice_id
) AS dn_subquery 
ON subquery.id = dn_subquery.purchase_invoice_id
LEFT JOIN debit_note dn 
ON subquery.id = dn.purchase_invoice_id
AND dn.branch_id = '$branch_id'
GROUP BY 
    subquery.customer_id, 
    subquery.customer_name
ORDER BY 
    Total_Payables DESC;";

    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}

// Function to get data for All Payables
function getAllPayables($conn) {
    $data = [];

   
 $query="SELECT 
    subquery.pi_invoice_id,
    subquery.customer_id,
    subquery.customer_name,
    subquery.due_date,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN -30 AND 0 THEN subquery.remaining_due
        ELSE 0 
    END) AS `0_30_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN -60 AND -31 THEN subquery.remaining_due
        ELSE 0 
    END) AS `31_60_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN -90 AND -61 THEN subquery.remaining_due
        ELSE 0 
    END) AS `61_90_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) < -90 THEN subquery.remaining_due
        ELSE 0 
    END) AS `Above_90_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 0 AND 30 THEN subquery.remaining_due
        ELSE 0 
    END) AS `+0_30_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 31 AND 60 THEN subquery.remaining_due
        ELSE 0 
    END) AS `+31_60_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 61 AND 90 THEN subquery.remaining_due
        ELSE 0 
    END) AS `+61_90_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) > 90 THEN subquery.remaining_due
        ELSE 0 
    END) AS `+Above_90_Days`,
    SUM(subquery.remaining_due) AS `Total`
FROM (
    SELECT 
        pi.id AS pi_invoice_id,
        pi.customer_id,
        pi.customer_name,
        pi.due_date,
        pi.grand_total - (
            COALESCE(v.total_paid, 0) + 
       
            COALESCE(dn.total_adjusted, 0) -- Subtract debit note adjusted amount
        ) AS remaining_due
    FROM 
        pi_invoice pi
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(paid_amount) AS total_paid
        FROM 
            voucher
        GROUP BY 
            invoice_id
    ) v ON pi.id = v.invoice_id
    LEFT JOIN (
        SELECT 
            pi_invoice_id, 
            SUM(reconciled_amount) AS total_reconciled
        FROM 
            voucher_reconciliation
        GROUP BY 
            pi_invoice_id
    ) vr ON pi.id = vr.pi_invoice_id
    LEFT JOIN (
        SELECT 
            purchase_invoice_id, 
            SUM(total_amount) AS total_adjusted
        FROM 
            debit_note
        -- WHERE status = 'approved' -- Only consider approved debit notes
        GROUP BY 
            purchase_invoice_id
    ) dn ON pi.id = dn.purchase_invoice_id
    WHERE 
        ( pi.status = 'pending' OR pi.status = 'partial')  and branch_id='" . $_SESSION['branch_id'] . "'
    GROUP BY 
        pi.id, pi.customer_id, pi.customer_name, pi.due_date, pi.grand_total
) AS subquery
GROUP BY 
    subquery.customer_id, subquery.customer_name
ORDER BY 
    `Total` DESC;

";
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}



function getOverduePayables($conn) {
    $data = [];
    $today = date('Y-m-d'); // Current date

  

  
 $query="SELECT 
    subquery.pi_invoice_id,
    subquery.customer_id,
    subquery.customer_name,
    subquery.due_date,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN -30 AND 0 THEN subquery.remaining_due
        ELSE 0 
    END) AS `0_30_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN -60 AND -31 THEN subquery.remaining_due
        ELSE 0 
    END) AS `31_60_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN -90 AND -61 THEN subquery.remaining_due
        ELSE 0 
    END) AS `61_90_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) < -90 THEN subquery.remaining_due
        ELSE 0 
    END) AS `Above_90_Days`,
    
    SUM(
        CASE 
            WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN -30 AND 0 THEN subquery.remaining_due
            ELSE 0 
        END +
        CASE 
            WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN -60 AND -31 THEN subquery.remaining_due
            ELSE 0 
        END +
        CASE 
            WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN -90 AND -61 THEN subquery.remaining_due
            ELSE 0 
        END +
        CASE 
            WHEN DATEDIFF(subquery.due_date, CURDATE()) < -90 THEN subquery.remaining_due
            ELSE 0 
        END
    ) AS `Total`
FROM (
    SELECT 
        pi.id AS pi_invoice_id,
        pi.customer_id,
        pi.customer_name,
        pi.due_date,
       pi.grand_total - (
            COALESCE(v.total_paid, 0) + 
     
            COALESCE(dn.total_adjusted, 0) -- Subtract debit note adjusted amount
        ) AS remaining_due
    FROM 
        pi_invoice pi
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(paid_amount) AS total_paid
        FROM 
            voucher
        GROUP BY 
            invoice_id
    ) v ON pi.id = v.invoice_id
    LEFT JOIN (
        SELECT 
            pi_invoice_id, 
            SUM(reconciled_amount) AS total_reconciled
        FROM 
            voucher_reconciliation
        GROUP BY 
            pi_invoice_id
    ) vr ON pi.id = vr.pi_invoice_id
    LEFT JOIN (
        SELECT 
            purchase_invoice_id, 
            SUM(total_amount) AS total_adjusted
        FROM 
            debit_note
        -- WHERE status = 'approved' -- Only consider approved debit notes
        GROUP BY 
            purchase_invoice_id
    ) dn ON pi.id = dn.purchase_invoice_id
    WHERE 
      (DATEDIFF(due_date, CURDATE()) < 0)  AND  (( pi.status = 'pending' OR pi.status = 'partial')  and branch_id='" . $_SESSION['branch_id'] . "')
    GROUP BY 
        pi.id, pi.customer_id, pi.customer_name, pi.due_date, pi.grand_total
) AS subquery
GROUP BY 
    subquery.customer_id, subquery.customer_name
ORDER BY 
    `Total` DESC;

";

    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}


function getUnderduePayables($conn) {
    $data = [];
    $today = date('Y-m-d'); // Current date

    


  
 $query="SELECT 
    subquery.pi_invoice_id,
    subquery.customer_id,
    subquery.customer_name,
    subquery.due_date,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 0 AND 30 THEN subquery.remaining_due
        ELSE 0 
    END) AS `0_30_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 31 AND 60 THEN subquery.remaining_due
        ELSE 0 
    END) AS `31_60_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 61 AND 90 THEN subquery.remaining_due
        ELSE 0 
    END) AS `61_90_Days`,
    SUM(CASE 
         WHEN DATEDIFF(subquery.due_date, CURDATE()) > 90 THEN subquery.remaining_due
        ELSE 0 
    END) AS `Above_90_Days`,
    
    SUM(
        CASE 
            WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 0 AND 30 THEN subquery.remaining_due
            ELSE 0 
        END +
        CASE 
            WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 31 AND 60 THEN subquery.remaining_due
            ELSE 0 
        END +
        CASE 
            WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 61 AND 90 THEN subquery.remaining_due
            ELSE 0 
        END +
        CASE 
            WHEN DATEDIFF(subquery.due_date, CURDATE()) > 90 THEN subquery.remaining_due
            ELSE 0 
        END
    ) AS `Total`
FROM (
    SELECT 
        pi.id AS pi_invoice_id,
        pi.customer_id,
        pi.customer_name,
        pi.due_date,
        pi.grand_total - (
            COALESCE(v.total_paid, 0) + 
       
            COALESCE(dn.total_adjusted, 0) -- Subtract debit note adjusted amount
        ) AS remaining_due
    FROM 
        pi_invoice pi
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(paid_amount) AS total_paid
        FROM 
            voucher
        GROUP BY 
            invoice_id
    ) v ON pi.id = v.invoice_id
    LEFT JOIN (
        SELECT 
            pi_invoice_id, 
            SUM(reconciled_amount) AS total_reconciled
        FROM 
            voucher_reconciliation
        GROUP BY 
            pi_invoice_id
    ) vr ON pi.id = vr.pi_invoice_id
    LEFT JOIN (
        SELECT 
            purchase_invoice_id, 
            SUM(total_amount) AS total_adjusted
        FROM 
            debit_note
        -- WHERE status = 'approved' -- Only consider approved debit notes
        GROUP BY 
            purchase_invoice_id
    ) dn ON pi.id = dn.purchase_invoice_id
    WHERE 
        (DATEDIFF(due_date, CURDATE()) > 0)  AND  (( pi.status = 'pending' OR pi.status = 'partial')  and branch_id='" . $_SESSION['branch_id'] . "')
    GROUP BY 
        pi.id, pi.customer_id, pi.customer_name, pi.due_date, pi.grand_total
) AS subquery
GROUP BY 
    subquery.customer_id, subquery.customer_name
ORDER BY 
    `Total` DESC;

";

    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}

// Depending on the selection, fetch the appropriate data
// Assuming there's a GET parameter 'view' that controls the selection
$partyWisePayables = getPartyWisePayables($conn);
$getAllPayables = getAllPayables($conn);
$overduePayables = getOverduePayables($conn); 
$underduePayables = getUnderduePayables($conn);

?>
 <style>
        /* Styling for overdue columns */
.overdue {
    background-color: #f8d7da; /* Light red for overdue */
    color: #721c24; /* Dark red text */
}

/* Styling for upcoming due columns */
.upcoming {
    background-color: #d4edda; /* Light green for upcoming dues */
    color: #155724; /* Dark green text */
}
 th.overdue {
    background-color: #f8d7da; /* Light red background for overdue headers */
    color: #721c24; /* Dark red text */
}

/* Styling for upcoming due column headers */
th.upcoming {
    background-color: #d4edda; /* Light green background for upcoming headers */
    color: #155724; /* Dark green text */
}
    </style>

        <table class="table table-bordered" id="dataTables-example">
            <!-- Table Header -->
            <thead>
                <tr>
                    <!--<th>Payable</th>-->
                    <th>Customer ID</th>
                    <tH>Purchase Invoice</th>
                    <th>Customer Name</th>
                    <th>Total payable</th>
                    <!--<th>Total Paid</th>-->
                    <!--<th>Last Payment</th>-->
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
<?php foreach ($partyWisePayables as $rec) : ?>
<tr>
    <!--<td><?php echo htmlspecialchars($rec['Payable']); ?></td>-->
    <!--<td><?php echo htmlspecialchars($rec['customerName']); ?></td>-->
    <!--<td><?php echo htmlspecialchars($rec['TotalBilled']); ?></td>-->
    <!--<td><?php echo htmlspecialchars($rec['TotalPaid']); ?></td>-->
    <!--<td><?php echo htmlspecialchars($rec['LastPayment']); ?></td>-->
    
    <td><?php echo htmlspecialchars($rec['customer_id']); ?></td>
     <td><?php echo htmlspecialchars($rec['invoice_codes']); ?></td>
    <td><?php echo htmlspecialchars($rec['customer_name']); ?></td>
<td><?php echo htmlspecialchars(number_format($rec['Total_Payables'], 2)); ?></td>

    
</tr>
<?php endforeach; ?>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="allreciv_sec">
        <table class="table table-bordered" id="dataTables-example1">
            <!-- Table Header -->
            <thead>
            <tr>
            <th>S.No.</th>
            <th>Name</th>
            <th colspan="4" class="overdue">OverDue</th> <!-- OverDue label for all overdue columns -->
            <th colspan="4" class="upcoming">Upcoming Due</th> 
            <th>Total</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
              <th class="overdue">0-30 Days</th>
        <th class="overdue">31-60 Days</th>
        <th class="overdue">61-90 Days</th>
        <th class="overdue">Above 90 Days</th>
        <th class="upcoming">0-30 Days</th>
        <th class="upcoming">31-60 Days</th>
        <th class="upcoming">61-90 Days</th>
        <th class="upcoming">Above 90 Days</th>
            <th></th>
        </tr>
            </thead>
           
          <tbody>
   
    
 <?php
    $sno=0;


    
    foreach ($getAllPayables as $ageing) :
    $sno++;
    ?>
    
         <tr>
        <td><?php echo $sno; ?></td>
<!-- <td><?php #echo htmlspecialchars($ageing['customer_id']); ?></td> -->
<?php
$customer_name = $ageing['customer_name'];
$name_parts = explode(' | ', $customer_name); // Split the string by " | "
?>
<td><?php echo htmlspecialchars($name_parts[0]); ?></td>


<!--<td><?php #echo htmlspecialchars($ageing['customer_name']); ?></td>-->

<td class="overdue"><?php echo htmlspecialchars(ceil($ageing['0_30_Days'])); ?></td>

<td class="overdue"><?php echo htmlspecialchars(ceil($ageing['31_60_Days'])); ?></td>

<td class="overdue"><?php echo htmlspecialchars(ceil($ageing['61_90_Days'])); ?></td>

<td class="overdue"><?php echo htmlspecialchars(ceil($ageing['Above_90_Days'])); ?></td>

<td class="upcoming"><?php echo htmlspecialchars(ceil($ageing['+0_30_Days'])); ?></td>

<td class="upcoming"><?php echo htmlspecialchars(ceil($ageing['+31_60_Days'])); ?></td>

<td class="upcoming"><?php echo htmlspecialchars(ceil($ageing['+61_90_Days'])); ?></td>

<td class="upcoming"><?php echo htmlspecialchars(ceil($ageing['+Above_90_Days'])); ?></td>

<td><?php echo htmlspecialchars(ceil($ageing['Total'])); ?></td>

    
    </tr>
        <?php endforeach; ?>
    </tbody>
        </table>
        
    </div>

<!--     <div class="tab-pane fade" id="myreciv_sec">
        <table class="table table-bordered">
           <thead>
                <tr>
                    <th>Balance</th>
                    <th>Customer</th>
                    <th>Total Billed</th>
                    <th>Total Paid</th>
                    <th>Balance</th>
                    <th>Last Payment</th>
                </tr>
            </thead>
            <tbody>
                 <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
        </table>
    </div> -->

    <div class="tab-pane fade" id="overdue_sec">
        <table class="table table-striped table-bordered" id="dataTables-example2">
            <!-- Table Header -->
            <thead>
                <tr>
                <th>Customer ID</th>
            <th>Customer Name</th>
            <th> 0-30 Days</th>
            <th> 31-60 Days</th>
            <th> 61-90 Days</th>
            <th> Above 90 Days</th>
          
            <th>Total</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
    <?php foreach ($overduePayables as $ageing) : ?>
    
         <tr>
        
<td><?php echo htmlspecialchars($ageing['customer_id']); ?></td>
<td><?php echo htmlspecialchars($ageing['customer_name']); ?></td>

<td><?php echo htmlspecialchars($ageing['0_30_Days']); ?></td>
<td><?php echo htmlspecialchars($ageing['31_60_Days']); ?></td>
<td><?php echo htmlspecialchars($ageing['61_90_Days']); ?></td>
<td><?php echo htmlspecialchars($ageing['Above_90_Days']); ?></td>


<td><?php echo htmlspecialchars($ageing['Total'] ); ?></td>
    </tr>
        <?php endforeach; ?>
    </tbody>
        </table>
    </div>

    <div class="tab-pane fade" id="underdue_sec">
        <table class="table table-bordered" id="dataTables-example1">
            <!-- Table Header -->
            <thead>
                <tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
         
            <th>0-30 Days</th>
            <th>31-60 Days</th>
            <th>61-90 Days</th>
            <th>After 90 Days</th>
            <th>Total</th>
                </tr>
            </thead>
           
          <tbody>
    <?php foreach ($underduePayables as $ageing) : ?>
    
         <tr>
        
<td><?php echo htmlspecialchars($ageing['customer_id']); ?></td>
<td><?php echo htmlspecialchars($ageing['customer_name']); ?></td>



<!--<td><?php echo htmlspecialchars($ageing['0_30_Days']); ?></td>-->
<td><?php echo htmlspecialchars(number_format($ageing['0_30_Days'], 2)); ?></td>

<td><?php echo  htmlspecialchars(number_format($ageing['31_60_Days'], 2)); ?></td>
<td><?php echo htmlspecialchars(number_format($ageing['61_90_Days'], 2)); ?></td>
<td><?php echo htmlspecialchars(number_format($ageing['Above_90_Days'], 2)); ?></td>

<td><?php echo htmlspecialchars(number_format($ageing['Total'], 2)); ?></td>

    </tr>
        <?php endforeach; ?>
    </tbody>
        </table>
        
    </div>
</div>

</div>

       <!--  <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="estimate_sec1">
                        <div class="list-group">
                            <a href="" class="list-group-item list-group-item-action">
                                0-15 Days
                                <span class="badge badge-primary float-right" id="total_receiveable_15">INR.0</span>
                            </a>
                            <a href="" class="list-group-item list-group-item-action">
                                15-30 Days
                                <span class="badge badge-success float-right" id="total_receiveable_30">INR.0</span>
                            </a>
                            <a href="" class="list-group-item list-group-item-action">
                                30-60 Days
                                <span class="badge badge-danger float-right" id="total_receiveable_60">INR.0</span>
                            </a>
                            <a href="" class="list-group-item list-group-item-action">
                                60-90 Days
                                <span class="badge badge-primary float-right" id="total_receiveable_90">INR.0</span>
                            </a>
                            <a href="" class="list-group-item list-group-item-action">
                                90-180 Days
                            <span class="badge badge-success float-right" id="total_receiveable_180">INR.0</span>
                            </a>
                            <a href="" class="list-group-item list-group-item-action">
                                Above 180 Days
                            <span class="badge badge-danger float-right" id="total_receiveable-180">INR.0</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Center Section End-->
        <!-- Right Section Start-->
        <!-- Right Section End -->
    </div>
    </div>


       </div>
    </section>        
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.min.js"></script>
    <script src="assets/js/myscript.js"></script>

    <script type="text/javascript">
    $(document).ready(function () {
    $('#dataTables-example').DataTable();
    $('.dataTables_length').addClass('bs-select');

    });
    $('#dataTables-example').dataTable( {
    "orderFixed": [ 4, 'asc' ]
    } );
</script>

 <script type="text/javascript">
    $(document).ready(function () {
    $('#dataTables-example1').DataTable();
    $('.dataTables_length').addClass('bs-select');

    });
    $('#dataTables-example1').dataTable( {
    "orderFixed": [ 4, 'asc' ]
    } );
</script>

 <script type="text/javascript">
    $(document).ready(function () {
    $('#dataTables-example2').DataTable();
    $('.dataTables_length').addClass('bs-select');

    });
    $('#dataTables-example2').dataTable( {
    "orderFixed": [ 4, 'asc' ]
    } );
</script>
</body>
</html>
