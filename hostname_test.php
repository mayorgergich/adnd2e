<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>MariaDB Hostname Connection Test</h1>";

$host = 'mariadb';
$dbname = 'adnd2e_db';
$user = 'pawneemayor';
$pass = 'password321';

echo "<h2>Environment Information</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Host: " . $host . "<br>";
echo "Database: " . $dbname . "<br>";

echo "<h2>DNS Resolution Test</h2>";
echo "Trying to resolve '$host'...<br>";
$resolved_ip = gethostbyname($host);
echo "PHP gethostbyname('$host') resolves to: $resolved_ip<br>";
if ($resolved_ip == $host) {
    echo "❌ Warning: Hostname not resolved!<br>";
} else {
    echo "✅ Hostname resolved successfully<br>";
}

echo "<h2>Connection Tests</h2>";

// Test 1: mysqli with hostname
echo "<h3>Test 1: mysqli with hostname</h3>";
try {
    $start = microtime(true);
    $mysqli = new mysqli($host, $user, $pass, $dbname);
    $duration = microtime(true) - $start;
    
    if ($mysqli->connect_error) {
        echo "❌ Connection failed: " . $mysqli->connect_error . "<br>";
    } else {
        echo "✅ Connection successful (took " . number_format($duration * 1000, 2) . "ms)<br>";
        $result = $mysqli->query("SELECT COUNT(*) FROM page");
        $row = $result->fetch_row();
        echo "Pages: " . $row[0] . "<br>";
        $mysqli->close();
    }
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "<br>";
}

// Test 2: PDO with hostname
echo "<h3>Test 2: PDO with hostname</h3>";
try {
    $start = microtime(true);
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $duration = microtime(true) - $start;
    echo "✅ Connection successful (took " . number_format($duration * 1000, 2) . "ms)<br>";
    $result = $pdo->query("SELECT COUNT(*) FROM page");
    $count = $result->fetchColumn();
    echo "Pages: " . $count . "<br>";
    $pdo = null;
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "<br>";
}

// Test 3: Low-level socket connection
echo "<h3>Test 3: Direct socket connection</h3>";
$socket = @fsockopen($host, 3306, $errno, $errstr, 5);
if (!$socket) {
    echo "❌ Socket connection failed: $errstr ($errno)<br>";
} else {
    echo "✅ Socket connection successful<br>";
    fclose($socket);
}

// Test 4: Network commands
echo "<h3>Test 4: Network diagnostics</h3>";
echo "<pre>";
system("ping -c 2 $host 2>&1");
echo "\n";
system("hostname -I 2>&1");
echo "\n";
system("cat /etc/hosts 2>&1");
echo "</pre>";

// Test 5: Try different connection parameters
echo "<h3>Test 5: Connection with explicit parameters</h3>";
try {
    $mysqli = new mysqli();
    $mysqli->init();
    $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
    $mysqli->real_connect($host, $user, $pass, $dbname, 3306);
    
    if ($mysqli->connect_error) {
        echo "❌ Connection with parameters failed: " . $mysqli->connect_error . "<br>";
    } else {
        echo "✅ Connection with parameters successful<br>";
        $mysqli->close();
    }
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "<br>";
}
