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
        ?>
        
        <script type="text/javascript"> 
    //alert("Business ID: " + "<?php echo $_SESSION['business_id']; ?>" +           "\nBranch ID: " + "<?php echo $_SESSION['branch_id']; ?>" +            "\nGSTIN: " + "<?php echo $_SESSION['sel_gstin']; ?>");
</script>
        <?php
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
                                <h4 class="m-b-10">Manage Receivables</h4>
                            </div>
                            <ul class="breadcrumb" style="float: right; margin-top:-40px;">
                                <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                                <!-- <li class="breadcrumb-item"><a href="#">Manage Receivables</a></li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

<?php include("sales_menu.php");?>
      <div class="container-fluid">
    <div class="row">
        <!-- Center Section Start-->
        <div class="col-lg-12 card">
     <div class="row">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#partywise_sec">Party-wise Receivables</a> 
        </li>
        <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#allreciv_sec">ageing</a>
        </li>
       <!--  <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#myreciv_sec">My Receivables</a>
        </li> -->
        <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#overdue_sec">Overdue Receivables</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#underdue_sec">Upcoming Receivables</a>
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

function getPartyWiseReceivables($conn) {
    $data = [];
$branch_id = $_SESSION['branch_id'];
  
              
//             $query = "SELECT 
//     subquery.customer_id,
//     subquery.customer_name,
//     subquery.invoice_code,
//     subquery.remaining_due,
//     COALESCE(cn_subquery.credit_note_total_amount, 0) AS credit_note_total_amount,  -- Summing the credit note amounts
//     (subquery.remaining_due - COALESCE(cn_subquery.credit_note_total_amount, 0)) AS `Total_Receivables`, -- Total Receivables
//     cn.id AS credit_note_id,
//     cn.cnote_code,
//     cn.cnote_file,
//     cn.invoice_id AS credit_note_invoice_id,
//     cn.customer_id AS credit_note_customer_id,
//     cn.branch_id AS credit_note_branch_id,
//     cn.customer_name AS credit_note_customer_name,
//     cn.email AS credit_note_email,
//     cn.cnote_date,
//     cn.total_amount AS credit_note_total_amount,
//     cn.adjusted_amount AS credit_note_adjusted_amount,
//     cn.terms_condition,
//     cn.note,
//     cn.status AS credit_note_status,
//     cn.created_by AS credit_note_created_by,
//     cn.created_at AS credit_note_created_at,
//     cn.total_gst_amount,
//     cn.total_cess_amount,
//     cn.is_deleted AS credit_note_is_deleted
// FROM (
//     SELECT 
//         i.customer_id,
//         i.customer_name,
//         i.invoice_code,
//         i.id,
//         i.grand_total - COALESCE(r.total_paid, 0) AS remaining_due
//     FROM 
//         invoice i
//     LEFT JOIN (
//         SELECT 
//             invoice_id, 
//             SUM(paid_amount) AS total_paid
//         FROM 
//             receipts
//         GROUP BY 
//             invoice_id
//     ) r ON i.id = r.invoice_id
//     WHERE 
//         (i.status = 'pending' OR i.status = 'partial')  
//         AND i.branch_id = '$branch_id'
// ) AS subquery
// LEFT JOIN (
//     SELECT 
//         cn.invoice_id,
//         SUM(cn.total_amount) AS credit_note_total_amount
//     FROM 
//         credit_note cn
//     WHERE 
//         cn.branch_id = '$branch_id'
//     GROUP BY 
//         cn.invoice_id
// ) AS cn_subquery 
// ON subquery.id = cn_subquery.invoice_id  -- Join on invoice.id to ensure we're getting the right credit notes for the invoice
// LEFT JOIN credit_note cn 
// ON subquery.id = cn.invoice_id  -- Ensure we are linking the credit notes by invoice.id
// AND cn.branch_id = '$branch_id'
// GROUP BY 
//     subquery.customer_id, 
//     subquery.customer_name, 
//     subquery.invoice_code, 
//     subquery.id, 
//     cn.id
// ORDER BY 
//     `Total_Receivables` DESC;";

$query="SELECT 
    subquery.customer_id,
    subquery.customer_name,
    SUM(subquery.remaining_due) AS total_remaining_due,
    SUM(subquery.gttotal) AS gttotalAmt,
    COALESCE(SUM(dn_subquery.credit_note_total_amount), 0) AS credit_note_total_amount,
    (SUM(subquery.remaining_due) - COALESCE(SUM(dn_subquery.credit_note_total_amount), 0)) AS Total_Receivables,
    GROUP_CONCAT(subquery.invoice_code) AS invoice_codes,
    GROUP_CONCAT(dn_subquery.cnote_code) AS credit_note_codes,  -- Use dnote_code from dn_subquery to avoid duplication
    GROUP_CONCAT(dn_subquery.cnote_file) AS credit_note_files,  -- Use dnote_file from dn_subquery
    MAX(dn_subquery.cnote_date) AS latest_debit_note_date  -- Get the latest debit note date from dn_subquery
FROM (
    SELECT 
        i.customer_id,
        i.customer_name,
        i.invoice_code,
        i.id,
        i.status,
    i.grand_total AS gttotal,
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
        (i.status = 'pending' OR i.status = 'partial')  
        AND i.branch_id = '$branch_id'
) AS subquery
LEFT JOIN (
    SELECT 
        dn.invoice_id,
        dn.customer_id,
        dn.is_deleted,
        SUM(dn.total_amount) AS credit_note_total_amount,
        GROUP_CONCAT(dn.cnote_code) AS cnote_code,
        GROUP_CONCAT(dn.cnote_file) AS cnote_file,
        MAX(dn.cnote_date) AS cnote_date
    FROM 
        credit_note dn
    WHERE 
        dn.branch_id = '$branch_id' AND (dn.is_deleted = 0 OR dn.is_deleted IS NULL)
    GROUP BY 
        dn.invoice_id, dn.customer_id
) AS dn_subquery 
ON subquery.id = dn_subquery.invoice_id
GROUP BY 
    subquery.customer_id
ORDER BY 
    Total_Receivables DESC;";


//SELECT `id`, `bill_code`, `customer_id`, `customer_name`, `customer_email`, `due_date`, `total_amount`,  `grand_total`, `due_amount`,  `branch_id`, FROM `bill_of_supply` WHERE 

    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}

