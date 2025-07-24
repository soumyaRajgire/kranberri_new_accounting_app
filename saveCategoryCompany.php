<?php
header('Content-Type: application/json');
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? ''; // Either 'category' or 'company'
    $name = trim(mysqli_real_escape_string($conn, $_POST['name'] ?? ''));

    if (empty($type) || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Type or name is empty.']);
        exit;
    }

    // Determine the table based on the type
    if ($type === 'category') {
        $sql = "INSERT INTO categories (category_name) VALUES (?)";
    } elseif ($type === 'company') {
        $sql = "INSERT INTO companies (company_name) VALUES (?)";
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid type specified.']);
        exit;
    }

    // Prepare the statement and bind parameters
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $name);

    // Execute the statement and handle success/failure
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'name' => $name]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving to database: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>