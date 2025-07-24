<?php
// fetch_raw_material_per_unit.php

// Include database configuration
include("config.php");

$response = ['status' => 'error', 'message' => 'Something went wrong!'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Query to fetch raw material per unit for Purchased Items
    $sql = "SELECT DISTINCT raw_material_per_unit
            FROM inventory_master";  // Fetch unique raw material per unit

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($raw_material_per_unit);

    $rawMaterialPerUnit = [];

    // Fetch all raw material per unit options
    while ($stmt->fetch()) {
        $rawMaterialPerUnit[] = $raw_material_per_unit;
    }

    // If data is found for raw material per unit, send it as a response
    if (!empty($rawMaterialPerUnit)) {
        $response['status'] = 'success';
        $response['rawMaterialPerUnit'] = $rawMaterialPerUnit;
    } else {
        $response['message'] = 'No raw material per unit found.';
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);

    $stmt->close();
    $conn->close();
}
?>