// Function to get data for All Receivables
function getAllReceivables($conn) {
    $data = [];

   $branch_id = $_SESSION['branch_id'];
 $query="SELECT 
    subquery.invoice_id,
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
        i.id AS invoice_id,
        i.customer_id,
        i.customer_name,
        i.due_date,
        -- Calculate remaining_due, now subtracting credit note amounts as well
        i.grand_total - (
            COALESCE(r.total_paid, 0) + 
            COALESCE(dn.total_credit_note, 0) -- Subtract the total credit note amount
        ) AS remaining_due
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
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(reconciled_amount) AS total_reconciled
        FROM 
            reconciliation
        GROUP BY 
            invoice_id
    ) rc ON i.id = rc.invoice_id
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(total_amount) AS total_credit_note
        FROM 
            credit_note
        WHERE 
            is_deleted = 0 OR is_deleted IS NULL  -- Only non-deleted credit notes
        GROUP BY 
            invoice_id
    ) dn ON i.id = dn.invoice_id  -- Join credit notes to subtract from remaining due
    WHERE 
        (i.status = 'pending' OR i.status = 'partial') 
        AND i.branch_id = '$branch_id'
) AS subquery
GROUP BY 
    subquery.customer_id, subquery.customer_name
ORDER BY 
    `Total` DESC;";
    
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}





