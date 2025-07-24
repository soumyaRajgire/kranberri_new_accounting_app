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

<?php include("sales_menu.php");?>
      <div class="container-fluid">
    <div class="row">
        <!-- Center Section Start-->
        <div class="col-lg-12 card">
     <div class="row">
    <!-- Nav tabs -->
   
    </div>
    <!-- Tab panes -->
    <div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="partywise_sec">
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
        <?php


// Function to get data for All Receivables
function getAllReceivables($conn) {
    $data = [];
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;

  $query = "
    SELECT 
        i.id AS invoice_id,
        i.customer_id,
        i.customer_name,
        i.due_date,
        i.grand_total - COALESCE(r.total_paid, 0) AS remaining_due,
        DATEDIFF(i.due_date, CURDATE()) AS due_in_days
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
    WHERE 
        (i.status = 'pending' OR i.status = 'partial') 
        AND i.branch_id = '" . $_SESSION['branch_id'] . "'";

if ($customer_id) {
    $query .= " AND i.customer_id = '$customer_id'";  // Filter by customer_id if provided
}

$query .= " ORDER BY i.due_date DESC";


    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();
    }

    return $data;
}




//$getAllReceivables = getAllReceivables($conn);


?>

      
    </div>
 <?php
  
  $getAllReceivables = getAllReceivables($conn);

$sno = 0;
foreach ($getAllReceivables as $ageing) :
    $sno++;

    // Calculate the aging categories
    $due_in_days = $ageing['due_in_days'];
    $remaining_due = $ageing['remaining_due'];

    // Determine which category this invoice falls into
    $age_category = '';
    if ($due_in_days <= 30 && $due_in_days >= 0) {
        $age_category = '0_30_Days';
    } elseif ($due_in_days <= 60 && $due_in_days >= 31) {
        $age_category = '31_60_Days';
    } elseif ($due_in_days <= 90 && $due_in_days >= 61) {
        $age_category = '61_90_Days';
    } elseif ($due_in_days < 0) {
        $age_category = 'Above_90_Days';
    }

    // Initialize aging category amounts
    $age_amounts = [
        '0_30_Days' => 0,
        '31_60_Days' => 0,
        '61_90_Days' => 0,
        'Above_90_Days' => 0
    ];

    // Assign amounts to the correct aging category
    if ($age_category) {
        $age_amounts[$age_category] = $remaining_due;
    }
    ?>

    <tr>
        <td><?php echo $sno; ?></td>
        <td><?php echo htmlspecialchars($ageing['customer_name']); ?></td>
        <td><?php echo htmlspecialchars($ageing['invoice_id']); ?></td>
        <td><?php echo htmlspecialchars($ageing['due_date']); ?></td>

        <!-- Display the corresponding aging category value -->
        <td class="overdue"><?php echo htmlspecialchars(ceil($age_amounts['0_30_Days'])); ?></td>
        <td class="overdue"><?php echo htmlspecialchars(ceil($age_amounts['31_60_Days'])); ?></td>
        <td class="overdue"><?php echo htmlspecialchars(ceil($age_amounts['61_90_Days'])); ?></td>
        <td class="overdue"><?php echo htmlspecialchars(ceil($age_amounts['Above_90_Days'])); ?></td>

        <td><?php echo htmlspecialchars(ceil($ageing['remaining_due'])); ?></td>
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
