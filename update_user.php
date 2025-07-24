
<?php
session_start();
include("config.php"); // Include Database Connection

// Check if user is logged in
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit();
}

// Ensure business and branch IDs are set
if (!isset($_SESSION['business_id']) || !isset($_SESSION['branch_id'])) {
    header("Location: dashboard.php");
    exit();
}



// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branch_id = $_SESSION['branch_id']; // Assume branch_id is sent as hidden input for identification
    $business_id = $_SESSION['business_id']; // Assume business_id is also sent
   $gstin = isset($_POST['gstin']) ? trim($_POST['gstin']) : "";
$constitution = isset($_POST['constitution']) ? trim($_POST['constitution']) : "";
$alias_name = isset($_POST['alias_name']) ? trim($_POST['alias_name']) : "";
$phone_number = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : "";
$address_line1 = isset($_POST['address_line11']) ? trim($_POST['address_line11']) : "";
$address_line2 = isset($_POST['address_line12']) ? trim($_POST['address_line12']) : "";
$pincode = isset($_POST['pincode1']) ? trim($_POST['pincode1']) : "";
$city = isset($_POST['city1']) ? trim($_POST['city1']) : "";
$state = isset($_POST['state1']) ? trim($_POST['state1']) : "";
$state_code = isset($_POST['state_code1']) ? trim($_POST['state_code1']) : "";

    $eway_user = $_POST['eway_user'];
    $eway_password = $_POST['eway_password'];
    $gov_user = $_POST['gov_user'];
    $gov_password = $_POST['gov_password'];
    $einv_user = $_POST['einv_user'];
    $einv_password = $_POST['einv_password'];
    $bank_upi = $_POST['bank_upi'];
    $payee_name = $_POST['payee_name'];

    // Validation (Basic example, extend as needed)
    if (empty($branch_id) || empty($business_id)) {
        die("Branch ID and Business ID are required.");
    }

    // Prepare SQL for updating the branch
    $sql = "UPDATE add_branch 
            SET 
                GST = ?, 
                constitution = ?, 
                alias_name = ?, 
                phone_number = ?, 
                address_line1 = ?, 
                address_line2 = ?, 
                city = ?, 
                pincode = ?, 
                state = ?, 
                state_code = ?,
                eway_user = ?, 
                eway_password = ?, 
                gov_user = ?, 
                gov_password = ?, 
                einv_user = ?, 
                einv_password = ?, 
                bank_upi = ?, 
                payee_name = ?
            WHERE branch_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssssssi",
        $gstin,
        $constitution,
        $alias_name,
        $phone_number,
        $address_line1,
        $address_line2,
        $city,
        $pincode,
        $state,
        $state_code,
        $eway_user,
        $eway_password,
        $gov_user,
        $gov_password,
        $einv_user,
        $einv_password,
        $bank_upi,
        $payee_name,
        $branch_id
    );

    if ($stmt->execute()) {
        echo "Branch details updated successfully.";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>




