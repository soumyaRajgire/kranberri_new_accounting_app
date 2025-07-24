<?php
include("config.php");
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);


$response = ['status' => 'error', 'message' => 'Invalid request'];

if (isset($_GET['invoiceID'])) {
    $invoiceId = $_GET['invoiceID'];

    // Fetch main invoice details
    $invoiceQuery = "
        SELECT 
            p.id AS invoice_id,
            p.invoice_code,
            p.total_amount,
            p.total_gst,
            p.total_cess,
            p.grand_total,
            c.id AS customer_id, 
            c.customerName, 
            c.email AS customer_email, 
            c.mobile AS customer_mobile, 
            c.gstin AS customer_gstin,
            a.b_address_line1, a.b_address_line2, a.b_city, a.b_pincode, a.b_state,
            a.s_address_line1, a.s_address_line2, a.s_city, a.s_pincode, a.s_state
        FROM pi_invoice p
        JOIN customer_master c ON p.customer_id = c.id
        LEFT JOIN address_master a ON a.customer_master_id = c.id
        WHERE p.id = ?";
    $stmt = $conn->prepare($invoiceQuery);
    $stmt->bind_param("i", $invoiceId);

    if ($stmt->execute()) {
        $invoiceResult = $stmt->get_result();
        $invoiceData = $invoiceResult->fetch_assoc();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch invoice data.']);
        exit();
    }
    $stmt->close();


$itemQuery = "SELECT i.id , i.productid, i.product, i.prod_desc, i.qty, i.price, i.discount, i.gst, i.cgst, i.sgst, i.igst, i.cess_rate, i.cess_amount, i.total,  IFNULL(SUM(ci.qty), 0) AS credited_qty, ci.cgst as ccgst ,ci.sgst as csgst,ci.igst as cigst
        FROM pi_invoice_items i
        LEFT JOIN debit_note_items ci ON i.productid = ci.productid AND ci.dnote_id IN (SELECT c.id FROM debit_note c WHERE purchase_invoice_id = i.invoice_id)
        WHERE i.invoice_id = ? 
        GROUP BY i.productid, i.product, i.prod_desc, i.qty, i.price, i.discount, i.gst, i.cgst, i.sgst, i.igst, i.cess_rate, i.cess_amount HAVING i.qty != IFNULL(SUM(ci.qty), 0);";

    $stmt = $conn->prepare($itemQuery);
    $stmt->bind_param("i", $invoiceId);

    if ($stmt->execute()) {
        $itemResult = $stmt->get_result();
        $items = [];

        while ($row = $itemResult->fetch_assoc()) {
            $items[] = $row;
        }
        if (empty($items)) {
        $response = ['status' => 'error', 'message' => 'No product to credit for this invoice.'];
        echo json_encode($response);
        exit();
    }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch item data.']);
        exit();
    }
    $stmt->close();

    // Fetch additional charges
    $additionalChargesQuery = "SELECT charge_type, charge_price 
        FROM pi_invoice_additional_charges 
        WHERE invoice_id = ?";
    $stmt = $conn->prepare($additionalChargesQuery);
    $stmt->bind_param("i", $invoiceId);

    if ($stmt->execute()) {
        $additionalChargesResult = $stmt->get_result();
        $additionalCharges = [];

        while ($row = $additionalChargesResult->fetch_assoc()) {
            $additionalCharges[] = $row;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch additional charges.']);
        exit();
    }
    $stmt->close();

    // Fetch transportation details
    $transportationQuery = "
        SELECT 
            mode, 
            freight_charges, 
            vehicle_number, 
            driver_name, 
            driver_contact, 
            estimated_arrival 
        FROM pi_transportation_details 
        WHERE p_invoice_id = ?";
    $stmt = $conn->prepare($transportationQuery);
    $stmt->bind_param("i", $invoiceId);

    if ($stmt->execute()) {
        $transportationResult = $stmt->get_result();
        $transportationDetails = $transportationResult->fetch_assoc();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch transportation details.']);
        exit();
    }
    $stmt->close();

    // Combine all data into the response
    $response = [
        'status' => 'success',
        'invoice' => $invoiceData,
        'items' => $items,
        'additional_charges' => $additionalCharges,
        'transportation_details' => $transportationDetails
    ];
} else {
    $response = ['status' => 'error', 'message' => 'Invoice ID not provided.'];
}

// Output JSON response
echo json_encode($response);
exit();
?>
