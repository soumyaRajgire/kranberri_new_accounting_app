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

if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    
    $query = "SELECT 
        po.invoice_code as po_no,
        po.customer_name,
        po.invoice_date as po_date,
        po.due_date,
        po.grand_total,
        po.status,
        po.created_on
    FROM 
        purchase_orders po
    WHERE 
        po.branch_id = ? " . getDateCondition('po.invoice_date', $from_date, $to_date);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['PO No', 'Customer Name', 'Date', 'Due Date', 'Grand Total', 'Status', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['po_no'],
            $row['customer_name'],
            $row['po_date'],
            $row['due_date'],
            $row['grand_total'],
            $row['status'],
            $row['created_on']
        ];
    }

    $filename = "purchase_orders_" . $from_date . "_to_" . $to_date . ".csv";
    generateCSV($headers, $data, $filename);
}

header("Location: view-purchase-order.php");
exit();
?>
