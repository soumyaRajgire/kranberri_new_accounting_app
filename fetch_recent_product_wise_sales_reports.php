<?php
session_start();
$directory = "generated_reports/";

if (!file_exists($directory)) {
    mkdir($directory, 0777, true);
}

// Ensure branch_id exists in session
if (!isset($_SESSION['branch_id'])) {
    die("Branch ID not set. Please log in again.");
}

$branch_id = $_SESSION['branch_id']; // Fetch branch ID from session

// Fetch only sales reports for the logged-in branch
$files = array_filter(scandir($directory, SCANDIR_SORT_DESCENDING), function ($file) use ($branch_id) {
    return strpos($file, "Product_Sales_Report_Branch_{$branch_id}_") !== false;
});

// Sort files by last modified time (newest first)
usort($files, function ($a, $b) use ($directory) {
    return filemtime($directory . $b) - filemtime($directory . $a);
});

if (empty($files)) {
    echo "<p class='text-center text-muted'>No recent sales reports found for your branch.</p>";
} else {
    echo "<div class='recent-reports-list'>";

    foreach ($files as $file) {
        if (is_file($directory . $file)) {
            // Determine file type icon
            $file_ext = pathinfo($file, PATHINFO_EXTENSION);
            $icon_class = ($file_ext == "pdf") ? "fa-file-pdf text-danger" : "fa-file-excel text-success";

            // Extract date range from the filename
            if (preg_match("/Product_Sales_Report_Branch_{$branch_id}_(\d{2}-\d{2}-\d{4})_to_(\d{2}-\d{2}-\d{4})/", $file, $matches)) {
                $date_range = "{$matches[1]} - {$matches[2]}";
            } else {
                $date_range = "Unknown Date";
            }

            echo "
            <div class='report-item d-flex align-items-center p-2 border rounded shadow-sm'>
                <i class='fas $icon_class fa-2x me-2'></i>
                <div class='report-details flex-grow-1'>
                    <p class='report-title mb-0'><strong>Product Sales Report</strong></p>
                    <p class='report-date text-muted mb-0'>$date_range</p>
                </div>
                <a href='{$directory}{$file}' class='btn btn-info btn-sm download-btn' title='Download' download>
                    <i class='fas fa-download'></i>
                </a>
            </div>
            ";
        }
    }

    echo "</div>";
}
?>
