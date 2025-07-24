<?php
include("config.php");

if (isset($_GET['invoice_id'])) {
    $invoice_id = mysqli_real_escape_string($conn, $_GET['invoice_id']);
    
    // Fetch customer data and purchase invoice details
    $invoiceQuery = "SELECT p.*, c.customerName, c.mobile, c.email, c.gstin, a.b_address_line1, a.b_address_line2, a.b_city, a.b_state, a.b_Pincode
                     FROM purchase_invoice AS p
                     JOIN customer_master AS c ON p.customer_id = c.id
                     JOIN address_master AS a ON a.customer_master_id = c.id
                     WHERE p.id = '$invoice_id'";
    $invoiceResult = mysqli_query($conn, $invoiceQuery);
    $invoiceData = mysqli_fetch_assoc($invoiceResult);

    // Prepare customer information HTML
    $customerInfo = "
        <div class='col-md-4 border-left border-bottom border-dark p-3'>
            <h6>Supplier Info</h6>
            <p><strong>Name:</strong> {$invoiceData['customerName']}</p>
            <p><strong>Email:</strong> {$invoiceData['email']}</p>
            <p><strong>GSTIN:</strong> {$invoiceData['gstin']}</p>
        </div>
        <div class='col-md-4 border-left border-bottom border-dark p-3'>
            <h6>Billing Address</h6>
            <p>{$invoiceData['b_address_line1']}</p>
            <p>{$invoiceData['b_address_line2']}</p>
            <p>{$invoiceData['b_city']}, {$invoiceData['b_state']}, {$invoiceData['b_Pincode']}</p>
        </div>
        <div class='col-md-4 border-left border-bottom border-dark p-3'>
            <h6>Shipping Address</h6>
            <p>{$invoiceData['b_address_line1']}</p>
            <p>{$invoiceData['b_address_line2']}</p>
            <p>{$invoiceData['b_city']}, {$invoiceData['b_state']}, {$invoiceData['b_Pincode']}</p>
        </div>
    ";

    // Fetch purchase invoice items
    $itemsQuery = "SELECT * FROM purchase_invoice_items WHERE pinvoice_id = '$invoice_id'";
    $itemsResult = mysqli_query($conn, $itemsQuery);
    $items = [];
    while ($itemRow = mysqli_fetch_assoc($itemsResult)) {
        $items[] = [
            'product' => $itemRow['product'],
            'prod_desc' => $itemRow['prod_desc'],
            'qty' => $itemRow['qty'],
            'price' => $itemRow['price'],
            'total' => $itemRow['total']
        ];
    }

    // Prepare response
    $response = [
        'customerInfo' => $customerInfo,
        'items' => $items,
        'sub_total' => $invoiceData['total_amount'],
        'grand_total' => $invoiceData['grand_total']
    ];

    echo json_encode($response);
}
?>
