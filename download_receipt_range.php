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
        recpt_id,
        customer_id,
        receipt_date,
        total_amount,
        paid_amount,
        payment_mode,
        
        created_at
    FROM 
        receipts
    WHERE 
        branch_id = ? " . getDateCondition('receipt_date', $from_date, $to_date);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    
    
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Receipt No', 'Customer Name', 'Receipt Date', 'Total Amount', 'Paid Amount', 'Payment Mode', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['receipt_no'],
            $row['customer_name'],
            $row['receipt_date'],
            $row['total_amount'],
            $row['paid_amount'],
            $row['payment_mode'],
            
            $row['created_at']
        ];
    }

    $filename = "receipts_" . $from_date . "_to_" . $to_date . ".csv";
    generateCSV($headers, $data, $filename);
}

header("Location: manage-receipt.php");
exit();
?>
