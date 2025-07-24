<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $employeeId = mysqli_real_escape_string($conn, $_POST['id']);

    $sql = "SELECT * FROM employees_data WHERE id = '$employeeId'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Output employee details
        echo "<p><strong>Name:</strong> " . $row['salutation'] . " " . $row['name'] . "</p>";
        echo "<p><strong>Employee ID:</strong> " . $row['employee_id'] . "</p>";
        echo "<p><strong>Department:</strong> " . $row['department'] . "</p>";
        echo "<p><strong>Designation:</strong> " . $row['designation'] . "</p>";
        echo "<p><strong>Official Email:</strong> " . $row['officemail'] . "</p>";
        echo "<p><strong>Personal Mobile:</strong> " . $row['personalmobile'] . "</p>";
        echo "<p><strong>Date of Joining:</strong> " . $row['doj'] . "</p>";
        echo "<p><strong>Branch:</strong> " . $row['branch'] . "</p>";
        echo "<p><strong>Account Name:</strong> " . $row['accountname'] . "</p>";
        echo "<p><strong>Account Number:</strong> " . $row['accountnumber'] . "</p>";
        echo "<p><strong>IFSC:</strong> " . $row['ifsc'] . "</p>";
        echo "<p><strong>Account Type:</strong> " . $row['accounttype'] . "</p>";
        echo "<p><strong>Bank Name:</strong> " . $row['bankname'] . "</p>";
        echo "<p><strong>Bank Branch:</strong> " . $row['bankbranch'] . "</p>";
        echo "<p><strong>Aadhar:</strong> " . $row['aadhar'] . "</p>";
        echo "<p><strong>PAN:</strong> " . $row['pan'] . "</p>";
        echo "<p><strong>UAN:</strong> " . $row['uan'] . "</p>";
        echo "<p><strong>ESI:</strong> " . $row['esi'] . "</p>";
        if (!empty($row['image_path'])) {
            echo "<p><strong>Image:</strong><br><img src='" . $row['image_path'] . "' alt='Employee Image' style='max-width: 200px;'></p>";
        } else {
            echo "<p><strong>Image:</strong> No image available.</p>";
        }
    } else {
        echo "No employee found with the given ID.";
    }
} else {
    echo "Invalid request.";
}
?>
