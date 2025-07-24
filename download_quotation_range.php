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
        q.invoice_code,
        q.customer_name,
        q.invoice_date,
        q.total_amount,
        q.status,
        q.created_on
    FROM 
        quotation q
    WHERE 
        q.branch_id = ? " . getDateCondition('q.invoice_date', $from_date, $to_date);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Quotation No', 'Customer Name', 'Date', 'Total Amount', 'Status', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['invoice_code'],
            $row['customer_name'],
            $row['invoice_date'],
            $row['total_amount'],
            $row['status'],
            $row['created_on']
        ];
    }

    $filename = "quotations_" . $from_date . "_to_" . $to_date . ".csv";
    generateCSV($headers, $data, $filename);
}

header("Location: view-quotation.php");
exit();
?>
