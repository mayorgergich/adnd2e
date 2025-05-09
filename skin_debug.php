<?php
echo "<h1>BIOSTerminal Skin Debug</h1>";

// Check skin directories
$skinDir = "/var/www/html/skins/BIOSTerminal";
echo "<h2>Skin Directory Check:</h2>";
if (is_dir($skinDir)) {
    echo "✅ BIOSTerminal skin directory exists<br>";
} else {
    echo "❌ BIOSTerminal skin directory is missing!<br>";
    exit;
}

// Check key files
$requiredFiles = [
    "$skinDir/skin.json",
    "$skinDir/includes/SkinBIOSTerminal.php",
    "$skinDir/includes/BIOSTerminalTemplate.php",
    "$skinDir/resources/css/BIOSTerminal.css",
    "$skinDir/resources/js/biosterminal.js",
    "$skinDir/resources/js/emergency-fix.js"
];

echo "<h2>Required Files:</h2>";
echo "<ul>";
foreach ($requiredFiles as $file) {
    echo "<li>";
    if (file_exists($file)) {
        echo "✅ " . basename($file) . " exists";
        
        // For PHP files, check for syntax errors
        if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
            $output = [];
            $return_var = 0;
            exec("php -l " . escapeshellarg($file), $output, $return_var);
            if ($return_var === 0) {
                echo " - No syntax errors detected";
            } else {
                echo " - <strong style='color:red'>SYNTAX ERROR!</strong> " . implode(" ", $output);
            }
        }
    } else {
        echo "❌ " . basename($file) . " is missing!";
    }
    echo "</li>";
}
echo "</ul>";

// Check skin registration in LocalSettings.php
echo "<h2>Skin Registration Check:</h2>";
$localSettings = file_get_contents("/var/www/html/LocalSettings.php");
if (strpos($localSettings, "BIOSTerminal") !== false) {
    echo "✅ BIOSTerminal is mentioned in LocalSettings.php<br>";
    
    if (strpos($localSettings, "wfLoadSkin( 'BIOSTerminal' )") !== false) {
        echo "✅ BIOSTerminal is properly loaded with wfLoadSkin()<br>";
    } else {
        echo "❌ BIOSTerminal might not be properly loaded. Missing wfLoadSkin() call.<br>";
    }
    
    if (strpos($localSettings, "biosterminal") !== false && strpos($localSettings, "wgDefaultSkin") !== false) {
        echo "✅ BIOSTerminal appears to be set as default skin<br>";
    }
} else {
    echo "❌ BIOSTerminal is not mentioned in LocalSettings.php at all!<br>";
}

// Check skin.json for errors
echo "<h2>skin.json Check:</h2>";
if (file_exists("$skinDir/skin.json")) {
    $skinJson = file_get_contents("$skinDir/skin.json");
    $json = json_decode($skinJson);
    if ($json === null) {
        echo "❌ skin.json has JSON syntax errors: " . json_last_error_msg() . "<br>";
    } else {
        echo "✅ skin.json is valid JSON<br>";
        echo "Version: " . $json->version . "<br>";
        echo "Name: " . $json->name . "<br>";
        
        // Check for double emergency-fix.js entry
        if (strpos($skinJson, "emergency-fix.js") !== false) {
            $count = substr_count($skinJson, "emergency-fix.js");
            if ($count > 1) {
                echo "❌ emergency-fix.js is included $count times - may cause issues!<br>";
            } else {
                echo "✅ emergency-fix.js is included correctly<br>";
            }
        }
    }
}

// Suggest fix strategy
echo "<h2>Recommended Fix Strategy:</h2>";
echo "<ol>";
echo "<li>Create a backup of the entire skin folder</li>";
echo "<li>Fix any identified issues in the skin files</li>";
echo "<li>Modify LocalSettings.php to include a working skin</li>";
echo "</ol>";

// Create a fixed skin.json
echo "<h2>Generate Fixed skin.json:</h2>";
if (file_exists("$skinDir/skin.json")) {
    $skinJson = file_get_contents("$skinDir/skin.json");
    // Fix common issues
    $fixedJson = preg_replace('/("resources\/js\/emergency-fix\.js",)\s*"resources\/js\/emergency-fix\.js"/', '$1', $skinJson);
    
    echo "<pre>" . htmlspecialchars($fixedJson) . "</pre>";
    
    echo "<p>To save this fixed version, run:</p>";
    echo "<code>docker exec -it adnd2e cp /var/www/html/skins/BIOSTerminal/skin.json /var/www/html/skins/BIOSTerminal/skin.json.backup</code><br>";
    echo "<code>docker exec -it adnd2e bash -c 'cat > /var/www/html/skins/BIOSTerminal/skin.json << EOF\n" . 
        htmlspecialchars($fixedJson) . 
        "\nEOF'</code>";
}
