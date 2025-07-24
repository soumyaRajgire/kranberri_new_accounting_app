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
        b.bill_code,
        b.customer_name,
        b.bill_date,
        b.due_date,
        b.total_amount,
        b.total_gst,
        b.total_cess,
        b.grand_total,
        b.due_amount,
        b.status,
        b.created_on
    FROM 
        bill_of_supply b
    WHERE 
        b.branch_id = ? " . getDateCondition('b.bill_date', null, null, $month, $year);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Bill No', 'Customer Name', 'Bill Date', 'Due Date', 'Total Amount', 'GST Amount', 'Cess Amount', 'Grand Total', 'Due Amount', 'Status', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['bill_code'],
            $row['customer_name'],
            $row['bill_date'],
            $row['due_date'],
            $row['total_amount'],
            $row['total_gst'],
            $row['total_cess'],
            $row['grand_total'],
            $row['due_amount'],
            $row['status'],
            $row['created_on']
        ];
    }

    $month_name = date('F', mktime(0, 0, 0, $month, 1));
    $filename = "bills_of_supply_{$month_name}_{$year}.csv";
    generateCSV($headers, $data, $filename);
}

header("Location: manage-billsupply.php");
exit();
?>
