<?php
// Display PHP info and server environment
echo "<h1>PHP Information</h1>";
echo "<h2>PHP Version: " . phpversion() . "</h2>";

echo "<h2>Loaded Extensions:</h2>";
$extensions = get_loaded_extensions();
sort($extensions);
echo "<ul>";
foreach ($extensions as $extension) {
    echo "<li>$extension</li>";
}
echo "</ul>";

// Check for common MediaWiki requirements
echo "<h2>MediaWiki Requirements Check:</h2>";
$requirements = [
    'mysqli' => extension_loaded('mysqli'),
    'xml' => extension_loaded('xml'),
    'json' => extension_loaded('json'),
    'intl' => extension_loaded('intl'),
    'mbstring' => extension_loaded('mbstring'),
    'gd' => extension_loaded('gd'),
    'fileinfo' => extension_loaded('fileinfo')
];

echo "<ul>";
foreach ($requirements as $req => $status) {
    echo "<li>$req: " . ($status ? "✅ Available" : "❌ Missing") . "</li>";
}
echo "</ul>";

// Check file permissions
echo "<h2>File Permission Check:</h2>";
$files = [
    "/var/www/html/LocalSettings.php",
    "/var/www/html/index.php",
    "/var/www/html/cache",
    "/var/www/html/images"
];

echo "<ul>";
foreach ($files as $file) {
    echo "<li>$file: ";
    if (file_exists($file)) {
        $perms = fileperms($file);
        $owner = posix_getpwuid(fileowner($file));
        $group = posix_getgrgid(filegroup($file));
        echo "Exists - Owner: " . $owner['name'] . ", Group: " . $group['name'] . ", Permissions: " . decoct($perms & 0777);
    } else {
        echo "Does not exist";
    }
    echo "</li>";
}
echo "</ul>";

// Test error logging
echo "<h2>Error Logging Test:</h2>";
error_log("Test error message from debug_info.php");
echo "Attempted to write to error log.<br>";
echo "Error log path: " . ini_get('error_log');
?>
