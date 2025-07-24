<?php
session_start();
include("config.php");
include("includes/download_handler.php");

if (!isset($_SESSION['LOG_IN']) || !isset($_SESSION['business_id'])) {
    header("Location:login.php");
    exit();
}

$business_id = $_SESSION['business_id'];
$branch_id = $_SESSION['branch_id'] ?? null;

if (isset($_POST['month']) && isset($_POST['year'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    
    $query = "SELECT 
        dc.dc_code,
        dc.customer_name,
        dc.dc_date,
        dc.due_date,
        dc.total_amount,
        dc.total_gst,
        dc.total_cess,
        dc.grand_total,
        dc.created_on
    FROM 
        delivery_challan dc
    WHERE 
        dc.branch_id = ? " . getDateCondition('dc.dc_date', null, null, $month, $year);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['DC No', 'Customer Name', 'DC Date', 'Due Date', 'Total Amount', 'GST Amount', 'Cess Amount', 'Grand Total', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['dc_code'],
            $row['customer_name'],
            $row['dc_date'],
            $row['due_date'],
            $row['total_amount'],
            $row['total_gst'],
            $row['total_cess'],
            $row['grand_total'],
            $row['created_on']
        ];
    }

    $month_name = date('F', mktime(0, 0, 0, $month, 1));
    $filename = "delivery_challans_{$month_name}_{$year}.csv";
    generateCSV($headers, $data, $filename);
}

header("Location: manage_delivery_challan.php");
exit();
?>
