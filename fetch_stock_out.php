<?php
include("config.php");

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $sql = "SELECT add_and_deduct, reference_no,remark, quantity, DATE_FORMAT(date, '%d/%m/%Y') as formatted_date
            FROM stock_master 
            WHERE product_id = ? AND add_and_deduct IN ('deduct','Sale', 'credit note', 'bill of supply', 'debit note')
            ORDER BY date DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['add_and_deduct']) . "</td>
                   <td>" . htmlspecialchars($row['reference_no']) . "</td>
                    <td>" . htmlspecialchars($row['quantity']) . "</td>
                    <td>" . htmlspecialchars($row['remark']) . "</td>
                    <td>" . htmlspecialchars($row['formatted_date']) . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4' class='text-danger'>No stock-in records found</td></tr>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<tr><td colspan='4' class='text-danger'>Invalid request</td></tr>";
}
?>
