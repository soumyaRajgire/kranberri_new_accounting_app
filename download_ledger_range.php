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
        l.ledger_no,
        l.account_name,
        l.transaction_date,
        l.debit_amount,
        l.credit_amount,
        l.balance,
        l.created_on
    FROM 
        ledger l
    WHERE 
        l.branch_id = ? " . getDateCondition('l.transaction_date', $from_date, $to_date);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Ledger No', 'Account Name', 'Date', 'Debit Amount', 'Credit Amount', 'Balance', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['ledger_no'],
            $row['account_name'],
            $row['transaction_date'],
            $row['debit_amount'],
            $row['credit_amount'],
            $row['balance'],
            $row['created_on']
        ];
    }

    $filename = "ledger_" . $from_date . "_to_" . $to_date . ".csv";
    generateCSV($headers, $data, $filename);
}

header("Location: manage-ledger.php");
exit();
?>
