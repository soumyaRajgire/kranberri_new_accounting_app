<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inv_id = isset($_POST['inv_id']) ? intval($_POST['inv_id']) : 0;
    $form_type = $_POST['form_type'];
    $authorized_user = $_POST['authorized_user'] ?? '';
    $remarks = $_POST['remarks'] ?? '';

    try {
        if ($form_type === "signature") {
            // Handle text signature
            $signature_name = $_POST['signature_name'];
            $font_style = $_POST['font_style'];
            $font_weight = $_POST['font_weight'];
            $font_style_type = $_POST['font_style_type'];

            // Create signature image
            $target_dir = "pdf/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name = "signature_" . time() . ".png";
            $file_path = $target_dir . $file_name;

            $img_width = 400;
            $img_height = 100;
            $image = imagecreatetruecolor($img_width, $img_height);
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            imagefilledrectangle($image, 0, 0, $img_width, $img_height, $white);

            $font_path = __DIR__ . "/fonts/";
            $font_file = $font_path . str_replace(' ', '', $font_style) . ".ttf";
            imagettftext($image, 30, 0, 50, 60, $black, $font_file, $signature_name);
            imagepng($image, $file_path);
            imagedestroy($image);

        } elseif ($form_type === "upload") {
            // Handle file upload
            if (!empty($_FILES["uploaded_file"]["name"])) {
                $target_dir = "pdf/";
                $file_name = time() . "_" . basename($_FILES["uploaded_file"]["name"]);
                $file_path = $target_dir . $file_name;
                
                if (!move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $file_path)) {
                    throw new Exception("Failed to upload file");
                }
            }
        }

        // Save to database
        $sql = "INSERT INTO signatures (inv_id, uploaded_file, authorized_user, remarks, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $inv_id, $file_path, $authorized_user, $remarks);
        
        if ($stmt->execute()) {
            // Redirect to template creation
            header("Location: call_save_signature_for_all_templates.php?inv_id=" . $inv_id);
            exit();
        } else {
            throw new Exception("Database error");
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
