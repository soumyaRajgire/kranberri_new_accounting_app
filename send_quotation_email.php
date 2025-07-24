<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if (isset($_POST['sendMail'])) {
    // Sanitize and validate inputs
    $customerEmail = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'] ?? 'Quotation Details', FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING);
    $quotationFile = $_POST['quotation_file'] ?? '';

    // Validate customer email
    if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid or missing customer email. Please enter a valid email address.");</script>';
        exit;
    }

    // Validate file name
    if (empty($quotationFile)) {
        echo '<script>alert("No invoice file specified. Please provide a valid file name.");</script>';
        exit;
    }

    // Construct the secure file path
    $filePath = realpath(__DIR__ . '/' . $quotationFile);
    if ($filePath === false || strpos($filePath, realpath(__DIR__)) !== 0) {
        echo '<script>alert("Invalid file path.");</script>';
        exit;
    }

    // Check if the file exists
    if (!file_exists($filePath)) {
        echo '<script>alert("The specified quotation file was not found.");</script>';
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
        $mail->addAddress($customerEmail);
        $mail->addAttachment($filePath);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

        // Send the email
        $mail->send();
        echo '<script>alert("Quotation email has been sent successfully!"); window.location.href = "view-quotation.php";</script>';
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        echo '<script>alert("Failed to send the email. Error: ' . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8') . '"); window.location.href = "view-quotation.php";</script>';
    }
}
?>