<?php
// Path to your error log file
$logFile = 'error_log.txt';

// Read the log file
$logContent = file_get_contents($logFile);

// Convert the content into an array of log entries
$logEntries = explode("\n", $logContent);

// Prepare an array to store log data
$logs = [];

foreach ($logEntries as $entry) {
    // Skip empty lines
    if (empty($entry)) continue;

    // Match the log format: Date, Time, Error Message, File, Line Number
    if (preg_match('/\[(.*?)\] (.*?)( in .*? on line (\d+))?$/', $entry, $matches)) {
        // Extract components from the log
        $timestamp = $matches[1];  // e.g. [28-Feb-2025 06:54:00 UTC]
        $errorMessage = $matches[2]; // Error message
        $lineNumber = isset($matches[4]) ? $matches[4] : ''; // Line number (optional)
        
        // Convert timestamp to India Standard Time (IST)
        $dateTime = new DateTime($timestamp, new DateTimeZone('UTC'));
        $dateTime->setTimezone(new DateTimeZone('Asia/Kolkata')); // Set to IST
        $formattedTime = $dateTime->format('d-M-Y H:i:s'); // Format as per your requirement

        // Parse the file path
        $filePath = isset($matches[3]) ? $matches[3] : ''; // e.g. in /path/to/file.php
        
        // Store in logs array
        $logs[] = [
            'time' => $formattedTime,
            'error' => $errorMessage,
            'file' => $filePath,
            'line' => $lineNumber,
        ];
    }
}

// Reverse the array to display the latest error first
$logs = array_reverse($logs);

// Display the log data in a table
echo '<table border="1" cellpadding="10">';
echo '<thead>';
echo '<tr><th>S.No</th><th>Time (IST)</th><th>Error Message</th><th>Page Name</th><th>Line Number</th></tr>';
echo '</thead>';
echo '<tbody>';

$sno = 1; // Serial number
foreach ($logs as $log) {
    echo '<tr>';
    echo '<td>' . $sno++ . '</td>';
    echo '<td>' . $log['time'] . '</td>';
    echo '<td>' . $log['error'] . '</td>';
    echo '<td>' . $log['file'] . '</td>';
    echo '<td>' . $log['line'] . '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
?>
