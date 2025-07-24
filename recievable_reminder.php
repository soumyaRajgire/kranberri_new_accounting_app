<?php

require 'config.php';
// SQL query to fetch data
$sql = "
SELECT 
    subquery.invoice_id,
    subquery.customer_id,
    subquery.invoice_code,
    subquery.customer_name,
    subquery.mobile,
    subquery.email,
    subquery.due_date, 
    subquery.remaining_due AS `Due_Amount`
FROM (
    SELECT 
        i.id AS invoice_id,
        i.invoice_code,
        i.customer_id,
        i.customer_name,
        cm.mobile,
        cm.email,
        i.due_date,
        i.grand_total - (
            COALESCE(r.total_paid, 0) + COALESCE(rc.total_reconciled, 0)
        ) AS remaining_due
    FROM 
        invoice i
    INNER JOIN 
        customer_master cm ON i.customer_id = cm.id -- Properly join the customer_master table
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(paid_amount) AS total_paid
        FROM 
            receipts
        GROUP BY 
            invoice_id
    ) r ON i.id = r.invoice_id
    LEFT JOIN (
        SELECT 
            invoice_id, 
            SUM(reconciled_amount) AS total_reconciled
        FROM 
            reconciliation
        GROUP BY 
            invoice_id
    ) rc ON i.id = rc.invoice_id
    WHERE 
        (i.status = 'pending' OR i.status = 'partial')
        AND (DATEDIFF(i.due_date, CURDATE()) = 3 OR DATEDIFF(i.due_date, CURDATE()) = 7)
    GROUP BY 
        i.id, i.customer_id, i.customer_name, cm.mobile, i.due_date, i.grand_total
) AS subquery
ORDER BY 
    `Due_Amount` DESC;
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // API endpoint
    $url = "https://iiiqbets.pythonanywhere.com/api/single-message-with-multiple-variable/";

    while ($row = $result->fetch_assoc()) {
        // Prepare the payload
        $payload = [
            "mobile_number" => $row['mobile'],
            "template_name" => "due_amount",
            "template_variable" => [
                $row['customer_name'], 
                $row['Due_Amount'], 
                $row['invoice_code'], 
                
                $row['due_date']
            ]
        ];
        //$row['invoice_id'],     
        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the API call
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            echo "Response for mobile " . $row['mobile'] . ": " . $response . "\n";
        }

        // Close the cURL session
        curl_close($ch);
    }
} else {
    echo "No results found.\n";
}

// Close the database connection
$conn->close();
?>
