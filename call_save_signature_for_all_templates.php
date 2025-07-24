<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once "phpqrcode/qrlib.php"; // Include QR code library

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

// Check if a business is selected
if (!isset($_SESSION['business_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['url'] = $_SERVER['REQUEST_URI'];
    $business_id = $_SESSION['business_id'];
    $branch_id = $_SESSION['branch_id'] ?? null;
    $GLOBALS['branch_id'] = $branch_id;
}

include("config.php");
   
if (!isset($_GET['inv_id'])) {
    
    die("Invoice ID not provided");
}

$inv_id = $_GET['inv_id'];

// Get signature details
$sql = "SELECT * FROM signatures WHERE inv_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $inv_id);
$stmt->execute();
$signature = $stmt->get_result()->fetch_assoc();

if (!$signature || !file_exists($signature['uploaded_file'])) {
    die("Signature not found");
}

// Get invoice details
$sql = "SELECT * FROM invoice WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $inv_id);
$stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc();

if (!$invoice) {
    die("Invoice not found");
}
else {
    // echo "<script>alert('Invoice found');</script>";
}

// Template files array with their specific signature positions
$templates = [
    [
        'file' => $invoice['invoice_file_template1'],
        'x' => 150,
        'y' => 250
    ],
    [
        'file' => $invoice['invoice_file_template2'],
        'x' => 160,
        'y' => 240
    ],
    [
        'file' => $invoice['invoice_file_template3'],
        'x' => 155,
        'y' => 245
    ],
    [
        'file' => $invoice['invoice_file_template4'],
        'x' => 145,
        'y' => 255
    ]
];

// Add at the top of the file after include("config.php"):
require_once('fpdf/fpdf.php');
require_once('vendor/setasign/fpdi/src/autoload.php');
use setasign\Fpdi\Fpdi;

try {
    $conn->begin_transaction();

    foreach ($templates as $key => $template) {
        if (!empty($template['file']) && file_exists($template['file'])) {
            // Create new PDF using FPDI instead of FPDF
            $pdf = new Fpdi();
            $pdf->AddPage();
            
            // Import existing PDF correctly
            $pdf->setSourceFile($template['file']);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0, null, null, true);

            // Add "For" text above signature
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Text($template['x'], $template['y'] - 10, "For");

            // Add signature
            $pdf->Image($signature['uploaded_file'], 
                       $template['x'], 
                       $template['y'] - 30,  // Moved up by 30 units
                       40,
                       20
            );

            // Add "Authorised Signatory" below signature
            $pdf->SetFont('Arial', '', 8);
            $pdf->Text($template['x'], 
                      $template['y'] - 5,  // Adjusted position
                      "Authorised Signatory");

            // Add signature details with adjusted positions
            $pdf->Text($template['x'], 
                      $template['y'], 
                      "Signed by: " . $signature['authorized_user']);
            $pdf->Text($template['x'], 
                      $template['y'] + 5, 
                      "Date: " . date('d-m-Y H:i:s'));

            // Create signed PDFs directory if it doesn't exist
            $signed_dir = 'invoice/signed/';
            if (!file_exists($signed_dir)) {
                mkdir($signed_dir, 0777, true);
            }

            // Save the new PDF in signed directory
            $base_name = basename($template['file']);
            $new_filename = $signed_dir . str_replace('.pdf', '_signed.pdf', $base_name);
            $pdf->Output('F', $new_filename);

            // Update database with new filename
            $column = 'invoice_file_template' . ($key + 1);
            $sql = "UPDATE invoice SET $column = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_filename, $inv_id);
            if($stmt->execute()) {
                $success = true;
            } else {
                $success = false;
                throw new Exception("Failed to update database");
            }
        }
    }

    if($success) {
        $conn->commit();
        echo "<script>alert('Signatures added successfully to all templates'); 
              window.location.href='view-invoice-action.php?inv_id=" . $inv_id . "';</script>";
    }

   
    

} catch (Exception $e) {
    $conn->rollback();
    echo "<script>alert('Error adding signatures: " . $e->getMessage() . "'); 
          window.history.back();</script>";
}
?>