<?php
$host = 'mariadb';
$user = 'pawneemayor';
$pass = 'password321';

echo "Checking MariaDB configuration...<br>";
try {
    $conn = new mysqli($host, $user, $pass);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Successfully connected to MariaDB<br>";
    
    // Check key configuration values
    $configs = [
        'innodb_buffer_pool_size',
        'max_connections',
        'query_cache_type',
        'query_cache_size',
        'thread_cache_size'
    ];
    
    foreach ($configs as $config) {
        $result = $conn->query("SHOW VARIABLES LIKE '$config'");
        $row = $result->fetch_assoc();
        echo "$config: " . $row['Value'] . "<br>";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
