<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve POST data
    $business_id = $_SESSION['business_id']; // Assuming business_id is stored in session
    $branch_name = $_POST['branch_name'];
    $email = $_POST['bemail']; // Ensure form input `name` attribute matches 'email'
    $gst = $_POST['gst'];
    $address_line1 = $_POST['branch_address_line1'];
    $address_line2 = $_POST['branch_address_line2'];
    $city = $_POST['city'];
    $pincode = $_POST['pincode'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $office_email = $_POST['office_email'];
    $phone_number = $_POST['phone_number1'];
    $status = $_POST['status'];
    $billing_scheme = $_POST['billing_scheme'];
    $additional_business_name = $_POST['additional_business_name'];
    $b_business_name = $_POST['b_business_name'];
    $branch_state_code = $_POST['branch_state_code'];
    $billing_scheme = $_POST['billing_scheme'];
    $nature_of_premises = $_POST['nature_of_premises'];
    $nature_of_business = $_POST['nature_of_business'];
    $taluka = $_POST['taluka'];


    // SQL Insert Query
    $sql = "INSERT INTO add_branch 
            (business_id, branch_name, alias_name,email, address_line1, address_line2, city, pincode, state, state_code, country, GST, billing_scheme,office_email, phone_number,nature_of_premises, nature_of_business, status) 
            VALUES ('$business_id', '$branch_name', '$additional_business_name', '$email', '$address_line1', '$address_line2', '$city', '$pincode', '$state','$branch_state_code', '$country','$gst','$billing_scheme','$office_email', '$phone_number','$nature_of_premises', '$nature_of_business','$status')";

    if (mysqli_query($conn, $sql)) {
        // Success Alert
        echo "<script>
            alert('Branch added successfully!');
            window.location.href = 'manage-business.php';
        </script>";
    } else {
        // Error Alert
        $error = mysqli_error($conn);
        echo "<script>
            alert('Error: $error');
            window.location.href = 'manage-business.php';
        </script>";
    }
    exit;
}
?>
