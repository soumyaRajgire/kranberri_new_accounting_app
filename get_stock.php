<?php
include("config.php");

if (isset($_GET['id'])) {
    $itemId = $_GET['id'];
    $sql = "SELECT opening_stock FROM inventory_master WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['opening_stock' => $row['opening_stock']]);
    } else {
        echo json_encode(['opening_stock' => 0]); // Default if not found
    }

    $stmt->close();
}
?>
