<?php
// fetch_raw_material_units.php

// Include database configuration
include("config.php");

$response = ['status' => 'error', 'message' => 'Something went wrong!'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected raw material per unit
    $raw_material_per_unit = isset($_POST['raw_material_per_unit']) ? mysqli_real_escape_string($conn, $_POST['raw_material_per_unit']) : '';

    if (empty($raw_material_per_unit)) {
        $response['message'] = 'Raw material per unit is required.';
        echo json_encode($response);
        exit();
    }

    // Query to fetch raw material units based on the selected raw material per unit
    $sql = "SELECT raw_material_units
            FROM inventory_master
            WHERE raw_material_per_unit = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $raw_material_per_unit);  // Bind the raw material per unit
    $stmt->execute();
    $stmt->bind_result($raw_material_units);

    $units = [];

    // Fetch all the raw material units for the selected raw material per unit
    while ($stmt->fetch()) {
        $units[] = $raw_material_units;
    }

    // If data is found for raw materials, send it as a response
    if (!empty($units)) {
        $response['status'] = 'success';
        $response['units'] = $units;  // Return fetched units
    } else {
        $response['message'] = 'No units found for the selected raw material per unit.';
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    $stmt->close();
    $conn->close();
}
?>
