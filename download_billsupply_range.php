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
        bs.bill_code,
        bs.customer_name,
        bs.bill_date,
        bs.grand_total,
        bs.created_on
    FROM 
        bill_of_supply bs
    WHERE 
        bs.branch_id = ? " . getDateCondition('bs.bill_date', $from_date, $to_date);

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $headers = ['Bill No', 'Customer Name', 'Date', 'Total Amount', 'Created On'];

    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['bill_code'],
            $row['customer_name'],
            $row['bill_date'],
            $row['grand_total'],
            $row['created_on']
        ];
    }

    $filename = "bill_of_supply_" . $from_date . "_to_" . $to_date . ".csv";
    generateCSV($headers, $data, $filename);
}

header("Location: manage-billsupply.php");
exit();
?>
