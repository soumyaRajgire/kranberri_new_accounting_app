<?php
session_start();
include("config.php");

// ✅ Enable Error Reporting (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_type = $_POST['form_type'];
    $inv_id = isset($_POST['inv_id']) ? intval($_POST['inv_id']) : 0;

    if ($inv_id == 0) {
        die("❌ Error: Invalid Invoice ID.");
    }

    try {
        // ✅ Handle Signature Creation (Convert Text to Image)
        if ($form_type === "signature") {
            $signature_name = $_POST['signature_name'] ?? 'Signature';
            $font_style = $_POST['font_style'] ?? 'Arial';
            $font_weight = $_POST['font_weight'] ?? 'normal';
            $font_style_type = $_POST['font_style_type'] ?? 'normal';
            $authorized_user = $_POST['authorized_user'] ?? 'Authorized User';
            $remarks = $_POST['remarks'] ?? '';

            // ✅ Store Signatures in `pdf/` Folder
            $target_dir = "pdf/"; // 🔹 Change from `uploads/` to `pdf/`
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name = "signature_" . time() . ".png";
            $file_path = $target_dir . $file_name; // ✅ Store full path

            $img_width = 400;
            $img_height = 100;
            $image = imagecreatetruecolor($img_width, $img_height);

            if (!$image) {
                throw new Exception("❌ Error: Unable to create image.");
            }

            // ✅ Set Background to White
            $white = imagecolorallocate($image, 255, 255, 255);
            imagefilledrectangle($image, 0, 0, $img_width, $img_height, $white);

            // ✅ Set Text Color to Black
            $black = imagecolorallocate($image, 0, 0, 0);

            // ✅ Define Font Path
            $font_path = __DIR__ . "/fonts/";
            $font_file = $font_path . str_replace(' ', '', strtolower($font_style)) . ".ttf";

            if (!file_exists($font_file)) {
                $available_fonts = array_diff(scandir($font_path), array('..', '.'));
                throw new Exception("❌ Error: Font file '$font_file' not found! Available fonts: " . implode(", ", $available_fonts));
            }

            // ✅ Apply Signature Text to Image
            imagettftext($image, 30, 0, 50, 60, $black, $font_file, $signature_name);

            // ✅ Save Image
            if (!imagepng($image, $file_path)) {
                throw new Exception("❌ Error: Failed to save image.");
            }
            imagedestroy($image);

            // ✅ Store Full Path in Database
            $sql = "INSERT INTO signatures (inv_id, uploaded_file, authorized_user, remarks, created_at) 
                    VALUES (?, ?, ?, ?, NOW())";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("❌ Error: Database prepare failed - " . $conn->error);
            }

            $stmt->bind_param("isss", $inv_id, $file_path, $authorized_user, $remarks);

            if (!$stmt->execute()) {
                throw new Exception("❌ Error: Database insert failed - " . $stmt->error);
            }

            echo "✅ Signature saved as image successfully!";
            $stmt->close();
        }

        // ✅ Handle File Upload for Signature
        elseif ($form_type === "upload") {
            $authorized_user = $_POST['authorized_user'] ?? '';
            $remarks = $_POST['remarks'] ?? '';

            if (!empty($_FILES["uploaded_file"]["name"])) {
                $target_dir = "pdf/"; // 🔹 Change from `uploads/` to `pdf/`
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $file_name = time() . "_" . basename($_FILES["uploaded_file"]["name"]);
                $uploaded_file = $target_dir . $file_name; // ✅ Store full path

                if (!move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $uploaded_file)) {
                    throw new Exception("❌ Error: File upload failed!");
                }

                // ✅ Store Full Path in Database
                $sql = "INSERT INTO signatures (inv_id, uploaded_file, authorized_user, remarks, created_at) 
                        VALUES (?, ?, ?, ?, NOW())";

                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("❌ Error: Database prepare failed - " . $conn->error);
                }

                $stmt->bind_param("isss", $inv_id, $uploaded_file, $authorized_user, $remarks);

                if (!$stmt->execute()) {
                    throw new Exception("❌ Error: Database insert failed - " . $stmt->error);
                }

                echo "✅ Signature uploaded successfully!";
                $stmt->close();
            } else {
                throw new Exception("❌ Error: No file selected!");
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        error_log($e->getMessage(), 3, "error_log.txt"); // ✅ Log errors for debugging
    }
}
?>
