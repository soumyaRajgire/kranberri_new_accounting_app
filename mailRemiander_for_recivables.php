<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include("config.php");

$mail = new PHPMailer(true);

try {
    // PHPMailer Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.titan.email'; 
    $mail->SMTPAuth = true;
    $mail->Username = 'testmail@iiiqai.com'; 
    $mail->Password = 'Gim]++Pdk$)jx7?'; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('testmail@iiiqai.com', 'Gimbook'); 

    // Query to find payments due in 3 or 7 days
    $query = "SELECT 
        subquery.invoice_id,
        subquery.customer_id,
        subquery.customer_name,
        subquery.mobile,
        subquery.email,
        subquery.due_date,
        subquery.remaining_due AS Due_Amount
    FROM (
        SELECT 
            i.id AS invoice_id,
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
            customer_master cm ON i.customer_id = cm.id
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
            i.id, i.customer_id, i.customer_name, cm.mobile, cm.email, i.due_date, i.grand_total
    ) AS subquery
    ORDER BY 
        Due_Amount DESC;";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Format due_date as DD-MM-YYYY
            $due_date = DateTime::createFromFormat('Y-m-d', $row['due_date'])->format('d-m-Y');
            
            // Fetch email addresses and split into an array
            $emailArray = explode(',', $row['email']);
            
            foreach ($emailArray as $singleEmail) {
                $singleEmail = trim($singleEmail); // Trim whitespace from the email address

                if (filter_var($singleEmail, FILTER_VALIDATE_EMAIL)) { // Validate email address
                    try {
                        $mail->clearAddresses(); // Clear previous recipient addresses
                        $mail->addAddress($singleEmail); // Add recipient email

                        $mail->isHTML(true);
                        $mail->Subject = "Payment Reminder for Invoice #" . $row['invoice_id'];
                        $mail->Body = "Dear " . $row['customer_name'] . ",<br><br>" .
                                      "This is a gentle reminder regarding the due amount of Rs. " . $row['Due_Amount'] . 
                                      " for Invoice ID " . $row['invoice_id'] . ". Please make the payment by " . 
                                      $due_date . " to avoid inconvenience.<br><br>" .
                                      "Thank you,<br>Gim Books";

                        // Send the email
                        if ($mail->send()) {
                            echo "Reminder sent to " . $singleEmail . "<br>";
                        } else {
                            echo "Failed to send reminder to " . $singleEmail . ": " . $mail->ErrorInfo . "<br>";
                        }
                    } catch (Exception $e) {
                        echo "Failed to send reminder to " . $singleEmail . ": " . $mail->ErrorInfo . "<br>";
                    }
                } else {
                    echo "Invalid email format: " . $singleEmail . "<br>";
                }
            }
        }
    } else {
        echo "No reminders to send today.<br>";
    }
} catch (Exception $e) {
    echo "Mailer Error: " . $e->getMessage();
}

// Close database connection
$conn->close();
?>
