<?php
include("config.php");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables with default values
    $voucherNumber = $paymentDate = $salaryMonth = $paymentMode = $employee = $amount = $notes = $tds = $pTax = $pf = $erPf = $esi = $erEsi = $welfare = $others = $netPay = $ctcPay = "";

    // Check if keys are set in the $_POST array
    $voucherNumber = isset($_POST["voucherNumber"]) ? $_POST["voucherNumber"] : "";
    $paymentDate = isset($_POST["payment_date"]) ? $_POST["payment_date"] : "";
    $salaryMonth = isset($_POST["salary_month"]) ? $_POST["salary_month"] : "";
    $paymentMode = isset($_POST["payment_mode"]) ? $_POST["payment_mode"] : "";
    $employee = isset($_POST["employee"]) ? $_POST["employee"] : "";
    $amount = isset($_POST["amount"]) ? $_POST["amount"] : "";
    $notes = isset($_POST["notes"]) ? $_POST["notes"] : "";
    $tds = isset($_POST["tds"]) ? $_POST["tds"] : "";
    $pTax = isset($_POST["p_tax"]) ? $_POST["p_tax"] : "";
    $pf = isset($_POST["pf"]) ? $_POST["pf"] : "";
    $erPf = isset($_POST["er_pf"]) ? $_POST["er_pf"] : "";
    $esi = isset($_POST["esi"]) ? $_POST["esi"] : "";
    $erEsi = isset($_POST["er_esi"]) ? $_POST["er_esi"] : "";
    $welfare = isset($_POST["welfare"]) ? $_POST["welfare"] : "";
    $others = isset($_POST["others"]) ? $_POST["others"] : "";
    $netPay = isset($_POST["netpay"]) ? $_POST["netpay"] : "";
    $ctcPay = isset($_POST["ctc_pay"]) ? $_POST["ctc_pay"] : "";

    // Insert data into the database
    $sql = "INSERT INTO salary_payments (voucherNumber, payment_date, salary_month, payment_mode, employee, amount, notes, tds, p_tax, pf, er_pf, esi, er_esi, welfare, others, net_pay, ctc)
            VALUES ('$voucherNumber', '$paymentDate', '$salaryMonth', '$paymentMode', '$employee', '$amount', '$notes', '$tds', '$pTax', '$pf', '$erPf', '$esi', '$erEsi', '$welfare', '$others', '$netPay', '$ctcPay')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Data saved successfully!");</script>';
        echo '<script>window.location = "purchase_invoices.php";</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
