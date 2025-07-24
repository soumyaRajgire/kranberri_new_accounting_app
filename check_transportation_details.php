<?php
// Database connection
require 'config.php';

if (isset($_POST['invoice_id'])) {
    $inv_id = $_POST['invoice_id'];

    // Fetch transportation details for the invoice
    $query = "SELECT * FROM transportation_details WHERE invoice_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $inv_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Check for missing data fields (Vehicle Number, Distance, etc.)
        if (empty($row['Vehno']) || empty($row['Distance']) || empty($row['Transdocno'])) {
            echo 'missing'; // Respond with 'missing' if any field is empty
        } else {
            echo 'ok'; // Respond with 'ok' if all required details are present
        }
    } else {
        echo 'missing'; // Respond with 'missing' if no transportation details are found
    }

    // Close the statement
    $stmt->close();
} else {
    echo 'missing'; // Respond with 'missing' if invoice ID is not provided
}

?>
