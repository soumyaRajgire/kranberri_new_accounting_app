<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if (isset($_POST['sendMail'])) {
    // Sanitize and validate inputs
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL); 
    $customerEmail = filter_var($_POST['customer_email'] ?? '', FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'] ?? 'Quotation Details', FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING);
    $quotationFile = $_POST['dc_file'] ?? ''; 

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid or missing email address. Please enter a valid email address."); window.location.href = "view-delivery-challan-action.php";</script>';
        exit;
    }

    // Validate file name
    if (empty($quotationFile)) {
        echo '<script>alert("No credit note file specified. Please provide a valid file name."); window.location.href = "view-delivery-challan-action.php";</script>';
        exit;
    }

    // Construct the secure file path (update path to match your directory structure)
    $filePath = __DIR__ . '/' . $quotationFile;

    // Check if the file exists
    if (!file_exists($filePath)) {
        echo '<script>alert("The specified delivery challan file was not found in the folder."); window.location.href = "view-delivery-challan-action.php";</script>';
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
        $mail->Body = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

        // Send the email
        if ($mail->send()) {
            echo '<script>alert("Delivery challan has been sent successfully!"); window.location.href = "view-delivery-challan-action.php";</script>';
        } else {
            echo '<script>alert("Failed to send the email. Please try again later."); window.location.href = "view-delivery-challan-action.php";</script>';
        }
    } catch (Exception $e) {
        echo '<script>alert("Failed to send the email. Error: ' . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8') . '"); window.location.href = "view-delivery-challan-action.php";</script>';
    }
}
?>
