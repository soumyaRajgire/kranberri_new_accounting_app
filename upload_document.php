<?php
// Include the database connection file
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $documentName = trim($_POST['documentName']);
    $targetDir = "uploads/"; // The directory where files will be saved

    // Ensure the uploads directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true); // Create with limited permissions
    }

    // Generate a unique filename to avoid overwriting existing files
    $originalFileName = basename($_FILES["documentFile"]["name"]);
    $fileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
    $uniqueFileName = md5(time() . $originalFileName) . '.' . $fileType;
    $targetFile = $targetDir . $uniqueFileName;

    $uploadOk = 1;
    $validFileTypes = ['pdf', 'doc', 'docx', 'txt']; // Define the allowed file types

    // Check if file is a valid document format
    if (in_array($fileType, $validFileTypes)) {
        // Check file size (optional - example limit: 5MB)
        if ($_FILES["documentFile"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Attempt to upload file if checks passed
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["documentFile"]["tmp_name"], $targetFile)) {
                // Prepare to save the file information to the database
                $stmt = $conn->prepare("INSERT INTO documents (document_name, document_path) VALUES (?, ?)");
                $stmt->bind_param("ss", $documentName, $targetFile);
                
                if ($stmt->execute()) {
                    // Redirect back to the document page
                    header("Location: documents.php?success=1"); // Use a query parameter for success
                    exit();
                } else {
                    echo "Error saving the document to the database: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "Sorry, only PDF, DOC, DOCX, and TXT files are allowed.";
    }
}

// Optionally close the database connection if not needed further
$conn->close();
?>
