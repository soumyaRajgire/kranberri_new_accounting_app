<?php
function sendInvoiceWhatsAppMessage($conn, $customer_id, $template_name, $template_variables, $redirect_url ) {
    // Fetch customer details
    $customer_result = mysqli_query($conn, "SELECT * FROM customer_master WHERE id = $customer_id");

    if (!$customer_result) {
        echo "<script>alert('Failed to fetch customer details.');</script>";
        return false;
    }

    $customerRow = mysqli_fetch_assoc($customer_result);
    if (!$customerRow) {
        echo "<script>alert('No customer found with the given ID.');</script>";
        return false;
    }

    $mobile_number = $customerRow['mobile'];

    // Prepare API payload
    $api_url = "https://iiiqbets.pythonanywhere.com/api/single-message-with-multiple-variable/";
    $payload = [
        "mobile_number" => $mobile_number,
        "template_name" => $template_name,
        "template_variable" => $template_variables
    ];

    // Initialize cURL
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    // Execute the cURL request and handle response
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "<script>alert('cURL Error: " . curl_error($ch) . "');</script>";
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    // Display success message and redirect
    echo "<script>
        alert('Quotation created successfully. WhatsApp API Response: " . addslashes($response) . "');
        window.location = '$redirect_url';
    </script>";

    return true;
}
?>
