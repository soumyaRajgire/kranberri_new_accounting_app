<?php
session_start();
include("config.php");

// Check if the user is logged in
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

// Get the ID of the deleted contact to restore
$deleted_contact_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : null;

if ($deleted_contact_id) {
    // Start a transaction to ensure data integrity
    mysqli_begin_transaction($conn);

    try {
        // Retrieve data from deleted_contacts
        $query = "SELECT * FROM deleted_contacts WHERE id = '$deleted_contact_id'";
        $result = mysqli_query($conn, $query);
        $deleted_data = mysqli_fetch_assoc($result);

        if (!$deleted_data) {
            throw new Exception("Deleted contact not found.");
        }
        $created_by = 'iiiQbets';

        // Insert data back into customer_master
        $query_insert_customer = "INSERT INTO customer_master (title, customerName, entityType, mobile, email, gstin, gst_reg_name, 
            business_name, display_name, phone_no, fax, account_no, account_name, bank_name, account_type, ifsc_code, branch_name, 
            pan, tan, tds_slab_rate, currency, terms_of_payment, reverse_charge, export_or_sez, contact_type, created_by, created_on, 
            updated_on, aadhaar, dob, citizenship, status)
            VALUES ('{$deleted_data['title']}', '{$deleted_data['customerName']}', '{$deleted_data['entityType']}', '{$deleted_data['mobile']}', 
            '{$deleted_data['email']}', '{$deleted_data['gstin']}', '{$deleted_data['gst_reg_name']}', '{$deleted_data['business_name']}', 
            '{$deleted_data['display_name']}', '{$deleted_data['phone_no']}', '{$deleted_data['fax']}', '{$deleted_data['account_no']}', 
            '{$deleted_data['account_name']}', '{$deleted_data['bank_name']}', '{$deleted_data['account_type']}', '{$deleted_data['ifsc_code']}', 
            '{$deleted_data['branch_name']}', '{$deleted_data['pan']}', '{$deleted_data['tan']}', '{$deleted_data['tds_slab_rate']}', 
            '{$deleted_data['currency']}', '{$deleted_data['terms_of_payment']}', '{$deleted_data['reverse_charge']}', 
            '{$deleted_data['export_or_sez']}', '{$deleted_data['contact_type']}', '$created_by', 
            '{$deleted_data['created_on']}', '{$deleted_data['updated_on']}', '{$deleted_data['aadhaar']}', '{$deleted_data['dob']}', 
            '{$deleted_data['citizenship']}', '{$deleted_data['status']}')";
        
        if (!mysqli_query($conn, $query_insert_customer)) {
            throw new Exception("Error restoring customer data: " . mysqli_error($conn));
        }

        // Insert data back into address_master
        $query_insert_address = "INSERT INTO address_master (s_address_line1, s_address_line2, s_city, s_Pincode, s_state, s_country, 
            s_branch, s_gstin, b_address_line1, b_address_line2, b_city, b_Pincode, b_state, b_country, b_branch, b_gstin, customer_master_id)
            VALUES ('{$deleted_data['s_address_line1']}', '{$deleted_data['s_address_line2']}', '{$deleted_data['s_city']}', 
            '{$deleted_data['s_Pincode']}', '{$deleted_data['s_state']}', '{$deleted_data['s_country']}', '{$deleted_data['s_branch']}', 
            '{$deleted_data['s_gstin']}', '{$deleted_data['b_address_line1']}', '{$deleted_data['b_address_line2']}', '{$deleted_data['b_city']}', 
            '{$deleted_data['b_Pincode']}', '{$deleted_data['b_state']}', '{$deleted_data['b_country']}', '{$deleted_data['b_branch']}', 
            '{$deleted_data['b_gstin']}', LAST_INSERT_ID())"; // Use LAST_INSERT_ID to link to the newly created customer_master record
        
        if (!mysqli_query($conn, $query_insert_address)) {
            throw new Exception("Error restoring address data: " . mysqli_error($conn));
        }

        // Delete the restored contact from deleted_contacts
        $query_delete_deleted = "DELETE FROM deleted_contacts WHERE id = '$deleted_contact_id'";
        if (!mysqli_query($conn, $query_delete_deleted)) {
            throw new Exception("Error deleting from deleted_contacts: " . mysqli_error($conn));
        }

        // Commit transaction
        mysqli_commit($conn);
        echo "<script>
            alert('Contact restored successfully.');
            window.location.href = 'deleted_contacts.php';
        </script>";

    } catch (Exception $e) {
        // Rollback on error
        mysqli_rollback($conn);
        $error_message = mysqli_real_escape_string($conn, $e->getMessage());
        echo "<script>
            alert('Error restoring contact: $error_message');
            window.location.href = 'deleted_contacts.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Invalid contact ID.');
        window.location.href = 'deleted_contacts.php';
    </script>";
}

// Close the connection
mysqli_close($conn);
?>
