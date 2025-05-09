<?php
// Test multiple connection methods to identify issues

echo "<h1>MediaWiki Database Connection Diagnostics</h1>";

// Test standard connection
$host = 'mariadb';
$dbname = 'adnd2e_db';
$user = 'pawneemayor';
$pass = 'password321';

echo "<h2>Test 1: Standard Connection</h2>";
try {
    $start = microtime(true);
    $conn = new mysqli($host, $user, $pass, $dbname);
    $duration = microtime(true) - $start;
    
    if ($conn->connect_error) {
        echo "❌ Connection failed: " . $conn->connect_error . "<br>";
    } else {
        echo "✅ Connection successful (took " . number_format($duration * 1000, 2) . "ms)<br>";
        $conn->close();
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Test with different connection method (PDO)
echo "<h2>Test 2: PDO Connection</h2>";
try {
    $start = microtime(true);
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $duration = microtime(true) - $start;
    
    echo "✅ PDO Connection successful (took " . number_format($duration * 1000, 2) . "ms)<br>";
    $pdo = null;
} catch (PDOException $e) {
    echo "❌ PDO Error: " . $e->getMessage() . "<br>";
}

// Test connection with timeout options
echo "<h2>Test 3: Connection with Extended Timeout</h2>";
try {
    $start = microtime(true);
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
    $duration = microtime(true) - $start;
    
    if ($conn->connect_error) {
        echo "❌ Connection failed: " . $conn->connect_error . "<br>";
    } else {
        echo "✅ Connection with timeout options successful (took " . number_format($duration * 1000, 2) . "ms)<br>";
        $conn->close();
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Network diagnostic
echo "<h2>Test 4: Network Connectivity</h2>";
$output = null;
$retval = null;
exec("ping -c 1 $host", $output, $retval);
echo "Ping result code: $retval<br>";
if ($retval === 0) {
    echo "✅ Network connectivity to $host successful<br>";
} else {
    echo "❌ Cannot ping $host<br>";
}
echo "<pre>" . implode("\n", $output) . "</pre>";

// Database exists check
echo "<h2>Test 5: Database Existence</h2>";
try {
    $conn = new mysqli($host, $user, $pass);
    if ($conn->connect_error) {
        echo "❌ Connection failed: " . $conn->connect_error . "<br>";
    } else {
        $result = $conn->query("SHOW DATABASES LIKE '$dbname'");
        if ($result && $result->num_rows > 0) {
            echo "✅ Database '$dbname' exists<br>";
        } else {
            echo "❌ Database '$dbname' does not exist<br>";
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
