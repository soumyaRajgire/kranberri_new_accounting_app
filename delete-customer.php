<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'includes/insert_audit_log.php';
     session_start();
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    mysqli_begin_transaction($conn);

    try {
        // Fetch data from customer_master where contact_type is 'Customer'
        $query_customer = "SELECT * FROM customer_master WHERE id = ? AND contact_type = 'Customer'";
        $stmt_customer = mysqli_prepare($conn, $query_customer);
        mysqli_stmt_bind_param($stmt_customer, "i", $id);
        mysqli_stmt_execute($stmt_customer);
        $result_customer = mysqli_stmt_get_result($stmt_customer);
        $customer_data = mysqli_fetch_assoc($result_customer);

        if (!$customer_data) {
            throw new Exception("No customer found with the provided ID or the contact type is not 'Customer'.");
        }

        // Fetch data from address_master related to this customer
        $query_address = "SELECT * FROM address_master WHERE customer_master_id = ?";
        $stmt_address = mysqli_prepare($conn, $query_address);
        mysqli_stmt_bind_param($stmt_address, "i", $id);
        mysqli_stmt_execute($stmt_address);
        $result_address = mysqli_stmt_get_result($stmt_address);
        $address_data = mysqli_fetch_assoc($result_address);

        // Building columns and values for insertion dynamically
        $columns = [];
        $values = [];
        $types = '';

        // Map columns to data from customer_master and address_master
        $data_map = [
            'original_customer_master_id' => ['value' => $customer_data['id'], 'type' => 'i'],
            'original_address_master_id' => ['value' => $address_data['id'] ?? null, 'type' => 'i'],
            'title' => ['value' => $customer_data['title'], 'type' => 's'],
            'customerName' => ['value' => $customer_data['customerName'], 'type' => 's'],
            'entityType' => ['value' => $customer_data['entityType'], 'type' => 's'],
            'mobile' => ['value' => $customer_data['mobile'], 'type' => 's'],
            'email' => ['value' => $customer_data['email'], 'type' => 's'],
            'gstin' => ['value' => $customer_data['gstin'], 'type' => 's'],
            'gst_reg_name' => ['value' => $customer_data['gst_reg_name'], 'type' => 's'],
            'business_name' => ['value' => $customer_data['business_name'], 'type' => 's'],
            'display_name' => ['value' => $customer_data['display_name'], 'type' => 's'],
            'phone_no' => ['value' => $customer_data['phone_no'], 'type' => 's'],
            'fax' => ['value' => $customer_data['fax'], 'type' => 's'],
            'account_no' => ['value' => $customer_data['account_no'], 'type' => 's'],
            'account_name' => ['value' => $customer_data['account_name'], 'type' => 's'],
            'bank_name' => ['value' => $customer_data['bank_name'], 'type' => 's'],
            'account_type' => ['value' => $customer_data['account_type'], 'type' => 's'],
            'ifsc_code' => ['value' => $customer_data['ifsc_code'], 'type' => 's'],
            'branch_name' => ['value' => $customer_data['branch_name'], 'type' => 's'],
            'pan' => ['value' => $customer_data['pan'], 'type' => 's'],
            'tan' => ['value' => $customer_data['tan'], 'type' => 's'],
            'tds_slab_rate' => ['value' => $customer_data['tds_slab_rate'], 'type' => 's'],
            'currency' => ['value' => $customer_data['currency'], 'type' => 's'],
            'terms_of_payment' => ['value' => $customer_data['terms_of_payment'], 'type' => 's'],
            'reverse_charge' => ['value' => $customer_data['reverse_charge'], 'type' => 's'],
            'export_or_sez' => ['value' => $customer_data['export_or_sez'], 'type' => 's'],
            'contact_type' => ['value' => $customer_data['contact_type'], 'type' => 's'],
            'created_by' => ['value' => $customer_data['created_by'], 'type' => 's'],
            'created_on' => ['value' => $customer_data['created_on'], 'type' => 's'],
            'updated_on' => ['value' => $customer_data['updated_on'], 'type' => 's'],
            'aadhaar' => ['value' => $customer_data['aadhaar'], 'type' => 's'],
            'dob' => ['value' => $customer_data['dob'], 'type' => 's'],
            'citizenship' => ['value' => $customer_data['citizenship'], 'type' => 's'],
            'status' => ['value' => $customer_data['status'], 'type' => 's'],
            's_address_line1' => ['value' => $address_data['s_address_line1'] ?? null, 'type' => 's'],
            's_address_line2' => ['value' => $address_data['s_address_line2'] ?? null, 'type' => 's'],
            's_city' => ['value' => $address_data['s_city'] ?? null, 'type' => 's'],
            's_Pincode' => ['value' => $address_data['s_Pincode'] ?? null, 'type' => 's'],
            's_state' => ['value' => $address_data['s_state'] ?? null, 'type' => 's'],
            's_country' => ['value' => $address_data['s_country'] ?? null, 'type' => 's'],
            'deleted_on' => ['value' => date("Y-m-d H:i:s"), 'type' => 's']
        ];

        // Populate columns, values, and types dynamically based on available data
        foreach ($data_map as $column => $data) {
            if (!is_null($data['value'])) {
                $columns[] = $column;
                $values[] = $data['value'];
                $types .= $data['type'];
            }
        }

        // Generate dynamic SQL
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $columns_list = implode(', ', $columns);
        $query_insert = "INSERT INTO deleted_contacts ($columns_list) VALUES ($placeholders)";
        
        $stmt_insert = mysqli_prepare($conn, $query_insert);
        mysqli_stmt_bind_param($stmt_insert, $types, ...$values);

        if (!mysqli_stmt_execute($stmt_insert)) {
            throw new Exception("Error inserting into deleted_contacts: " . mysqli_error($conn));
        }

        // Delete from address_master and customer_master
        $query_delete_address = "DELETE FROM address_master WHERE customer_master_id = ?";
        $stmt_delete_address = mysqli_prepare($conn, $query_delete_address);
        mysqli_stmt_bind_param($stmt_delete_address, "i", $id);
        if (!mysqli_stmt_execute($stmt_delete_address)) {
            throw new Exception("Error deleting from address_master: " . mysqli_error($conn));
        }

        $query_delete_customer = "DELETE FROM customer_master WHERE id = ?";
        $stmt_delete_customer = mysqli_prepare($conn, $query_delete_customer);
        mysqli_stmt_bind_param($stmt_delete_customer, "i", $id);
        if (!mysqli_stmt_execute($stmt_delete_customer)) {
            throw new Exception("Error deleting from customer_master: " . mysqli_error($conn));
        }
        $file_path = isset($file_path) ? $file_path : '';
        require_once 'includes/insert_audit_log.php';
        insertAuditLog($conn, "Deleted Customer", $file_path);

        mysqli_commit($conn);
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Customer deleted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'customers.php';
                });
            });
        </script>";
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request. No ID provided.";
}
?>
