<?php
// Test database connection using MediaWiki settings
$host = 'mariadb';
$dbname = 'adnd2e_db';
$user = 'pawneemayor';
$pass = 'password321';

echo "Testing connection to MediaWiki database...<br>";

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected successfully to $dbname database.<br>";
    
    // Test if we can query a MediaWiki table
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        echo "Successfully queried tables. Found " . $result->num_rows . " tables:<br>";
        while($row = $result->fetch_array()) {
            echo "- " . $row[0] . "<br>";
        }
    } else {
        echo "Failed to query tables: " . $conn->error;
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
