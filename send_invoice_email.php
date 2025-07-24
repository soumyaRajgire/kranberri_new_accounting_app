<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendMail'])) {
    // Validate and sanitize POST data
    $customerEmail = isset($_POST['customer_email1']) ? filter_var($_POST['customer_email1'], FILTER_SANITIZE_EMAIL) : '';
    $invoiceFile = isset($_POST['invoice_file']) ? filter_var($_POST['invoice_file'], FILTER_SANITIZE_STRING) : '';
    $subject = isset($_POST['subject']) ? filter_var($_POST['subject'], FILTER_SANITIZE_STRING) : 'Invoice';
    $message = isset($_POST['message']) ? filter_var($_POST['message'], FILTER_SANITIZE_STRING) : '';

    // Validate required fields
    if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid or missing customer email.");</script>';
        exit;
    }

    if (empty($invoiceFile)) {
        echo '<script>alert("Invoice file name is empty.");</script>';
        exit;
    }

    $filePath = __DIR__ . '/' . $invoiceFile;
    if (!file_exists($filePath)) {
        echo '<script>alert("Invoice file not found.");</script>';
        exit;
    }

    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email';
        $mail->SMTPAuth = true;
        $mail->Username = 'testmail@iiiqai.com'; // Replace with your SMTP username
        $mail->Password = 'Gim]++Pdk$)jx7?';     // Replace with your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Set email details
        $mail->setFrom('testmail@iiiqai.com', 'Gimbook');
        $mail->addAddress($customerEmail);
        $mail->addAttachment($filePath);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br($message);

        // Send the email
        $mail->send();
        echo '<script>alert("Message has been sent successfully!"); window.location.href = "view-invoices.php";</script>';
    } catch (Exception $e) {
        echo '<script>alert("Mailer Error: ' . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8') . '"); window.location.href = "view-invoices.php";</script>';
    }
} else {
    echo '<script>alert("Invalid request. Please try again.");</script>';
    exit;
}
?>
