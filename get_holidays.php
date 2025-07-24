<?php
// Include your database connection logic here
include("config.php");

// Fetch data from the database
$sql = "SELECT * FROM holidays";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr data-holiday-id='" . $row['id'] . "' data-status='" . $row['status'] . "'>";
        echo "<td>" . $row['holidayName'] . "</td>";
        echo "<td class='active-cell'>" . $row['status'] . "</td>";
        echo "<td>" . $row['holidayDate'] . "</td>";
        echo "<td>
                <div class='dropdown'>
                    <button class='btn' type='button' id='actionDropdown' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>&#8230;</button>
                    <div class='dropdown-menu' aria-labelledby='actionDropdown'>
                        <a class='dropdown-item' href='#' onclick='updateHolidayStatus(\"Active\", " . $row['id'] . ")'>Active</a>
                        <a class='dropdown-item' href='#' onclick='updateHolidayStatus(\"Inactive\", " . $row['id'] . ")'>Inactive</a>
                        <a class='dropdown-item' href='#' data-toggle='modal' data-target='#editHolidayModal'>Edit</a>
                    </div>
                </div>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No data found</td></tr>";
}

$conn->close();
?>
