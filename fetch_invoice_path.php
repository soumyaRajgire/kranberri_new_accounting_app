<?php
include("config.php");
session_start();

// Get the invoice_id from the request
$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;

// ✅ Fetch selected template from DB (not just session)
$template_query = "SELECT temp_name FROM invoice_temp WHERE status = 'active' LIMIT 1";
$template_result = $conn->query($template_query);
$selected_template = 'template1'; // Default template

if ($template_result && $row = $template_result->fetch_assoc()) {
    $selected_template = $row['temp_name'];
}

// ✅ Fetch invoice details
if ($invoice_id > 0) {
    $query = "SELECT invoice_code, invoice_file_template1, invoice_file_template2, invoice_file_template3, invoice_file_template4 FROM invoice WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        // ✅ Map templates to file paths
        $template_files = [
            'template1' => $row['invoice_file_template1'],
            'template2' => $row['invoice_file_template2'],
            'template3' => $row['invoice_file_template3'],
            'template4' => $row['invoice_file_template4']
        ];

        // ✅ Get the selected file path
        $selected_file = isset($template_files[$selected_template]) ? $template_files[$selected_template] : null;

        if ($selected_file) {
            echo json_encode([
                'status' => 'success',
                'message' => 'from file fetch_invoice_path - Invoice file URL  found for the selected template.',
                'file_url' => $selected_file
                
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'from file fetch_invoice_path - Invoice file not found for the selected template.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'from file fetch_invoice_path - Invoice not found.'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'from file fetch_invoice_path - Invalid invoice ID.'
    ]);
}

$conn->close();
?>
