<?php
// Include your database connection logic here
include("config.php");

// Fetch data from the database
$sql = "SELECT * FROM shifts";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $shiftStartTime = date("H:i:s", strtotime($row['startTime'])); // Format start time
        $shiftEndTime = date("H:i:s", strtotime($row['endTime']));     // Format end time

        // Concatenate start and end time to create shiftTime
        $shiftTime = $shiftStartTime . ' - ' . $shiftEndTime;

        echo "<tr data-shift-id='" . $row['id'] . "' data-status='" . $row['status'] . "'>";
        echo "<td>" . $row['shiftType'] . "</td>";
        echo "<td>" . $shiftTime . "</td>"; // Display combined shift time
        echo "<td class='active-cell'>" . $row['status'] . "</td>";
        echo "<td>
                <div class='dropdown'>
                    <button class='btn' type='button' id='actionDropdown' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>&#8230;</button>
                    <div class='dropdown-menu' aria-labelledby='actionDropdown'>
                        <a class='dropdown-item' href='#' onclick='updateShiftStatus(\"Active\", " . $row['id'] . ")'>Active</a>
                        <a class='dropdown-item' href='#' onclick='updateShiftStatus(\"Inactive\", " . $row['id'] . ")'>Inactive</a>
                        <a class='dropdown-item' href='#' data-toggle='modal' data-target='#editShiftModal'>Edit</a>
                    </div>
                </div>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No data found</td></tr>";
}

$conn->close();
?>
