<?php
session_start();
include_once("config.php");

if (isset($_GET['invoiceID'])) {
    $invoice_id = $_GET['invoiceID'];

    // Fetch products related to the invoice
    $productSql = "SELECT 
                       purchase_invoice_items.product AS product_name,
                       purchase_invoice_items.prod_desc,
                       purchase_invoice_items.qty,
                       purchase_invoice_items.price,
                       purchase_invoice_items.line_total AS total
                   FROM 
                       purchase_invoice_items
                   WHERE 
                       purchase_invoice_items.pinvoice_id = '$invoice_id'";
    $productResult = $conn->query($productSql);

    $products = [];
    if ($productResult && $productResult->num_rows > 0) {
        while ($productRow = $productResult->fetch_assoc()) {
            $products[] = $productRow;
        }
    }

    // Output the products in JSON format
    echo json_encode($products);
    exit();
}
?>
