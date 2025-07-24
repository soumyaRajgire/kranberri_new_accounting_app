<?php
session_start();
// if(!isset($_SESSION['LOG_IN'])){
//    header("Location:login.php");
// }
// else
// {
// $_SESSION['url'] = $_SERVER['REQUEST_URI'];
// }
include("config.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if($_SERVER['REQUEST_METHOD'] === 'POST')
{ 
    
    $title= mysqli_real_escape_string($conn, $_POST['title']);
    $name= mysqli_real_escape_string($conn, $_POST['name']);
    $entity_type= mysqli_real_escape_string($conn, $_POST['entity_type']);
    $mobile_number= mysqli_real_escape_string($conn, $_POST['mobile_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $customer_gstin = mysqli_real_escape_string($conn, $_POST['customer_gstin']);
    $customer_registered_name = mysqli_real_escape_string($conn, $_POST['customer_registered_name']);
    $business_name = mysqli_real_escape_string($conn, $_POST['business_name']);
    $additional_business_name = mysqli_real_escape_string($conn, $_POST['additional_business_name']);

    $display_name = mysqli_real_escape_string($conn, $_POST['display_name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $fax = mysqli_real_escape_string($conn, $_POST['fax']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
    $account_name = mysqli_real_escape_string($conn, $_POST['account_name']);
    $bank_name = mysqli_real_escape_string($conn, $_POST['bank_name']);
    $ifsc_code= mysqli_real_escape_string($conn, $_POST['ifsc_code']);
    $account_type= mysqli_real_escape_string($conn, $_POST['account_type']);
    $branch_name= mysqli_real_escape_string($conn, $_POST['branch_name']);
    $pan= mysqli_real_escape_string($conn, $_POST['pan']);
    $tan= mysqli_real_escape_string($conn, $_POST['tan']);
    $tds_slab_rate= mysqli_real_escape_string($conn, $_POST['tds_slab_rate']) ?? null;
    $currency= mysqli_real_escape_string($conn, $_POST['currency']);
    $terms_of_payment= mysqli_real_escape_string($conn, $_POST['terms_of_payment']);
    $reverse_charge= mysqli_real_escape_string($conn, $_POST['reverse_charge']);
    $export_type= mysqli_real_escape_string($conn, $_POST['export_type']);
    $bill_address_line1= mysqli_real_escape_string($conn, $_POST['bill_address_line1']);
    $bill_address_line2= mysqli_real_escape_string($conn, $_POST['bill_address_line2']);
    $bill_city= mysqli_real_escape_string($conn, $_POST['bill_city']);
    $bill_pin_code= mysqli_real_escape_string($conn, $_POST['bill_pin_code']);
    $bill_state= mysqli_real_escape_string($conn, $_POST['bill_state']);
    $bill_country= mysqli_real_escape_string($conn, $_POST['bill_country']);
    $bill_branch_name= mysqli_real_escape_string($conn, $_POST['bill_branch_name']);
    $bill_gstin= mysqli_real_escape_string($conn, $_POST['bill_gstin']);

$business_id_feild = mysqli_real_escape_string($conn,$_POST['business_id_feild']);
$branch_id_feild = mysqli_real_escape_string($conn,$_POST['branch_id_feild']);
 $checkbox_name = mysqli_real_escape_string($conn,$_POST['checkbox_name']);

    if(isset($_POST['checkbox_name']))
    {
        $ship_address_line1= mysqli_real_escape_string($conn, $_POST['bill_address_line1']);
    $ship_address_line2 = mysqli_real_escape_string($conn, $_POST['bill_address_line2']);
    $ship_city = mysqli_real_escape_string($conn,$_POST['bill_city']);
    $ship_pin_code = mysqli_real_escape_string($conn, $_POST['bill_pin_code']);
    $ship_state = mysqli_real_escape_string($conn, $_POST['bill_state']);
    $ship_country = mysqli_real_escape_string($conn, $_POST['bill_country']);
    $ship_branch_name = mysqli_real_escape_string($conn, $_POST['bill_branch_name']);
    $ship_gstin = mysqli_real_escape_string($conn, $_POST['bill_gstin']);
        
    }
    else
    {
        $ship_address_line1= mysqli_real_escape_string($conn, $_POST['ship_address_line1']);
    $ship_address_line2 = mysqli_real_escape_string($conn, $_POST['ship_address_line2']);
    $ship_city = mysqli_real_escape_string($conn,$_POST['ship_city']);
    $ship_pin_code = mysqli_real_escape_string($conn, $_POST['ship_pin_code']);
    $ship_state = mysqli_real_escape_string($conn, $_POST['ship_state']);
    $ship_country = mysqli_real_escape_string($conn, $_POST['ship_country']);
    $ship_branch_name = mysqli_real_escape_string($conn, $_POST['ship_branch_name']);
    $ship_gstin = mysqli_real_escape_string($conn, $_POST['ship_gstin']);
    }

    
$created_by = $_SESSION['name'];
    

    $sqlCustomer = "INSERT INTO customer_master (business_id,branch_id,title, customerName, entityType, mobile, email, gstin, gst_reg_name, business_name, additional_business_name, display_name, phone_no, fax, account_no, account_name, bank_name, account_type,ifsc_code,branch_name, pan, tan, tds_slab_rate, currency, terms_of_payment, reverse_charge, export_or_sez,contact_type,created_by)
                VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmtCustomer = $conn->prepare($sqlCustomer);

// Specify the correct data types for each parameter in the bind_param call
$customerType = 'Customer'; // Create a variable for 'Customer'
$stmtCustomer->bind_param("iisssssssssssssssssssssssssss", $business_id_feild,$branch_id_feild, $title, $name, $entity_type, $mobile_number, $email, $customer_gstin, $customer_registered_name, $business_name, $additional_business_name, $display_name, $phone_number, $fax, $account_number, $account_name, $bank_name, $account_type, $ifsc_code, $branch_name, $pan, $tan, $tds_slab_rate, $currency, $terms_of_payment, $reverse_charge, $export_type, $customerType, $created_by);


if ($stmtCustomer->execute() === TRUE) {
 $customer_id = $stmtCustomer->insert_id;

$sqlAddress = "INSERT INTO address_master (s_address_line1, s_address_line2,s_city,s_Pincode,s_state,s_country,s_branch,s_gstin,b_address_line1,b_address_line2,b_city,b_Pincode,b_state,b_country,b_branch,b_gstin,customer_master_id )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmtAddress = $conn->prepare($sqlAddress);
$stmtAddress->bind_param("ssssssssssssssssi",$ship_address_line1,$ship_address_line2,$ship_city,$ship_pin_code,$ship_state,$ship_country,$ship_branch_name,$ship_gstin,$bill_address_line1, $bill_address_line2, $bill_city, $bill_pin_code, $bill_state, $bill_country, $bill_branch_name, $bill_gstin,$customer_id);
if ($stmtAddress->execute()) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        title: "Success!",
                        text: "Customer inserted successfully.",
                        icon: "success"
                    }).then(() => {
                        window.location.href = "customers.php";
                    });
                });
            </script>';
            exit();
        }
        
    }

    echo "Error: " . $stmtCustomer->error;
}
?>