<?php
session_start();
include("config.php");

// Check if the user is logged in
if(!isset($_SESSION['LOG_IN'])){
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if(!isset($_SESSION['business_id'])){
    header("Location:dashboard.php");
    exit();
} else {
 // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
        // Branch-specific code or logic here
    } 
}
// Get the contact ID and type from the URL
$contact_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;
$contact_type = isset($_GET['contact_type']) ? mysqli_real_escape_string($conn, $_GET['contact_type']) : null;

// Check if contact ID and type are valid
if ($contact_id && $contact_type) {
    // Start a transaction to ensure all actions happen together
    mysqli_begin_transaction($conn);

    try {
        // Retrieve the contact details from customer_master and address_master
        $query = "SELECT cm.*, am.id AS address_id, am.s_address_line1, am.s_address_line2, am.s_city, am.s_Pincode, 
                         am.s_state, am.s_country, am.s_branch, am.s_gstin, am.b_address_line1, am.b_address_line2, 
                         am.b_city, am.b_Pincode, am.b_state, am.b_country, am.b_branch, am.b_gstin
                  FROM customer_master AS cm
                  LEFT JOIN address_master AS am ON cm.id = am.customer_master_id
                  WHERE cm.id = '$contact_id' AND cm.contact_type = '$contact_type'";
        $result = mysqli_query($conn, $query);
        $contact_data = mysqli_fetch_assoc($result);

        if (!$contact_data) {
            throw new Exception("Contact not found for deletion.");
        }

        // Insert the contact data into deleted_contacts table
        $query_insert = "INSERT INTO deleted_contacts 
                        (original_customer_master_id, original_address_master_id, title, customerName, entityType, mobile, email, 
                         gstin, gst_reg_name, business_name, display_name, phone_no, fax, account_no, account_name, bank_name, 
                         account_type, ifsc_code, branch_name, pan, tan, tds_slab_rate, currency, terms_of_payment, reverse_charge, 
                         export_or_sez, contact_type, created_by, created_on, updated_on, aadhaar, dob, citizenship, status, 
                         s_address_line1, s_address_line2, s_city, s_Pincode, s_state, s_country, s_branch, s_gstin, 
                         b_address_line1, b_address_line2, b_city, b_Pincode, b_state, b_country, b_branch, b_gstin, deleted_on)
                        VALUES 
                        ('{$contact_data['id']}', '{$contact_data['address_id']}', '{$contact_data['title']}', '{$contact_data['customerName']}', 
                         '{$contact_data['entityType']}', '{$contact_data['mobile']}', '{$contact_data['email']}', '{$contact_data['gstin']}', 
                         '{$contact_data['gst_reg_name']}', '{$contact_data['business_name']}', '{$contact_data['display_name']}', 
                         '{$contact_data['phone_no']}', '{$contact_data['fax']}', '{$contact_data['account_no']}', '{$contact_data['account_name']}', 
                         '{$contact_data['bank_name']}', '{$contact_data['account_type']}', '{$contact_data['ifsc_code']}', 
                         '{$contact_data['branch_name']}', '{$contact_data['pan']}', '{$contact_data['tan']}', '{$contact_data['tds_slab_rate']}', 
                         '{$contact_data['currency']}', '{$contact_data['terms_of_payment']}', '{$contact_data['reverse_charge']}', 
                         '{$contact_data['export_or_sez']}', '{$contact_data['contact_type']}', '{$contact_data['created_by']}', 
                         '{$contact_data['created_on']}', '{$contact_data['updated_on']}', '{$contact_data['aadhaar']}', 
                         '{$contact_data['dob']}', '{$contact_data['citizenship']}', '{$contact_data['status']}', 
                         '{$contact_data['s_address_line1']}', '{$contact_data['s_address_line2']}', '{$contact_data['s_city']}', 
                         '{$contact_data['s_Pincode']}', '{$contact_data['s_state']}', '{$contact_data['s_country']}', 
                         '{$contact_data['s_branch']}', '{$contact_data['s_gstin']}', '{$contact_data['b_address_line1']}', 
                         '{$contact_data['b_address_line2']}', '{$contact_data['b_city']}', '{$contact_data['b_Pincode']}', 
                         '{$contact_data['b_state']}', '{$contact_data['b_country']}', '{$contact_data['b_branch']}', 
                         '{$contact_data['b_gstin']}', NOW())";
                         
        if (!mysqli_query($conn, $query_insert)) {
            throw new Exception("Error inserting into deleted_contacts: " . mysqli_error($conn));
        }

        // First, delete the address details from address_master
        $query1 = "DELETE FROM `address_master` WHERE `customer_master_id` = '$contact_id'";
        if (!mysqli_query($conn, $query1)) {
            throw new Exception("Error deleting address: " . mysqli_error($conn));
        }

        // Then, delete the contact from customer_master
        $query2 = "DELETE FROM `customer_master` WHERE `id` = '$contact_id' AND `contact_type` = '$contact_type'";
        if (!mysqli_query($conn, $query2)) {
            throw new Exception("Error deleting contact: " . mysqli_error($conn));
        }
$file_path = isset($file_path) ? $file_path : '';
        require_once 'includes/insert_audit_log.php';
                insertAuditLog($conn, "Deleted Other Contact", $file_path);

        // Commit the transaction
        mysqli_commit($conn);

        echo "<script>
            alert('Contact deleted successfully.');
            window.location.href = 'other_contacts.php';
        </script>";
    } catch (Exception $e) {
        // Rollback the transaction if any query fails
        mysqli_rollback($conn);
        $error_message = mysqli_real_escape_string($conn, $e->getMessage());
        echo "<script>
            alert('Error deleting record: $error_message');
            window.location.href = 'other_contacts.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Invalid contact ID.');
        window.location.href = 'other_contacts.php';
    </script>";
}

// Close the connection
mysqli_close($conn);
?>
