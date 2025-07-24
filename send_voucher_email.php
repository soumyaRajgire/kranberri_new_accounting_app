<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sendVoucherMail'])) {
    $customerEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['voucher_subject'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['voucher_message'], FILTER_SANITIZE_STRING);
    $voucherFile = filter_var($_POST['pdf_file_path'], FILTER_SANITIZE_STRING);

    // Validate inputs
    if (!$customerEmail || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email address."); window.history.back();</script>';
        exit;
    }
    if (!$voucherFile || !file_exists($voucherFile)) {
        echo '<script>alert("Voucher file not found."); window.history.back();</script>';
        exit;
    }

    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'testmail@iiiqai.com'; // Your email
        $mail->Password = 'Gim]++Pdk$)jx7?'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Email settings
        $mail->setFrom('testmail@iiiqai.com', 'Gimbook');
        $mail->addAddress($customerEmail);
        $mail->addAttachment($voucherFile);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

        // Send email
        if ($mail->send()) {
            echo '<script>alert("Voucher sent to email successfully."); window.location.href = "manage-voucher.php";</script>';
        } else {
            echo '<script>alert("Failed to send email: ' . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8') . '"); window.history.back();</script>';
        }
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        echo '<script>alert("Failed to send email: ' . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8') . '"); window.history.back();</script>';
    }
} else {
    echo '<script>alert("Invalid request."); window.history.back();</script>';
}
?>
