<?php
session_start(); // Start the session
include("config.php");

// Check if branch_id and business_id are set in the session
if (!isset($_SESSION['branch_id']) || !isset($_SESSION['business_id'])) {
    echo "Branch ID or Business ID is not set. Please check your session.";
    exit;
}

$branch_id = $_SESSION['branch_id'];
$business_id = $_SESSION['business_id'];
$created_by = isset($_SESSION['name']) ? $_SESSION['name'] : 'Unknown';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $voucherNumber = mysqli_real_escape_string($conn, $_POST["voucherNumber"]);
    $voucherDate = mysqli_real_escape_string($conn, $_POST["voucherDate"]);
    $paymentMode = mysqli_real_escape_string($conn, $_POST["paymentMode"]);
    $customer_name = mysqli_real_escape_string($conn, $_POST["customer_name_choice"]);
    $amount = mysqli_real_escape_string($conn, $_POST["amount"]);
    $notes = mysqli_real_escape_string($conn, $_POST["notes"]);
    $collected_by = mysqli_real_escape_string($conn, $_POST["collected_by"]);
    $bank_name = mysqli_real_escape_string($conn, $_POST["bank_name"]);
    $trans_no = mysqli_real_escape_string($conn, $_POST["trans_no"]);
    $cheque_no = mysqli_real_escape_string($conn, $_POST["cheque_no"]);
    $dd_no = mysqli_real_escape_string($conn, $_POST["dd_no"]);
    $card_last_no = mysqli_real_escape_string($conn, $_POST["card_last_no"]);
    $transaction_date = mysqli_real_escape_string($conn, $_POST["transaction_date"]);
    $pinvoice_code = mysqli_real_escape_string($conn, $_POST["pinvoice_code"]);

    // Insert into the current branch first
    $sql = "INSERT INTO vouchers 
        (`voucherNumber`, `voucherDate`, `paymentMode`, `amount`, `customer_name`, `notes`, 
        `collected_by`, `bank_name`, `trans_no`, `cheque_no`, `dd_no`, `card_last_no`, 
        `transaction_date`, `pinvoice_code`, `branch_id`, `status`, `created_by`) 
        VALUES 
        ('$voucherNumber', '$voucherDate', '$paymentMode', '$amount', '$customer_name', '$notes', 
        '$collected_by', '$bank_name', '$trans_no', '$cheque_no', '$dd_no', '$card_last_no', 
        '$transaction_date', '$pinvoice_code', '$branch_id', 'pending', '$created_by')";

    if ($conn->query($sql) === TRUE) {
        // Fetch all branches for the business from the `add_branch` table
        $branch_query = "SELECT branch_id FROM add_branch WHERE business_id = '$business_id'";
        $branch_result = $conn->query($branch_query);

        if ($branch_result->num_rows > 0) {
            while ($branch_row = $branch_result->fetch_assoc()) {
                $current_branch_id = $branch_row['branch_id'];

                // Skip the branch already saved
                if ($current_branch_id == $branch_id) {
                    continue;
                }

                // Insert into other branches
                $sql_other_branch = "INSERT INTO vouchers 
                    (`voucherNumber`, `voucherDate`, `paymentMode`, `amount`, `customer_name`, `notes`, 
                    `collected_by`, `bank_name`, `trans_no`, `cheque_no`, `dd_no`, `card_last_no`, 
                    `transaction_date`, `pinvoice_code`, `branch_id`, `status`, `created_by`) 
                    VALUES 
                    ('$voucherNumber', '$voucherDate', '$paymentMode', '$amount', '$customer_name', '$notes', 
                    '$collected_by', '$bank_name', '$trans_no', '$cheque_no', '$dd_no', '$card_last_no', 
                    '$transaction_date', '$pinvoice_code', '$current_branch_id', 'pending', '$created_by')";

                if (!$conn->query($sql_other_branch)) {
                    error_log("Error inserting voucher for branch $current_branch_id: " . $conn->error);
                }
            }
        }
        ?>
        <script>
            alert("Voucher created successfully!");
            window.location = "purchase_invoices.php?voucherCard";
        </script>
        <?php
    } else {
        ?>
        <script>
            alert("Error creating voucher: <?php echo $conn->error; ?>");
            window.location = "create_voucher.php";
        </script>
        <?php
    }

    $conn->close();
}
?>
