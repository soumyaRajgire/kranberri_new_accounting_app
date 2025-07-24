<?php
include("config.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request'];

if (isset($_GET['invoiceID'])) {
    $invoiceId = $_GET['invoiceID'];

    // Fetch customer details including address
    $customerQuery = "
        SELECT 
            c.id, c.customerName, c.email, c.mobile, c.gstin,
            a.b_address_line1, a.b_address_line2, a.b_city, a.b_pincode, a.b_state,
            a.s_address_line1, a.s_address_line2, a.s_city, a.s_pincode, a.s_state
        FROM customer_master c
        JOIN pi_invoice p ON p.customer_id = c.id
        LEFT JOIN address_master a ON a.customer_master_id = c.id
        WHERE p.id = ?";
    $stmt = $conn->prepare($customerQuery);
    $stmt->bind_param("i", $invoiceId);

    if ($stmt->execute()) {
        $customerResult = $stmt->get_result();

        if ($customerResult->num_rows > 0) {
            $customer = $customerResult->fetch_assoc();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Customer not found for the given invoice ID.']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch customer data.']);
        exit();
    }
    $stmt->close();

    // Fetch product details
    $productQuery = "
        SELECT 
            pi_invoice_items.product AS product_name,
            pi_invoice_items.prod_desc,
            pi_invoice_items.qty,
            pi_invoice_items.price,
            pi_invoice_items.line_total AS total
        FROM 
            pi_invoice_items
        WHERE 
            pi_invoice_items.invoice_id = ?";
    $stmt = $conn->prepare($productQuery);
    $stmt->bind_param("i", $invoiceId);

    if ($stmt->execute()) {
        $productResult = $stmt->get_result();
        $products = [];

        while ($row = $productResult->fetch_assoc()) {
            $products[] = $row;
        }

        $response = [
            'status' => 'success',
            'customer' => $customer,
            'products' => $products,
        ];
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch product data.']);
        exit();
    }
    $stmt->close();
} else {
    $response = ['status' => 'error', 'message' => 'Invoice ID not provided.'];
}

// Output JSON response
echo json_encode($response);
exit();
?>
