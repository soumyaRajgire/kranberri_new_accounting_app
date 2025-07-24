
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


<?php
// include("config.php");

// Fetch data from delivery_challan table
// $queryChallan = "SELECT * FROM `delivery_challan` WHERE 1";
// $resultChallan = mysqli_query($conn, $queryChallan);

// $challans = [];
// if ($resultChallan) {
//     while ($row = mysqli_fetch_assoc($resultChallan)) {
//         $challans[] = $row;
//     }
// }


$queryTdc = "SELECT 
        dc.id as dcid, 
        dc.dc_code, 
        dc.customer_name, 
        dc.dc_date, 
        dc.total_amount, 
        dc.created_on, 
        dct.mode, 
        dct.vehicle_number, 
        dct.coach_number, 
        dct.flight_number, 
        dct.voyage_number
    FROM 
        delivery_challan AS dc
    LEFT JOIN 
        delivery_challan_transportation_details AS dct
    ON 
        dc.id = dct.dc_id
    WHERE 
        dc.branch_id = '$branch_id' ORDER BY created_on DESC";
$resultChallanTdc = mysqli_query($conn, $queryTdc);

$challansTds = [];
if ($resultChallanTdc) {
    while ($row = mysqli_fetch_assoc($resultChallanTdc)) {
        $challansTds[] = $row;
    }
}


// Fetch data from delivery_challan_items table
// $queryItems = "SELECT `id`, `dc_code`, `product_name`, `prod_desc`, `qty`, `price`, `line_total`, `gst`, `gst_amt`, `total`, `created_by`, `created_at`, `updated_at` FROM `delivery_challan_items` WHERE 1";
// $resultItems = mysqli_query($conn, $queryItems);

$queryItems = "SELECT * FROM `delivery_challan_items` WHERE `dc_id` = ?";
$stmt = $conn->prepare($queryItems);
$stmt->bind_param("s", $dc_code); // Replace $dc_code with the actual DC code
$stmt->execute();
$resultItems = $stmt->get_result();


$items = [];
if ($resultItems) {
    while ($row = mysqli_fetch_assoc($resultItems)) {
        $items[] = $row;
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">

    <style>
    .tab-button.active {
    background-color: #007bff;
    color: #fff;
}
.mandatory-symbol {
    color: red;
  }
  .error {
            color: red;
            size: 80%
        }

        .hidden {
            display: none;
        }

</style>
<style>
    .highlight-error {
        border: 2px solid red;
    }
</style>

</head>

<body class="">
    <!-- [ Pre-loader ] start -->

    <?php include("menu.php"); ?>

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
                                <h4 class="m-b-10">Delivery challan</h4>
                                
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
<?php include("sales_menu.php");?>
        
            <!-- <div class="card mt-3"> -->
                <div class="col-md-12">
            <!-- Table and Filters Section -->
            <div class="card">
                 <div class="card-header">
                <h5>View Delivery Challan Details</h5>
       <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                            <!-- Form for Month and Year Selection -->
                            <form class="form-inline" method="POST" action="download_delivery_challan_monthly.php" style="display: flex; align-items: center; margin-right: 10px;">
                                <label style="margin-right: 10px;">Select Month and Year Data:</label>
                                <select class="form-control" id="month" name="month" style="margin-right: 10px; width: auto;">
                                    <?php
                                    $current_month = date("m");
                                    for ($month = 1; $month <= 12; $month++) {
                                        $selected = ($current_month == $month) ? 'selected' : '';
                                        echo "<option value=\"$month\" $selected>" . date('F', mktime(0, 0, 0, $month, 1)) . "</option>";
                                    }
                                    ?>
                                </select>
                                <select class="form-control" id="year" name="year" style="margin-right: 10px; width: auto;" required>
                                    <?php
                                    $current_year = date("Y");
                                    for ($i = $current_year; $i >= 2017; $i--) {
                                        echo "<option value=\"$i\">$i</option>";
                                    }
                                    ?>
                                </select>
                                <button type="submit" class="btn btn-success" name="download_month">
                                    <i class="fa fa-download"></i> Download
                                </button>
                            </form>
                            
                            <!-- Form for Date Range Selection -->
                            <form class="form-inline" method="POST" action="download_delivery_challan_range.php" style="display: flex; align-items: center; margin-right: 10px;">
                                <label style="margin-right: 10px;">Select Date Range:</label>
                                <input type="date" class="form-control" id="from_date" name="from_date" required style="margin-right: 10px; width: auto;" value="<?php echo date('Y-m-d', strtotime('-1 month')); ?>">
                                <input type="date" class="form-control" id="to_date" name="to_date" required style="margin-right: 10px; width: auto;" value="<?php echo date('Y-m-d'); ?>">
                                <button type="submit" class="btn btn-success" name="download_range">
                                    <i class="fa fa-download"></i> Download Range
                                </button>
                            </form>
                            <a href="delivery_challan.php" class="btn btn-info" style="color: #fff !important;">Create</a>
                        </div>
            </div>
            <div class="card-body table-border-style">
             
                <!-- Search and Filters -->
               
<div class="table-responsive">
                <!-- Table -->
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Customer</th>
                            <th scope="col">Delivery Challan</th>
                            <th scope="col">DC Amount</th>
                            <th scope="col">Dispatched / Vehicle</th>
                            <th scope="col">Created</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
        <?php if (!empty($challansTds)): ?>
            <?php foreach ($challansTds as $challansTds): ?>
                <tr>
                    <td><?= htmlspecialchars($challansTds['customer_name']) ?></td>
                    <td><a href="view-delivery-challan-action.php?id=<?php echo $challansTds['dcid']?>"><?= htmlspecialchars($challansTds['dc_code']) ?></a><br/><?= htmlspecialchars($challansTds['dc_date']) ?></td>
                    <td><?= number_format($challansTds['total_amount'], 2) ?><br/><a href="view-delivery-challan-action.php?id=<?php echo $challansTds['dcid']?>" style="color:green">View DC</a></td>
                   <td>
                    <?php
                        if ($challansTds['mode'] === 'Road') {
                            echo htmlspecialchars($challansTds['vehicle_number']);
                        } elseif ($challansTds['mode'] === 'Train') {
                            echo htmlspecialchars($challan['train_number']);
                        } elseif ($challansTds['mode'] === 'Air') {
                            echo htmlspecialchars($challan['flight_number']);
                        } elseif ($challansTds['mode'] === 'Sea') {
                            echo htmlspecialchars($challansTds['voyage_number']);
                        } else {
                            echo '-';
                        }
                    ?>
                </td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($challansTds['created_on']))) ?></td>
                    <td>
                        <a href="view-delivery-challan-action.php?id=<?php echo $challansTds['dcid']?>" class="btn btn-primary btn-sm">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No records found</td>
            </tr>
        <?php endif; ?>
    </tbody>
                </table>
</div>
             
            </div>
</div>
           
        </div>
    
<!-- </div> -->


<div id="top-error-message" class="error hidden" style="text-align: center; margin-bottom: 20px;"></div>


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
<script>
    $(document).ready(function() {
    $('#daterange').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    // Apply the selected date range to the input field
    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    // Clear the input field on cancel
    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // Open the date picker when the calendar icon is clicked
    $('#daterange-btn').click(function() {
        $('#daterange').focus();
    });
});

</script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

</body>
</html>