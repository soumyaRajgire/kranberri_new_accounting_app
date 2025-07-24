<?php
session_start();
include("config.php"); // Database connection file

// Check if the user is logged in
if (!isset($_SESSION['LOG_IN'])) {
    header("Location:login.php");
    exit();
}

// Check if the request is valid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['month'], $_POST['year'])) {
    $month = intval($_POST['month']);
    $year = intval($_POST['year']);

    // File name for the download
    $fileName = "Invoice_Report_{$month}_{$year}.csv";

    // Headers for download
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment;filename={$fileName}");

    // Open PHP output stream for writing CSV
    $output = fopen("php://output", "w");

    fputcsv($output, [
        'Customer Name',
        'Customer Email',
        'Invoice Code',
        'Total Amount',
        'Invoice Date',
        'Due Date',
        'Status',
        'Invoice File URL'
    ]);
    
    // Fetch and include the data
    $branch_id = $_SESSION['branch_id'];
    $query = "SELECT 
                    cm.customerName, 
                    cm.email AS customerEmail, 
                    q.invoice_code AS invoiceCode, 
                    q.total_amount AS totalAmount, 
                    q.invoice_date AS invoiceDate, 
                    q.due_date AS dueDate, 
                    q.status, 
                    q.invoice_file
              FROM 
                    invoice q 
              JOIN 
                    customer_master cm ON q.customer_id = cm.id 
              WHERE 
                    q.branch_id = '$branch_id' 
                    AND MONTH(q.invoice_date) = '$month' 
                    AND YEAR(q.invoice_date) = '$year' 
              ORDER BY 
                    q.id DESC";
    
    $result = mysqli_query($conn, $query);
    
    // Check if data exists
    if (mysqli_num_rows($result) > 0) {
        // Write each row to the CSV file
        while ($row = mysqli_fetch_assoc($result)) {
            $invoiceFileUrl = (!empty($row['invoice_file']))
                ? "https://localhost/gimbook2/" . $row['invoice_file']
                : "No file available";
    
            fputcsv($output, [
                $row['customerName'],
                $row['customerEmail'],
                $row['invoiceCode'],
                $row['totalAmount'],
                $row['invoiceDate'],
                $row['dueDate'],
                $row['status'],
                $invoiceFileUrl
            ]);
        }
    } else {
        // No data found message in the CSV file
        fputcsv($output, ['No records found for the selected month and year.']);
    }
    // Close output stream
    fclose($output);
    exit();
} else {
    // Redirect to the main page if the request is invalid
    header("Location:view_invoice.php");
    exit();
}
?>
