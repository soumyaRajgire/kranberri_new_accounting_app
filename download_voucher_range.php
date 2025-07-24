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
        v.voucher_id,
        v.customer_id,
        v.voucher_date,
        v.total_amount,
        
        v.created_at
    FROM 
        voucher v
    WHERE 
        v.branch_id = ? " . getDateCondition('v.voucher_date', $from_date, $to_date);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Voucher No', 'Customer Name', 'Date', 'Amount', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['voucher_id'],
            $row['customer_id'],
            $row['voucher_date'],
            $row['total_amount'],
            
            $row['created_at']
        ];
    }

    $filename = "vouchers_" . $from_date . "_to_" . $to_date . ".csv";
    generateCSV($headers, $data, $filename);
}

header("Location: manage-voucher.php");
exit();
?>
