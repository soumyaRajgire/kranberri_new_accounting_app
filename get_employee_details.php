<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeId = isset($_POST["employeeId"]) ? $_POST["employeeId"] : "";

    if ($employeeId !== "") {
        $sql = "SELECT bankname FROM employees_data WHERE id = '$employeeId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo '<option value="' . $row['bankname'] . '">' . $row['bankname'] . '</option>';
        } else {
            echo '<option value="">No bank details found</option>';
        }
    }
}

$conn->close();
?>
