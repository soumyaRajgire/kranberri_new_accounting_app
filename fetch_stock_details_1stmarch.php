<?php
include("config.php");

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']); // Sanitize input

    // Fetch stock details
    $query = "SELECT name, opening_stock,Stock_in, stock_out,balance_stock
              FROM inventory_master WHERE id = $productId";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<h5>Product Name: " . htmlspecialchars($row['name']) . "</h5>";
        echo "<p><strong>Opening Stock:</strong> " . htmlspecialchars($row['opening_stock']) . "</p>";
          echo "<p><strong>Stock In:</strong> " . htmlspecialchars($row['Stock_in']) . "</p>";
        echo "<p><strong>Stock Out:</strong> " . htmlspecialchars($row['stock_out']) . "</p>";
        echo "<p><strong>Balance Stock:</strong> " . htmlspecialchars($row['balance_stock']) . "</p>";
    } else {
        echo "<p>No stock details found for this product.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>
