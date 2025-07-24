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
        q.invoice_code as quotation_no,
        q.customer_name,
        q.invoice_date as quotation_date,
        q.due_date,
        q.total_amount,
        q.total_gst,
        q.grand_total,
        q.status,
        q.created_on
    FROM 
        quotation q
    WHERE 
        q.branch_id = ? " . getDateCondition('q.invoice_date', null, null, $month, $year);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Quotation No', 'Customer Name', 'Date', 'Due Date', 'Total Amount', 'GST', 'Grand Total', 'Status', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['quotation_no'],
            $row['customer_name'],
            $row['quotation_date'],
            $row['due_date'],
            $row['total_amount'],
            $row['total_gst'],
            $row['grand_total'],
            $row['status'],
            $row['created_on']
        ];
    }

    $month_name = date('F', mktime(0, 0, 0, $month, 1));
    $filename = "quotations_{$month_name}_{$year}.csv";
    generateCSV($headers, $data, $filename);
}

header("Location: view-quotation.php");
exit();
?>
