<?php
include("config.php");

// ✅ Enable Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json"); // Ensure JSON Response

$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;

$response = [
    'status' => 'error',
    'message' => '❌ Invoice not found.',
    'file_url' => null,
    'signature_urls' => [] // ✅ Store multiple signatures here
];

if ($invoice_id > 0) {
    // ✅ Fetch Invoice File Path
    $invoice_query = "SELECT invoice_file FROM invoice WHERE id = $invoice_id";
    $invoice_result = mysqli_query($conn, $invoice_query);

    // ✅ Fetch Signature File Paths
    $signature_query = "SELECT uploaded_file FROM signatures WHERE inv_id = $invoice_id";
    $signature_result = mysqli_query($conn, $signature_query);

    if ($invoice_result && mysqli_num_rows($invoice_result) > 0) {
        $invoice_row = mysqli_fetch_assoc($invoice_result);
        $invoice_file = !empty($invoice_row['invoice_file']) ? "pdf/" . basename($invoice_row['invoice_file']) : null;

        if ($invoice_file && file_exists(__DIR__ . "/" . $invoice_file)) {
            $response['status'] = 'success';
            $response['file_url'] = $invoice_file;
        } else {
            $response['message'] = '❌ PDF file not found.';
        }

        // ✅ Fetch All Signatures
        while ($signature_row = mysqli_fetch_assoc($signature_result)) {
            $signature_file = !empty($signature_row['uploaded_file']) ? "pdf/" . basename($signature_row['uploaded_file']) : null;

            // ✅ DEBUGGING: Log signature paths
            error_log("🔍 Checking Signature Path: " . __DIR__ . "/" . $signature_file);

            if ($signature_file && file_exists(__DIR__ . "/" . $signature_file)) {
                $response['signature_urls'][] = $signature_file; // ✅ Store multiple signatures
            } else {
                error_log("⚠️ Signature file exists in DB but is missing: " . $signature_file);
            }
        }

        // ✅ If no signatures found, set message
        if (empty($response['signature_urls'])) {
            $response['message'] = '❌ No signatures found for this invoice.';
        }
    } else {
        $response['message'] = '❌ Invoice not found in the database.';
    }
}

// ✅ Return JSON Response
echo json_encode($response);

mysqli_close($conn);
?>
