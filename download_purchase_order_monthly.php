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
        po.invoice_code as po_no,
        po.customer_name,
        po.invoice_date as po_date,
        po.due_date,
        po.total_amount,
        po.total_gst,
        po.total_cess,
        po.grand_total,
        po.status,
        po.created_on
    FROM 
        purchase_orders po
    WHERE 
        po.branch_id = ? " . getDateCondition('po.invoice_date', null, null, $month, $year);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['PO No', 'Customer Name', 'PO Date', 'Due Date', 'Total Amount', 'GST Amount', 'Cess Amount', 'Grand Total', 'Status', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['po_no'],
            $row['customer_name'],
            $row['po_date'],
            $row['due_date'],
            $row['total_amount'],
            $row['total_gst'],
            $row['total_cess'],
            $row['grand_total'],
            $row['status'],
            $row['created_on']
        ];
    }

    $month_name = date('F', mktime(0, 0, 0, $month, 1));
    $filename = "purchase_orders_{$month_name}_{$year}.csv";
    generateCSV($headers, $data, $filename);
}

header("Location: view-purchase-order.php");
exit();
?>