function getOverdueReceivables($conn) {
    $data = [];
    $today = date('Y-m-d'); // Current date

    // $query = "SELECT r.*, c.customerName  FROM receipts r  INNER JOIN customer_master c ON r.customer_id = c.id  WHERE  (SUM(r.total_amount) - SUM(r.paid_amount)) > 0 AND r.transaction_date < '$today'";

  //  $query =" SELECT r.customer_id, c.customerName, SUM(r.total_amount) AS total_amount, SUM(r.paid_amount) AS paid_amount, r.transaction_date FROM receipts r INNER JOIN customer_master c ON r.customer_id = c.id WHERE r.transaction_date < '$today' GROUP BY r.customer_id, c.customerName HAVING (SUM(r.total_amount) - SUM(r.paid_amount)) > 0";

$branch_id = $_SESSION['branch_id'];
  
 $query="SELECT 
    subquery.customer_id,
    subquery.customer_name,
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
    SUM(subquery.remaining_due) AS `Total`,
    -- Final Receivables after Credit Notes and Receipts
    SUM(subquery.remaining_due) - COALESCE(SUM(dn_subquery.credit_note_total_amount), 0) AS `Total_Receivables`
FROM (
    SELECT 
        i.customer_id,
        i.customer_name,
        i.invoice_code,
        i.id,
        i.status,
        i.due_date,
        i.grand_total AS gttotal,
        -- Adjust remaining_due calculation after considering paid amounts and credit notes
        i.grand_total - COALESCE(r.total_paid, 0) - COALESCE(dn.total_credit_note, 0) AS remaining_due  -- Subtract both receipts and credit notes
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
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(total_amount) AS total_credit_note
        FROM 
            credit_note
        WHERE 
            is_deleted = 0 OR is_deleted IS NULL  -- Only non-deleted credit notes
        GROUP BY 
            invoice_id
    ) dn ON i.id = dn.invoice_id  -- Join credit note to subtract from remaining due
    WHERE 
        (i.status = 'pending' OR i.status = 'partial')  
        AND i.branch_id = '$branch_id'
) AS subquery
LEFT JOIN (
    SELECT 
        dn.invoice_id,
        dn.customer_id,
        SUM(dn.total_amount) AS credit_note_total_amount
    FROM 
        credit_note dn
    WHERE 
        dn.branch_id = '$branch_id' 
        AND (dn.is_deleted = 0 OR dn.is_deleted IS NULL)  -- Only non-deleted credit notes
    GROUP BY 
        dn.invoice_id, dn.customer_id
) AS dn_subquery 
ON subquery.id = dn_subquery.invoice_id  -- Join on invoice.id to ensure we're getting the right credit notes for the invoice
GROUP BY 
    subquery.customer_id, 
    subquery.customer_name
ORDER BY 
    `Total_Receivables` DESC;
";



    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}


function getUnderdueReceivables($conn) {
    $data = [];
    $today = date('Y-m-d'); // Current date

    // $query = "SELECT r.*, c.customerName  FROM receipts r  INNER JOIN customer_master c ON r.customer_id = c.id  WHERE  (SUM(r.total_amount) - SUM(r.paid_amount)) > 0 AND r.transaction_date < '$today'";

  //  $query =" SELECT r.customer_id, c.customerName, SUM(r.total_amount) AS total_amount, SUM(r.paid_amount) AS paid_amount, r.transaction_date FROM receipts r INNER JOIN customer_master c ON r.customer_id = c.id WHERE r.transaction_date < '$today' GROUP BY r.customer_id, c.customerName HAVING (SUM(r.total_amount) - SUM(r.paid_amount)) > 0";

$branch_id = $_SESSION['branch_id'];
  
 $query="SELECT  
    subquery.customer_id,
    subquery.customer_name,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 0 AND 30 THEN subquery.remaining_due
        ELSE 0 
    END) AS `0_30_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 31 AND 60 THEN subquery.remaining_due
        ELSE 0 
    END) AS `31_60_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) BETWEEN 60 AND 90 THEN subquery.remaining_due
        ELSE 0 
    END) AS `61_90_Days`,
    SUM(CASE 
        WHEN DATEDIFF(subquery.due_date, CURDATE()) > 90 THEN subquery.remaining_due
        ELSE 0 
    END) AS `Above_90_Days`,
    SUM(CASE 
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
    ) AS `Total`,
    -- Final Receivables after Credit Notes
    SUM(subquery.remaining_due) - COALESCE(SUM(dn_subquery.credit_note_total_amount), 0) AS `Total_Receivables`
FROM (
    SELECT 
        i.customer_id,
        i.customer_name,
        i.invoice_code,
        i.id,
        i.status,
        i.due_date,
        i.grand_total AS gttotal,
        -- Adjust remaining_due calculation after considering paid amounts, reconciled amounts, and credit notes
        i.grand_total - COALESCE(r.total_paid, 0) - COALESCE(dn.total_credit_note, 0) AS remaining_due  -- Subtract both receipts and credit notes
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
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(total_amount) AS total_credit_note
        FROM 
            credit_note
        WHERE 
            is_deleted = 0 OR is_deleted IS NULL  -- Only non-deleted credit notes
        GROUP BY 
            invoice_id
    ) dn ON i.id = dn.invoice_id  -- Join with credit note table to subtract from remaining due
    WHERE 
        (i.status = 'pending' OR i.status = 'partial')  
        AND i.branch_id = '$branch_id'
) AS subquery
LEFT JOIN (
    SELECT 
        dn.invoice_id,
        dn.customer_id,
        SUM(dn.total_amount) AS credit_note_total_amount
    FROM 
        credit_note dn
    WHERE 
        dn.branch_id = '$branch_id' 
        AND (dn.is_deleted = 0 OR dn.is_deleted IS NULL)  -- Only non-deleted credit notes
    GROUP BY 
        dn.invoice_id, dn.customer_id
) AS dn_subquery 
ON subquery.id = dn_subquery.invoice_id  -- Join on invoice.id to ensure we're getting the right credit notes for the invoice
GROUP BY 
    subquery.customer_id, 
    subquery.customer_name
