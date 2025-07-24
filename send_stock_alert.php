<?php
// Include database connection
include("config.php");

// Include PHPMailer files
require 'PHPMailer-master/src/PHPMailer.php';
        require 'PHPMailer-master/src/SMTP.php';
        require 'PHPMailer-master/src/Exception.php';
        
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php'; // If using Composer

// Fetch products with low stock
$sql = "
    SELECT `id`, `name`, `balance_stock`, `min_stockalert`
    FROM `inventory_master`
    WHERE `balance_stock` <= `min_stockalert`
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through each product and send an email notification
    while ($row = $result->fetch_assoc()) {
        // Prepare email details
        $productName = $row['name'];
        $balanceStock = $row['balance_stock'];
        $minStockAlert = $row['min_stockalert'];
      

        // Prepare email content
        $subject = "Low Stock Alert: $productName";
        $message = "Dear user,\n\nThe stock for the product '$productName' has reached the minimum alert level. Current stock: $balanceStock, Minimum alert stock: $minStockAlert.\n\nPlease take necessary action.\n\nBest Regards,\nSouthSutra Team";

        // Create PHPMailer instance
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();                                          // Set mailer to use SMTP
            $mail->Host = 'smtp.titan.email';                       // Set the SMTP server
            $mail->SMTPAuth = true;                                    // Enable SMTP authentication
            $mail->Username = 'infotest@iiiqai.com';             // SMTP username
            $mail->Password = '@34Rf4rd';                   // SMTP password
            $mail->SMTPSecure = 'ssl';       // Enable TLS encryption
            $mail->Port = 465;                                         // TCP port to connect to

            // Recipients
            $mail->setFrom('infotest@iiiqai.com', 'SouthSutra Team');
            $mail->addAddress('krishnavamshi927917@gmail.com');                                // Add recipient email address

             // Content
            $mail->isHTML(false);                                      // Set email format to plain text
            $mail->Subject = $subject;
            $mail->Body    = $message;

            // Send email
            $mail->send();
            $response['status'] = 'success';
            $response['message'] = 'Email sent successfully for product: ' . $productName;
            $response['productName'] = $productName; // Send product name
            $response['balanceStock'] = $balanceStock; // Send stock level
        } catch (Exception $e) {
            $response['message'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    }
} else {
    $response['message'] = "No products with low stock found.";
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>