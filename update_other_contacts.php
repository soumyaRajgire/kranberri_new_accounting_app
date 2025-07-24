<?php
session_start();
include("config.php");

// Check if the user is logged in
if (!isset($_SESSION['LOG_IN'])) {
    header("Location: login.php");
    exit;
}

// Retrieve and sanitize form data
$contact_id = mysqli_real_escape_string($conn, $_POST['contact_id']);
$contact_type = mysqli_real_escape_string($conn, $_POST['contact_type']);
$title = mysqli_real_escape_string($conn, $_POST['title']);
$name = mysqli_real_escape_string($conn, $_POST['name']);
$citizenship = mysqli_real_escape_string($conn, $_POST['citizenship']);
$mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$pan = mysqli_real_escape_string($conn, $_POST['pan']);
$aadhaar = mysqli_real_escape_string($conn, $_POST['aadhaar']);
$dob = mysqli_real_escape_string($conn, $_POST['dob']);
$designation = mysqli_real_escape_string($conn, $_POST['designation']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$gstin = mysqli_real_escape_string($conn, $_POST['gstin']);
$status = mysqli_real_escape_string($conn, $_POST['status']);
$address1 = mysqli_real_escape_string($conn, $_POST['address1']);
$address2 = mysqli_real_escape_string($conn, $_POST['address2']);
$city = mysqli_real_escape_string($conn, $_POST['city']);
$state = mysqli_real_escape_string($conn, isset($_POST['state_dropdown']) ? $_POST['state_dropdown'] : $_POST['state_input']);
$pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
$country = mysqli_real_escape_string($conn, $_POST['country']);

// Update customer_master table
$query1 = "UPDATE `customer_master` SET
    `title` = '$title',
    `customerName` = '$name',
    `citizenship` = '$citizenship',
    `mobile` = '$mobile',
    `email` = '$email',
    `pan` = '$pan',
    `aadhaar` = '$aadhaar',
    `dob` = '$dob',
    `entityType` = '$designation',
    `phone_no` = '$phone',
    `gstin` = '$gstin',
    `status` = '$status',
    `updated_on` = NOW()
    WHERE `id` = '$contact_id' AND `contact_type` = '$contact_type'";

// Execute the update for customer_master
if (mysqli_query($conn, $query1)) {
    // Update address_master table
    $query2 = "UPDATE `address_master` SET
        `s_address_line1` = '$address1',
        `s_address_line2` = '$address2',
        `s_city` = '$city',
        `s_state` = '$state',
        `s_Pincode` = '$pincode',
        `s_country` = '$country'
        WHERE `customer_master_id` = '$contact_id'";

    // Execute the update for address_master
    if (mysqli_query($conn, $query2)) {
        echo "<script>
            alert('Contact updated successfully.');
            window.location.href = 'other_contact-details.php?id=$contact_id';
        </script>";
    } else {
        $error_message = mysqli_real_escape_string($conn, mysqli_error($conn));
        echo "<script>
            alert('Error updating address record: $error_message');
            window.location.href = 'other_contact-details.php?id=$contact_id';
        </script>";
    }
} else {
    $error_message = mysqli_real_escape_string($conn, mysqli_error($conn));
    echo "<script>
        alert('Error updating contact record: $error_message');
        window.location.href = 'other_contact-details.php?id=$contact_id';
    </script>";
}

// Close the connection
mysqli_close($conn);
?>
