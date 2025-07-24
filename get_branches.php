<?php
// Include your database connection logic here
include("config.php");

// Check if branch data should be fetched for the dropdown
if (isset($_GET['fetch_dropdown']) && $_GET['fetch_dropdown'] == 1) {
    fetchBranchesForDropdown();
} else {
    // Fetch data from the database
    $sql = "SELECT * FROM branches";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-branch-id='" . $row['id'] . "' data-status='" . $row['status'] . "'>";
            echo "<td>" . $row['officeName'] . "</td>";
            echo "<td class='active-cell'>" . $row['status'] . "</td>";
            echo "<td>" . $row['employeesCount'] . "</td>";
            echo "<td>
                    <div class='dropdown'>
                        <button class='btn' type='button' id='actionDropdown' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>&#8230;</button>
                        <div class='dropdown-menu' aria-labelledby='actionDropdown'>
                            <a class='dropdown-item' href='#' onclick='updateStatus(\"Active\", " . $row['id'] . ")'>Active</a>
                            <a class='dropdown-item' href='#' onclick='updateStatus(\"Inactive\", " . $row['id'] . ")'>Inactive</a>
                            <a class='dropdown-item' href='#' data-toggle='modal' data-target='#addDepartmentModal'>Edit</a>
                        </div>
                    </div>
                </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No data found</td></tr>";
    }

    $conn->close();
}

// Function to fetch branch names for the dropdown
function fetchBranchesForDropdown() {
    global $conn;

    $sql = "SELECT id, officeName FROM branches";
    $result = $conn->query($sql);

    $branches = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $branches[] = $row;
        }
    }

    $conn->close();

    // Return the branch names as JSON
    header('Content-Type: application/json');
    echo json_encode($branches);
}
?>
