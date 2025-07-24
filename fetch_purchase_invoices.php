<?php
include("config.php");

if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];
    $sql = "SELECT id, pinvoice_code FROM purchase_invoice WHERE customer_id = '$customer_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row["id"] . '">' . $row["pinvoice_code"] . '</option>';
        }
    } else {
        echo '<option value="">No Purchase Invoices Found</option>';
    }
}
?>
