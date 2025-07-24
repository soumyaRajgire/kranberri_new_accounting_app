<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_id']; // Assuming you add an item_id field in the form
    $quantityToAdd = intval($_POST['quantity']);

    // Fetch current opening stock
    $sql = "SELECT opening_stock FROM inventory_master WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentStock = intval($row['opening_stock']);
        
        // Calculate new stock
        $newStock = $currentStock + $quantityToAdd;

        // Update the opening stock in the database
        $updateSql = "UPDATE inventory_master SET opening_stock = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ii", $newStock, $itemId);

        if ($updateStmt->execute()) {
            echo '<script>alert("Stock updated successfully"); window.location.href="manage-products.php?type=Sales%20Catalog";</script>';
        } else {
            echo "Error updating stock: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        echo "Item not found.";
    }

    $stmt->close();
}
?>
