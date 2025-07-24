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
        pi.invoice_code,
        pi.customer_name,
        pi.invoice_date,
        pi.due_date,
        pi.total_amount,
        pi.total_gst,
        pi.total_cess,
        pi.grand_total,
        pi.status,
        pi.created_on
    FROM 
        pi_invoice pi
    WHERE 
        pi.branch_id = ? " . getDateCondition('pi.invoice_date', null, null, $month, $year);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Invoice Code', 'Customer Name', 'Invoice Date', 'Due Date', 'Total Amount', 'GST Amount', 'Cess Amount', 'Grand Total', 'Status', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['invoice_code'],
            $row['customer_name'],
            $row['invoice_date'],
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
    $filename = "purchase_invoices_{$month_name}_{$year}.csv";
    generateCSV($headers, $data, $filename);
}

header("Location: view-purchase-invoices.php");
exit();
?>
