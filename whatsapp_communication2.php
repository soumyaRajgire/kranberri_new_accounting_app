<?php
function whatsapp_communication($mobile_number, $customer_name, $purchaseDate, $pdfType, $destinationURL, $filename) {
    // Static values for template name and base URL
    $template_name = "varuable_5"; // Update the template name if it changes
    $company_name = "Civil Core Projects"; // Static company name
    $base_url = "https://paleturquoise-jellyfish-674855.hostingersite.com/gimbook4/"; // Static base URL

    // Construct the file URL
    $file_url = $base_url . $filename;

    $formatted_Purchase_date = date("d/m/Y", strtotime($purchaseDate));
    
    
    // Prepare the template variables
    $template_variables = [
        $customer_name, // Customer name
        $company_name,  // Company name
        $pdfType,       // Document type (e.g., Invoice, Receipt)
         $formatted_Purchase_date,  // Purchase date
        $file_url       // File URL
    ];

    // API URL for WhatsApp communication
    $api_url = "https://iiiqbets.pythonanywhere.com/api/single-message-with-multiple-variable/";

    // Prepare the payload
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

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        // Handle cURL errors
        echo "<script>alert('cURL Error: " . curl_error($ch) . "');</script>";
    } else {
        // Show success message
        echo "<script>
            alert('" . $pdfType . " created successfully, WhatsApp API Response: " . addslashes($response) . "');
            window.location = '" . $destinationURL . "';
        </script>";
    }

    // Close cURL
    curl_close($ch);
}
?>