ORDER BY 
    `Total_Receivables` DESC;
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
$partyWiseReceivables = getPartyWiseReceivables($conn);
$getAllReceivables = getAllReceivables($conn);
$overdueReceivables = getOverdueReceivables($conn); 
$underdueReceivables = getUnderdueReceivables($conn);

?>

        <table class="table table-bordered" id="dataTables-example">
            <!-- Table Header -->
            <thead>
                <tr>
                    <!-- <th>User ID</th> -->
                    <th>Code</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <!--<th>Total paid Amount</th></th>-->
                    <th>Credit amount</th>
                    <th>Total Receivable</th>
                    <!--<th>Total Paid</th>-->
                    <!--<th>Last Payment</th>-->
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
<?php 

foreach ($partyWiseReceivables as $rec) :

?>
<!--<tr>-->
<!--    <td><?php echo htmlspecialchars($rec['Receivable']); ?></td>-->
<!--    <td><?php echo htmlspecialchars($rec['customerName']); ?></td>-->
<!--    <td><?php echo htmlspecialchars($rec['TotalBilled']); ?></td>-->
<!--    <td><?php echo htmlspecialchars($rec['TotalPaid']); ?></td>-->
<!--    <td><?php echo htmlspecialchars($rec['LastPayment']); ?></td>-->
<!--</tr>-->
<tr>
    
    <!-- <td><?php echo htmlspecialchars($rec['customer_id']); ?></td> -->
    <td><?php echo $rec['invoice_codes']?></td>
    <td><?php echo htmlspecialchars($rec['customer_name']); ?></td>
    <td><?php echo htmlspecialchars($rec['gttotalAmt']); ?></td>
      <!--<td><?php echo htmlspecialchars($rec['TotalPaidAmount']); ?></td>-->
    <td><?php echo htmlspecialchars($rec['credit_note_total_amount']); ?></td>
    <td><?php echo htmlspecialchars(number_format($rec['Total_Receivables'], 2)); ?></td>
    
</tr>

<?php endforeach; ?>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
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
    <div class="tab-pane fade" id="allreciv_sec">
        <table class="table table-bordered" id="dataTables-example1">
            <!-- Table Header -->
            <thead>
            <tr>
            <th>S.No.</th>
            <th>Name</th>
          <th colspan="4" class="overdue">Overdue</th> <!-- Overdue label for all overdue columns -->
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


    
    foreach ($getAllReceivables as $ageing) :
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

<td class="overdue"><?php echo htmlspecialchars(($ageing['0_30_Days'])); ?></td>

<td class="overdue"><?php echo htmlspecialchars(($ageing['31_60_Days'])); ?></td>

<td class="overdue"><?php echo htmlspecialchars(($ageing['61_90_Days'])); ?></td>

<td class="overdue"><?php echo htmlspecialchars(($ageing['Above_90_Days'])); ?></td>

<td class="upcoming"><?php echo htmlspecialchars(($ageing['+0_30_Days'])); ?></td>

<td class="upcoming"><?php echo htmlspecialchars(($ageing['+31_60_Days'])); ?></td>

<td class="upcoming"><?php echo htmlspecialchars(($ageing['+61_90_Days'])); ?></td>
<td class="upcoming"><?php echo htmlspecialchars(($ageing['+Above_90_Days'])); ?></td>


<td><?php echo htmlspecialchars(($ageing['Total'])); ?></td>

    </tr>
        <?php endforeach; ?>
    </tbody>
        </table>
        
    </div>


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
    <?php foreach ($overdueReceivables as $ageing) : ?>
    
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
    <?php foreach ($underdueReceivables as $ageing) : ?>
    
         <tr>
        
<td><?php echo htmlspecialchars($ageing['customer_id']); ?></td>

    <td>
        <a href="individual_invoice_wise_recievables.php?customer_id=<?php echo $ageing['customer_id']; ?>">
            <?php echo htmlspecialchars($ageing['customer_name']); ?>
            </a>
            </td>





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
</div>

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
