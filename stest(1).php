
<?php
// Get all PHP files in the current directory that start with "delete"
// The pattern now matches filenames starting with 'delete' and then allows underscores or hyphens, and ends with .php
$files = glob('./delete*.php*'); // Adjusted to match files like delete_*.php or delete-*.php

foreach ($files as $file) {
    // Get the content of each file
    $content = file_get_contents($file);

    // Check if the file includes 'config.php' and does not already have session_start()
    if (strpos($content, "include('config.php');") !== false && strpos($content, 'session_start();') === false) {
        // Add session_start(); immediately after include('config.php');
        $modifiedContent = preg_replace(
            "/(include\('config.php'\);)/",
            "$1\nsession_start();",
            $content
        );

        // Save the modified content back to the file
        if (@file_put_contents($file, $modifiedContent) === false) {
            echo "Failed to update file: $file\n";
        } else {
            echo "Updated file: $file\n";
        }
    } else {
        if (strpos($content, 'session_start();') !== false) {
            echo "session_start() already present in file: $file\n";
        } else {
            echo "No config.php include in file: $file\n";
        }
    }
}
?>
