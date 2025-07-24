<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $voucherNumber = $voucherDate = $paymentMode = $customer_name = $amount = $notes = $collected_by = $bank_name = $trans_no = $cheque_no = $dd_no = $card_last_no = $transaction_date = "";

  $voucherNumber = mysqli_real_escape_string($conn, $_POST["voucherNumber"]);
  $voucherDate = mysqli_real_escape_string($conn, $_POST["voucherDate"]);
  $paymentMode = mysqli_real_escape_string($conn, $_POST["paymentMode"]);
  $customer_name = mysqli_real_escape_string($conn, $_POST["customer_name_choice"]);
 
  $amount = mysqli_real_escape_string($conn, $_POST["amount"]);
  $notes = mysqli_real_escape_string($conn, $_POST["notes"]);
  $collected_by = mysqli_real_escape_string($conn, $_POST["collected_by"]);
  $bank_name = mysqli_real_escape_string($conn, $_POST["bank_name"]);
  $trans_no = mysqli_real_escape_string($conn, $_POST["trans_no"]);
  $cheque_no = mysqli_real_escape_string($conn, $_POST["cheque_no"]);
  $dd_no = mysqli_real_escape_string($conn, $_POST["dd_no"]);
  $card_last_no = mysqli_real_escape_string($conn, $_POST["card_last_no"]);
  $transaction_date = mysqli_real_escape_string($conn, $_POST["transaction_date"]);
  $pinvoice_code = mysqli_real_escape_string($conn, $_POST["pinvoice_code"]);
  $customer_id = mysqli_real_escape_string($conn, $_POST["customer_id"]);
  
  $sql = "INSERT INTO vouchers (voucherNumber, voucherDate, paymentMode, customer_name, customer_id, amount, notes, collected_by, bank_name, trans_no, cheque_no, dd_no, card_last_no, transaction_date, pinvoice_code)
            VALUES ('$voucherNumber', '$voucherDate', '$paymentMode', '$customer_name', '$customer_id', '$amount', '$notes', '$collected_by', '$bank_name', '$trans_no', '$cheque_no', '$dd_no', '$card_last_no', '$transaction_date', '$pinvoice_code')";

  if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Data saved successfully!");</script>';
    echo '<script>window.location = "purchase_invoices.php";</script>';
  } else {
    echo '<script>alert("Error: ' . $conn->error . '");</script>';
  }

  $conn->close();
}
?>
