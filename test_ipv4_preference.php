<?php
echo "<h1>PHP IPv4 Preference Test</h1>";

// Check if the IPv4 preference setting is applied
echo "<h2>PHP Configuration Check</h2>";
echo "sys_default_family setting: " . ini_get('sys_default_family') . "<br>";
echo "mysqli.default_port: " . ini_get('mysqli.default_port') . "<br>";

// Test hostname resolution order
echo "<h2>Hostname Resolution Order</h2>";
$host = 'mariadb';
echo "Resolving $host...<br>";

// Get all IP addresses for the hostname
$ips = gethostbynamel($host);
if ($ips === false) {
    echo "Could not resolve hostname<br>";
} else {
    echo "IP addresses for $host:<br>";
    foreach ($ips as $ip) {
        echo "- $ip<br>";
    }
}

// Test mysqli connection with hostname
echo "<h2>mysqli Connection Test</h2>";
$dbname = 'adnd2e_db';
$user = 'pawneemayor';
$pass = 'password321';

$mysqli = @new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    echo "❌ Connection failed: " . $mysqli->connect_error . "<br>";
} else {
    echo "✅ Connection successful!<br>";
    
    // Check which IP we connected to
    $status = $mysqli->query("SHOW STATUS LIKE 'Ssl_cipher'");
    $row = $status->fetch_assoc();
    echo "Connected to server: " . $mysqli->host_info . "<br>";
    
    $mysqli->close();
}
?>
