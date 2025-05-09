<?php
// Database health check script

// Try connecting to the database
$host = 'mariadb';
$dbname = 'adnd2e_db';
$user = 'pawneemayor';
$pass = 'password321';

echo "Testing database connection...<br>";

try {
    $start_time = microtime(true);
    $conn = new mysqli($host, $user, $pass, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn_time = microtime(true) - $start_time;
    echo "Connected successfully in " . number_format($conn_time * 1000, 2) . "ms<br>";
    
    // Test a simple query
    $query_start = microtime(true);
    $result = $conn->query("SELECT COUNT(*) FROM page");
    $query_time = microtime(true) - $query_start;
    
    if ($result) {
        $row = $result->fetch_row();
        echo "Query successful. Found " . $row[0] . " pages in " . number_format($query_time * 1000, 2) . "ms<br>";
    } else {
        echo "Query failed: " . $conn->error . "<br>";
    }
    
    // Get database statistics
    echo "<h3>Database Status</h3>";
    
    $result = $conn->query("SHOW GLOBAL STATUS LIKE 'Threads_connected'");
    $row = $result->fetch_row();
    echo "Current connections: " . $row[1] . "<br>";
    
    $result = $conn->query("SHOW GLOBAL STATUS LIKE 'Max_used_connections'");
    $row = $result->fetch_row();
    echo "Max used connections: " . $row[1] . "<br>";
    
    $result = $conn->query("SHOW GLOBAL STATUS LIKE 'Slow_queries'");
    $row = $result->fetch_row();
    echo "Slow queries: " . $row[1] . "<br>";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
