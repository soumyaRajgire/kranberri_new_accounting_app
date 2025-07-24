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
        cn.cnote_code,
        cn.customer_name,
        cn.invoice_id,
        cn.cnote_date,
        cn.total_amount,
        cn.adjusted_amount,
        cn.total_gst_amount,
        cn.total_cess_amount,
        cn.status,
        cn.created_at
    FROM 
        credit_note cn
    WHERE 
        cn.branch_id = ? AND cn.is_deleted = 0 " . getDateCondition('cn.cnote_date', null, null, $month, $year);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Credit Note No', 'Customer Name', 'Invoice ID', 'Date', 'Total Amount', 'Adjusted Amount', 'GST Amount', 'Cess Amount', 'Status', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['cnote_code'],
            $row['customer_name'],
            $row['invoice_id'],
            $row['cnote_date'],
            $row['total_amount'],
            $row['adjusted_amount'],
            $row['total_gst_amount'],
            $row['total_cess_amount'],
            $row['status'],
            $row['created_at']
        ];
    }

    $month_name = date('F', mktime(0, 0, 0, $month, 1));
    $filename = "credit_notes_{$month_name}_{$year}.csv";
    generateCSV($headers, $data, $filename);
}

header("Location: manage-creditnote.php");
exit();
?>
