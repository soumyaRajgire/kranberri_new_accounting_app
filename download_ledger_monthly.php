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
    l.voucher_id,
    l.account_name,
    l.transaction_date,
    l.amount,
    l.debit_credit,
    
    
    l.created_at
FROM 
    ledger l
    WHERE 
        l.branch_id = ? " . getDateCondition('l.transaction_date', null, null, $month, $year);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Voucher ID', 'Account Name', 'Transaction Date', 'Amount', 'Debit/Credit', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['voucher_id'],
            $row['account_name'],
            $row['transaction_date'],
            $row['amount'],
            $row['debit_credit'],
            
            $row['created_at']
        ];
    }

    $month_name = date('F', mktime(0, 0, 0, $month, 1));
    $filename = "ledger_{$month_name}_{$year}.csv";
    generateCSV($headers, $data, $filename);
}

header("Location: manage-ledger.php");
exit();
?>
