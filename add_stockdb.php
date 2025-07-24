<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_id']; // Product ID from the form
    $quantity = intval($_POST['quantity']);
    $operation = $_POST['operation']; // "add" or "deduct"
    $remark = isset($_POST['remark']) ? $_POST['remark'] : null;
    $created_by = 1; // Replace with logged-in user ID

    // Fetch current stock values
    $sql = "SELECT Stock_in, stock_out, balance_stock FROM inventory_master WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentStockIn = intval($row['Stock_in']);
        $currentStockOut = intval($row['stock_out']);
        $currentBalanceStock = intval($row['balance_stock']);

        // Start transaction
        $conn->begin_transaction();

        try {
            if ($operation === 'add') {
                $newStockIn = $currentStockIn + $quantity;
                $newBalanceStock = $currentBalanceStock + $quantity; // Increase balance stock
                $addAndDeduct = 'add';

                $updateSql = "UPDATE inventory_master SET Stock_in = ?, balance_stock = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("iii", $newStockIn, $newBalanceStock, $itemId);

            } elseif ($operation === 'deduct') {
                if ($currentBalanceStock >= $quantity) {
                    $newStockOut = $currentStockOut + $quantity;
                    $newBalanceStock = $currentBalanceStock - $quantity; // Reduce balance stock
                    $addAndDeduct = 'deduct';

                    $updateSql = "UPDATE inventory_master SET stock_out = ?, balance_stock = ? WHERE id = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("iii", $newStockOut, $newBalanceStock, $itemId);

                } else {
                    echo '<script>alert("Error: Insufficient stock!"); window.location.href="manage-products.php?type=Sales%20Catalog";</script>';
                    exit;
                }
            } else {
                echo '<script>alert("Invalid operation!");</script>';
                exit;
            }

            // Execute the update statement
            $updateStmt->execute();
            $updateStmt->close();

            // Insert into `stock_master`
            $insertSql = "INSERT INTO stock_master (product_id, quantity, add_and_deduct, remark, date, created_by, created_on) 
                          VALUES (?, ?, ?, ?, NOW(), ?, NOW())";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("iissi", $itemId, $quantity, $addAndDeduct, $remark, $created_by);
            $insertStmt->execute();
            $insertStmt->close();

            // Commit transaction
            $conn->commit();

            echo '<script>alert("Stock updated successfully"); window.location.href="manage-products.php?type=Sales%20Catalog";</script>';
        } catch (Exception $e) {
            // Rollback in case of an error
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Item not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
