<?php
// fetch_pinvoice_code.php
// Replace this with your actual database connection code

$customer_id = $_POST['customer_id'];

// Perform a database query to fetch pinvoice_code based on customer_id
// Replace the following code with your actual query
$sql = "SELECT pinvoice_code FROM purchase_invoice WHERE customer_id = $customer_id LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  echo $row['pinvoice_code'];
} else {
  echo ""; // If no pinvoice_code found
}
?>
