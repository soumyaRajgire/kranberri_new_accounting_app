
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


error_reporting(E_ALL);
session_start(); 
if(!isset($_SESSION['name']) && $_SESSION['ROLE']!='1')
{
  // Check if the user is logged in
if(!isset($_SESSION['LOG_IN'])){
    header("Location:login.php");
    exit();
}

// Check if a business is selected
if(!isset($_SESSION['business_id'])){
    header("Location:dashboard.php");
    exit();
} else {
 // Set up variables for selected business and branch
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    // Check if a specific branch is selected
    if (isset($_SESSION['branch_id'])) {
        $branch_id = $_SESSION['branch_id'];
        // Branch-specific code or logic here
    } 
}
include("config.php");

if(isset($_POST['submit']))
{
 
  include("config.php");
  // include("fpdf/fpdf.php");
 $qid = mysqli_real_escape_string($conn, $_POST['qid']);
    $qcode = mysqli_real_escape_string($conn, $_POST['qcode']);
    $to_address = mysqli_real_escape_string($conn, $_POST['to_address']);
    // $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    // $mail_body = mysqli_real_escape_string($conn, $_POST['mail_body']);
    $quotation = isset($_FILES["attachment"]) ? $_FILES["attachment"]["name"] : null;
// $attachment = chunk_split(base64_encode($quotation));

    // ... Other validations and checks

require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// Create a new PHPMailer instance
$mail = new PHPMailer;

// SMTP Configuration
$mail->isSMTP();
$mail->Host = 'smtp.titan.email';
$mail->Port = 465;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Username = 'bhagath.koduri@iiiqbets.com';
$mail->Password = 'Bhagath@123$';

// Email content
$mail->setFrom('bhagath.koduri@iiiqbets.com', 'iiiQbets');
// $mail->addAddress('soumyacn16@gmail.com', 'Dr Spine Admin');
$mail->addAddress($to_address, 'iiiQbets');

$mail->Subject = 'Estimation from iiiQbets';
$mail->isHTML(true); // Set email format to HTML

$mail->Body = '<table width="100%" style="background-color:#dadada;border-collapse:collapse;border-spacing:0;border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td align="center">
<table width="682" style="border-collapse:collapse;border-spacing:0">

<tbody><tr class="m_-1958935385513098443header">
<td bgcolor="#eeeeee"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="12">&nbsp;</td>
</tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left;border-bottom:3px solid #2f94d7" height="18">&nbsp;</td>
</tr>
</tbody></table></td>
</tr>


<tr><td bgcolor="#ffffff"> 

<table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td width="20" style="font-size:0;line-height:0">&nbsp;</td>
<td width="640" style="font-size:0;line-height:0">

<table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="15">&nbsp;</td>
</tr>
<tr>
<td style="background-color:#f8f8f8;border:1px solid #ebebeb"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="15">&nbsp;</td>
</tr>
<tr>
<td style="margin:0;color:#1e4a7b;font-size:20px;line-height:24px;font-family:Arial,Helvetica,sans-serif;font-style:normal;font-weight:normal;text-align:center">
Greetings from iiiQbets!!!!</td>
</tr><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="5">&nbsp;</td>
</tr></tbody></table></td></tr></tbody></table>

<table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
</tr>
<tr>
<td style="vertical-align:top;margin:0;padding:0;font-size:16px;color:#231f20;line-height:24px;font-family:Arial,Helvetica,sans-serif;font-weight:normal;text-align:left">Hello,
</td></tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
</tr>
<tr>
<td style="margin:0;padding:0;font-size:16px;color:#231f20;line-height:24px;text-align:center;font-family:Arial,Helvetica,sans-serif;font-weight:normal">

<div style="text-align:left"></div><div style="text-align:left"><span style="background-color:transparent">
Please find the attached Estimation. If you have any queries please feel free to contact.</span>

</div>
</td>
</tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
</tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="20">&nbsp;</td>
</tr>
<tr>
<td style="margin:0;padding:0;font-size:16px;color:#231f20;line-height:21px;font-family:Arial,Helvetica,sans-serif;font-weight:normal">Regards,<br><span class="il">iiiQbets</span> Team</td>
</tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="10">&nbsp;</td>
</tr>
<tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:left" height="10">&nbsp;</td>
</tr>
</tbody></table>

</td>
<td width="20" style="font-size:0;line-height:0">&nbsp;</td>
</tr>
</tbody></table></td></tr>


<tr>
<td bgcolor="#eeeeee"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td width="35">&nbsp;</td>
<td width="557"><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td><table width="100%" border="0" style="border-collapse:collapse;border-spacing:0">
<tbody><tr>
<td style="line-height:0;font-size:0;vertical-align:top;padding:0px;text-align:center" height="25">&nbsp;</td>
</tr>
</tbody></table></td>
</tr>


</tbody></table></td>
<td width="35">&nbsp;</td>
</tr>
</tbody></table></td>
</tr>

</tbody></table></td>
</tr>
</tbody></table>';

// Attachment
$filename = 'mailupload/';
$uploadFile = $filename . basename($_FILES['attachment']['name']);

if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadFile)) {
    File uploaded successfully, now attach it to the email
    $mail->addAttachment($uploadFile, $quotation,'base64', 'application/pdf'); // Change 'Quotation.pdf' to the desired attachment name
} else {
    echo 'File upload failed.';
}
//     require 'PHPMailer/src/PHPMailer.php';
//     require 'PHPMailer/src/SMTP.php';
//     require 'PHPMailer/src/Exception.php';

//     // Create a new PHPMailer instance
//     $mail = new PHPMailer();

//  $mail->isSMTP();
// $mail->Host = 'smtp.titan.email';
// $mail->Port = 465;
// $mail->SMTPAuth = true;
// $mail->SMTPSecure = 'ssl';
// $mail->Username = 'bhagath.koduri@iiiqbets.com';
// $mail->Password = 'Bhagath@123$';

// // Email content
// $mail->setFrom('bhagath.koduri@iiiqbets.com', 'iiiQbets Admin');
// $mail->addAddress('soumyacn16@gmail.com', 'Dr Spine Admin');
// $mail->addAddress($to_address, 'iiiQbets Admin');

// $mail->Subject = $subject;
// $mail->isHTML(true); // Set email format to HTML

// $mail->Body = nl2br($mail_body);;

//     // Attachment
    // if ($quotation != null) {
        // $filename = $_FILES["attachment"]["name"];
        // $mail->addAttachment($_FILES["attachment"]["tmp_name"], $filename); // Add attachment
    // }

    if ($mail->send()) {
       ?>
        <script>
             window.location = "view-quotation.php";
             alert("Successfully Created Quotation");
         </script> 
         <?php
     } else {
         echo '<script>alert("Email sending failed. Error: ' . $mail->ErrorInfo . '")</script>';
         echo "<script type='text/javascript'> document.location ='mail-quotation.php?id=$qid&qcode=$qcode' </script>";
     }
}
 ?>