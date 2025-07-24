<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if (isset($_POST['sendMail'])) {
    // Sanitize and validate inputs
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    $invoiceFile = $_POST['invoice_file'] ?? '';

    // Validate email address
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email address. Please provide a valid email."); history.back();</script>';
        exit;
    }

    // Validate the invoice file
    if (empty($invoiceFile)) {
        echo '<script>alert("No invoice file specified."); history.back();</script>';
        exit;
    }

    // File path
    $filePath = __DIR__ . '/' . $invoiceFile;
    if (!file_exists($filePath)) {
        echo '<script>alert("The specified invoice file does not exist."); history.back();</script>';
        exit;
    }

    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email';
        $mail->SMTPAuth = true;
        $mail->Username = 'testmail@iiiqai.com';
        $mail->Password = 'Gim]++Pdk$)jx7?';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Email settings
        $mail->setFrom('testmail@iiiqai.com', 'Gimbook');
        $mail->addAddress($email);
        $mail->addAttachment($filePath);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br($message);

        // Send the email
        $mail->send();
        echo '<script>alert("Email sent successfully."); window.location.href = "view-purchase-invoices.php";</script>';
    } catch (Exception $e) {
        echo '<script>alert("Error sending email: ' . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8') . '"); history.back();</script>';
    }
}
?>
