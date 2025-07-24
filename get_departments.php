<?php
// Include your database connection logic here
include("config.php");

// Check if the script should fetch data for the dropdown
if (isset($_GET['fetch_dropdown']) && $_GET['fetch_dropdown'] == 1) {
    fetchDepartmentsForDropdown();
} else {
    // Fetch data for the table
    $sql = "SELECT * FROM departments";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-department-id='" . $row['id'] . "' data-status='" . $row['status'] . "'>";
            echo "<td>" . $row['departmentName'] . "</td>";
            echo "<td class='active-cell'>" . $row['status'] . "</td>";
            echo "<td>" . $row['employeesCount'] . "</td>";
            // Add other columns as needed
            echo "<td>
                    <div class='dropdown'>
                        <button class='btn' type='button' id='actionDropdown' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>&#8230;</button>
                        <div class='dropdown-menu' aria-labelledby='actionDropdown'>
                            <a class='dropdown-item' href='#' onclick='updateDepartmentStatus(\"Active\", " . $row['id'] . ")'>Active</a>
                            <a class='dropdown-item' href='#' onclick='updateDepartmentStatus(\"Inactive\", " . $row['id'] . ")'>Inactive</a>
                            <a class='dropdown-item' href='#' data-toggle='modal' data-target='#editDepartmentModal'>Edit</a>
                        </div>
                    </div>
                </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No data found</td></tr>";
    }

    $conn->close();
}

// Function to fetch department names for the dropdown
function fetchDepartmentsForDropdown() {
    global $conn;

    $sql = "SELECT id, departmentName FROM departments";
    $result = $conn->query($sql);

    $departments = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    }

    $conn->close();

    // Return the department names as JSON
    header('Content-Type: application/json');
    echo json_encode($departments);
}
?>
