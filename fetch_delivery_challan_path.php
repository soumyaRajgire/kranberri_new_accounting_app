<?php
include("config.php");

header('Content-Type: application/json');
ini_set('display_errors', 0); // Suppress warnings and errors in JSON response

$dcid = isset($_GET['id']) ? intval($_GET['dcid']) : 0;

if ($dcid > 0) {
    $query = "SELECT dc_file FROM delivery_challan WHERE id = $dcid";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $file_url = $row['dc_file'];

        if (!empty($file_url) && file_exists($file_url) && filesize($file_url) > 0) {
            echo json_encode(['status' => 'success', 'file_url' => $file_url]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'The file does not exist or is empty.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Delivery challan not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid challan ID.']);
}

mysqli_close($conn);
?>