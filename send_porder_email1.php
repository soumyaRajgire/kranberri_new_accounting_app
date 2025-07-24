<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if (isset($_POST['sendMail'])) {
    // Sanitize and validate inputs
    $customerEmail = filter_var($_POST['customer_email'] ?? '', FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'] ?? 'Quotation Details', FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING);
    $quotationFile = $_POST['quotation_file'] ?? ''; // Extract the file name safely

    // Validate customer email
    if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid or missing customer email. Please enter a valid email address."); window.location.href = "view-purchase-order.php";</script>';
        exit;
    }

    // Validate file name
    if (empty($quotationFile)) {
        echo '<script>alert("No purchase order file specified. Please provide a valid file name."); window.location.href = "view-purchase-order.php";</script>';
        exit;
    }

    // Construct the secure file path (update path to match your directory structure)
    $filePath = __DIR__ . '/' . $quotationFile;

    // Check if the file exists in the correct directory
    if (!file_exists($filePath)) {
        echo '<script>alert("The specified purchase order file was not found in the folder."); window.location.href = "view-purchase-order.php";</script>';
        exit;
    }

    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'testmail@iiiqai.com'; // SMTP username
        $mail->Password = 'Gim]++Pdk$)jx7?'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Email settings
        $mail->setFrom('testmail@iiiqai.com', 'Gimbook'); // Sender's email and name
        $mail->addAddress($customerEmail); // Recipient's email
        $mail->addAttachment($filePath); // Attach the quotation file

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')); // Sanitize message for HTML

        // Send the email
        if ($mail->send()) {
            echo '<script>alert("Purchase order has been sent successfully! The customer will receive the details shortly."); window.location.href = "view-purchase-order.php";</script>';
        } else {
            echo '<script>alert("Failed to send the email. Please try again later."); window.location.href = "view-purchase-order.php";</script>';
        }
    } catch (Exception $e) {
        // Log error details (optional)
        error_log('Mailer Error: ' . $mail->ErrorInfo);

        // Display an error message with more details
        echo '<script>alert("Failed to send the email. Error: ' . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8') . '"); window.location.href = "view-purchase-order.php";</script>';
    }
}
?>


