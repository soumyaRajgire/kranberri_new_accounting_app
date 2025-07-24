<?php
// Class representing a Quotation
class Quotation {
    public $quotationNumber;
    public $customerName;
    public $items;
    public $totalAmount;

    public function __construct($quotationNumber, $customerName, $items, $totalAmount) {
        $this->quotationNumber = $quotationNumber;
        $this->customerName = $customerName;
        $this->items = $items;
        $this->totalAmount = $totalAmount;
    }

    public function printQuotation() {
        echo "Quotation Number: " . $this->quotationNumber . "<br>";
        echo "Customer Name: " . $this->customerName . "<br>";
        echo "Items:<br>";
        foreach ($this->items as $item) {
            echo "- " . $item['name'] . " | Quantity: " . $item['quantity'] . " | Price: " . $item['price'] . "<br>";
        }
        echo "Total Amount: " . $this->totalAmount . "<br><br>";
    }
}

// Function to convert Quotation into Sales Invoice
function convertToInvoice(Quotation $quotation) {
    // Assuming some calculations are done for taxes and discounts here
    $totalAmount = $quotation->totalAmount * 1.1; // Adding 10% tax for simplicity
    $invoiceNumber = "INV-" . $quotation->quotationNumber;
    return new Quotation($invoiceNumber, $quotation->customerName, $quotation->items, $totalAmount);
}

// Sample data for quotation
$quotationNumber = "Q123";
$customerName = "John Doe";
$items = [
    ['name' => 'Product A', 'quantity' => 2, 'price' => 100],
    ['name' => 'Product B', 'quantity' => 1, 'price' => 50],
    ['name' => 'Service C', 'quantity' => 5, 'price' => 25],
];
$totalAmount = 400;

// Create a quotation
$quotation = new Quotation($quotationNumber, $customerName, $items, $totalAmount);

// Print the quotation
echo "Quotation Details:<br>";
$quotation->printQuotation();

// Convert the quotation into a sales invoice
$invoice = convertToInvoice($quotation);

// Print the sales invoice
echo "Sales Invoice Details:<br>";
$invoice->printQuotation();
?>