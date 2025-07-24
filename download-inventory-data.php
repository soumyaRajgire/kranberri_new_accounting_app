<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['download_range'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Query to fetch data from database
    $query = "SELECT * FROM inventory_master WHERE inventory_type = 'Purchased Items' 
              AND DATE(created_on) BETWEEN ? AND ?";

    // Prepare and execute query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $from_date, $to_date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Headers for the CSV
    $headers = ['Name', 'Category', 'Purchase Price', 'GST Rate', 'Net Price', 'HSN Code', 'Units', 'Can Be Sold'];

    // Collect the data from the result
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            $row['name'],
            $row['category'],
            $row['price'],
            $row['gst_rate'],
            $row['net_price'],
            $row['hsn_code'],
            $row['units'],
            $row['can_be_sold']
        ];
    }

    // Function to generate and force download CSV
    function generateCSV($headers, $data, $filename) {
        // Open PHP output stream
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $output = fopen('php://output', 'w');

        // Write the headers to the CSV
        fputcsv($output, $headers);

        // Write the data rows
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        // Close the output stream
        fclose($output);
        exit();
    }

    // Filename for the CSV file
    $filename = "purchase_items_{$from_date}_to_{$to_date}.csv";

    // Generate the CSV file
    generateCSV($headers, $data, $filename);
}
?>
